<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class FamilyNameTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'familyName' => $obj->familyName
        ];


        return $array;
    }

}