<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class ScientificNameTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'scientific name' => $obj->scientificName
        ];


        return $array;
    }

}