<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");

$host = refine(@$_REQUEST["host"]);
$login = refine(@$_REQUEST["login"]);
$pwd = refine(@$_REQUEST["pwd"]);
$port = refine(@$_REQUEST["port"]);
$db = refine(@$_REQUEST["db"]);
$todo = refine(@$_REQUEST["todo"]);


if(!$todo || $todo=="connect")
{
?>
<form method="get" name="mainform" action="phprunner.php">
<input type="Hidden" name="todo" value="connect">
<table cellspacing="2" cellpadding="2" border="0">
<tr>
  <td nowrap>Server address</td>
  <td><input type="Text" name="host" value="<?php if(!$host) echo "localhost"; else echo htmlspecialchars($host);?>"></td>
</tr>
<tr>
  <td nowrap>Username</td>
  <td><input type="Text" name="login" value="<?php echo htmlspecialchars($login);?>"></td>
</tr>
<tr>
  <td nowrap>Password</td>
  <td><input type="Text" name="pwd" value="<?php echo htmlspecialchars($pwd);?>"></td>
</tr>
<tr>
  <td nowrap>Port (if not 3306)</td>
  <td><input type="Text" name="port" value="<?php echo htmlspecialchars($port);?>"></td>
</tr>
<tr>
  <td></td>
  <td><input type="button" value="Connect" onclick="mainform.submit();"></td>
</tr>
<?php
  if($todo=="connect")
  {
    if((integer)$port)
      $host=$host.":".(integer)$port;
    $conn=mysql_connect($host,$login,$pwd);
    if(!$conn)
    {
      echo mysql_error();
      exit();
    }
    
?>
<tr>
  <td nowrap>Database</td>
  <td><select name="db">
<?php
    $dblist=mysql_list_dbs($conn);
    while($row=mysql_fetch_array($dblist,MYSQL_ASSOC))
      echo "<option value=\"".htmlspecialchars($row["Database"])."\">".htmlspecialchars($row["Database"])."</option>";
?>  
  </select>
</tr>
<tr>
  <td></td>
  <td><input type="button" value="Show schema" onclick="mainform.todo.value='schema'; mainform.submit();"></td>
</tr>
<?php
  }
?>
</table>
</form>
<?php
  return;
}

if((integer)$port)
  $host=$host.":".(integer)$port;
$conn=mysql_connect($host,$login,$pwd);
if(!$conn)
{
  echo mysql_error();
  exit();
}

if($todo=="testconnect")
{
  echo "connected ok";
  return;
}
if($todo=="exec")
{
  if($db && !mysql_select_db($db,$conn))
  {
    echo mysql_error();
    return;
  }
  $res = mysql_query(refine($_REQUEST["sql"]),$conn);
  if(!$res)
    echo mysql_error();
  else
    echo "1";
  return;
}
header("Content-type: text/xml");
echo '<?xml version="1.0" standalone="yes" ?>';

if($todo=="schema")
{
  if(!mysql_select_db($db,$conn))
  {
    echo mysql_error();
    exit();
  }
  //  determine php version
  $phpversion=phpversion();
  //  determine mysql version
  $mysqlversion = "unknown";
  $res = mysql_query("SHOW VARIABLES LIKE 'version'",$conn) or showerror();
  if($row=mysql_fetch_array($res,MYSQL_ASSOC))
    $mysqlversion = $row["Value"];
?>
<phprunner phpversion="<?php echo htmlspecialchars($phpversion);?>" mysqlversion="<?php echo htmlspecialchars($mysqlversion);?>">
<tables>
<?php
    $tables=mysql_query("SHOW TABLES",$conn) or showerror();
    if(!$tables)
    {
      echo mysql_error();
      exit();
    }
    while($table=mysql_fetch_array($tables,MYSQL_NUM))
    {
?>
<table name="<?php echo htmlspecialchars($table[0]);?>">
<?php
      showtablefields($table[0]);
?>    
</table>
    <?php
    }
?>
</tables>
</phprunner>
<?php
}

