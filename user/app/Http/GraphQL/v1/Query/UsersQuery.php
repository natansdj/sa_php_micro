<?php

namespace App\Http\GraphQL\v1\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use App\Repositories\UserRepository as User;

class UsersQuery extends Query
{

    public $model;
    protected $attributes = [
        'name' => 'users',
        'uri'  => 'query=query{users{id,name,email,timestamp{}}}}'
    ];

    public function __construct(User $model, $attributes = [])
    {
        parent::__construct($attributes);
        $this->model = $model;
    }

    public function type()
    {
        return Type::listOf(GraphQL::type('User'));
    }

    public function args()
    {
        return [
            'id'    => ['name' => 'id', 'type' => Type::int()],
            'email' => ['name' => 'email', 'type' => Type::string()],
            'name'  => ['name' => 'name', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return array($this->model->find($args['id']));
        } else if (isset($args['email'])) {
            return array($this->model->findBy('email', $args['email']));
        } else {
            return $this->model->all();
        }
    }

}