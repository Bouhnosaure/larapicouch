<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class FamilyNameTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'family name' => $obj->familyName
        ];


        return $array;
    }

}