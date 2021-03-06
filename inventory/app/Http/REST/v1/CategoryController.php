<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\CategoryRepository as Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;
use Gate;

/**
 * Category resource representation.
 *
 * @Resource("Category", uri="/category")
 */
class CategoryController extends ApiBaseController
{
    /**
     * @var Category
     */
    private $category;

    /**
     * Initialize @var Category
     *
     * @Request Category
     *
     */
    public function __construct(Category $category)
    {
        parent::__construct();
        $this->category = $category;
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all category.
     *
     * @Get("/category")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Category Name"})
     */
    public function index()
    {
        $models = $this->category->all();
        if ($models) {
            $data     = $this->api
                ->includes('product')
                ->serializer(new KeyArraySerializer('category'))
                ->collection($models, new CategoryTransformer);
            return $this->response->addModelLinks(new $this->category->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show specific category
     *
     * Get a JSON representation of the category.
     *
     * @Get("/category/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"name":"Category Name"})
     */
    public function show($id)
    {
        $model = $this->category->find($id);
        if ($model) {
            $data = $this->api
                ->includes('product')
                ->serializer(new KeyArraySerializer('category'))
                ->item($model, new CategoryTransformer);

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

}
