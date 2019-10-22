<?php
require("header.inc.php");
include("milib.inc");
global $db;

$id_doc = -1;

$my_const = new cls_my_const();
$kvo_def = $my_const->kvo_def;

$h_kod =''; $info_tovar=""; $kvo   = $kvo_def ;
$only_ost='checked'; $only_doc='no';

// проверка на ввод фрагмента кода
if ( isset($_GET['h_kod']) ) {
    $h_kod = $_GET['h_kod'];
}

// проверка на ввод фрагмента наименования
if ( isset($_GET['fragment']) ) {
    $frgm = $_GET['fragment'];
}  else { // возьмем из сесии
    $frgm = frgm_tov_searh();
}

//флажок только остатки передаем через скрытое поле
if ( isset($_GET['txt_only_ost']) ) {
    $only_ost = $_GET['txt_only_ost'];
    //echo 'only_ost='.$only_ost;
    //if( $only == 'no'  ) $_SESSION['only_searh'] = '';
    //if( $only == 'checked'  ) $_SESSION['only_searh'] = $only;
}

//флажок только остатки передаем через скрытое поле
if ( isset($_GET['txt_only_doc']) ) {
    $only_doc = $_GET['txt_only_doc'];
    //echo 'onlydoc='.$only_doc;
    //if( $only == 'no'  ) $_SESSION['only_searh'] = '';
    //if( $only == 'checked'  ) $_SESSION['only_searh'] = $only;
}


// данные о документе
if (isset($_GET['id_doc'])) {
    $id_doc = $_GET['id_doc'];
    
    //echo ''.$id_doc;
    
    if($id_doc!='list'){

        // получим данные для шапки
        $txt_sql = "SELECT `firms`.`name_firm`, `Klient`.`name_klient`,`DocHd`.`id`,`DocHd`.`nomDoc`,"
        .         " `DocHd`.`DataDoc`, `DocHd`.`SumDoc`, `DocHd`.`flg_optPrice` , `StatusDoc`.`nameStatus`,"
        .         "`DocHd`.`SkidkaProcent`, `DocHd`.`Pometka`, `VidDoc`.`name_doc`, `VidDoc`.`php_file`, `users`.`full_name`\n"
        . "FROM `firms`\n"
        . " LEFT JOIN `DocHd`     ON `firms`.`id` = `DocHd`.`firms_id` \n"
        . " LEFT JOIN `Klient`    ON `DocHd`.`Klient_id` = `Klient`.`id_` \n"
        . " LEFT JOIN `VidDoc`    ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id` \n"
        . " LEFT JOIN `StatusDoc` ON `DocHd`.`statusDoc` = `StatusDoc`.`idStatus` \n"
        . " LEFT JOIN `users`     ON `DocHd`.`users_id` = `users`.`id` \n"
        . "WHERE (`DocHd`.`id` = " . $id_doc . " ) ";

    //echo ' = ' . $txt_sql;

        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);

       // $nm_firm = $s_arr['name_firm'];
       // $nm_klient = $s_arr['name_klient'];
        $flgopt = 'розница' ;
        if( $s_arr['flg_optPrice'] == 1) $flgopt = 'опт' ; 
        $skidka_doc =  $s_arr['SkidkaProcent'];
        $nm_doc = "Подбор товара для:" .  $s_arr['name_doc']  . ' №_' . $s_arr['nomDoc'] . ' от:' . datesql_to_str( $s_arr['DataDoc']) ; //Товарный чек №_32 от 28.11.2012
        $php_file = $s_arr['php_file'] ;
        //$nm_doc.= $flgopt;
        //$avtor  =  "автор: " . $s_arr['full_name'];
        //$pometka = addslashes( $s_arr['Pometka']) ; // экранируем символы
    }else { 
        //echo 'подбор товара для ценников';
        $nm_doc = "подбор товара для ценников"  ;
        $only_doc = 'no';
        $php_file = 'doc_cennik.php' ;
    }

}

