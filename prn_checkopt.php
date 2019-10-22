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
<title>Печать накладная</title>
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
<style id="ПЕЧАТЬ_28008_Styles">
<!--table
	{mso-displayed-decimal-separator:"\,";
	mso-displayed-thousand-separator:" ";}
.xl1528008
	{padding-top:1px;
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
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl1728008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl1828008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl1928008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
.xl2028008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
.xl2128008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:top;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl2228008
	{padding-top:1px;
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
	vertical-align:top;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl2328008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:12.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:left;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
.xl2428008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:left;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
.xl2528008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl2628008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:14.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl2728008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:center;
	vertical-align:middle;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
.xl2828008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:center-across;
	vertical-align:middle;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
.xl2928008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:0;
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl3028008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:left;
	vertical-align:top;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
.xl3128008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:center;
	vertical-align:top;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl3228008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:"0\.000";
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl3328008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl3428008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:right;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl3528008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:Fixed;
	text-align:right;
	vertical-align:bottom;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl3628008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:right;
	vertical-align:bottom;
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:none;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl3728008
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
-->
</style>
</head>

<body>
<!--[if !excel]>&nbsp;&nbsp;<![endif]-->
<!--Следующие сведения были подготовлены мастером публикации веб-страниц
Microsoft Office Excel.-->
<!--При повторной публикации этого документа из Excel все сведения между тегами
DIV будут заменены.-->
<!----------------------------->
<!--НАЧАЛО ФРАГМЕНТА ПУБЛИКАЦИИ МАСТЕРА ВЕБ-СТРАНИЦ EXCEL -->
<!----------------------------->

<div id="ПЕЧАТЬ_28008" align=center x:publishsource="Excel">

<table x:str border=0 cellpadding=0 cellspacing=0 width=790 style='border-collapse:
 collapse;table-layout:fixed;width:594pt'>
 <col width=29 style='mso-width-source:userset;mso-width-alt:1237;width:22pt'>
 <col width=359 style='mso-width-source:userset;mso-width-alt:15317;width:269pt'>
 <col width=42 style='mso-width-source:userset;mso-width-alt:1792;width:32pt'>
 <col width=46 style='mso-width-source:userset;mso-width-alt:1962;width:35pt'>
 <col width=57 style='mso-width-source:userset;mso-width-alt:2432;width:43pt'>
 <col width=62 style='mso-width-source:userset;mso-width-alt:2645;width:47pt'>
 <col width=69 style='mso-width-source:userset;mso-width-alt:2944;width:52pt'>
 <col width=71 style='mso-width-source:userset;mso-width-alt:3029;width:53pt'>
 <col width=55 style='mso-width-source:userset;mso-width-alt:2346;width:41pt'>
 <tr height=25 style='mso-height-source:userset;height:18.75pt'>
  <td height=25 class=xl1528008 width=29 style='height:18.75pt;width:22pt'></td>
  <td colspan=7 class=xl2628008 width=706 style='width:531pt'> <?php echo $firm ?> <span style='mso-spacerun:yes'> </span></td>
  <td class=xl1528008 width=55 style='width:41pt'></td>
 </tr>
 <tr height=24 style='mso-height-source:userset;height:18.0pt'>
  <td height=24 class=xl1528008 style='height:18.0pt'></td>
  <td colspan=7 class=xl2328008 width=706 style='width:531pt'>
      <?php echo 'Товарний  чек №_' .  $s_arrHD['nomDoc'] .  '   від: '. datesql_to_str( $s_arrHD['DataDoc']) ; ?>
  </td>
  <td class=xl1528008></td>
 </tr>
 <tr height=24 style='mso-height-source:userset;height:18.0pt'>
  <td height=24 class=xl1528008 style='height:18.0pt'></td>
  <td colspan=7 class=xl2428008 width=706 style='width:531pt'>Обрана система оподаткування: єдиний податок</td>
  <td class=xl1528008></td>
 </tr>
 <tr height=16 style='height:12.0pt'>
  <td height=16 class=xl1528008 style='height:12.0pt'></td>
  <td colspan=7 class=xl2528008><?php echo 'тел.:' . $tel;  ?></td>
  <td class=xl1528008></td>
 </tr>
 <tr height=16 style='mso-height-source:userset;height:12.0pt'>
  <td height=16 class=xl1728008 style='height:12.0pt'></td>
  <td colspan=7 class=xl2428008 width=706 style='width:531pt'><?php echo $nalog_data;  ?> </td>
  <td class=xl1728008></td>
 </tr>
 <tr height=16 style='height:12.0pt'>
  <td height=16 class=xl1728008 style='height:12.0pt'></td>
  <td class=xl1728008></td>
  <td class=xl1728008></td>
  <td class=xl1728008></td>
  <td class=xl1728008></td>
  <td class=xl1728008></td>
  <td class=xl1728008></td>
  <td class=xl1728008></td>
  <td class=xl1728008></td>
 </tr>
 <tr class=xl2028008 height=28 style='mso-height-source:userset;height:21.0pt'>
  <td height=28 class=xl2728008 width=29 style='height:21.0pt;width:22pt'>№</td>
  <td class=xl2828008 align=center width=359 style='border-left:none;
  width:269pt'>Найменування товару</td>
  <td class=xl2728008 width=42 style='width:32pt'>Од.</td>
  <td class=xl2728008 width=46 style='border-left:none;width:35pt'>К-ть</td>
  <td class=xl2728008 width=57 style='border-left:none;width:43pt'>Ціна</td>
  <td class=xl2728008 width=62 style='border-left:none;width:47pt'>Ціна опт</td>
  <td class=xl2728008 width=69 style='border-left:none;width:52pt'>Сума</td>
  <td class=xl2728008 width=71 style='border-left:none;width:53pt'>Сума опт</td>
  <td class=xl1928008 width=55 style='width:41pt'></td>
 </tr>
 
<!--//     
 <tr class=xl2228008 height=17 style='mso-height-source:userset;height:12.75pt'>
  <td height=17 class=xl2928008 style='height:12.75pt;border-top:none' x:num>1</td>
  <td class=xl3028008 width=359 style='border-top:none;border-left:none; width:269pt'>Скоросшив. пл. А4 &quot;Economix&quot; E31509-12 фиол.<span  style='mso-spacerun:yes'>  </span>**</td>
  <td class=xl3128008 style='border-top:none'>штука</td>
  <td class=xl2928008 style='border-top:none;border-left:none' x:num>40</td>
  <td class=xl3228008 style='border-top:none;border-left:none' x:num="1.5">1,500</td>
  <td class=xl3228008 style='border-top:none;border-left:none' x:num="1.5">1,500</td>
  <td class=xl3228008 style='border-top:none;border-left:none' x:num="60">60,000</td>
  <td class=xl3228008 style='border-top:none;border-left:none' x:num="60">60,000</td>
  <td class=xl2128008></td>
 </tr>
 </tr>//-->



 <?php
 // выводим данные продаж в таблицу


$txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Tovar`, `Tovar`.`ed_izm`,
    `Tovar`.`Price` as Cena  ,  `DocTab`.`Cena` as CenaOpt ,"
    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$iddoc." AND `DocTab`.`Kvo` > 0 )\n"
    . "ORDER BY `DocTab`.`id` ASC ";



$sql = mysql_query($txt_sql, $db);
$sum_itog=0;  $sum_sum=0; $skidka=0;
$sum_itog_opt=0;  $sum_sum_opt=0;
while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {

  $sum     = sprintf("%.2f", $srt_arr['Kvo'] *  $srt_arr['Cena']);
  $sum_opt = sprintf("%.2f", $srt_arr['Kvo'] *  $srt_arr['CenaOpt']);
  $it  = sprintf("%.2f", $srt_arr['Kvo'] *  $srt_arr['Cena']  * (1 - $srt_arr['Skidka']/100  ) );
  $it_opt  = sprintf("%.2f", $srt_arr['Kvo'] *  $srt_arr['CenaOpt']  * (1 - $srt_arr['Skidka']/100  ) );
  $sum_itog = $sum_itog +   $srt_arr['Kvo'] *  $srt_arr['Cena']  * (1 - $srt_arr['Skidka']/100  );
  $sum_itog_opt = $sum_itog_opt +   $srt_arr['Kvo'] *  $srt_arr['CenaOpt']  * (1 - $srt_arr['Skidka']/100  );
  $sum_sum = $sum_sum + $sum;
  $sum_sum_opt = $sum_sum_opt + $sum_opt;
  $skidka = round( skidkadoc($iddoc) ,2) ; //$srt_arr['Skidka'];
  $cena = sprintf('%.2f', $srt_arr['Cena']);
  $cena_opt = sprintf('%.2f', $srt_arr['CenaOpt']);
  
  /*
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

*/
echo "
    <tr class=xl2228008 height=17 style='mso-height-source:userset;height:12.75pt'>
     <td height=17 class=xl2928008 style='height:12.75pt;border-top:none' x:num> " . $srt_arr['nomstr']."</td>
     <td class=xl3028008 width=359 style='border-top:none;border-left:none; width:269pt'>" .$srt_arr['Tovar']."</td>
     <td class=xl3128008 style='border-top:none'>" .$srt_arr['ed_izm']."</td>
     <td class=xl2928008 style='border-top:none;border-left:none' >" .$srt_arr['Kvo']."</td>
     <td class=xl3228008 style='border-top:none;border-left:none' >$cena </td>
     <td class=xl3228008 style='border-top:none;border-left:none' >$cena_opt</td>
     <td class=xl3228008 style='border-top:none;border-left:none' >$sum</td>
     <td class=xl3228008 style='border-top:none;border-left:none' >$sum_opt</td>
     <td class=xl2128008></td>
    </tr>
       
";

}
        // округляем до 10 копеек
    //$sum_itog = round($sum_itog, 1);
    //$sum_sum  = round($sum_sum, 1);
    //$sum_itog  = sprintf("%.2f",$sum_itog);
    $sum_sum = sprintf("%.2f",$sum_sum);
    $sum_sum_opt = sprintf("%.2f",$sum_sum_opt);

?>


<tr height=16 style='height:12.0pt'>
  <td height=16 class=xl3328008 style='height:12.0pt'></td>
  <td class=xl3328008></td>
  <td class=xl3328008></td>
  <td class=xl3328008></td>
  <td class=xl3428008></td>
  <td class=xl3628008 style='border-top:none' x:str="Всього: ">Всього:<span
  style='mso-spacerun:yes'> </span></td>
  <td class=xl3528008 style='border-top:none;border-left:none' ><?php echo $sum_sum;  ?></td>
  <td class=xl3528008 style='border-top:none;border-left:none' ><?php echo $sum_sum_opt;  ?></td>
  <td class=xl1828008></td>
 </tr>
 <tr height=16 style='height:12.0pt'>
  <td height=16 class=xl3328008 style='height:12.0pt'></td>
  <td class=xl3328008></td>
  <td class=xl3328008></td>
  <td class=xl3328008></td>
  <td class=xl3328008></td>
  <td class=xl3328008></td>
  <td class=xl3328008></td>
  <td class=xl3328008></td>
  <td class=xl1528008></td>
 </tr>
 <tr height=16 style='height:12.0pt'>
  <td height=16 class=xl3328008 style='height:12.0pt'></td>
  <td class=xl3728008>Видав(ла) ___________________________</td>
  <td class=xl3728008><span style='mso-spacerun:yes'> </span></td>
  <td colspan=5 class=xl3728008>Отримав(ла) _____________________________</td>
  <td class=xl1528008></td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=29 style='width:22pt'></td>
  <td width=359 style='width:269pt'></td>
  <td width=42 style='width:32pt'></td>
  <td width=46 style='width:35pt'></td>
  <td width=57 style='width:43pt'></td>
  <td width=62 style='width:47pt'></td>
  <td width=69 style='width:52pt'></td>
  <td width=71 style='width:53pt'></td>
  <td width=55 style='width:41pt'></td>
 </tr>
 <![endif]>
</table>

</div>

</body>

</html>
