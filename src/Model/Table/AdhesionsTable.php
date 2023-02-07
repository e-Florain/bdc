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

    public function getAdhpros($params)
    {
        $http = new Client();
        $paramsurl = http_build_query($params);
        $url = $this->florapi['url'].'/getAdhpros?'.$paramsurl;
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

    public function updateAdh($datas) {
        $http = new Client();
        $url = $this->florapi['url'].'/putAdhs';
        /*$datas = array(
            "email" => $datas['email'],
            "changeeuros" => $datas['changeeuros']
    
        );*/
        $json = json_encode($datas);
        //var_dump($json);
        $response = $http->post($url, $json, [
            'headers' => [
                'x-api-key' => $this->florapi['x-api-key'],
                'Content-Type' => 'application/json'
                ]
        ]);
        $results = $response->getJson();
        var_dump($results);
        return $results;
            /*$url = $infoflorapi['url'].'/putAdhs';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'x-api-key: '.$infoflorapi['key'],
                'Content-Type: application/json'
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $json = json_encode($datas);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo curl_error($ch);
                die();
            }
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($http_code == intval(200) or $http_code == intval(201)){
                $arr = json_decode($response, true);
                return $arr;
            }
            else{
                return -1;
            }
        } catch (\Throwable $th) {
            throw $th;
        } finally {
            curl_close($ch);
        }*/
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