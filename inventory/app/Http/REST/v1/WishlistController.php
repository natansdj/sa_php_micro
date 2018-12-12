<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\WishlistRepository as Wishlist;
use App\Transformers\WishlistTransformer;
use Illuminate\Http\Request;
use Gate;

/**
 * Wishlist resource representation.
 *
 * @Resource("Wishlist", uri="/wishlist")
 */
class WishlistController extends ApiBaseController
{
    /**
     * @var Wishlist
     */
    private $wishlist;

    /**
     * Initialize @var Wishlist
     *
     * @Request Wishlist
     *
     */
    public function __construct(Wishlist $wishlist)
    {
        parent::__construct();
        $this->wishlist = $wishlist;
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all wishlist.
     *
     * @Get("/wishlist/{user_id}")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Wishlist Name"})
     */
    public function index(Request $request)
    {
        $models = $this->wishlist->model->where([
            ['user_id', '=', $request->user_id],
        ])->get();

        if ($models) {
            $data = $this->api
                ->includes(['user', 'product', 'category', 'image'])
                ->serializer(new KeyArraySerializer('wishlist'))
                ->collection($models, new WishlistTransformer);
            return $this->response->addModelLinks(new $this->wishlist->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show specific wishlist
     *
     * Get a JSON representation of the wishlist.
     *
     * @Get("/wishlist/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"name":"Wishlist Name"})
     */
    public function show($id)
    {
        $model = $this->wishlist->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['user', 'product', 'category' ,'image'])
                ->serializer(new KeyArraySerializer('wishlist'))
                ->item($model, new WishlistTransformer);

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new wishlist
     *
     * Get a JSON representation of new wishlist.
     *
     * @Post("/wishlist")
     * @Versions({"v1"})
     * @Request(array -> {"name":"Wishlist Name"})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->wishlist->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->wishlist->create($request->all());
            if ($task) {
                return $this->response->success("Wishlist created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific wishlist
     *
     * Get a JSON representation of get wishlist.
     *
     * @Delete("/wishlist/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        /*if (Gate::denies('wishlist.delete', $request)) {
            return $this->response->errorInternal();
        }*/

        $task = $this->wishlist->delete($request->id);
        if ($task) {
            return $this->response->success("Wishlist deleted");
        }

        return $this->response->errorInternal();
    }

}
