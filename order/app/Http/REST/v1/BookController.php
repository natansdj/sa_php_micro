<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\InvoiceRepository as Invoice;
use App\Transformers\InvoiceTransformer;

use App\Repositories\CartRepository as Cart;
use App\Transformers\CartTransformer;

use App\Repositories\UserRepository as User;
use App\Transformers\UserTransformer;

use Gate;
use Illuminate\Http\Request;

/**
 * Invoice resource representation.
 *
 * @Resource("Invoice", uri="/invoice")
 */
class BookController extends ApiBaseController
{
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var User
     */
    private $user;

    /**
     * UserController constructor.
     *
     * @param Invoice $invoice
     * @param Cart $cart
     * @param User $user
     */
    public function __construct(Invoice $invoice, Cart $cart, User $user)
    {
        parent::__construct();

        $this->invoice = $invoice;
        $this->cart = $cart;
        $this->user = $user;
    }

    public function showInvoice($id)
    {
        $model = $this->invoice->find($id);
        if ($model) {
            $data = $this->api->includes(['cart'])
                ->serializer(new KeyArraySerializer('invoice'))
                ->item($model, new InvoiceTransformer);

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    public function storeInvoice(Request $request)
    {
        $validator = $this->invoice->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->invoice->create($request->all());
            if ($task) {
                return (int) $task->id;
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    public function updateInvoice(Request $request, $id)
    {
        $request->id = $id;

        $failed = false;
        /*if (Gate::denies('invoice.update', $request)) {
            $failed = true;
        }*/

        $validator = $this->invoice->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->invoice->update($request->all(), $request->id);

            if ($task) {
                return (int) $request->id;
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    public function assignCartInvoice($invoice_id, $user_id)
    {
        $model = $this->cart->model->where([
            ['status', '=', 'incomplete'],
            ['user_id', '=', $user_id]
        ])->get();

        if ($numberOfCart = $model->count()) {
            $request_all = array(
                'invoice_id' => $invoice_id
            );
            foreach($model as $k => $v) {
                $cart_id = $v->id;

                $validator = $this->cart->validateRequest($request_all, "update");
                if ($validator->status() == "200") {
                    $task = $this->cart->update($request_all, $cart_id);

                    if ($task) {
                        if ($k == ($numberOfCart-1)) {
                            return $this->response->success("Invoice installed to cart");
                        }
                        continue;
                    }
                }

                return $validator;
            }
        }

        return $this->response->errorBadRequest();
    }

    public function setInvoiceLock(Request $request, $id)
    {
        $request->id = $id;

        $request->request->add([
            'status' => 'lock'
        ]);

        $failed = false;
        /*if (Gate::denies('invoice.update', $request)) {
            $failed = true;
        }*/

        $validator = $this->invoice->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->invoice->update($request->all(), $request->id);
            if ($task) {
                return (int) $request->id;
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    public function setCartPending(Request $request, $id)
    {
        $model = $this->invoice->find($id);

        $data = $this->api->includes(['cart'])
            ->serializer(new KeyArraySerializer('invoice'))
            ->item($model, new InvoiceTransformer);

        $request->request->add([
            'status' => 'pending'
        ]);

        if ($numberOfCart = count($data['invoice']['cart'])) {
            foreach($data['invoice']['cart'] as $k => $v) {
                $request->id = $v['id'];
                
                $failed = false;
                /*if (Gate::denies('cart.update', $request)) {
                    $failed = true;
                }*/

                $validator = $this->cart->validateRequest($request->all(), "update");
                if ( ! $failed && $validator->status() == "200") {
                    $task = $this->cart->update($request->all(), $request->id);

                    if ($task) {
                        if ($k == ($numberOfCart-1)) {
                            return (int) $numberOfCart;
                        }
                        continue;
                    }

                    $failed = true;
                }

                if ($failed) {
                    return $this->response->errorInternal();
                }

                return $validator;
            }
        }

        return $this->response->errorBadRequest();
    }

    public function getUser($id)
    {
        $model = $this->user->find($id);
        if ($model) {
            $data = $this->api
                ->serializer(new KeyArraySerializer('user'))
                ->item($model, new UserTransformer);

            return $data;
        }

        return array();
    }

    /**
     * Checkout
     *
     * Get a JSON representation of new/updated Invoice.
     *
     * @Post("/book/checkout/{user_id}")
     * @Versions({"v1"})
     * @Request(array -> {"total":1200000,"user_id":1,"address":"ship address","method":"pay method"})
     * @Response(200, success or error)
     */
    public function checkout(Request $request)
    {
        $models = $this->cart->model->where([
            ['status', '=', 'incomplete'],
            ['user_id', '=', $request->user_id],
        ])->get();

        $user = $this->getUser($request->user_id);

        if ($models && $user) {
            $data = $this->api
                ->includes('product')
                ->serializer(new KeyArraySerializer('cart'))
                ->collection($models, new CartTransformer);

            return $this->response->data(
                array_merge($data, $user),
                200
            );
        }

        return $this->response->errorNotFound();
    }

    /**
     * Confirm
     *
     * Get a JSON representation of updated Invoice.
     *
     * @Post("/book/confirm/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"address":"another ship address","method":"another pay method"})
     * @Response(200, success or error)
     */
    public function confirm(Request $request)
    {
        // update invoice
        $invoice_id = $this->updateInvoice($request, $request->id);

        if (is_int($invoice_id)) {
            // show invoice
            return $this->showInvoice($invoice_id);
        }

        return $this->response->errorInternal();
    }

    /**
     * Commit
     *
     * Set invoice status to lock, also cart status to pending
     *
     * @PUT("/book/confirm/{id}")
     * @Versions({"v1"})
     * @Response(200, success or error)
     */
    public function commit(Request $request)
    {
        // set invoice to lock
        $setLock = $this->setInvoiceLock($request, $request->id);
        
        // set cart to pending
        $setPending = $this->setCartPending($request, $request->id);

        if ($setLock && $setPending) {
            return $this->response->success();
        }

        return $this->response->errorInternal();
    }

}
