<?php

    // if (empty($_POST)) {
    //     header("HTTP/1.0 404 Not Found");
    //     echo 'HTTP/1.0 404 Not Found';
    //     exit;
    // }

    //ip адрес или название сервера ldap(AD)
    $ldaphost = "192.168.5.2"; //127.0.0.1

    //Порт подключения
    //$ldapport = "389";

    //Полный путь к группе которой должен принадлежать человек, что бы пройти аутентификацию. 
    $memberof = "cn=Users,dc=bm,dc=local"; 

    //Откуда начинаем искать 
    $base = "ou=corp,dc=eddnet,dc=org";

    //Собственно говоря фильтр по которому будем аутентифицировать пользователя
    $filter = "sAMAccountName=";

    //для авторизации через AD
    $domain = "@bm.local"; //admin_bm@bm.local
    
?>