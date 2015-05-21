

@extends('app')

@section('content')

    <div class="row">
        <div class="col-lg-12 text-center">
            <h1>CouchDB Methods on current ressource : {{ $object }}</h1>

            <h3>Php Style</h3>

            <p class="lead">
                <pre>
                    <code class="language-php">

                        //# Install Composer and get the lib
                        //curl -sS https://getcomposer.org/installer | php
                        //php composer.phar require guzzlehttp/guzzle:~5.0

                        require 'vendor/autoload.php';

                        use GuzzleHttp\Client;

                        $client = new Client(['base_url' => '{{url()}}/api/{{$object}}']);

                        //List of objects
                        $response = $client->get('/');

                        //Get object by id
                        $response = $client->get('/{id}');

                        //Create object
                        $response = $client->post('/',[
                            'body' => [
                                'field' => 'foo',
                                'other_field' => 'bar'
                            ]
                        ]);

                        //Update object
                        $response = $client->patch('/{id}',[
                            'body' => [
                                'field' => 'foo',
                                'other_field' => 'bar'
                            ]
                        ]);

                        //Update Object
                        $response = $client->put('/{id}',[
                            'body' => [
                                'field' => 'foo',
                                'other_field' => 'bar'
                            ]
                        ]);

                        //Delete Object
                        $response = $client->delete('/{id}');

                        //display response in json
                        $json = $response->json();

                        //display response in xml
                        $xml = $response->xml();

                    </code>
                </pre>
            </p>
            <br>
            <br>

            <h3>Java Style</h3>

            <p class="lead">
                <pre>
                    <code class="language-java">

                        /*
                        *
                        * if android => {{ '<uses-permission android:name="android.permission.INTERNET"/>' }}
                        *
                        * dependencies {
                        *   compile 'com.loopj.android:android-async-http:1.4.5'
                        * }
                        *
                        */

                        import com.loopj.android.http.*;

                        public class RestClient {

                            private static final String BASE_URL = "{{url()}}/api/{{$object}}";

                            private static AsyncHttpClient client = new AsyncHttpClient();

                            public static void getAll(AsyncHttpResponseHandler responseHandler) {
                                client.get(getAbsoluteUrl('/'), null, responseHandler);
                            }

                            public static void get(String id, AsyncHttpResponseHandler responseHandler) {
                                client.get(getAbsoluteUrl('/' + id), null, responseHandler);
                            }

                            public static void post(RequestParams params, AsyncHttpResponseHandler responseHandler) {
                                client.post(getAbsoluteUrl('/'), params, responseHandler);
                            }

                            public static void put(String id, RequestParams params, AsyncHttpResponseHandler responseHandler) {
                                client.put(getAbsoluteUrl('/' + id), params, responseHandler);
                            }

                            public static void delete(String id, AsyncHttpResponseHandler responseHandler) {
                                client.delete(getAbsoluteUrl('/' + id), null, responseHandler);
                            }

                            private static String getAbsoluteUrl(String relativeUrl) {
                                return BASE_URL + relativeUrl;
                            }
                        }

                        import org.json.*;
                        import com.loopj.android.http.*;

                        class RestClientUsage {
                            public void getAllObjects() throws JSONException {

                                //GET one doc
                                //
                                //RestClient.get("IDFOO", new JsonHttpResponse...

                                //CREATE one doc
                                //
                                //RequestParams params = new RequestParams();
                                //params.put("field", "foo");
                                //RestClient.post(params, new JsonHttpResponse...

                                //UPDATE one doc
                                //
                                //RequestParams params = new RequestParams();
                                //params.put("field", "foo");
                                //RestClient.put("IDFOO", params, new JsonHttpResponse...

                                // DELETE one doc
                                //
                                // RestClient.delete("IDFOO", new JsonHttpResponse...

                                //GET ALL docs
                                //
                                RestClient.getAll(new JsonHttpResponseHandler() {
                                    @Override
                                    public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                                        //Do a barrel roll
                                    }

                                    @Override
                                    public void onSuccess(int statusCode, Header[] headers, JSONArray timeline) {
                                        //Do another barrel roll
                                    }
                                });
                            }
                        }


                    </code>
                </pre>
            </p>
            <br>
            <br>

            <h3>JavaScript Style</h3>

            <p class="lead">
                <pre>
                    <code class="language-javascript">
                        // USE https://github.com/jpillora/jquery.rest
                        // JQUERY

                        var client = new $.RestClient('{{url()}}/api/{{$object}}/');

                        client.add('{{$object}}');

                        // Create
                        client.{{$object}}.create({a:21,b:42});
                        // POST /rest/api/foo/ (with data a=21 and b=42)

                        // Read
                        client.{{$object}}.read();
                        // GET {{url()}}/api/{{$object}}
                        client.{{$object}}.read(42);
                        // GET {{url()}}/api/{{$object}}42

                        // Update
                        client.{{$object}}.update(42, {my:"updates"});
                        // PUT {{url()}}/api/{{$object}}42/   my=updates

                        // Delete
                        client.{{$object}}.destroy(42);
                        client.{{$object}}.del(42);
                        // DELETE {{url()}}/api/{{$object}}42/


                        //CALLBACK
                        client.{{$object}}.read().done(function (data){
                            alert('I have my FOO data: ' + data);
                        });

                    </code>
                </pre>
            </p>

        </div>
    </div>

@stop