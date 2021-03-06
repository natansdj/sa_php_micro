<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\CartRepository as Cart;
use App\Transformers\CartTransformer;
use Illuminate\Http\Request;
use Gate;

/**
 * Cart resource representation.
 *
 * @Resource("Cart", uri="/cart")
 */
class CartController extends ApiBaseController
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * Initialize @var Cart
     *
     * @Request Cart
     *
     */
    public function __construct(Cart $cart)
    {
        parent::__construct();
        $this->cart = $cart;
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all cart.
     *
     * @Get("/cart")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"total":1500000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1})
     */
    public function index()
    {
        $models = $this->cart->all();
        if ($models) {
            $data     = $this->api
                ->serializer(new KeyArraySerializer('cart'))
                ->collection($models, new CartTransformer);
            return $this->response->addModelLinks(new $this->cart->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show specific cart
     *
     * Get a JSON representation of the cart.
     *
     * @Get("/cart/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"total":1500000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1})
     */
    public function show($id)
    {
        $model = $this->cart->find($id);
        if ($model) {
            $data = $this->api
                ->serializer(new KeyArraySerializer('cart'))
                ->item($model, new CartTransformer);

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new cart
     *
     * Get a JSON representation of new cart.
     *
     * @Post("/cart")
     * @Versions({"v1"})
     * @Request(array -> {"total":1200000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->cart->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->cart->create($request->all());
            if ($task) {
                return $this->response->success("Cart created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update cart
     *
     * Get a JSON representation of update cart.
     *
     * @Put("/cart/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"total":1200000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $failed = false;
        if (Gate::denies('cart.update', $request)) {
            $failed = true;
        }

        $validator = $this->cart->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->cart->update($request->all(), $request->id);
            if ($task) {
                return $this->response->success("Cart updated");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific cart
     *
     * Get a JSON representation of get cart.
     *
     * @Delete("/cart/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        if (Gate::denies('cart.delete', $request)) {
            return $this->response->errorInternal();
        }

        $task = $this->cart->delete($request->id);
        if ($task) {
            return $this->response->success("Cart deleted");
        }

        return $this->response->errorInternal();
    }

}
