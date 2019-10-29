<?php

class Tzabbix {
    var $auth="";    
    var $curl_opts = array(
        CURLOPT_URL=>"https://zabbix.my.site/api.php",
        CURLOPT_RETURNTRANSFER => true, 
        CURLOPT_SSL_VERIFYPEER => false, // вход по SSL
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_FOLLOWLOCATION => false, // редиректы
        CURLOPT_MAXREDIRS => 10, // максимальное количество редиректов
        CURLOPT_CONNECTTIMEOUT=>5,
        CURLOPT_CONNECTTIMEOUT=>5,
        CURLOPT_VERBOSE=>true,
        //CURLOPT_SSLCERT=>"/home/gavnetadmin/cd_service/cert/noc_noc.crt",
        //CURLOPT_SSLKEY=>"/home/gavnetadmin/cd_service/cert/noc_noc.key",
        CURLOPT_SSLKEYPASSWD=>"",
        CURLOPT_POST=>true,
        CURLOPT_POSTFIELDS=>""
    ); 

public function __construct(){
    if (! function_exists('curl_init')) {
        throw new Exception('CURL модуль для PHP не установлен!');
    }
}
function SetCurlOpt($option,$value){    
    $this->curl_opts=array_replace($this->curl_opts,[$option=>$value]);            
    var_dump($this->curl_opts);
}
/**
 * Получение сессионного ключа и id пользователя
 * @param type $url
 * @param type $login
 * @param type $pass
 * @return type
 */    
function Auth($login,$pass){       
    $curl = curl_init();        
    foreach ($this->curl_opts as $opt => $val){
        curl_setopt($curl, $opt, $val);        
    };
    $header=array('Content-Type:application/json-rpc');    
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);        
    curl_setopt($curl, CURLOPT_POSTFIELDS, '{
    "jsonrpc": "2.0",
    "method": "user.login",
    "params": {
        "user": "'.$login.'",
        "password": "'.$pass.'"
    },
    "id": 1,
    "auth": null
}');          
    $data = curl_exec($curl);    
    var_dump($data);
    
        if (curl_errno($curl)) {              
              $ret=curl_error($curl);
              die($ret);            
        } else {
            return json_decode($data);
            curl_close($curl);
        };    
} 
};
?>

