<?php

require("header.inc.php");
include("milib.inc");
$const = new cls_my_const;
$arr_files = '' ; // массив файлов с данными: номфайла  имя файла
$file_new_ver = ''; // зип файл с обновлениями для программы

$date1 = date('d.m.y');
if (isset($_GET['time'])) {
  $_SESSION['obmen_time'] = $_GET['time'];
}

//require("data/newver.php");

//***********************************  baba-jaga@i.ua  **************************************
// запись в Журнал  обмена
function save_log( $sobitie, $info ){
    global $db;

    // ключевые слова: Начало, Ошибка

    $txt_sql = "INSERT INTO `log_obmen` ( `sobitie`, `info`, `user`)
                                VALUES ('".$sobitie."', '".$info."', '".  name_user() ."');";
    $sql     = mysql_query($txt_sql, $db);


}

//***********************************  baba-jaga@i.ua  **************************************
// Заносим в табл изменения цен, цены если изменились
function save_log_price($id_tovar, $new_price, $new_price_opt, $old_price, $old_price_opt ){

    //создание таблицы лога цен, если еще нет
    $txt_sql = "CREATE TABLE IF NOT EXISTS `log_price` (
          `id_tovar` int(11) NOT NULL,
          `date_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `old_price` decimal(10,2) NOT NULL,
          `new_price` decimal(10,2) NOT NULL,
          `old_price_opt` decimal(10,2) NOT NULL,
          `new_price_opt` decimal(10,2) NOT NULL,
          KEY `date_edit` (`date_edit`)
        ) ENGINE=MYISAM DEFAULT CHARSET=cp1251;" ;
    
    
    cls_my_sql::run_sql($txt_sql);
    
    $save_ = 0;
    
  if($new_price != $old_price) {
      
      $save_ = 1;
      
  } 
  
  if($new_price_opt != $old_price_opt){
      
       $save_ = 1;
  }
  
  //$id_tovar, $new_price, $new_price_opt, $old_price, $old_price_opt 
  //echo 'save_ = ' . $save_ .'<br>' ;
  
  if($save_ == 1){
      
     $txt_sql = "INSERT INTO `log_price` (`id_tovar`, `old_price`, `new_price`, `old_price_opt`, `new_price_opt`)"
             . " VALUES (                 '$id_tovar','$old_price','$new_price','$old_price_opt','$new_price_opt');";
 
     cls_my_sql::run_sql($txt_sql); 
     
  // echo '$txt_sql = ' . $txt_sql  .'<br>';  
     
  }
  
  
  
  
  return $save_; // если есть изменения в журнале обмена вставим ссылку на просмотр
 
    
}

//***********************************  baba-jaga@i.ua  **************************************
// упаковываем и отправляем передаем полные имена файла для отправки и полное имя создаваемого зип файла
// все должно быть в папке дата
function file_in_zip_in_ftp($f_name,$f_zip){
    global $const;
    if (is_writable($f_name)) { //файл есть можно упаковывать и отправлять
        //echo "упаковываем: $f_name <br>";
        //save_log('Отправка данных', 'Создан файл для отправки: ' . $f_name );

        //$f_zip    = 'data/' . $const->kod_sklad . '_backup.zip';
        $filename = str_replace('data/', '', $f_name);   // без пути складываем в zip
        $zip      = new ZipArchive;
        $res      = $zip->open($f_zip, ZipArchive::CREATE);
        if ($res === TRUE) {
            $zip->addFile($f_name, $filename);
            $zip->close();

            $connect = ftp_connect($const->ftp_server);
            if (!$connect) {
                //echo("<H1>подключитесь к интенет</H1>");
                $err = 'подключитесь к интенет';
                save_log('Ошибка отправки данных', $err );
                return $err ;
            }

            $result = ftp_login($connect, $const->ftp_l, $const->ftp_p);
            if(!$result){
                $err = 'нет подключения к серверу, возможно не верный логин или пароль';
                save_log('Ошибка отправки данных', $err );
                return $err ;
            }
             // включение пассивного режима
            ftp_pasv($connect, true);
            //echo("Соединение установлено<br>");
            if(! ftp_chdir($connect, $const->ftp_catalog) ){ // переход в каталог

                $err = 'не верно задана дирректория на сервере';
                save_log('Ошибка отправки данных', $err );
             }

            $filename = str_replace('data/', '', $f_zip);   // имя без пути
            if (ftp_put($connect, $filename, $f_zip, FTP_BINARY)) {
                //echo "данные отправлены. Можно продолжать работать\n";
                save_log('Отправка данных', 'Файл: '.  $filename. '  отправлен на сервер. '   );

            }
            else {
                //echo "Не удалось отправить данные\n";
                save_log('Ошибка отправки данных', "Не удалось отправить данные" );
            }

            ftp_quit($connect);  // завершаем соединениt
        }
        else {
            //echo 'f';
            save_log('Ошибка отправки данных', "failed zip" );
        }

        unlink($f_name);
        unlink($f_zip);

    }
        else {
        save_log('Ошибка отправки данных', "не существует файла" . $f_name );
    }


}

//***********************************  baba-jaga@i.ua  **************************************
// Делаем копию БД
function backup_database_tables($tables){
    //global $db;
    global $const;
    // echo 'начало';
        //$link = mysql_connect($host,$user,$pass);
        //mysql_select_db($name,$link);
        $return='';
        //get all of the tables
        if($tables == '*')
        {
                $tables = array();
                $result = mysql_query('SHOW TABLES');
                while($row = mysql_fetch_row($result))
                {
                        $tables[] = $row[0];
                }
        }
        else
        {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
        }
        //cycle through each table and format the data
        foreach($tables as $table)
        {
                $result = mysql_query('SELECT * FROM '.$table);
                $num_fields = mysql_num_fields($result);
                $return.= 'DROP TABLE IF EXISTS '.$table.';';
                $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
                $return.= "\n\n".$row2[1].";\n\n";
                for ($i = 0; $i < $num_fields; $i++)
                {
                        while($row = mysql_fetch_row($result))
                        {
                                $return.= 'INSERT INTO '.$table.' VALUES(';
                                for($j=0; $j<$num_fields; $j++)
                                {
                                        $row[$j] = addslashes($row[$j]);
                                        //$row[$j] = ereg_replace("\n","\\n",$row[$j]);
                                         $row[$j] = mysql_real_escape_string($row[$j]);

                                        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                                        if ($j<($num_fields-1)) { $return.= ','; }
                                }
                                $return.= ");\n";
                        }
                }
                $return.="\n\n\n";
        }
        //save the file
        //$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
        $f_name = 'data/db-backup.sql';
        $handle = fopen($f_name,'w+');
        fwrite($handle,$return);
        fclose($handle);
        chmod($f_name, 0777);

        $f_zip    = 'data/' . $const->kod_sklad .'_'. $const->kod_pc . '_backup.zip';
        // упаковываем и отправляем
        file_in_zip_in_ftp($f_name,$f_zip);




}


//Прием данных***********************************  baba-jaga@i.ua  **************************************
function tovars($xml) {
    //  <Tovars><Tovar Kod="1230" Name="Белье мужское  а (детские)  33" Price="45" Ost="0" Sostav="" DatePrihod="" del="0"/>
    global $db;
        
    $open_log_price = 0;

    $tv = $xml->Tables->Tovars;
    foreach ($tv->Tovar as $tovar) {
        //echo $tovar["Kod"] . '&nbsp;&nbsp;&nbsp;' . cnv($tovar["Name"]) . '<br>' ;
        //Tovar Kod="020722"
        //KodeBar="4820058221105"
        //Name="Альбом  8л  70 г/м АВ-9 Mix"
        //name_in_cennik="Альбом  8л  70 г/м АВ-9 Mix"
        //Price="2.1"
        //PriceOpt="2.1"
        //Ost="0"
        //NDS="20%"
        //DatePrihod="2012-03-07"
        //
        //del="0"/>
        //доработать HtampDateTime="              "

        $kod = $tovar["Kod"];
        $kode_bar = trim( str_replace("'", "",  $tovar['KodeBar'])); // Старун прикол подкинул
        if(strlen($kode_bar) < 4 ) $kode_bar=$kod;


        $nds = 1;

        $txt_sql1 = "SELECT `id_tovar`,`Kod1C`, `Kod`,`Price`,`PriceOpt`,`edit_time`,`name_in_cennik`,`v_upakovke`,`flg_edit` FROM `Tovar` WHERE `Kod1C` = '" . $kod . "'";
        $sql_1     = mysql_query($txt_sql1, $db);
        $s_arr   = mysql_fetch_array($sql_1, MYSQL_BOTH);
       
         // для совмещения с базой Канцлера пользуемся всегда поиском только по коду1С  
         // при нахождении не меняем ни штрих код ни наименование 
        
//поищем по штрих коду не катит
//        if ($s_arr['id_tovar'] == NULL) {
//           $txt_sql1 = "SELECT `id_tovar`,`Kod1C`, `Kod`,`edit_time`,`name_in_cennik`,`v_upakovke`,`flg_edit` FROM `Tovar` WHERE `Kod` = '" . $kode_bar . "'";
//           $sql_1     = mysql_query($txt_sql1, $db);
//           $s_arr   = mysql_fetch_array($sql_1, MYSQL_BOTH);          
//       }
        
        
        if ($s_arr['id_tovar'] == NULL) {
            
            $nm_tov = addslashes(cnv($tovar['Name']));

            $txt_sql = "INSERT INTO `Tovar` ( `Kod1C`, `Kod` , `Tovar`, `Price`, `PriceOpt`, `PriceIn`, `NDS`,
                                    `Sostav`, `Ostatok`, `ed_izm`,`v_upakovke`,`magazin`,`redaktor`,`Pometka`, `flg_del` )
             VALUES ('" . $kod . "', '"
                    . $kode_bar . "', '"
                    . $nm_tov . "', '"
                    . $tovar['Price'] . "', '"
                    . $tovar['PriceOpt'] . "', '"
                    . $tovar['PriceIn'] . "', '"
                    . $nds . "', '"
                    . cnv($tovar['Sostav']) . "', '"
                    . $tovar['Ost'] . "', '"
                    . cnv($tovar['ed']) . "', '"
                    . cnv($tovar['up']) . "', '"
                    . cnv($tovar['magazin']) . "', '"
                    . cnv($tovar['redactor']) . "', '"
                    . cnv($tovar['Pometka']) . "', '"
                    . $tovar['del'] . "');";
            $sql     = mysql_query($txt_sql, $db);
            if ($sql) {
                $id_t = mysql_insert_id(); // возвращает id только что записаннной строки
                $log_price= save_log_price($id_t, $tovar['Price'], $tovar['PriceOpt'], 0, 0);
                if($log_price == 1)$open_log_price = 1;
                
            }

           // echo '=' . $txt_sql . '<br>' ;

        } else {
                       //занести в изменение цен с проверкой
                                     //$id_tovar,         $new_price,         $new_price_opt, $old_price,      $old_price_opt
           
          // echo '<br>====== idt=' . $s_arr['id_tovar'] . " price=" . $s_arr['Price'] . " priceOpt=" . $s_arr['PriceOpt'] .  '</br>' ; 
           $log_price=save_log_price($s_arr['id_tovar'], $tovar['Price'], $tovar['PriceOpt'], $s_arr['Price'], $s_arr['PriceOpt']);
           if($log_price == 1)$open_log_price = 1;
                                
                     $txt_sql_fullupd = "UPDATE `Tovar` SET " 
                        . " `Price` = '" . $tovar['Price']
                        . "', `PriceOpt` = '" . $tovar['PriceOpt']
                        . "', `PriceIn` = '"  . $tovar['PriceIn']     
                        . "', `NDS` = '" . $nds
                        . "', `Sostav` = '" . cnv($tovar['Sostav'])
                        . "', `Ostatok` = '" . $tovar['Ost']
                        . "', `ed_izm` = '" . cnv($tovar['ed'])
                        . "', `v_upakovke` = '" . cnv($tovar['up'])
                        . "', `magazin` = '" . cnv($tovar['magazin'])
                        . "', `redaktor` = '" . cnv($tovar['redactor'])
                        . "', `Pometka` = '" . cnv($tovar['Pometka'])
                        . "', `flg_edit` = '0"
                        . "', `flg_del` = '" . $tovar['del']
                        . "' WHERE `Tovar`.`id_tovar` = " . $s_arr['id_tovar'] . ";";
           // $htamp_1c = $tovar["HtampDateTime"];
           // $htamp_my = $s_arr['edit_time'];
           // if ($htamp_1c > $htamp_my) {
           //if($s_arr['flg_edit']==0){
                $sql     = mysql_query($txt_sql_fullupd, $db);
            //}else {  //
            //    $fulupd = 1;
            //    $htamp_1c  = $tovar["HtampDateTime"];
            //    $htamp_my  = htampdatetime_to_num($s_arr['edit_time']);

            //    if ($htamp_my > $htamp_1c  )$fulupd = 0;

             //   if($fulupd==0){
                // обновляем только цены и остатки

             //    $txt_sql3 = "UPDATE `Tovar` SET
             //                 `Price` = '" . $tovar['Price']
             //           . "', `PriceOpt` = '" . $tovar['PriceOpt']
             //           . "', `PriceIn` = '" . $tovar['PriceIn'] 
             //           . "', `Ostatok` = '" . $tovar['Ost']
             //           . "' WHERE `Tovar`.`id_tovar` = " . $s_arr['id_tovar'] . ";";
             //   }else{ // обновился на сервере и пришел обновленный
              //      $txt_sql3 = $txt_sql_fullupd;
              //  }
               // $sql3     = mysql_query($txt_sql3, $db);

            }

        }
    

    save_log('Обработка данных', 'Принят обновленный прайс-лист');
    if( $open_log_price == 1 ){
        save_log('Изменение цен', "Просмотр товара с измененными ценами" );
    }
}

//Прием данных***********************************  baba-jaga@i.ua  **************************************
function firms( $xml ){
  //<Firms><Firm Kod="3" Name="Груздова Т. М." Tel="80988814428" del="0"/>

  global $db;
  $fr = $xml->Tables->Firms;
  
  //echo 'count=' . count($fr)  ;
  if(count($fr) == 0)      return;
  foreach ($fr->Firm as $firm) {
        //если есть в базе - обновим иначе вставим
      $kod = $firm["Kod"];

      $txt_sql = "SELECT id FROM `firms` WHERE `Kod1C` = " . $kod ;
      $sql     = mysql_query($txt_sql, $db);
      $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);

      if( $s_arr['id'] == NULL ){
          $txt_sql = "INSERT INTO `firms` ( `Kod1C`,  `name_firm`)
                                    VALUES ('".$kod."', '". cnv( $firm["Name"]) ."');";

           $sql2 = mysql_query($txt_sql, $db);
           $id_firm = mysql_insert_id(); // возвращает id только что записаннной строки

           //save_log('query', $txt_sql);

      } else {
         $id_firm  = $s_arr['id'];
      }

      $fromcheck = 1; if(cnv($firm["nal_bn"]) == 'для безнала')$fromcheck = 0;

       $txt_sql = "UPDATE `firms` SET `flg_del` = '".$firm["del"]
               ."', `name_firm`  = '". cnv($firm["Name"])
               ."', `name_full`  = '". cnv($firm["NameFull"])
               ."', `tel_firm`   = '". cnv($firm["Tel"])
               ."', `INN`        = '". $firm["INN"]
               ."', `nom_svid`   = '". $firm["NomSv_vo"]
               ."', `r_schet`    = '". $firm["r_schet"]
               ."', `name_bank`  = '". cnv($firm["nm_bank"])
               ."', `mfo_bank`   = '". cnv($firm["nm_MFO"])
               ."', `okpo`       = '". cnv($firm["OKPO"])
               ."', `info_nalog` = '". cnv($firm["info_nalog"])
               ."', `adres`      = '". cnv($firm["adres"])
               ."', `flg_del`    = '". $firm["del"]
               ."', `from_chek`  = '".$fromcheck
               ."' WHERE `firms`.`id` = " .  $id_firm . ";";

      $sql3     = mysql_query($txt_sql, $db);
       //save_log('query', $txt_sql);
    }


}


//Прием данных***********************************  baba-jaga@i.ua  **************************************
function users( $xml ){

    //<Users><User Kod="00013" Name="Демуш Женя" L="Женя" P="1111" del="0"/>
  global $db;
  $fr = $xml->Tables->Users;
  if(count($fr) == 0)      return;
  foreach ($fr->User as $user) {
      //если есть в базе - обновим иначе вставим
      $kod = cnv($user["Kod"]);

      $txt_sql = "SELECT `id` FROM `users` WHERE `Kod1C` =  '" . $kod ."'" ;
      $sql     = mysql_query($txt_sql, $db);
      $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
      if( $s_arr['id'] == NULL ){
          $txt_sql = "INSERT INTO `users` ( `Kod1C`, `login`, `full_name`, `pas`, `fl_del`)
              VALUES ('". $kod."', '". cnv($user["L"])."', '". cnv($user["Name"])."', '". cnv($user["P"])."', '". $user["del"]."');";
      } else {
          $txt_sql = "UPDATE `users` SET `login` = '". cnv($user["L"])."',
                                    `full_name` = '". cnv($user["Name"])."',
                                    `pas` = '". cnv($user["P"])."',
                                    `fl_del` = '0' WHERE `users`.`id` = ".$s_arr['id'].";";
      }
       $sql     = mysql_query($txt_sql, $db);

  }

}


//Прием данных**************************  baba-jaga@i.ua  **************************************
function klients( $xml ){



  global $db;
  $kl = $xml->Tables->Klients;
    foreach ($kl->Klient as $klient) {
        $edit = FALSE;
        //если есть в базе - обновим иначе вставим
        //Сначала ищем по дисконту, затем по коду
        $id_klient = NULL;
        $flgnew = true;
        $diskont = $klient["diskont"];
        if(trim($diskont)!=''){
            $txt_sql = "SELECT `id_`,`edit_time` FROM `Klient` WHERE `diskont` = '" . $diskont . "'";
            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            if ($s_arr['id_'] != NULL){ // нашли по дисконту

                $id_klient = $s_arr['id_'];
                $htamp_1c  = $klient["HtampDateTime"];
                $htamp_my  = htampdatetime_to_num($s_arr['edit_time']);
                $flgnew    = FALSE;
                //if ($htamp_1c > $htamp_my)  
                 $edit      = TRUE;

            }
        }

        $kod     = $klient["Kod"];
        if($id_klient==NULL){ // поищем по коду
            $txt_sql = "SELECT `id_`,`edit_time` FROM `Klient` WHERE `kod` = '" . $kod . "'";
            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            if ($s_arr['id_'] != NULL){ // нашли по коду

                $id_klient = $s_arr['id_'];
                $htamp_1c  = $klient["HtampDateTime"];
                $htamp_my  = htampdatetime_to_num($s_arr['edit_time']);
                $flgnew    = FALSE;
                //if ($htamp_1c > $htamp_my)   
                $edit      = TRUE;
            }

        }

        if($diskont == ''){
            $diskont = $kod;
        }
        
        if($flgnew){ // пишем нового
                $txt_sql = "INSERT INTO `Klient` (`name_klient`, `diskont`, `kod`)
                VALUES ('" . cnv($klient["Name"]) . "','" . $diskont . "','" . $diskont . "');";

                $sql       = mysql_query($txt_sql, $db);
                $id_klient = mysql_insert_id(); // возвращает id только что записаннной строки
                $edit      = TRUE;


        }

        if($edit){
          // обновляем запись либо только что созданную, либо созданную ранее
          $txt_sql = "UPDATE `Klient` SET `name_klient`      = '". cnv($klient["Name"])."',
                                          `name_full`        = '". cnv($klient["NameFull"])."',
                                          `kod`              = '". $klient["Kod"]."',
                                          `diskont`          = '". $diskont."',
                                          `Skidka`           = '". $klient["Skidka"]."',
                                          `telefon`          = '". cnv($klient["Tel"])."',
                                          `adres`            = '". cnv($klient["Adres"])."',
                                          `pometka`          = '". cnv($klient["Pometka"])."',
                                          `data_zakupki`     = '". $klient["DatePosetil"] ."',
                                          `OKPO`             = '". $klient["EGRPOU"] ."' ,
                                          `INN`              = '". $klient["INN"] ."' ,
                                          `magazin`          = '". cnv($klient["Magazin"]) ."' ,
                                          `nomer_sv_NDS`     = '". $klient["nomer_sv_NDS"] ."' ,
                                          `flg_del`          = '". $klient["del"] ."' ,
                                          `flg_edit`         = '0'
                                          WHERE `Klient`.`id_` = ".$id_klient.";";
         // echo $txt_sql . '<br>';
          $sql     = mysql_query($txt_sql, $db) or die("Invalid query: " . mysql_error());
        }

    }

    save_log('Обработка данных', 'Обновили список клиентов');


}

//Прием данных**************************  baba-jaga@i.ua  **************************************
function consts( $xml ){

        //<Consts><KodSklad>Gr1</KodSklad>
  global $db;
  $cn = $xml->Consts;
  foreach ($cn->children() as $constanta) {
        //echo 'const='.$constanta . '  =' . $constanta->getName() . '<br>';
        $nm_const = $constanta->getName();

        //если есть в базе - обновим иначе вставим
      //$kod = $user["Kod"];

      $txt_sql = "SELECT `id` FROM `const` WHERE `kod` = '" . $nm_const . "'" ;
      $sql     = mysql_query($txt_sql, $db);
      $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
      if( $s_arr['id'] == NULL ){
          $txt_sql = "INSERT INTO `const` ( `kod`, `name`) VALUES ('".$nm_const."', '".$constanta."');";
      } else {
          $txt_sql = "UPDATE `const` SET `kod` = '".$nm_const."', `name` = '".$constanta."' WHERE `const`.`id` = '".$s_arr['id']."';";
      }
       $sql     = mysql_query($txt_sql, $db);


  }


}

//Прием данных**************************  baba-jaga@i.ua  **************************************
function docs($xml) {
    //<Docs><Prihods><Prihod
    //Prihod nomDoc="1018" dateDoc="2010-12-10"

    global $db;
    $my_cnst = new cls_my_const();
    $docs = $xml->Docs->Prihods;
    if (count($docs) != 0) {


        $id_viddoc = id_viddoc('Поступление');

        foreach ($docs->Prihod as $doc) {

            //ШАПКА если есть в базе - обновим иначе вставим
            //
        //echo ' doc =' .  $doc->getName() . '  nom = ' .  trim( cnv($doc['nomDoc'])) . '<br>' ;

            $txt_sql = "SELECT * FROM `DocHd`
          WHERE `nomDoc` = '" . trim(cnv($doc['nomDoc'])) . "' AND `VidDoc_id` = '" . $id_viddoc .
                    "' AND `DataDoc` = '" . $doc['dateDoc'] . "'"; // AND `Pometka` LIKE '" . trim(cnv($doc)) . "'   ";

            $sql = mysql_query($txt_sql, $db);
            $s_arr = mysql_fetch_array($sql, MYSQL_BOTH);
            if ($s_arr['id'] == NULL) {

                $txt_sql = "INSERT INTO `DocHd` ( `nomDoc`, `VidDoc_id`, `statusDoc`, `Klient_id`, `users_id`, `firms_id`, `DataDoc`,`Pometka`)
              VALUES ( '" . trim(cnv($doc['nomDoc'])) . "', '" . $id_viddoc . "',
                        '" . id_status('на прием') . "', '" . $my_cnst->id_klient_def . "', '" . id_user() . "',
                        '" . $my_cnst->id_firma_def . "', '" . $doc['dateDoc'] . "', '" . trim(cnv($doc)) . "');";
                $sql = mysql_query($txt_sql, $db);
                $id_doc = mysql_insert_id(); // возвращает id только что записаннной строки
                // foreach ($doc->sDoc as $str_doc) {
                //<sDoc strNom="1" KodTov="4765" Kvo="1"/>
                //      upd_tabdoc($id_doc, $str_doc['KodTov'], 0, $str_doc['Kvo'], 0, 0, 0);
                //  }

                save_log('Обработка данных', "Получен приходник №_" . cnv($doc['nomDoc']) . " от:" . $doc['dateDoc'] . " для приемки");
            } else {

                // был принят ранее, редактим только в случае если еще нет приемки
                $id_doc = $s_arr['id'];
                if (!write_doc($id_doc))
                    continue;
            }

            //табличная часть все сотрем и напишем по новой
            $txt_sql = "DELETE  FROM `DocTab` WHERE `DocHd_id` = '" . $id_doc . "' ";
            $sql = mysql_query($txt_sql, $db);
            $strdoc = $doc->sDoc;
            foreach ($doc->sDoc as $str_doc) {
                //<sDoc strNom="1" KodTov="4765" Kvo="1"/>

                $err = upd_tabdoc($id_doc, $str_doc['KodTov'], 0, $str_doc['Kvo'], 0, 0, 0);
                if ($err != '')
                    save_log("Ошибка приемки приходника", $err);
            }
            save_log('Обработка данных', "Обновлен приходник №_" . cnv($doc['nomDoc']) . " от:" . $doc['dateDoc'] . " для приемки");
        }
    } //count docs
    // нехватки в раскомплектацию ***************************
    $docs = $xml->Docs->Nehvatki;
    if (count($docs) != 0) {
        $id_viddoc = id_viddoc('Комплектация');

        foreach ($docs->Nehvatka as $doc) {

            //ШАПКА если есть в базе - обновим иначе вставим
            //
       // echo ' doc =' .  $doc->getName() . '  nom = ' .  trim( cnv($doc['nomDoc'])) . '<br>' ;

            $txt_sql = "SELECT * FROM `DocHd`
          WHERE `nomDoc` = 'S_" . trim(cnv($doc['nomDoc'])) . "' AND `VidDoc_id` = '" . $id_viddoc .
                    "' AND `DataDoc` = '" . $doc['dateDoc'] . "'"; // AND `Pometka` LIKE '" . trim(cnv($doc)) . "'   ";

            $sql = mysql_query($txt_sql, $db);
            $s_arr = mysql_fetch_array($sql, MYSQL_BOTH);
            if ($s_arr['id'] == NULL) {

                $txt_sql = "INSERT INTO `DocHd` ( `nomDoc`, `VidDoc_id`, `statusDoc`, `Klient_id`, `users_id`, `firms_id`, `DataDoc`,`Pometka`)
              VALUES ( '" . trim(cnv($doc['nomDoc'])) . "', '" . $id_viddoc . "',
                        '" . id_status('новый') . "', '" . $my_cnst->id_klient_def . "', '" . id_user() . "',
                        '" . $my_cnst->id_firma_def . "', '" . $doc['dateDoc'] . "', 'Нехватка по чекам: " . trim(cnv($doc['Pometka'])) . "');";
                $sql = mysql_query($txt_sql, $db);
                $id_doc = mysql_insert_id(); // возвращает id только что записаннной строки
                // foreach ($doc->sDoc as $str_doc) {
                //<sDoc strNom="1" KodTov="4765" Kvo="1"/>
                //      upd_tabdoc($id_doc, $str_doc['KodTov'], 0, $str_doc['Kvo'], 0, 0, 0);
                //  }

                save_log('Обработка данных', "Получены данные о нехватке №_" . cnv($doc['nomDoc']) . " от:" . $doc['dateDoc']);
            } else {

                // был принят ранее, редактим только в случае если еще нет приемки
                $id_doc = $s_arr['id'];
                if (!write_doc($id_doc))
                    continue;
            }

            //табличная часть все сотрем и напишем по новой
            $txt_sql = "DELETE  FROM `DocTab` WHERE `DocHd_id` = '" . $id_doc . "' ";
            $sql = mysql_query($txt_sql, $db);
            $strdoc = $doc->sDoc;
            foreach ($doc->sDoc as $str_doc) {
                //<sDoc strNom="1" KodTov="89269" HKod="6909074113040" KvoSpisano="3" KvoNaSklade="0"/>
                $id_t = id_tovar('', $str_doc['KodTov']);
                $txt_sql = "SELECT `Price`, `PriceOpt`, `Kod1C`, `Kod`, `vid_cennic`  FROM `Tovar` "
                        . " WHERE `id_tovar` = ' $id_t '";
                $sql = mysql_query($txt_sql, $db);
                $s_arr = mysql_fetch_array($sql, MYSQL_BOTH);

                $kvo = $str_doc['KvoSpisano'] - $str_doc['KvoNaSklade'];
                $err = upd_tabdoc($id_doc, $str_doc['KodTov'], $s_arr['Price'], 0, 0, $s_arr['Price'], $kvo);
                if ($err != '')
                    save_log("Ошибка приемки нехватки", $err);
            }
        }
    }//CountDocs nehvatki
}

//Прием данных**************************  baba-jaga@i.ua  **************************************
function schets($xml) {
    //<Schets><Schet
    //<Schet nomDoc="ТГ-1385   " dateDoc="2013-07-24" SumOtg="900">

    global $db;
    
     $docs = $xml->Docs->Schets;
     if(count($docs) == 0)      return;
    
    $my_cnst = new cls_my_const();

    $id_viddoc = id_viddoc('Счет');


    foreach ($docs->Schet as $doc) {

        //ШАПКА если есть в базе - обновим иначе вставим
        //
        //echo ' doc =' .  $doc->getName() . '  nom = ' .  trim( cnv($doc['nomDoc'])) . '<br>' ;


       /// одинэска при приеме счета, переделывает номер,
       // Вернем старый
       $nomdoc =  trim(cnv($doc['nomDoc']));
       $nomdoc_old = "";
       if( strstr( $nomdoc , $my_cnst->kod_sklad )  ){ // найден код склада в номере документа

            //$string = substr("Hello, world!", 6, 2);
           $nomdoc_old = substr($nomdoc, strlen($nomdoc)-3 );
           //echo 'nomdocold=' . $nomdoc_old .'<br>' ;
           if(substr($nomdoc_old, 0,1) =='0')$nomdoc_old = substr($nomdoc, strlen($nomdoc)-2 );
           //echo 'nomdocold=' . $nomdoc_old .'<br>' ;
           if(substr($nomdoc_old, 0,1) =='0')$nomdoc_old = substr($nomdoc, strlen($nomdoc)-1 );
           //echo 'nomdocold=' . $nomdoc_old .'<br>' ;

           $nomdoc = $nomdoc_old;

       }

            $txt_sql = "SELECT `id_` FROM `Klient` WHERE `kod` =  '" . $doc['kodKlient'] . "'" ;
            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            $id_klient = $s_arr['id_'];

            $txt_sql = "SELECT `id` FROM `firms` WHERE `Kod1C`='" . $doc['kodFirm'] . "'";
            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            $id_firm = $s_arr['id'];




// счетов может быть несколько с одним номером в одной дате
      $txt_sql = "SELECT * FROM `DocHd`
                  WHERE `nomDoc` = '" . $nomdoc . "' AND `VidDoc_id` = '" . $id_viddoc .
                "' AND `DataDoc` = '" . $doc['dateDoc'] . "'" ;// AND `Pometka` LIKE '" . trim(cnv($doc)) . "'   ";


        $id_doc = ''; $sumotg = 0;
        $sumopl = $doc['SumKOtg']; //- $doc['SumOtg']; //СуммаОплаты = СуммаКОтгрузке - СуммаОтгружено
 
        // у каждого счета сменим примечание
        $sql   = mysql_query($txt_sql, $db);
        while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
                    
            $id_doc_ = $s_arr['id'];
            $txt_sql_eit = "  UPDATE  `DocHd` SET  `Pometka`='" . trim(cnv($doc['pometka'])) . "' WHERE `id`='" . $id_doc_ . "'";
            $sql_e     = mysql_query($txt_sql_eit, $db);
        }
        
        

        
        if($sumopl <= 0)  continue; // счет либо не оплачен либо уже закрыт

        $sql   = mysql_query($txt_sql, $db);
        while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {


            
            if (!write_doc($s_arr['id'])){ // суммируем сумму проданных счетов
                $sumotg=$sumotg+$s_arr['SumDoc'];

            }else { // есть счет который можно редактировать
                save_log('<H3>Оплачен счет</H3>', "Получена оплата по счету №_" . cnv($doc['nomDoc']) . " от:" . $doc['dateDoc']);
                $id_doc = $s_arr['id'];
            }

        }
        
 


        if($id_doc==''){ // не нашли счет "новый"
            if($sumotg==0){ // счет сделали в  1C
                save_log('<H3>Новый счет</H3>', "Получен счет для сборки и выдачи товара №_" . cnv($doc['nomDoc']) . " от:" . $doc['dateDoc']);
            }elseif ($sumotg < $doc['SumKOtg'] + 1) {
                save_log('<H3>Дооплачен счет</H3>', "Получена еще одна оплата по счету №_" . cnv($doc['nomDoc']) . " от:" . $doc['dateDoc']);
            }elseif ($sumotg == $doc['SumOtg']) {
                if($doc['SumKOtg']>0){
                   save_log('<H3>Дооплачен счет</H3>', "Получена еще одна оплата по счету №_" . cnv($doc['nomDoc']) . " от:" . $doc['dateDoc']);
                }

            }else                continue;

           $sumvkassu = $doc['SumKOtg'];
           if($sumotg > $doc['SumOtg'] ){ //  если здесь сумма отгруженного больше,
                 // то сумма к оплате = сума к оплате минус разница между отгрузками
                 // т.е. тут уже отгрузили, а там не знают

               $sumvkassu =   $doc['SumKOtg'] - $sumotg ;

           }

          //echo 'sumvkassu=' . $sumvkassu . '  sumotg='.$sumotg . '  v1cOTG=' .$doc['SumOtg'] ;

           $txt_sql_new = "INSERT INTO `DocHd` ( `nomDoc`, `VidDoc_id`, `statusDoc`, `sum_v_kassu` , `Klient_id`, `users_id`, `firms_id`, `DataDoc`,`Pometka`)
              VALUES ( '" . trim(cnv($doc['nomDoc'])) . "', '" . $id_viddoc . "',
                        '" . id_status('новый') . "', '" . $sumvkassu  . "', '" . $id_klient . "', '" . id_user() . "',
                        '" . $id_firm . "', '" . $doc['dateDoc'] . "', '" . trim(cnv($doc['pometka'])) . "');";

            $sql    = mysql_query($txt_sql_new, $db);
            $id_doc = mysql_insert_id(); // возвращает id только что записаннной строки


        } else { // редактим счет
            $txt_sql = "  UPDATE  `DocHd` SET  `sum_v_kassu`='" . $sumopl . "' WHERE `id`='" . $id_doc . "'";
            $sql     = mysql_query($txt_sql, $db);

        }


        //табличная часть все сотрем и напишем по новой
         $txt_sql = "DELETE  FROM `DocTab` WHERE `DocHd_id` = '". $id_doc ."' ";
         $sql     = mysql_query($txt_sql, $db);
         $strdoc = $doc->sDoc;
              foreach ($doc->sDoc as $str_doc) {
        //<sDoc strNom="1" KodTov="4765" Kvo="1"/>

             $err = upd_tabdoc($id_doc, $str_doc['KodTov'] , $str_doc['Cena'] ,  $str_doc['Kvo'] ,$str_doc['Skidka'], 0, 0);
             if($err != '') save_log("Ошибка приемки счета id=" . $id_doc , $err);
              }

    }
}



//Отправка данных**************************  baba-jaga@i.ua  **************************************
function klients_($klients){

   global $db;

   $txt_sql =  "SELECT * FROM `Klient` WHERE `flg_edit` = 1  ";

   $sql = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_array( $sql, MYSQL_ASSOC ) ) {
        $klient = $klients->addChild('Klient');
          foreach($srt_arr as $key => $value)
            {
              $klient->addAttribute($key, cnv_( $value) );
               //echo "$key = $value <br />";
            }
        //save_log('Отправка данных', "По клиенту: " . $srt_arr['name_klient'] );
    }



}

//Отправка данных**************************  baba-jaga@i.ua  **************************************
function tovars_($tovars){

   global $db;

       // установим всем записям время отправки
    $txt_sql = "UPDATE `Tovar` SET `edit_time`='". date('Y-m-d') . " " . date('H:i:s')  ."' WHERE `flg_edit`=1";
    $sql = mysql_query($txt_sql, $db);


    //отправим
   $txt_sql =  "SELECT * FROM `Tovar` WHERE `flg_edit` = 1  ";

   $sql = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_array( $sql, MYSQL_ASSOC ) ) {
        $tovar = $tovars->addChild('Tovar');
          foreach($srt_arr as $key => $value)
            {
              $tovar->addAttribute($key, cnv_( $value) );
               //echo "$key = $value <br />";
            }
        //save_log('Отправка данных', "изменения в товаре: " . $srt_arr['Tovar'] );
    }



}

//Отправка данных**************************  baba-jaga@i.ua  **************************************
function addstrdoc($xmldoc , $iddoc ){
  global $db;

  $txt_sql =  "SELECT `DocTab`.`DocHd_id`, `Tovar`.`Kod1C`, `Tovar`.`Tovar`, `DocTab`.`nomstr`, `DocTab`.`Cena`,
                `DocTab`.`Kvo`, `DocTab`.`Skidka`, `DocTab`.`Kvo2`, `DocTab`.`Cena2`, `Tovar`.`PriceIn`, `DocTab`.`pometka`,
                `DocTab`.`timeShtamp`, `Tovar`.`id_tovar`, `Tovar`.`fix_prc_rozn`\n"
            . "FROM `DocTab`\n"
            . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
            . "WHERE (`DocTab`.`DocHd_id` ='".$iddoc."'  )\n"
            . "ORDER BY `DocTab`.`timeShtamp` DESC\n"
            . " ";

    $sql = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_array( $sql, MYSQL_ASSOC ) ) {
        $strdoc = $xmldoc->addChild('StrDoc');
          foreach($srt_arr as $key => $value)
            {
              $strdoc->addAttribute($key, cnv_( $value) );
               //echo "$key = $value <br />";
            }

    }

}

//Отправка данных**************************  baba-jaga@i.ua  **************************************
function docs_($docs, $dt_doc) {

    global $db;

    $idst = id_status('новый'); // новых не должно быть, удаленные не отправляем
    // проверить чтоб все доки были закрыты на указанную дату
    // доки Поступление и Счет - пропускаем
    // $idpost = id_viddoc('Поступление');

    $txt_sql = "SELECT * FROM `DocHd` WHERE `statusDoc` = '" . $idst . "' AND `DataDoc` = '" . $dt_doc . "' ";


    $sql = mysql_query($txt_sql, $db);

    while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
        if ($srt_arr['VidDoc_id'] == id_viddoc('Поступление'))
            continue;
        if ($srt_arr['VidDoc_id'] == id_viddoc('Счет'))
            continue;

        $err = 'Не закрыт документ №_' . viddoc($srt_arr['VidDoc_id']) . ' №_' . $srt_arr['nomDoc'] . ', данные не отправлены.';
        //echo $err;
        save_log('Ошибка отправки данных', $err);
        return $err;
    }


    //
    $idst = id_status('Удален'); // новых не должно быть, удаленные не отправляем
    // два запроса, один по сегодняшнему дню, а один по счетам, они могут быть и за прошлые дни
    // отредактированы, но при этом поменялся TimeShtamp

    $txt_sql_0 = " SELECT `DocHd`.`id`, `DocHd`.`nomDoc`, `VidDoc`.`nameIsXML`, `StatusDoc`.`nameStatus`,
            `Klient`.`name_klient`,`Klient`.`kod`, `Klient`.`diskont`, `users`.`Kod1C` as userkod , `firms`.`Kod1C` as firmkod,
            `DocHd`.`DataDoc`, `DocHd`.`TimeShtamp`, `DocHd`.`SumDoc`, `DocHd`.`SkidkaProcent`,
            `DocHd`.`oplataBank` ,`DocHd`.`flg_optPrice`, `DocHd`.`Pometka`
            FROM `firms`
            LEFT JOIN `DocHd` ON `firms`.`id` = `DocHd`.`firms_id`
            LEFT JOIN `Klient` ON `DocHd`.`Klient_id` = `Klient`.`id_`
            LEFT JOIN `VidDoc` ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id`
            LEFT JOIN `StatusDoc` ON `DocHd`.`statusDoc` = `StatusDoc`.`idStatus`
            LEFT JOIN `users` ON `DocHd`.`users_id` = `users`.`id`
            WHERE ((`nameIsXML` !='') AND (`DocHd`.`statusDoc` !='" . $idst . "')
                AND (`DocHd`.`DataDoc` = '" . $dt_doc . "') )
            ORDER BY `DocHd`.`VidDoc_id` ASC
            ";

    $txt_sql_1 = " SELECT `DocHd`.`id`, `DocHd`.`nomDoc`, `VidDoc`.`nameIsXML`, `StatusDoc`.`nameStatus`,
            `Klient`.`name_klient`,`Klient`.`kod`,`Klient`.`diskont`, `users`.`Kod1C` as userkod , `firms`.`Kod1C` as firmkod,
            `DocHd`.`DataDoc`, `DocHd`.`TimeShtamp`, `DocHd`.`SumDoc`, `DocHd`.`SkidkaProcent`,
            `DocHd`.`oplataBank` ,`DocHd`.`flg_optPrice`, `DocHd`.`Pometka`
            FROM `firms`
            LEFT JOIN `DocHd` ON `firms`.`id` = `DocHd`.`firms_id`
            LEFT JOIN `Klient` ON `DocHd`.`Klient_id` = `Klient`.`id_`
            LEFT JOIN `VidDoc` ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id`
            LEFT JOIN `StatusDoc` ON `DocHd`.`statusDoc` = `StatusDoc`.`idStatus`
            LEFT JOIN `users` ON `DocHd`.`users_id` = `users`.`id`
            WHERE ((`nameIsXML` ='schet') AND (`DocHd`.`statusDoc` !='" . $idst . "')
                AND (`DocHd`.`TimeShtamp` > '" . $dt_doc . " 00:00:00') AND (`DocHd`.`DataDoc` != '" . $dt_doc . "') )
            ORDER BY `DocHd`.`VidDoc_id` ASC
            ";

    for ($index = 0; $index < 2; $index++) {
        $txt_sql = $txt_sql_1;
        if($index==0)$txt_sql=$txt_sql_0;

   // echo '<br>'.$txt_sql.'<br>';

        $sql     = mysql_query($txt_sql, $db);
        while ($srt_arr = mysql_fetch_array($sql, MYSQL_ASSOC)) {
            $nmdoc = $srt_arr['nameIsXML'];
            $doc   = $docs->addChild($nmdoc);
            foreach ($srt_arr as $key => $value) {
                $doc->addAttribute($key, cnv_($value));
                //echo "$key = $value <br />";
            }
            // добавим строки документов
            addstrdoc($doc, $srt_arr['id']);

            // флаг отправки
            $t_sql = "UPDATE `DocHd` SET `flg_otpravlen` = '1' WHERE `DocHd`.`id` = '" . $srt_arr['id'] . "';";
            $upd   = mysql_query($t_sql, $db);
        }
    }


    return '';
}

//Отправка данных**************************  baba-jaga@i.ua  **************************************
// отправляем данные за выбранную дату
function otpravka(){

    global $const;

    $date1   = $_GET['date1'];
    $dt      = str_replace('.', '', $date1);
    $date1 = str_to_datesql($date1);
    $my_comp = $const->kod_pc; // имя компа с которого ведутся продажи
    $skl     = $const->kod_sklad; # указыват 1С какой это склад
    $f_name  = "data/" . $skl . "_" . $my_comp . '_' . $dt . '.xml';



    $handle = fopen($f_name, 'w');
    $str    = "<?xml version='1.0' encoding='windows-1251' ?> ";
    $str .= "\n";
    fwrite($handle, $str);
    $str    = '<Data DateTime="' . date('Y-m-d') . '" User="' . name_user() . '" Magazin="Гермес" KodMAgazin="Gr1"></Data>';
    fwrite($handle, $str);
    fclose($handle);

    chmod($f_name, 0777);

    $xmlString = file_get_contents($f_name);
    $xml       = new SimpleXMLElement($xmlString);


    $tables  = $xml->addChild('Tables');
    $klients = $tables->addChild('Klients');
    klients_($klients);
    $tovars = $tables->addChild('tovars');
    tovars_($tovars);

    $docs = $xml->addChild('Docs');
    $err = docs_($docs, $date1);
    if($err != '')        return $err ;

    $xml->saveXML($f_name);


    // упаковываем и отправляем
    save_log('Отправка данных', 'Создан файл для отправки: ' . $f_name );
    $f_zip    = str_replace('.xml', '.zip', $f_name);  //меняем txt на zip
    file_in_zip_in_ftp($f_name,$f_zip);

}

//Прием данных**************************  baba-jaga@i.ua  **************************************
//получаем все файлы для нас
function priem() {
    // на серваке файлы удалять нельзя, т.к. возможно они понадобятся
    // др. компу с этого склада, но со своим mySQL
    // в конст. записан последний принятый и обработанный файл
    // берем с него цифры и сравниваем, если на серваке больше, то грузим ,
    // также и файл обновлений он начинается ver785.zip
    // если цыфры больше, то грузим, выполняем и заноим данные в константу
    // после того, как загрузили все файлы с сервака, - обрабатываем тоже
    // в порядке возрастания цифр. т.к. могли поменяться остатки и цены и им подобное,
    // нам нужны все данные и последние самые свежие
    // в константы записываем данные только после обработки без ошибок

    global $const;
    global $arr_files;
    global $file_new_ver;

    $startnmfile  = 's_' . $const->kod_sklad . '_';
    $startverfile = 'ver';


    $endfile = $const->fileendpriem;
    $nom_end_ok_file = 0;
    if(strlen($endfile) > 1) $nom_end_ok_file  = substr($endfile, strlen($startnmfile) , strlen($endfile) - 4  );//минус расширение


    $nomver = 1;
    $filever  = $const->version;
    //if(strlen($filever) > 1)$nomver = substr($filever , strlen($startverfile) , strlen($filever) - 4  );//минус расширение
    if(strlen($filever) > 1)$nomver = substr($filever , strlen($startverfile)   );
    $nomver = str_replace('.zip','' ,$nomver);

    $connect = ftp_connect($const->ftp_server);
    if (!$connect) {
       // echo("<H1>подключитесь к интенет</H1>");
        $err = 'подключитесь к интенет';
        save_log('Ошибка получения данных', $err );
        return $err;
    }

    $result = ftp_login($connect, $const->ftp_l, $const->ftp_p);
    if(!$result){
         $err = 'не верное подключение к серверу';
        save_log('Ошибка получения данных', $err );
        return $err;
    }
    // включение пассивного режима
    ftp_pasv($connect, true);

    //echo("Соединение установлено<br>");
    save_log('Получение данных', "Соединение с сервером установлено ");
    ftp_chdir($connect, $const->ftp_catalog); // переход в каталог

    $file_list = ftp_nlist($connect, ".");
    if (is_array($file_list)) {
        foreach ($file_list as $file) {

            //file=s_Gr1_20121221184154.zip
            //file=s_Gr1_20121221184954.zip
            //file=s_Gr1_20121221185215.zip
            
            //echo '='.$file;

            if (( strpos($file, $startnmfile) !== 0) and ( strpos($file, $startverfile) !== 0 ))
                continue;

            //если это файл данных
            if (strpos($file, $startverfile) !== 0) {
                //echo 'file=' . $file . '. startnmfile='.$startnmfile.'<br>';
                $nomfile =  substr($file, strlen($startnmfile) , 14 ); //номер всегда такой длинны
                //echo 'nomfile=' . $nomfile . '. nom_end_ok_file='.$nom_end_ok_file.'<br>' ;
                if ($nomfile > $nom_end_ok_file) { // загружаем нам подходит
                    if (ftp_get($connect, "" . "data/" . $file, $file, FTP_BINARY)) {
                        //echo "получен файл данных ". $file ." <br>";
                        save_log('Получение данных', "получен файл данных ". $file ." ");
                        $arr_files[$nomfile]= "data/" . $file ;
                    }
                    else {

                        save_log('Ошибка получения данных',"Не удалось получить файлы данных " );
                    }
                }
            }
            else { // Это файл версии
                //$nomfile = substr($file, strlen($startverfile) , strlen($file) - 4  );//минус расширение
                $nomfile = substr($file, strlen($startverfile)  );//минус расширение
                $nomfile = str_replace('.zip','' ,$nomfile);
                //echo '=' . $nomfile . '  jkl dth =' . $nomver . '<br>' ;
                if ($nomfile > $nomver) { // загружаем нам подходит
                    if (ftp_get($connect, "" .  $file, $file, FTP_BINARY)) {
                        //copy("data/" . $file, $file);
                        //unlink("data/" . $file);
                        $file_new_ver = $file;
                        //echo "получен файл обновлений программы ". $file ." <br>";
                        save_log('Получение данных', "получен файл обновлений программы ". $file ." .");
                    }
                    else {
                        //echo "Не удалось получить файл обновлений программы\n";
                         save_log('Ошибка получения данных',"Не удалось получить файл обновлений программы");
                    }
                }
            }
        }
    }

    ftp_quit($connect);  // завершаем соединениt
}

//Прием данных**************************  baba-jaga@i.ua  **************************************
//если в папке есть файл обновления, обработаем его и прибьем
function update_program() {

    global $file_new_ver;
    global $db;
    
    // раскаментить для теста
    //require("data/newver.php");
    
   //throw new Exception('Нет такого файла.');

    //echo 'file_new_ver=' . $file_new_ver ;
    if ($file_new_ver == '')
        return;

    // распакуем
    $zip = new ZipArchive;
    if ($zip->open($file_new_ver) === true) {

        // удалим потому что дает ошибку
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fl = $zip->getNameIndex($i);
            //echo 'Filename: ' . $zip->getNameIndex($i) . '<br />';
            $openf = fopen($fl, "r");
            if ($openf) {
                fclose($openf);
                unlink($fl);
            }


            // ...
        }

        $zip->extractTo(getcwd());
        $zip->close();
        unlink($file_new_ver);
        //echo "<br> данные извлечены и разложены по местам";
        //save_log('Получение данных', "У ". $file ." .");
    }
    else {
        //echo "<br> не удалось открыть архив с данными";
        save_log('Ошибка получения данных', "не удалось открыть архив с данными обновления программы");
        return;
    }
    //echo "получены обновления программы." ;
    //если есть , то выполним и удалим
    if (is_writable("newver.php")) {
        include("newver.php");
        upd_mysql();
        //require("newver.php");
        unlink("newver.php");
    }

    $txt_sql = "UPDATE `const` SET `name` = '" . $file_new_ver . "' WHERE `kod` = 'Version' ;";
    $sql     = mysql_query($txt_sql, $db);

    save_log('Получение данных', "Успешно обработан файл обновлений программы ". $file_new_ver ." .");



}


// нажата кнопка прием данных
if (isset($_GET['priem'])) {

    //echo 'нажата кнопка прием данных <br>';
    save_log('Начало получения данных с сервера', "");
    priem(); // получили файлики и сложили в папку /data
    // имена и номера файлов в массиве
    //берем по одному сначала ver, затем данные в порядке возрастания
    update_program();

    // если есть принятые файлы Обрабатываем!
    if (is_array($arr_files)) {

        ksort($arr_files);

        foreach ($arr_files as $index => $f_name) {

            //   $f_name = 'data/s_Gr1.xml' ;
            // распакуем
            $zip = new ZipArchive;
            if ($zip->open($f_name) === true) {
                $zip->extractTo('data/');
            }
            $zip->close();
            unlink($f_name); // del zip file

            $f_name = str_replace('.zip', '.xml', $f_name);


            $xmlString = file_get_contents($f_name);
            $xml       = new SimpleXMLElement($xmlString);

            consts($xml);
            tovars($xml);
            firms($xml);
            users($xml);
            klients($xml);
            docs($xml);
            schets($xml);

            unlink($f_name); // он уже не нужен
         //обновим константу
        $name_f  = str_replace('data/', '', $f_name);
        $txt_sql = "UPDATE `const` SET `name` = '" . $name_f . "' WHERE `kod` = 'nameFilePriem' ;";
        $sql     = mysql_query($txt_sql, $db);

        }

    }
        else {
            save_log('Получение данных', "Нет данных для обработки ");
    }

    save_log('Получение данных', "Данные получены и обработаны.");

}

// нажата кнопка отправки данных
if (isset($_GET['otpravka'])) {

    //echo 'нажата кнопка отправки данных <br>';
    save_log('Начало отправки данных на сервер', "");
    otpravka();


    //require("bakcup.php"); // создание копии БД


    //$nextWeek = time() + (7 * 24 * 60 * 60);

    // копия БД раз в неделю
    if (time() > $const->dttime_copy_BD) {
        backup_database_tables('*');
        $const->save_const('dttime_copy_BD', time() + (7 * 24 * 60 * 60));


        // удалить все доки старше 93 -а дней три месяца
        //$txt_sql = "DELETE FROM  `DocHd` WHERE (TO_DAYS(NOW()) - TO_DAYS(`DocHd`.`DataDoc`) > 93 )  ";
        // и не счет
        $txt_sql = "SELECT * FROM `DocHd` WHERE (TO_DAYS(NOW()) - TO_DAYS(`DocHd`.`DataDoc`) > 93 ) and `VidDoc_id` <> 2";
       
        $sql     = mysql_query($txt_sql, $db);
        
        // Все записи без родителя
        $txt_sql = "DELETE `DocTab`
                    FROM `DocTab`
                    LEFT JOIN `DocHd` ON `DocHd`.`id` = `DocTab`.`DocHd_id`
                    WHERE (`DocHd`.`id` is null)";

        $sql = mysql_query($txt_sql, $db);


        // Удалим записи из таблицы обмена
        $txt_sql = "DELETE FROM `log_obmen`
                        WHERE ( TO_DAYS( NOW( ) ) - TO_DAYS( `log_obmen`.`datetime` ) >7 ) ";
        $sql = mysql_query($txt_sql, $db);

    }





}

?>
<!--
    обмен данными
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title></title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />
        <script src="mylib.js?v<?php echo  date('d.m.y') ; ?>" ></script>
        <script type="text/javascript">
            //**********************baba-jaga@i.ua**********************
            function on_load(){
                if(!check_user() )  return;
                document.getElementById("f_date1").value = <?php echo '"' . $date1 . '"'; ?> ;
                top.content.location  = "obmen_log.php";
            }

            //**********************baba-jaga@i.ua**********************
            function priem(){
                //принимаем данные
                top.top_menu.location = "obmen_menu.php?priem=1";
            }

            //**********************baba-jaga@i.ua**********************
            function otpravka(){
                //принимаем данные
                var date1 = document.getElementById("f_date1").value;
                top.top_menu.location = "obmen_menu.php?otpravka=1&date1=" + date1  ;
            }

              //**********************baba-jaga@i.ua**********************
            function open_calendar(btn){
               var  popupURL = 'calendar.php?btn='+btn;
               var popup = window.open(popupURL, null, 'toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=0, width=214, height=214 , left=200 ,top=210 ');
            }
            
            function show_all(){
              top.content.location  = "obmen_log.php?show=1";
              //alert(Math.round(+new Date()/1000));
               //alert('show'); 
            }

        </script>
    </head>
    <body onload="javascript:on_load()" >


        <div style=" padding: 5px 0px 0px 0px; font-size: 18px; color: #AA0000;
                     text-align: left ;  width: 538px; /* background: #fc0; */" >

                Обмен данными

        </div>

        <div style=" padding: 7px 0px 0px 0px;  " >

                <input type="button" id="priem" name="priem" onmouseup= "javascript:priem()" value="  Получить данные  "    >
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                  дата:
                    <input type="text" size="8" id="f_date1" name="f_date1"  readonly="true" >
                    <button id="f_btn1"  onmouseup="javascript:open_calendar('f_date1')" >...</button>

                <input type="button" id="otpravka" name="otpravka" onmouseup= "javascript:otpravka()" value="  Отправить данные "    >
                <input type="button" style="margin-left: 50px" id="show_all" name="show_all" onmouseup= "javascript:show_all()" value=" Показать весь журнал "    >

        </div>


        <div style=" padding: 9px 0px 0px 0px; font-size: 18px; color: #AA0000;
                     text-align: center ;  width: 538px; /* background: #fc0; */" >

                Журнал обмена

        </div>



        <div style=" padding: 5px 0px 0px 0px " >
            <table cellspacing='0' border='0' class="Design5" >
                <col width="180px">
                <col width="180px">
                <col width="280px">
                <col width="90px">


                <thead>
                    <tr>
                        <th class="Odd">Дата время</th>
                        <th>Событие</th>
                        <th  class="Odd">Комментарий</th>
                        <th >Опреатор</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

            </table>
        </div>


    </body>
</html>
