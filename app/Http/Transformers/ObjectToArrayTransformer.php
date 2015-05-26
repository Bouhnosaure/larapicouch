<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class ObjectToArrayTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'commonName' => $obj->commonName
        ];


        return $array;
    }

}