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
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

    public function plant_by_id($id)
    {

        $info = DB::table('plant_types')->where('id','=', $id)->first();
        $photo = DB::table('plant_type_photos')->where('plantType_id', '=', $id)->first();




        return $this->response->withItem([$info,$photo], new PlantTransformer());
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






}
