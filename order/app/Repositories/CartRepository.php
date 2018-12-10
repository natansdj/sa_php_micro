<?php
/**
 * Created by PhpStorm.
 * User: fabrizio
 * Date: 26/07/18
 * Time: 19.19
 */

namespace App\Repositories;

use Core\Repository\Eloquent\RepositoryAbstract;
use Illuminate\Support\Facades\Validator;

class CartRepository extends RepositoryAbstract
{
    private static $rules = [
        'price' => 'required|numeric',
        'status' => 'min:3|max:255',
        'product_id' => 'required|exists:product,id',
        'user_id' => 'exists:users,id',
        'qty' => 'required|numeric',
        'invoice_id' => 'exists:invoice,id'
    ];

    private static $rules_update = [
        'price' => 'numeric',
        'status' => 'min:3|max:255',
        'product_id' => 'exists:product,id',
        'user_id' => 'exists:users,id',
        'qty' => 'numeric',
        'invoice_id' => 'exists:invoice,id'
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\Cart';
    }

    /** Validate request api
     *
     * @param array $request
     * @param $type
     * @param array $rules_specific
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateRequest(array $request, $type = "", array $rules_specific = [])
    {
        $rules = $this->rules($type, $rules_specific);

        if (!isset($request)) {
            return $this->response->errorNotFound();
        }

        $validator = Validator::make($request, $rules);
        if ($validator->fails()) {
            return $this->response->addData($validator->errors()->toArray())->errorNotFound();
        }

        return $this->response->success("Rules validate success");
    }

    /** Use rules based on request
     *
     * @param $type
     * @param array $rules_specific
     * @return array
     */
    private function rules($type, array $rules_specific = [])
    {
        if(!empty($rules_specific)){
            return $rules_specific;
        }

        switch ($type) {
            case "store":
            case "create":
                $rules = self::$rules;
                break;
            case "update":
                $rules = self::$rules_update;
                break;
            default:
                $rules = self::$rules;
                break;
        }
        return $rules;
    }
}