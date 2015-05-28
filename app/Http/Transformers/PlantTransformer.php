<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class PlantTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'id' => $obj[0]->id,
            'commonName' => $obj[0]->commonName,
            'scientificName' => $obj[0]->scientificName,
            'category' => $obj[0]->familyName,
            'averageTemp' => $obj[0]->genericInstructionTemperature,
            'minTemp' => $obj[0]->hardiness,
            'enlightment' => $obj[0]->genericInstructionLight,
            'humidity' => $obj[0]->genericInstructionWater,
            'photo' => $obj[1]->dataUrl,
            'current' => $obj[2],
            'notification' =>$obj[3]
        ];

        return $array;
    }



}