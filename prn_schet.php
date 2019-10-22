<?php

require("header.inc.php");
include("milib.inc");

$iddoc = $_GET['iddoc'];

global $db;

$txt_sql = "SELECT `name_full`,`INN`,`tel_firm`,`nom_svid`,`okpo`,`Info_nalog`,`adres`,
                    `name_bank`,`mfo_bank`,`r_schet`
            FROM `firms` WHERE `id` IN (SELECT `firms_id` FROM `DocHd` WHERE `id`='$iddoc') ";

   $s_arr   = mysql_fetch_array(mysql_query($txt_sql, $db), MYSQL_BOTH);
   $firm = $s_arr['name_full'];
   $tel  = $s_arr['tel_firm'];
   $tel_zkpo = " ЗКПО:&nbsp;" . $s_arr['okpo'] . "&nbsp;&nbsp;&nbsp; тел.:&nbsp;" . $s_arr['tel_firm'];
   $firm_adres = $s_arr['adres'];
   $str_nalog = $s_arr['Info_nalog'];
   $nalog_data = '&nbsp;ІПН : &nbsp;' . $s_arr['INN'] . ',&nbsp;&nbsp; номер свідоцтва:' . $s_arr['nom_svid'];
   $info_banc1 = 'Платіжні реквізити:&nbsp;' .  $s_arr['name_bank'] . '&nbsp;&nbsp; МФО:&nbsp;' .  $s_arr['mfo_bank'] ;
   $info_banc2 = 'Р/р:&nbsp;' . $s_arr['r_schet'] ;
   $info_banc=$info_banc1.' '.$info_banc2;

   $txt_sql = "SELECT `DocHd`.`nomDoc`, `DocHd`.`DataDoc`, `DocHd`.`SumDoc`, `DocHd`.`SkidkaProcent`,
                       `Klient`.`name_klient`,  `Klient`.`name_full`
                FROM `DocHd`
                LEFT JOIN `Klient` ON `DocHd`.`Klient_id` = `Klient`.`id_`
                WHERE `DocHd`.`id` =" . $iddoc ;

   $s_arrHD = mysql_fetch_array(mysql_query($txt_sql, $db), MYSQL_BOTH);


$nom_chek = $s_arrHD['nomDoc'];
$name_klient = $s_arrHD['name_klient'];
if(trim($s_arrHD['name_full']) != '' ) $name_klient =$s_arrHD['name_full'];

$dateSchet = datesql_to_str( $s_arrHD['DataDoc']) ;


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
<title>Печать счет</title>
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
<!--table
	{mso-displayed-decimal-separator:"\,";
	mso-displayed-thousand-separator:" ";}
@page
	{margin:.98in .79in .98in .79in;
	mso-header-margin:.5in;
	mso-footer-margin:.5in;}
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
	font-size:14.0pt;
	font-weight:700;
	font-style:italic;}
.xl20
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;}
.xl21
	{mso-style-parent:style0;
	font-size:6.0pt;
	text-align:right;}
.xl22
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl23
	{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl24
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	vertical-align:top;}
.xl25
	{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	vertical-align:top;}
.xl26
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:middle;
	border:.5pt solid windowtext;
	white-space:normal;}
.xl27
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	vertical-align:middle;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	white-space:normal;}
.xl28
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;
	white-space:normal;}
.xl29
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:none;
	white-space:normal;}
.xl30
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	white-space:normal;}
.xl31
	{mso-style-parent:style0;
	font-size:10.0pt;
	white-space:normal;}
.xl32
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:0;
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl33
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl34
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:"0\.000";
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl35
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	vertical-align:top;}
.xl36
	{mso-style-parent:style0;
	vertical-align:top;}
.xl37
	{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;}
.xl38
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	mso-number-format:Fixed;
	text-align:right;
	border:.5pt solid windowtext;}
.xl39
	{mso-style-parent:style0;
	font-size:12.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl40
	{mso-style-parent:style0;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl41
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	vertical-align:middle;}
.xl42
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;
	vertical-align:middle;}
.xl43
	{mso-style-parent:style0;
	font-size:12.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;
	text-align:left;
	white-space:normal;}
.xl44
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	white-space:normal;}
.xl45
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	vertical-align:top;
	white-space:normal;}
.xl46
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	vertical-align:top;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	white-space:normal;}
-->
</style>


</head>