//+ "&id_tov=" + id_tov + "&kvo=" + kvo;
// если изменили количество Пропишем его в док
if (isset($_GET['id_tov'])) {
    $id_tov = $_GET['id_tov'];
    $kvo    = $_GET['kvo'];
    $id_doc = $_GET['id_doc'];
    
    //echo ''. $id_doc;
    
    $txt_sql = "SELECT `Price`, `PriceOpt`, `Kod1C`, `Kod`, `vid_cennic`  FROM `Tovar` WHERE `id_tovar` = '$id_tov'";
    if($kvo<1)$kvo=0;
    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
    
    if($id_doc!='list'){



        $price = $s_arr['Price'];
        if ($flgopt == 'опт') $price = $s_arr['PriceOpt'];
        
                //получим цену товара
        if ($price == NULL or $price == 0 or $price == '' or $price == '0'  ) {
            echo "<br><br><br>не верная цена      =" . $price . "<br><br><br>";
        }else {
            $kod_t = $s_arr['Kod'];
            $info_tovar = upd_tabdoc($id_doc , $kod_t , $price , $kvo , $skidka_doc, 0 , 0 );
        }
    }else { // в табл ценник
        
        $tipcn =  $s_arr['vid_cennic'];
        
        $txt_sqli = "INSERT INTO `cenniki` (`id_tovar`, `kvo_hkod`, `kvo_cennik`, `kvo`, `tip_cn`)
                VALUES ('$id_tov', '$kvo', '$kvo', '$kvo' , '$tipcn' );";
        $sql     = mysql_query($txt_sqli, $db);
        
    }
   // echo '=' . $info_tovar ;
    
}


