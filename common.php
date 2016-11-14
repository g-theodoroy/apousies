<?php
//error_reporting ( E_ALL & ~ E_STRICT & ~ E_NOTICE );

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

session_start ();

date_default_timezone_set ( 'Europe/Athens' );

$classes_prefix = 'Classes/';

$style_prefix = '';
$_SESSION ['style_prefix'] = $style_prefix;

$images_prefix = 'images/';
$_SESSION ['images_prefix'] = $images_prefix;

// καθοριζω αν θα επιτρέπεται η καταχώρηση νέων χρηστών
// κάντε σχόλιο τη μια επιλογή με // μπροστά
$_SESSION ['allowregister'] = True;
//$_SESSION['allowregister'] = False;

// εδω καθορίζονται τα είδη τμημάτων και απουσιών
// ΓΕΝΙΚΗΣ ΠΑΙΔΕΙΑΣ, ΚΑΤΕΥΘΥΝΣΗΣ, ΕΙΔΙΚΟΤΗΤΑΣ, ΕΡΕΥΝΗΤΙΚΗΣ ΕΡΓΑΣΙΑΣ, ...
// οι αλλαγες εδω επηρεάζουν τη συμπεριφορά του προγράμματος
// καλό είναι οι επιλογες να παραμείνουν 3 ώστε να μην επηρεαστούν
// τα παρουσιολόγια
$apousies_define_ini = array (
		0 => array (
				"kod" => "g",
				"perigrafi" => "ΓΕΝ ΠΑΙΔΕΙΑΣ",
				"label" => "ΓΕΝ<br>ΠΑΙ" 
		),
		1 => array (
				"kod" => "k",
				"perigrafi" => "ΚΑΤΕΥΘΥΝΣΗΣ",
				"label" => "ΚΑΤ<br>ΕΥΘ" 
		),
		2 => array (
				"kod" => "e",
				"perigrafi" => "ΕΠΙΛΟΓΗΣ",
				"label" => "ΕΠΙ<br>ΛΟΓ" 
		),
		3 => array (
				"kod" => "p",
				"perigrafi" => "PROJECT",
				"label" => "PRO<br>JECT" 
		) 
);

// εδω καθορίζονται απο ποιον μπορουν να δικαιολογηθούν
// οι απουσίες που γινονται
// οι αλλαγες εδω επηρεάζουν τις στηλες των δικαιολογημενων απουσιων
// P = ΚΗΔΕΜΟΝΑΣ (Parent)
// D = ΓΙΑΤΡΟΣ (Doktor)
// E = ΕΡΓΟΔΟΤΗΣ (Employ)
// M = ΔΙΕΥΘΥΝΤΗΣ (Manager)
$dikaiologisi_define_ini = array (
		0 => array (
				"kod" => "P",
				"perigrafi" => "ΚΗΔΕΜΟΝΑ",
				"label" => "ΚΗΔΕ<br>ΜΟΝΑ",
				"color" => "green" 
		),
		1 => array (
				"kod" => "D",
				"perigrafi" => "ΓΙΑΤΡΟ",
				"label" => "ΓΙΑ<br>ΤΡΟ",
				"color" => "red" 
		),
		2 => array (
				"kod" => "M",
				"perigrafi" => "ΔΙΕΥΘΥΝΤΗ",
				"label" => "ΔΙΕΥ<br>ΘΥΝΤ",
				"color" => "blue" 
		),
		3 => array (
				"kod" => "E",
				"perigrafi" => "ΕΡΓΟΔΟΤΗ",
				"label" => "ΕΡΓΟ<br>ΔΟΤΗ",
				"color" => "yellow" 
		),
		4 => array (
				"kod" => "U",
				"perigrafi" => "ΑΛΛΟΣ ΛΟΓΟΣ",
				"label" => "ΑΛΛΟΣ<br>ΛΟΓΟΣ",
				"color" => "white" 
		) 
);
// παράμετροι για την εκτύπωση των ειδοποιητηρίων
// πρέπει να αντιστοιχεί με τον παραπάνω $dikaiologisi_define
$paper_dik_define_ini = array (
		0 => array (
				'perigrafi' => 'από Γονέα<br>Κηδεμόνα' 
		),
		1 => array (
				'perigrafi' => 'για λόγους<br>Υγείας' 
		),
		2 => array (
				'perigrafi' => 'από<br>Διευθυντή' 
		),
		3 => array (
				'perigrafi' => 'από<br>Εργοδότη' 
		),
		4 => array (
				'perigrafi' => 'για<br>άλλο λόγο' 
		) 
);
// για την αίτηση δικαιολόγησης aitisi_dik.php
// οι λίστες έχουν συνάφεια με τον πίνακα $dikaiologisi_define
// αφήνω κενή μια επιλογή για να μην εμφανιστεί (ο ΔΝΤΗΣ δεν χρειάζεται
// να κάνει αίτηση δικαιολόγησης απουσιών)
// λίστα συννημένων εγγράφων
$dik_me_list_ini = array (
		0 => 'με Δήλωση Κηδεμόνα',
		1 => 'με Ιατρική Βεβαίωση',
		2 => '',
		3 => 'με Δήλωση Εργοδότη',
		4 => '' 
);
// λόγος απουσίας
$logos_list_ini = array (
		0 => 'σοβαρό οικογενειακό λόγο',
		1 => 'ασθένεια',
		2 => '',
		3 => 'επαγγελματικές υποχρεώσεις',
		4 => '' 
);

// φτιάχνω τους πίνακες με βάση την επιλογή
// του κάθε χρήστη
if (isset ( $_SESSION ['parent'] )) {
	$myuser = $_SESSION ['parent'];
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	$query = "SELECT `apoucheck`,`dikcheck` FROM `users` WHERE `username` = '$myuser' ;";
	
	$result = mysqli_query ( $link, $query );
	$row = mysqli_fetch_assoc ( $result );
	$user_apou_def = $row ['apoucheck'];
	$user_dik_def = $row ['dikcheck'];
	
	mysqli_close ( $link );
	
	$apousies_define = array ();
	$k = 0;
	for($i = 0; $i < count ( $apousies_define_ini ); $i ++) {
		if (! (strpos ( $user_apou_def, ( string ) $i ) === false)) {
			foreach ( $apousies_define_ini [$i] as $key => $value ) {
				$apousies_define [$k] [$key] = $value;
			}
			$k ++;
		}
	}
	$dikaiologisi_define = array ();
	$dik_me_list = array ();
	$paper_dik_define = array ();
	$logos_list = array ();
	
	$k = 0;
	for($i = 0; $i < count ( $dikaiologisi_define_ini ); $i ++) {
		if (! (strpos ( $user_dik_def, ( string ) $i ) === false)) {
			foreach ( $dikaiologisi_define_ini [$i] as $key => $value ) {
				$dikaiologisi_define [$k] [$key] = $value;
			}
			foreach ( $paper_dik_define_ini [$i] as $key => $value ) {
				$paper_dik_define [$k] [$key] = $value;
			}
			$dik_me_list [$k] = $dik_me_list_ini [$i];
			$logos_list [$k] = $logos_list_ini [$i];
			$k ++;
		}
	}
} else {
	$apousies_define = $apousies_define_ini;
}

