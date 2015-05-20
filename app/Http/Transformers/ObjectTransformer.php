<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class ObjectTransformer extends Transformer
{
    public function transform($array)
    {
        return $array;
    }

}