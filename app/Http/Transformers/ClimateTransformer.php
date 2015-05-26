<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class ClimateTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'climate' => $obj->climate
        ];


        return $array;
    }

}