// εισαγωγή της κλάσης για την αποστολή e-mail
require_once "{$classes_prefix}PHPMailer/PHPMailerAutoload.php";
include "{$classes_prefix}PHPMailer/class.smtp.php";
include "includes/mailer.inc.php";

function begin() {
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	mysqli_query ( $link, "BEGIN" );
}
function commit() {
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	mysqli_query ( $link, "COMMIT" );
}
function rollback() {
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	mysqli_query ( $link, "ROLLBACK" );
}
function makedatestamp($date) {
	if (substr ( $date, 0, 1 ) == 0) {
		$myday = substr ( $date, 1, 1 );
		$index = 3;
	} else if (substr ( $date, 1, 1 ) == "/") {
		$myday = substr ( $date, 0, 1 );
		$index = 2;
	} else {
		$myday = substr ( $date, 0, 2 );
		$index = 3;
	}
	
	if (substr ( $date, $index, 1 ) == 0) {
		$mymonth = substr ( $date, $index + 1, 1 );
		$index += 3;
	} else if (substr ( $date, $index + 1, 1 ) == "/") {
		$mymonth = substr ( $date, $index, 1 );
		$index += 2;
	} else {
		$mymonth = substr ( $date, $index, 2 );
		$index += 3;
	}
	
	$myyear = substr ( $date, $index );
	
	if (strlen ( $myday ) == 1) {
		$myday = "0" . $myday;
	}
	
	if (strlen ( $mymonth ) == 1) {
		$mymonth = "0" . $mymonth;
	}
	
	if (strlen ( $myyear ) == 2) {
		$myyear > 80 ? $myyear = "19$myyear" : $myyear = "20$myyear";
	} elseif (strlen ( $myyear ) == 4) {
	} else {
		$myyear = date ( "Y" );
	}
	$datestamp = $myyear . $mymonth . $myday;
	
	// echo "day= $myday month= $mymonth year= $myyear $datestamp<hr>";
	
	return $datestamp;
}
function generatePassword($length = 8) {
	
	// start with a blank password
	$password = "";
	
	// define possible characters
	$possible = "0123456789abcdfghjkmnpqrstvwxyz";
	
	// set up a counter
	$i = 0;
	
	// add random characters to $password until $length is reached
	while ( $i < $length ) {
		
		// pick a random character from the possible ones
		$char = substr ( $possible, mt_rand ( 0, strlen ( $possible ) - 1 ), 1 );
		
		// we don't want this character if it's already in the password
		if (! strstr ( $password, $char )) {
			$password .= $char;
			$i ++;
		}
	}
	
	// done!
	return $password;
}
function changeUserdata($user, $oldpass, $newpass1, $newpass2, $email, $check) {
	$errorText = '';
	
	// έλεγχος αν συμπληρώθηκαν τα στοιχεία
	if ($email == '') {
		$errorText = 'To email δεν μπορεί να είναι κενό!';
		return $errorText;
	}
	if ($check == '') {
		$errorText = 'To "Κάτι που εύκολα θυμάμαι" δεν μπορεί να είναι κενό!';
		return $errorText;
	}
	// έλεγχος αν συμπληρώθηκαν τα στοιχεία
	if ($oldpass == '') {
		$errorText = 'Συμπληρώστε το ισχύον password!';
		return $errorText;
	}
	// if ($newpass1 == ''){$errorText = 'Συμπληρώστε το νέο password!'; return $errorText;}
	// if ($newpass2 == ''){$errorText = 'Συμπληρώστε πάλι το νέο password!'; return $errorText;}
	if ($newpass1 != $newpass2) {
		$errorText = 'Τα δύο νέα password δεν είναι ίδια!';
		return $errorText;
	}
	// έλεγχος για τον χαρακτήρα ^ που χρησιμοποιώ για το διαχωρισμό
	// if (strstr($email . $check . $newpass1 . $newpass2 ,'^')!=false){$errorText = 'Μη χρησιμοποιείτε τον χαρακτήρα "^" παρακαλώ!'; return $errorText;}
	// έλεγχος για το μήκος του password
	If (strlen ( $newpass1 ) > 0 && strlen ( $newpass1 ) < 6) {
		$errorText = 'Το νέο password είναι μικρό!';
		return $errorText;
	}
	
	$oldpasscheck = md5 ( $oldpass );
	$newpass1 == '' ? $newpass = md5 ( $oldpass ) : $newpass = md5 ( $newpass1 );
	
	if ($errorText == '') {
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		$query = "SELECT * FROM `users` WHERE `username` = '$user' AND `password` = '$oldpasscheck' ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
		}
		
		$num = mysqli_num_rows ( $result );
		
		if (! $num) {
			$errorText = 'Λάθος στο ισχύον password!';
		}
	}
	
	if ($errorText == '') {
		
		$query = "UPDATE `users` SET  `password`= '$newpass',  `email`= '$email', `reminder`='$check' WHERE `username` = '$user' AND `password` = '$oldpasscheck' ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
		}
	}
	
	mysqli_close ( $link );
	return $errorText;
}
function restorepass($user, $check) {
	$errorText = '';
	$validuser = false;
	
	// έλεγχος αν συμπληρώθηκαν τα στοιχεία
	if ($user == '') {
		$errorText = 'To username δεν μπορεί να είναι κενό!';
		return $errorText;
	}
	if ($check == '') {
		$errorText = 'To "Κάτι που εύκολα θυμάμαι" δεν μπορεί να είναι κενό!';
		return $errorText;
	}
	
	if ($errorText == '') {
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		$query = "SELECT * FROM `users` WHERE `username` = '$user' AND `reminder` = '$check' ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
		}
		
		$num = mysqli_num_rows ( $result );
		
		if ($num) {
			$validuser = true;
		}
		
		if ($validuser == false) {
			$errorText = 'Λάθος στο username ή στο "Κάτι που εύκολα θυμάμαι"!';
		}
	}
	
	if ($errorText == '') {
		$row = mysqli_fetch_assoc ( $result );
		$email = $row ["email"];
		$writetime = $row ["timestamp"];
		
		// create password
		$pass = generatePassword ();
		
		$mail = new MyPHPMailer ();
		$mail->Subject = "Διαχείριση Απουσιών. Υπενθύμιση password.";
		$mail->Body = "Αυτό είναι ένα ηλεκτρονικό μήνυμα που φτιάχτηκε αυτόματα.\n\nΔημιουργήθηκε ένα νέο password για το λογαριασμό σας.\n\nΤα στοιχεία σας:\n\nusername: $user\npassword: $pass\nemail: $email\nΚάτι που εύκολα θυμόσαστε: $check\n\nΕυχαριστούμε.";
		$mail->AddAddress ( $email );
		
		if (! $mail->Send ())
			$errorText = "Κάποιο πρόβλημα δημιουργήθηκε στην αποστολή email! Ξαναπροσπαθείστε σε λίγο!";
	}
	
	// If everything is OK -> store user data
	if ($errorText == '') {
		
		$userpass = md5 ( $pass );
		
		$query = "UPDATE `users` SET `password`='$userpass' WHERE `username` = '$user' AND `reminder` = '$check' ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
		}
	}
	
	mysqli_close ( $link );
	return $errorText;
}
function registerUser($user, $email, $check, $pass = '', $group = '') {
	$errorText = '';
	if ($group == ''){$group = $user;}
	
	// έλεγχος αν συμπληρώθηκαν τα στοιχεία
	if (! trim ( $user ) || ! trim ( $email ) || ! trim ( $check )) {
		$errorText = 'Συμπληρώστε τα στοιχεία!';
		return $errorText;
	}
	// έλεγχος για τον χαρακτήρα ^ που χρησιμοποιώ για το διαχωρισμό
	// if (strstr(trim($user) . trim($email) . trim($check),'^')!=false){$errorText = 'Μη χρησιμοποιείτε τον χαρακτήρα "^" παρακαλώ!'; return $errorText;}
	
	if ($errorText == '') {
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		$query = "SELECT * FROM `users` WHERE `username` = '$user' ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
		}
		
		$num = mysqli_num_rows ( $result );
		
		if ($num) {
			$errorText = "Το όνομα χρησιμοποιείται από άλλο χρήστη!";
		}
	}
	
	if ($errorText == '') {
		
		// create password
		if ($pass == ''){
			$pass = generatePassword ();
			
			$mail = new MyPHPMailer ();
			$mail->Subject = "Διαχείριση Απουσιών.";
			$mail->Body = "Αυτό είναι ένα ηλεκτρονικό μήνυμα που φτιάχτηκε αυτόματα.\n\nΟ λογαριασμός σας στη \"Διαχείριση Απουσιών\" δημιουργήθηκε με επιτυχία.\n\nΤα στοιχεία σας:\n\nusername: $user\npassword: $pass\nemail: $email\nΚάτι που εύκολα θυμόσαστε: $check\n\nΕυχαριστούμε.\n\n\nΣας ενημερώνουμε ότι εάν δεν εισέλθετε στο λογαριασμό σας μέσα σε 7 ημέρες από την ημέρα δημιουργίας του θα διαγραφεί αυτόματα.\nΤο ίδιο θα γίνει εάν ο λογαριασμός σας μείνει ανενεργός για πανω από ένα χρόνο.";
			$mail->AddAddress ( $email );
			
			if (! $mail->Send ()) {
				$errorText = "Κάποιο πρόβλημα δημιουργήθηκε στην αποστολή email! Ξαναπροσπαθείστε σε λίγο!<br>" . $mail->ErrorInfo;
			}
		}
	}
	
	// If everything is OK -> store user data
	if ($errorText == '') {
		// Secure password string
		$userpass = md5 ( $pass );
		$writetime = time ();
		
		$query = "INSERT INTO `users` (`username`, `password`, `email`, `reminder`, `timestamp`, `lastlogin`, `groupname`) VALUES ('$user','$userpass','$email','$check','$writetime',$writetime, '$group') ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
		}
	}
	
	mysqli_close ( $link );
	return $errorText;
}
function loginUser($user, $pass) {
	$errorText = '';
	$validUser = false;
	$Userexists = false;
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	$query = "SELECT * FROM `users` WHERE `username` = '$user' ;";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	$num = mysqli_num_rows ( $result );
	mysqli_close ( $link );
	
	// Check user existance
	if ($num) {
		$Userexists = true;
		
		// User exists, check password
		$row = mysqli_fetch_assoc ( $result );
		if (md5 ( $pass ) == $row ["password"]) {
			$validUser = true;
			$_SESSION ['userName'] = $user;
			$_SESSION ['lastlogin'] = date ( "G:i:s j/n/Y", $row ["lastlogin"] );
		}
		
		if ($validUser == true){
			$_SESSION ['validUser'] = true;
		}else{
			$_SESSION ['validUser'] = false;
			$errorText = "Λανθασμένο password!";
		}
	} else {
		$errorText = "Ανύπαρκτος χρήστης. Δημιουργείστε ένα λογαριασμό!</a>";
	}
	
	if ($errorText == '') {
		
		// έλεγχοσ αν έχει εγγραφεί πάνω από ένα χρόνο και αν έχει
		// κάνει login τον τελευταίο μήνα
		if ($user !== 'demo') {
			$sign_up = $row ["timestamp"];
			$last_login = $row ["lastlogin"];
			$now_time = time ();
			if ($now_time - $sign_up > 365 * 24 * 3600 && $now_time - $last_login < 30 * 24 * 3600) {
				$_SESSION ['donate_request'] = true;
			}
		}
		
		// έλεγχος αν είναι parentUser ή subuser
		if ($user == $row["groupname"]){
			$_SESSION ['parentUser'] = true;
		}else{
			$_SESSION ['parentUser'] = false;
		}

		$_SESSION ['parent'] = $row["groupname"];
		
		$lastlogin = time ();
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "UPDATE `users` SET `lastlogin`='$lastlogin' WHERE `username` = '$user';";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
		}
		mysqli_close ( $link );
	}
	
	return $errorText;
}
function logoutUser() {
	foreach ( $_SESSION as $key => $value ) {
		unset ( $_SESSION [$key] );
	}
}
function checkUser() {
	if (! isset ( $_SESSION ['validUser'] ) || $_SESSION ['validUser'] != true) {
		header ( 'Location: login.php' );
	}
}
function checkParent() {
	if (! isset ( $_SESSION ['parentUser'] ) || $_SESSION ['parentUser'] != true) {
		header ( 'Location: index.php' );
	checkUser();
	}
}
function checktmima() {
	if (! isset ( $_SESSION ['tmima'] )) {
		header ( 'Location: class.php' );
	}
}
function rmdir_r($dir, $DeleteMe = TRUE) {
	if (! $dh = opendir ( $dir )) return;
	while ( false !== ($obj = readdir ( $dh )) ) {
		if ($obj == '.' || $obj == '..') continue;
		if (is_dir ( $dir . '/' . $obj )){
			rmdir_r ( $dir . '/' . $obj, true );
	    }else{
	        unlink ( $dir . '/' . $obj );
	    }
	 }
	
	closedir ( $dh );
	if ($DeleteMe) {
		rmdir ( $dir );
	}
}
function create_zip($files = array(), $destination = '', $overwrite = false) {
	/*
	 * creates a compressed zip file
	 *
	 * τρόπος να καλεστεί
	 *
	 * $files_to_zip = array(
	 * 'preload-images/1.jpg',
	 * 'preload-images/2.jpg',
	 * 'preload-images/5.jpg',
	 * 'kwicks/ringo.gif',
	 * 'rod.jpg',
	 * 'reddit.gif'
	 * );
	 * //if true, good; if false, zip creation failed
	 * $result = create_zip($files_to_zip,'my-archive.zip');
	 *
	 *
	 */
	// if the zip file already exists and overwrite is false, return false
	if (file_exists ( $destination ) && ! $overwrite) {
		return false;
	}
	// vars
	$valid_files = array ();
	// if files were passed in...
	if (is_array ( $files )) {
		// cycle through each file
		foreach ( $files as $file ) {
			// make sure the file exists
			if (file_exists ( $file )) {
				$valid_files [] = $file;
			}
		}
	}
	// if we have good files...
	if (count ( $valid_files )) {
		// create the archive
		$zip = new ZipArchive ();
		if ($zip->open ( $destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE ) !== true) {
			return false;
		}
		// add the files
		foreach ( $valid_files as $file ) {
			$zip->addFile ( $file, $file );
		}
		// debug
		// echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		// close the zip -- done!
		$zip->close ();
		
		// check to make sure the file exists
		return file_exists ( $destination );
	} else {
		return false;
	}
}
function usercount() {
	include ("includes/dbinfo.inc.php");
	
	$now = time ();
	
	$query = "SELECT count(`username`)as `usercount` FROM `users` WHERE '$now' - `lastlogin` < 60*60*24*30;";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	mysqli_close ( $link );
	
	$row = mysqli_fetch_assoc ( $result );
	return $row["usercount"];
}

