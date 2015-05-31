<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\LocalDataRequest;
use App\Http\Transformers\ObjectTransformer;
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
     * @return Response
     */
    public function show($id)
    {
        $data = DB::connection('sqlite_app')->table('local_data')->where('id', '=', $id)->get();

        dd($data);

        $data['info'] = DB::table('plant_types')->where('id', '=', $data['plant_id'])->first();
        $photo = DB::table('plant_type_photos')->where('plantType_id', '=', $data['plant_id'])->first();

        $name = explode("/", $photo->dataUrl);
        $name = explode(".", last($name));
        $photo->dataUrl = 'img/flowers/' . head($name) . '.jpg';
        $data['photo'] = $photo;

        return $this->response->withItem($data, new ObjectTransformer());
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

}
