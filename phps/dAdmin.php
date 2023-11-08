<?php

function getAdminById($id){

    $sql = "select * from admin adm where adm.id = $id ";

    $link = conectarBD();
    $data = queryBD($sql,$link);

    return $data;

}



