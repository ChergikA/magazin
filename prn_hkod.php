<?php

//***********************************  baba-jaga@i.ua  **************************************


//*********************** baba-jaga@i.ua ********************************
//'Определение набора полос Code 128 по ID
function Code_128_ID($id ){
    //Dim S As String
    switch ($id){ //Select Case ID
        case 0: $s = "212222";    break;
        case 1: $s = "222122";    break;
        case 2: $s = "222221";    break;
        case 3: $s = "121223";    break;
        case 4: $s = "121322";    break;
        case 5: $s = "131222";    break;
        case 6: $s = "122213";    break;
        case 7: $s = "122312";    break;
        case 8: $s = "132212";    break;
        case 9: $s = "221213";    break;
        case 10: $s = "221312";    break;
        case 11: $s = "231212";    break;
        case 12: $s = "112232";    break;
        case 13: $s = "122132";    break;
        case 14: $s = "122231";    break;
        case 15: $s = "113222";    break;
        case 16: $s = "123122";    break;
        case 17: $s = "123221";    break;
        case 18: $s = "223211";    break;
        case 19: $s = "221132";    break;
        case 20: $s = "221231";    break;
        case 21: $s = "213212";    break;
        case 22: $s = "223112";    break;
        case 23: $s = "312131";    break;
        case 24: $s = "311222";    break;
        case 25: $s = "321122";    break;
        case 26: $s = "321221";    break;
        case 27: $s = "312212";    break;
        case 28: $s = "322112";    break;
        case 29: $s = "322211";    break;
        case 30: $s = "212123";    break;
        case 31: $s = "212321";    break;
        case 32: $s = "232121";    break;
        case 33: $s = "111323";    break;
        case 34: $s = "131123";    break;
        case 35: $s = "131321";    break;
        case 36: $s = "112313";    break;
        case 37: $s = "132113";    break;
        case 38: $s = "132311";    break;
        case 39: $s = "211313";    break;
        case 40: $s = "231113";    break;
        case 41: $s = "231311";    break;
        case 42: $s = "112133";    break;
        case 43: $s = "112331";    break;
        case 44: $s = "132131";    break;
        case 45: $s = "113123";    break;
        case 46: $s = "113321";    break;
        case 47: $s = "133121";    break;
        case 48: $s = "313121";    break;
        case 49: $s = "211331";    break;
        case 50: $s = "231131";    break;
        case 51: $s = "213113";    break;
        case 52: $s = "213311";    break;
        case 53: $s = "213131";    break;
        case 54: $s = "311123";    break;
        case 55: $s = "311321";    break;
        case 56: $s = "331121";    break;
        case 57: $s = "312113";    break;
        case 58: $s = "312311";    break;
        case 59: $s = "332111";    break;
        case 60: $s = "314111";    break;
        case 61: $s = "221411";    break;
        case 62: $s = "431111";    break;
        case 63: $s = "111224";    break;
        case 64: $s = "111422";    break;
        case 65: $s = "121124";    break;
        case 66: $s = "121421";    break;
        case 67: $s = "141122";    break;
        case 68: $s = "141221";    break;
        case 69: $s = "112214";    break;
        case 70: $s = "112412";    break;
        case 71: $s = "122114";    break;
        case 72: $s = "122411";    break;
        case 73: $s = "142112";    break;
        case 74: $s = "142211";    break;
        case 75: $s = "241211";    break;
        case 76: $s = "221114";    break;
        case 77: $s = "413111";    break;
        case 78: $s = "241112";    break;
        case 79: $s = "134111";    break;
        case 80: $s = "111242";    break;
        case 81: $s = "121142";    break;
        case 82: $s = "121241";    break;
        case 83: $s = "114212";    break;
        case 84: $s = "124112";    break;
        case 85: $s = "124211";    break;
        case 86: $s = "411212";    break;
        case 87: $s = "421112";    break;
        case 88: $s = "421211";    break;
        case 89: $s = "212141";    break;
        case 90: $s = "214121";    break;
        case 91: $s = "412121";    break;
        case 92: $s = "111143";    break;
        case 93: $s = "111341";    break;
        case 94: $s = "131141";    break;
        case 95: $s = "114113";    break;
        case 96: $s = "114311";    break;
        case 97: $s = "411113";    break;
        case 98: $s = "411311";    break;
        case 99: $s = "113141";    break;
        case 100: $s = "114131";    break;
        case 101: $s = "311141";    break;
        case 102: $s = "411131";    break;
        case 103: $s = "211412";    break;
        case 104: $s = "211214";    break;
        case 105: $s = "211232";    break;
        case 106: $s = "2331112";    break;
    }
    return $s;
}

