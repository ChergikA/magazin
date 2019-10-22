<?php

require("header.inc.php");
include("milib.inc");
$const = new cls_my_const;
$arr_files = '' ; // ������ ������ � �������: ��������  ��� �����
$file_new_ver = ''; // ��� ���� � ������������ ��� ���������

$date1 = date('d.m.y');
if (isset($_GET['time'])) {
  $_SESSION['obmen_time'] = $_GET['time'];
}

//require("data/newver.php");

//***********************************  baba-jaga@i.ua  **************************************
// ������ � ������  ������
function save_log( $sobitie, $info ){
    global $db;

    // �������� �����: ������, ������

    $txt_sql = "INSERT INTO `log_obmen` ( `sobitie`, `info`, `user`)
                                VALUES ('".$sobitie."', '".$info."', '".  name_user() ."');";
    $sql     = mysql_query($txt_sql, $db);


}

//***********************************  baba-jaga@i.ua  **************************************
// ������� � ���� ��������� ���, ���� ���� ����������
function save_log_price($id_tovar, $new_price, $new_price_opt, $old_price, $old_price_opt ){

    //�������� ������� ���� ���, ���� ��� ���
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
  
  
  
  
  return $save_; // ���� ���� ��������� � ������� ������ ������� ������ �� ��������
 
    
}

//***********************************  baba-jaga@i.ua  **************************************
// ����������� � ���������� �������� ������ ����� ����� ��� �������� � ������ ��� ������������ ��� �����
// ��� ������ ���� � ����� ����
function file_in_zip_in_ftp($f_name,$f_zip){
    global $const;
    if (is_writable($f_name)) { //���� ���� ����� ����������� � ����������
        //echo "�����������: $f_name <br>";
        //save_log('�������� ������', '������ ���� ��� ��������: ' . $f_name );

        //$f_zip    = 'data/' . $const->kod_sklad . '_backup.zip';
        $filename = str_replace('data/', '', $f_name);   // ��� ���� ���������� � zip
        $zip      = new ZipArchive;
        $res      = $zip->open($f_zip, ZipArchive::CREATE);
        if ($res === TRUE) {
            $zip->addFile($f_name, $filename);
            $zip->close();

            $connect = ftp_connect($const->ftp_server);
            if (!$connect) {
                //echo("<H1>������������ � �������</H1>");
                $err = '������������ � �������';
                save_log('������ �������� ������', $err );
                return $err ;
            }

            $result = ftp_login($connect, $const->ftp_l, $const->ftp_p);
            if(!$result){
                $err = '��� ����������� � �������, �������� �� ������ ����� ��� ������';
                save_log('������ �������� ������', $err );
                return $err ;
            }
             // ��������� ���������� ������
            ftp_pasv($connect, true);
            //echo("���������� �����������<br>");
            if(! ftp_chdir($connect, $const->ftp_catalog) ){ // ������� � �������

                $err = '�� ����� ������ ����������� �� �������';
                save_log('������ �������� ������', $err );
             }

            $filename = str_replace('data/', '', $f_zip);   // ��� ��� ����
            if (ftp_put($connect, $filename, $f_zip, FTP_BINARY)) {
                //echo "������ ����������. ����� ���������� ��������\n";
                save_log('�������� ������', '����: '.  $filename. '  ��������� �� ������. '   );

            }
            else {
                //echo "�� ������� ��������� ������\n";
                save_log('������ �������� ������', "�� ������� ��������� ������" );
            }

            ftp_quit($connect);  // ��������� ���������t
        }
        else {
            //echo 'f';
            save_log('������ �������� ������', "failed zip" );
        }

        unlink($f_name);
        unlink($f_zip);

    }
        else {
        save_log('������ �������� ������', "�� ���������� �����" . $f_name );
    }


}

//***********************************  baba-jaga@i.ua  **************************************
// ������ ����� ��
function backup_database_tables($tables){
    //global $db;
    global $const;
    // echo '������';
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
        // ����������� � ����������
        file_in_zip_in_ftp($f_name,$f_zip);




}


