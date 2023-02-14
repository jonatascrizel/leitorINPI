<?php

function getFileInfo($url){
    $ch = curl_init($url);
    curl_setopt( $ch, CURLOPT_NOBODY, true );
    curl_setopt( $ch, CURLOPT_HEADER, false );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
    curl_exec( $ch );

    $headerInfo = curl_getinfo( $ch );
    curl_close( $ch );

    return $headerInfo;
}

function fileDownload($url, $destination){
    $fp = fopen ($destination, 'w+');
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt( $ch, CURLOPT_FILE, $fp );
    curl_exec( $ch );
    curl_close( $ch );
    fclose( $fp );

    if (filesize($destination) > 0) return true;
}