//*********************** baba-jaga@i.ua ********************************
//'Штриховые символы шрифта iQs Code 128 по набору полос
function Code_Char($a){
    $s='';
    //Dim I As Integer
    //Dim B As String
    switch ($a){
    case "211412": $s = "A";    break;
    case "211214": $s = "B";    break;
    case "211232": $s = "C";    break;
    case "2331112":$s = "@";    break;
    default :
        $s = "";
        for ($i = 0;  $i <= strlen($a)/2-1 ;   $i++) { // надо минус 1 или нет
            switch (substr($a, 2 * $i , 2)){
           // Select Case Mid(A, 2 * I + 1, 2) поз два шт
                case "11": $s.= "0";                    break;
                case "21": $s.= "1";                    break;
                Case "31": $s.= "2";                    break;
                case "41": $s.= "3";                    break;
                case "12": $s.= "4";                    break;
                case "22": $s.= "5";                    break;
                case "32": $s.= "6";                    break;
                case "42": $s.= "7";                    break;
                Case "13": $s.= "8";                    break;
                Case "23": $s.= "9";                    break;
                Case "33": $s.= ":";                    break;
                Case "43": $s.= ";";                    break;
                Case "14": $s.= "<";                    break;
                Case "24": $s.= "=";                    break;
                Case "34": $s.= ">";                    break;
                Case "44": $s.= "?";                    break;
            }
        }
    }
    return $s ;
}

//*********************** baba-jaga@i.ua ********************************
//Строка штрих-кода в кодировке Code 128
function code_128($a){
    $BCode = array();
    //Dim BInd As Integer
    //Dim CurMode As String
    //Dim Ch As Integer
    //Dim Ch2 As Integer
    //Dim I As Integer
    //Dim LenA As Integer
    //Dim CCode As Integer
    //Dim S As String
    //Dim BarArray As Variant

    //Собираем строку кодов
    $BInd = 0;
    $CurMode = "";
    $i = 0;
    $LenA = strlen($a);
    while ($i<=$LenA-1){
    //While I <= LenA
        //'Текущий символ в строке
        $Ch = ord(substr($a, $i, 1));
        $i++;
        //'Разбираем только символы от 0 до 127
        if( $Ch <= 127){
            //'Следующий символ
            if( $i <= $LenA){
                $Ch2 = ord(substr($a, $i, 1));
            }else {
                $Ch2 = 0;
            }
            //'Пара цифр - режим С
            if( (48 <= $Ch) and ($Ch <= 57) and (48 <= $Ch2) and ($Ch2 <= 57)){
                $i++;
                if( $BInd == 0){
                   // 'Начало с режима С
                    $CurMode = "C";
                    $BCode[$BInd] = 105;
                    $BInd++;
                }elseif( $CurMode <> "C"){
                    //'Переключиться на режим С
                    $CurMode = "C";
                    $BCode[$BInd] = 99;
                    $BInd++;
                }
                //'Добавить символ режима С
                $BCode[$BInd] = intval( chr($Ch).chr($Ch2)); // CInt(Chr(Ch) & Chr(Ch2))
                $BInd++;
            }else{
                if($BInd == 0){
                    if( $Ch < 32){
                       // 'Начало с режима A
                        $CurMode = "A";
                        $BCode[$BInd] = 103;
                        $BInd++;
                    }else{
                       // 'Начало с режима B
                        $CurMode = "B";
                        $BCode[$BInd] = 104;
                        $BInd++;
                    }
                }
                //'Переключение по надобности в режим A
                if( ($Ch < 32) and ($CurMode <> "A")){
                    $CurMode = "A";
                    $BCode[$BInd] = 101;
                    $BInd++;
                //'Переключение по надобности в режим B
                }elseif( ((64 <= $Ch) and ($CurMode <> "B")) or ($CurMode = "C")){
                    $CurMode = "B";
                    $BCode[$BInd] = 100;
                    $BInd++;
                }
                //'Служебные символы
                if($Ch < 32){
                    $BCode[$BInd] = $Ch + 64;
                    $BInd++;
                //'Все другие символы
                }else{
                    $BCode[$BInd] = $Ch - 32;
                    $BInd++;
                }
            }
        }
    } //Wend
    //'Подсчитываем контрольную сумму
    $CCode = $BCode[0] % 103;
    for ($i = 1; $i <=$BInd-1; $i++) {//For I = 1 To BInd - 1
        $CCode = ($CCode + $BCode[$i] * $i) % 103 ;
    }
    $BCode[$BInd] = $CCode;
    $BInd++;
    //'Завершающий символ
    $BCode[$BInd] = 106;
    $BInd++;
    /*'Собираем строку символов шрифта
    'BarArray = Array("155", "515", "551", "449", "485", "845", "458", "494", "854", _
        "548", "584", "944", "056", "416", "452", "065", "425", "461", "560", "506", _
        "542", "164", "524", "212", "245", "605", "641", "254", "614", "650", "119", _
        "191", "911", "089", "809", "881", "098", "818", "890", "188", "908", "980", _
        "01:", "092", "812", "029", "0:1", "821", "221", "182", "902", "128", "1:0", _
        "122", "209", "281", ":01", "218", "290", ":10", "230", "5<0", ";00", "04=", _
        "0<5", "40=", "4<1", "<05", "<41", "05<", "0=4", "41<", "4=0", "<14", "<50", _
        "=40", "50<", "320", "=04", "830", "047", "407", "443", "074", "434", "470", _
        "344", "704", "740", "113", "131", "311", "00;", "083", "803", "038", "0;0", _
        "308", "380", "023", "032", "203", "302", "A", "B", "C", "@")
    */
    $s = "";
    for ($i = 0; $i <= $BInd-1; $i++) { // For I = 0 To BInd - 1
        $s.= Code_Char(Code_128_ID($BCode[$i]));
    }
    return $s;
}

