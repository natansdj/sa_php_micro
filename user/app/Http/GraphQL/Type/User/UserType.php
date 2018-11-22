<?php

namespace App\Http\GraphQL\Type\User;

use Carbon\Carbon;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use App\Http\GraphQL\Type\MyTypeRegistry;

class UserType extends GraphQLType
{

	protected $attributes = [
		'name'        => 'User',
		'description' => 'A user'
	];

	public function fields()
	{
		return [
			'id'        => [
				'type' => Type::nonNull(Type::string()),
			],
			'email'     => [
				'type' => Type::string(),
			],
			'name'      => [
				'type' => Type::string(),
			],
			'timestamp' => MyTypeRegistry::timestamp()
		];
	}
}