<?php

    include("../phps/validateSession.php");
    include("../phps/dpaviferia_grupocliente.php");

    $id = getNewIdGrupoPaviferiaCliente();

    echo $id;