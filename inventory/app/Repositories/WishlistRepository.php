<?php

namespace App\Repositories;

class WishlistRepository extends BaseRepository
{
    /**
     * @var \App\Models\Wishlist|\Illuminate\Database\Eloquent\Model
     */
    public $model;

    protected static $rules = [
        'user_id'     => 'required|exists:users,id',
        'product_id'  => 'required|exists:product,id',
    ];

    protected static $rules_update = [
        'user_id'     => 'exists:users,id',
        'product_id'  => 'exists:product,id',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'App\Models\Wishlist';
    }

}