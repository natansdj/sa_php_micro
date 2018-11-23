<?php

namespace App\Repositories;

class ProductImageRepository extends BaseRepository
{
    protected static $rules = [
        'product_id' => 'required|exists:product,id',
        CONST_IMAGE  => 'required|image',
    ];

    protected static $rules_update = [
        'product_id' => 'exists:product,id',
        CONST_IMAGE  => 'image',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'App\Models\ProductImage';
    }
    
}