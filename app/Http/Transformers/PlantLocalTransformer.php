<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class PlantLocalTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'data' => $obj[0],
            'info' => $obj[1],
            'photo' => $obj[2]->dataUrl,
            'current' => $obj[3],
            'notification' =>$obj[4]
        ];

        return $array;
    }



}