//����� ������***********************************  baba-jaga@i.ua  **************************************
function tovars($xml) {
    //  <Tovars><Tovar Kod="1230" Name="����� �������  � (�������)  33" Price="45" Ost="0" Sostav="" DatePrihod="" del="0"/>
    global $db;
        
    $open_log_price = 0;

    $tv = $xml->Tables->Tovars;
    foreach ($tv->Tovar as $tovar) {
        //echo $tovar["Kod"] . '&nbsp;&nbsp;&nbsp;' . cnv($tovar["Name"]) . '<br>' ;
        //Tovar Kod="020722"
        //KodeBar="4820058221105"
        //Name="������  8�  70 �/� ��-9 Mix"
        //name_in_cennik="������  8�  70 �/� ��-9 Mix"
        //Price="2.1"
        //PriceOpt="2.1"
        //Ost="0"
        //NDS="20%"
        //DatePrihod="2012-03-07"
        //
        //del="0"/>
        //���������� HtampDateTime="              "

        $kod = $tovar["Kod"];
        $kode_bar = trim( str_replace("'", "",  $tovar['KodeBar'])); // ������ ������ ��������
        if(strlen($kode_bar) < 4 ) $kode_bar=$kod;


        $nds = 1;

        $txt_sql1 = "SELECT `id_tovar`,`Kod1C`, `Kod`,`Price`,`PriceOpt`,`edit_time`,`name_in_cennik`,`v_upakovke`,`flg_edit` FROM `Tovar` WHERE `Kod1C` = '" . $kod . "'";
        $sql_1     = mysql_query($txt_sql1, $db);
        $s_arr   = mysql_fetch_array($sql_1, MYSQL_BOTH);
       
         // ��� ���������� � ����� �������� ���������� ������ ������� ������ �� ����1�  
         // ��� ���������� �� ������ �� ����� ��� �� ������������ 
        
//������ �� ����� ���� �� �����
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
                $id_t = mysql_insert_id(); // ���������� id ������ ��� ����������� ������
                $log_price= save_log_price($id_t, $tovar['Price'], $tovar['PriceOpt'], 0, 0);
                if($log_price == 1)$open_log_price = 1;
                
            }

           // echo '=' . $txt_sql . '<br>' ;

        } else {
                       //������� � ��������� ��� � ���������
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
                // ��������� ������ ���� � �������

             //    $txt_sql3 = "UPDATE `Tovar` SET
             //                 `Price` = '" . $tovar['Price']
             //           . "', `PriceOpt` = '" . $tovar['PriceOpt']
             //           . "', `PriceIn` = '" . $tovar['PriceIn'] 
             //           . "', `Ostatok` = '" . $tovar['Ost']
             //           . "' WHERE `Tovar`.`id_tovar` = " . $s_arr['id_tovar'] . ";";
             //   }else{ // ��������� �� ������� � ������ �����������
              //      $txt_sql3 = $txt_sql_fullupd;
              //  }
               // $sql3     = mysql_query($txt_sql3, $db);

            }

        }
    

    save_log('��������� ������', '������ ����������� �����-����');
    if( $open_log_price == 1 ){
        save_log('��������� ���', "�������� ������ � ����������� ������" );
    }
}

