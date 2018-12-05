<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\InvoiceRepository as Invoice;
use App\Transformers\InvoiceTransformer;
use Gate;
use Illuminate\Http\Request;

/**
 * Invoice resource representation.
 *
 * @Resource("Invoice", uri="/invoice")
 */
class InvoiceController extends ApiBaseController
{
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * UserController constructor.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        parent::__construct();
        $this->invoice = $invoice;
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all invoice.
     *
     * @Get("/invoice/history/{user_id}")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"total":1500000,"user_id":1,"address":"ship address","status":"status name","method":"method name"})
     */
    public function index(Request $request)
    {
        $models = $this->invoice->model->where([
            ['status', '!=', 'open'],
            ['user_id', '=', $request->user_id],
        ])->paginate();

        if ($models) {
            $data = $this->api
                ->includes(['cart'])
                ->serializer(new KeyArraySerializer('invoice'))
                ->paginate($models, new InvoiceTransformer());

            return $this->response->addModelLinks(new $this->invoice->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show a specific invoice
     *
     * Get a JSON representation of get invoice.
     *
     * @Get("/invoice/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"total":1500000,"user_id":1,"address":"ship address","status":"status name","method":"method name"})
     */
    public function show($id, $include_cart = true)
    {
        $model = $this->invoice->find($id);
        if ($model) {
            $data = $this->api;
            if ($include_cart) {
                $data = $data->includes(['cart']);
            }
            $data = $data->serializer(new KeyArraySerializer('invoice'))
                         ->item($model, new InvoiceTransformer);

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new invoice
     *
     * Get a JSON representation of new invoice.
     *
     * @Post("/invoice/store")
     * @Versions({"v1"})
     * @Request(array -> {"total":1200000,"user_id":1,"address":"ship address","status":"status name","method":"method name"})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->invoice->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->invoice->create($request->all());
            if ($task) {
                return $this->show($task->id, false);
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update invoice
     *
     * Get a JSON representation of update invoice.
     *
     * @Put("/invoice/update/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"total":1200000,"user_id":1,"address":"ship address","status":"status name","method":"method name"}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request, $id = null)
    {
        $request->id = ($request->id) ? $request->id : $id;

        $failed = false;
        /*if (Gate::denies('invoice.update', $request)) {
            $failed = true;
        }*/

        $validator = $this->invoice->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $this->invoice->update($request->all(), $request->id);
            return $this->show($request->id, false);
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific invoice
     *
     * Get a JSON representation of get invoice.
     *
     * @Delete("/invoice/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        if (Gate::denies('invoice.delete', $request)) {
            return $this->response->errorInternal();
        }

        $task = $this->invoice->delete($request->id);
        if ($task) {
            return $this->response->success("Invoice deleted");
        }

        return $this->response->errorInternal();
    }

    /**
     * Set invoice status to lock
     *
     * Get a JSON representation of update invoice.
     *
     * @Put("/invoice/setlock/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"status":"lock"}, id)
     * @Response(200, success or error)
     */
    public function setLock(Request $request)
    {
        $request->request->add(['status' => 'lock']);

        $failed = false;
        /*if (Gate::denies('invoice.update', $request)) {
            $failed = true;
        }*/

        $validator = $this->invoice->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->invoice->update($request->all(), $request->id);
            if ($task) {
                return $this->response->success("Invoice status set to lock");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Create or update a new invoice
     *
     * Get a JSON representation of new invoice.
     *
     * @Post("/invoice/checkout")
     * @Versions({"v1"})
     * @Request(array -> {"total":1200000,"user_id":1,"address":"ship address","method":"transfer"})
     * @Response(200, success or error)
     */
    public function checkout(Request $request)
    {
        $model = $this->invoice->model->where([
            ['status', '=', 'open'],
            ['user_id', '=', $request->user_id],
        ])->first();

        if ($model) {
            return $this->update($request, $model->id);
        } else {
            return $this->store($request);
        }
    }

}
