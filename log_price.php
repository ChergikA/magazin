<?php
require("header.inc.php");
include("milib.inc");
global $db;



$h_kod =''; $info_tovar=""; 

$date = date('Y-m-d');
if (isset($_GET['date']) ) { // 
    $date = $_GET['date'];
}

 $nm_firm = "";
 $nm_doc  = "»зменение ценах от: " . $date ;
 $avtor   = "";



// печать этикетки &kvoprn=" + kvoprihod
if (isset($_GET['kvoprn'])) {
    // если установлен принтер штрих-кода, провер€ем в константах
    $txt_sql = "SELECT `name` FROM `const` WHERE `kod` LIKE 'PrintHKod' ";
    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
    if ($s_arr['name'] == 1) {


        $kvoprn = $_GET['kvoprn'];
        $kod1c  = $_GET['kod1c'];
        $cn     = $_GET['cena'];
        $idtov  = id_tovar('', $kod1c);
        $idstr = trim($kod1c);

        $txt_sql = 'TRUNCATE cenniki'; // очистим табл ценников
        $sql     = mysql_query($txt_sql, $db);


        $txt_sql = "INSERT INTO `cenniki` (`id_tovar`, `kvo_hkod`, `kvo_cennik`, `tip_cn`,`cena`)
                VALUES ('" . $idtov . "', '" . $kvoprn . "', '0' , '0', '".$cn."');";
        $sql     = mysql_query($txt_sql, $db);
        require("prn_hkod.php");
        prnxml();
    }
    else {
        echo '!!! не установлен принтер штрих-кода';
    }
    //kod1c
}

