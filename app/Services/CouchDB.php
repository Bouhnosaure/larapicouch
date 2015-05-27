<?php namespace App\Services;


use Carbon\Carbon;
use couchClient;
use GuzzleHttp\Client as Gclient;
use Illuminate\Support\Facades\Cache;

class CouchDB
{

    private $client;

    /**
     *
     */
    public function __construct()
    {
        $this->client = new couchClient(getenv('COUCHDB_HOST'), getenv('COUCHDB_DATABASE'));

    }

    public function getAll()
    {
        return $this->client->getAllDocs();
    }

    public function get($id)
    {
        try {
            return $this->objectToArray($this->client->getDoc($id));
        } catch (Exception $e) {
            return 'Not Found : ' . $e;
        }
    }

    public function getAllAsArray()
    {
        $array = array();
        $list = $this->getAll();

        foreach ($list->rows as $doc) {
            array_push($array, $this->get($doc->id));
        }

        return $array;
    }

    public function insert(array $data)
    {
        $data['name_plante'] = 'clumeau';
        $data['type_plante'] = 'petunia';
        $data['center'] = 'bordeaux';
        $data['group'] = '2';
        $data['basename'] = 'flowair';
        $data['device_id'] = 'f1089f3ca2';
        $data['datetime'] = Carbon::now()->toIso8601String();

        Cache::add('data-temp', $data, 1);

        $obj = $this->arrayToObject($data);
        try {
            $response = $this->client->storeDoc($obj);
            return $this->objectToArray($response);
        } catch (Exception $e) {
            return 'Error : ' . $e;
        }
    }

    public function update($id, array $data)
    {
        $doc = $this->objectToArray($this->get($id));

        $doc = array_replace($doc, $data);

        return $this->insert($doc);

    }

    public function delete($id)
    {
        return $this->objectToArray(
            $this->client->deleteDoc(
                $this->arrayToObject(
                    $this->get($id)
                )
            )
        );
    }

    public function dropCreateDatabase()
    {
        $this->client->deleteDatabase();
        $this->client->createDatabase();
    }

    public function commit()
    {
        return $response = $this->client->ensureFullCommit();
    }

    private function arrayToObject($array)
    {
        return $stdClass = json_decode(json_encode($array));
    }

    private function objectToArray($data)
    {
        return $array = json_decode(json_encode($data), true);
    }

    public function get_average_temperature()
    {
        $client = new Gclient();
        $response = $client->get('http://couchdb.ovh:5984/flowair/_design/mesures/_view/temperature_day?group=true');
        return $response->json();
    }

    public function get_average_brightness()
    {
        $client = new Gclient();
        $response = $client->get('http://couchdb.ovh:5984/flowair/_design/mesures/_view/brightness_day?group=true');
        return $response->json();
    }

    public function get_average_moisture()
    {
        $client = new Gclient();
        $response = $client->get('http://couchdb.ovh:5984/flowair/_design/mesures/_view/moisture_day?group=true');
        return $response->json();
    }

}