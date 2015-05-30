<?php namespace App\Services;


use Carbon\Carbon;
use Elastica\Aggregation\Terms;
use Elastica\Client;
use Elastica\Document;
use Elastica\Facet\Terms as FacetQuery;
use Elastica\Facet\Statistical as FacetQueryStats;
use Elastica\Query;
use Elastica\Query\Term;
use Elastica\Query\Match as Match;
use Elastica\Filter\Term as FilterTerm;
use Elastica\Filter\Terms as FilterTerms;
use Elastica\Query\MatchAll as MatchAll;
use Elastica\Request;
use Elastica\Type\Mapping as TypeMapping;
use Elastica\Filter\Bool as BooleanFilter;
use Elastica\Filter\Range as RangeFilter;
use Elastica\Filter\BoolAnd as BoolAnd;
use Elastica\Query\Filtered as Filtered;
use Elastica\Search as Search;
use Elastica\Aggregation\Terms as AggTerm;
use Symfony\Component\Yaml\Yaml;

class ElasticSearch
{

    private $result;

    private $index;

    private $type;


    private $client;

    private $path_search;

    /**
     *
     */
    public function __construct()
    {
        $this->result = "";

        if ($this->client == null) {
            $this->client = new Client(array('host' => 'couchdb.ovh', 'port' => '9200'));
        }

        $this->index = $this->client->getIndex('flowair');
        $this->type = $this->index->getType('flowair');
        $this->path_search = $path = $this->index->getName() . '/' . $this->type->getName() . '/_search';

    }

    public function getLast()
    {
        $query = '{"from": 0,"size": 1,"sort": {"datetime": {"order": "desc"}}}';
        $response = $this->client->request($this->path_search, Request::GET, $query);
        $data = $response->getData();
        $this->result = $data['hits']['hits'];

        return $this;
    }

    public function getAll()
    {
        $query = '{"from": 0,"size": 50,"sort": {"datetime": {"order": "desc"}}}';
        $response = $this->client->request($this->path_search, Request::GET, $query);
        $data = $response->getData();
        $this->result = $data['hits']['hits'];

        return $this;
    }


    public function getResults()
    {
        return $this->result->getResults();
    }

    public function toArray($field = "search")
    {
        $array = array();

        if ($field == "search") {
            foreach ($this->result as $result) {
                array_push($array, $result['_source']);
            }
        } elseif ($field == "agg") {
            foreach ($this->result as $res) {
                array_push($array, $res);
            }
        }

        return $array;
    }

    public function oneToArray()
    {
        return $this->result[0]['_source'];
    }

    public function getAllIp()
    {
        $agg = new Terms("terms");
        $agg->setField("ip");
        $agg->setField("datetime");

        $query = Query::create(new MatchAll());
        $query->addAggregation($agg);
        $search = new Search($this->client);

        $this->result = $search->search($query)->getAggregation("terms");
        $this->result = $this->result['buckets'];

        return $this;
    }

    public function getHistogramHours()
    {
        $query = '
        {
            query: {
                "bool": {
                    "must": {
                       "range": {
                          "datetime": {
                             "from": "' . Carbon::now()->subDay()->subHours(8)->toIso8601String() . '",
                             "to": "' . Carbon::now()->toIso8601String() . '"
                          }
                       }
                    }
                }
            },
            aggregations: {
                day_interval: {
                    date_histogram: {
                        field: "datetime",
                        interval: "hour",
                        "format" : "yyyy-MM-dd HH:mm:ss"
                    },
                    aggregations: {
                        "by_ip": {
                            "terms": {
                                "field": "ip"
                            },
                            aggregations: {
                                temperature: {
                                    avg: {
                                        field: "temperature"
                                    }
                                },
                                brightness: {
                                    avg: {
                                        field: "brightness"
                                    }
                                },
                                moisture: {
                                    avg: {
                                        field: "moisture"
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        ';
        $response = $this->client->request($this->path_search, Request::GET, $query);
        $data = $response->getData();

        $return = array();

        foreach ($data['aggregations']['day_interval']['buckets'] as $value) {
            $datetime = $value['key_as_string'];
            foreach ($value['by_ip']['buckets'] as $ip) {

                if ($ip['key'] == null) {
                    continue;
                }

                $return[$ip['key']][] = [
                    'ip' => $ip['key'],
                    'brightness' => $ip['brightness']['value'],
                    'moisture' => $ip['moisture']['value'],
                    'temperature' => $ip['temperature']['value'],
                    'datetime' => $datetime
                ];

            }
        }

        return $return;
    }

    public function reindex()
    {
        $index = $this->client->getIndex('flowair');
        $type = $index->getType('flowair');

        $mapping = new TypeMapping();
        $mapping->setType($type);

        $mapping->setProperties(Yaml::parse(storage_path('mapping.yml')));
        $response = $mapping->send()->isOk();

        $type->getIndex()->refresh();

        return 'OK';
    }


}