<body link=blue vlink="#B38FEE"  >

<table x:str border=0 cellpadding=0 cellspacing=0 width=1056 style='border-collapse:
 collapse;table-layout:fixed;width:795pt'>
 <col width=29 style='mso-width-source:userset;mso-width-alt:1237;width:22pt'>
 <col width=98 style='mso-width-source:userset;mso-width-alt:4181;width:74pt'>
 <col width=83 style='mso-width-source:userset;mso-width-alt:3541;width:62pt'>
 <col width=82 style='mso-width-source:userset;mso-width-alt:3498;width:62pt'>
 <col width=31 style='mso-width-source:userset;mso-width-alt:1322;width:23pt'>
 <col width=46 style='mso-width-source:userset;mso-width-alt:1962;width:35pt'>
 <col width=70 style='mso-width-source:userset;mso-width-alt:2986;width:53pt'>
 <col width=68 style='mso-width-source:userset;mso-width-alt:2901;width:51pt'>
 <col width=55 style='mso-width-source:userset;mso-width-alt:2346;width:41pt'>
 <col width=29 style='mso-width-source:userset;mso-width-alt:1237;width:22pt'>
 <col width=62 span=2 style='mso-width-source:userset;mso-width-alt:2645;width:47pt'>
 <col width=143 style='mso-width-source:userset;mso-width-alt:6101;width:107pt'>
 <col width=30 style='mso-width-source:userset;mso-width-alt:1280;width:23pt'>
 <col width=43 style='mso-width-source:userset;mso-width-alt:1834;width:32pt'>
 <col width=63 style='mso-width-source:userset;mso-width-alt:2688;width:47pt'>
 <col width=62 span=240 style='mso-width-source:userset;mso-width-alt:2645;width:47pt'>
 <tr height=25 style='mso-height-source:userset;height:18.75pt'>
  <td height=25 width=29 style='height:18.75pt;width:22pt'></td>
  <td class=xl19 colspan=7 width=478 style='mso-ignore:colspan;width:360pt'>
 	 <?php echo 'Рахунок №_' .  $nom_chek . '&nbsp;&nbsp;від:&nbsp;'. $dateSchet.'&nbsp;p.' ?>
  </td>
  <td width=55 style='width:41pt'></td>
  <td width=29 style='width:22pt'></td>
  <td class=xl19 colspan=7 width=465 style='mso-ignore:colspan;width:350pt'>
      <?php echo 'Рахунок №_' .  $nom_chek . '&nbsp;&nbsp;від:&nbsp;'. $dateSchet.'&nbsp;p.' ?>
  </td>
 </tr>
 <tr height=24 style='mso-height-source:userset;height:18.0pt'>
  <td height=24 style='height:18.0pt'></td>
  <td colspan=7 class=xl43 width=478 style='width:360pt'>	<?php echo $firm ?> </td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td colspan=7 class=xl43 width=465 style='width:350pt'>	<?php echo $firm ?> </td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'></td>
  <td class=xl20 colspan=4 style='mso-ignore:colspan'> <?php echo 'тел.&nbsp;' . $tel . $nalog_data  ?>  </td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl21></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl20 colspan=4 style='mso-ignore:colspan'> <?php echo 'тел.&nbsp;' . $tel . $nalog_data  ?>  </td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=16 style='height:12.0pt'>
  <td height=16 class=xl22 style='height:12.0pt'></td>
  <td colspan=7 class=xl44 width=478 style='width:360pt'>
  	<?php echo $info_banc  ?>
  </td>
  <td colspan=2 class=xl22 style='mso-ignore:colspan'></td>
  <td colspan=7 class=xl44 width=465 style='width:350pt'>
    <?php echo $info_banc ?>
  </td>
 </tr>
 <tr height=16 style='height:12.0pt'>
  <td height=16 class=xl22 style='height:12.0pt'></td>
  <td class=xl22 colspan=5 style='mso-ignore:colspan'><?php  echo  $str_nalog   ?></td>
  <td colspan=4 class=xl22 style='mso-ignore:colspan'></td>
  <td class=xl22 colspan=5 style='mso-ignore:colspan'> <?php echo $str_nalog   ?> </td>
  <td colspan=2 class=xl22 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=38 style='mso-height-source:userset;height:28.5pt'>
  <td height=38 class=xl23 style='height:28.5pt'></td>
  <td class=xl24>Платник:</td>
  <td colspan=6 class=xl45 width=380 style='width:286pt'> <?php echo $name_klient ?> </td>
  <td colspan=2 class=xl25 style='mso-ignore:colspan'></td>
  <td class=xl24>Платник:</td>
  <td colspan=6 class=xl45 width=403 style='width:303pt'><?php echo $name_klient ?></td>
 </tr>
 <tr class=xl31 height=28 style='mso-height-source:userset;height:21.0pt'>
  <td height=28 class=xl26 width=29 style='height:21.0pt;width:22pt'>№</td>
  <td colspan=3 class=xl27 align=center width=263 style='mso-ignore:colspan;
  border-right:.5pt solid black;width:198pt'>Найменування товару</td>
  <td class=xl26 width=31 style='border-left:none;width:23pt'>Од.</td>
  <td class=xl26 width=46 style='border-left:none;width:35pt'>К-ть</td>
  <td class=xl26 width=70 style='border-left:none;width:53pt'>Ціна</td>
  <td class=xl26 width=68 style='border-left:none;width:51pt'>Сума</td>
  <td class=xl30></td>
  <td class=xl26 width=29 style='width:22pt'>№</td>
  <td colspan=3 class=xl27 align=center width=267 style='mso-ignore:colspan;
  border-right:.5pt solid black;width:201pt'>Найменування товару</td>
  <td class=xl26 width=30 style='border-left:none;width:23pt'>Од.</td>
  <td class=xl26 width=43 style='border-left:none;width:32pt'>К-ть</td>
  <td class=xl26 width=63 style='border-left:none;width:47pt'>Ціна</td>
  <td class=xl26 width=62 style='border-left:none;width:47pt'>Сума</td>
 </tr>

