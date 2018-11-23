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
			$users = $this->product->paginate();

			if ($users) {
				$data = $this->api
					->includes(['image', 'category'])
					->serializer(new KeyArraySerializer('product'))
					->paginate($users, new ProductTransformer());

				$response = $this->response->addModelLinks(new $this->product->model())->data($data, 200);
				return $response;
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
			$product = $this->product->find($id);
			if ($product) {
				$data = $this->api
					->includes(['image', 'category'])
					->serializer(new KeyArraySerializer('product'))
					->item($product, new ProductTransformer);

				$response = $this->response->data($data, 200);
				return $response;
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
			if (Gate::denies('product.update', $request))
				return $this->response->errorInternal();

			$validator = $this->product->validateRequest($request->all(), "update");
			if ($validator->status() == "200") {
				$task = $this->product->updateUser($request->all(), $request->id);
				if ($task)
					return $this->response->success("Product updated");
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
			if (Gate::denies('product.delete', $request))
				return $this->response->errorInternal();

			$task = $this->product->delete($request->id);
			if ($task)
				return $this->response->success("Product deleted");

			return $this->response->errorInternal();
		}
	}
