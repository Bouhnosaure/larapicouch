<?php namespace App\Http\Controllers;

use App\Services\CouchDB;
use App\Services\ElasticSearch;
use Illuminate\Support\Facades\DB;

class ElasticSearchController extends Controller
{


    public function reindex(ElasticSearch $elasticSearch)
    {
        return $elasticSearch->reindex();
    }

}
