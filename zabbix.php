<?php

class Tzabbix {
    var $debug=false;
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
    var $api_key=null;
    var $id=1;
/**
 * Инициализация
 * @param type $debug - true/false
 * @throws Exception
 */    
public function __construct($debug){
    if (! function_exists('curl_init')) {
        throw new Exception('CURL модуль для PHP не установлен!');
    };
    $this->debug=$debug;
    $this->curl_opts=array_replace($this->curl_opts,[CURLOPT_VERBOSE=>$debug]);            
    $this->id=$this->GetRandomId(3);
}
/** Получить случайный идентификатор длинной $n
 * @param type $n
 * @return string
 */
function GetRandomId($n) { // результат - случайная строка из цифр длинной n
	$id = '';
	for ($i = 1; $i <= $n; $i++) {
		$id .= chr(rand(48, 56));
	}
	return $id;
}
/**
 * Установить значение опций CURL
 * @param type $option
 * @param type $value
 */
function SetCurlOpt($option,$value){    
    $this->curl_opts=array_replace($this->curl_opts,[$option=>$value]);            
    if ($this->debug==true){var_dump($this->curl_opts);};
}
/**
 *  Получаем шапку запроса API
 */
function GetInitial(){
 $res=array();   
 $res["jsonrpc"]="2.0";
 $res["id"]=$this->id;
 $res["auth"]=$this->api_key;
 return $res;
}
/**
 * Получение сессионного ключа и id пользователя
 * @param type $url
 * @param type $login
 * @param type $pass
 * @return type
 */    
function Auth($login,$pass){       
    if ($this->debug==true){echo "-Login and get Api key\n";};
    $ret= $this->Execute("user.login", ["user"=>$login,"password"=>$pass]); 
    return $ret;        
} 
/**
 * Выполнить произвольный метод API
 * @param type $method
 * @param type $params
 * @return type
 */
function Execute($method,$params){
    $curl = curl_init();        
    foreach ($this->curl_opts as $opt => $val){
        curl_setopt($curl, $opt, $val);        
    };    
    $header=array('Content-Type:application/json-rpc');    
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    $api_post=$this->GetInitial();    
    $api_post["method"]=$method;
    $api_post["params"]=$params;    
    if ($this->debug==true){var_dump(json_encode($api_post));};
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($api_post));          
    $data = curl_exec($curl);            
    if (curl_errno($curl)) {                                    
          $zz=curl_error($curl);
          $ret=["result"=>false,"message"=>curl_error($curl)];              
    } else {
        if ($this->debug==true){var_dump($data);};
            $ret=json_decode($data);
            $this->api_key=$ret->result;            
            curl_close($curl);
    };    
    return $ret;            
}

};
?>