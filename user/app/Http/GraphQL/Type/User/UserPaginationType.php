<?php

namespace App\Http\GraphQL\Type\User;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use App\Http\GraphQL\Type\MyTypeRegistry;

class UserPaginationType extends GraphQLType
{

	protected $attributes = [
		'name'        => 'UserPagination',
		'description' => 'User with paginate',
	];

	public function fields()
	{
		return [
			'user' => [
				'type'    => Type::listOf(GraphQL::type('User')),
				'resolve' => function ($root) {
					return $root;
				}
			],
			'meta' => MyTypeRegistry::paginationMeta()
		];
	}
}