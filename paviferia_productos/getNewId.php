<?php

    include("../phps/validateSession.php");
    include("../phps/dpaviferia_productos.php");

    $id = getNewIdProductoPaviferia();

    echo $id;