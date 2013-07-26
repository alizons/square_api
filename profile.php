<?php
    $mongo = new Mongo('mongodb://testuser01:testpassword01@ds037368.mongolab.com:37368/testdb01');
    $db = $mongo->selectDB('testdb01');
    $collection = $db->profile;
    $collection->remove();
    for ($i = 1; $i <= 5; ++$i) {
        $pid = pcntl_fork();
        if (!$pid) {
            $timeArray = requestApi('http://localhost/index.php/activity/11111', 'GET', $i);
            $timeArray = requestApi('http://localhost/index.php/user/1', 'GET', $i);
            $timeArray = requestApi('http://localhost/index.php/track/sample/1/movie', 'PUT', $i);
            exit($i);
        }
    }

    while (pcntl_waitpid(0, $status) != -1) {
        $status = pcntl_wexitstatus($status);
    }

    handleResults($collection, 'http://localhost/index.php/activity/11111');
    handleResults($collection, 'http://localhost/index.php/user/1');
    handleResults($collection, 'http://localhost/index.php/track/sample/1/movie');


function handleResults($collection, $url) {
    echo 'Profiling for '.$url.' API :' . " \r\n";

    $cursor = $collection->find(array('url' => $url));
    $arr = array();
    foreach ($cursor as $doc) {
        $arr[] = $doc['time'];
    }

    echo 'Min request time: ' . min($arr) . "\r\n";
    echo 'Max request time: ' . max($arr) . "\r\n";
    echo 'Average request time: ' . array_sum($arr) / count($arr) . "\r\n";
}


function requestApi($url, $method, $childId) {
    $timeArray = array();
    for ($k = 1; $k <= 3; ++$k) {
        $time1 = time();
        createRequest($url, $method);
        $time2 = time();
        $timeArray[] = $time2 - $time1;
    }
    putInDb($childId, $timeArray, $url);
    return $timeArray;
}

function putInDb($childId, $timeArray, $url) {
    $mongo = new Mongo('mongodb://testuser01:testpassword01@ds037368.mongolab.com:37368/testdb01');
    $db = $mongo->selectDB('testdb01');
    $collection = $db->profile;

    foreach ($timeArray as $key => $value) {
        $profile = array();
        $profile['url'] = $url;
        $profile['time'] = $value;
        $collection->save($profile);
    }
    return true;
}

function createRequest($url, $method) {
    $curl = curl_init();
    if ($method == 'PUT') {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_PUT, 1);
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($curl);
    //$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    //return $http_status;

}

?>
