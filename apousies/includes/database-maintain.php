<?php
include ("../includes/dbinfo.inc.php");

$check = false;

//διαγραφή εγγραφων με κενο  user ή tmima ή am
$query =  "DELETE  FROM `students` WHERE `user` = '' OR `am` = '' ;";
	
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "1 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές εγγραφών `students` με κενό  user ή am<hr width=\"600\" align=\"left\">";
	$check = true;
}

//διαγραφή εγγραφων με κενο  user ή am
$query =  "DELETE  FROM `apousies` WHERE `user` = ''  OR `student_am` = '' ;";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "2 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές εγγραφών `apousies`  με κενό  user ή am<hr width=\"600\" align=\"left\">";
	$check = true;
}

//διαγραφή εγγραφων με κενο  user ή tmima
$query =  "DELETE  FROM `tmimata` WHERE `username` = '' OR `tmima`= '' ;";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "3 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές εγγραφών `tmimata` με κενο  user ή tmima<hr width=\"600\" align=\"left\">";
	$check = true;
}

//διαγραφή εγγραφων με κενο  username ή password
$query =  "DELETE  FROM `users` WHERE `username` = '' OR `password`= '' ;";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "4 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές εγγραφών `users` με κενο  username ή password<hr width=\"600\" align=\"left\">";
	$check = true;
}

//διαγραφή εγγραφων με κενο  user ή tmima
$query =  "DELETE  FROM `parameters` WHERE `user` = '' OR `tmima`= '' ;";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "5 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές εγγραφών `parameters` με κενο  user ή tmima<hr width=\"600\" align=\"left\">";
	$check = true;
}
//διαγραφή χρηστών που δεν έκαναν καθόλου login μετά την εγγραφή για 7 μέρες
$now = time();
$query =  "DELETE  FROM `users` WHERE `lastlogin` = `timestamp` AND '$now' - `timestamp` > 60*60*24*7 ";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "6 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές χρηστών που δεν έκαναν καθόλου login μετά την εγγραφή για 7 μέρες<hr width=\"600\" align=\"left\">";
	$check = true;
}

//διαγραφή χρηστών που δεν έκαναν καθόλου login για 1 χρόνια
$query =  "DELETE  FROM `users` WHERE '$now' - `lastlogin` > 60*60*24*365 ";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "7 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές χρηστών που δεν έκαναν καθόλου login για 1 χρόνο<hr width=\"600\" align=\"left\">";
	$check = true;
}

//διαγραφή εγγραφών τμημάτων με ανύπαρκτους users
$query =  "DELETE  `tmimata` FROM `tmimata` LEFT JOIN `users` ON `tmimata`.`username` = `users`.`username` WHERE ISNULL(`users`.`username`)";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "8 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές εγγραφών τμημάτων με ανύπαρκτους users<hr width=\"600\" align=\"left\">";
	$check = true;
}

//διαγραφή εγγραφών μαθητών με ανύπαρκτους users
$query =  "DELETE  `students` FROM `students` LEFT JOIN `users` ON `students`.`user` = `users`.`username` WHERE ISNULL(`users`.`username`)";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "9 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές εγγραφών μαθητών με ανύπαρκτους users<hr width=\"600\" align=\"left\">";
	$check = true;
}

//διαγραφή εγγραφών απουσιών με ανύπαρκτους users
$query =  "DELETE  `apousies` FROM `apousies` LEFT JOIN `users` ON `apousies`.`user` = `users`.`username` WHERE ISNULL(`users`.`username`)";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "10 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές εγγραφών απουσιών με ανύπαρκτους users<hr width=\"600\" align=\"left\">";
	$check = true;
}


//διαγραφή εγγραφών απουσιών με ανύπαρκτους users
$query =  "DELETE   FROM `paperhistory`  WHERE ISNULL(`user`)";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "11 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές εγγραφών ιστορικού με ανύπαρκτους users<hr width=\"600\" align=\"left\">";
	$check = true;
}

$myyear = date ("Y");
if (date ("n")<8)$myyear--;

$checkdate = date('Ymd',mktime(0,0,0,8,1,$myyear));

//διαγραφή εγγραφών απουσιών περυσινής χρονια του demo
//$query =  "DELETE  `apousies` FROM `apousies`  WHERE  `user`= 'demo' and `date`< '$checkdate' ";
$query =  "DELETE  `apousies` FROM `apousies`  WHERE  `mydate`< '$checkdate' ";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "12 $errorText<hr width=\"600\" align=\"left\">";
}
$affected = mysqli_affected_rows();
if($affected!=0){
	echo "$affected διαγραφές απουσιών πριν από 1/8/$myyear <hr width=\"600\" align=\"left\">";
	$check = true;
}



$now = time();
$query =  "SELECT * FROM `users` WHERE  $now - `lastlogin` > 60*60*24*365  ORDER BY `lastlogin` ASC;";
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "13 $errorText<hr width=\"600\" align=\"left\">";
}	

$num= mysqli_num_rows($result);

if ($num){
 echo "Οι Παρακάτω χρήστες δεν εμφανίστηκαν γιά ένα έτος:<br>";
	while ($row = mysqli_fetch_assoc($result)) {
		$logindate = date("d M Y",$row["lastlogin"]);
	
		echo $logindate . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .$row["username"] . "<br>";
	}
 echo "<hr width=\"600\" align=\"left\">";
}


if($check==false){
 echo "Καμία αλλαγή";
}



mysqli_close($link);
?>
