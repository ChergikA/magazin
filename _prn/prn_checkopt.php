<?php


//***********************************  baba-jaga@i.ua  **************************************
// отправляем файл для печати по фтп, На клиента на котором установлен требуемый принтер
//'data/cennik_sr.fods'
function prn_na_ftp(){
            $connect = ftp_connect('192.168.1.7');
            if (!$connect) {
                echo("<H1>Не включен компьютер с принтером</H1>");
                return  ;
            }

            $result = ftp_login($connect, 'sales2', '111');
            if(!$result){
                echo("<H1>Нет подключения к папкам , возможно нужно перезагрузить <br>"
                        . " компютер, на котром установлен принтер для печати ценников</H1>");
                return ;
            }
             // включение пассивного режима
            ftp_pasv($connect, true);
            //echo("Соединение установлено<br>");
            //if(! ftp_chdir($connect, $const->ftp_catalog) ){ // переход в каталог

              //  $err = 'не верно задана дирректория на сервере';
              //  save_log('Ошибка отправки данных', $err );
             //}

            $filename = 'cennik_sr.fods';   // имя без пути
            if (ftp_put($connect, $filename,'data/cennik_sr.fods', FTP_BINARY)) {
                echo "Если принтер включен, то напечатано.";
                //save_log('Отправка данных', 'Файл: '.  $filename. '  отправлен на сервер. '   );

            }    
}


//*********************** baba-jaga@i.ua ********************************
// добавляем в файл хмл нужные секции
function addinxml($num, $nm, $kvo, $cena , $cena_opt, $sum, $sum_opt  ) {

  $str = '    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>'.$num.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce9" office:value-type="string">
      <text:p>Т'.$nm.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce9" office:value-type="string">
      <text:p>шт.</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce11" office:value-type="string">
      <text:p>'.$kvo.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce13" office:value-type="string">
      <text:p>'.$cena.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce13" office:value-type="string">
      <text:p>'.$cena_opt.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce14" office:value-type="string">
      <text:p>'.$sum.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce18" office:value-type="string">
      <text:p>'.$sum_opt.'</text:p>
     </table:table-cell>
     <table:table-cell/>
    </table:table-row>';

//Блокнот А5 96л # &quot;Ш&quot; 493 спир.бок. лак.
    $str = cnv_($str);
    

    return$str;
}

