<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class PlantTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'common name' => $obj[0]->commonName,
            'photo' => $obj[1]->dataUrl
        ];



        return $array;
    }

}