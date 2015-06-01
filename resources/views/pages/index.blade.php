@extends('app')

@section('content')

    <div class="row">
        <div class="col-lg-12 text-center">
            <h1>CouchDB Endpoint</h1>

            <h3>Routes</h3>

            <p class="lead" >
                <pre>
                    <code class="language-bash">

                        +----------+-------------------------------+---------------------------------------------------------------------+
                        | Method   | URI                           |Action                                                               |
                        +----------+-------------------------------+---------------------------------------------------------------------+
                        | GET|HEAD | /                             |App\Http\Controllers\HomeController@index                            |
                        | GET|HEAD | methods                       |App\Http\Controllers\HomeController@methods                          |
                        | GET|HEAD | warning-clear                 |App\Http\Controllers\HomeController@clear                            |
                        | GET|HEAD | warning-dummy                 |App\Http\Controllers\HomeController@dummy                            |
                        | GET|HEAD | appdata/plant/photos          |App\Http\Controllers\KoubachiController@plant_types_photos           |
                        | GET|HEAD | appdata/plant/climates        |App\Http\Controllers\KoubachiController@plant_types_climates         |
                        | GET|HEAD | appdata/plant/scientificNames |App\Http\Controllers\KoubachiController@plant_types_scientific_names |
                        | GET|HEAD | appdata/plant/familyNames     |App\Http\Controllers\KoubachiController@plant_types_family_names     |
                        | GET|HEAD | appdata/plant/list            |App\Http\Controllers\KoubachiController@plant_list                   |
                        | GET|HEAD | appdata/plant/{id}            |App\Http\Controllers\KoubachiController@plant_by_id                  |
                        | GET|HEAD | api/mesures                   |App\Http\Controllers\CouchDBController@index                         |
                        | GET|HEAD | api/mesures/create            |App\Http\Controllers\CouchDBController@create                        |
                        | POST     | api/mesures                   |App\Http\Controllers\CouchDBController@store                         |
                        | GET|HEAD | api/mesures/{mesures}         |App\Http\Controllers\CouchDBController@show                          |
                        | GET|HEAD | api/mesures/{mesures}/edit    |App\Http\Controllers\CouchDBController@edit                          |
                        | PUT      | api/mesures/{mesures}         |App\Http\Controllers\CouchDBController@update                        |
                        | PATCH    | api/mesures/{mesures}         |App\Http\Controllers\CouchDBController@update                        |
                        | DELETE   | api/mesures/{mesures}         |App\Http\Controllers\CouchDBController@destroy                       |
                        | GET|HEAD | api/average/brightness        |App\Http\Controllers\CouchDBController@avgBright                     |
                        | GET|HEAD | api/average/temperature       |App\Http\Controllers\CouchDBController@avgTemp                       |
                        | GET|HEAD | api/average/moisture          |App\Http\Controllers\CouchDBController@avgMoisture                   |
                        +----------+-------------------------------+---------------------------------------------------------------------+
                    </code>
                </pre>
            </p>
            <br>
            <br>


        </div>
    </div>

@stop