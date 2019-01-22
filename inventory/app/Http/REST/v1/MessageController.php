<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use Bschmitt\Amqp\Facades\Amqp;

use Illuminate\Http\Request;
use Gate;

/**
 * Message resource representation.
 *
 * @Resource("Message", uri="/message")
 */
class MessageController extends ApiBaseController
{

    /**
     * Initialize @var Message
     *
     * @Request Message
     *
     */
    public function __construct()
    {
        parent::__construct();
        
        //$this->middleware('jwt.verify', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all message.
     *
     * @Get("/message")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Message Name"})
     */
    public function rabbitPush(Request $request)
    {
        Amqp::publish($request->routing_key, $request->msg);
        return $this->response->success();
    }

    public function kafka(Request $request)
    {
        
    }

}
