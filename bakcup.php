<?php

//$db = mysql_connect('Localhost','root','111') OR DIE("��� ����������� � SQL");
/* ������� ���� ������. ���� ���������� ������ - ������� �� */
//mysql_select_db("Vivat",$db) or die(mysql_error());

//Deprecated: Function ereg_replace() is deprecated in /home/san/webServer/Frames/database_bakcup/bakcup.php on line 43 Deprecated: Function ereg_replace() is dep

//���� ������� addslashes/ereg_replace ���� �������� �� mysql_escape_string ��� ����� mysql_real_escape_string.

//backup_database_tables('Localhost','root','111','Vivat', '*');
backup_database_tables( '*');
// backup the db function
function backup_database_tables($tables){
    global $db;


    // echo '������';
        //$link = mysql_connect($host,$user,$pass);
        //mysql_select_db($name,$link);
        $return='';
        //get all of the tables
        if($tables == '*')
        {
                $tables = array();
                $result = mysql_query('SHOW TABLES');
                while($row = mysql_fetch_row($result))
                {
                        $tables[] = $row[0];
                }
        }
        else
        {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
        }
        //cycle through each table and format the data
        foreach($tables as $table)
        {
                $result = mysql_query('SELECT * FROM '.$table);
                $num_fields = mysql_num_fields($result);
                $return.= 'DROP TABLE IF EXISTS '.$table.';';
                $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
                $return.= "\n\n".$row2[1].";\n\n";
                for ($i = 0; $i < $num_fields; $i++)
                {
                        while($row = mysql_fetch_row($result))
                        {
                                $return.= 'INSERT INTO '.$table.' VALUES(';
                                for($j=0; $j<$num_fields; $j++)
                                {
                                        $row[$j] = addslashes($row[$j]);
                                        //$row[$j] = ereg_replace("\n","\\n",$row[$j]);
                                         $row[$j] = mysql_real_escape_string($row[$j]);

                                        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                                        if ($j<($num_fields-1)) { $return.= ','; }
                                }
                                $return.= ");\n";
                        }
                }
                $return.="\n\n\n";
        }
        //save the file
        //$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
        $handle = fopen('data/db-backup.sql','w+');
        fwrite($handle,$return);
        fclose($handle);
        chmod('data/db-backup.sql', 0777);
}
?>
