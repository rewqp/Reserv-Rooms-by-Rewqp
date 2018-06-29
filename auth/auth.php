<?php

    // if (empty($_POST)) {
    //     header("HTTP/1.0 404 Not Found");
    //     echo 'HTTP/1.0 404 Not Found';
    //     exit;
    // }

    //Начинаем сессию
    session_start();
    //Подключаем конфигурационный файл
    include_once ("auth/ldap.php");
    
    // Logout
    if (isset($_GET['logout']))
    {
        if (isset($_SESSION['user_id']))
                {
                unset($_SESSION['user_id']);  
                setcookie('login', '', 0, "/");
                setcookie('password', '', 0, "/");
                header('Location: index.php');
                exit;
        }
    }
    
    //Если пользователь уже аутентифицирован, то перебросить его на страницу main.php
    if (isset($_SESSION['user_id']))
        {
        header('Location: index.php');
        exit;
    }
        
    //Если пользователь не аутентифицирован, то проверить его используя LDAP
    if (isset($_POST['login']) && isset($_POST['password']))
        {
        $username = $_POST['login'];
        $login = $_POST['login'].$domain;
        $password = $_POST['password'];
        //подсоединяемся к LDAP серверу
        $ldap = ldap_connect($ldaphost,$ldapport) or die("Cant connect to LDAP Server");
        //Включаем LDAP протокол версии 3
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    
        if ($ldap)
                {
                // Пытаемся войти в LDAP при помощи введенных логина и пароля
                $bind = ldap_bind($ldap,$login,$password);
    
                if ($bind)
                    {
                    // Проверим, является ли пользователь членом указанной группы.
                    $result = ldap_search($ldap,$base,"(&(memberOf=".$memberof.")(".$filter.$username."))");
                    // Получаем количество результатов предыдущей проверки
                    $result_ent = ldap_get_entries($ldap,$result);
                }
                else
                    {
                    die('Вы ввели неправильный логин или пароль. попробуйте еще раз<br /> <a href="index.php">Вернуться назад</a>');
                }
        }
    
        // Если пользователь найден, то пропускаем его дальше и перебрасываем на main.php
        if ($result_ent['count'] != 0)
                {
                $_SESSION['user_id'] = $login;
                header('Location: main.php');
                exit;
        }
        else
                {
                die('К сожалению, вам доступ закрыт<br /> <a href="index.php">Вернуться назад</a>');
        }
    }
?>