<?php

namespace App\Http\GraphQL\v1\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\Models\User;

class UpdateUserNameMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateUserName'
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'id'   => ['name' => 'id', 'type' => Type::string()],
            'name' => ['name' => 'name', 'type' => Type::string()]
        ];
    }

    public function rules()
    {
        return [
            'id'   => ['required'],
            'name' => ['required']
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::find($args['id']);
        if ( ! $user) {
            return null;
        }

        $user->name = $args['name'];
        $user->save();

        return $user;
    }
}