//*********************** baba-jaga@i.ua ********************************
// добавляем в файл хмл нужные секции
function addinxml( $cena, $hkod, $strhkod ,  $n) {

  $str = '  <table:table-row table:style-name="ro1">
     <table:table-cell table:style-name="ce1" office:value-type="string">
      <text:p>'.$cena.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce2" office:value-type="string">
      <text:p>'.$hkod.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce3" office:value-type="string">
      <text:p>'.$strhkod.'</text:p>
     </table:table-cell>';
    
  $str1 = '  <table:table-cell table:style-name="ce4"/>
   <table:table-cell table:style-name="ce1" office:value-type="string">
      <text:p>'.$cena.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce2" office:value-type="string">
      <text:p>'.$hkod.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce3" office:value-type="string">
      <text:p>'.$strhkod.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce5"/>
    </table:table-row>';


    if ($n == 2) $str = $str1;

    return$str;
}

//*********************** baba-jaga@i.ua ********************************
// откр зап сохр
function prnxml(){
    //$xmlString = file_get_contents('hkod.xml');
    //$xml       = new SimpleXMLElement($xmlString);
    $handle = fopen('data/hkod.fods', 'w');
    //addinxml($xml, $tip, $zn, $n);

    fwrite($handle, strfile('start') ) ;


    global $db;
    $txt_sql = "SELECT `Tovar`.`Kod`, `Tovar`.`Price`, `Tovar`.`PriceOpt`, `cenniki`.`kvo_hkod`\n"
    . "FROM `cenniki`\n"
    . " LEFT JOIN `Tovar` ON `cenniki`.`id_tovar` = `Tovar`.`id_tovar` \n"
    . "WHERE (`cenniki`.`kvo_hkod` >0)\n"
    . " ";

    $sql = mysql_query($txt_sql, $db);
    $j=1;
    while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
        $hkod = code_128($srt_arr['Kod']);//&lt;
        $hkod = str_replace('<', '&lt;', $hkod);
        $kvo=$srt_arr['kvo_hkod'];
        $cena = $srt_arr['Price'];
        
        $cena = sprintf("%.2f", $cena);
        $cena = '';
        for ($i = 1; $i <=$kvo; $i++) {
            $n=1;
            if( $j % 2 == 0 ) $n=2;
            $j++;
            $str = addinxml( $cena, $hkod, $srt_arr['Kod'] ,  $n);
            fwrite($handle, $str ) ;
        }
    }
    
    if($n==1){
        $n=2;
            $str = addinxml( '','', '' ,  $n);
            fwrite($handle, $str ) ;
    }

    //$xml->saveXML('data/hkod.xml');
    fwrite($handle, strfile('end') ) ;
    fclose($handle);
    chmod('data/hkod.fods', 0777);

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