//����� ������***********************************  baba-jaga@i.ua  **************************************
function firms( $xml ){
  //<Firms><Firm Kod="3" Name="�������� �. �." Tel="80988814428" del="0"/>

  global $db;
  $fr = $xml->Tables->Firms;
  
  //echo 'count=' . count($fr)  ;
  if(count($fr) == 0)      return;
  foreach ($fr->Firm as $firm) {
        //���� ���� � ���� - ������� ����� �������
      $kod = $firm["Kod"];

      $txt_sql = "SELECT id FROM `firms` WHERE `Kod1C` = " . $kod ;
      $sql     = mysql_query($txt_sql, $db);
      $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);

      if( $s_arr['id'] == NULL ){
          $txt_sql = "INSERT INTO `firms` ( `Kod1C`,  `name_firm`)
                                    VALUES ('".$kod."', '". cnv( $firm["Name"]) ."');";

           $sql2 = mysql_query($txt_sql, $db);
           $id_firm = mysql_insert_id(); // ���������� id ������ ��� ����������� ������

           //save_log('query', $txt_sql);

      } else {
         $id_firm  = $s_arr['id'];
      }

      $fromcheck = 1; if(cnv($firm["nal_bn"]) == '��� �������')$fromcheck = 0;

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


//����� ������***********************************  baba-jaga@i.ua  **************************************
function users( $xml ){

    //<Users><User Kod="00013" Name="����� ����" L="����" P="1111" del="0"/>
  global $db;
  $fr = $xml->Tables->Users;
  if(count($fr) == 0)      return;
  foreach ($fr->User as $user) {
      //���� ���� � ���� - ������� ����� �������
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


//����� ������**************************  baba-jaga@i.ua  **************************************
function klients( $xml ){



  global $db;
  $kl = $xml->Tables->Klients;
    foreach ($kl->Klient as $klient) {
        $edit = FALSE;
        //���� ���� � ���� - ������� ����� �������
        //������� ���� �� ��������, ����� �� ����
        $id_klient = NULL;
        $flgnew = true;
        $diskont = $klient["diskont"];
        if(trim($diskont)!=''){
            $txt_sql = "SELECT `id_`,`edit_time` FROM `Klient` WHERE `diskont` = '" . $diskont . "'";
            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            if ($s_arr['id_'] != NULL){ // ����� �� ��������

                $id_klient = $s_arr['id_'];
                $htamp_1c  = $klient["HtampDateTime"];
                $htamp_my  = htampdatetime_to_num($s_arr['edit_time']);
                $flgnew    = FALSE;
                //if ($htamp_1c > $htamp_my)  
                 $edit      = TRUE;

            }
        }

        $kod     = $klient["Kod"];
        if($id_klient==NULL){ // ������ �� ����
            $txt_sql = "SELECT `id_`,`edit_time` FROM `Klient` WHERE `kod` = '" . $kod . "'";
            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            if ($s_arr['id_'] != NULL){ // ����� �� ����

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
        
        if($flgnew){ // ����� ������
                $txt_sql = "INSERT INTO `Klient` (`name_klient`, `diskont`, `kod`)
                VALUES ('" . cnv($klient["Name"]) . "','" . $diskont . "','" . $diskont . "');";

                $sql       = mysql_query($txt_sql, $db);
                $id_klient = mysql_insert_id(); // ���������� id ������ ��� ����������� ������
                $edit      = TRUE;


        }

        if($edit){
          // ��������� ������ ���� ������ ��� ���������, ���� ��������� �����
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

    save_log('��������� ������', '�������� ������ ��������');


}

//����� ������**************************  baba-jaga@i.ua  **************************************
function consts( $xml ){

        //<Consts><KodSklad>Gr1</KodSklad>
  global $db;
  $cn = $xml->Consts;
  foreach ($cn->children() as $constanta) {
        //echo 'const='.$constanta . '  =' . $constanta->getName() . '<br>';
        $nm_const = $constanta->getName();

        //���� ���� � ���� - ������� ����� �������
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

//����� ������**************************  baba-jaga@i.ua  **************************************
function docs($xml) {
    //<Docs><Prihods><Prihod
    //Prihod nomDoc="1018" dateDoc="2010-12-10"

    global $db;
    $my_cnst = new cls_my_const();
    $docs = $xml->Docs->Prihods;
    if (count($docs) != 0) {


        $id_viddoc = id_viddoc('�����������');

        foreach ($docs->Prihod as $doc) {

            //����� ���� ���� � ���� - ������� ����� �������
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
                        '" . id_status('�� �����') . "', '" . $my_cnst->id_klient_def . "', '" . id_user() . "',
                        '" . $my_cnst->id_firma_def . "', '" . $doc['dateDoc'] . "', '" . trim(cnv($doc)) . "');";
                $sql = mysql_query($txt_sql, $db);
                $id_doc = mysql_insert_id(); // ���������� id ������ ��� ����������� ������
                // foreach ($doc->sDoc as $str_doc) {
                //<sDoc strNom="1" KodTov="4765" Kvo="1"/>
                //      upd_tabdoc($id_doc, $str_doc['KodTov'], 0, $str_doc['Kvo'], 0, 0, 0);
                //  }

                save_log('��������� ������', "������� ��������� �_" . cnv($doc['nomDoc']) . " ��:" . $doc['dateDoc'] . " ��� �������");
            } else {

                // ��� ������ �����, �������� ������ � ������ ���� ��� ��� �������
                $id_doc = $s_arr['id'];
                if (!write_doc($id_doc))
                    continue;
            }

            //��������� ����� ��� ������ � ������� �� �����
            $txt_sql = "DELETE  FROM `DocTab` WHERE `DocHd_id` = '" . $id_doc . "' ";
            $sql = mysql_query($txt_sql, $db);
            $strdoc = $doc->sDoc;
            foreach ($doc->sDoc as $str_doc) {
                //<sDoc strNom="1" KodTov="4765" Kvo="1"/>

                $err = upd_tabdoc($id_doc, $str_doc['KodTov'], 0, $str_doc['Kvo'], 0, 0, 0);
                if ($err != '')
                    save_log("������ ������� ����������", $err);
            }
            save_log('��������� ������', "�������� ��������� �_" . cnv($doc['nomDoc']) . " ��:" . $doc['dateDoc'] . " ��� �������");
        }
    } //count docs
    // �������� � ��������������� ***************************
    $docs = $xml->Docs->Nehvatki;
    if (count($docs) != 0) {
        $id_viddoc = id_viddoc('������������');

        foreach ($docs->Nehvatka as $doc) {

            //����� ���� ���� � ���� - ������� ����� �������
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
                        '" . id_status('�����') . "', '" . $my_cnst->id_klient_def . "', '" . id_user() . "',
                        '" . $my_cnst->id_firma_def . "', '" . $doc['dateDoc'] . "', '�������� �� �����: " . trim(cnv($doc['Pometka'])) . "');";
                $sql = mysql_query($txt_sql, $db);
                $id_doc = mysql_insert_id(); // ���������� id ������ ��� ����������� ������
                // foreach ($doc->sDoc as $str_doc) {
                //<sDoc strNom="1" KodTov="4765" Kvo="1"/>
                //      upd_tabdoc($id_doc, $str_doc['KodTov'], 0, $str_doc['Kvo'], 0, 0, 0);
                //  }

                save_log('��������� ������', "�������� ������ � �������� �_" . cnv($doc['nomDoc']) . " ��:" . $doc['dateDoc']);
            } else {

                // ��� ������ �����, �������� ������ � ������ ���� ��� ��� �������
                $id_doc = $s_arr['id'];
                if (!write_doc($id_doc))
                    continue;
            }

            //��������� ����� ��� ������ � ������� �� �����
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
                    save_log("������ ������� ��������", $err);
            }
        }
    }//CountDocs nehvatki
}

//����� ������**************************  baba-jaga@i.ua  **************************************
function schets($xml) {
    //<Schets><Schet
    //<Schet nomDoc="��-1385   " dateDoc="2013-07-24" SumOtg="900">

    global $db;
    
     $docs = $xml->Docs->Schets;
     if(count($docs) == 0)      return;
    
    $my_cnst = new cls_my_const();

    $id_viddoc = id_viddoc('����');


    foreach ($docs->Schet as $doc) {

        //����� ���� ���� � ���� - ������� ����� �������
        //
        //echo ' doc =' .  $doc->getName() . '  nom = ' .  trim( cnv($doc['nomDoc'])) . '<br>' ;


       /// �������� ��� ������ �����, ������������ �����,
       // ������ ������
       $nomdoc =  trim(cnv($doc['nomDoc']));
       $nomdoc_old = "";
       if( strstr( $nomdoc , $my_cnst->kod_sklad )  ){ // ������ ��� ������ � ������ ���������

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




// ������ ����� ���� ��������� � ����� ������� � ����� ����
      $txt_sql = "SELECT * FROM `DocHd`
                  WHERE `nomDoc` = '" . $nomdoc . "' AND `VidDoc_id` = '" . $id_viddoc .
                "' AND `DataDoc` = '" . $doc['dateDoc'] . "'" ;// AND `Pometka` LIKE '" . trim(cnv($doc)) . "'   ";


        $id_doc = ''; $sumotg = 0;
        $sumopl = $doc['SumKOtg']; //- $doc['SumOtg']; //����������� = �������������� - ��������������
 
        // � ������� ����� ������ ����������
        $sql   = mysql_query($txt_sql, $db);
        while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
                    
            $id_doc_ = $s_arr['id'];
            $txt_sql_eit = "  UPDATE  `DocHd` SET  `Pometka`='" . trim(cnv($doc['pometka'])) . "' WHERE `id`='" . $id_doc_ . "'";
            $sql_e     = mysql_query($txt_sql_eit, $db);
        }
        
        

        
        if($sumopl <= 0)  continue; // ���� ���� �� ������� ���� ��� ������

        $sql   = mysql_query($txt_sql, $db);
        while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {


            
            if (!write_doc($s_arr['id'])){ // ��������� ����� ��������� ������
                $sumotg=$sumotg+$s_arr['SumDoc'];

            }else { // ���� ���� ������� ����� �������������
                save_log('<H3>������� ����</H3>', "�������� ������ �� ����� �_" . cnv($doc['nomDoc']) . " ��:" . $doc['dateDoc']);
                $id_doc = $s_arr['id'];
            }

        }
        
 


        if($id_doc==''){ // �� ����� ���� "�����"
            if($sumotg==0){ // ���� ������� �  1C
                save_log('<H3>����� ����</H3>', "������� ���� ��� ������ � ������ ������ �_" . cnv($doc['nomDoc']) . " ��:" . $doc['dateDoc']);
            }elseif ($sumotg < $doc['SumKOtg'] + 1) {
                save_log('<H3>��������� ����</H3>', "�������� ��� ���� ������ �� ����� �_" . cnv($doc['nomDoc']) . " ��:" . $doc['dateDoc']);
            }elseif ($sumotg == $doc['SumOtg']) {
                if($doc['SumKOtg']>0){
                   save_log('<H3>��������� ����</H3>', "�������� ��� ���� ������ �� ����� �_" . cnv($doc['nomDoc']) . " ��:" . $doc['dateDoc']);
                }

            }else                continue;

           $sumvkassu = $doc['SumKOtg'];
           if($sumotg > $doc['SumOtg'] ){ //  ���� ����� ����� ������������ ������,
                 // �� ����� � ������ = ���� � ������ ����� ������� ����� ����������
                 // �.�. ��� ��� ���������, � ��� �� �����

               $sumvkassu =   $doc['SumKOtg'] - $sumotg ;

           }

          //echo 'sumvkassu=' . $sumvkassu . '  sumotg='.$sumotg . '  v1cOTG=' .$doc['SumOtg'] ;

           $txt_sql_new = "INSERT INTO `DocHd` ( `nomDoc`, `VidDoc_id`, `statusDoc`, `sum_v_kassu` , `Klient_id`, `users_id`, `firms_id`, `DataDoc`,`Pometka`)
              VALUES ( '" . trim(cnv($doc['nomDoc'])) . "', '" . $id_viddoc . "',
                        '" . id_status('�����') . "', '" . $sumvkassu  . "', '" . $id_klient . "', '" . id_user() . "',
                        '" . $id_firm . "', '" . $doc['dateDoc'] . "', '" . trim(cnv($doc['pometka'])) . "');";

            $sql    = mysql_query($txt_sql_new, $db);
            $id_doc = mysql_insert_id(); // ���������� id ������ ��� ����������� ������


        } else { // �������� ����
            $txt_sql = "  UPDATE  `DocHd` SET  `sum_v_kassu`='" . $sumopl . "' WHERE `id`='" . $id_doc . "'";
            $sql     = mysql_query($txt_sql, $db);

        }


        //��������� ����� ��� ������ � ������� �� �����
         $txt_sql = "DELETE  FROM `DocTab` WHERE `DocHd_id` = '". $id_doc ."' ";
         $sql     = mysql_query($txt_sql, $db);
         $strdoc = $doc->sDoc;
              foreach ($doc->sDoc as $str_doc) {
        //<sDoc strNom="1" KodTov="4765" Kvo="1"/>

             $err = upd_tabdoc($id_doc, $str_doc['KodTov'] , $str_doc['Cena'] ,  $str_doc['Kvo'] ,$str_doc['Skidka'], 0, 0);
             if($err != '') save_log("������ ������� ����� id=" . $id_doc , $err);
              }

    }
}



//�������� ������**************************  baba-jaga@i.ua  **************************************
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
        //save_log('�������� ������', "�� �������: " . $srt_arr['name_klient'] );
    }



}

//�������� ������**************************  baba-jaga@i.ua  **************************************
function tovars_($tovars){

   global $db;

       // ��������� ���� ������� ����� ��������
    $txt_sql = "UPDATE `Tovar` SET `edit_time`='". date('Y-m-d') . " " . date('H:i:s')  ."' WHERE `flg_edit`=1";
    $sql = mysql_query($txt_sql, $db);


    //��������
   $txt_sql =  "SELECT * FROM `Tovar` WHERE `flg_edit` = 1  ";

   $sql = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_array( $sql, MYSQL_ASSOC ) ) {
        $tovar = $tovars->addChild('Tovar');
          foreach($srt_arr as $key => $value)
            {
              $tovar->addAttribute($key, cnv_( $value) );
               //echo "$key = $value <br />";
            }
        //save_log('�������� ������', "��������� � ������: " . $srt_arr['Tovar'] );
    }



}

//�������� ������**************************  baba-jaga@i.ua  **************************************
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

//�������� ������**************************  baba-jaga@i.ua  **************************************
function docs_($docs, $dt_doc) {

    global $db;

    $idst = id_status('�����'); // ����� �� ������ ����, ��������� �� ����������
    // ��������� ���� ��� ���� ���� ������� �� ��������� ����
    // ���� ����������� � ���� - ����������
    // $idpost = id_viddoc('�����������');

    $txt_sql = "SELECT * FROM `DocHd` WHERE `statusDoc` = '" . $idst . "' AND `DataDoc` = '" . $dt_doc . "' ";


    $sql = mysql_query($txt_sql, $db);

    while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
        if ($srt_arr['VidDoc_id'] == id_viddoc('�����������'))
            continue;
        if ($srt_arr['VidDoc_id'] == id_viddoc('����'))
            continue;

        $err = '�� ������ �������� �_' . viddoc($srt_arr['VidDoc_id']) . ' �_' . $srt_arr['nomDoc'] . ', ������ �� ����������.';
        //echo $err;
        save_log('������ �������� ������', $err);
        return $err;
    }


    //
    $idst = id_status('������'); // ����� �� ������ ����, ��������� �� ����������
    // ��� �������, ���� �� ������������ ���, � ���� �� ������, ��� ����� ���� � �� ������� ���
    // ���������������, �� ��� ���� ��������� TimeShtamp

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
            // ������� ������ ����������
            addstrdoc($doc, $srt_arr['id']);

            // ���� ��������
            $t_sql = "UPDATE `DocHd` SET `flg_otpravlen` = '1' WHERE `DocHd`.`id` = '" . $srt_arr['id'] . "';";
            $upd   = mysql_query($t_sql, $db);
        }
    }


    return '';
}