function tmimacount() {
	include ("includes/dbinfo.inc.php");
	
	$now = time ();
	
	$query = "SELECT count(`tmima`)as `tmimacount` FROM `tmimata`
    JOIN `users` ON `users`.`username` = `tmimata`.`username`
    WHERE '$now' - `lastlogin` < 60*60*24*30;";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	mysqli_close ( $link );
	$row = mysqli_fetch_assoc ( $result );
	return $row["tmimacount"];
}
function studentscount($myuser, $mytmima) {
	if (! $myuser || ! $mytmima)
		return false;
	
	include ("includes/dbinfo.inc.php");
	
	$query = "SELECT count(`students`.`am`) as `studentscount` FROM `students` 
    JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`
    WHERE `students`.`user`= '$myuser' AND  `studentstmimata`.`tmima` = '$mytmima' ; ";
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	mysqli_close ( $link );
	$row = mysqli_fetch_assoc ( $result );
	
	return $row ["studentscount"];
}
function dayscount($myuser, $mytmima) {
	if (! $myuser || ! $mytmima)
		return false;
	
	include ("includes/dbinfo.inc.php");
	
	$query = "SELECT DISTINCT DATE_FORMAT(`mydate`,'%d/%m/%Y') FROM `apousies` 
    LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am`  
    JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
    WHERE `apousies`.`user`= '$myuser' AND  `studentstmimata`.`tmima` = '$mytmima' ; ";
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	mysqli_close ( $link );
	
	return mysqli_num_rows ( $result );
}
function sumapousies($myuser, $mytmima) {
	if (! $myuser || ! $mytmima)
		return false;
	
	global $apousies_define;
	global $dikaiologisi_define;
	
	include ("includes/dbinfo.inc.php");
	
	$sumstr = '';
	$presumstr = '';
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$k = $x + 1;
		$y = $x * 3 + 1;
		$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
		$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
	}
	$sumstr = substr ( $sumstr, 0, - 1 );
	$presumstr = substr ( $presumstr, 0, - 1 );
	
	$query = "SELECT $sumstr as `sumapousies` FROM `apousies` 
    LEFT JOIN `students` on `apousies`.`user` = `students`.`user` and  `apousies`.`student_am` = `students`.`am` 
    JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
    WHERE `apousies`.`user`= '$myuser' AND  `studentstmimata`.`tmima` = '$mytmima' ; ";
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	$query1 = "SELECT $presumstr as `sumapousiespre` FROM `apousies_pre`  
    JOIN `students` on `apousies_pre`.`user` = `students`.`user` and  `apousies_pre`.`student_am` = `students`.`am`  
    JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
    WHERE `apousies_pre`.`user`= '$myuser' AND  `studentstmimata`.`tmima` = '$mytmima' ; ";
	
	// echo $query1 . "<hr>";
	
	$result1 = mysqli_query ( $link, $query1 );
	
	if (! $result1) {
		$errorText = mysqli_error ( $link );
	}
	
	mysqli_close ( $link );
	$retval = null;
	
	if ($row = mysqli_num_rows ( $result ))
		$retval = $row ["sumapousies"];
	if ($row = mysqli_num_rows ( $result1 ))
		$retval += $row ["sumapousiespre"];
	
	return $retval;
}
function sumpapers($myuser, $mytmima) {
	if (! $myuser || ! $mytmima)
		return false;
	
	include ("includes/dbinfo.inc.php");
	
	$query = "SELECT `mydate`  FROM `paperhistory` 
    LEFT JOIN `students` on `paperhistory`.`user` = `students`.`user` and  `paperhistory`.`am` = `students`.`am`  
    JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `students`.`am` = `studentstmimata`.`student_am`
    WHERE `paperhistory`.`user` ='$myuser'  AND  `studentstmimata`.`tmima` = '$mytmima' ; ";
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	$num = mysqli_num_rows ( $result );
	
	mysqli_close ( $link );
	
	return $num;
}
function newpapers($myuser, $mytmima, $myorio) {
	if (! $myuser || ! $mytmima || ! $myorio)
		return false;
	
	global $apousies_define;
	
	include ("includes/dbinfo.inc.php");
	
	$sumstr = '';
	$presumstr = '';
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$k = $x + 1;
		$y = $x * 3 + 1;
		$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
		$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
	}
	$sumstr = substr ( $sumstr, 0, - 1 );
	$presumstr = substr ( $presumstr, 0, - 1 );
	
	$query = "SELECT 
        `t3`.`epitheto`,
        `t3`.`onoma`,
        `t3`.`sumap` 

