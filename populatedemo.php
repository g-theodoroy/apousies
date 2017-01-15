<?php
// require_once('common.php');
// checkUser();
isset ( $_GET ['u'] ) ? $usernametowatch = $_GET ['u'] : $usernametowatch = '';
isset ( $_GET ['l'] ) ? $limit = $_GET ['l'] : $limit = 24;

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$query = 'DELETE FROM `users` WHERE `username`="demo" ;';
$result = mysqli_query ( $link, $query );

$lastlogin = time ();

$query = "INSERT INTO `users` (`username`, `password`, `email`, `reminder`, `timestamp`, `lastlogin`, `groupname`) VALUES
('demo', '4c7a34d25eff9121c49658dbceadf694', 'demodemo.demo', 'demo', 1232368233, $lastlogin, 'demo')";
$result = mysqli_query ( $link, $query );

$query = 'DELETE FROM `tmimata` WHERE `username`="demo" ;';
$result = mysqli_query ( $link, $query );

$query = "INSERT INTO `tmimata` (`tmima`, `type`, `lastselect`, `username`) VALUES
( 'demo','g','1316858176','demo'),
( 'demo1','g','1316858017','demo'),
( 'demo2','g','1326018661','demo'),
( 'Κ1','k','1327087700','demo'),
( 'Κ2','k','1327087685','demo'),
( 'Κ3','k','1316843160','demo'),
( 'Ε1','e','1316775976','demo'),
( 'Ε2','e','1316776009','demo'),
( 'Ε3','e','1316777393','demo');";
$result = mysqli_query ( $link, $query );

$query = 'DELETE FROM `students` WHERE `user`="demo" ;';
$result = mysqli_query ( $link, $query );

$query = "INSERT INTO `students` (`am`, `epitheto`, `onoma`, `patronimo`, `ep_kidemona`, `on_kidemona`, `dieythinsi`, `tk`, `poli`, `til1`, `til2`, `filo`, `user`) VALUES
( '1','Επίθετο_Α','Ονομα_Α','Πατρώνυμο_Α','Επίθετο_Α','Ον_Πατέρα_Α','Διεύθυνση_Α','','','','','Α','demo'),
( '2','Επίθετο_Β','Ονομα_Β','Πατρώνυμο_Β','Επίθετο_Β','Ον_Πατέρα_Β','Διεύθυνση_Β','','','','','Θ','demo'),
( '3','Επίθετο_Γ','Ονομα_Γ','Πατρώνυμο_Γ','Επίθετο_Γ','Ον_Πατέρα_Γ','Διεύθυνση_Γ','','','','','Α','demo'),
( '4','Επίθετο_Δ','Ονομα_Δ','Πατρώνυμο_Δ','Επίθετο_Δ','Ον_Πατέρα_Δ','Διεύθυνση_Δ ','','','','','Θ','demo'),
( '5','Επίθετο_Ε','Ονομα_Ε','Πατρώνυμο_Ε','Επίθετο_Ε','Ον_Πατέρα_Ε','Διεύθυνση_Ε','','','','','Α','demo'),
( '6','Επίθετο_Ζ','Ονομα_Ζ','Πατρώνυμο_Ζ','Επίθετο_Ζ','Ον_Πατέρα_Ζ','Διεύθυνση_Ζ ','','','','','Α','demo'),
( '7','Επίθετο_Η','Ονομα_Η','Πατρώνυμο_Η','Επίθετο_Η','Ον_Πατέρα_Η','Διεύθυνση_Η','','','','','Α','demo'),
( '8','Επίθετο_Θ','Ονομα_Θ','Πατρώνυμο_Θ','Επίθετο_Θ','Ον_Πατέρα_Θ','Διεύθυνση_Θ','','','','','Θ','demo'),
( '9','Επίθετο_Ι','Ονομα_Ι','Πατρώνυμο_Ι','Επίθετο_Ι','Ον_Πατέρα_Ι','Διεύθυνση_Ι','','','','','Α','demo'),
( '10','Επίθετο_Κ','Ονομα_Κ','Πατρώνυμο_Κ','Επίθετο_Κ','Ον_Πατέρα_Κ','Διεύθυνση_Κ','','','','','Α','demo'),
( '11','Επίθετο_Λ','Ονομα_Λ','Πατρώνυμο_Λ','Επίθετο_Λ','Ον_Πατέρα_Λ','Διεύθυνση_Λ','','','','','Α','demo'),
( '12','Επίθετο_Μ','Ονομα_Μ','Πατρώνυμο_Μ','Επίθετο_Μ','Ον_Πατέρα_Μ','Διεύθυνση_Μ','','','','','Α','demo'),
( '13','Επίθετο_Ν','Ονομα_Ν','Πατρώνυμο_Ν','Επίθετο_Ν','Ον_Πατέρα_Ν','Διεύθυνση_Ν','','','','','Α','demo'),
( '14','Επίθετο_Ξ','Ονομα_Ξ','Πατρώνυμο_Ξ','Επίθετο_Ξ','Ον_Πατέρα_Ξ','Διεύθυνση_Ξ','','','','','Α','demo'),
( '15','Επίθετο_Ο','Ονομα_Ο','Πατρώνυμο_Ο','Επίθετο_Ο','Ον_Πατέρα_Ο','Διεύθυνση_Ο','','','','','Α','demo'),
( '16','Επίθετο_Π','Ονομα_Π','Πατρώνυμο_Π','Επίθετο_Π','Ον_Πατέρα_Π','Διεύθυνση_Π','','','','','Α','demo'),
( '17','Επίθετο_Ρ','Ονομα_Ρ','Πατρώνυμο_Ρ','Επίθετο_Ρ','Ον_Πατέρα_Ρ','Διεύθυνση_Ρ','','','','','Α','demo'),
( '18','Επίθετο_Σ','Ονομα_Σ','Πατρώνυμο_Σ','Επίθετο_Σ','Ον_Πατέρα_Σ','Διεύθυνση_Σ','','','','','Α','demo'),
( '19','Επίθετο_Τ','Ονομα_Τ','Πατρώνυμο_Τ','Επίθετο_Τ','Ον_Πατέρα_Τ','Διεύθυνση_Τ','','','','','Α','demo'),
( '20','Επίθετο_Υ','Ονομα_Υ','Πατρώνυμο_Υ','Επίθετο_Υ','Ον_Πατέρα_Υ','Διεύθυνση_Υ','','','','','Α','demo'),
( '21','Επίθετο_Φ','Ονομα_Φ','Πατρώνυμο_Φ','Επίθετο_Φ','Ον_Πατέρα_Φ','Διεύθυνση_Φ','','','','','Α','demo'),
( '22','Επίθετο_Χ','Ονομα_Χ','Πατρώνυμο_Χ','Επίθετο_Χ','Ον_Πατέρα_Χ','Διεύθυνση_Χ','','','','','Α','demo'),
( '23','Επίθετο_Ψ','Ονομα_Ψ','Πατρώνυμο_Ψ','Επίθετο_Ψ','Ον_Πατέρα_Ψ','Διεύθυνση_Ψ','','','','','Α','demo'),
( '24','Επίθετο_Ω','Ονομα_Ω','Πατρώνυμο_Ω','Επίθετο_Ω','Ον_Πατέρα_Ω','Διεύθυνση_Ω','','','','','Α','demo');";
$result = mysqli_query ( $link, $query );

