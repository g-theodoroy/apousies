<?php
require_once ('common.php');
checkUser ();
checktmima ();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';
// παίρνω το τμήμα που επιλέχτηκε

// βρίσκω πόσες εγγραφές έχω για να κάνω
// αντίστοιχες λούπες
isset($_POST ["checknum"]) ? $checknum = trim ( $_POST ["checknum"] ) : $checknum = null ;
// βρίσκω τα πατημένα και βάζω σε πίνακα τους am για διαγραφή
for($k = 0; $k < $checknum; $k ++) {
	if (isset ( $_POST ["chk$k"] ) && trim ( $_POST ["chk$k"] ) != "") {
		$pinakas [] = trim ( $_POST ["chk$k"] );
	}
}

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

if ($parent) {
	// εμφανίζω τα στοιχεία
	// $query = "SELECT `paperhistory`.`am`, `epitheto`, `onoma`,`protok`,`mydate`,`apous` FROM `paperhistory` RIGHT JOIN `students` on `students`.`am`= `paperhistory`.`am` AND `students`.`user`= `paperhistory`.`user` AND `students`.`tmima`= `paperhistory`.`tmima` WHERE `paperhistory`.`user` = '$parent' AND `paperhistory`.`tmima`= '$tmima' ORDER BY `students`.`epitheto`, `students`.`onoma`, `mydate` ASC ";
	
	$query = "SELECT * FROM  `students`  
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$tmima' AND `students`.`user` = '$parent'  ORDER BY  `epitheto`, `onoma` ASC ";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
		echo "1 $errorText<hr>";
	}
	
	$num = mysqli_num_rows ( $result );
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="el">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- Liquid Blueprint CSS -->
<link rel="stylesheet"
	href="<?php echo $style_prefix; ?>blueprint/reset.css" type="text/css"
	media="screen, projection">
<link rel="stylesheet"
	href="<?php echo $style_prefix; ?>blueprint/liquid.css" type="text/css"
	media="screen, projection">
<link rel="stylesheet"
	href="<?php echo $style_prefix; ?>blueprint/typography.css"
	type="text/css" media="screen, projection">
<link rel="stylesheet"
	href="<?php echo $style_prefix; ?>blueprint/fancy-type.css"
	type="text/css" media="screen, projection">
<!--[if IE]><link rel="stylesheet" href="<?php echo $style_prefix; ?>blueprint/lib/ie.css" type="text/css" media="screen, projection"><![endif]-->


<title>ΙΣΤΟΡΙΚΟ ΕΙΔΟΠΟΙΗΤΗΡΙΩΝ</title>

<style type="text/css">
table {
	table-layout: fixed;
	width: 100%;
}

th, td {
	text-align: center;
	vertical-align: middle;
	padding: 0;
	overflow: hidden;
	white-space: nowrap;
	border-color: #ddd;
	border-style: solid;
	border-width: 1px;
}
</style>



</head>
<body>
	<div class="container">
		<!-- HEADER -->
		<div class="block">
			<div class="column span-24 last">

				<table cellspacing="0" cellpadding="0" align="center">
					<tbody>
						<tr>
							<th style="border-style: none;" width="15%"></th>
							<th style="border-style: none;"></th>
							<th style="border-style: none;" width="3%"></th>
							<th style="border-style: none;"></th>
							<th style="border-style: none;" width="3%"></th>
							<th style="border-style: none;"></th>
							<th style="border-style: none;" width="3%"></th>
							<th style="border-style: none;"></th>
							<th style="border-style: none;" width="3%"></th>
							<th style="border-style: none;"></th>
							<th style="border-style: none;" width="3%"></th>
							<th style="border-style: none;"></th>
							<th style="border-style: none;" width="3%"></th>
							<th style="border-style: none;"></th>
							<th style="border-style: none;" width="3%"></th>
							<th style="border-style: none;"></th>
							<th style="border-style: none;" width="3%"></th>
							<th style="border-style: none;"></th>
							<th style="border-style: none;" width="3%"></th>
						</tr>
						<tr>
							<th style="text-align: left; border-style: none;">Τμήμα: <?php echo $tmima; ?></th>
							<th style="text-align: center; border-style: none;" colspan="14"><h2>Απεσταλμένα
									ειδοποιητήρια</h2></th>
							<th style="text-align: right; border-style: none;" colspan="4"><?php echo date("j/n/Y") ?></th>
						</tr>
						<tr>
							<th rowspan="2">ΟΝΟΜΑΤΕΠΩΝΥΜΟ</th>
							<th colspan="2">ΣΕΠΤΕΜΒΡΙΟΣ</th>
							<th colspan="2">ΟΚΤΩΒΡΙΟΣ</th>
							<th colspan="2">ΝΟΕΜΒΡΙΟΣ</th>
							<th colspan="2">ΔΕΚΕΜΒΡΙΟΣ</th>
							<th colspan="2">ΙΑΝΟΥΑΡΙΟΣ</th>
							<th colspan="2">ΦΕΒΡΟΥΑΡΙΟΣ</th>
							<th colspan="2">ΜΑΡΤΙΟΣ</th>
							<th colspan="2">ΑΠΡΙΛΙΟΣ</th>
							<th colspan="2">ΜΑΙΟΣ</th>
						</tr>
						<tr>
							<th>ΠΡ/ΗΜ</th>
							<th>ΑΠ</th>
							<th>ΠΡ/ΗΜ</th>
							<th>ΑΠ</th>
							<th>ΠΡ/ΗΜ</th>
							<th>ΑΠ</th>
							<th>ΠΡ/ΗΜ</th>
							<th>ΑΠ</th>
							<th>ΠΡ/ΗΜ</th>
							<th>ΑΠ</th>
							<th>ΠΡ/ΗΜ</th>
							<th>ΑΠ</th>
							<th>ΠΡ/ΗΜ</th>
							<th>ΑΠ</th>
							<th>ΠΡ/ΗΜ</th>
							<th>ΑΠ</th>
							<th>ΠΡ/ΗΜ</th>
							<th>ΑΠ</th>
						</tr>


