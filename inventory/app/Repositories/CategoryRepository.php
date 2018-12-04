<?php

namespace App\Repositories;

class CategoryRepository extends BaseRepository
{
    /**
     * @var \App\Models\Category|\Illuminate\Database\Eloquent\Model
     */
    public $model;

    protected static $rules = [
        'name' => 'required|min:5|max:255',
    ];

    protected static $rules_update = [
        'name' => 'min:5|max:255',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'App\Models\Category';
    }

}