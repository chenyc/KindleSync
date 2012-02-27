<?php
function shortenSinaUrl($long_url){
 $apiKey='2734593680';
 $apiUrl='http://api.t.sina.com.cn/short_url/shorten.json?source='.$apiKey.'&url_long='.$long_url;
 $curlObj = curl_init();
 curl_setopt($curlObj, CURLOPT_URL, $apiUrl);
 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
 curl_setopt($curlObj, CURLOPT_HEADER, 0);
 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
 $response = curl_exec($curlObj);
 curl_close($curlObj);
 
 $json = json_decode($response);
 return $json[0]->url_short;
}
?>