<?php

    include('required-table-values/Run.php');

    $data = array(
        'database_host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'test'
    );

    new Run($data);

?>