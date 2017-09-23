<?php
# post 测试
function arrayToXml($data) {
    $xml = '<xml>';
    foreach($data as $key => $value){
        $xml .= "\n<{$key}>{$value}</{$key}>";
    }
    $xml .= '</xml>';
    return $xml;
}
function http_request($url, $data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
$data = ['test'=>'value'];
print_r(http_request('http://api.com',arrayToXml($data)));