$query = 'DELETE FROM `studentstmimata` WHERE `user`="demo" ;';
$result = mysqli_query ( $link, $query );

$query = "INSERT INTO `studentstmimata` (`student_am`, `tmima`, `user`) VALUES
( '1','demo','demo'),( '1','Κ1','demo'),( '1','Ε1','demo'),
( '2','demo','demo'),( '2','Κ3','demo'),( '2','Ε2','demo'),
( '3','demo','demo'),( '3','Κ3','demo'),( '3','Ε1','demo'),
( '4','demo','demo'),( '4','Κ1','demo'),( '4','Ε3','demo'),
( '5','demo1','demo'),( '5','Κ2','demo'),( '5','Ε2','demo'),
( '6','demo1','demo'),( '6','Κ1','demo'),( '6','Ε2','demo'),
( '7','demo1','demo'),( '7','Κ3','demo'),( '7','Ε1','demo'),
( '8','demo','demo'),( '8','Κ2','demo'),( '8','Ε1','demo'),
( '9','demo2','demo'),( '9','Κ1','demo'),( '9','Ε3','demo'),
( '10','demo1','demo'),( '10','Κ2','demo'),( '10','Ε1','demo'),
( '11','demo','demo'),( '11','Κ3','demo'),( '11','Ε3','demo'),
( '12','demo2','demo'),( '12','Κ2','demo'),( '12','Ε1','demo'),
( '13','demo1','demo'),( '13','Κ1','demo'),( '13','Ε3','demo'),
( '14','demo','demo'),( '14','Κ3','demo'),( '14','Ε2','demo'),
( '15','demo2','demo'),( '15','Κ2','demo'),( '15','Ε2','demo'),
( '16','demo','demo'),( '16','Κ3','demo'),( '16','Ε1','demo'),
( '17','demo1','demo'),( '17','Κ1','demo'),( '17','Ε2','demo'),
( '18','demo','demo'),( '18','Κ2','demo'),( '18','Ε3','demo'),
( '19','demo2','demo'),( '19','Κ1','demo'),( '19','Ε2','demo'),
( '20','demo','demo'),( '20','Κ2','demo'),( '20','Ε3','demo'),
( '21','demo1','demo'),( '21','Κ3','demo'),( '21','Ε1','demo'),
( '22','demo2','demo'),( '22','Κ2','demo'),( '22','Ε2','demo'),
( '23','demo','demo'),( '23','Κ1','demo'),( '23','Ε3','demo'),
( '24','demo1','demo'),( '24','Κ3','demo'),( '24','Ε3','demo');";
$result = mysqli_query ( $link, $query );

