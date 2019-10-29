<?php

include_once 'zabbix.php';

$url="https://my_zabbix.ru/api_jsonrpc1.php";
$login="weoriufeowiuf";
$password="owerierfoiewroi";

@include_once 'config.php';

$zab=new Tzabbix();
$zab->SetCurlOpt(CURLOPT_URL,$url);
$zab->Auth($login, $password);

?>