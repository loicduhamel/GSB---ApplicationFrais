<?php
/**
 * Created by PhpStorm.
 * User: ryujjin
 * Date: 26/02/18
 * Time: 19:26
 */

function openDb(){
    $connexion = mysqli_connect("localhost", "root","root","AppliFrais");
    mysqli_set_charset($connexion, "utf8");
    if (mysqli_connect_errno()){
        echo "Connection failed : ".mysqli_connect_error();
        exit();
    }
    return $connexion;
}

function closeDb($connexion){
    mysqli_close($connexion);
}

?>