//*********************** baba-jaga@i.ua ********************************
// откр зап сохр
function prnxml($id_doc){
    //$xmlString = file_get_contents('hkod.xml');
    //$xml       = new SimpleXMLElement($xmlString);
    $handle = fopen('data/cennik_sr.fods', 'w'); //историческое название
    //addinxml($xml, $tip, $zn, $n);

    $sum_itog=0;
    $sum_sum=0;
    $skidka=0;
    $sum_itog_opt=0;
    $sum_sum_opt=0;
    $num=1;
    $no_simvols = array("\"", "'", "»", "«","&",">","<","#9675;"  );
    
    fwrite($handle, strfile('start', $id_doc, 0,0  ) ) ;
 


    $txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Tovar`, `Tovar`.`ed_izm`,
    `Tovar`.`Price` as Cena  ,  `DocTab`.`Cena` as CenaOpt ,"
    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$id_doc." AND `DocTab`.`Kvo` > 0 )\n"
    . "ORDER BY `DocTab`.`id` ASC ";
    
    $sql = cls_my_sql::tbl_sql($txt_sql);
   while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {

       $kvo=$srt_arr['Kvo'];
       if ($kvo !== '0'){
          $sum           = sprintf("%.2f", $kvo *  $srt_arr['Cena']);
          $sum_opt       = sprintf("%.2f", $kvo *  $srt_arr['CenaOpt']);
          $it            = sprintf("%.2f", $kvo *  $srt_arr['Cena']  * (1 - $srt_arr['Skidka']/100  ) );
          $it_opt        = sprintf("%.2f", $kvo *  $srt_arr['CenaOpt']  * (1 - $srt_arr['Skidka']/100  ) );
          //$sum_itog     .= $kvo *  $srt_arr['Cena']  * (1 - $srt_arr['Skidka']/100  );
          //$sum_itog_opt .= $kvo *  $srt_arr['CenaOpt']  * (1 - $srt_arr['Skidka']/100  );
          $sum_sum      =$sum_sum    + $sum;
          $sum_sum_opt  =$sum_sum_opt+ $sum_opt;
          //echo '<br>='.$sum_sum.'<br>';
          //$skidka        = round( skidkadoc($iddoc) ,2) ; //$srt_arr['Skidka'];
          $cena          = sprintf('%.2f', $srt_arr['Cena']);
          $cena_opt      = sprintf('%.2f', $srt_arr['CenaOpt']);
          $nm=$srt_arr['Tovar'];
          $nm         = str_replace($no_simvols, "",$nm);  

       $str = addinxml($num, $nm, $kvo, $cena , $cena_opt, $sum, $sum_opt );
       $num++;
       fwrite($handle, $str ) ;
       }
     
    }
    
    //echo '='.$sum_sum;

    //$xml->saveXML('data/hkod.xml');
    fwrite($handle, strfile('end', $id_doc,$sum_sum, $sum_sum_opt ) ) ;
    fclose($handle);
    chmod('data/cennik_sr.fods', 0777);
    
   // prn_na_ftp();

      // только ос ubuntu
   
   // в меню Ubuntu "Запуск приложений" кладем скрипт, который мониторит наличие файла prn_hkod.fods
   // если есть  то выполняем :
   // libreoffice -pt "TSC_TDP-225" "/home/san/webServer/km/data/hkod.fods"
   // и удаляем его. и мониторим дальше


}

/*
   <Row ss:AutoFitHeight="0" ss:Height="15.75">
    <Cell ss:Index="2" ss:StyleID="s28"><Data ss:Type="String">   1.30</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="19.5">
    <Cell ss:Index="2" ss:StyleID="s26"><Data ss:Type="String">C41&lt;830032461434@</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="9">
    <Cell ss:Index="2" ss:StyleID="s27"><Data ss:Type="String">71791</Data></Cell>
   </Row>
 *
   <Row ss:AutoFitHeight="0" ss:Height="22.5">
    <Cell ss:Index="2" ss:StyleID="s28"><Data ss:Type="String">   1.30</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="19.5">
    <Cell ss:Index="2" ss:StyleID="s26"><Data ss:Type="String">C41&lt;830032461434@</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="9.75">
    <Cell ss:Index="2" ss:StyleID="s27"><Data ss:Type="String">71791</Data></Cell>
   </Row>
*/


function strfile($f,$id_doc,$sum, $sum_opt){
    
    $my_const = new cls_my_const();
    $skl = $my_const->magazin;
    $sumdoc = sumdoc($id_doc);
    


        // получим данные для шапки
    $txt_sql = "SELECT `firms`.`name_firm`, `firms`.`tel_firm`, `firms`.`INN`, `firms`.`nom_svid`,
                    `Klient`.`name_klient`,`DocHd`.`id`,`DocHd`.`nomDoc`,
                    `DocHd`.`DataDoc`, `DocHd`.`SumDoc`, `DocHd`.`flg_optPrice` , `StatusDoc`.`nameStatus`,
                    `DocHd`.`SkidkaProcent`, `DocHd`.`Pometka`, `VidDoc`.`name_doc`, `users`.`full_name`
                FROM `firms`
                    LEFT JOIN `DocHd`     ON `firms`.`id` = `DocHd`.`firms_id`
                    LEFT JOIN `Klient`    ON `DocHd`.`Klient_id` = `Klient`.`id_`
                    LEFT JOIN `VidDoc`    ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id` 
                    LEFT JOIN `StatusDoc` ON `DocHd`.`statusDoc` = `StatusDoc`.`idStatus`
                    LEFT JOIN `users`     ON `DocHd`.`users_id` = `users`.`id`
                WHERE (`DocHd`.`id` = " . $id_doc . " ) ";

//echo ' = ' . $txt_sql;

    $s_arr   = cls_my_sql::const_sql($txt_sql);
    
    //ІПН 3158207438, номер свідоцтва 564889
    $rekv_nalog = '';
    if($s_arr['INN'] !=  '')      $rekv_nalog = "ІПН: "  . $s_arr['INN']; 
    if($s_arr['nom_svid'] !=  '') $rekv_nalog .= ', номер свідоцтва:' . $s_arr['nom_svid'];
    
    $nm_klient = $s_arr['name_klient'];
    $nom_doc  = $s_arr['nomDoc'];
    $phpdate = strtotime( $s_arr['DataDoc'] );
    $data_doc=date ("d.m.y", $phpdate);
    
   $start = '<?xml version="1.0" encoding="UTF-8"?>

<office:document xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:rpt="http://openoffice.org/2005/report" xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:grddl="http://www.w3.org/2003/g/data-view#" xmlns:tableooo="http://openoffice.org/2009/table" xmlns:field="urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0" xmlns:formx="urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0" xmlns:css3t="http://www.w3.org/TR/css3-text/" office:version="1.2" office:mimetype="application/vnd.oasis.opendocument.spreadsheet">
 <office:meta><dc:title>Оптовый чек</dc:title><meta:document-statistic meta:table-count="1" meta:cell-count="42" meta:object-count="0"/><meta:generator>LibreOffice/3.5$Linux_X86_64 LibreOffice_project/350m1$Build-2</meta:generator><meta:user-defined meta:name="ProgId">Excel.Sheet</meta:user-defined></office:meta>
 <office:settings>
  <config:config-item-set config:name="ooo:view-settings">
   <config:config-item config:name="VisibleAreaTop" config:type="int">0</config:config-item>
   <config:config-item config:name="VisibleAreaLeft" config:type="int">0</config:config-item>
   <config:config-item config:name="VisibleAreaWidth" config:type="int">17831</config:config-item>
   <config:config-item config:name="VisibleAreaHeight" config:type="int">5863</config:config-item>
   <config:config-item-map-indexed config:name="Views">
    <config:config-item-map-entry>
     <config:config-item config:name="ViewId" config:type="string">view1</config:config-item>
     <config:config-item-map-named config:name="Tables">
      <config:config-item-map-entry config:name="Лист1">
       <config:config-item config:name="CursorPositionX" config:type="int">1</config:config-item>
       <config:config-item config:name="CursorPositionY" config:type="int">12</config:config-item>
       <config:config-item config:name="HorizontalSplitMode" config:type="short">0</config:config-item>
       <config:config-item config:name="VerticalSplitMode" config:type="short">0</config:config-item>
       <config:config-item config:name="HorizontalSplitPosition" config:type="int">0</config:config-item>
       <config:config-item config:name="VerticalSplitPosition" config:type="int">0</config:config-item>
       <config:config-item config:name="ActiveSplitRange" config:type="short">2</config:config-item>
       <config:config-item config:name="PositionLeft" config:type="int">0</config:config-item>
       <config:config-item config:name="PositionRight" config:type="int">0</config:config-item>
       <config:config-item config:name="PositionTop" config:type="int">0</config:config-item>
       <config:config-item config:name="PositionBottom" config:type="int">0</config:config-item>
       <config:config-item config:name="ZoomType" config:type="short">0</config:config-item>
       <config:config-item config:name="ZoomValue" config:type="int">100</config:config-item>
       <config:config-item config:name="PageViewZoomValue" config:type="int">60</config:config-item>
       <config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item>
      </config:config-item-map-entry>
     </config:config-item-map-named>
     <config:config-item config:name="ActiveTable" config:type="string">Лист1</config:config-item>
     <config:config-item config:name="HorizontalScrollbarWidth" config:type="int">270</config:config-item>
     <config:config-item config:name="ZoomType" config:type="short">0</config:config-item>
     <config:config-item config:name="ZoomValue" config:type="int">100</config:config-item>
     <config:config-item config:name="PageViewZoomValue" config:type="int">60</config:config-item>
     <config:config-item config:name="ShowPageBreakPreview" config:type="boolean">false</config:config-item>
     <config:config-item config:name="ShowZeroValues" config:type="boolean">true</config:config-item>
     <config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item>
     <config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item>
     <config:config-item config:name="GridColor" config:type="long">12632256</config:config-item>
     <config:config-item config:name="ShowPageBreaks" config:type="boolean">true</config:config-item>
     <config:config-item config:name="HasColumnRowHeaders" config:type="boolean">true</config:config-item>
     <config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item>
     <config:config-item config:name="IsOutlineSymbolsSet" config:type="boolean">true</config:config-item>
     <config:config-item config:name="IsSnapToRaster" config:type="boolean">false</config:config-item>
     <config:config-item config:name="RasterIsVisible" config:type="boolean">false</config:config-item>
     <config:config-item config:name="RasterResolutionX" config:type="int">1000</config:config-item>
     <config:config-item config:name="RasterResolutionY" config:type="int">1000</config:config-item>
     <config:config-item config:name="RasterSubdivisionX" config:type="int">1</config:config-item>
     <config:config-item config:name="RasterSubdivisionY" config:type="int">1</config:config-item>
     <config:config-item config:name="IsRasterAxisSynchronized" config:type="boolean">true</config:config-item>
    </config:config-item-map-entry>
   </config:config-item-map-indexed>
  </config:config-item-set>
  <config:config-item-set config:name="ooo:configuration-settings">
   <config:config-item config:name="ShowZeroValues" config:type="boolean">true</config:config-item>
   <config:config-item config:name="LoadReadonly" config:type="boolean">false</config:config-item>
   <config:config-item config:name="UpdateFromTemplate" config:type="boolean">true</config:config-item>
   <config:config-item config:name="GridColor" config:type="long">12632256</config:config-item>
   <config:config-item config:name="AllowPrintJobCancel" config:type="boolean">true</config:config-item>
   <config:config-item config:name="IsSnapToRaster" config:type="boolean">false</config:config-item>
   <config:config-item config:name="RasterSubdivisionX" config:type="int">1</config:config-item>
   <config:config-item config:name="RasterSubdivisionY" config:type="int">1</config:config-item>
   <config:config-item config:name="AutoCalculate" config:type="boolean">true</config:config-item>
   <config:config-item config:name="PrinterSetup" config:type="base64Binary">cQH+/0dPREVYLURUMgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQ1VQUzpHT0RFWC1EVDIAAAAAAAAAAAAAAAAAAAAAAAAWAAMAkwAAAAAAAAALAJETAAC2DwAASm9iRGF0YSAxCnByaW50ZXI9R09ERVgtRFQyCm9yaWVudGF0aW9uPVBvcnRyYWl0CmNvcGllcz0xCm1hcmdpbmRhanVzdG1lbnQ9MCwwLDAsMApjb2xvcmRlcHRoPTI0CnBzbGV2ZWw9MApwZGZkZXZpY2U9MQpjb2xvcmRldmljZT0wClBQRENvbnRleERhdGEKEgBDT01QQVRfRFVQTEVYX01PREUOAERVUExFWF9VTktOT1dO</config:config-item>
   <config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item>
   <config:config-item config:name="IsDocumentShared" config:type="boolean">false</config:config-item>
   <config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item>
   <config:config-item config:name="PrinterName" config:type="string">GODEX-DT2</config:config-item>
   <config:config-item config:name="LinkUpdateMode" config:type="short">3</config:config-item>
   <config:config-item config:name="IsKernAsianPunctuation" config:type="boolean">false</config:config-item>
   <config:config-item config:name="SaveVersionOnClose" config:type="boolean">false</config:config-item>
   <config:config-item config:name="RasterIsVisible" config:type="boolean">false</config:config-item>
   <config:config-item config:name="ApplyUserData" config:type="boolean">true</config:config-item>
   <config:config-item config:name="RasterResolutionX" config:type="int">1000</config:config-item>
   <config:config-item config:name="RasterResolutionY" config:type="int">1000</config:config-item>
   <config:config-item config:name="IsOutlineSymbolsSet" config:type="boolean">true</config:config-item>
   <config:config-item config:name="ShowPageBreaks" config:type="boolean">true</config:config-item>
   <config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item>
   <config:config-item config:name="CharacterCompressionType" config:type="short">0</config:config-item>
   <config:config-item config:name="IsRasterAxisSynchronized" config:type="boolean">true</config:config-item>
   <config:config-item config:name="HasColumnRowHeaders" config:type="boolean">true</config:config-item>
  </config:config-item-set>
 </office:settings>
 <office:scripts>
  <office:script script:language="ooo:Basic">
   <ooo:libraries xmlns:ooo="http://openoffice.org/2004/office" xmlns:xlink="http://www.w3.org/1999/xlink"/>
  </office:script>
 </office:scripts>
 <office:font-face-decls>
  <style:font-face style:name="Times New Roman" svg:font-family="&apos;Times New Roman&apos;" style:font-family-generic="roman" style:font-pitch="variable"/>
  <style:font-face style:name="Arial" svg:font-family="Arial" style:font-family-generic="swiss" style:font-pitch="variable"/>
  <style:font-face style:name="DejaVu Sans" svg:font-family="&apos;DejaVu Sans&apos;" style:font-family-generic="system" style:font-pitch="variable"/>
  <style:font-face style:name="Lohit Hindi" svg:font-family="&apos;Lohit Hindi&apos;" style:font-family-generic="system" style:font-pitch="variable"/>
  <style:font-face style:name="WenQuanYi Micro Hei" svg:font-family="&apos;WenQuanYi Micro Hei&apos;" style:font-family-generic="system" style:font-pitch="variable"/>
 </office:font-face-decls>
 <office:styles>
  <style:default-style style:family="table-cell">
   <style:paragraph-properties style:tab-stop-distance="1.25cm"/>
   <style:text-properties style:font-name="Arial" fo:language="ru" fo:country="RU" style:font-name-asian="DejaVu Sans" style:language-asian="zh" style:country-asian="CN" style:font-name-complex="DejaVu Sans" style:language-complex="hi" style:country-complex="IN"/>
  </style:default-style>
  <number:number-style style:name="N0">
   <number:number number:min-integer-digits="1"/>
  </number:number-style>
  <number:currency-style style:name="N111P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
  </number:currency-style>
  <number:currency-style style:name="N111">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N111P0"/>
  </number:currency-style>
  <number:date-style style:name="N108">
   <number:era/>
   <number:year/>
   <number:text>n</number:text>
   <number:year/>
   <number:year number:style="long"/>
   <number:text>al</number:text>
  </number:date-style>
  <number:number-style style:name="N109">
   <number:number number:decimal-places="0" number:min-integer-digits="4">
    <number:embedded-text number:position="3">.</number:embedded-text>
   </number:number>
  </number:number-style>
  <number:date-style style:name="N110">
   <number:text>Fix</number:text>
   <number:year/>
   <number:day/>
  </number:date-style>
  <style:style style:name="Default" style:family="table-cell">
   <style:text-properties style:font-name-asian="WenQuanYi Micro Hei" style:font-name-complex="Lohit Hindi"/>
  </style:style>
  <style:style style:name="Result" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties fo:font-style="italic" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold"/>
  </style:style>
  <style:style style:name="Result2" style:family="table-cell" style:parent-style-name="Result" style:data-style-name="N111"/>
  <style:style style:name="Heading" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties fo:font-size="16pt" fo:font-style="italic" fo:font-weight="bold"/>
  </style:style>
  <style:style style:name="Heading1" style:family="table-cell" style:parent-style-name="Heading">
   <style:table-cell-properties style:rotation-angle="90"/>
  </style:style>
 </office:styles>
 <office:automatic-styles>
  <style:style style:name="co1" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.817cm"/>
  </style:style>
  <style:style style:name="co2" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="8.714cm"/>
  </style:style>
  <style:style style:name="co3" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.843cm"/>
  </style:style>
  <style:style style:name="co4" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.117cm"/>
  </style:style>
  <style:style style:name="co5" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.279cm"/>
  </style:style>
  <style:style style:name="co6" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.633cm"/>
  </style:style>
  <style:style style:name="co7" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.66cm"/>
  </style:style>
  <style:style style:name="co8" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.769cm"/>
  </style:style>
  <style:style style:name="co9" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.258cm"/>
  </style:style>
  <style:style style:name="ro1" style:family="table-row">
   <style:table-row-properties style:row-height="0.605cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro2" style:family="table-row">
   <style:table-row-properties style:row-height="0.446cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro3" style:family="table-row">
   <style:table-row-properties style:row-height="0.427cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro4" style:family="table-row">
   <style:table-row-properties style:row-height="0.452cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ta1" style:family="table" style:master-page-name="Default">
   <style:table-properties table:display="true" style:writing-mode="lr-tb"/>
  </style:style>
  <number:number-style style:name="N1">
   <number:number number:decimal-places="0" number:min-integer-digits="1"/>
  </number:number-style>
  <number:text-style style:name="N100">
   <number:text-content/>
  </number:text-style>
  <style:style style:name="ce1" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N108">
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce2" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce3" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border-bottom="0.06pt solid #000000" fo:border-left="none" fo:border-right="0.06pt solid #000000" fo:border-top="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce4" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N1">
   <style:table-cell-properties fo:border-bottom="0.06pt solid #000000" style:text-align-source="fix" style:repeat-content="false" fo:border-left="none" fo:border-right="0.06pt solid #000000" fo:border-top="0.06pt solid #000000"/>
   <style:paragraph-properties fo:text-align="start" fo:margin-left="0cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce5" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="14pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="14pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="14pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce6" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce7" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce8" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" fo:border="0.06pt solid #000000"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce9" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce10" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" fo:border="0.06pt solid #000000"/>
   <style:paragraph-properties fo:text-align="center" fo:margin-left="0cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce11" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N1">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" fo:border="0.06pt solid #000000"/>
   <style:paragraph-properties fo:text-align="center" fo:margin-left="0cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce12" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce13" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N100">
   <style:table-cell-properties fo:border="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce14" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N109">
   <style:table-cell-properties fo:border="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce15" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border-bottom="0.06pt solid #000000" fo:border-left="none" fo:border-right="none" fo:border-top="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce16" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N110">
   <style:table-cell-properties fo:border-bottom="0.06pt solid #000000" fo:border-left="none" fo:border-right="none" fo:border-top="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce17" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border-bottom="0.06pt solid #000000" style:text-align-source="fix" style:repeat-content="false" fo:border-left="0.06pt solid #000000" fo:border-right="none" fo:border-top="0.06pt solid #000000"/>
   <style:paragraph-properties fo:text-align="center" fo:margin-left="0cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce18" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N109">
   <style:table-cell-properties fo:border-bottom="0.06pt solid #000000" fo:border-left="0.06pt solid #000000" fo:border-right="none" fo:border-top="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce19" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N100">
   <style:table-cell-properties fo:border-bottom="0.06pt solid #000000" fo:border-left="0.06pt solid #000000" fo:border-right="none" fo:border-top="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:page-layout style:name="pm1">
   <style:page-layout-properties fo:margin-top="1cm" fo:margin-bottom="1cm" fo:margin-left="2cm" fo:margin-right="1cm" style:shadow="none" style:writing-mode="lr-tb"/>
   <style:header-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-bottom="0.25cm"/>
   </style:header-style>
   <style:footer-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-top="0.25cm"/>
   </style:footer-style>
  </style:page-layout>
  <style:page-layout style:name="pm2">
   <style:page-layout-properties style:writing-mode="lr-tb"/>
   <style:header-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-bottom="0.25cm" fo:border="2.49pt solid #000000" fo:padding="0.018cm" fo:background-color="#c0c0c0">
     <style:background-image/>
    </style:header-footer-properties>
   </style:header-style>
   <style:footer-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-top="0.25cm" fo:border="2.49pt solid #000000" fo:padding="0.018cm" fo:background-color="#c0c0c0">
     <style:background-image/>
    </style:header-footer-properties>
   </style:footer-style>
  </style:page-layout>
 </office:automatic-styles>
 <office:master-styles>
  <style:master-page style:name="Default" style:page-layout-name="pm1">
   <style:header style:display="false">
    <text:p><text:sheet-name>???</text:sheet-name></text:p>
   </style:header>
   <style:header-left style:display="false"/>
   <style:footer style:display="false">
    <text:p>Страница <text:page-number>1</text:page-number></text:p>
   </style:footer>
   <style:footer-left style:display="false"/>
  </style:master-page>
  <style:master-page style:name="Report" style:page-layout-name="pm2">
   <style:header>
    <style:region-left>
     <text:p><text:sheet-name>???</text:sheet-name> (<text:title>???</text:title>)</text:p>
    </style:region-left>
    <style:region-right>
     <text:p><text:date style:data-style-name="N2" text:date-value="2017-03-13">00.00.0000</text:date>, <text:time>00:00:00</text:time></text:p>
    </style:region-right>
   </style:header>
   <style:header-left style:display="false"/>
   <style:footer>
    <text:p>Страница <text:page-number>1</text:page-number> / <text:page-count>99</text:page-count></text:p>
   </style:footer>
   <style:footer-left style:display="false"/>
  </style:master-page>
 </office:master-styles>
 <office:body>
  <office:spreadsheet>
   <table:table table:name="Лист1" table:style-name="ta1">
    <table:table-column table:style-name="co1" table:default-cell-style-name="ce2"/>
    <table:table-column table:style-name="co2" table:default-cell-style-name="ce2"/>
    <table:table-column table:style-name="co3" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co4" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co5" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co6" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co7" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co8" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co9" table:default-cell-style-name="ce2"/>
    <table:table-row table:style-name="ro1">
     <table:table-cell table:style-name="ce1"/>
     <table:table-cell table:style-name="ce5" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'. $s_arr['name_firm']  .'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell/>
     <table:table-cell table:style-name="ce6" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>Товарний чек №_'.$nom_doc.' від: '.$data_doc.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell/>
     <table:table-cell table:style-name="ce7" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>Обрана система оподаткування: єдиний податок</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell/>
     <table:table-cell table:style-name="ce7" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>тел.:'.$s_arr['tel_firm'].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell/>
     <table:table-cell table:style-name="ce7" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'.$rekv_nalog.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce2" table:number-columns-repeated="6"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce3" office:value-type="string">
      <text:p>№</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce8" office:value-type="string">
      <text:p>Найменування товару</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce10" office:value-type="string">
      <text:p>Од.</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce10" office:value-type="string">
      <text:p>К-ть</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce10" office:value-type="string">
      <text:p>Ціна</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce10" office:value-type="string">
      <text:p>Ціна опт</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce10" office:value-type="string">
      <text:p>Сума</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce17" office:value-type="string">
      <text:p>Сума опт</text:p>
     </table:table-cell>
     <table:table-cell/>
    </table:table-row>';


  $end = '    <table:table-row table:style-name="ro2">
     <table:table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce2" table:number-columns-repeated="3"/>
     <table:table-cell table:style-name="ce15" office:value-type="string">
      <text:p>Всього:</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce16" office:value-type="string">
      <text:p>'.$sum.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce15" office:value-type="string">
      <text:p>'.$sum_opt.'</text:p>
     </table:table-cell>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce2" table:number-columns-repeated="6"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell/>
     <table:table-cell office:value-type="string">
      <text:p>Видав(ла) ___________________________</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce2"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="5" table:number-rows-spanned="1">
      <text:p>Отримав(ла) _____________________________</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="4"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro4">
     <table:table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce2" table:number-columns-repeated="6"/>
     <table:table-cell/>
    </table:table-row>
   </table:table>
   <table:named-expressions>
    <table:named-range table:name="HTML_1" table:base-cell-address="$Лист1.$A$1" table:cell-range-address=".$A$1:.$I$14"/>
    <table:named-range table:name="HTML_all" table:base-cell-address="$Лист1.$A$1" table:cell-range-address=".$A$1:.$I$14"/>
    <table:named-range table:name="HTML_tables" table:base-cell-address="$Лист1.$A$1" table:cell-range-address=".$A$1:.$A$1"/>
   </table:named-expressions>
  </office:spreadsheet>
 </office:body>
</office:document>';

 if($f=='start')     return cnv_ ( $start) ;
 if($f=='end')       return cnv_ ( $end );

}



?>
