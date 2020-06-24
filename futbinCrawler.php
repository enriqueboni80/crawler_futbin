<?php

require "vendor/autoload.php";

use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

/*
Crawler de jogador do Futbin
*/

class futBinCrawler
{
    private $startPage = 1;
    private $endPage = 3;
    private $listaJogadores = array();

    function __construct()
    {
        for ($i = $this->startPage; $i < $this->endPage; $i++) {
            $this->getContent('https://www.futbin.com/20/players?page=' . $i);
        }
    }

    function getContent($url)
    {
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36'
            ]
        ]);
        $html = $client->request("GET", $url)->getBody();
        $dom = HtmlDomParser::str_get_html($html);

        $results = $dom->find('[class^=player_tr_]');
        foreach ($results as $result) {
            $jogador = new stdClass;
            $jogador->name = $result->find('[class=player_name_players_table]', 0)->plaintext;
            $jogador->rat = $result->find('td', 1)->plaintext;
            $jogador->pos = $result->find('td', 2)->plaintext;
            $jogador->ver = $result->find('td', 3)->plaintext;
            $jogador->value = $result->find('td', 4)->plaintext;
            $jogador->img = $this->getImgPlayerUrl($result->find('img', 0));
            array_push($this->listaJogadores, $jogador);
        }
    }

    function getImgPlayerUrl($imgElement)
    {
        $url = explode("data-original=", $imgElement)[1];
        return str_replace(['>', '"', ' '], '', $url);
    }

    function getListaJogadores()
    {
        return $this->listaJogadores;
    }
}
