<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\LocalDataRequest;
use App\Http\Transformers\ObjectTransformer;
use App\Http\Transformers\PlantLocalTransformer;
use App\Http\Transformers\PlantTransformer;
use App\Services\ElasticSearch;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocalDataController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = DB::connection('sqlite_app')->table('local_data')->get();

        foreach ($data as $key => $flower) {
            $photo = DB::table('plant_type_photos')->where('plantType_id', '=', $flower->plant_id)->first();
            $name = explode("/", $photo->dataUrl);
            $name = explode(".", last($name));
            $photo->dataUrl = 'img/flowers/' . head($name) . '.jpg';
            $data[$key]->photo = $photo;
        }

        return $this->response->withCollection($data, new ObjectTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(LocalDataRequest $dataRequest)
    {
        $vars = $dataRequest->all();
        $vars['created_at'] = Carbon::now()->timestamp;
        $vars['updated_at'] = Carbon::now()->timestamp;

        $data = DB::connection('sqlite_app')->table('local_data')->insert($vars);
        return 'ok';
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param ElasticSearch $es
     * @return Response
     */
    public function show($id, ElasticSearch $es)
    {
        $data = DB::connection('sqlite_app')->table('local_data')->where('id', '=', $id)->first();

        $info = DB::table('plant_types')->where('id', '=', $data->plant_id)->first();
        $info->alias = $data->alias;

        $photo = DB::table('plant_type_photos')->where('plantType_id', '=', $data->plant_id)->first();

        $name = explode("/", $photo->dataUrl);
        $name = explode(".", last($name));
        $photo->dataUrl = 'img/flowers/' . head($name) . '.jpg';

        $current = $es->getLastByIp($data->ip)->oneToArray();

        $notification = array();

        // Checks for temperature, enlightment and moisture/humidity : Comparison between values from CouchDB and values required from plant Sqlite Database THEN Notif

        $tempStatus = $this->checkTemperature($this->extractTemp($info->hardiness), $current['temperature'], $this->extractTemp($info->genericInstructionTemperature));

        array_push($notification, array("temperature" => $tempStatus));

        $enlightStatus = $this->checkEnlightment($info->genericInstructionLight, $current['brightness']);

        array_push($notification, array("enlightment" => $enlightStatus));

        $humiStatus = $this->checkHumidity($info->genericInstructionWater, $current['moisture']);

        array_push($notification, array("moisture" => $humiStatus));


        return $this->response->withItem([$info, $photo, $current, $notification], new PlantLocalTransformer());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id, LocalDataRequest $dataRequest)
    {
        $vars = $dataRequest->all();

        $vars['updated_at'] = Carbon::now()->timestamp;

        $data = DB::connection('sqlite_app')->table('local_data')->where('id', '=', $id)->update($vars);
        return 'ok';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $data = DB::connection('sqlite_app')->table('local_data')->where('id', '=', $id)->delete();
        return 'ok';
    }


    public function checkHumidity($genericInstruction, $actualMoisture)
    {


        if (($genericInstruction == "Veillez à ce que le sol soit humide." && $actualMoisture < 30) || ($genericInstruction == "N’arroser que pendant les mois d’été." && $actualMoisture < 0) || ($genericInstruction == "Le sol ne devrait jamais complètement dessécher." && $actualMoisture < 15) || ($genericInstruction == "Le sol doit constamment être mouillé." && $actualMoisture < 85) || ($genericInstruction == "Le sol devrait être constamment très humide (presque mouillé)." && $actualMoisture < 70) || ($genericInstruction == "En été, le sol devrait être très humide (presque mouillé) pendant qu’en hiver, il devrait être simplement humide." && $actualMoisture < 35) || ($genericInstruction == "En été, le sol devrait être humide pendant qu’en hiver il ne devrait pas dessécher." && $actualMoisture < 30) || ($genericInstruction == "Veillez à ce que le sol soit humide." && $actualMoisture < 35)) {

            return "low";
        } else if (($genericInstruction == "Veillez à ce que le sol soit humide." && $actualMoisture > 65) || ($genericInstruction == "N’arroser que pendant les mois d’été." && $actualMoisture > 15) || ($genericInstruction == "Le sol ne devrait jamais complètement dessécher." && $actualMoisture > 25) || ($genericInstruction == "Le sol doit constamment être mouillé." && $actualMoisture > 120) || ($genericInstruction == "Le sol devrait être constamment très humide (presque mouillé)." && $actualMoisture > 100) || ($genericInstruction == "En été, le sol devrait être très humide (presque mouillé) pendant qu’en hiver, il devrait être simplement humide." && $actualMoisture > 50) || ($genericInstruction == "En été, le sol devrait être humide pendant qu’en hiver il ne devrait pas dessécher." && $actualMoisture > 50) || ($genericInstruction == "Veillez à ce que le sol soit humide." && $actualMoisture > 50)) {

            return "high";
        } else {
            return "ok";
        }

    }

    //Check enlightment reference attribute çf transformed object by comparying to CouchDB cache mesure

    public function checkEnlightment($genericInstruction, $actualEnlightment)
    {


        if (($genericInstruction == "Préfère le soleil direct." && $actualEnlightment < 50000) || ($genericInstruction == "Préfère des endroits lumineux sans soleil direct." && $actualEnlightment < 25000) || ($genericInstruction == "Préfère des endroits mi-ombragés." && $actualEnlightment < 1000) || ($genericInstruction == "Préfère des endroits ombragés." && $actualEnlightment < 100)) {

            return "low";
        } else if (($genericInstruction == "Préfère le soleil direct." && $actualEnlightment > 100000) || ($genericInstruction == "Préfère des endroits lumineux sans soleil direct." && $actualEnlightment > 30000) || ($genericInstruction == "Préfère des endroits mi-ombragés." && $actualEnlightment > 5000) || ($genericInstruction == "Préfère des endroits ombragés." && $actualEnlightment > 400)) {
            return "high";
        } else {
            return "ok";
        }


    }

    //Check temperature reference attribute çf transformed object by comparying to CouchDB cache mesure

    public function checkTemperature($hardiness, $averageTemp, $actualTemp)
    {

        if ($hardiness > $actualTemp) {

            return "low";
        } else if ($actualTemp >= $averageTemp + 15) {
            return "high";
        } else {
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
