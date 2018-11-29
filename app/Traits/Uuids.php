<?php
namespace App\Traits;
use Ramsey\Uuid\Uuid;

trait Uuids
{
    /**
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Make sure that we create a uuid when creating a model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
        });
    }
}
