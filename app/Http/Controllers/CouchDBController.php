<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ObjectRequest;
use App\Http\Transformers\ObjectTransformer;
use App\Services\CouchDB;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;

class CouchDBController extends Controller
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
     * @param CouchDB $couchDB
     * @return Response
     */
    public function index(CouchDB $couchDB)
    {
        return $this->response->withCollection($couchDB->getAllAsArray(), new ObjectTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param CouchDB $couchDB
     * @return Response
     */
    public function store(Request $request, CouchDB $couchDB)
    {
        return $this->response->withItem($couchDB->insert($request->all()), new ObjectTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param CouchDB $couchDB
     * @return Response
     */
    public function show($id, CouchDB $couchDB)
    {
        return $this->response->withItem($couchDB->get($id), new ObjectTransformer());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @param CouchDB $couchDB
     * @return Response
     */
    public function update(Request $request, $id, CouchDB $couchDB)
    {
        return $this->response->withItem($couchDB->update($id, $request->all()), new ObjectTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param CouchDB $couchDB
     * @return Response
     */
    public function destroy($id, CouchDB $couchDB)
    {
        return $this->response->withItem($couchDB->delete($id), new ObjectTransformer());
    }

}
