<?php

namespace App\Transformers;

use App\Models\Invoice;
use League\Fractal\TransformerAbstract;

class InvoiceTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'cart',
    ];

    protected $defaultIncludes = [];

    /**
     * @Request Invoice
     * @Response array
     */
    public function transform(Invoice $model)
    {
        return [
            'id'            => $model->id,
            'total'         => $model->total,
            'user_id'       => $model->user_id,
            'address'       => $model->address,
            'status'        => $model->status,
            'method'        => $model->method,
            'created_at'    => $model->created_at,
        ];
    }

    public function includeCart(Invoice $model)
    {
        $carts = $model->cart()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($carts, new CartTransformer, - 1);
    }
}
