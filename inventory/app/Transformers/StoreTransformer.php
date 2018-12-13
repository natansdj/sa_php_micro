<?php

namespace App\Transformers;

use App\Models\Store;
use League\Fractal\TransformerAbstract;

class StoreTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user'
    ];

    protected $defaultIncludes = [];

    /**
     * @Request Store
     * @Response array
     */
    public function transform(Store $model)
    {
        return [
            'id'            => $model->id,
            'user_id'       => $model->user_id,
            'name'          => $model->name,
            'description'   => $model->description,
            'image'         => $model->image,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at
        ];
    }

    public function includeUser(Store $model)
    {
        $users = $model->user()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($users, new UserTransformer(), - 1);
    }

    /*public function includeProduct(Store $model)
    {
        $images = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ProductTransformer(), - 1);
    }

    public function includeCategory(Store $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryTransformer(), - 1);
    }

    public function includeImage(Store $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageTransformer(), - 1);
    }*/
}