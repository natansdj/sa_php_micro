<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
	protected $availableIncludes = [];

	protected $defaultIncludes = [];

	/**
	 * @Request User
	 * @Response array
	 */
	public function transform(User $user)
	{
		return [
			'id'       => $user->id,
			'email'    => $user->email,
			'name'     => $user->name,
			'username' => $user->username,
		];
	}
}