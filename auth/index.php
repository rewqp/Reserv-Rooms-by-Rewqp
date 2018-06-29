<?php
    // Подключаем файл auth.php 
    include_once ("auth/auth.php");
?>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=koi8-r" />
    <title>Аутентификация пользователей через LDAP</title>
    </head>
 
<?php
// Форма для ввода пароля и логина 
    print '
    <form action="index.php" method="post">
    <table>
        <tr>
                <td>Имя:</td>
                <td><input type="text" name="login" /></td>
        </tr>
        <tr>
                <td>Пароль:</td>
                <td><input type="password" name="password" /></td>
        </tr>
        <tr>
                <td></td>
                <td><input type="submit" value="Авторизироваться" /></td>
        </tr>
    </table>
    </form>
    ';
    echo "<br /><h3>Для авторизации необходимы ваши учетные данные</h3>";
?>