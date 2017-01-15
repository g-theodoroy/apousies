<?php
isset ($_POST["host"]) ? $host = $_POST["host"] : $host = '';
isset ($_POST["username"]) ? $username = $_POST["username"] : $username = '';
isset ($_POST["password"]) ? $password = $_POST["password"] : $password = '';
isset ($_POST["database"]) ? $database = $_POST["database"] : $database = '';
isset ($_POST["replacedatabase"]) ? $replacedatabase = $_POST["replacedatabase"] : $replacedatabase = 0;

isset ($_POST["mailserver"]) ? $mailserver = $_POST["mailserver"] : $mailserver = '';
isset ($_POST["mailport"]) ? $mailport = $_POST["mailport"] : $mailport = '';
isset ($_POST["mailusername"]) ? $mailusername = $_POST["mailusername"] : $mailusername = '';
isset ($_POST["mailpassword"]) ? $mailpassword = $_POST["mailpassword"] : $mailpassword = '';
isset ($_POST["secure"]) ? $secure = $_POST["secure"] : $secure = '';
isset ($_POST["from"]) ? $from = $_POST["from"] : $from = '';
isset ($_POST["fromname"]) ? $fromname = $_POST["fromname"] : $fromname = '';

//echo "$host - $username - $password - $database<hr>";

$errortext = null;

//έλεγχος αν πήρα όλα τα στοιχεία
if (!$host || !$username || !$password || !$database) $errortext="Ελλιπή στοιχεία. Δεν μπορώ να συνεχίσω!";

if (!$mailserver || !$mailport || !$mailusername || !$mailpassword || !$from || !$fromname) $errortext="Ελλιπή στοιχεία. Δεν μπορώ να συνεχίσω!";

//echo "1 - $errortext<hr>";


// 'ελεγχος της σύνδεσης
if (!$errortext){
	if (! mysql_connect($host,$username,$password))$errortext="Αδύνατη η σύνδεση στη mysql. Βεβαιωθείτε ότι έχετε τα σωστά στοιχεία!";
}

//echo "2 - $errortext<hr>";


//δημιουργία βάσης δεδομένων
if (!$errortext){

	mysql_query("SET character_set_connection=utf8");
	mysql_query("SET character_set_client=utf8"); 
	mysql_query("set character set 'utf8'");
	mysql_query("SET NAMES 'utf8'");
        
        if($replacedatabase==1){
            $query = "DROP DATABASE `$database` ;";
            $result = mysql_query($query);
        }

	$query = "CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
	$result = mysql_query($query);
	if (! $result) $errortext="Αδύνατη η δημιουργία της βάσης δεδομένων. Βεβαιωθείτε ότι δεν υπάρχει ήδη βάση δεδομένων με το όνομα αυτό!";
}

//echo "3 - $errortext<hr>";

//επιλογή της βάσης
if (!$errortext){
	if (!mysql_select_db($database))$errortext="Αδύνατη η επιλογή της βάσης δεδομένων";
}

//echo "4 - $errortext<hr>";


//δημιουργία πινάκων
if (!$errortext){
	$queryarray= file("apousies_db.sql");
	for ($i=0;$i<count($queryarray);$i++){
		$result = mysql_query($queryarray[$i]);
		if(! $result) $errortext="Σφάλμα στην δημιουργία των πινάκων στη βάση δεδομένων $database: " . mysql_error() .  "!";
	}
}

//echo "5 - $errortext<hr>";



//δημιουργία mail.inc.php
if (!$errortext){

$myFile = "../includes/mailer.inc.php";
$fh = fopen($myFile, 'w') or die("Δεν μπορώ να αποθηκεύσω τα στοιχεία!");

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
$str = "<?php

class MyPHPMailer extends PHPMailer {

    // Set default variables for all new objects
    public \$From = '$from';
    public \$FromName = '$fromname';
    public \$Mailer = 'sendmail';
    public \$CharSet = 'UTF-8';
    public \$WordWrap = 120;

}

?>
";
} else {
$str = "<?php

class MyPHPMailer extends PHPMailer {

    // Set default variables for all new objects
    public \$From = '$from';
    public \$FromName = '$fromname';
    public \$Mailer = 'smtp';
    public \$Host = '$mailserver';
    public \$Port = $mailport;
    public \$SMTPAuth = true;
    public \$SMTPSecure = '$secure';
    public \$Username = '$mailusername';
    public \$Password = '$mailpassword';
    public \$CharSet = 'UTF-8';
    public \$WordWrap = 120;

}

?>
";
}