//***********************************  baba-jaga@i.ua  ********************************************************
//выводим строки документа
function str_view(){
    global $id_doc;
    global $db;
    global $frgm;
    global $h_kod;
    global $only_ost;
    global $only_doc;


    $txt_sql = "SELECT * FROM `Tovar` ";
    $where    = ' WHERE `flg_del` = 0 ';  $limit   = ' LIMIT 0 , 150 ';
    
     if($only_doc != 'no'){
    
      $txt_sql = "SELECT `Tovar`.* 
                    FROM `DocTab`
                    LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar`"; 
                    
                    //WHERE (`DocTab`.`DocHd_id` ='2157')";
                    $where.=" AND `DocTab`.`DocHd_id` ='$id_doc' ";
                    $limit = '';
      
     }
                
                if ($only_ost != 'no') {
                    $where    = $where . ' AND `Ostatok` >0 ';
                    //$limit = '';
                }
                
                
                $fragment = $frgm;
                if ($fragment == 'фрагмент') $fragment = '';

                if ($h_kod  != '') {
                 
                    $where = $where . 'AND (`Kod` LIKE "%' .  $h_kod  . '%" OR `Kod1C` LIKE "%' . $h_kod . '%")' ;
                   // $limit = '';
                }elseif ($fragment != '') {
            
                    $where = $where . " AND `Tovar` LIKE '%" . addslashes($fragment) . "%' ";
                     //$limit = '';
                }


                $txt_sql = $txt_sql . $where . " ORDER BY `Tovar` ASC" . $limit;
                 //echo  ( '<br>txtsql=' . $txt_sql . '<br>'  );
               
                $sql     = mysql_query($txt_sql, $db);
                while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {

                 $kvo = kvoindoc($id_doc, $s_arr['id_tovar'] );    
                // if($only_doc != 'no'){
                //                     if($kvo < 1 )                         continue;
                // }   
                  
                 if($kvo<1)$kvo='';
                 
                  echo '<tr>';
                        echo '<th class="Odd">'. $s_arr['Kod1C']     .'</th>';
                        echo '<th>'            . $s_arr['Tovar']   .'</th>';
                        echo '<th class="Odd">'. $s_arr['Price']   .'</th>';
                        echo '<th>'            . $s_arr['Ostatok'] .'</th>';
                        echo '<th class="Odd">';
                            echo ' <input type="text" value="'.$kvo.'" name="kvo'.$s_arr['id_tovar'].'" id="kvo'.$s_arr['id_tovar']
                                                .'" style="width: 38px" maxlength="5" onchange="javascript:re_kvo('. "'" . $s_arr['id_tovar']. "'" . ');" >';
                        
                        echo '</th>
                        <th>&nbsp;</th>';
                    echo '</tr>';
                

                }

}

//***********************************  baba-jaga@i.ua  ********************************************************
//есть ли такой товар в документе, и его количество
function kvoindoc($id_doc, $id_tovar) {
    global $db;

    //echo "<br><br><br>" . $id_doc . "<br><br><br>" ;
    if ($id_doc != 'list') {
        $txt_sql = "SELECT sum(`Kvo`) as kvo FROM `DocTab` WHERE `DocHd_id`= '$id_doc' AND `Tovar_id` = '$id_tovar'";
        $sql = mysql_query($txt_sql, $db);
        $s_arr = mysql_fetch_array($sql, MYSQL_BOTH);

         $kvo = $s_arr['kvo'];
    } else {
       $txt_sql =  "SELECT sum(`kvo_hkod`) as kvo_hkod, sum(`kvo_cennik`) as kvo_cennik FROM `cenniki` WHERE `id_tovar` = '$id_tovar'"; 
       $sql = mysql_query($txt_sql, $db);
       $s_arr = mysql_fetch_array($sql, MYSQL_BOTH);
       $kvo = $s_arr['kvo_hkod'];
       if($s_arr['kvo_cennik'] > $kvo) $kvo = $s_arr['kvo_cennik'];
       
    }


    if ($kvo == NULL)
        $kvo = 0;

    return $kvo;
}

?>
<!--

-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title> <?php echo  $nm_doc ; ?> </title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />

        <script type="text/javascript">






            //**********************baba-jaga@i.ua**********************
            function on_load(){

                document.frm_searh.id_doc.value =   <?php echo '"' . $id_doc . '"'; ?>;

                var h_kod =   <?php echo '"' . $h_kod . '"'; ?>;
                document.frm_searh.h_kod.value =  h_kod  ;
                
                var frgm =   <?php echo '"' . $frgm . '"'; ?>;
                document.frm_searh.fragment.value =  frgm  ;
                
                var only =  <?php echo '"' . $only_ost . '"'; ?>  ;
                if(only === 'checked' ) document.frm_searh.only_ost.checked =  only ;
                document.frm_searh.txt_only_ost.value =  only ;
                
                var only =  <?php echo '"' . $only_doc . '"'; ?>  ;
                if(only === 'checked' ) document.frm_searh.only_doc.checked =  only ;
                document.frm_searh.txt_only_doc.value =  only ;


                plays();
               // hide_bar(); // не показывать вспл окно

            }
            
            //**********************baba-jaga@i.ua**********************
            // изменяем количество в строке           
            function re_kvo( id_el ){
                var id_doc   =   <?php echo '"' . $id_doc . '"'; ?>;
                var h_kod    =   <?php echo '"' . $h_kod . '"'; ?>;
                var frgm     =   <?php echo '"' . $frgm . '"'; ?>;
                var only_ost =   <?php echo '"' . $only_ost . '"'; ?>  ;
                var only_doc =   <?php echo '"' . $only_doc . '"'; ?>  ;
             
                var id_tov = document.getElementById('kvo'+ id_el );
                var kvo    = id_tov.value ;
                //alert('='+id_tov.value);
                
                document.location = "podbor_tovar.php?id_doc=" + id_doc + "&h_kod=" + h_kod
                                            + "&fragment=" + frgm + "&txt_only_ost=" + only_ost
                                            + "&txt_only_doc=" + only_doc 
                                            + "&id_tov=" + id_el + "&kvo=" + kvo;
                
            }    
            
                //**********************baba-jaga@i.ua**********************
                // выставляем хидден в зависимости от флажка
               function on_off_only(){
                   //alert("="+ document.frm_searh.only_doc.checked);
                   if(document.frm_searh.only_ost.checked){
                       document.frm_searh.txt_only_ost.value = "checked";
                   }else{
                      document.frm_searh.txt_only_ost.value = "no";
                   }
                   
                   if(document.frm_searh.only_doc.checked){
                       document.frm_searh.txt_only_doc.value = "checked";
                   }else{
                      document.frm_searh.txt_only_doc.value = "no";
                   }
                   
               }


            //**********************baba-jaga@i.ua**********************
            // нажата кнопка закрыть
            function closeWin(){
               window.close();
            }

            //**********************baba-jaga@i.ua**********************
            // по идее дзинькает при нажатии
            function plays() {
              var snd = new Audio("images/ok.wav");

               snd.preload = "auto";

                 snd.load();
                 snd.play();

            }
 
             //**********************baba-jaga@i.ua**********************
            // закроем подбор
            function ne_ok(){
                window.close();  
            }
            
            //**********************baba-jaga@i.ua**********************
            // откроем документ
            function ok_doc(){
                var iddoc = <?php echo  "'" . $id_doc . "'" ; ?> ;
                var php_file = <?php echo "'" .  $php_file . "'"; ?> ; 
                window.open( php_file + "?id_doc=" + iddoc );
                window.close();  
   
            }
            
            


        </script>
    </head>
    <body onload="javascript:on_load()" >

        <!-- таблица делящая страницу -->
        <table cellspacing='3' border='0' style=" width: 100%;  margin: 0px " >
            <col >
            <col width="800px">
            <col >
            <tr>
                <td style="background: #ffeded;" >&nbsp;</td> <!-- левая колонка основной таблицы -->
                <td> <!-- средняя колонка основной таблицы -->


                    <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >
                            <!-- таблица упорядочивания данных формы ввода -->
                            <table cellspacing='0' border='0' style=" width: 100%;  " >
                                <col width="170px">
                                <col width="200px">
                                <col width="190px">
                                <col width="100px">
                                <col width="80px">
                                <col >
                                <tr> <!-- первая строка формы -->
                                    
                                    <input type="hidden"   name="id_doc" >
                                    
                                    <td colspan="6" > <h3 style="font-size: 1.3em; text-align: center" > <?php echo  $nm_doc ; ?>   </h3> </td>
                                    
                                </tr>
                                <tr> <!-- вторая строка формы -->
                                    
                                    <td colspan="2" >  &nbsp; </td>
                                    <td>
                                         <input type="hidden"   name="txt_only_ost" >
                                         <input type="checkbox" name="only_ost" onclick="javascript:on_off_only()" > <span style="font-size: 13px" >только остатки </span>
                                    </td>
                                    <td colspan="2" >  &nbsp;</td>
                                    
                                    <td> <a href="#" onclick="javascript:ok_doc()" > <img src="images/btnDA.jpg" border=0> </a> </td>
                                </tr>
                                <tr> <!-- третья строка формы ИНФО О Товаре -->
                                    <td colspan="2" >  &nbsp;</td>

                                    <td > 
                                        <input type="hidden"   name="txt_only_doc" >
                                        <input type="checkbox" name="only_doc" onclick="javascript:on_off_only()" > <span style="font-size: 13px" >набранный товар </span> 
                                    </td>
                                    <td colspan="3" >  &nbsp;</td>
                                </tr >
                                <tr  > <!-- четвертая строка формы -->
                                    <td colspan="3" >

                                        Код : &nbsp; <input type="text" name="h_kod" id="h_kod" size="7" maxlength="13"
                                                                  tabindex="0" >
                                        &nbsp; &nbsp; &nbsp; фрагмент:
                                        <input type="text" name="fragment" id ="fragment"  size="10" maxlength="13"
                                               tabindex="1"  >
                                        
                                        <input type="submit" name="submit" id="submit"  value="  Найти  "    >

                                    </td>
                                    <td> <h4>ВСЕГО: </h4> </td>
                                    <td> <input type="text" readonly="true" name="sum_vsego" id ="sum_vsego" size="5" value= <?php if($id_doc!='list'){ echo  '"' . sumdoc($id_doc) . '"';}else{echo  '0';} ; ?>  > </td>
                                    <td> <a href="#" onclick="javascript:ne_ok()" > <img src="images/btnClose.jpg" border=0> </a>  </td>

                                </tr>
                            </table>


                   </form>

        <div style=" padding: 10px 0px 0px 0px " >
            <table cellspacing='0' class="Design5" >
                <col width="120px">
                <col width="300px">
                <col width="75px">
                <col width="75px">
                <col width="90px">




                <thead>
                    <tr>
                        <th class="Odd">код</th>
                        <th>наименование товара</th>
                        <th  class="Odd">цена грн.</th>
                        <th   >         остаток</th>
                        <th class="Odd">к-во</th>
                        <th>&nbsp;</th>
                    </tr>
                    
                </thead>
                
                
                 <?php str_view() ; ?>
                


            </table>
        </div>


                </td> <!-- конец средней колонки основной таблицы -->
                <td style="background: #ffeded;" >&nbsp;</td> <!-- правая колонка основной таблицы -->
            </tr></table> <!--конец таблицы делящая страницу -->



    </body>
</html>
