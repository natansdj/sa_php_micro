<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;
use App\Repositories\ProductRepository as Product;
use App\Transformers\ProductTransformer;
use Gate;
use Illuminate\Http\Request;

/**
 * Product resource representation.
 *
 * @Resource("Product", uri="/product")
 */
class ProductController extends ApiBaseController
{
    /**
     * @var Product
     */
    private $product;

    /**
     * UserController constructor.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct();
        $this->product = $product;
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all product.
     *
     * @Get("/product")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Product Name","description":"Product Description","harga":100000,"stock":5})
     */
    public function index()
    {
        $models = $this->product->paginate();

        if ($models) {
            $data = $this->api
                ->includes(['image', 'category'])
                ->serializer(new KeyArraySerializer('product'))
                ->paginate($models, new ProductTransformer());

            return $this->response->addModelLinks(new $this->product->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show a specific product
     *
     * Get a JSON representation of get product.
     *
     * @Get("/product/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"name":"Product Name","description":"Product Description","harga":100000,"stock":5})
     */
    public function show($id)
    {
        $model = $this->product->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['image', 'category'])
                ->serializer(new KeyArraySerializer('product'))
                ->item($model, new ProductTransformer);

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new product
     *
     * Get a JSON representation of new product.
     *
     * @Post("/product")
     * @Versions({"v1"})
     * @Request(array -> {"id":1,"name":"Product Name","description":"Product Description","harga":100000,"stock":5})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->product->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->product->create($request->all());
            if ($task) {
                return $this->response->success("Product created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update product
     *
     * Get a JSON representation of update product.
     *
     * @Put("/product/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"id":1,"name":"Product Name","description":"Product Description","harga":100000,"stock":5}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $failed = false;
        if (Gate::denies('product.update', $request)) {
            $failed = true;
        }

        $validator = $this->product->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->product->update($request->all(), $request->id);
            if ($task) {
                return $this->response->success("Product updated");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific product
     *
     * Get a JSON representation of get product.
     *
     * @Delete("/product/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        if (Gate::denies('product.delete', $request)) {
            return $this->response->errorInternal();
        }

        $task = $this->product->delete($request->id);
        if ($task) {
            return $this->response->success("Product deleted");
        }

        return $this->response->errorInternal();
    }
}
