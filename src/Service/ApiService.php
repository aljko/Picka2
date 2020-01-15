<?php


namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;


class ApiService
{
    const API_URL = 'https://superheroapi.com/api/';
    const API_TOKEN = '10215133638405581';


    public function requestApi($id, $table = '')
    {
        if ($table) {
            $table = '/'.$table;
        }

        $client = HttpClient::create();
        $response = $client->request('GET', self::API_URL.self::API_TOKEN.'/'.$id.$table);

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = $response->toArray();

            foreach ($content as $k => $v) {
                $content[str_replace('-','',$k)] = $content[$k];
                if (is_array($v)) {
                    foreach($v as $ke => $va) {
                        $v[str_replace('-','',$ke)] = $v[$ke];
                    }
                }
            }

            return $content;
        }
    }
}
