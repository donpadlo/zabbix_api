<?php

include_once 'zabbix.php';

$url="https://my_zabbix.ru/api_jsonrpc1.php";
$login="weoriufeowiuf";
$password="owerierfoiewroi";

@include_once 'config.php';

// инициализация класса true/false - вывод в режиме debug
$zab=new Tzabbix(false);
$zab->SetCurlOpt(CURLOPT_URL,$url);
echo "-получаем api-key\n";
$res=$zab->Auth($login, $password);
if (isset($res->result)==true){
    if ($res->result!==false){        
        // cсписок хостов
        $res=$zab->Execute("host.get", ["output"=>["hostid","host"],"selectInterfaces"=>["interfaceid","ip"]]);
        var_dump($res);
        // список активных триггеров
        $res=$zab->Execute("trigger.get", ["only_true"=>1,"skipDependent"=>1,"monitored"=>1,"active"=>1,"output"=>'extend',"expandDescription"=>1, "min_severity"=>"priority"]);
        var_dump($res);
        
    } else {
        var_dump($res);        
        die(-1);
    };    
} else {
  echo "--не понятная ошибка. попробуйте переключить в debug=true";  
};    

?>