FROM `paperhistory` RIGHT JOIN

(
SELECT `students`.`user` ,
        `students`.`am` ,
        `students`.`epitheto`,
        `students`.`onoma`,
        `t2`.`sumap` 
       
FROM `students`
JOIN 
(SELECT `user`,
        `student_am`, 
        SUM(`t1`.`sumap`) as `sumap`
                
FROM  
(SELECT `user`,
        `apousies`.`student_am`,
        $sumstr as `sumap`
FROM `apousies`
where `apousies`.`user` = '$myuser' 
group by  `apousies`.`student_am`
        
UNION
SELECT `user`,
        `apousies_pre`.`student_am`,
        $presumstr as `sumap`
        
FROM `apousies_pre`
where `apousies_pre`.`user` = '$myuser' ) as t1
group by `student_am`
having `sumap`>$myorio) as `t2`
        
on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am` 
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$mytmima' 
ORDER BY `epitheto`,`onoma` ASC 
)as `t3`

ON `paperhistory`.`user` = `t3`.`user` AND `paperhistory`.`am` = `t3`.`am` 
WHERE ISNULL(`paperhistory`.`am`)
ORDER BY `epitheto`,`onoma` ASC;";
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
		echo $errorText . ' newpapers' . "<hr>";
	}
	
	$k = 0;
	$str = "";
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$k ++;
		$str .= "$k. " . $row ["epitheto"] . " " . $row ["onoma"] . " (" . $row ["sumap"] . ")\\n";
	}
	
	mysqli_close ( $link );
	
	return $str;
}
function almost_orio_adik($myuser, $mytmima) {
	if (! $myuser || ! $mytmima)
		return false;
	
	$myorio = getparameter ( "orio_adik", $myuser, $mytmima );
	$mydownorio = $myorio - 10;
	$myorio ++;
	
	if (! $myorio)
		return false;
	
	global $apousies_define;
	global $dikaiologisi_define;
	
	include ("includes/dbinfo.inc.php");
	
	$sumstr = '';
	$presumstr = '';
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$kod = $apousies_define [$x] ['kod'];
		$k = $x + 1;
		$y = $x * 3 + 1;
		$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
		$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
	}
	$sumstr = substr ( $sumstr, 0, - 1 );
	$presumstr = substr ( $presumstr, 0, - 1 );
	
	$totpredikstr = '';
	for($x = 0; $x < count ( $dikaiologisi_define ); $x ++) {
		$y = $x * 3 + 1;
		$kod = $dikaiologisi_define [$x] ['kod'];
		$totpredikstr .= " CONVERT( MID(`apousies_pre`.`dik`,$y,3), UNSIGNED INTEGER) +";
	}
	$totpredikstr = substr ( $totpredikstr, 0, - 1 );
	
	$query = "SELECT 
         CONCAT(`students`.`epitheto` , ' ' ,  `students`.`onoma`) as `name`,
        `t2`.`fldtot`, 
        `t2`.`fldadik`
       
FROM `students`
JOIN 
(SELECT `user`,
        `student_am`, 
        SUM(`t1`.`fldtot`) as `fldtot`,
         SUM(`t1`.`fldadik`) as `fldadik`
                
FROM  
(SELECT `user`,
        `apousies`.`student_am`,
        $sumstr as `fldtot`,
        $sumstr - SUM(`dik`) as `fldadik` 
FROM `apousies`
where `apousies`.`user` = '$myuser'  
group by  `apousies`.`student_am`
        
UNION
SELECT `user`,
        `apousies_pre`.`student_am`,
        $presumstr as `fldtot`,
        $presumstr - ( $totpredikstr ) as `fldadik`
        
FROM `apousies_pre`
where `apousies_pre`.`user` = '$myuser' ) as t1
group by `student_am`
 ) as `t2`
        
on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am` 
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$mytmima'  AND `fldadik` > $mydownorio AND `fldadik` < $myorio
";
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
		echo $errorText . ' almost_orio_adik' . "<hr>";
	}
	
	$num = mysqli_num_rows ( $result );
	mysqli_close ( $link );
	if (! $num)
		return false;
	
	$k = 0;
	$str = "";
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$k ++;
		$str .= "$k. " . $row ["name"] . " (" . $row ["fldadik"] . ")\\n";
	}
	return $str;
}
function over_orio_adik($myuser, $mytmima) {
	if (! $myuser || ! $mytmima)
		return false;
	
	$myorio = getparameter ( "orio_adik", $myuser, $mytmima );
	
	global $apousies_define;
	global $dikaiologisi_define;
	
	include ("includes/dbinfo.inc.php");
	
	$sumstr = '';
	$presumstr = '';
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$k = $x + 1;
		$y = $x * 3 + 1;
		$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
		$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
	}
	$sumstr = substr ( $sumstr, 0, - 1 );
	$presumstr = substr ( $presumstr, 0, - 1 );
	
	$totpredikstr = '';
	for($x = 0; $x < count ( $dikaiologisi_define ); $x ++) {
		$y = $x * 3 + 1;
		$totpredikstr .= " CONVERT( MID(`apousies_pre`.`dik`,$y,3), UNSIGNED INTEGER) +";
	}
	$totpredikstr = substr ( $totpredikstr, 0, - 1 );
	
	$query = "SELECT 
         CONCAT(`students`.`epitheto` , ' ' ,  `students`.`onoma`) as `name`,
        `t2`.`fldadik` 
       
