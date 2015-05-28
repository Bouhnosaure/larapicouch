<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Transformers\ClimateTransformer;
use App\Http\Transformers\PlantListTransformer;
use App\Http\Transformers\PlantTransformer;
use App\Http\Transformers\FamilyNameTransformer;
use App\Http\Transformers\ObjectTransformer;
use App\Http\Transformers\ScientificNameTransformer;
use App\Services\CouchDB;
use App\Services\ElasticSearch;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class KoubachiController extends Controller
{

    /**
     * @var Response
     */
    private $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {

        $this->response = $response;
    }


    public function plant_types_scientific_names()
    {
        $sciNames = DB::table('plant_types')->get();

        return $this->response->withCollection($sciNames, new ScientificNameTransformer());
    }

    public function plant_types_photos()
    {
        return DB::table('plant_type_photos')->get();
    }

    public function plant_types_climates()
    {
        $climates = DB::table('plant_types')->select('climate')->groupBy('climate')->get();

        return $this->response->withCollection($climates, new ClimateTransformer());
    }

    public function plant_types_family_names()
    {
        $famNames = DB::table('plant_types')->select('familyName')->groupBy('familyName')->get();

        return $this->response->withCollection($famNames, new FamilyNameTransformer());
    }

    public function plant_by_id($id, ElasticSearch $es)
    {

        $info = DB::table('plant_types')->where('id', '=', $id)->first();
        $photo = DB::table('plant_type_photos')->where('plantType_id', '=', $id)->first();

        $current = $es->getAll()->toArray();

        $notification = array();

        // Checks for temperature, enlightment and moisture/humidity : Comparison between values from CouchDB and values required from plant Sqlite Database THEN Notif


        $tempStatus = $this->checkTemperature($this->extractTemp($info->hardiness), $current['temperature'], $this->extractTemp($info->genericInstructionTemperature));

        array_push($notification, array("temperature" => $tempStatus));

        $enlightStatus = $this->checkEnlightment($info->genericInstructionLight, $current['brightness']);

        array_push($notification, array("enlightment" => $enlightStatus));

        $humiStatus = $this->checkHumidity($info->genericInstructionWater, $current['moisture']);

        array_push($notification, array("moisture" => $humiStatus));


        return $this->response->withItem([$info, $photo, $current, $notification], new PlantTransformer());
    }

    public function  getDevicesIp(){
        
    }

    public function plant_list()
    {
        $infos = DB::table('plant_types')->paginate('20');

        foreach ($infos as $key => $info) {

            $photo = DB::table('plant_type_photos')->select('dataUrl')->where('plantType_id', '=', $info->id)->first();
            $infos[$key]->photo = $photo;

        }


        return $this->response->withPaginator($infos, new PlantListTransformer());

    }


    //Check moisture reference attribute çf transformed object by comparying to CouchDB cache mesure

    public function checkHumidity($genericInstruction, $actualMoisture){


        if(($genericInstruction == "Veillez à ce que le sol soit humide." && $actualMoisture < 30) || ($genericInstruction == "N’arroser que pendant les mois d’été." && $actualMoisture < 0) || ($genericInstruction == "Le sol ne devrait jamais complètement dessécher." && $actualMoisture < 15) || ($genericInstruction == "Le sol doit constamment être mouillé." && $actualMoisture < 85) || ($genericInstruction == "Le sol devrait être constamment très humide (presque mouillé)." && $actualMoisture < 70) || ($genericInstruction == "En été, le sol devrait être très humide (presque mouillé) pendant qu’en hiver, il devrait être simplement humide." && $actualMoisture < 35) || ($genericInstruction == "En été, le sol devrait être humide pendant qu’en hiver il ne devrait pas dessécher." && $actualMoisture < 30) || ($genericInstruction == "Veillez à ce que le sol soit humide." && $actualMoisture < 35) )
        {

            return "low";
        }

        else if(($genericInstruction == "Veillez à ce que le sol soit humide." && $actualMoisture > 65) || ($genericInstruction == "N’arroser que pendant les mois d’été." && $actualMoisture > 15) || ($genericInstruction == "Le sol ne devrait jamais complètement dessécher." && $actualMoisture > 25) || ($genericInstruction == "Le sol doit constamment être mouillé." && $actualMoisture > 120) || ($genericInstruction == "Le sol devrait être constamment très humide (presque mouillé)." && $actualMoisture > 100) || ($genericInstruction == "En été, le sol devrait être très humide (presque mouillé) pendant qu’en hiver, il devrait être simplement humide." && $actualMoisture > 50) || ($genericInstruction == "En été, le sol devrait être humide pendant qu’en hiver il ne devrait pas dessécher." && $actualMoisture > 50) || ($genericInstruction == "Veillez à ce que le sol soit humide." && $actualMoisture > 50) )
        {

            return "high";
        }

        else
        {
            return "ok";
        }

    }

    //Check enlightment reference attribute çf transformed object by comparying to CouchDB cache mesure

    public function checkEnlightment($genericInstruction, $actualEnlightment) {


        if(($genericInstruction == "Préfère le soleil direct." && $actualEnlightment < 50000) || ($genericInstruction == "Préfère des endroits lumineux sans soleil direct." && $actualEnlightment < 25000 ) || ($genericInstruction == "Préfère des endroits mi-ombragés." && $actualEnlightment < 1000) || ($genericInstruction == "Préfère des endroits ombragés." && $actualEnlightment < 100))
        {

           return "low";
        }

        else if (($genericInstruction == "Préfère le soleil direct." && $actualEnlightment > 100000) || ($genericInstruction == "Préfère des endroits lumineux sans soleil direct." && $actualEnlightment > 30000 ) || ($genericInstruction == "Préfère des endroits mi-ombragés." && $actualEnlightment > 5000) || ($genericInstruction == "Préfère des endroits ombragés." && $actualEnlightment > 400))
        {
            return "high";
        }

        else
        {
            return "ok";
        }


    }

    //Check temperature reference attribute çf transformed object by comparying to CouchDB cache mesure

    public function checkTemperature($hardiness, $averageTemp, $actualTemp)
    {

        if($hardiness > $actualTemp)
        {

            return "low";
        }

        else if ($actualTemp >= $averageTemp + 15 )
        {
            return "high";
        }

        else
        {
            return "ok";
        }
    }

    // Get true numeric information from talkative attributes.

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