//�������� ������**************************  baba-jaga@i.ua  **************************************
// ���������� ������ �� ��������� ����
function otpravka(){

    global $const;

    $date1   = $_GET['date1'];
    $dt      = str_replace('.', '', $date1);
    $date1 = str_to_datesql($date1);
    $my_comp = $const->kod_pc; // ��� ����� � �������� ������� �������
    $skl     = $const->kod_sklad; # �������� 1� ����� ��� �����
    $f_name  = "data/" . $skl . "_" . $my_comp . '_' . $dt . '.xml';



    $handle = fopen($f_name, 'w');
    $str    = "<?xml version='1.0' encoding='windows-1251' ?> ";
    $str .= "\n";
    fwrite($handle, $str);
    $str    = '<Data DateTime="' . date('Y-m-d') . '" User="' . name_user() . '" Magazin="������" KodMAgazin="Gr1"></Data>';
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


    // ����������� � ����������
    save_log('�������� ������', '������ ���� ��� ��������: ' . $f_name );
    $f_zip    = str_replace('.xml', '.zip', $f_name);  //������ txt �� zip
    file_in_zip_in_ftp($f_name,$f_zip);

}

//����� ������**************************  baba-jaga@i.ua  **************************************
//�������� ��� ����� ��� ���
function priem() {
    // �� ������� ����� ������� ������, �.�. �������� ��� �����������
    // ��. ����� � ����� ������, �� �� ����� mySQL
    // � �����. ������� ��������� �������� � ������������ ����
    // ����� � ���� ����� � ����������, ���� �� ������� ������, �� ������ ,
    // ����� � ���� ���������� �� ���������� ver785.zip
    // ���� ����� ������, �� ������, ��������� � ������ ������ � ���������
    // ����� ����, ��� ��������� ��� ����� � �������, - ������������ ����
    // � ������� ����������� ����. �.�. ����� ���������� ������� � ���� � �� ��������,
    // ��� ����� ��� ������ � ��������� ����� ������
    // � ��������� ���������� ������ ������ ����� ��������� ��� ������

    global $const;
    global $arr_files;
    global $file_new_ver;

    $startnmfile  = 's_' . $const->kod_sklad . '_';
    $startverfile = 'ver';


    $endfile = $const->fileendpriem;
    $nom_end_ok_file = 0;
    if(strlen($endfile) > 1) $nom_end_ok_file  = substr($endfile, strlen($startnmfile) , strlen($endfile) - 4  );//����� ����������


    $nomver = 1;
    $filever  = $const->version;
    //if(strlen($filever) > 1)$nomver = substr($filever , strlen($startverfile) , strlen($filever) - 4  );//����� ����������
    if(strlen($filever) > 1)$nomver = substr($filever , strlen($startverfile)   );
    $nomver = str_replace('.zip','' ,$nomver);

    $connect = ftp_connect($const->ftp_server);
    if (!$connect) {
       // echo("<H1>������������ � �������</H1>");
        $err = '������������ � �������';
        save_log('������ ��������� ������', $err );
        return $err;
    }

    $result = ftp_login($connect, $const->ftp_l, $const->ftp_p);
    if(!$result){
         $err = '�� ������ ����������� � �������';
        save_log('������ ��������� ������', $err );
        return $err;
    }
    // ��������� ���������� ������
    ftp_pasv($connect, true);

    //echo("���������� �����������<br>");
    save_log('��������� ������', "���������� � �������� ����������� ");
    ftp_chdir($connect, $const->ftp_catalog); // ������� � �������

    $file_list = ftp_nlist($connect, ".");
    if (is_array($file_list)) {
        foreach ($file_list as $file) {

            //file=s_Gr1_20121221184154.zip
            //file=s_Gr1_20121221184954.zip
            //file=s_Gr1_20121221185215.zip
            
            //echo '='.$file;

            if (( strpos($file, $startnmfile) !== 0) and ( strpos($file, $startverfile) !== 0 ))
                continue;

            //���� ��� ���� ������
            if (strpos($file, $startverfile) !== 0) {
                //echo 'file=' . $file . '. startnmfile='.$startnmfile.'<br>';
                $nomfile =  substr($file, strlen($startnmfile) , 14 ); //����� ������ ����� ������
                //echo 'nomfile=' . $nomfile . '. nom_end_ok_file='.$nom_end_ok_file.'<br>' ;
                if ($nomfile > $nom_end_ok_file) { // ��������� ��� ��������
                    if (ftp_get($connect, "" . "data/" . $file, $file, FTP_BINARY)) {
                        //echo "������� ���� ������ ". $file ." <br>";
                        save_log('��������� ������', "������� ���� ������ ". $file ." ");
                        $arr_files[$nomfile]= "data/" . $file ;
                    }
                    else {

                        save_log('������ ��������� ������',"�� ������� �������� ����� ������ " );
                    }
                }
            }
            else { // ��� ���� ������
                //$nomfile = substr($file, strlen($startverfile) , strlen($file) - 4  );//����� ����������
                $nomfile = substr($file, strlen($startverfile)  );//����� ����������
                $nomfile = str_replace('.zip','' ,$nomfile);
                //echo '=' . $nomfile . '  jkl dth =' . $nomver . '<br>' ;
                if ($nomfile > $nomver) { // ��������� ��� ��������
                    if (ftp_get($connect, "" .  $file, $file, FTP_BINARY)) {
                        //copy("data/" . $file, $file);
                        //unlink("data/" . $file);
                        $file_new_ver = $file;
                        //echo "������� ���� ���������� ��������� ". $file ." <br>";
                        save_log('��������� ������', "������� ���� ���������� ��������� ". $file ." .");
                    }
                    else {
                        //echo "�� ������� �������� ���� ���������� ���������\n";
                         save_log('������ ��������� ������',"�� ������� �������� ���� ���������� ���������");
                    }
                }
            }
        }
    }

    ftp_quit($connect);  // ��������� ���������t
}

