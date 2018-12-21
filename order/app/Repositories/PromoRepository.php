<?php

namespace App\Repositories;

class PromoRepository extends BaseRepository
{
    /**
     * @var \App\Models\Promo|\Illuminate\Database\Eloquent\Model
     */
    public $model;

    protected static $rules = [
        'code' => 'required|min:5|max:255',
        'value' => 'required|numeric',
        'begin_date' => 'required|date_format:Y-m-d',
        'end_date' => 'required|date_format:Y-m-d|after:begin_date'
    ];

    protected static $rules_update = [
        'code' => 'min:5|max:255',
        'value' => 'numeric',
        'begin_date' => 'date_format:Y-m-d',
        'end_date' => 'date_format:Y-m-d|after:begin_date'
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'App\Models\Promo';
    }

}