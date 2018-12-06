<?php

namespace App\Transformers;

use App\Models\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product'
    ];

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

    public function includeProduct(Cart $model)
    {
        $products = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($products, new ProductTransformer, - 1);
    }
}