//����� ������**************************  baba-jaga@i.ua  **************************************
//���� � ����� ���� ���� ����������, ���������� ��� � �������
function update_program() {

    global $file_new_ver;
    global $db;
    
    // ������������ ��� �����
    //require("data/newver.php");
    
   //throw new Exception('��� ������ �����.');

    //echo 'file_new_ver=' . $file_new_ver ;
    if ($file_new_ver == '')
        return;

    // ���������
    $zip = new ZipArchive;
    if ($zip->open($file_new_ver) === true) {

        // ������ ������ ��� ���� ������
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
        //echo "<br> ������ ��������� � ��������� �� ������";
        //save_log('��������� ������', "� ". $file ." .");
    }
    else {
        //echo "<br> �� ������� ������� ����� � �������";
        save_log('������ ��������� ������', "�� ������� ������� ����� � ������� ���������� ���������");
        return;
    }
    //echo "�������� ���������� ���������." ;
    //���� ���� , �� �������� � ������
    if (is_writable("newver.php")) {
        include("newver.php");
        upd_mysql();
        //require("newver.php");
        unlink("newver.php");
    }

    $txt_sql = "UPDATE `const` SET `name` = '" . $file_new_ver . "' WHERE `kod` = 'Version' ;";
    $sql     = mysql_query($txt_sql, $db);

    save_log('��������� ������', "������� ��������� ���� ���������� ��������� ". $file_new_ver ." .");



}


