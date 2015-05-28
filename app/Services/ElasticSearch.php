<?php namespace App\Services;


use Carbon\Carbon;
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
use Elastica\Type\Mapping as TypeMapping;
use Elastica\Filter\Bool as BooleanFilter;
use Elastica\Filter\Range as RangeFilter;
use Elastica\Filter\BoolAnd as BoolAnd;
use Elastica\Query\Filtered as Filtered;
use Elastica\Search as Search;
use Elastica\Aggregation\Terms as AggTerm;

class ElasticSearch
{

    private $result;


    private $client;

    /**
     *
     */
    public function __construct()
    {
        $this->result = "";

        if ($this->client == null) {

            $this->client = new Client(array(

                'host' => 'couchdb.ovh',
                'port' => '9200'
            ));

        }


    }

    public function getLast()
    {

        $query = Query::create(new MatchAll());
        $query->setSize(1);
        $query->setSort(array('datatime' => array('order' => 'desc', 'ignore_unmapped' => true)));
        $search = new Search($this->client);

        $this->result = $search->search($query);

        return $this;
    }

    public function getAll()
    {


        $query = Query::create(new MatchAll());
        $query->setSize(100);
        $query->setSort(array('datatime' => array('order' => 'desc', 'ignore_unmapped' => true)));
        $search = new Search($this->client);

        $this->result = $search->search($query);

        return $this;
    }


    public function getResults()
    {
        return $this->result->getResults();
    }

    public function toArray()
    {
        return $this->result->getResults()[0]->getHit()['_source'];
    }





}