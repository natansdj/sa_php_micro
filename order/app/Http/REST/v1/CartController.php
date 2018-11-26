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
     * @Response(200, body={"id":1,"name":"Cart Name"})
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
     * @Response(200, body={"id":1,"name":"Cart Name"})
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

}
