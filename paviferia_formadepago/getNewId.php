<?php

    include("../phps/validateSession.php");
    include("../phps/dpaviferia_formapago.php");

    $id = getNewIdFormaPago();

    echo $id;