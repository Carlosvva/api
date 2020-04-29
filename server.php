<?php
require_once('server.php');
error_reporting(E_ALL & ~E_NOTICE);
    $data;
    $flag1 = false;
    $flag2 = false;
    $flag3 = false;
    $search_val = $_GET['search_val'];


    // itunes.apple.com
    $contentone = file_get_contents('https://itunes.apple.com/search?term='.$search_val);
    $jsonone = json_decode($contentone, true);
    // print_r($jsonone);
    display($jsonone['results'], 'https://km.support.apple.com/resources/sites/APPLE/content/live/IMAGES/0/IM749/en_US/itunes-macos-icon-240.png');


    // api.tvmaze.com
    $contenttwo = file_get_contents('http://api.tvmaze.com/search/shows?q='.$search_val);
    $jsontwo = json_decode($contenttwo, true);
    // print_r($contenttwo);
    display($jsontwo, 'http://static.tvmaze.com/images/tvm-header-logo.png');


    // api.worldbank.org
    $contentthree = file_get_contents('http://api.worldbank.org/v2/country/'.$search_val.'?format=json');
    $jsonthree = json_decode($contentthree, true);
    // print_r($jsonthree);
    display($jsonthree, 'https://www.worldbank.org/content/dam/wbr/logo/logo-wb-header-en.svg');
    
    function display($response, $url) {
        // print_r($response);
        if($url === 'https://km.support.apple.com/resources/sites/APPLE/content/live/IMAGES/0/IM749/en_US/itunes-macos-icon-240.png') {
            for ($i = 1; $i <count($response); $i++) {
                if(isset($response[$i]['trackViewUrl'])) {
                    $GLOBALS['data'][$response[$i]['artistName']] = [$url, $response[$i]['trackViewUrl']];
                } else {
                    $GLOBALS['data'][$response[$i]['artistName']] = [$url, '#'];
                }
            }
            $GLOBALS['flag1'] = true;
        } else {
            $data;
        }
        if($url === 'http://static.tvmaze.com/images/tvm-header-logo.png') {
            for ($i = 1; $i <count($response); $i++) {
                $GLOBALS['data'][$response[$i]['show']['name']] = [$url, $response[$i]['show']['url']];
            }
            $GLOBALS['flag2'] = true;
        } else {
            $data;
        }
        if($url === 'https://www.worldbank.org/content/dam/wbr/logo/logo-wb-header-en.svg') {
            if(count($response) != 1){
                $GLOBALS['data'][$response[1][0]['name']] = [$url, '#'];
            }
            $GLOBALS['flag3'] = true;
        } else {
            $data;
        }
        if($GLOBALS['flag1'] && $GLOBALS['flag2'] && $GLOBALS['flag3']) {
            echo json_encode($GLOBALS['data']);
        }
        if ($GLOBALS['data'] == null || "") {
            echo "no hay resoltados";
        }
    }
?>