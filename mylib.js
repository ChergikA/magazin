
  //**********************baba-jaga@i.ua**********************
function check_user(){

    top.footer.location = "footer.php"; // ���� ������ ���� � ��� ���� �� ���� ������ ��� ����

    var name_user = top.footer.name_user;

    // alert('=' + name_user + "=" );

    if( name_user == ""   ){
      //alert("glob_u ="+ txt_user );
      top.top_menu.location = "notUser.php";
      top.content.location  = "notUser.php";
        return false;
     }else return true; // � ������������� ��� � �������
//return false;
}

//**********************baba-jaga@i.ua**********************
// ��� ���������  ������ ����� ���������
// ������ �������� ��������� �� ����� ��� ������������� || vid_doc == '��������'
function changedoc(){
    var f = document.getElementById("sel_firm");
    var firm = f.options[f.selectedIndex].value;

    f = document.getElementById("sel_doc");
    var vid_doc = f.options[f.selectedIndex].value;

    if(vid_doc == '�������'){
        top.top_menu.location = "newdoc_menu_2.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }else if( vid_doc == '���' || vid_doc == '�������' || vid_doc == '����' ){
        top.top_menu.location = "newdoc_menu.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }else if( vid_doc == '�����������' || vid_doc == '������������' || vid_doc == '���������������' ){
        top.top_menu.location = "newdoc_menu_vzv_sklad.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }else if( vid_doc == '��������' ){
        top.top_menu.location = "newdoc_menu_3.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }else if( vid_doc == '�����������' ){
        top.top_menu.location = "newdoc_menu_prihod.php?id_firm=" + firm + "&viddoc=" + vid_doc ;
    }


}

//**********************baba-jaga@i.ua**********************
// ��������� ����� ���
function open_doc(){

    var f = document.getElementById("sel_firm");
    var firm = f.options[f.selectedIndex].value;

    f = document.getElementById("sel_doc");
    var vid_doc = f.options[f.selectedIndex].value;

    //|| vid_doc == '�������'
    if( vid_doc == '���' || vid_doc == '����'  ){
        var nameklient = new String( document.getElementById("nm_klient").value );

        if( nameklient.length == 0 ){
            alert("�������� �������");
            return false;
        }
        if( nameklient.indexOf("������������ �������") != -1 ) {
            alert("�� ������ ������");
            return false;
        }
        top.top_menu.location = "newdoc_menu.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == '�������' ){
        var nameklient = new String( document.getElementById("nm_klient").value );

        if( nameklient.length == 0 ){
            alert("�������� �������");
            return false;
        }
        if( nameklient.indexOf("������������ �������") != -1 ) {
            alert("�� ������ ������");
            return false;
        }

    }else if( vid_doc == '�����������' ){
        top.top_menu.location = "newdoc_menu_vzv_sklad.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == '������������' ){
        top.top_menu.location = "newdoc_menu_vzv_sklad.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == '���������������' ){
        top.top_menu.location = "newdoc_menu_vzv_sklad.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == '�����������' ){
        top.top_menu.location = "newdoc_menu_prihod.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
    }else if( vid_doc == '��������' ){
        var strdoc =  "newdoc_menu_3.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" + vid_doc;
        var fragment = new String( document.getElementById("fragment").value );
        if( fragment.length > 0 ) strdoc = strdoc + "&fragment=" + fragment;
        var txtonly = new String( document.getElementById("txt_only").value );
        if( txtonly ==  'checked' ) strdoc = strdoc + "&txtonly=" + txtonly;
        //alert('strdoc =' + strdoc );

        top.top_menu.location = strdoc;

    }else if( vid_doc == '�������' ){
        var nameklient = new String( document.getElementById("nm_klient").value );
        //alert('=' + nameklient );
        //if( nameklient.length == 0 ){
        //    alert("�������� ��������� �������� �� �������� ����� ��������� �����");
        //    return false;
        //}

        top.top_menu.location = "newdoc_menu_2.php?newdoc=newdoc&id_firm=" + firm + "&viddoc=" +
        vid_doc + "&idprihodnik=" + nameklient ;
    }

}

//**********************baba-jaga@i.ua**********************
//��������� ����������� ������ ��� �������������� ������������� ����
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
//������ ��������� ���� �������������� ������ ���� ��� �������
function hide_bar() {
    document.getElementById('h_kod').focus();
    document.getElementById("win").style.visibility="hidden";
    document.getElementById("winpometka").style.visibility="hidden";
}

//*********************baba-jaga@i.ua**********************
// ���� �������������� ����������
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
// ������ ������ �������
function closeWin(){
    window.close();
}

//**********************baba-jaga@i.ua**********************
// �� ���� ��������� ��� �������
function plays() {
    var snd = new Audio("images/ok.wav");

    snd.preload = "auto";

    snd.load();
    snd.play();

}


