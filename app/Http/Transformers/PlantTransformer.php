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
            'averageTemp' => $this->extractTemp($obj[0]->genericInstructionTemperature),
            'minTemp' => $this->extractTemp($obj[0]->hardiness),
            'enlightment' => $obj[0]->genericInstructionLight,
            'humidity' => $obj[0]->genericInstructionWater,
            'photo' => $obj[1]->dataUrl,
            'current' => $obj[2]
        ];

        return $array;
    }


    public function extractTemp($attribute)
    {

        $matches = array();

        preg_match('/\-?(\d+)\s?\°/', $attribute, $matches);


        if (is_array($matches)) {
            foreach ($matches as $key => $matche) {
                trim($matche);
                $tmp = explode('°', $matche);
                $tmp = explode(' ', $tmp[0]);
                $matches[$key] = $tmp[0];
            }

            array_pop($matches);
            $matches = $matches[0];
        }


        return $matches;

    }
}