$query = 'DELETE FROM `parameters` WHERE `user`="demo" ;';
$result = mysqli_query ( $link, $query );

$query = "INSERT INTO `parameters` (`tmima`, `key`, `value`, `user`) VALUES 
('demo','orio_adik','50','demo'),
('demo','orio_dik','64','demo'),
('demo','orio_paper','23','demo'),
('demo1','orio_adik','50','demo'),
('demo1','orio_dik','64','demo'),
('demo1','orio_paper','23','demo'),
('demo2','orio_adik','50','demo'),
('demo2','orio_dik','64','demo'),
('demo2','orio_paper','23','demo'),
('Ε1','orio_adik','50','demo'),
('Ε1','orio_dik','64','demo'),
('Ε1','orio_paper','23','demo'),
('Ε2','orio_adik','50','demo'),
('Ε2','orio_dik','64','demo'),
('Ε2','orio_paper','23','demo'),
('Κ2','orio_adik','50','demo'),
('Κ2','orio_dik','64','demo'),
('Κ2','orio_paper','23','demo'),
('Κ3','orio_adik','50','demo'),
('Κ3','orio_dik','64','demo'),
('Κ3','orio_paper','23','demo');";
$result = mysqli_query ( $link, $query );

$query = 'DELETE FROM `apousies` WHERE `user`="demo" ;';
$result = mysqli_query ( $link, $query );

$query = "SELECT `student_am`, `user`  FROM `apousies`  WHERE `user`= '$usernametowatch'   GROUP BY `student_am` ORDER BY count(`mydate`) DESC LIMIT $limit;";
$result = mysqli_query ( $link, $query );
$num = mysqli_num_rows ( $result );

$amarray = array ();
$i = 0;
while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$amarray [$i + 1] = $row ["student_am"];
	$i ++;
}

$query = "INSERT INTO `apousies` (`mydate`, `apous`, `dik`, `from`, `fh`, `mh`, `lh`, `oa`, `da`, `user`, `student_am`)
SELECT `mydate`, `apous`, `dik`, `from`, `fh`, `mh`, `lh`, `oa`, `da`,'demo', `t1`.`student_am` FROM `apousies` left JOIN
(SELECT `student_am`, `user`  FROM `apousies`  WHERE `user`= '$usernametowatch'   GROUP BY `student_am` ORDER BY count(`mydate`) DESC LIMIT $limit) AS t1
ON `apousies`.`student_am`=  `t1`.`student_am` and  `apousies`.`user`=  `t1`.`user` where not isnull(`t1`.`student_am`)";
$result = mysqli_query ( $link, $query );

for($i = 1; $i <= $num; $i ++) {
	$myam = $amarray [$i];
	$query = "UPDATE `apousies` SET `student_am`='$i'   WHERE `user`='demo' AND `student_am`='$myam' ;";
	$result = mysqli_query ( $link, $query );
}

for($i = 1; $i <= $num; $i ++) {
	$myam = $amarray [$i];
	$query = "UPDATE `apousies` SET `student_am`='$i'   WHERE `user`='demo' AND `student_am`='$myam' ;";
	$result = mysqli_query ( $link, $query );
}

$query = 'DELETE FROM `paperhistory` WHERE `user`="demo" ;';
$result = mysqli_query ( $link, $query );

$query = "INSERT INTO `paperhistory` (`protok`,`mydate`,`am`,`apous`,`user` )
SELECT `protok`,`mydate`,`am`,`apous`,'demo'  FROM `paperhistory` left JOIN
(SELECT `student_am`, `user`  FROM `apousies`  WHERE `user`= '$usernametowatch'   GROUP BY `student_am` ORDER BY count(`mydate`) DESC LIMIT $limit) AS t1
ON `am`=  `t1`.`student_am` and  `paperhistory`.`user`=  `t1`.`user` where not isnull(`t1`.`student_am`)";
$result = mysqli_query ( $link, $query );

for($i = 1; $i <= $num; $i ++) {
	$myam = $amarray [$i];
	$query = "UPDATE `paperhistory` SET `am`='$i'   WHERE `user`='demo' AND `am`='$myam' ;";
	$result = mysqli_query ( $link, $query );
}

/*
 * if (mysqli_error($link)){
 * echo mysqli_error($link);
 * }else{
 * echo "OK";
 * }
 */

header('Location: index.php');

?>