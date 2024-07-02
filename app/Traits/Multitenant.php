<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Multitenant
{
    protected static function bootMultitenant()
    {
        if (auth()->check()) {
            static::creating(function ($model) {
                $model->business_id = auth()->user()->business_id;
            });

            static::addGlobalScope('business_id', function (Builder $builder) {
                $builder->from($builder->getModel()->getTable())->where(''.$builder->getModel()->getTable().'.business_id', auth()->user()->business_id);
            });
        }
        else if(auth('employee-api')->check()){
            static::creating(function ($model) {
                $model->business_id = auth('employee-api')->user()->business_id;
            });

            static::addGlobalScope('business_id', function (Builder $builder) {
                $builder->from($builder->getModel()->getTable())->where(''.$builder->getModel()->getTable().'.business_id', auth('employee-api')->user()->business_id);
            });
        }
    }
}
