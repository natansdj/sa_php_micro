<?php

namespace App\Repositories;

use Core\Repository\Eloquent\RepositoryAbstract;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRepository extends RepositoryAbstract
{
    const CONST_REQ_STR_MIN_MAX = 'required|min:5|max:255';

    private static $rules = [
        'email'    => 'required|email|unique:users,email|max:255',
        'password' => 'required|min:6|max:20',
        'username' => self::CONST_REQ_STR_MIN_MAX,
        'name'     => self::CONST_REQ_STR_MIN_MAX
    ];

    private static $rules_update = [
        'email'    => 'email|max:255',
        'username' => 'min:5|max:255',
        'name'     => self::CONST_REQ_STR_MIN_MAX
    ];

    private static $rules_password = [
        'password'         => 'required|min:6|max:20',
        'confirm_password' => 'required|same:password'
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\User';
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     *
     * @return mixed
     */
    public function updateUser(array $data, $id, $attribute = "id")
    {
        if (array_has($data, 'password')) {
            data_set($data, 'password', Hash::make($data["password"]));
        }

        return $this->update($data, $id, $attribute);
    }

    /** Validate request api
     *
     * @param array $request
     * @param $type
     * @param array $rules_specific
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateRequest(array $request, $type, array $rules_specific = [])
    {
        $modelRules = $this->rules($type, $rules_specific);

        if ( ! isset($request)) {
            return $this->response->errorNotFound();
        }

        $validator = Validator::make($request, $modelRules);
        if ($validator->fails()) {
            return $this->response->addData($validator->errors()->toArray())->errorNotFound();
        }

        return $this->response->success("Rules validate success");
    }

    /** Use rules based on request
     *
     * @param $type
     * @param array $rules_specific
     *
     * @return array
     */
    private function rules($type, array $rules_specific = [])
    {
        if ( ! empty($rules_specific)) {
            return $rules_specific;
        }

        switch ($type) {
            case "store":
            case "create":
                $modelRules = self::$rules;
                break;
            case "update":
                $modelRules = self::$rules_update;
                break;
            case "password":
                $modelRules = self::$rules_password;
                break;
            default:
                $modelRules = self::$rules;
                break;
        }

        return $modelRules;
    }
}