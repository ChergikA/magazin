<?php

//***********************************  baba-jaga@i.ua  **************************************
// выравниваем кодировку
function cnv($str){
    $str = iconv("UTF-8", "windows-1251//TRANSLIT", $str);
    
    //$str = mb_convert_encoding($str, "cp1251");
    return $str ;
}

//***********************************  baba-jaga@i.ua  **************************************
// выравниваем кодировку при отправке
function cnv_($str){
    $str = iconv( "cp1251", "UTF-8",  $str);
    return $str ;
}

//***********************************  baba-jaga@i.ua  ********************************************************
// при приеме почты с именами файлов на русском, возникает проблема. В базу пишется одно имя а в 
// файл под другим. напр Транспортныи?2.xls и Транспортны?.xls
function translit($s) {
  $s = (string) $s; // преобразуем в строковое значение
  $s = strip_tags($s); // убираем HTML-теги
  $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
  $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
  $s = trim($s); // убираем пробелы в начале и конце строки
  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
  $s = strtr($s, array('а'=>'a',
      'б'=>'b',
      'в'=>'v',
      'г'=>'g',
      'д'=>'d',
      'е'=>'e',
      'ё'=>'e',
      'ж'=>'j',
      'з'=>'z',
      'и'=>'i',
      'й'=>'y',
      'к'=>'k',
      'л'=>'l',
      'м'=>'m',
      'н'=>'n',
      'о'=>'o',
      'п'=>'p',
      'р'=>'r',
      'с'=>'s',
      'т'=>'t',
      'у'=>'u',
      'ф'=>'f',
      'х'=>'h',
      'ц'=>'c',
      'ч'=>'ch',
      'ш'=>'sh',
      'щ'=>'shch',
      'ы'=>'y',
      'э'=>'e',
      'ю'=>'yu',
      'я'=>'ya',
      'ъ'=>'',
      'ь'=>'',
      '?'=>''));
  //$s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
  $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
  return $s; // возвращаем результат
}

//***********************************  baba-jaga@i.ua  ********************************************************
// отдаем имя юзера
function name_user(){
    if( isset( $_SESSION['txt_user'] ) ) {
        return  $_SESSION['txt_user'] ;
    }
        else {
            return "" ;
    }
}

//***********************************  baba-jaga@i.ua  ********************************************************
// отдаем ид юзера
function user_id(){
global $db;
    $nm_user = name_user();
    if( trim( $nm_user) == '' ) {
        echo 'не найде пользователь БД. Продолжение невозможно';
        return 0 ;
    }

   $txt_sql= "SELECT * FROM `users` WHERE `login` = '" . $nm_user . "' ";
   $sql     = mysql_query($txt_sql, $db);
   $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
   $id_user = $s_arr['id'];

   return $id_user ;

}

//***********************************  baba-jaga@i.ua  ********************************************************
// для удобства (историческое)
function id_user(){

   $id_user = user_id() ;

   return $id_user ;

}


//***********************************  baba-jaga@i.ua  ********************************************************
// к нему привяжем все настройки касаемо организации
// для Канцлера свои, для Укртехпрома свои и т.д. , Канцмаркету свои
// Kancler, UTP, Kancmarket
function kod_organization(){

    if( ! isset( $_SESSION['kod_organization'] ) ) {

        $myconst = new cls_my_const();
        $_SESSION['kod_organization'] = $myconst->kod_organization;
    }
     return  $_SESSION['kod_organization'] ;
}

//***********************************  baba-jaga@i.ua  ********************************************************
// если нету в сессии то всунем туда по умолчанию
function id_firm(){

    if( ! isset( $_SESSION['id_firm'] ) ) {

        $myconst = new cls_my_const();
        $_SESSION['id_firm'] = $myconst->id_firma_def;
    }
     return  $_SESSION['id_firm'] ;
}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем наименование товара от его кода
function nm_tovar($kod_tov){

    global $db;
    $nm_tov = "Наименование товара ";

    $txt_sql = "SELECT `Tovar` FROM `Tovar` WHERE `Kod` =  '" . $kod_tov . "'" ;
    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {

                $nm_tov =  $srt_arr[0]  ;
    }
    return $nm_tov;

}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем цену товара от его кода
function cena_tovar($kod_tov) {
    global $db;

    $txt_sql = "SELECT `Price` FROM `Tovar` WHERE `Kod` = '" . $kod_tov . "' ";
    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
    if ($s_arr['Price'] == NULL)
        return 0;
    return $s_arr['Price'];
}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем дисконт клиента из сесии
function diskont_klienta() {
    // был ли ввежен сейчас или ранее штрихкод
    if( isset( $_SESSION['diskont_klienta']) ) {
            $h_kod = $_SESSION['diskont_klienta'] ;
    }else $h_kod = 'дисконт' ;
return $h_kod ;
}