function strfile($f){
   $start = '<?xml version="1.0" encoding="UTF-8"?>

<office:document xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:rpt="http://openoffice.org/2005/report" xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:grddl="http://www.w3.org/2003/g/data-view#" xmlns:tableooo="http://openoffice.org/2009/table" xmlns:field="urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0" xmlns:formx="urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0" xmlns:css3t="http://www.w3.org/TR/css3-text/" office:version="1.2" office:mimetype="application/vnd.oasis.opendocument.spreadsheet">
 <office:meta><meta:printed-by>san </meta:printed-by><meta:print-date>2014-01-02T18:22:31</meta:print-date><meta:generator>LibreOffice/3.5$Linux_X86_64 LibreOffice_project/350m1$Build-2</meta:generator><meta:document-statistic meta:table-count="1" meta:cell-count="24" meta:object-count="0"/></office:meta>
 <office:settings>
  <config:config-item-set config:name="ooo:view-settings">
   <config:config-item config:name="VisibleAreaTop" config:type="int">0</config:config-item>
   <config:config-item config:name="VisibleAreaLeft" config:type="int">0</config:config-item>
   <config:config-item config:name="VisibleAreaWidth" config:type="int">3197</config:config-item>
   <config:config-item config:name="VisibleAreaHeight" config:type="int">9263</config:config-item>
   <config:config-item-map-indexed config:name="Views">
    <config:config-item-map-entry>
     <config:config-item config:name="ViewId" config:type="string">view1</config:config-item>
     <config:config-item-map-named config:name="Tables">
      <config:config-item-map-entry config:name="Лист1">
       <config:config-item config:name="CursorPositionX" config:type="int">9</config:config-item>
       <config:config-item config:name="CursorPositionY" config:type="int">2</config:config-item>
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
     <config:config-item config:name="HorizontalScrollbarWidth" config:type="int">555</config:config-item>
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
   <config:config-item-map-indexed config:name="ForbiddenCharacters">
    <config:config-item-map-entry>
     <config:config-item config:name="Language" config:type="string">ru</config:config-item>
     <config:config-item config:name="Country" config:type="string">RU</config:config-item>
     <config:config-item config:name="Variant" config:type="string"/>
     <config:config-item config:name="BeginLine" config:type="string"/>
     <config:config-item config:name="EndLine" config:type="string"/>
    </config:config-item-map-entry>
   </config:config-item-map-indexed>
   <config:config-item config:name="AutoCalculate" config:type="boolean">true</config:config-item>
   <config:config-item config:name="PrinterSetup" config:type="base64Binary">lAH+/1RTQ19URFAtMjI1AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQ1VQUzpUU0NfVERQLTIyNQAAAAAAAAAAAAAAAAAAAAAWAAMApAAAAAAAAAALAIoMAABfCQAASm9iRGF0YSAxCnByaW50ZXI9VFNDX1REUC0yMjUKb3JpZW50YXRpb249UG9ydHJhaXQKY29waWVzPTEKbWFyZ2luZGFqdXN0bWVudD0wLDAsMCwwCmNvbG9yZGVwdGg9MjQKcHNsZXZlbD0wCnBkZmRldmljZT0xCmNvbG9yZGV2aWNlPTAKUFBEQ29udGV4RGF0YQpQYWdlU2l6ZTp3Mmg0AAAKAElzUXVpY2tKb2IEAHRydWUSAENPTVBBVF9EVVBMRVhfTU9ERQ4ARFVQTEVYX1VOS05PV04=</config:config-item>
   <config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item>
   <config:config-item config:name="IsDocumentShared" config:type="boolean">false</config:config-item>
   <config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item>
   <config:config-item config:name="PrinterName" config:type="string">TSC_TDP-225</config:config-item>
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
   <ooo:libraries xmlns:ooo="http://openoffice.org/2004/office" xmlns:xlink="http://www.w3.org/1999/xlink">
    <ooo:library-embedded ooo:name="Standard"/>
   </ooo:libraries>
  </office:script>
 </office:scripts>
 <office:font-face-decls>
  <style:font-face style:name="Barcode" svg:font-family="Barcode"/>
  <style:font-face style:name="Liberation Sans" svg:font-family="&apos;Liberation Sans&apos;" style:font-family-generic="swiss" style:font-pitch="variable"/>
  <style:font-face style:name="DejaVu Sans" svg:font-family="&apos;DejaVu Sans&apos;" style:font-family-generic="system" style:font-pitch="variable"/>
 </office:font-face-decls>
 <office:styles>
  <style:default-style style:family="table-cell">
   <style:paragraph-properties style:tab-stop-distance="1.25cm"/>
   <style:text-properties style:font-name="Liberation Sans" fo:language="ru" fo:country="RU" style:font-name-asian="DejaVu Sans" style:language-asian="zh" style:country-asian="CN" style:font-name-complex="DejaVu Sans" style:language-complex="hi" style:country-complex="IN"/>
  </style:default-style>
  <number:number-style style:name="N0">
   <number:number number:min-integer-digits="1"/>
  </number:number-style>
  <number:number-style style:name="N109P0" style:volatile="true">
   <number:text>$</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
  </number:number-style>
  <number:number-style style:name="N109">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>$-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N109P0"/>
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
  <number:currency-style style:name="N112P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
  </number:currency-style>
  <number:currency-style style:name="N112">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N112P0"/>
  </number:currency-style>
  <style:style style:name="Default" style:family="table-cell"/>
  <style:style style:name="Result" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties style:use-window-font-color="true" fo:font-size="10pt" fo:font-style="italic" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="italic" style:font-weight-asian="bold" style:font-size-complex="10pt" style:font-style-complex="italic" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="Result2" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N109">
   <style:text-properties style:use-window-font-color="true" fo:font-size="10pt" fo:font-style="italic" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="italic" style:font-weight-asian="bold" style:font-size-complex="10pt" style:font-style-complex="italic" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="Heading" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties style:use-window-font-color="true" fo:font-size="16pt" fo:font-style="italic" fo:font-weight="bold" style:font-size-asian="16pt" style:font-style-asian="italic" style:font-weight-asian="bold" style:font-size-complex="16pt" style:font-style-complex="italic" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="Heading1" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties style:use-window-font-color="true" fo:font-size="16pt" fo:font-style="italic" fo:font-weight="bold" style:font-size-asian="16pt" style:font-style-asian="italic" style:font-weight-asian="bold" style:font-size-complex="16pt" style:font-style-complex="italic" style:font-weight-complex="bold"/>
  </style:style>
 </office:styles>
 <office:automatic-styles>
  <style:style style:name="co1" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.52cm"/>
  </style:style>
  <style:style style:name="co2" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.55cm"/>
  </style:style>
  <style:style style:name="co3" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.379cm"/>
  </style:style>
  <style:style style:name="co4" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.298cm"/>
  </style:style>
  <style:style style:name="co5" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.381cm"/>
  </style:style>
  <style:style style:name="co6" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.258cm"/>
  </style:style>
  <style:style style:name="ro1" style:family="table-row">
   <style:table-row-properties style:row-height="2.316cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro2" style:family="table-row">
   <style:table-row-properties style:row-height="0.427cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ta1" style:family="table" style:master-page-name="Default">
   <style:table-properties table:display="true" style:writing-mode="lr-tb"/>
  </style:style>
  <number:number-style style:name="N2">
   <number:number number:decimal-places="2" number:min-integer-digits="1"/>
  </number:number-style>
  <style:style style:name="ce1" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N2">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" style:rotation-angle="90" style:vertical-align="middle"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties fo:font-size="10pt" fo:font-weight="bold" style:font-size-asian="10pt" style:font-weight-asian="bold" style:font-size-complex="10pt" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce2" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" style:rotation-angle="90" style:vertical-align="middle"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties style:use-window-font-color="true" style:font-name="Barcode" fo:font-size="24pt" style:font-name-asian="Barcode" style:font-size-asian="24pt" style:font-name-complex="Barcode" style:font-size-complex="24pt"/>
  </style:style>
  <style:style style:name="ce3" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" style:rotation-angle="90" style:vertical-align="middle"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties fo:font-size="7pt" style:font-size-asian="7pt" style:font-size-complex="7pt"/>
  </style:style>
  <style:style style:name="ce4" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" style:rotation-angle="90" style:vertical-align="middle"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties fo:font-size="9pt" fo:font-weight="bold" style:font-size-asian="9pt" style:font-weight-asian="bold" style:font-size-complex="9pt" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce5" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties fo:font-size="7pt" fo:font-weight="bold" style:font-size-asian="7pt" style:font-weight-asian="bold" style:font-size-complex="7pt" style:font-weight-complex="bold"/>
  </style:style>
  <style:page-layout style:name="pm1">
   <style:page-layout-properties fo:page-width="3.2cm" fo:page-height="2.3cm" fo:margin="0cm" fo:margin-top="0cm" fo:margin-bottom="0cm" fo:margin-left="0cm" fo:margin-right="0cm" style:writing-mode="lr-tb"/>
   <style:header-style>
    <style:header-footer-properties fo:min-height="0.101cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-bottom="0cm"/>
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
  <style:page-layout style:name="pm3">
   <style:page-layout-properties style:num-format="1" style:print-orientation="portrait" style:first-page-number="continue"/>
   <style:header-style/>
   <style:footer-style/>
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
     <text:p><text:date style:data-style-name="N2" text:date-value="2014-01-02">00.00.0000</text:date>, <text:time>00:00:00</text:time></text:p>
    </style:region-right>
   </style:header>
   <style:header-left style:display="false"/>
   <style:footer>
    <text:p>Страница <text:page-number>1</text:page-number> / <text:page-count>99</text:page-count></text:p>
   </style:footer>
   <style:footer-left style:display="false"/>
  </style:master-page>
  <style:master-page style:name="PageStyle_5f_Лист1" style:display-name="PageStyle_Лист1" style:page-layout-name="pm3">
   <style:header style:display="false"/>
   <style:header-left style:display="false"/>
   <style:footer style:display="false"/>
   <style:footer-left style:display="false"/>
  </style:master-page>
  <style:master-page style:name="PageStyle_5f_Лист2" style:display-name="PageStyle_Лист2" style:page-layout-name="pm3">
   <style:header style:display="false"/>
   <style:header-left style:display="false"/>
   <style:footer style:display="false"/>
   <style:footer-left style:display="false"/>
  </style:master-page>
  <style:master-page style:name="PageStyle_5f_Лист3" style:display-name="PageStyle_Лист3" style:page-layout-name="pm3">
   <style:header style:display="false"/>
   <style:header-left style:display="false"/>
   <style:footer style:display="false"/>
   <style:footer-left style:display="false"/>
  </style:master-page>
 </office:master-styles>
 <office:body>
  <office:spreadsheet>
   <table:table table:name="Лист1" table:style-name="ta1">
    <table:table-column table:style-name="co1" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co2" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co3" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co4" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co1" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co2" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co3" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co5" table:default-cell-style-name="Default"/>
    ';


  $end = '    
      <table:table-row table:style-name="ro3" table:number-rows-repeated="1048573">
            <table:table-cell table:number-columns-repeated="8"/>
           </table:table-row>
           <table:table-row table:style-name="ro3">
            <table:table-cell table:number-columns-repeated="8"/>
           </table:table-row>
          </table:table>
          <table:named-expressions/>
         </office:spreadsheet>
        </office:body>
       </office:document>';

 if($f=='start')     return cnv_ ( $start) ;
 if($f=='end')       return cnv_ ( $end );

}



?>