<?php

while ( $row = mysqli_fetch_assoc ( $result ) ) {
	
	$am = $row ["am"];
	
	$histquery = "SELECT * FROM `paperhistory` WHERE `user` = '$parent'  AND `am`= '$am' ORDER BY  `mydate` ASC ";
	
	$histresult = mysqli_query ( $link, $histquery );
	
	if (! $histresult) {
		$errorText = mysqli_error ( $link );
		echo "2 $errorText<hr>";
	}
	
	$histnum = mysqli_num_rows ( $histresult );
	
	$data = array ();
	
	while ( $histrow = mysqli_fetch_assoc ( $histresult ) ) {
		
		$mydate = $histrow ["mydate"];
		
		$index = intval ( substr ( $mydate, 4, 2 ) );
		$index -= 9;
		if ($index < 0)
			$index += 12;
		trim ( $histrow ["protok"] ) != '' ? $protok = trim ( $histrow ["protok"] ) . "/" : $protok = '';
		
		$formateddate = intval ( substr ( $mydate, 6, 2 ) ) . "-" . intval ( substr ( $mydate, 4, 2 ) );
		
		isset($data [0] [$index]) ? $data [0] [$index] .= "<br>" . $protok . $formateddate : $data [0] [$index] = $protok . $formateddate;
		isset($data [1] [$index]) ? $data [1] [$index] .= "<br>" . $histrow ["apous"] : $data [1] [$index] = $histrow ["apous"];
	}
	
	$epitheto = $row ["epitheto"];
	$onoma = $row ["onoma"];
	
	echo "<tr>\n<th style=\"text-align:left;\">$epitheto $onoma</th>";
	
	if (isset($data [0] [0]))
		echo "<td>" . $data [0] [0] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	if (isset($data [1] [0]))
		echo "<td>" . $data [1] [0] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	
	if (isset($data [0] [1]))
		echo "<td>" . $data [0] [1] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	if (isset($data [1] [1]))
		echo "<td>" . $data [1] [1] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	
	if (isset($data [0] [2]))
		echo "<td>" . $data [0] [2] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	if (isset($data [1] [2]))
		echo "<td>" . $data [1] [2] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	
	if (isset($data [0] [3]))
		echo "<td>" . $data [0] [3] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	if (isset($data [1] [3]))
		echo "<td>" . $data [1] [3] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	
	if (isset($data [0] [4]))
		echo "<td>" . $data [0] [4] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	if (isset($data [1] [4]))
		echo "<td>" . $data [1] [4] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	
	if (isset($data [0] [5]))
		echo "<td>" . $data [0] [5] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	if (isset($data [1] [5]))
		echo "<td>" . $data [1] [5] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	
	if (isset($data [0] [6]))
		echo "<td>" . $data [0] [6] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	if (isset($data [1] [6]))
		echo "<td>" . $data [1] [6] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	
	if (isset($data [0] [7]))
		echo "<td>" . $data [0] [7] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	if (isset($data [1] [7]))
		echo "<td>" . $data [1] [7] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	
	if (isset($data [0] [8]))
		echo "<td>" . $data [0] [8] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	if (isset($data [1] [8]))
		echo "<td>" . $data [1] [8] . "</td>";
	else
		echo "<td>&nbsp;</td>";
	
	echo    "</tr>";



}

mysqli_close($link);

?>
  </tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
