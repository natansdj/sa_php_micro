<?php

namespace App\Repositories;

class ProductRepository extends BaseRepository
{

    /**
     * @var \App\Models\Product|\Illuminate\Database\Eloquent\Model
     */
    public $model;
    
    protected static $rules = [
        'user_id'     => 'required|exists:users,id',
        'name'        => 'required|min:5|max:255',
        'description' => 'required|min:5',
        'harga'       => 'required|numeric',
        'stock'       => 'required|numeric',
    ];

    protected static $rules_update = [
        'user_id'     => 'exists:users,id',
        'name'        => 'min:5|max:255',
        'description' => 'min:5'
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'App\Models\Product';
    }

}