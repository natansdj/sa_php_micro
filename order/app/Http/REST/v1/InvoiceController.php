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
     * @Get("/invoice")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"total":1500000,"user_id":1,"address":"ship address","status":"status name","method":"method name"})
     */
    public function index()
    {
        $models = $this->invoice->paginate();

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
    public function show($id)
    {
        $model = $this->invoice->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['cart'])
                ->serializer(new KeyArraySerializer('invoice'))
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
     * @Post("/invoice")
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
                return $this->response->success("Invoice created");
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
     * @Put("/invoice/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"total":1200000,"user_id":1,"address":"ship address","status":"status name","method":"method name"}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $failed = false;
        if (Gate::denies('invoice.update', $request)) {
            $failed = true;
        }

        $validator = $this->invoice->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->invoice->update($request->all(), $request->id);
            if ($task) {
                return $this->response->success("Invoice updated");
            }

            $failed = true;
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
}
