<?php
class Properties {

    public function dbReader() {
        $ip = "mysql15.citynetwork.se";
        $user = "135026-sj27698";
        $password = "potatissallad";
        $dbName = "135026-bildblogg";

        $db = new mysqli($ip, $user, $password, $dbName);
        return $db;
    }


}


