<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");

if(@$_POST["a"]=="logout" || @$_GET["a"]=="logout")
{
	session_unset();
	setcookie("username","",time()-365*1440*60);
	setcookie("password","",time()-365*1440*60);
	header("Location: login.php");
	exit();
}

include('libs/xtempl.php');
$xt = new Xtempl();


$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessLogin"))
	BeforeProcessLogin($conn);


$myurl=@$_SESSION["MyURL"];
unset($_SESSION["MyURL"]);

$defaulturl="";
						$defaulturl="acadcomp_list.php";


$cUserName = "ets";
$cPassword = "netsys";


$message="";

$pUsername=postvalue("username");
$pPassword=postvalue("password");

$rememberbox_checked="";
$rememberbox_attrs = "name=\"remember_password\" value=\"1\"";
if(@$_COOKIE["username"] || @$_COOKIE["password"])
	$rememberbox_checked=" checked";

if (@$_POST["btnSubmit"] == "Login")
{
	if(@$_POST["remember_password"] == 1)
	{
		setcookie("username",$pUsername,time()+365*1440*60);
		setcookie("password",$pPassword,time()+365*1440*60);
		$rememberbox_checked=" checked";
	}
	else
	{
		setcookie("username","",time()-365*1440*60);
		setcookie("password","",time()-365*1440*60);
		$rememberbox_checked="";
	}
//		 username and password are hardcoded
	$retval=true;
	if(function_exists("BeforeLogin"))
		$retval=BeforeLogin($pUsername,$pPassword);

	if($retval && !strcmp($cPassword, $pPassword) && !strcmp($cUserName, $pUsername))
	{
		$_SESSION["UserID"] = $pUsername;
		$_SESSION["AccessLevel"] = ACCESS_LEVEL_USER;
		
		if(function_exists("AfterSuccessfulLogin"))
		{
			$dummy=array();
			AfterSuccessfulLogin($pUsername,$pPassword,$dummy);
		}
		
		
		if($myurl)
			header("Location: ".$myurl);
		else
			header("Location: ".$defaulturl);
		return;
	}
	else
	{
		if(function_exists("AfterUnsuccessfulLogin"))
			AfterUnsuccessfulLogin($pUsername,$pPassword);
		$message = "Invalid Login";
	}	
}

$xt->assign("rememberbox_attrs",$rememberbox_attrs.$rememberbox_checked);

$_SESSION["MyURL"]=$myurl;
if($myurl)
	$xt->assign("guestlink_attrs","href=\"".$myurl."\"");
else
	$xt->assign("guestlink_attrs","href=\"".$defaulturl."\"");


if(@$_POST["username"] || @$_GET["username"])
	$xt->assign("username_attrs","value=\"".htmlspecialchars($pUsername)."\"");
else
	$xt->assign("username_attrs","value=\"".htmlspecialchars(refine(@$_COOKIE["username"]))."\"");


$password_attrs="onkeydown=\"e=event; if(!e) e = window.event; if (e.keyCode != 13) return; e.cancel = true; e.cancelBubble=true; document.forms[0].submit(); return false;\"";
if(@$_POST["password"])
	$password_attrs.=" value=\"".htmlspecialchars($pPassword)."\"";
else
	$password_attrs.=" value=\"".htmlspecialchars(refine(@$_COOKIE["password"]))."\"";
$xt->assign("password_attrs",$password_attrs);

if(@$_GET["message"]=="expired")
	$message = "Your session has expired. Please login again.";


if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}

$body=array();
$body["begin"]="<form method=post action=\"login.php\" id=form1 name=form1>
		<input type=hidden name=btnSubmit value=\"Login\">";
$body["end"]="</form>
<script>
document.forms[0].elements['username'].focus();
</script>";
$xt->assignbyref("body",$body);

$templatefile="login.htm";
if(function_exists("BeforeShowLogin"))
	BeforeShowLogin($xt,$templatefile);

$xt->display($templatefile);
?>