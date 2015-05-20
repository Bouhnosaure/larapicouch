@extends('app')

@section('content')

    <div class="row">
        <div class="col-lg-12 text-center">
            <h1>CouchDB Endpoint</h1>

            <h3>Routes</h3>

            <p class="lead">
                <pre>
                    <code class="language-bash">

                        +----------+--------------------------+--------------------+------------------------------------------------+
                        | Method   | URI                      | Name               | Action                                         |
                        +----------+--------------------------+--------------------+------------------------------------------------+
                        | GET|HEAD | /                        |                    | App\Http\Controllers\HomeController@index      |
                        | GET|HEAD | methods                  |                    | App\Http\Controllers\HomeController@methods    |
                        | GET|HEAD | clear                    |                    | App\Http\Controllers\HomeController@clear      |
                        | GET|HEAD | dummy                    |                    | App\Http\Controllers\HomeController@dummy      |
                        | GET|HEAD | api/object               | api.object.index   | App\Http\Controllers\CouchDBController@index   |
                        | POST     | api/object               | api.object.store   | App\Http\Controllers\CouchDBController@store   |
                        | GET|HEAD | api/object/{object}      | api.object.show    | App\Http\Controllers\CouchDBController@show    |
                        | PUT      | api/object/{object}      | api.object.update  | App\Http\Controllers\CouchDBController@update  |
                        | PATCH    | api/object/{object}      |                    | App\Http\Controllers\CouchDBController@update  |
                        | DELETE   | api/object/{object}      | api.object.destroy | App\Http\Controllers\CouchDBController@destroy |
                        +----------+--------------------------+--------------------+------------------------------------------------+
                    </code>
                </pre>
            </p>
            <br>
            <br>


        </div>
    </div>

@stop