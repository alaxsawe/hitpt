<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn(); //added in 20120408
//if (get_user_class() < UC_ADMINISTRATOR)
//stderr("Error", "Permission denied.");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
$userid = trim($_POST["userid"]);
$password = $_POST["password"]; 

//added in 20120408
if (get_user_class() < UC_ADMINISTRATOR && $userid != $CURUSER["id"])
stderr("错误", "Permission denied.");
if (get_user_class() < UC_ADMINISTRATOR && ($CURUSER["passhash"] != md5($CURUSER["secret"] . $password . $CURUSER["secret"])))
stderr("错误", "密码不正确！ 清影PT为保障用户的账户安全，删除自己账户时请正确输入您的密码！");

if (!$userid)
  stderr("Error", "Please fill out the form correctly.");

$res = sql_query("SELECT * FROM users WHERE id=" . sqlesc($userid)) or sqlerr();
if (mysql_num_rows($res) != 1)
  stderr("Error", "Bad user id or password. Please verify that all entered information is correct.");
$arr = mysql_fetch_assoc($res);

$id = $arr['id'];
$name = $arr['username'];
$res = sql_query("DELETE FROM users WHERE id=$id") or sqlerr();
if (mysql_affected_rows() != 1)
  stderr("Error", "Unable to delete the account.");
stderr("Success", "The account <b>".htmlspecialchars($name)."</b> was deleted.",false);
}
stdhead("Delete account");
?>
<h1>Delete account</h1>
<table border=1 cellspacing=0 cellpadding=5>
<form method=post action=delacctadmin.php>
<tr><td class=rowhead>User name</td><td><input size=40 name=userid></td></tr>

<tr><td colspan=2><input type=submit class=btn value='Delete'></td></tr>
</form>
</table>
<?php
stdfoot();
