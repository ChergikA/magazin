<?php


//echo '=' . session_save_path()  ;

//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'../sess/');
//session_set_cookie_params(36000 ,"/sess" ); // время жизни - 10 часов , и путь к хранениям сессий
//session_start(); // т.к возможен вариант нескольких пользователей, то данные для конкретной сесии (места)

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', 1);

// будем передавать ч/з сесии
//
//
                 // ч/з global - например подключение к БД
//
 // Вывод ошибок на экран после отладки надо закаментить

/*
 * цепляем вверху каждого файла
 * старт сессий и проверка ошибок
 */


//echo 'asdf';

//!@$ // тут специально допущена ошибка - должен реагировать

/* создать соединение */
//$db = mysql_connect('Localhost','root','111') OR DIE("Нет подключения к SQL");
/* выбрать базу данных. Если произойдет ошибка - вывести ее */
//mysql_select_db("Vivat",$db) or die(mysql_error());
//mysql_query('SET NAMES CP1251');              // это кодировка сайта и базы и рхр



?>

<html>
<head>
<title>КАНЦМАРКЕТ сеть магазинов канцтоваров</title>
<meta name="description" content="">
<meta name="keywords" content="">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<!-- <meta http-equiv="pragma" content= "no-cache"> -->

</head>

<!--
    rows rows="60, *, 60" 1-я строка 60 вторая все остальное 3-я  -60
-->
<!-- -->

<frameset rows="78, *, 35" frameborder="0" framespacing="0" >
  <frame src="header.php" noresize name="header" scrolling="no" > <!-- шапка -->
  <frameset cols="190px, *">  <!-- две колонки -->
    <frame src="leftMenu.php" noresize scrolling="no"> <!-- левая колонка -->
        <frameset rows="120, *">
            <frame src="list_menu.php"  noresize name="top_menu" >  <!-- горизонтальное меню -->
            <frame src="php_info.php" noresize name="content">
         </frameset>
  </frameset>
  <frame src="footer.php" noresize name="footer" >
</frameset>

<noframes>
Для просмотра этого сайта необходимо, чтобы Ваш браузер поддерживал фреймы. Раз Вы видите этот текст, значит Ваш
браузер не поддерживает фреймы. Это потому что либо он у Вас очень старой версии и Вам давно пора его сменить. Либо в
настройках браузера надо включить поддержку фреймов.<br><br>
В браузере Opera надо зайти в меню "Инструменты" --> "Быстрые настройки" --> "Настройки для сайта..." --> "Вид" и там
поставить галочку "Включить фреймы". После этого надо перезагрузить эту страницу.<br><br>
В последних версиях браузеров IE и Mozilla FireFox поддержка фреймов постоянно включена.
</noframes>

</html>