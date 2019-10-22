<?php

require("header.inc.php");
include("milib.inc");

$iddoc = $_GET['iddoc'];

global $db;

$txt_sql = "SELECT  `name_full`,`INN`,`tel_firm`,`nom_svid`,`okpo`,`Info_nalog`,`adres`
            FROM `firms` 
            WHERE `id` IN (SELECT `firms_id` FROM `DocHd` WHERE `id`='$iddoc') ";
$s_arr   = mysql_fetch_array(mysql_query($txt_sql, $db), MYSQL_BOTH);

$firm = $s_arr['name_full'];
$tel  = $s_arr['tel_firm'];
$nalog_data = 'ІПН : ' . $s_arr['INN'] . ', номер свідоцтва:' . $s_arr['nom_svid'];

$txt_sql = "SELECT `nomDoc`,`DataDoc`,`SumDoc`,`sum_v_kassu`, `SkidkaProcent`,
        `flg_optPrice`,`Pometka` FROM `DocHd` WHERE `id` = " . $iddoc ;
$s_arrHD = mysql_fetch_array(mysql_query($txt_sql, $db), MYSQL_BOTH);




?>

<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1251">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 11">
<link rel=File-List href="ttt.files/filelist.xml">
<link rel=Edit-Time-Data href="ttt.files/editdata.mso">
<link rel=OLE-Object-Data href="ttt.files/oledata.mso">
<title>Печать чек</title>
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:LastAuthor>a_4ergik</o:LastAuthor>
  <o:LastPrinted>2011-10-18T02:51:07Z</o:LastPrinted>
  <o:Created>2011-10-18T03:05:10Z</o:Created>
  <o:LastSaved>2011-10-18T03:05:10Z</o:LastSaved>
  <o:Version>11.5703</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:Colors>
   <o:Color>
    <o:Index>10</o:Index>
    <o:RGB>#808000</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>11</o:Index>
    <o:RGB>#000080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>14</o:Index>
    <o:RGB>#808080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>15</o:Index>
    <o:RGB>#C0C0C0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>16</o:Index>
    <o:RGB>#8080FF</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>17</o:Index>
    <o:RGB>#802060</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>18</o:Index>
    <o:RGB>#FFFFC0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>19</o:Index>
    <o:RGB>#A0E0E0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>20</o:Index>
    <o:RGB>#600080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>22</o:Index>
    <o:RGB>#0080C0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>23</o:Index>
    <o:RGB>#C0C0FF</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>24</o:Index>
    <o:RGB>#00CFFF</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>25</o:Index>
    <o:RGB>#69FFFF</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>26</o:Index>
    <o:RGB>#E0FFE0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>27</o:Index>
    <o:RGB>#DD9CB3</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>28</o:Index>
    <o:RGB>#B38FEE</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>29</o:Index>
    <o:RGB>#2A6FF9</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>30</o:Index>
    <o:RGB>#3FB8CD</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>31</o:Index>
    <o:RGB>#488436</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>32</o:Index>
    <o:RGB>#958C41</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>33</o:Index>
    <o:RGB>#8E5E42</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>34</o:Index>
    <o:RGB>#A0627A</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>35</o:Index>
    <o:RGB>#624FAC</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>36</o:Index>
    <o:RGB>#1D2FBE</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>37</o:Index>
    <o:RGB>#286676</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>38</o:Index>
    <o:RGB>#004500</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>39</o:Index>
    <o:RGB>#453E01</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>40</o:Index>
    <o:RGB>#6A2813</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>41</o:Index>
    <o:RGB>#85396A</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>42</o:Index>
    <o:RGB>#4A3285</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>43</o:Index>
    <o:RGB>#C0DCC0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>44</o:Index>
    <o:RGB>#A6CAF0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>45</o:Index>
    <o:RGB>#800000</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>46</o:Index>
    <o:RGB>#008000</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>47</o:Index>
    <o:RGB>#000080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>48</o:Index>
    <o:RGB>#808000</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>49</o:Index>
    <o:RGB>#800080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>50</o:Index>
    <o:RGB>#008080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>51</o:Index>
    <o:RGB>#808080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>52</o:Index>
    <o:RGB>#FFFBF0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>53</o:Index>
    <o:RGB>#A0A0A4</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>54</o:Index>
    <o:RGB>#313900</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>55</o:Index>
    <o:RGB>#D9853E</o:RGB>
   </o:Color>
  </o:Colors>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<style>

 /* div{border: 1px solid #000; width:210mm; height:297mm; margin:25px 0;} */
 /* div{border: 1px solid #000; width:210; height:297mm; margin:2px 0;}
print{div{border:0; width:auto; height:auto; margin:2px 0;}}
*/

 div{border: 0px ; width:210mm; height:297mm; margin:2px 0;}
	@media
	print{div{zoom: 1.6;}}


