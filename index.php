<?php

require('./futbinCrawler.php');

$futBinCrawler =  new futBinCrawler();
$listaDeJogadores = $futBinCrawler->getListaJogadores();
/* print_r($listaDeJogadores); */
$listaDeJogadoresJSON = json_encode($listaDeJogadores);
echo $listaDeJogadoresJSON;