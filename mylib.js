
  //**********************baba-jaga@i.ua**********************
function check_user(){

    top.footer.location = "footer.php"; // верх всегда один и тот файл из него сессии Имя Юзер

    var name_user = top.footer.name_user;

    // alert('=' + name_user + "=" );

    if( name_user == ""   ){
      //alert("glob_u ="+ txt_user );
      top.top_menu.location = "notUser.php";
      top.content.location  = "notUser.php";
        return false;
     }else return true; // с пользователем все в порядке
//return false;
}

//**********************baba-jaga@i.ua**********************
// при изменении  списка видов документа
// меняем видимост элементов на форме при необходимости || vid_doc == 'Переучет'
function changedoc(){
    var f = document.getElementById("sel_firm");
    var firm = f.options[f.selectedIndex].value;

    f = document.getElementById("sel_doc");
    var vid_doc = f.options[f.selectedIndex].value;

    if(vid_doc == 'Приемка'){
        top.top_menu.location = "newdoc_menu_2.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }else if( vid_doc == 'Чек' || vid_doc == 'Возврат' || vid_doc == 'Счет' ){
        top.top_menu.location = "newdoc_menu.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }else if( vid_doc == 'Перемещение' || vid_doc == 'Комплектация' || vid_doc == 'Раскомплектация' ){
        top.top_menu.location = "newdoc_menu_vzv_sklad.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }else if( vid_doc == 'Переучет' ){
        top.top_menu.location = "newdoc_menu_3.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }else if( vid_doc == 'Поступление' ){
        top.top_menu.location = "newdoc_menu_prihod.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }


}

//**********************baba-jaga@i.ua**********************
// открываем новый док
function open_doc(){

    var f = document.getElementById("sel_firm");
    var firm = f.options[f.selectedIndex].value;

    f = document.getElementById("sel_doc");
    var vid_doc = f.options[f.selectedIndex].value;

    //|| vid_doc == 'Возврат'
    if( vid_doc == 'Чек' || vid_doc == 'Счет'  ){
        var nameklient = new String( document.getElementById("nm_klient").value );

        if( nameklient.length == 0 ){
            alert("Выберите клиента");
            return false;
        }
        if( nameklient.indexOf("Наименование клиента") != -1 ) {
            alert("Не выбран клиент");
            return false;
        }
        top.top_menu.location = "newdoc_menu.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == 'Возврат' ){
        var nameklient = new String( document.getElementById("nm_klient").value );

        if( nameklient.length == 0 ){
            alert("Выберите клиента");
            return false;
        }
        if( nameklient.indexOf("Наименование клиента") != -1 ) {
            alert("Не выбран клиент");
            return false;
        }

    }else if( vid_doc == 'Перемещение' ){
        top.top_menu.location = "newdoc_menu_vzv_sklad.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == 'Комплектация' ){
        top.top_menu.location = "newdoc_menu_vzv_sklad.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == 'Раскомплектация' ){
        top.top_menu.location = "newdoc_menu_vzv_sklad.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == 'Поступление' ){
        top.top_menu.location = "newdoc_menu_prihod.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == 'Переучет' ){
        var strdoc =  "newdoc_menu_3.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
        var fragment = new String( document.getElementById("fragment").value );
        if( fragment.length > 0 ) strdoc = strdoc + "&fragment=" + fragment;
        var txtonly = new String( document.getElementById("txt_only").value );
        if( txtonly ==  'checked' ) strdoc = strdoc + "&txtonly=" + txtonly;
        //alert('strdoc =' + strdoc );

        top.top_menu.location = strdoc;

    }else if( vid_doc == 'Приемка' ){
        var nameklient = new String( document.getElementById("nm_klient").value );
        //alert('=' + nameklient );
        //if( nameklient.length == 0 ){
        //    alert("Выберите приходный документ по которому будем принимать товар");
        //    return false;
        //}

        top.top_menu.location = "newdoc_menu_2.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" +
        vid_doc + "&idprihodnik=" + nameklient ;
    }

}

//**********************baba-jaga@i.ua**********************
//открываем всплывающее окошко для редактирования рекомендуемой цены
function show_bar(event, idstr, oldskidka) {

    var MouseX = event.clientX + document.body.scrollLeft;
    var MouseY = event.clientY + document.body.scrollTop;
    var obj = document.getElementById("win");

    obj.style.top = MouseY - 40 + 'px' ;
    obj.style.left = MouseX -160 + 'px' ;
    obj.style.visibility = "visible";
    document.getElementById('_edit').value  = oldskidka;
    document.getElementById('_idstr').value  = idstr;

    document.getElementById('_edit').focus();


}

//**********************baba-jaga@i.ua**********************
//просто закрываем окно редактирования скидки цены или пометки
function hide_bar() {
    document.getElementById('h_kod').focus();
    document.getElementById("win").style.visibility="hidden";
    document.getElementById("winpometka").style.visibility="hidden";
}

//*********************baba-jaga@i.ua**********************
// окно редактирования примечания
function show_editpometka(event, idstr ,txtpometka){
    var MouseX = event.clientX + document.body.scrollLeft;
    var MouseY = event.clientY + document.body.scrollTop;
    var obj = document.getElementById("winpometka");

    var pox = -620;
    if(idstr == '' ) pox=20;

    obj.style.width =  '600px' ;
    obj.style.top = MouseY -60 + 'px' ;
    obj.style.left = MouseX + pox + 'px' ;
    obj.style.visibility = "visible";

    document.getElementById('_idstrp').value  = idstr;

    document.getElementById('_editpometka').style.width =  '500px' ;
    document.getElementById('_editpometka').value  = txtpometka ;
    document.getElementById('_editpometka').focus();
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


