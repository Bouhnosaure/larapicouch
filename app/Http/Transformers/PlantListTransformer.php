<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class PlantListTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'commonName' => $obj->commonName,
            'type' => $obj->familyName,
            'photo' => $obj->photo
        ];



        return $array;
    }

}