else if($todo=="dbs")
{
  echo "<databases>";
  $dblist=mysql_list_dbs($conn);
  while($row=mysql_fetch_array($dblist,MYSQL_ASSOC))
    echo "<database name=\"".htmlspecialchars($row["Database"])."\" />";
  echo "</databases>";
}

else if($todo=="queryfields")
{
  mysql_select_db($db,$conn) or showerror();
  $sql = refine(@$_REQUEST["sql"]);
  if(!$sql)
    return;
  $sql.= " limit 0,0";
  $res = mysql_query($sql,$conn);
  echo "<fields>";
  for($i=0;$i<mysql_num_fields($res);$i++)
  {
    $flags=strtolower(mysql_field_flags($res,$i));
    $type=mysql_field_type($res,$i);
    if($type=="blob" && strpos($flags,"binary")===false)
      $type="text";
    echo "<field name=\"".htmlspecialchars(mysql_field_name($res,$i))."\" type=\"".htmlspecialchars($type)."\" size=\"".htmlspecialchars(mysql_field_len($res,$i))."\" ";
    if(!(strpos($flags,"auto_increment")===false))
      echo "auto_increment=\"auto_increment\" ";
    if(!(strpos($flags,"primary_key")===false))
      echo "key=\"PRI\" ";
    if(!(strpos($flags,"not_null")===false))
      echo "null=\"\" ";
    else
      echo "null=\"YES\" ";
    echo " />";
  }
  echo "</fields>";
}
else if($todo=="tables")
{
  mysql_select_db($db,$conn) or showerror();
  echo "<tables>";
  $tablist=mysql_list_tables($db,$conn);
  while($row=mysql_fetch_array($tablist,MYSQL_NUM))
    echo "<table name=\"".htmlspecialchars($row[0])."\" />";
  echo "</tables>";
}
else if($todo=="tablefields")
{
  $table = refine(@$_REQUEST["table"]);
  if(!$table)
    return;
  mysql_select_db($db,$conn) or showerror();
  showtablefields($table);
}
else if($todo=="queryvalues")
{
  mysql_select_db($db,$conn) or showerror();
  $sql = refine(@$_REQUEST["sql"]);
  if(!$sql)
    return;
  $sql.=" limit 0,200";
  $res = mysql_query($sql);
  if(mysql_num_fields($res)==1)
  {
    echo "<values>";
    while($row=mysql_fetch_array($res,MYSQL_NUM))
      echo "<value>".htmlspecialchars($row[0])."</value>";
    echo "</values>";
  }
  else
  {
    echo "<rows>\r\n";
    while($row=mysql_fetch_array($res,MYSQL_NUM))
    {
      echo "<row>";
      for($i=0;$i<mysql_num_fields($res);$i++)
        echo "<value>".htmlspecialchars($row[$i])."</value>";
      echo "</row>\r\n";
    }
    echo "</rows>";
  }
}
else if($todo=="queryvaluesraw")
{
  mysql_select_db($db,$conn) or showerror();
  $sql = refine(@$_REQUEST["sql"]);
  if(!$sql)
    return;
  $res = mysql_query($sql);
  if(!$res)
  {
    echo mysql_error();
    return;
  }

  echo "<rows>\r\n";

  echo "<row>\r\n";
  for($i=0;$i<mysql_num_fields($res);$i++)
    echo "<value>".htmlspecialchars(mysql_field_name($res,$i))."</value>\r\n";
  echo "</row>\r\n";

  while($row=mysql_fetch_array($res,MYSQL_NUM))
  {
    echo "<row>\r\n";
    for($i=0;$i<mysql_num_fields($res);$i++)
    {
      echo "<value>".htmlspecialchars($row[$i])."</value>\r\n";
    }
    echo "</row>\r\n";
  }
  echo "</rows>\r\n";
}
else if($todo=="queryvaluesstr")
{
  mysql_select_db($db,$conn) or showerror();
  $sql = refine(@$_REQUEST["sql"]);
  if(!$sql)
    return;
  $res = mysql_query($sql);
  if(!$res)
  {
    echo mysql_error();
    return;
  }

  $binfields = array();
  for($i=0;$i<mysql_num_fields($res);$i++)
  {
    $flags=strtolower(mysql_field_flags($res,$i));
    if(strpos($flags,"binary")!==false)
      $binfields[]=$i;
  }

  echo "<rows>\r\n";

  echo "<row>\r\n";
  for($i=0;$i<mysql_num_fields($res);$i++)
    echo "<value>".htmlspecialchars(mysql_field_name($res,$i))."</value>\r\n";
  echo "</row>\r\n";

  while($row=mysql_fetch_array($res,MYSQL_NUM))
  {
    echo "<row>\r\n";
    for($i=0;$i<mysql_num_fields($res);$i++)
    {
      $ret=array_search($i,$binfields);
      if($ret===FALSE || $ret===NULL)
        echo "<value>".htmlspecialchars($row[$i])."</value>\r\n";
      else
       if (strlen($row[$i]) == 0)
        echo "<value>NULL</value>\r\n";
       else
        echo "<value>0x".bin2hex($row[$i])."</value>\r\n";
    }
    echo "</row>\r\n";
  }
  echo "</rows>\r\n";
}

