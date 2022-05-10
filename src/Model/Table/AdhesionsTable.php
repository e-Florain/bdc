<?php
// src/Model/Table/BdcsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Http\Client;

class AdhesionsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
    }

    private $florapi = array(
        "url" => "http://10.0.3.184",
        "x-api-key" => "ITPeApnIUQK5trRjFJ2HLfM2e9VsrzPm5BL1FNbh4aVHCLfSnUGpUoKc7TFAJNVm"
    );

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

    public function getAdhs($params = array()) 
    {   
        $http = new Client();
        $url = $this->florapi['url'].'/getAdhs';
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