// ������ ������ ����� ������
if (isset($_GET['priem'])) {

    //echo '������ ������ ����� ������ <br>';
    save_log('������ ��������� ������ � �������', "");
    priem(); // �������� ������� � ������� � ����� /data
    // ����� � ������ ������ � �������
    //����� �� ������ ������� ver, ����� ������ � ������� �����������
    update_program();

    // ���� ���� �������� ����� ������������!
    if (is_array($arr_files)) {

        ksort($arr_files);

        foreach ($arr_files as $index => $f_name) {

            //   $f_name = 'data/s_Gr1.xml' ;
            // ���������
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

            unlink($f_name); // �� ��� �� �����
         //������� ���������
        $name_f  = str_replace('data/', '', $f_name);
        $txt_sql = "UPDATE `const` SET `name` = '" . $name_f . "' WHERE `kod` = 'nameFilePriem' ;";
        $sql     = mysql_query($txt_sql, $db);

        }

    }
        else {
            save_log('��������� ������', "��� ������ ��� ��������� ");
    }

    save_log('��������� ������', "������ �������� � ����������.");

}

// ������ ������ �������� ������
if (isset($_GET['otpravka'])) {

    //echo '������ ������ �������� ������ <br>';
    save_log('������ �������� ������ �� ������', "");
    otpravka();


    //require("bakcup.php"); // �������� ����� ��


    //$nextWeek = time() + (7 * 24 * 60 * 60);

    // ����� �� ��� � ������
    if (time() > $const->dttime_copy_BD) {
        backup_database_tables('*');
        $const->save_const('dttime_copy_BD', time() + (7 * 24 * 60 * 60));


        // ������� ��� ���� ������ 93 -� ���� ��� ������
        //$txt_sql = "DELETE FROM  `DocHd` WHERE (TO_DAYS(NOW()) - TO_DAYS(`DocHd`.`DataDoc`) > 93 )  ";
        // � �� ����
        $txt_sql = "SELECT * FROM `DocHd` WHERE (TO_DAYS(NOW()) - TO_DAYS(`DocHd`.`DataDoc`) > 93 ) and `VidDoc_id` <> 2";
       
        $sql     = mysql_query($txt_sql, $db);
        
        // ��� ������ ��� ��������
        $txt_sql = "DELETE `DocTab`
                    FROM `DocTab`
                    LEFT JOIN `DocHd` ON `DocHd`.`id` = `DocTab`.`DocHd_id`
                    WHERE (`DocHd`.`id` is null)";

        $sql = mysql_query($txt_sql, $db);


        // ������ ������ �� ������� ������
        $txt_sql = "DELETE FROM `log_obmen`
                        WHERE ( TO_DAYS( NOW( ) ) - TO_DAYS( `log_obmen`.`datetime` ) >7 ) ";
        $sql = mysql_query($txt_sql, $db);

    }





}