<!--// // <tr class=xl36 height=17 style='height:12.75pt'>
//  <td height=17 class=xl32 style='height:12.75pt;border-top:none' x:num>1</td>
//  <td colspan=3 class=xl46 width=263 style='border-left:none;width:198pt'>Анкета
//  для друзей А5 &quot;Истории&quot; 18346</td>
//  <td class=xl33 style='border-top:none'>шт.</td>
//  <td class=xl32 style='border-top:none;border-left:none' x:num>1</td>
//  <td class=xl34 style='border-top:none;border-left:none' x:num="3.6">3,600</td>
//  <td class=xl34 style='border-top:none;border-left:none' x:num="3.6">3,600</td>
//  <td class=xl35></td>
//  <td class=xl32 style='border-top:none' x:num>1</td>
//  <td colspan=3 class=xl46 width=267 style='border-left:none;width:201pt'>Анкета
//  для друзей А5 &quot;Истории&quot; 18346</td>
//  <td class=xl33 style='border-top:none'>шт.</td>
//  <td class=xl32 style='border-top:none;border-left:none' x:num>1</td>
//  <td class=xl34 style='border-top:none;border-left:none' x:num="3.6">3,600</td>
//  <td class=xl34 style='border-top:none;border-left:none' x:num="3.6">3,600</td>
// </tr>
////-->

 <?php
$txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Tovar`, `Tovar`.`ed_izm`,  `DocTab`.`Cena`,"
    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$iddoc." AND `DocTab`.`Kvo` > 0 )\n"
    . "ORDER BY `DocTab`.`id` ASC ";


$sql = mysql_query($txt_sql, $db);
$sum_itog=0;  $sum_sum=0; $skidka=0;
while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {
        $nom_str = $srt_arr['nomstr'];
	$skidka = $srt_arr['Skidka'];
	$cena = sprintf('%.2f', $srt_arr['Cena']);
        $sum = sprintf("%.2f", $cena * $srt_arr['Kvo']);
        $it = sprintf("%.2f", $srt_arr['Cena'] *  $srt_arr['Kvo']  * (1 - $srt_arr['Skidka']/100  ) );
	$sum_itog = $sum_itog +   $srt_arr['Cena'] *  $srt_arr['Kvo']  * (1 - $srt_arr['Skidka']/100  );
	$sum_sum = $sum_sum + $sum;
        $ed = $srt_arr['ed_izm'];
        if($ed=='')$ed='шт.';

		echo "<tr class=xl36 height=17 style='height:12.75pt'>
			 <td height=17 class=xl32 style='height:12.75pt;border-top:none' x:num>$nom_str</td>
			 <td colspan=3 class=xl46 width=263 style='border-left:none;width:198pt'>".$srt_arr['Tovar']."</td>
			 <td class=xl33 style='border-top:none'>$ed</td>
			 <td class=xl32 style='border-top:none;border-left:none' x:num>".$srt_arr['Kvo']."</td>
			 <td class=xl34 style='border-top:none;border-left:none' x:num>$cena</td>
			 <td class=xl34 style='border-top:none;border-left:none' x:num>$sum</td>
			 <td class=xl35></td>
			 <td class=xl32 style='border-top:none' x:num>$nom_str</td>
			 <td colspan=3 class=xl46 width=267 style='border-left:none;width:201pt'>".$srt_arr['Tovar']."</td>
			 <td class=xl33 style='border-top:none'>$ed</td>
			 <td class=xl32 style='border-top:none;border-left:none' x:num>".$srt_arr['Kvo']."</td>
			 <td class=xl34 style='border-top:none;border-left:none' x:num>$cena</td>
			 <td class=xl34 style='border-top:none;border-left:none' x:num>$sum</td>
			 </tr>";


    }
    $sum_itog  = sprintf("%.2f",$sum_itog);
    $sum_sum = sprintf("%.2f",$sum_sum);

?>


 <tr height=19 style='height:14.25pt'>
  <td colspan=8 class=xl37 >
   	<?php
	 echo 'Всього:&nbsp;' . $sum_sum . '&nbsp;грн.&nbsp;&nbsp;';
	 if($skidka != 0 ) {
	 	echo '&nbsp;&nbsp;знижка:&nbsp;&nbsp;' . $skidka . '%&nbsp;&nbsp; до сплати:&nbsp;' . $sum_itog . '&nbsp;грн.';
	 }
	?>
  </td>

  <td colspan=9 class=xl37 >
   	<?php
	 echo 'Всього:&nbsp;' . $sum_sum . '&nbsp;грн.&nbsp;&nbsp;';
	 if($skidka != 0 ) {
	 	echo '&nbsp;&nbsp;знижка:&nbsp;&nbsp;' . $skidka . '%&nbsp;&nbsp; до сплати:&nbsp;' . $sum_itog . '&nbsp;грн.';
	 }
	?>
  </td>
 </tr>
 <tr height=21 style='height:15.75pt'>
  <td height=21 class=xl39 colspan=2 style='height:15.75pt;mso-ignore:colspan'>Рахунок
  склав(ла):</td>
  <td class=xl40>&nbsp;</td>
  <td colspan=7 style='mso-ignore:colspan'></td>
  <td class=xl39 colspan=2 style='mso-ignore:colspan'>Рахунок склав(ла):</td>
  <td class=xl40>&nbsp;</td>
  <td colspan=4 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl41 colspan=7 style='height:12.75pt;mso-ignore:colspan'>Рахунок
  необхiдно сплати протягом 3-х днiв. У разi невиконання, фiрма</td>
  <td colspan=6 class=xl23 style='mso-ignore:colspan'></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td class=xl42></td>
 </tr>
 <tr height=17 style='mso-height-source:userset;height:12.75pt'>
  <td height=17 class=xl41 colspan=7 style='height:12.75pt;mso-ignore:colspan'>має
  право змiнити цiну на товар i не гарантує його наявнiсть на складi<span
  style='mso-spacerun:yes'> </span></td>
  <td colspan=6 class=xl23 style='mso-ignore:colspan'></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td class=xl42><span
  style='mso-spacerun:yes'> </span></td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=29 style='width:22pt'></td>
  <td width=98 style='width:74pt'></td>
  <td width=83 style='width:62pt'></td>
  <td width=82 style='width:62pt'></td>
  <td width=31 style='width:23pt'></td>
  <td width=46 style='width:35pt'></td>
  <td width=70 style='width:53pt'></td>
  <td width=68 style='width:51pt'></td>
  <td width=55 style='width:41pt'></td>
  <td width=29 style='width:22pt'></td>
  <td width=62 style='width:47pt'></td>
  <td width=62 style='width:47pt'></td>
  <td width=143 style='width:107pt'></td>
  <td width=30 style='width:23pt'></td>
  <td width=43 style='width:32pt'></td>
  <td width=63 style='width:47pt'></td>
  <td width=62 style='width:47pt'></td>
 </tr>
 <![endif]>
</table>

</body>

</html>

