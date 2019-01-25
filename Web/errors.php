<?php

function return_database_connection_error() {
    header("HTTP/1.0 500 Internal Server Error");
    echo 'Cause: Database Connection';
    exit();
}

function return_database_selection_error() {
    header("HTTP/1.0 500 Internal Server Error");
    echo 'Cause: Database Selection';
    exit();
}

function return_database_query_error($error) {
    header("HTTP/1.0 500 Internal Server Error");
    echo 'Cause: ' . $error;
    exit();
}

function return_hash_match_error() {
    header("HTTP/1.0 403 Forbidden");
    exit();
}

function return_unsupported_http_method_error() {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}

function return_bad_request_error() {
    header("HTTP/1.0 400 Bad Request");
    exit();
}

?>