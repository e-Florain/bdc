<?php
// src/Model/Table/BdcsTable.php
namespace App\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Table;
use Cake\Http\Client;

class AdhesionsTable extends Table
{
    private $florapi = array();

    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
        $this->florapi['url'] = Configure::read('Florapi.url');
        $this->florapi['x-api-key'] = Configure::read('Florapi.key');
    }

    public function getBdcs()
    {
        $http = new Client();
        $url = $this->florapi['url'].'/getAdhpros?currency_exchange_office=1';
        $found = false;
        $response = $http->get($url, [], [
            'headers' => [
                'x-api-key' => $this->florapi['x-api-key'],
                'Content-Type' => 'application/json'
                ]
        ]);
        $results = $response->getJson();
        return $results;
    }

    public function getAdhpros()
    {
        $http = new Client();
        $url = $this->florapi['url'].'/getAdhpros';
        $found = false;
        $response = $http->get($url, [], [
            'headers' => [
                'x-api-key' => $this->florapi['x-api-key'],
                'Content-Type' => 'application/json'
                ]
        ]);
        $results = $response->getJson();
        return $results;
    }

    public function getAdhs($params) 
    {   
        $http = new Client();
        $paramsurl = http_build_query($params);
        $url = $this->florapi['url'].'/getAdhs?'.$paramsurl;
        $found = false;
        $response = $http->get($url, [], [
            'headers' => [
                'x-api-key' => $this->florapi['x-api-key'],
                'Content-Type' => 'application/json'
                ]
        ]);
        $results = $response->getJson();
        return $results;
    }

    public function getOdooAssos($params = array()) 
    {
        $http = new Client();
        $url = $this->florapi['url'].'/getAssos';
        $i=0;
        foreach ($params as $key=>$param) {
            if ($i==0) {
                $url=$url."?".$key."=".$param;
            } else {
                $url=$url."&".$key."=".$param;
            }
            $i++;
        }
        $found = false;
        $response = $http->get($url, [], [
            'headers' => [
                'x-api-key' => $this->florapi['x-api-key'],
                'Content-Type' => 'application/json'
                ]
        ]);
        $results = $response->getJson();
        return $results;
    }
}