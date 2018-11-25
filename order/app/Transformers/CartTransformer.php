<?php

namespace App\Transformers;

use App\Models\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [];

    /**
     * @Request Cart
     * @Response array
     */
    public function transform(Cart $model)
    {
        return [
            'id'            => $model->id,
            'created_at'    => $model->created_at,
            'total'         => $model->total,
            'status'        => $model->status,
            'product_id'    => $model->product_id,
            'user_id'       => $model->user_id,
            'stock'         => $model->stock,
            'invoice_id'    => $model->invoice_id,

        ];
    }
}
