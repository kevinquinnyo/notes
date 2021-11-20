<?php
declare(strict_types=1);
namespace App\Models;

use App\EntityErrorInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class AbstractModel extends Model implements EntityErrorInterface
{
    protected $errors = [];

    protected $rules = [];

    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        // pass the entity data through validation before saving
        // we set an errors array if there are validation issues
        $validate = function ($model) {
            $validator = Validator::make($model->toArray(), $model->getRules());        

            if ($validator->fails()) {
                $model->setErrors($validator->getMessageBag()->getMessages());

                return false;
            }
        };

        self::creating($validate);
        self::updating($validate);
    }
}
