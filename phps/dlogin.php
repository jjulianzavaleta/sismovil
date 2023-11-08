<?php

function validar_usuario($username, $password){
    if(is_usuario_habilitado_in_vale_or_contrato($username)){
        return validar_active_directory($username, $password);
    }else{
        return false;
    }
}

function is_usuario_habilitado_in_vale_or_contrato($username){

    if( !empty(is_usuario_habilitado_vale($username)) || is_usuario_habilitado_contrato($username) ){
        return true;
    }else{
        return false;
    }
}

function is_usuario_habilitado_vale($username){

    $sql = "select 1
			from vales_usuarioshabilitados vend1
			where vend1.usuario = '$username' and activo = 1 ";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    sqlsrv_close( $link );

    return $data;
}

function is_usuario_habilitado_contrato($username){
    
    $sql = "select 1
	        from contract_usuarioshabilitados vend
			where vend.usuario = '$username' and activo = 1";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    sqlsrv_close( $link );

    return $data;
}

function validar_active_directory($username, $password){

    $server = "10.152.1.4";
    $domain = "CHIMUASA";
    $port   = 389;
    $dc		= "dc=chimuasa, dc=com";

    $ldap_connection = ldap_connect($server, $port);

    if (! $ldap_connection){
        echo '<p>LDAP SERVER CONNECTION FAILED</p>';
        return false;
    }

    // Help talking to AD
    ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0);

    $password  = html_entity_decode($password);
    $ldap_bind = ldap_bind($ldap_connection, $username."@".$domain, $password);

    if (! $ldap_bind){
        return false;
    }

    //get data from AD
    $filter = "(sAMAccountName=" . $username . ")";
    $attr = array("givenname","company","department","description","sn","title");
    $result = ldap_search($ldap_connection, $dc, $filter, $attr) or exit("Unable to search LDAP server");
    $entries = ldap_get_entries($ldap_connection, $result);

    $givenname   = $entries[0]['givenname'][0];
    $company     = $entries[0]['company'][0];
    $department  = $entries[0]['department'][0];
    $description = $entries[0]['description'][0];
    $sn 		 = $entries[0]['sn'][0];
    $title       = $entries[0]['title'][0];
    $ad_data = array(
        "givenname" 	=> $givenname,
        "company"		=> $company,
        "department" 	=> $department,
        "description" 	=> $description,
        "lastname"		=> $sn,
        "title" 		=> $title
    );

    ldap_unbind ( $ldap_connection );
    return upsertUserFromActiveDirectoryToLocalDB($username,$ad_data,"@".strtolower($domain));
}

function upsertUserFromActiveDirectoryToLocalDB($username,$ad_data,$domain){

    $password   = "";
    $nocheckpassword = true;
    $local_user = getUsuarioByUsernameAndPassword($username.$domain,$password,$nocheckpassword);

    //user already exists on local database
    if(!empty($local_user)){
        $res = updateDataUserActiveDirectory($local_user['id'],$ad_data['givenname'],$ad_data['lastname'],$ad_data['department'],$ad_data['title'],$ad_data['description']);
        if($res ===true){
            return $local_user;
        }else{
            return false;
        }

    }else{//user doesn't exists on local database
        $local_user = registrarUsuario(  $username.$domain, "", $ad_data['givenname'], $ad_data['lastname'], 1,0,0,0,0,$ad_data['department'],$ad_data['title'],$ad_data['description']);

        if($local_user === false){
            return false;
        }else{
            return getUsuarioByUsernameAndPassword($username.$domain,$password);
        }
    }
}

function getUsuarioByUsernameAndPassword($user,$password,$nocheckpassword=false){

    $password_sql1 = " adm.password = '$password' and ";
    if($nocheckpassword){
        $password_sql1 = "";
    }

    $sql = "select id, usuario,permission_data, permission_pedidos, permission_paviferia, 1 as isadmin, manageusers, departamento,puesto,puesto2
            from admin adm
            where ".$password_sql1." adm.usuario = '$user' and adm.activo = 1";
    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    sqlsrv_close( $link );

    return $data;
}

function updateDataUserActiveDirectory($id,$nombres,$apellidos,$departamento,$puesto,$puesto2){

    $nombres = mb_strtoupper($nombres, 'UTF-8');
    $apellidos = mb_strtoupper($apellidos, 'UTF-8');

    $sql = "update admin set nombres = '$nombres', apellidos = '$apellidos',
                             departamento = '$departamento', puesto = '$puesto',
                             puesto2 = '$puesto2'
            where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    sqlsrv_close( $link );

    if($res === false)
        return false;
    else
        return true;
}

function registrarUsuario(  $usuario, $password, $nombres, $apellidos, $estado,
                           $permission_data,$permission_pedidos,$permission_paviferia,
                           $manageusers,$departamento="",$puesto="",$puesto2=""){

    $nombres = mb_strtoupper($nombres, 'UTF-8');
    $apellidos = mb_strtoupper($apellidos, 'UTF-8');

    $sql = "insert into admin(usuario,password,nombres,apellidos,activo,permission_data,permission_pedidos,permission_paviferia,manageusers,departamento,puesto,puesto2)
            values
                                ('$usuario','$password',
                                '$nombres','$apellidos',$estado,
                                $permission_data,$permission_pedidos,$permission_paviferia,$manageusers,'$departamento','$puesto','$puesto2')";
    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    sqlsrv_close( $link );

    if($res === false)
        return false;
    else
        return true;
}