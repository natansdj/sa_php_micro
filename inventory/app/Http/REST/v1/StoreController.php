<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\StoreRepository as Store;
use App\Transformers\StoreTransformer;

use App\Repositories\ProductRepository as Product;
use App\Transformers\ProductTransformer;

use Illuminate\Http\Request;
use Gate;

/**
 * Store resource representation.
 *
 * @Resource("Store", uri="/store")
 */
class StoreController extends ApiBaseController
{
    /**
     * @var Store
     */
    private $store;

    /**
     * Initialize @var Store
     *
     * @Request Store
     *
     */
    public function __construct(Store $store, Product $product)
    {
        parent::__construct();
        $this->store = $store;
        $this->product = $product;
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all store.
     *
     * @Get("/store")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Store Name"})
     */
    public function index()
    {
        $models = $this->store->all();
        if ($models) {
            $data = $this->api
                ->serializer(new KeyArraySerializer('store'))
                ->collection($models, new StoreTransformer);
            return $this->response->addModelLinks(new $this->store->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show specific store
     *
     * Get a JSON representation of the store.
     *
     * @Get("/store/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"name":"Store Name"})
     */
    public function show($id)
    {
        $model = $this->store->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['user', 'product'])
                ->serializer(new KeyArraySerializer('store'))
                ->item($model, new StoreTransformer);

            foreach ($data['store']['product'] as $k => $v) {
                $productModel = $this->product->find($v['id']);
                if ($productModel) {
                    $productData = $this->api
                        ->includes(['image', 'category'])
                        ->serializer(new KeyArraySerializer('product'))
                        ->item($productModel, new ProductTransformer);

                    $data['store']['product'][$k] = $productData['product'];
                }
            }

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new store
     *
     * Get a JSON representation of new store.
     *
     * @Post("/store")
     * @Versions({"v1"})
     * @Request(array -> {"name":"Store Name"})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->store->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->store->create($request->all());
            if ($task) {
                return $this->response->success("Store created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update store
     *
     * Get a JSON representation of update store.
     *
     * @Put("/store/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"name":"Store Name"}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $request->request->remove('name', 'user_id');

        $failed = false;
        /*if (Gate::denies('store.update', $request)) {
            $failed = true;
        }*/

        $validator = $this->store->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->store->update($request->all(), $request->id);
            if ($task) {
                return $this->response->success("Store updated");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific store
     *
     * Get a JSON representation of get store.
     *
     * @Delete("/store/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        /*if (Gate::denies('store.delete', $request)) {
            return $this->response->errorInternal();
        }*/

        $task = $this->store->delete($request->id);
        if ($task) {
            return $this->response->success("Store deleted");
        }

        return $this->response->errorInternal();
    }

}
