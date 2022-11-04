<?php

    class ApiView{

        function response($data, $status = 200){

            header("Content-Type: application/json");
            header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));

            echo json_encode($data);
        }

        private function _requestStatus($code){
             $status = array(
                200 => "OK",
                201 => "Created",
                400 => "Bad request",
                403 => "Forbidden",
                404 => "Not found",
                405 => "Not Allowed",
                500 => "Internal Server Error"
                );
            return (isset($status[$code])) ? $status[$code] : $status[500];
        }
    }