?>
<!--
    ����� �������
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
                //��������� ������
                top.top_menu.location = "obmen_menu.php?priem=1";
            }

            //**********************baba-jaga@i.ua**********************
            function otpravka(){
                //��������� ������
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

                ����� �������

        </div>

        <div style=" padding: 7px 0px 0px 0px;  " >

                <input type="button" id="priem" name="priem" onmouseup= "javascript:priem()" value="  �������� ������  "    >
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                  ����:
                    <input type="text" size="8" id="f_date1" name="f_date1"  readonly="true" >
                    <button id="f_btn1"  onmouseup="javascript:open_calendar('f_date1')" >...</button>

                <input type="button" id="otpravka" name="otpravka" onmouseup= "javascript:otpravka()" value="  ��������� ������ "    >
                <input type="button" style="margin-left: 50px" id="show_all" name="show_all" onmouseup= "javascript:show_all()" value=" �������� ���� ������ "    >

        </div>


        <div style=" padding: 9px 0px 0px 0px; font-size: 18px; color: #AA0000;
                     text-align: center ;  width: 538px; /* background: #fc0; */" >

                ������ ������

        </div>



        <div style=" padding: 5px 0px 0px 0px " >
            <table cellspacing='0' border='0' class="Design5" >
                <col width="180px">
                <col width="180px">
                <col width="280px">
                <col width="90px">


                <thead>
                    <tr>
                        <th class="Odd">���� �����</th>
                        <th>�������</th>
                        <th  class="Odd">�����������</th>
                        <th >��������</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

            </table>
        </div>


    </body>
</html>