function refine($str)
{
  if(get_magic_quotes_gpc())
    $ret=stripslashes($str);
  else
    $ret=$str;
  return html_special_decode($ret);
}

function html_special_decode($str)
{
  $ret=$str;
  $ret=str_replace("&gt;",">",$ret);
  $ret=str_replace("&lt;","<",$ret);
  $ret=str_replace("&quot;","\"",$ret);
  $ret=str_replace("&#039;","'",$ret);
  $ret=str_replace("&amp;","&",$ret);
  return $ret;
}

function showtablefields($table)
{
  global $conn;
      echo "<fields>";
      $fields=mysql_query("SHOW fields FROM `".$table."`",$conn);
      while($field=mysql_fetch_array($fields,MYSQL_ASSOC))
      {
        $attr=array();
        $attr["name"]=$field["Field"];
        $type=$field["Type"];
//  remove type modifiers
        if(substr($type,0,4)=="tiny") $type=substr($type,4);
        else if(substr($type,0,5)=="small") $type=substr($type,5);
        else if(substr($type,0,6)=="medium")  $type=substr($type,6);
        else if(substr($type,0,3)=="big") $type=substr($type,3);
        else if(substr($type,0,4)=="long")  $type=substr($type,4);
        if(substr($type,0,4)=="enum")
        {
          $attr["values"]=substr($type,5,strlen($type)-6);
          $attr["type"]="enum";
        }
        else if(substr($type,0,3)=="set")
        {
          $attr["values"]=substr($type,4,strlen($type)-5);
          $attr["type"]="set";
        }
        else
        {
          if($pos=strpos($type," "))
            $type=substr($type,0,$pos);
//  parse field sizes
          if($pos=strpos($type,"("))
          {
            if($pos1=strpos($type,",",$pos))
            {
              $attr["size"]=(integer)substr($type,$pos+1,$pos1-$pos-1);
              $attr["scale"]=(integer)substr($type,$pos1+1,strlen($type)-$pos1-2);
            }
            else
            {
              $attr["size"]=(integer)substr($type,$pos+1,strlen($type)-$pos-2);
              $attr["scale"]=0;
            }
            $type=substr($type,0,$pos);
          }
          $attr["type"]=$type;
        }
        if(!(strpos($field["Extra"],"auto_increment")===false))
          $attr["auto_increment"]="auto_increment";
        $attr["key"]=$field["Key"];
        $attr["default"]=$field["Default"];
        $attr["null"]=$field["Null"];

        echo '<field ';
        foreach($attr as $key=>$value)
          echo $key.'="'.htmlspecialchars($value).'" ';
        echo '/>';
      }
      echo "</fields>";
}

function showerror()
{
  echo mysql_error();
  exit();
}
?>