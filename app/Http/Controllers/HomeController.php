<?php namespace App\Http\Controllers;

use App\Services\CouchDB;

class HomeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Welcome Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
    */

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {

    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('pages.index');
    }

    /**
     * Show the application methods
     * @return Response
     * @internal param CouchDB $couchDB
     */
    public function methods()
    {
        return view('pages.methods');
    }

    /**
     * @param CouchDB $couchDB
     */
    public function clear(CouchDB $couchDB)
    {
        return $couchDB->dropCreateDatabase();
    }

    /**
     * @param CouchDB $couchDB
     * @return array
     */
    public function dummy(CouchDB $couchDB)
    {
        $couchDB->dropCreateDatabase();
        $couchDB->insert(['name' => 'john', 'email' => 'john@doe.fr']);
        $couchDB->insert(['name' => 'johnas', 'email' => 'johnas@doe.fr']);
        $couchDB->insert(['name' => 'johnid', 'email' => 'johnid@doe.fr']);
        $couchDB->insert(['name' => 'johnoh', 'email' => 'johnoh@doe.fr']);
        return $couchDB->getAllAsArray();
    }

}