table
{mso-displayed-decimal-separator:"\,"; 	mso-displayed-thousand-separator:"\,";}

tr
{mso-height-source:auto;}
col
{mso-width-source:auto;}
br
{mso-data-placement:same-cell;}
.style0
{mso-number-format:General;
	text-align:left;
	vertical-align:bottom;
	white-space:nowrap;
	mso-rotate:0;
	mso-background-source:auto;
	mso-pattern:auto;
	color:windowtext;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	border:none;
	mso-protection:locked visible;
	mso-style-name:Обычный;
	mso-style-id:0;}
td
{mso-style-parent:style0;
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	border:none;
	mso-background-source:auto;
	mso-pattern:auto;
	mso-protection:locked visible;
	white-space:nowrap;
	mso-rotate:0;}
.xl19
{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl20
{mso-style-parent:style0;
	font-size:14.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl21
{mso-style-parent:style0;
	font-size:6.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;}
.xl22
{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl23
{mso-style-parent:style0;
	font-size:10.0pt;}
.xl24
{mso-style-parent:style0;
	font-size:12.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;}
.xl25
{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;}
.xl26
{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl27
{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;}
.xl28
{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:middle;
	border:.5pt solid windowtext;}
.xl29
{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;}
.xl30
{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl31
{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl32
{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:middle;
	border:.5pt solid windowtext;
	white-space:normal;}
.xl33
{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:0;
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl34
{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl35
{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:Fixed;
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl36
{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	vertical-align:top;}
.xl37
{mso-style-parent:style0;
	font-size:9.0pt;
	vertical-align:top;}
.xl38
{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl39
{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	mso-number-format:Fixed;
	text-align:right;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl40
{mso-style-parent:style0;
	font-size:9.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl41
{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;
	vertical-align:top;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl42
{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl43
{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;}
.xl44
{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	vertical-align:top;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	white-space:normal;}
.xl45
{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	vertical-align:top;
	white-space:normal;}
.xl46
{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}

</style>
</head>

<div >

<body link=blue vlink="#B38FEE">

<table x:str border=0 cellpadding=0 cellspacing=0 width=670 style='border-collapse: collapse;table-layout:fixed;width:504pt'>
 <col width=22 style='mso-width-source:userset;mso-width-alt:938;width:17pt'>
 <col width=99 style='mso-width-source:userset;mso-width-alt:4224;width:74pt'>
 <col width=83 style='mso-width-source:userset;mso-width-alt:3541;width:62pt'>
 <col width=102 style='mso-width-source:userset;mso-width-alt:4352;width:77pt'>
 <col width=31 style='mso-width-source:userset;mso-width-alt:1322;width:23pt'>
 <col width=37 style='mso-width-source:userset;mso-width-alt:1578;width:28pt'>
 <col width=55 style='mso-width-source:userset;mso-width-alt:2346;width:41pt'>
 <col width=58 style='mso-width-source:userset;mso-width-alt:2474;width:44pt'>
 <col width=23 style='mso-width-source:userset;mso-width-alt:981;width:17pt'>
 <col width=26 style='mso-width-source:userset;mso-width-alt:1109;width:20pt'>
 <col width=40 style='mso-width-source:userset;mso-width-alt:1706;width:30pt'>
 <col width=41 style='mso-width-source:userset;mso-width-alt:1749;width:31pt'>
 <col width=53 style='mso-width-source:userset;mso-width-alt:2261;width:40pt'>
 <col width=62 span=243 style='mso-width-source:userset;mso-width-alt:2645; width:47pt'>

 <tr height=26 style='height:19.5pt'>
  <td height=26 class=xl19 width=22 style='height:19.5pt;width:17pt'></td>
  <td class=xl20 colspan=2 width=182 style='mso-ignore:colspan;width:136pt'>  	<?php echo $firm ?> </td>
  <td class=xl19 width=102 style='width:77pt'></td>
  <td class=xl19 width=31 style='width:23pt'></td>
  <td class=xl19 width=37 style='width:28pt'></td>
  <td class=xl19 width=55 style='width:41pt'></td>
  <td class=xl21 width=58 style='width:44pt'></td>
  <td class=xl19 width=23 style='width:17pt'></td>
  <td class=xl19 width=26 style='width:20pt'></td>
  <td width=40 style='width:30pt'></td>
  <td width=41 style='width:31pt'></td>
  <td class=xl21 width=53 style='width:40pt'></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl19 style='height:12.75pt'></td>
  <td class=xl22 colspan=3 style='mso-ignore:colspan'>Обрана система оподаткування: єдиний податок</td>
  <td colspan=6 class=xl19 style='mso-ignore:colspan'></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl19></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'></td>
  <td class=xl22 > <?php echo 'тел.:' . $tel;  ?> </td>
  <td colspan=11 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'></td>
  <td class=xl22 colspan=3 style='mso-ignore:colspan'><?php echo $nalog_data;  ?></td>
  <td colspan=5 style='mso-ignore:colspan'></td>
  <td class=xl23 colspan=4 style='mso-ignore:colspan'><?php echo datesql_to_str( $s_arrHD['DataDoc']) ;  ?></td>
 </tr>
 <tr height=23 style='mso-height-source:userset;height:17.25pt'>
  <td height=23 class=xl24 colspan=3 style='height:17.25pt;mso-ignore:colspan'><?php echo 'Товарний  чек №_' .  $s_arrHD['nomDoc']  ?></td>
  <td class=xl24 colspan=3 style='mso-ignore:colspan'><?php echo 'від: '. datesql_to_str( $s_arrHD['DataDoc']) ;  ?></td>
  <td colspan=3 class=xl19 style='mso-ignore:colspan'></td>
  <td class=xl25 colspan=3 style='mso-ignore:colspan'> <?php echo 'чек №_' .  $s_arrHD['nomDoc']  ?> </td>
  <td class=xl19></td>
 </tr>

 <tr height=10 style='mso-height-source:userset;height:7.5pt'>
  <td height=10 style='height:7.5pt'></td>
  <td colspan=7 class=xl27 style='mso-ignore:colspan'></td>
  <td class=xl19></td>
  <td></td>
  <td colspan=3 class=xl27 style='mso-ignore:colspan'></td>
 </tr>
 <tr class=xl23 height=17 style='height:12.75pt'>
  <td height=17 class=xl28 style='height:12.75pt'>№</td>
  <td colspan=3 class=xl29 align=center style='mso-ignore:colspan; border-right:.5pt solid black'>Найменування товару</td>
  <td class=xl28 style='border-left:none'>Од.</td>
  <td class=xl28 style='border-left:none'>К-ть</td>
  <td class=xl32 width=55 style='border-left:none;width:41pt'>Ціна</td>
  <td class=xl32 width=58 style='border-left:none;width:44pt'>Сума</td>
  <td class=xl22></td>
  <td class=xl28>№</td>
  <td class=xl28 style='border-left:none'>К-ть</td>
  <td class=xl32 width=41 style='border-left:none;width:31pt'>Ціна</td>
  <td class=xl32 width=53 style='border-left:none;width:40pt'>Сума</td>
 </tr>


<!--//
 <tr class=xl37 height=16 style='height:12.0pt'>
  <td height=16 class=xl33 style='height:12.0pt;border-top:none' x:num>1</td>
  <td colspan=3 class=xl44 width=284 style='border-left:none;width:213pt'>Бумага А3 KUM LUX (50л)</td>
  <td class=xl34 style='border-top:none'>шт.</td>
  <td class=xl33 style='border-top:none;border-left:none' x:num>1</td>
  <td class=xl35 style='border-top:none;border-left:none' x:num="33">33,00</td>
  <td class=xl35 style='border-top:none;border-left:none' x:num="33">33,00</td>
  <td class=xl36></td>
  <td class=xl33 style='border-top:none' x:num>1</td>
  <td class=xl33 style='border-top:none;border-left:none' x:num>1</td>
  <td class=xl35 style='border-top:none;border-left:none' x:num="33">33,00</td>
  <td class=xl35 style='border-top:none;border-left:none' x:num="33">33,00</td>
 </tr>//-->

 <?php
 // выводим данные продаж в таблицу


$txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Tovar`, `DocTab`.`Cena`,"
    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$iddoc." AND `DocTab`.`Kvo` > 0 )\n"
    . "ORDER BY `DocTab`.`id` ASC ";



$sql = mysql_query($txt_sql, $db);
$sum_itog=0;  $sum_sum=0; $skidka=0;
while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {

  $sum = sprintf("%.2f", $srt_arr['Kvo'] *  $srt_arr['Cena']);
  $it  = sprintf("%.2f", $srt_arr['Kvo'] *  $srt_arr['Cena']  * (1 - $srt_arr['Skidka']/100  ) );
  $sum_itog = $sum_itog +   $srt_arr['Kvo'] *  $srt_arr['Cena']  * (1 - $srt_arr['Skidka']/100  );
  $sum_sum = $sum_sum + $sum;
  $skidka = round( skidkadoc($iddoc) ,2) ; //$srt_arr['Skidka'];
  $cena = sprintf('%.2f', $srt_arr['Cena']);
   echo " <tr class=xl37 height=16 style='height:12.0pt'>
	  <td height=16 class=xl33 style='height:12.0pt;border-top:none' x:num>   " . $srt_arr['nomstr']."</td>
	  <td colspan=3 class=xl44 width=284 style='border-left:none;width:213pt'>" .$srt_arr['Tovar']."</td>
	  <td class=xl34 style='border-top:none'>шт.</td>
	  <td class=xl33 style='border-top:none;border-left:none' x:num>" .$srt_arr['Kvo']."</td>
	  <td class=xl35 style='border-top:none;border-left:none' x:num> $cena </td>
	  <td class=xl35 style='border-top:none;border-left:none' x:num>$sum</td>
	  <td class=xl36></td>
	  <td class=xl33 style='border-top:none' x:num>" . $srt_arr['nomstr']."</td>
	  <td class=xl33 style='border-top:none;border-left:none' x:num>" .$srt_arr['Kvo']."</td>
	  <td class=xl35 style='border-top:none;border-left:none' x:num>$cena </td>
	  <td class=xl35 style='border-top:none;border-left:none' x:num=>$sum</td>
			  </tr>  ";

	        //echo "<tr>";
	        //echo "<td>". $srt_arr[0] . "</td>";  //код
	        //echo "<td>". $srt_arr[1] . "</td>";   //товар
	        //echo "<td>&nbsp;". $srt_arr[2]."</td>";  //цена
	        //echo "<td>&nbsp;". $srt_arr[4] ."</td>"; // к-во
	        //echo "<td>&nbsp;".  $sum ."</td>"; //сумма
	       // echo "<td>&nbsp;", $srt_arr[5] ,"</td>"; # скидка
	       // echo "<td>&nbsp;". $it ."</td>"; //itog

}
        // округляем до 10 копеек
    //$sum_itog = round($sum_itog, 1);
    //$sum_sum  = round($sum_sum, 1);
    $sum_itog  = sprintf("%.2f",$sum_itog);
    $sum_sum = sprintf("%.2f",$sum_sum);

?>


 <tr height=20 style='mso-height-source:userset;height:15.0pt'>

  <td class=xl38"></td>
  <td height=20 colspan=6  class=xl39 style='border-top:none' >
  	<?php
	 echo 'Разом:&nbsp;' . $sum_sum ;
	 if($skidka != 0 ) {
	 	echo '&nbsp;&nbsp;знижка:&nbsp;&nbsp;' . $skidka . '%&nbsp;&nbsp; до сплати:' . $sum_itog;
	 }
	?></td>
  <td class=xl39 style='border-top:none'></td>
  <td class=xl39 style='border-top:none'></td>
  <td class=xl38 ></td>
  <td colspan=3 class=xl32 style='mso-ignore:colspan'>  	<?php
	 echo '=' . $sum_sum ;
	 if($skidka != 0 ) {
	 	echo '&nbsp/' . $skidka . '%&nbsp; /' . $sum_itog;
	 }
	?></td>

  <td class=xl39 style='border-top:none'></td>
 </tr>
 <tr height=15 style='height:11.25pt'>
  <td height=15 colspan=13 class=xl19 style='height:11.25pt;mso-ignore:colspan'></td>
 </tr>


 <tr height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 colspan=13 class=xl19 style='height:11.25pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl19 style='height:12.75pt'></td>
  <td class=xl41>Видав(ла)</td>
  <td colspan=2 class=xl46>&nbsp;</td>
  <td class=xl42>&nbsp;</td>
  <td class=xl41>Отримав(ла)</td>
  <td class=xl42>&nbsp;</td>
  <td colspan=2 class=xl19 style='mso-ignore:colspan'></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl42>&nbsp;</td>
  <td class=xl42>&nbsp;</td>
 </tr>

</table>

</body>
</div>
</html>