fwrite($fh, $str);

fclose($fh);

}
//δημιουργία dbinfo.inc.php
if (!$errortext){

$myFile = "../includes/dbinfo.inc.php";
$fh = fopen($myFile, 'w') or die("Δεν μπορώ να αποθηκεύσω τα στοιχεία!");

$str = "<?php
\$host=\"$host\";
\$username=\"$username\";
\$password=\"$password\";
\$database=\"$database\";

\$link = mysqli_connect ( \$host, \$username, \$password, \$database );

if (! \$link) {
	echo \"Error: Unable to connect to MySQL.\" . PHP_EOL;
	echo \"Debugging errno: \" . mysqli_connect_errno () . PHP_EOL;
	echo \"Debugging error: \" . mysqli_connect_error () . PHP_EOL;
	exit ();
}

mysqli_query ( \$link, \"SET character_set_connection=utf8\" );
mysqli_query ( \$link, \"SET character_set_client=utf8\" );
mysqli_query ( \$link, \"SET character set 'utf8'\" );
mysqli_query ( \$link, \"SET NAMES 'utf8'\" );
?>
";

fwrite($fh, $str);

fclose($fh);

}


//echo "6 - $errortext<hr>";


mysql_close();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="el">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- Liquid Blueprint CSS -->
<link rel="stylesheet" href="../blueprint/reset.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="../blueprint/liquid.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="../blueprint/typography.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="../blueprint/fancy-type.css" type="text/css" media="screen, projection">
<!--[if IE]><link rel="stylesheet" href="../blueprint/lib/ie.css" type="text/css" media="screen, projection"><![endif]-->
	
  <title>ΕΓΚΑΤΑΣΤΑΣΗ ΔΙΑΧΕΙΡΙΣΗΣ ΑΠΟΥΣΙΩΝ</title>

<style type="text/css">
	.box { background-color:#fff}
	td { border-style:none;margin:0px;padding:0px;padding-right:25px;}
	h4.nomargin{ margin:0px;padding:0px;}
	h4.r{text-align:right;}
</style>

<script language="JavaScript">

</script>
</head>
<body >
<div class="container">
<div class="block" style="min-height : 700px;">
	<!-- HEADER -->
	<div class="block">
		<div class="column span-24">
			<div>
				<div align="left" class="column span-5 "><IMG src="../images/evolution.gif" height="70" align="left" border="0"></div> 
				<div class="column span-19 ">&nbsp;</div>

				<div class="column span-14 last" align="center">


<h1>Εγκατάσταση</h1>

			</div> 

			</div>
		</div>
	</div>

	<hr>

	<!-- END HEADER -->

	<div class="block">

		<!-- MENU -->
		<div class="column span-3 " >&nbsp;</div>


		<!-- CONTENT -->
		<div class="column span-18 last" >

			<!-- SELECT -->
			<div class="block">
				<div class="column span-24 last" >
					<h1 align="center" >Διαχείριση απουσιών</h1>
					<hr class="space">

<h3 >
<?php
if ($errortext) {
	echo "<strong><u>ΠΡΟΒΛΗΜΑ:</u></strong> " . $errortext;
}else{
	echo "Η εγκατάσταση ολοκληρώθηκε με επιτυχία. Μπορείτε πλέον να χρησιμοποιήσετε το πρόγραμμα.<br><br>Καλό θα ήταν για λόγους ασφαλείας να διαγράψετε το φάκελο ''install'' ή να περιορίσετε τη δυνατότητα των χρηστών να τον επισκέπτονται ελέγχοντας τα ''Δικαιώματα'' του φακέλου.";
}
?>
</h3>

 <p  align="center">
      	<button type="button" name="cancel" value="cancel" onclick="window.location='<?php  echo $errortext ?  "install_1.php" : "../populatedemo.php" ;?>'"><?php  echo $errortext ?  "ΕΠΙΣΤΡΟΦΗ" : "ΣΥΝΕΧΕΙΑ" ;?></button>
</p>


			</div>

				</div>
			</div>
		</div>
	</div>
</div>

<hr>
	<!-- FOOTER -->
	<div class="block">
		<div class="column span-4">
			<div align="left">
				&nbsp;
			</div>
		</div>
		<div class="column span-16">
			<div align="center">
				&nbsp;
			</div>
		</div>
		<div class="column span-4 last">
			<div align="right">
				<a href="mailto:g.theodoroygmail.com?subject=Διαχείριση Απουσιών" >GΘ@2017</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>