FROM `students`
JOIN 
(SELECT `user`,
        `student_am`, 
        SUM(`t1`.`fldadik`) as `fldadik`
                
FROM  
(SELECT `user`,
        `apousies`.`student_am`,
        $sumstr - SUM(`dik`) as `fldadik`
FROM `apousies`
where `apousies`.`user` = '$myuser'  
group by  `apousies`.`student_am`
        
UNION
SELECT `user`,
        `apousies_pre`.`student_am`,
        $presumstr - ( $totpredikstr ) as `fldadik`
        
FROM `apousies_pre`
where `apousies_pre`.`user` = '$myuser' ) as t1
group by `student_am`
) as `t2`
        
on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am` 
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$mytmima' AND `fldadik` > $myorio
ORDER BY `name` ASC  
";
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
		return;
	}
	
	$num = mysqli_num_rows ( $result );
	mysqli_close ( $link );
	if (! $num)
		return false;
	
	$k = 0;
	$str = "";
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$k ++;
		$str .= "$k. " . $row ["name"] . " (" . $row ["fldadik"] . ")\\n";
	}
	return $str;
}
function almost_orio_total($myuser, $mytmima) {
	if (! $myuser || ! $mytmima)
		return false;
	
	$myorioadik = getparameter ( "orio_adik", $myuser, $mytmima );
	$myoriodik = getparameter ( "orio_dik", $myuser, $mytmima );
	$myorio = $myorioadik + $myoriodik;
	$mydownorio = $myorio - 15;
	$myorio ++;
	
	global $apousies_define;
	
	include ("includes/dbinfo.inc.php");
	
	$sumstr = '';
	$presumstr = '';
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$k = $x + 1;
		$y = $x * 3 + 1;
		$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
		$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
	}
	$sumstr = substr ( $sumstr, 0, - 1 );
	$presumstr = substr ( $presumstr, 0, - 1 );
	
	$query = "SELECT 
         CONCAT(`students`.`epitheto` , ' ' ,  `students`.`onoma`) as `name`,
        `t2`.`fldap` 
       
FROM `students`
JOIN 
(SELECT `user`,
        `student_am`, 
        SUM(`t1`.`fldap`) as `fldap`
                
FROM  
(SELECT `user`,
        `apousies`.`student_am`,
        $sumstr  as `fldap`
FROM `apousies`
where `apousies`.`user` = '$myuser'  
group by  `apousies`.`student_am`
        
UNION
SELECT `user`,
        `apousies_pre`.`student_am`,
        $presumstr  as `fldap`
        
FROM `apousies_pre`
where `apousies_pre`.`user` = '$myuser' ) as t1
group by `student_am`
having `fldap`>$mydownorio AND `fldap`<$myorio
) as `t2`
        
on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am` 
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$mytmima' 
ORDER BY `name` ASC  
";
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	$num = mysqli_num_rows ( $result );
	mysqli_close ( $link );
	if (! $num)
		return false;
	
	$k = 0;
	$str = "";
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$k ++;
		$str .= "$k. " . $row ["name"] . " (" . $row ["fldap"] . ")\\n";
	}
	return $str;
}
function over_orio_total($myuser, $mytmima) {
	if (! $myuser || ! $mytmima)
		return false;
	
	$myorioadik = getparameter ( "orio_adik", $myuser, $mytmima );
	$myoriodik = getparameter ( "orio_dik", $myuser, $mytmima );
	$myorio = $myorioadik + $myoriodik;
	
	global $apousies_define;
	global $dikaiologisi_define;
	
	include ("includes/dbinfo.inc.php");
	
	$sumstr = '';
	$presumstr = '';
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$k = $x + 1;
		$y = $x * 3 + 1;
		$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
		$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
	}
	$sumstr = substr ( $sumstr, 0, - 1 );
	$presumstr = substr ( $presumstr, 0, - 1 );
	
	$totpredikstr = '';
	for($x = 0; $x < count ( $dikaiologisi_define ); $x ++) {
		$y = $x * 3 + 1;
		$totpredikstr .= " CONVERT( MID(`apousies_pre`.`dik`,$y,3), UNSIGNED INTEGER) +";
	}
	$totpredikstr = substr ( $totpredikstr, 0, - 1 );
	
	$query = "SELECT 
         CONCAT(`students`.`epitheto` , ' ' ,  `students`.`onoma`) as `name`,
        `t2`.`fldtot`, 
        `t2`.`fldadik`
       
