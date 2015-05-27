<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class PlantTransformer extends Transformer
{
    public function transform($obj)
    {
        $array = [
            'common name' => $obj[0]->commonName,
            'scientific name' => $obj[0]->scientificName,
            'category' => $obj[0]->familyName,
            'temperature moyenne' => $this->extractTemp($obj[0]->genericInstructionTemperature),
            'temperature min' => $this->extractTemp($obj[0]->hardiness),
            'ensoleillement' => $obj[0]->genericInstructionLight,
            'humidite' => $obj[0]->genericInstructionWater,
            'photo' => $obj[1]->dataUrl
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