//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем имя клиента от его дисконта
function nm_klient($kod_klient){

    global $db;
    $nm_klient = "Наименование клиента";

    $txt_sql = "SELECT `name_klient` FROM `Klient` WHERE `diskont` =  '" . $kod_klient . "'" ;
    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {

                $nm_klient =  $srt_arr[0]  ;
    }
    return $nm_klient;

}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем скидку клиента который сидит в сессиии
function skidka_klienta(){
    global $db;
    $hk_klienta = diskont_klienta() ;
    if( trim($hk_klienta) == '' or trim($hk_klienta) == 'дисконт' ){
        //echo 'не верно выбран клиент, продолжение невозможно';
        return 0;
    }


    $txt_sql = "SELECT `Skidka` FROM `Klient` WHERE `diskont` =  '" . $hk_klienta . "'" ;
    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
    $s_klient = $s_arr['Skidka'];
    return $s_klient;

}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем ID клиента от его дисконта
function id_klient(){

    global $db;
    $hk_klienta = diskont_klienta() ;
    if( trim($hk_klienta) == '' or trim($hk_klienta) == 'дисконт' ){
        echo 'не верно выбран клиент, продолжение невозможно';
        return 0;
    }


    $txt_sql = "SELECT `id_` FROM `Klient` WHERE `diskont` =  '" . $hk_klienta . "'" ;
    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
    $id_klient = $s_arr['id_'];
    return $id_klient;

}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем ID статуса документа от имени статуса
function id_status( $nm_status ){
  
  $txt_sql = "SELECT `idStatus` FROM `StatusDoc` WHERE `nameStatus` = '" . $nm_status  . "'";
  $s_arr = cls_my_sql::const_sql($txt_sql);
  $id_status = $s_arr['idStatus'];
  return $id_status;


}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем ID товара от его ш-кода или кода1с
function id_tovar( $hkod='' , $kod1c='' ){
  global $db;
  $ok=FALSE; $id='';
  if($hkod != ''){
      $txt_sql='SELECT `id_tovar` FROM `Tovar` WHERE `Kod`="' . trim($hkod) .'"' ;
      $ok=TRUE;
  }
  if($kod1c != ''){
      $txt_sql='SELECT `id_tovar` FROM `Tovar` WHERE `Kod1C`="'.trim($kod1c).'"';
      $ok=TRUE;
  }

  if($ok){
    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_row($sql);
    if($s_arr[0]!=NULL) $id = $s_arr[0];
  }
  return $id;


}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем ID вида документа от его кода (краткого имени)
function id_viddoc( $kod_viddoc ){
  global $db;
  $txt_sql = "SELECT `id` FROM `VidDoc` WHERE `Kod` LIKE '" . $kod_viddoc  . "' ";
  $sql     = mysql_query($txt_sql, $db);
  $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
  if($s_arr['id']==NULL)      echo 'нет вида документа: '. $kod_viddoc ;
  $id = $s_arr['id'];
  return $id;


}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем что за документ (его вид) от ID документа
function viddoc( $iddoc ){
  global $db;
  $txt_sql = 'SELECT `VidDoc`.`Kod`
            FROM `DocHd`
             LEFT JOIN `km`.`VidDoc` ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id`
            WHERE (`DocHd`.`id` ='.$iddoc.')';


  $sql     = mysql_query($txt_sql, $db);
  $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
  if($s_arr['Kod']==NULL)      return '' ;

  return $s_arr['Kod'];


}

//***********************************  baba-jaga@i.ua  ********************************************************
// создаем новый док в базе и возвращаем его ID
function add_newdoc($id_firm, $kod_vid_doc) {
    global $db;

   //echo '' . $kod_vid_doc ;
    $id_vid_doc = id_viddoc($kod_vid_doc) ;

    if($kod_vid_doc == 'Чек' or $kod_vid_doc == 'Возврат' or $kod_vid_doc == 'Счет' ){
        $id_klient = id_klient();
    } else {
        $myconst = new cls_my_const();
        $id_klient = $myconst->id_klient_def;
    }

    $id_status = id_status('новый');

    $id_user = user_id();

    $skidka = skidka_klienta();

    //номер документа
    if($kod_vid_doc == 'Счет'){ // помесячная нумерация и номера могут меняться
       $txt_sql = "SELECT count( nomDoc ) FROM `DocHd`
                 WHERE `DataDoc`>'" . date('Y-m') . "-00' AND `VidDoc_id` = '" . $id_vid_doc ."'";



    }else{                     // ежедневная нумерация
        $txt_sql = "SELECT nomDoc FROM `DocHd`"
            . " WHERE `DataDoc`='" . date('Y-m-d') . "' and `VidDoc_id` = '" . $id_vid_doc
            . "' ORDER BY `DocHd`.`id` DESC";
    }

    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_array($sql  , MYSQL_BOTH);
    $nomdoc  = $s_arr[0];
    if ($nomdoc == NULL) $nomdoc  = 0;
    $nomdoc  = $nomdoc + 1;

    $txt_sql = "INSERT INTO `DocHd` (`VidDoc_id`  , `nomDoc`  , `statusDoc`         , `Klient_id`     , `users_id`    , `firms_id`    , `DataDoc`, `timedoc`, `SumDoc`, `SkidkaProcent`)
                     VALUES ('" . $id_vid_doc . "','" . $nomdoc . "','" . $id_status . "', '" . $id_klient . "', '" . $id_user . "', '" . $id_firm . "', '" . date('Y-m-d') . "', '" . date('H:i:s') . "',  '0', '" . $skidka . "');";

    $sql = mysql_query($txt_sql, $db);
    if ($sql) {
        $id_doc = mysql_insert_id(); // возвращает id только что записаннной строки
    }
    else {
        echo '<h1>Новый документ не создан. ОШИБКА</h1>';
        $id_doc = -1;
    }
    return $id_doc;
}

//***********************************  baba-jaga@i.ua  ********************************************************
// удаление позиции документа просто обнулим количество
function delstrdoc( $id_doc , $id_str ){
 global $db;

    if (write_doc($id_doc)) { //, `Kvo2` = '0'
        $txt_sql = " UPDATE `DocTab` SET `Kvo` = '0'   WHERE `id` = '" . $id_str . "' ;";
        $sql     = mysql_query($txt_sql, $db);

        savesumdoc($id_doc);
    }

}

//***********************************  baba-jaga@i.ua  ********************************************************
// пересчитываем скидку документа во всех его строках
function reskidkadoc($id_doc, $newsk, $flgopt=0){
    global $db;

       $txt_sql = "UPDATE `DocHd` SET `SkidkaProcent` = '" . $newsk . "',
                                       `flg_optPrice`  = '" . $flgopt . "'
                    WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
        $sql     = mysql_query($txt_sql, $db);

        $txt_sql = " UPDATE `DocTab` SET `Skidka` = '" . $newsk . "', `timeShtamp` = `timeShtamp`   WHERE `DocHd_id` = '" . $id_doc . "' ;";
        $sql     = mysql_query($txt_sql, $db);
}

//***********************************  baba-jaga@i.ua  ********************************************************
// меняем цену в доке на розн или опт для чеков сугубо
function recenadoc($id_doc, $flgopt) {
    global $db;
    if ($flgopt == 1) {  //оптовые цены
        reskidkadoc($id_doc, 0, 1);
        $txt_sql = 'SELECT `DocTab`.`id`, `Tovar`.`PriceOpt`, `DocTab`.`timeShtamp` 
                        FROM `DocTab`
                        LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar`
                      WHERE (`DocTab`.`DocHd_id` = ' . $id_doc . ')';
    } else {  //розничные цены

        $txt_sql = 'SELECT `DocTab`.`id`, `Tovar`.`Price`, `DocTab`.`timeShtamp`
                        FROM `DocTab`
                        LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar`
                      WHERE (`DocTab`.`DocHd_id` = ' . $id_doc . ')';
    }

    $sql = mysql_query($txt_sql, $db);

    while ($srt_arr = mysql_fetch_row($sql)) {
        $txt_upd = " UPDATE `DocTab` SET `Cena` = '" . $srt_arr[1] . "' , `timeShtamp` = '" . $srt_arr[2] . "'   WHERE `id` = '" . $srt_arr[0] . "' ;";
        $sqlupd  = mysql_query($txt_upd, $db);
    }
    $txt_upd = " UPDATE `DocHd` SET `flg_optPrice`=".$flgopt." WHERE `id` = '" . $id_doc . "' ;";
    $sqlupd  = mysql_query($txt_upd, $db);

}

//***********************************  baba-jaga@i.ua  ********************************************************
// пересчитываем  сумму документа, округляем если надо, вычисляем общую скидку по документу
function savesumdoc($id_doc) {
// вызывается при изменении данных таб части дока (upd_tabdoc())
//echo 'shet='.viddoc($id_doc);
    if (viddoc($id_doc) == 'Чек') {
        
        $txt_sql = "SELECT Sum( `DocTab`.`Kvo` * `Tovar`.`Price`) as SumRozn
           FROM `Tovar`
           LEFT JOIN `DocTab` ON `Tovar`.`id_tovar` = `DocTab`.`Tovar_id`
           WHERE (`DocTab`.`DocHd_id` = '" . $id_doc . "') ";
        $s_arr = cls_my_sql::const_sql($txt_sql);
        $sum_rozn = $s_arr['SumRozn'];
        
        if (cls_set::get_parametr('priceList', 'cenaOpt') == 1) { // используем оптовую колонку цен
            // проверка и если надо замена розницы на опт и наоборот
            $cnst = new cls_my_const();
            $min_opt = $cnst->min_sum_opt;
            // провера на опт и цены либо розн либо опт

            $txt_sql = "SELECT `flg_optPrice` FROM `DocHd` WHERE `id`= " . $id_doc;
            $s_arr = cls_my_sql::const_sql($txt_sql);
            $flg_opt = $s_arr['flg_optPrice'];

            // echo  '<br> flg=' . $flg_opt . '; sumdoc=' . $sumdoc . ' ; minopt=' . $min_opt . '<br>'  ;
            if ($sum_rozn < $min_opt) { // это розница
                if ($flg_opt == 1)
                    recenadoc($id_doc, 0);
            }else {                   // это опт
                if ($flg_opt == 0)
                    recenadoc($id_doc, 1);
            }
        }elseif (cls_set::get_parametr('priceList', 'skidkaOtSum') == 1  ) { // используем градацию скидок
            
            $txt_sql =  "SELECT `tip_skidki`,`SkidkaProcent` FROM `DocHd` WHERE `id`='$id_doc'";
            $s_arr = cls_my_sql::const_sql( $txt_sql );
            $skidka = $s_arr['SkidkaProcent'];
            If( $s_arr['tip_skidki'] == 'Накопительная' ){
            
                $do=0; $nom_grd = 0; $skidka =0;
                while ( $sum_rozn>$do ){
                   $nom_grd++; 
                  $nm_parametr = 'skidkaOtDo' . $nom_grd;
                  $do = cls_set::get_svojstvo('priceList', $nm_parametr, 'do');
                  $skidka = cls_set::get_parametr('priceList', $nm_parametr);

                 }
            }

            //$skida_v_doc = $s_arr['SkidkaProcent'];
            if ($skida_v_doc != $skidka ) { // скидка поменялась, пересчитываем
               reskidkadoc($id_doc, $skidka);
            }

        }
    }

    $txt_sql = "SELECT sum( `Kvo` * `Cena` * (1-`Skidka` / 100 ) ) as sum FROM `DocTab` WHERE `DocHd_id` = " . $id_doc;
    $s_arr = cls_my_sql::const_sql($txt_sql);
    $sumdoc = $s_arr['sum'];
    if ($sumdoc == NULL)
        $sumdoc = 0;

    // округляем до 1 копеек
    $sumdoc = round($sumdoc, 2);

    //получим усредненную скидку по доку  на случай разной скидки в строках
    $sk = skidkadoc($id_doc);

    $txt_sql = "UPDATE `DocHd` SET `SumDoc` = '" . $sumdoc . "',
                                `SkidkaProcent` = '" . $sk . "' WHERE `DocHd`.`id` = " . $id_doc;
    cls_my_sql::run_sql($txt_sql);
}

//***********************************  baba-jaga@i.ua  ********************************************************
//добавляем или редактим строку  документа
function upd_tabdoc($id_doc, $kod_tovara, $cena, $kvo, $skidka, $cena2, $kvo2, $tipdoc = '' ) {

    $ok = ""; //возвращаем текст ошибки

    if (!write_doc($id_doc))
        return 'Документ только для просмотра';

    // найдем товар сначала по штрих коду, если неудачно, то поищем по коду 1С (надо для вх приходников)
    $txt_sql = "SELECT `id_tovar` FROM `Tovar` WHERE `Kod` = '" . $kod_tovara . "' ";
    $s_arr   = cls_my_sql::const_sql($txt_sql); 
    if ($s_arr['id_tovar'] == NULL){
        $txt_sql = "SELECT `id_tovar` FROM `Tovar` WHERE `Kod1C` = '" . $kod_tovara . "' ";
        $s_arr2   = cls_my_sql::const_sql($txt_sql);
        if ($s_arr2['id_tovar'] == NULL) return " нет в БД товара с кодом: " . $kod_tovara;
        $id_tov  = $s_arr2['id_tovar'];
    }else {
        $id_tov  = $s_arr['id_tovar'];
    }

    // если в доке есть уже записи с таким товаром, то обновим иначе добавим

    $txt_sql = "SELECT `id` FROM `DocTab` WHERE `DocHd_id` = " . $id_doc . " AND `Tovar_id` = " . $id_tov;
    $s_arr   = cls_my_sql::const_sql($txt_sql);
    if ($s_arr['id'] == NULL) { // новая строка
        // номер строки
        $txt_sql = "SELECT max(nomstr)+1 as newNom FROM `DocTab`"
                . " WHERE `DocHd_id`='" . $id_doc . "'";
        $s_arr   = cls_my_sql::const_sql($txt_sql);
        $nomstr  = $s_arr['newNom'];
        if ($nomstr == NULL)
            $nomstr  = 1;

        $txt_sql = "INSERT INTO `DocTab` ( `DocHd_id`, `Tovar_id`,  `nomstr`, `Cena`, `Kvo`, `Skidka`, `Kvo2`, `Cena2`)
            VALUES ('" . $id_doc . "', '" . $id_tov . "', '" . $nomstr . "',  '" . $cena . "', '" . $kvo . "', '" . $skidka . "', '" . $kvo2 . "', '" . $cena2 . "');";
    }else { // обновляем строку кво прибавляем а цены в зависимости от типа документа
        $idstr   = $s_arr['id'];
        $txt_sql = "SELECT `Kvo`, `Kvo2` , `Cena`, `Skidka` FROM `DocTab` WHERE `id` = " . $idstr;
        $s_arr   = cls_my_sql::const_sql($txt_sql);
            $oldkvo  = $s_arr['Kvo'];
            $oldkvo2 = $s_arr['Kvo2'];
            $kvo     = $kvo + $oldkvo;
            //$kvo2    = $kvo2 + $oldkvo2;


        if ($tipdoc == '') {
            $kvo2    = $kvo2 + $oldkvo2; // кроме переучета

        } elseif ($tipdoc  == 'Возврат') { // возврат клиента по той цене по которой брал
            $cena   = $s_arr['Cena'];
            $cena2  = $cena;
            $skidka = $s_arr['Skidka'];
            $kvo2    = $kvo2 + $oldkvo2;
        }elseif ($tipdoc  == 'Переучет') { // возврат клиента по той цене по которой брал


        }


        $txt_sql = "UPDATE `DocTab` SET `Cena` = '" . $cena
                . "', `Kvo` = '" . $kvo
                . "', `Skidka` = '" . $skidka
                . "', `Kvo2` = '" . $kvo2
                . "', `Cena2` = '" . $cena2
                . "' WHERE `DocTab`.`id` = " . $idstr . ";";
    }
    cls_my_sql::run_sql($txt_sql);

    //echo '=' . $txt_sql ;

    savesumdoc($id_doc);

    return $ok;
}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем сумму документа
function sumdoc($id_doc){
// просто возвращаем колонку суммы из шапки документа
    // эта колонка вычисляется при изменении данных таб части дока (upd_tabdoc())

    //$txt_sql = "SELECT sum( `Kvo` * `Cena` * (1-`Skidka` / 100 ) ) as sum FROM `DocTab` WHERE `DocHd_id` = " . $id_doc ;
    $txt_sql = "SELECT `SumDoc` FROM `DocHd` WHERE `id` =  " . $id_doc ;
    $s_arr   = cls_my_sql::const_sql($txt_sql);

    $sumdoc  = $s_arr['SumDoc'];
    if($sumdoc == NULL ) $sumdoc = 0;

    $sumdoc=sprintf("%.2f", $sumdoc);

    return $sumdoc ;
}

//***********************************  baba-jaga@i.ua  ********************************************************
// возвращаем скидку по документу, если в строках скидки разные
function skidkadoc($id_doc){

    $txt_sql = "SELECT sum( `Kvo` * `Cena`  ) as sum1, sum( `Kvo` * `Cena` * (1-`Skidka` / 100 ) ) as sum2 FROM `DocTab` WHERE `DocHd_id` = " . $id_doc ;
    $s_arr   = cls_my_sql::const_sql($txt_sql);
    $sum1  = $s_arr['sum1'];
    $sum2  = $s_arr['sum2'];
    if($sum1== NULL )       return 0;
    if($sum1== 0 )          return 0;

    $sk = ($sum1 - $sum2 ) * 100 / $sum1 ;

   // $sk= round($sk , 2 );

    return $sk ;
}


//***********************************  baba-jaga@i.ua  ********************************************************
// доступен или нет документ для записи.
function write_doc($id_doc){
    // только "новый доступен" или id_status('на прием')

   $id_new_status = id_status('новый');
   $id_status2    = id_status('на прием');

   $txt_sql = "SELECT `statusDoc` FROM `DocHd` WHERE `id` = '".$id_doc."' ";
   $s_arr   = cls_my_sql::const_sql($txt_sql);
   $id_doc_status  = $s_arr['statusDoc'];
   if($id_new_status == $id_doc_status)       return TRUE;
   if($id_status2    == $id_doc_status)       return TRUE;
   return FALSE;

}

 //***********************************  baba-jaga@i.ua  ********************************************************
function str_to_datesql($str_date) {
    // преобразует строку типа: 21.08.11  в строку 2011-08-21 для записи в БД
    // или: 21.08.2011  в строку 2011-08-21 для записи в БД
    // работает до 2100 года

    if(trim($str_date) == '')        return '' ;

    $str_arr = explode(".", $str_date);

    $dd = $str_arr[0];
    $mm = $str_arr[1];
    $yy = $str_arr[2];
    if ($yy < 100)
        $yy = '20' . $yy;
    if (trim($dd) == '')
        return '';

    $dt = $yy . '-' . $mm . '-' . $dd;
    //echo "&nbsp;&nbsp; dt=" . $dt;
    return $dt;
}

//***********************************  baba-jaga@i.ua  ********************************************************
function htampdatetime_to_num($str_date){
    //преобразует штамп времени в число для сравнения с данными из 1С
    //

    $newstr = str_replace( ' ', '',$str_date);
    $newstr = str_replace( ':', '',$newstr);
    $newstr = str_replace( '-', '',$newstr);
    return $newstr;

}


 //***********************************  baba-jaga@i.ua  ********************************************************
function datesql_to_str($str_date) {
    // преобразует строку типа: 2011-08-21  в строку 21.08.11 из БД в обычный формат

    // работает до 2100 года
    if(trim($str_date) == '')        return '' ;

    $str_arr = explode("-", $str_date);

    $dd = $str_arr[2];
    $mm = $str_arr[1];
    $yy = $str_arr[0];
    if ($yy == '0000') return '';
    $yy = substr($yy, 2, 2);
    $dt = $dd . '.' . $mm . '.' . $yy;
    //echo "&nbsp;&nbsp; dt=" . $dt;
    return $dt;
}


//***********************************  baba-jaga@i.ua  ********************************************************
// отдаем код товара для поиска используем в прайс-листе
function kod_tov_searh(){
    if( isset( $_SESSION['kod_tov_searh'] ) ) {
        return  $_SESSION['kod_tov_searh'] ;
    }
        else {
            return "" ;
    }
}


//***********************************  baba-jaga@i.ua  ********************************************************
// отдаем фрагмент товара для поиска используем в прайс-листе
function frgm_tov_searh(){
    if( isset( $_SESSION['frgm_tov_searh'] ) ) {
        return  $_SESSION['frgm_tov_searh'] ;
    }
        else {
            return "" ;
    }
}

//***********************************  baba-jaga@i.ua  ********************************************************
// только остатки товара для поиска используем в прайс-листе
function only_searh(){
    //if(isset($_SESSION['only_searh'])) unset ( $_SESSION['only_searh'] );
    if( isset( $_SESSION['only_searh'] ) ) {
        return  $_SESSION['only_searh'] ;
    }else {
        return "" ;
    }
}

//***********************************  baba-jaga@i.ua  ********************************************************
// текущий код товара выбранного в прайс-листе
function kod_tov(){

    if( isset( $_SESSION['pricelist_kod_tov']) ) {
        return  $_SESSION['pricelist_kod_tov'] ;
    }else {
        return "" ;
    }

}

//***********************************  baba-jaga@i.ua  ********************************************************
class cls_my_sql{
    
    const NAME_v = '3';

    
    // если ошибка то будем отправлять мен мылом
    static function sm_err($txterr, $txt_sql){
        if($txterr!=''){
        echo 'ошибка в запросе: <br> ' . $txt_sql . '<br>' . $txterr ;
        }
        
        //echo $txterr . 'нет ошибки';
    } 
    
    // возвращаем массив записей
    public static function tbl_sql($sql_query){
        global $db;
        $sql     = mysql_query($sql_query, $db);
        self::sm_err(mysql_error(), $sql_query);
        //$s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        //return $s_arr ;
        return $sql;
        
        // для чтения записей в процедурах вызова пишем:
        //              $sql = cls_my_sql::tbl_sql($txt_sql);
        //        while ($srt_arr = mysql_fetch_array($sql)){
        //        ... бла бла бла 
        // }
        
        
    }
    
     // возвращаем первую строку результата
    public static function const_sql($sql_query){
        global $db;
        $sql     = mysql_query($sql_query, $db);
        self::sm_err(mysql_error(), $sql_query);
        $kvo_rows = mysql_num_rows($sql);
        //echo 'rvo_rows='.$kvo_rows;
        if($kvo_rows==0)  return FALSE;
        
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        return $s_arr ;
    }
    
     //просто выполняем запрос
    public static function run_sql($sql_query){
        global $db;
        $sql     = mysql_query($sql_query, $db);
        self::sm_err(mysql_error(), $sql_query);
        return $sql ;
    }  
    
    // возвращаем ID только что записанной записи
    public static function id_inserta($sql_query){
       $id = NULL; 
        global $db;
        $sql     = mysql_query($sql_query, $db);
        if ($sql)  $id = mysql_insert_id(); 
        self::sm_err(mysql_error(), $sql_query);        
        return $id;
    }
}

class cls_set{


    // получаем файл хмл
    static function xmlset(){
        $xmlString = file_get_contents("data/set.xml");
        $xml = new SimpleXMLElement($xmlString);      
        return $xml;
    }

    
     // возвращает значение указанного прарметра из файла настроек  "data/set.xml"
    public static function get_parametr($nm_dir, $nm_parametr) {
        $zn = '';
        $xml = self::xmlset();

        $dir = $xml->$nm_dir;
        foreach ($dir->children() as $cnst) {
          //  echo 'const='.$cnst . '  =' . $cnst->getName() . '<br>';
            $nm_const = $cnst->getName();
            if ($nm_const == $nm_parametr) {
                $zn = cnv($cnst);
                 return $zn;
            }
        }
        //$zn = $xml->$nm_parametr;
        return $zn;
    }
    
         // возвращает значение указанного прарметра из файла настроек  "data/set.xml"
    public static function get_svojstvo($nm_dir, $nm_parametr, $nm_svojstvo) {
        $zn = '';
        $xml = self::xmlset();

        $dir = $xml->$nm_dir;
        foreach ($dir->children() as $cnst) {
          //  echo 'const='.$cnst . '  =' . $cnst->getName() . '<br>';
            $nm_const = $cnst->getName();
            if ($nm_const == $nm_parametr) {
                
                $zn = cnv($cnst[$nm_svojstvo]);
               // echo '='.$zn;
                 return $zn;
            }
        }
        //$zn = $xml->$nm_parametr;
        return $zn;
    }

}

//***********************************  baba-jaga@i.ua  ********************************************************
class cls_my_const{

    var $id_firma_def;  // основная фирма (по умолчанию)
    var $id_klient_def; // клиент по умолчанию (для приходных)
    var $kvo_def;       // количество по умолчанию при наборке товара в документах
    var $kod_pc;        // код компа на складе если несколько mySQL
    var $ftp_catalog;
    var $ftp_server;
    var $kod_sklad;
    var $ftp_l;
    var $ftp_p;
    var $ftp_port;
    var $version;         //версия программы
    var $fileendpriem;    //последний удачно обработанный файл с данными
    var $dttime_copy_BD;  // когда следует делать копию БД time() + добавка
    var $magazin;  // имя магазина
    var $min_sum_opt; //сумма после которой оптовые цены
    var $kod_organization; //по этому коду выставляем картинки, файл обмена и др настройки

    // конструктор
    function cls_my_const(){
        global $db;

        $this->id_firma_def='';
        $this->id_klient_def=0;
        $this->kvo_def=0;
        $this->kod_pc=1;
        $this->ftp_catalog='';
        $this->ftp_server='';
        $this->kod_sklad='';
        $this->ftp_l='';
        $this->ftp_p='';
        $this->ftp_port='';
        $this->version=1;
        $this->fileendpriem='';
        $this->dttime_copy_BD=0;
        $this->magazin='';
        $this->min_sum_opt=100000;
        $this->kod_organization='km';


        $txt_sql = "SELECT `kod`, `name` FROM `const` ";
        $sql = mysql_query($txt_sql, $db);
        while ($srt_arr = mysql_fetch_array( $sql, MYSQL_ASSOC ) ) {
            if($srt_arr['kod'] == 'id_firm_default' ) $this->id_firma_def    = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'id_klient_default' ) $this->id_klient_def = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'kvo_default'       ) $this->kvo_def       = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'KodSklad'          ) $this->kod_sklad     = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'catalogFTP'        ) $this->ftp_catalog   = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'serverFTP'         ) $this->ftp_server    = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'portFTP'           ) $this->ftp_port      = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'lFTP'              ) $this->ftp_l         = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'pFTP'              ) $this->ftp_p         = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'idKodPC'           ) $this->kod_pc        = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'nameFilePriem'     ) $this->fileendpriem  = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'Version'           ) $this->version       = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'dttime_copy_BD'    ) $this->dttime_copy_BD= $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'magazin'           ) $this->magazin       = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'PorogOpt'          ) $this->min_sum_opt   = $srt_arr['name'] ;
        elseif($srt_arr['kod'] == 'kod_organization'  ) $this->kod_organization = $srt_arr['name'] ;
         }


    }

    //***********************************  baba-jaga@i.ua  *************************
    // записываем константу
    function save_const($name_const, $value_const){
        global $db;
        $txt_sql = "UPDATE `const` SET `name` = '".$value_const."' WHERE `kod` = '".$name_const."';";
      //  echo 'txt=' . $txt_sql ;
        $sql = mysql_query($txt_sql, $db);

    }


} // end  cls_my_const




//***********************************  baba-jaga@i.ua  ********************************************************
// общие для меню выбора данных новых документов

//выводим список фирм
function list_firm(){

    global $db;

    echo '<select id ="sel_firm" style="width: 140px " >';
    //echo ' <option selected="selected" value=""> Все фирмы</option>'; // и эта строка выбрана

    $sel_firma = id_firm();
    if ( isset($_GET['id_firm']) ) {
       $sel_firma = $_GET['id_firm'];
       $_SESSION['id_firm'] = $sel_firma;
    }

    $txt_sql = "SELECT `id`,`name_firm` FROM `firms` ";
    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {

        if($srt_arr[0] == $sel_firma ){
            echo '<option selected="selected" value="'.$srt_arr[0].'">'.$srt_arr[1].'</option>';
        }else {
            echo '<option                     value="'.$srt_arr[0].'">'.$srt_arr[1].'</option>';
        }
    }

    echo '</select>';

}

//выводим список видов документов
function list_viddoc(){

    global $db;

    $sel_doc = 'Чек';
    if ( isset($_GET['viddoc']) ) {
       $sel_doc = $_GET['viddoc'];
    }

    echo '<select id ="sel_doc" style="width: 140px " onchange="javascript:changedoc()"  >';
    //---echo '<option selected="selected" value="">все виды</option>'; // и эта строка выбрана

    $txt_sql =  "SELECT `Kod`,`name_doc` FROM `VidDoc` ORDER BY `VidDoc`.`Id` ASC  ";
    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {

        if($srt_arr[0] == 'Поступление')            continue;
        if($srt_arr[0] == $sel_doc) {
            echo '<option selected="selected" value="'.$srt_arr[0].'">'.$srt_arr[1].'</option>';
        }else{
            echo '<option                     value="'.$srt_arr[0].'">'.$srt_arr[1].'</option>';

        }
    }
    echo '</select>';

}

// конец общие для меню выбора данных новых документов

?>