FROM `students`
JOIN 
(SELECT `user`,
        `student_am`, 
        SUM(`t1`.`fldtot`) as `fldtot`,
         SUM(`t1`.`fldadik`) as `fldadik`
                
FROM  
(SELECT `user`,
        `apousies`.`student_am`,
        $sumstr as `fldtot`,
        $sumstr - SUM(`dik`) as `fldadik` 
FROM `apousies`
where `apousies`.`user` = '$myuser'  
group by  `apousies`.`student_am`
        
UNION
SELECT `user`,
        `apousies_pre`.`student_am`,
        $presumstr as `fldtot`,
        $presumstr - ( $totpredikstr ) as `fldadik`
        
FROM `apousies_pre`
where `apousies_pre`.`user` = '$myuser' ) as t1
group by `student_am`
 ) as `t2`
        
on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am` 
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$mytmima' AND `fldtot` > $myorio
ORDER BY `name` ASC  
";
	
	// echo $query . "<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	$num = mysqli_num_rows ( $result );
	mysqli_close ( $link );
	if (! $num)
		return false;
	
	$k = 0;
	$str = "";
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$k ++;
		$str .= "$k. " . $row ["name"] . " (" . $row ["fldtot"] . ")\\n";
	}
	return $str;
}
function get_all_parameters($myuser, $mytmima = false) {
	include ("includes/dbinfo.inc.php");
	
	if ($mytmima) {
		$query = "select `key`, `value` from `parameters` where `user`='$myuser' and `tmima`='$mytmima' ORDER BY `key` ASC;";
	} else {
		$query = "select `tmima`, `key`, `value` from `parameters` where `user`='$myuser' ORDER BY `tmima`,`key` ASC;";
	}
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	$num = mysqli_num_rows ( $result );
	
	mysqli_close ( $link );
	
	if (! $num)
		return false;
	
	$parameters = array ();
	
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$mytmima ? $tmima = $mytmima : $tmima = $row ["tmima"];
		$key = $row ["key"];
		$value = $row ["value"];
		$parameters [$tmima] [$key] = $value;
	}
	
	return $parameters;
}
function getparameter($mykey, $myuser, $mytmima) {
	include ("includes/dbinfo.inc.php");
	
	$query = "select `value` from `parameters` where `key`='$mykey' and `user`='$myuser' and `tmima`='$mytmima' ;";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	$num = mysqli_num_rows ( $result );
	
	mysqli_close ( $link );
	
	if (! $num)
		return false;
	$row = mysqli_fetch_assoc ( $result );
	return $row ["value"];
}
function setparameter($mykey, $myvalue, $myuser, $mytmima) {
	if (trim ( $myvalue ) != '') {
		if (! getparameter ( $mykey, $myuser, $mytmima )) {
			$query = "insert into `parameters` (`key`,`value`,`user`,`tmima`) values ('$mykey', '$myvalue', '$myuser', '$mytmima') ;";
		} else {
			$query = "update `parameters`  set `value` = '$myvalue' where `key`='$mykey' and `user`='$myuser' and `tmima`='$mytmima' ;";
		}
	} else {
		$query = "delete from `parameters` where `key`='$mykey' and `user`='$myuser' and `tmima`='$mytmima' ;";
	}
	
	include ("includes/dbinfo.inc.php");
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	$num = mysqli_affected_rows ( $link );
	
	mysqli_close ( $link );
	
	return $num;
}
function database_maintain() {
	if (! $_SESSION["parentUser"]){
		return;
	}
	include ("includes/dbinfo.inc.php");
	// διαγραφή εγγραφων με κενο user ή tmima ή am
	$query = "DELETE  FROM `students` WHERE `user` = '' OR `am` = '' ;";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφων με κενο user ή tmima ή am
	$query = "DELETE  FROM `apousies` WHERE `user` = '' OR `student_am` = '' ;";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφων με κενο user ή tmima
	$query = "DELETE  FROM `tmimata` WHERE `username` = '' ;";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφων με κενο username ή password
	$query = "DELETE  FROM `users` WHERE `username` = '' OR `password`= '' ;";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφων με κενο user ή tmima
	$query = "DELETE  FROM `parameters` WHERE `user` = '' ;";
	$result = mysqli_query ( $link, $query );
	// διαγραφή χρηστών που δεν έκαναν καθόλου login μετά την εγγραφή για 7 μέρες
	$now = time ();
	$query = "DELETE  FROM `users` WHERE `lastlogin` = `timestamp` AND '$now' - `timestamp` > 60*60*24*7 ";
	$result = mysqli_query ( $link, $query );
	// διαγραφή χρηστών που δεν έκαναν καθόλου login για 1 χρόνια
	$query = "DELETE  FROM `users` WHERE '$now' - `lastlogin` > 60*60*24*365*1 ";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφών τμημάτων με ανύπαρκτους users
	$query = "DELETE  `tmimata` FROM `tmimata` LEFT JOIN `users` ON `tmimata`.`username` = `users`.`username` WHERE ISNULL(`users`.`username`)";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφών μαθητών με ανύπαρκτους users
	$query = "DELETE  `students` FROM `students` LEFT JOIN `users` ON `students`.`user` = `users`.`username` WHERE ISNULL(`users`.`username`)";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφών απουσιών με ανύπαρκτους users
	$query = "DELETE  `apousies` FROM `apousies` LEFT JOIN `users` ON `apousies`.`user` = `users`.`username` WHERE ISNULL(`users`.`username`)";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφών απουσιών με ανύπαρκτους users
	$query = "DELETE   FROM `paperhistory`  WHERE ISNULL(`user`)";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφών απουσιών_pre με ανύπαρκτους users
	$query = "DELETE   FROM `apousies_pre`  WHERE ISNULL(`user`)";
	$result = mysqli_query ( $link, $query );
	// διαγραφή εγγραφών παραμετρων χωρις τμημα
	$query = "DELETE `parameters` FROM `parameters` LEFT JOIN `tmimata` on `parameters`.`user`=`tmimata`.`username` and `parameters`.`tmima` = `tmimata`.`tmima` WHERE ISNULL(`tmimata`.`tmima`);";
	$result = mysqli_query ( $link, $query );
	
	$myyear = date ( "Y" );
	if (date ( "n" ) < 8)
		$myyear --;
	$checkdate = $myyear . "0801";
	// διαγραφή εγγραφών απουσιών περυσινής χρονιάς
	$query = "DELETE FROM `apousies`  WHERE  `mydate`< '$checkdate' ";
	$result = mysqli_query ( $link, $query );
	$query = "DELETE FROM `apousies_pre`  WHERE  `mydate`< '$checkdate' ";
	$result = mysqli_query ( $link, $query );
	$query = "DELETE FROM `paperhistory`  WHERE  `mydate`< '$checkdate' ";
	$result = mysqli_query ( $link, $query );
	mysqli_close ( $link );
}
function get_pre_apousies($myuser, $myam) {
	include ("includes/dbinfo.inc.php");
	global $apousies_define;
	global $dikaiologisi_define;
	
	$query = "SELECT * FROM `apousies_pre` WHERE `user` = '$myuser' AND `student_am`= '$myam';";
	
	$result = mysqli_query ( $link, $query );
	
	$num = mysqli_num_rows ( $result );
	
	if (! $num || $num == 0)
		return false;
	
	$data = array ();
	$row = mysqli_fetch_assoc ( $result );
	$data ["date"] = substr ( $row ["mydate"], 6, 2 ) . "/" . substr ( $row ["mydate"], 4, 2 ) . "/" . substr ( $row ["mydate"], 0, 4 );
	
	$mydata = $row ["apous"];
	$k = 0;
	
	for($x = 0; $x < count ( $apousies_define ); $x ++) {
		$kod = "ap" . $apousies_define [$x] ["kod"];
		$data [$kod] = ( int ) substr ( $mydata, $k, 3 );
		$k += 3;
	}
	
	$mydata = $row ["dik"];
	$k = 0;
	
	for($x = 0; $x < count ( $dikaiologisi_define ); $x ++) {
		$kod = "di" . $dikaiologisi_define [$x] ["kod"];
		$data [$kod] = ( int ) substr ( $mydata, $k, 3 );
		$k += 3;
	}
	$data ["daysp"] = $row ["daysk"];
	$data ["fh"] = $row ["fh"];
	$data ["mh"] = $row ["mh"];
	$data ["lh"] = $row ["lh"];
	$data ["oa"] = $row ["oa"];
	$data ["da"] = $row ["da"];
	
	mysqli_close ( $link );
	
	return $data;
}
function set_pre_apousies($mydate, $myapous, $mydaysp, $mydik, $myfh, $mymh, $mylh, $myoa, $myda, $myuser, $myam) {
	$mydate ? $mydatestamp = makedatestamp ( $mydate ) : $mydatestamp = '';
	
	if ($myapous) {
		if (! get_pre_apousies ( $myuser, $myam )) {
			$query = "insert into `apousies_pre` (`mydate`,`apous`,`daysk`,`dik`,`fh`,`mh`,`lh`,`oa`,`da`,`user`,`student_am`) values ('$mydatestamp', '$myapous', '$mydaysp', '$mydik', '$myfh', '$mymh', '$mylh', '$myoa', '$myda', '$myuser', '$myam') ;";
		} else {
			$query = "update `apousies_pre`  set `mydate` = '$mydatestamp', `apous` = '$myapous', `daysk` = '$mydaysp', `dik` = '$mydik', `fh` = '$myfh', `mh` = '$mymh', `lh` = '$mylh', `oa` = '$myoa' ,`da` = '$myda' where `student_am`='$myam' and `user`='$myuser' ;";
		}
	} else {
		$query = "delete from `apousies_pre` where `student_am`='$myam' and `user`='$myuser' ;";
	}
	
	include ("includes/dbinfo.inc.php");
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
	}
	
	$num = mysqli_affected_rows ( $link );
	
	mysqli_close ( $link );
	
	return $num;
}
function makemydatestamp($date) {
	global $minyear;
	
	if (substr ( $date, 0, 1 ) == 0) {
		$myday = substr ( $date, 1, 1 );
		$index = 3;
	} else if (substr ( $date, 1, 1 ) == "/") {
		$myday = substr ( $date, 0, 1 );
		$index = 2;
	} else {
		$myday = substr ( $date, 0, 2 );
		$index = 3;
	}
	if (substr ( $date, $index, 1 ) == 0) {
		$mymonth = substr ( $date, $index + 1, 1 );
	} else if (substr ( $date, $index + 1, 1 ) == "/") {
		$mymonth = substr ( $date, $index, 1 );
	} else {
		$mymonth = substr ( $date, $index, 2 );
	}
	
	if (strlen ( $date ) > 5) {
		$myyear = substr ( $date, - 4, 4 );
	} else {
		$myyear = $minyear;
		if ($mymonth < 8)
			$myyear ++;
	}
	
	if (strlen ( $myday ) == 1) {
		$myday = "0" . $myday;
	}
	
	if (strlen ( $mymonth ) == 1) {
		$mymonth = "0" . $mymonth;
	}
	
	$datestamp = $myyear . $mymonth . $myday;
	
	// echo "day= $myday month= $mymonth year= $myyear $datestamp<hr>";
	
	return $datestamp;
}
function sectionArray($array, $step) {
	$sectioned = array ();
	
	$k = 0;
	for($i = 0; $i < count ( $array ); $i ++) {
		if (! ($i % $step)) {
			$k ++;
		}
		$sectioned [$k] [] = $array [$i];
	}
	return $sectioned;
}
function set_select_tmima($myuser) {
	include ("includes/dbinfo.inc.php");
	
	$query = "SELECT `tmima`  FROM `tmimata` where `username` = '$myuser' ORDER BY `tmima`;";
	$result = mysqli_query ( $link, $query );
	$num = mysqli_num_rows ( $result );
	mysqli_close ( $link );
	
	$sel_tmima = array ();
	while($row = mysqli_fetch_assoc($result)) {
		$sel_tmima [] = $row["tmima"];
	}
	if (count ( $sel_tmima ) == 1) {
		$_SESSION ['tmima'] = $sel_tmima [0];
		unset ( $_SESSION ['sel_tmima'] );
	} else {
		$_SESSION ['sel_tmima'] = $sel_tmima;
	}
	return;
}
function add_months($orgDate, $mth) {
	$cd = strtotime ( $orgDate );
	$retDAY = date ( 'Ymd', mktime ( 0, 0, 0, date ( 'm', $cd ) + $mth, date ( 'd', $cd ), date ( 'Y', $cd ) ) );
	return $retDAY;
}
function ZipDir($source, $destination) {
	if (! extension_loaded ( 'zip' ) || ! file_exists ( $source )) {
		return false;
	}
	
	$zip = new ZipArchive ();
	if (! $zip->open ( $destination, ZIPARCHIVE::CREATE )) {
		return false;
	}
	
	$source = str_replace ( '\\', '/', realpath ( $source ) );
	
	if (is_dir ( $source ) === true) {
		$files = new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( $source ), RecursiveIteratorIterator::SELF_FIRST );
		
		foreach ( $files as $file ) {
			$file = str_replace ( '\\', '/', realpath ( $file ) );
			
			if (is_dir ( $file ) === true) {
				$zip->addEmptyDir ( str_replace ( $source . '/', '', $file . '/' ) );
			} else if (is_file ( $file ) === true) {
				$zip->addFromString ( str_replace ( $source . '/', '', $file ), file_get_contents ( $file ) );
			}
		}
	} else if (is_file ( $source ) === true) {
		$zip->addFromString ( basename ( $source ), file_get_contents ( $source ) );
	}
	
	return $zip->close ();
}
function check_many_apousies($user, $tmima) {
	$query = "SELECT count( `mydate` ) AS dayscount, `mydate` , `apousies`.`student_am` , `epitheto` , `onoma`, `studentstmimata`.`tmima` 
FROM `apousies`
JOIN `students` ON `apousies`.`user` = `students`.`user` AND `student_am` = `am`
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$tmima'   AND  `apousies`.`user` = '$user'
GROUP BY `mydate` , `student_am`
HAVING count( `mydate` ) >1;";
	
	// echo $query . "<hr>";
	
	include ("includes/dbinfo.inc.php");
	
	$result = mysqli_query ( $link, $query );
	$num = mysqli_num_rows ( $result );
	mysqli_close ( $link );
	
	if (! $num) {
		return false;
	} else {
		$message = "Υπάρχουν πολλαπλές καταχωρήσεις απουσιών την ίδια μέρα στους παρακάτω μαθητές:\\n\\n";
		for($i = 0; $i < $num; $i ++) {
			$mydate = substr ( mysqli_fetch_all ( $result, MYSQLI_ASSOC ) [$i] ["mydate"], 6, 2 ) . "/" . substr ( mysqli_fetch_all ( $result, MYSQLI_ASSOC ) [$i] ["mydate"], 4, 2 ) . "/" . substr ( mysqli_fetch_all ( $result, MYSQLI_ASSOC ) [$i] ["mydate"], 0, 4 );
			$message .= "Ημνια:$mydate, καταχωρήσεις:" . mysqli_fetch_all ( $result, MYSQLI_ASSOC ) [$i] ["dayscount"] . ", τμήμα:" . mysqli_fetch_all ( $result, MYSQLI_ASSOC ) [$i] ["tmima"] . " " . mysqli_fetch_all ( $result, MYSQLI_ASSOC ) [$i] ["tmima-kat"] . " " . mysqli_fetch_all ( $result, MYSQLI_ASSOC ) [$i] ["tmima-epi"] . ", " . mysqli_fetch_all ( $result, MYSQLI_ASSOC ) [$i] ["epitheto"] . " " . mysqli_fetch_all ( $result, MYSQLI_ASSOC ) [$i] ["onoma"] . "\\n";
		}
		return $message;
	}
}
function checkInstall() {
	if (! file_exists ( 'includes/dbinfo.inc.php' ) || ! file_exists ( 'includes/mailer.inc.php' )) {
		header ( 'Location: install/index.php' );
	}
}
function gettmimata4student($myuser, $myam) {
	$query = "SELECT `type` , `studentstmimata`.`tmima` FROM `tmimata` JOIN `studentstmimata` ON `tmimata`.`username` = `studentstmimata`.`user` AND `tmimata`.`tmima` = `studentstmimata`.`tmima` WHERE `tmimata`.`username` = '$myuser' AND `student_am` = '$myam' ;";
	
	include ("includes/dbinfo.inc.php");
	
	$result = mysqli_query ( $link, $query );
	$num = mysqli_num_rows ( $result );
	mysqli_close ( $link );
	
	if (! $num) {
		return false;
	} else {
		$tmimataarray = array ();
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$type = $row ["type"];
			$mytmima = $row ["tmima"];
			$tmimataarray ["$type"] = $mytmima;
		}
		
		return $tmimataarray;
	}
}
function gettmimata4user($myuser) {
	$query = "SELECT `tmima`,`type` FROM `tmimata` WHERE `username` = '$myuser'  ORDER BY `tmima`;";
	
	include ("includes/dbinfo.inc.php");

	$result = mysqli_query ( $link, $query );
	$num = mysqli_num_rows ( $result );
	mysqli_close ( $link );

	if (! $num) {
		return false;
	} else {
		$x = 0;
		$tmimataarray = array ();
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$type = $row ["type"];
			$mytmima = $row ["tmima"];
			$tmimataarray["$type"][$x] = $mytmima;
			$x++;
		}

		return $tmimataarray;
	}
}


function ucfirst_utf8($stri) {
	if ($stri {0} >= "\xc3")
		return (($stri {1} >= "\xa0") ? ($stri {0} . chr ( ord ( $stri {1} ) - 32 )) : ($stri {0} . $stri {1})) . substr ( $stri, 2 );
	else
		return ucfirst ( $stri );
}


function sum_per_digits($numericString, $numOfDigits){
	$sum = 0;
	for ($index = 0; $index < strlen($numericString);$index+=$numOfDigits){
		$sum += (int)substr($numericString,$index,$numOfDigits);
	}
	return $sum;
}

function createSafeSheetName($sheetname){
	$badchars = '\/?*[]:"';
	$safeSheetName  = '';
	$stringLength = strlen($sheetname);
	for ($i = 0; $i < $stringLength; $i++){
		$char = $sheetname[$i];
		if (strpos($badchars, $char)){
			$safeSheetName .= "-" ;
		}else{
			$safeSheetName .= $char;
		}
	}
	$finalname = substr($safeSheetName,0,33);
	return $finalname;
}

?>