//***********************************  baba-jaga@i.ua  ********************************************************
//выводим строки документа
function str_view(){
    global $date;
    global $db;

   //                                 <td>п/н</td>
   //                                 <th class="Odd">  од</th>
   //                                 <th>             Ќаименование</th>
   //                                 <th  class="Odd">  -во <br> приход </th>
   //                                 <th>              -во <br> прин€то </th>
   //                                 <th  class="Odd">нехватка</th>
   //                                 <td>             излишек</td>
   //                                 <th class="Odd">&nbsp;</th>
   //                                 <td>            ÷ена</td>
   //                                 <th  class="Odd">÷ена <br> рекоменд. </th>
   //                                 <th>ѕометка</th>
   //                                 <th>&nbsp;</th>

    $txt_sql = "SELECT `Tovar`.`Kod1C`,`Tovar`.`Kod`,`Tovar`.`Tovar`,`Tovar`.`Price`,`Tovar`.`PriceOpt`,`Tovar`.`ed_izm`,
            `Tovar`.`Ostatok`,`log_price`.`date_edit`,`log_price`.`old_price`,`log_price`.`new_price`,`log_price`.`old_price_opt`,
            `log_price`.`new_price_opt` FROM Tovar 
         LEFT JOIN `log_price` ON `Tovar`.`id_tovar` = `log_price`.`id_tovar`
         Where `log_price`.`date_edit` > '$date 00:00:00' and `log_price`.`date_edit` < '$date 23:59:59' 
         ORDER BY `Tovar`.`Tovar` ASC";

     //   color: #F93D00 ; /* красный  */
     //   color: #00f  /* синий  */

    $sql     = mysql_query($txt_sql, $db);
    $nomstr=0;
    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {
        
        
        $st = '';
        $razn =    $s_arr['old_price'] - $s_arr['new_price']   ; //  прин€то -приход
        if($razn < 0){
            $st = ' style=" color: #F93D00 ;"';
        }elseif($razn > 0) {
            $st = ' style=" color: #00f ;"';
        }

         $st2 = '';
        $razn =    $s_arr['old_price_opt'] - $s_arr['new_price_opt']   ; //  прин€то -приход
        if($razn < 0){
            $st2 = ' style=" color: #F93D00 ;"';
        }elseif($razn > 0) {
            $st2 = ' style=" color: #00f ;"';
        }
       
        
        $prnkod = trim($s_arr['Kod']);
        if(strlen($prnkod) < 10 ){// печатаем только свои
            $prnkod = '<a href = "javascript:;"  onclick="javascript:prnhkod('.'event' .','. "'" . $s_arr['Kod1C'] . "'" .','.  "'1'". ','.  "'1'" . ','.  "'" . $s_arr['new_price'] . "'" .  ' )" > <img src="images/b_print.png" border=0>' . $s_arr['Kod'] .' </a>';
            
        }

       // $pricein = $s_arr['PriceIn'];
        //if($pricein == 0) $pricein='';
        $nomstr++;
        echo '<tr  id="'.$s_arr['Kod'].'" >';
        echo "<td>$nomstr</td>";
        echo '<th class="Odd">'.$prnkod.'</th>';
        echo '<th '. $st .' >'. $s_arr['Tovar'] .'</th>';
        echo '<td  class="Odd" '. $st .' >'. $s_arr['old_price'] .'</td>';  
        echo '<td  class="Odd" '. $st .' >'. $s_arr['new_price'] .'</td>'; 
        echo '<td>&nbsp</td>'; 
        
        echo '<td  class="Odd" '. $st2 .' >'. $s_arr['old_price_opt'] .'</td>';  
        echo '<td  class="Odd" '. $st2 .' >'. $s_arr['new_price_opt'] .'</td>'; 
       // echo '<td '. $st .' ><input type="text" id="kvoIn'.$s_arr['idstr'].'" style="width:47px" onchange="javascript:upd_kvoIn(event,'. $s_arr['idstr'] .',' . "'" . $s_arr['Kvo'] . "'" . ',' . "'" . $s_arr['id_tovar'] . "'" . ' )" value="'.$s_arr['Kvo'].'"></td>';           // прин€то
       // echo '<td class="Odd" '. $st .' ><input type="text" id="priceIn'.$s_arr['idstr'].'" style="width:47px" onchange="javascript:upd_priceIn(event,'. $s_arr['idstr'] .',' . "'" . $s_arr['Kvo'] . "'" . ',' . "'" . $s_arr['id_tovar'] . "'" . ' )" value="'. $pricein .'"></td>';

        echo '<td>&nbsp</td>';
        
        echo '<td  class="Odd" >'. $s_arr['Ostatok'] .'</td>'; 

        // разница цен
        //$st = '';
        //$razn =    $s_arr['Cena'] - $s_arr['Cena2']   ; //
        //if($razn < 0){
        //    $st = ' style=" color: #F93D00 ;"';
        //}elseif($razn > 0) {
        //    $st = ' style=" color: #00f ;"';
        //}
        $pometka = '___';
        //if(  trim($s_arr['pometka']) != '') $pometka = $s_arr['pometka'];
        //echo '<td            '. $st .'>'. sprintf("%.2f", $s_arr['Cena2']) .'</td>';
       // echo '<td class="Odd"  ><a href = "javascript:;" '. $st .' onclick="javascript:show_bar(event,'. $s_arr['idstr'] .','. sprintf("%.2f", $s_arr['Cena']) .' )" > '. sprintf("%.2f", $s_arr['Cena']) .' </a>   </td>';
        //echo '<td class="Odd"  ><input type="text" id="priceRec'.$s_arr['idstr'].'" style="width:47px" onchange="javascript:upd_priceRec(event,'. $s_arr['idstr'] .',' . "'" . $s_arr['Cena'] . "'" . ',' . "'" . $s_arr['id_tovar'] . "'" . ' )"  value="'.$s_arr['Cena'].'" ></td>';
        //echo '<td            >'. $s_arr['pometka'] .'</td>';
        //echo '<td >            <a href = "javascript:;"           onclick="javascript:show_editpometka(event,'. $s_arr['idstr'] .',' . "'" . $s_arr['pometka'] . "'" . ',' . "'" . $s_arr['id_tovar'] . "'" . ' )" > '. $pometka .' </a>   </td>';

        //echo '<th><a href = "javascript:;"  onclick="javascript:delstr('. $s_arr['idstr'] .' )" > <img src="images/b_drop.png" border=0> </a> </th>';
        echo '</tr>';


    }

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
            //печать этикеток штрих-кода
            function prnhkod( event,kod1c,kvoprihod,kvo,cena ){
                //if(kvoprihod<kvo)kvoprihod=kvo;
                //if(kvoprihod<1) return;
                
                //var MouseX = event.clientX + document.body.scrollLeft;
                //var MouseY = event.clientY + document.body.scrollTop;
                //var obj = document.getElementById("prnbar");

                //obj.style.width =  '300px' ;
                //obj.style.top = MouseY +120 + 'px' ;
                //obj.style.left = MouseX -60 + 'px' ;
                //obj.style.visibility = "visible";
                //document.getElementById('_kod_1c').value  = kod1c;
                //document.getElementById('_cena').value  = cena;
                //document.getElementById('_kolvo').value  = kvo;
                //document.getElementById('_kolvo').focus();

            }
            // Ёта функци€ вызываетьс€ с всплывающего окна дл€ печати этикеток в заданном кол-ве
             function prn_et () {
                //var kod1c =   document.getElementById('_kod_1c').value ; 
                //var cena   =  document.getElementById('_cena').value ;
                //var iddoc  = <?php echo "" //$id_doc; ?> ;
                //var kvoprihod= document.getElementById('_kolvo').value;
                //document.location = "doc_priemka.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&kvoprn=" + kvoprihod  + "&cena=" + cena  ;
            }




            //**********************baba-jaga@i.ua**********************
            function on_load(){

                plays();

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
            function cennik(){
                var dt = <?php echo  "'" . $date .  "'" ; ?>   ;
                window.open("doc_cennik.php?iddoc=info_cen&add=1&dt="+dt);
            }

 

        </script>
    </head>
    <body onload="javascript:on_load()" >

        <!-- таблица дел€ща€ страницу -->
        <table cellspacing='3' border='0' style=" width: 100%;  margin: 0px " >
            <col >
            <col width="800px">
            <col >
            <tr>
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- лева€ колонка основной таблицы -->
                <td> <!-- средн€€ колонка основной таблицы -->


                    <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >
                            <!-- таблица упор€дочивани€ данных формы ввода -->
                            <table cellspacing='0' border='0' style=" width: 100%;  " >
                                <col width="170px">
                                <col width="200px">
                                <col width="190px">
                                <col width="100px">
                                <col width="80px">
                                <col >
                                <tr> <!-- перва€ строка формы -->
                                    <td> <h3 style="font-size: 1.3em; text-align: left " > <?php echo  $nm_firm ; ?>  </h3> </td>
                                    <td colspan="2" > <h3 style="font-size: 1.3em; text-align: center" > <?php echo  $nm_doc ; ?>   </h3> </td>
                                    <td colspan="3" > <a href="#" onclick="javascript: cennik()" title="ѕечать ценников и этикеток" > <img src="images/btnPrint.jpg" border=0> </a> </td>
                                </tr>
                                <tr> <!-- втора€ строка формы -->
                                    <td colspan="2" > <h4> <?php echo  '' ; ?>  </h4> </td>
                                    <td> &nbsp;</td>

                                    <td> <h4> &nbsp;</h4> </td>
                                    <td>  &nbsp; </td>
                                    <td>  &nbsp;</td>
                                </tr>


                            </table>


                   </form>



                    <div style=" padding: 10px 0px 0px 0px " >
                        <!-- таблична€ часть документа -->
                        <table cellspacing='0' border='0' class="Design5" >
                            <col width="35px">
                            <col width="160px">
                            <col width="260px">  <!-- наименование -->
                            <col width="60px">
                            <col width="60px">
                            <col width="20px">
                            <col width="60px">
                            <col width="60px"> <!-- цена -->
                            <col width="5px">
                            <col width="30px"><!-- ост -->


                            <thead>
                                <tr>
                                    <td>п/н</td>
                                    <th class="Odd">  од</th>
                                    <th>             Ќаименование</th>
                                    <th  class="Odd"> розница <br>  была </th>
                                    <th  class="Odd">   розница <br>  нова€ </th>
                                    <th              >&nbsp;</th>
                                    <th  class="Odd"> оптова€ <br>  была </th>
                                    <th  class="Odd"> оптова€ <br>  нова€ </th>
                                    <th>&nbsp;</th>
                                    <th class="Odd" > ост. </th>

                                </tr>
                            </thead>

                            <?php  str_view() ; ?>


                        </table>
                    </div>


                </td> <!-- конец средней колонки основной таблицы -->
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- права€ колонка основной таблицы -->
            </tr></table> <!--конец таблицы дел€ща€ страницу -->

            
           <!-- всплывающее окно дл€ печати этикеток -->
            <div id=prnbar class=bar>
                <div align=right>
                <span style='cursor: pointer' title='«акрыть' onclick='hide_bar()'>x</span>
                </div>
                 ол-во этикеток: <input type="text" name="_kolvo" id ="_kolvo" size="5" >
                <input type="button" name="btn_prn" id="btn_prn"  onclick="javascript:prn_et()" value="  Ok  "    >
                
                <input type="hidden" id ="_kod_1c">
                <input type="hidden" id ="_cena">
                
            </div>


    </body>



</html>

