<?php
// в какое поле возвращать результат
if ( isset($_GET['btn']) ) {
    $pole =  $_GET['btn'];
}
?>


<html>
<head>
<title>календарь</title>
<meta name="description" content="">
<meta name="keywords" content="">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<!-- <meta http-equiv="pragma" content= "no-cache"> -->
<link rel="stylesheet" type="text/css" href="src/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="src/css/steel/steel.css" />
<script src="src/js/jscal2.js"></script>
<script src="src/js/lang/ru.js"></script>
<link rel="stylesheet" type="text/css" href="src/css/border-radius.css" />

</head>


<script type="text/javascript">

    var txt; //

    function on_load(){

       var w = window.opener; /* возвращает ссылку на окно, которое вызвало это окно */
       var pole = <?php echo "'" .  $pole . "'"  ?> ;
       txt = w.document.getElementById(pole);

        document.getElementById("mydata").value=txt.value ;
    }

    function redata(){ // этот инпут - для отладки
        var newdata = document.getElementById("mydata").value;
        txt.value = newdata;
    }

</script>

<body onload="javascript:on_load()"  >

    <input type="hidden" size="7" id="mydata" name="mydata" style="text-align: center"  onchange="javascript:redata()" >

<div id="calendar-container" style="text-align: left" >  </div>

</body>
</html>


<script type="text/javascript">


    function updateFields(cal) {

        var date = cal.selection.get();
        if (date) {
            date = Calendar.intToDate(date);
            txt.value = Calendar.printDate(date, "%d.%m.%y");
        }
        window.close();
    //    cal.hide();

    };


   var cal = Calendar.setup({
      cont: "calendar-container",
      selectionType: Calendar.SEL_MULTIPLE,
      onSelect: updateFields,
      showTime: false,
      firstDay : 1 ,
      //trigger    : "mydata",
      inputField : "mydata",
      titleFormat: "%B %Y"
   })
    // cal.manageFields( "mydata", "mydata", "%d-%m-%Y");


</script>



