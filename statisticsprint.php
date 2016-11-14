<?php
require_once ('common.php');
checkUser ();

isset ( $_SESSION ['parentUser'] ) ? $parentUser = $_SESSION ['parentUser'] : $parentUser = false;
if (! $parentUser){
	checktmima();
}

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima ='';




foreach ( $_POST as $key => $value ) {
	${$key} = $value;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="el">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- Liquid Blueprint CSS -->
<link rel="stylesheet" href="{$style_prefix}blueprint/reset.css"
	type="text/css" media="screen, projection">
<link rel="stylesheet" href="{$style_prefix}blueprint/liquid.css"
	type="text/css" media="screen, projection">
<link rel="stylesheet" href="{$style_prefix}blueprint/typography.css"
	type="text/css" media="screen, projection">
<link rel="stylesheet" href="{$style_prefix}blueprint/fancy-type.css"
	type="text/css" media="screen, projection">
<!--[if IE]><link rel="stylesheet" href="../blueprint/lib/ie.css" type="text/css" media="screen, projection"><![endif]-->


<title>ΣΤΑΤΙΣΤΙΚΑ</title>

<style type="text/css">
table {
	width: 100%;
}

th, td {
	text-align: center;
	vertical-align: middle;
	border-color: #ddd;
	border-width: 1px;
	border-style: solid;
}

table {
	page-break-after: auto
}

tr {
	page-break-inside: avoid;
	page-break-after: auto
}

td {
	page-break-inside: avoid;
	page-break-after: auto
}

thead {
	display: table-header-group
}

tfoot {
	display: table-footer-group
}
</style>



</head>
<body>
	<div class="container">
		<!-- HEADER -->
		<div class="block">
			<div class="column span-24 last">

				<h2 align="right"><?php echo $parent ?></h2>
				<h3 align="right">από <?php echo $apo?$apo:'αρχή'; ?> έως <?php echo $eos?$eos:'σήμερα'; ?> </h3>
				<table cellpadding="0" cellspacing="0" align="center">
<?php
$fields = '';
$where = '';
$tablecols = '';

if ($apo) {
	$apoval = makemydatestamp ( $apo );
	$where .= " AND `mydate`>=$apoval ";
}
if ($eos) {
	$eosval = makemydatestamp ( $eos );
	$where .= " AND `mydate`<=$eosval ";
}
if (isset ( $fh ) || isset ( $lh ) || isset ( $mh ) || isset ( $oa ) || isset ( $da )) {
	$where .= " AND (";
}
if (isset ( $fh )) {
	$fields .= ", `fh`";
	$where .= " `fh`>0 OR ";
	$tablecols .= "<th>1Η<br>ΩΡΑ</th>";
}
if (isset ( $mh )) {
	$fields .= ", `mh`";
	$where .= "  `mh`>0 OR ";
	$tablecols .= "<th>ΕΝΔ<br>ΩΡΑ</th>";
}
if (isset ( $lh )) {
	$fields .= ", `lh`";
	$where .= " `lh`>0 OR ";
	$tablecols .= "<th>ΤΕΛ<br>ΩΡΑ</th>";
}
if (isset ( $oa )) {
	$fields .= ", `oa`";
	$where .= "  `oa`>0 OR ";
	$tablecols .= "<th>ΩΡ<br>ΑΠΟ</th>";
}
if (isset ( $da )) {
	$fields .= ", `da`";
	$where .= "  `da`>0 OR ";
	$tablecols .= "<th>ΗΜ<br>ΑΠΟ</th>";
}

if (isset ( $fh ) || isset ( $lh ) || isset ( $mh ) || isset ( $oa ) || isset ( $da )) {
	$where = substr ( $where, 0, - 3 );
	$where .= ")";
}

if ($where !== '') {
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	$query = "SELECT `student_am`, count(student_am) as rowspan FROM `students` 
            join  `apousies`
            on `students`.`user`= `apousies`.`user` and `am`=`student_am`
           where `apousies`.`user`='$parent'
            $where
            group by `student_am`
             order by  `epitheto`, `onoma`, `mydate` ";
	
	// echo $query ."<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
		echo "counterr: $errorText<hr>";
	}
	$num = mysqli_num_rows ( $result );
	$countarray = array ();
	
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$am = $row ["student_am"];
		$countarray [$am] = $row ["rowspan"];
	}
	
	// var_dump($countarray); echo "<hr>";
	
	$query = "SELECT `student_am`, `epitheto`,`onoma`,`patronimo` , `mydate` $fields FROM `students` 
            join  `apousies`
            on `students`.`user`= `apousies`.`user` and `am`=`student_am`
            where `apousies`.`user`='$parent'
            $where
            order by  `epitheto`, `onoma`, `mydate` ";
	
	// echo $query ."<hr>";
	
	$result = mysqli_query ( $link, $query );
	
	if (! $result) {
		$errorText = mysqli_error ( $link );
		echo "err: $errorText<hr>";
	}
	
	$num = mysqli_num_rows ( $result );
}

echo "<thead><tr><th>Α/Α</th><th>ΕΠΙΘΕΤΟ</th><th>ΟΝΟΜΑ</th><th>ΠΑΤΡΩΝΥΜΟ</th><th>ΤΜΗΜΑ</th><th>ΗΜ/ΝΙΑ</th>$tablecols</thead>";

if ($where !== '') {
	$i = 0;
	$k = 1;
	
	while ( $i < $num ) {
		$row = mysqli_fetch_assoc ( $result );
		$data = '';
		if (isset ( $fh )) {
			$val = $row ["fh"] > 0 ? $row ["fh"] : "&nbsp;";
			$data .= "<td>$val</td>";
		}
		if (isset ( $lh )) {
			$val = $row ["lh"] > 0 ? $row ["lh"] : "&nbsp;";
			$data .= "<td>$val</td>";
		}
		if (isset ( $mh )) {
			$val = $row ["mh"] > 0 ? $row ["mh"] : "&nbsp;";
			$data .= "<td>$val</td>";
		}
		if (isset ( $oa )) {
			$val = $row ["oa"] > 0 ? $row ["oa"] : "&nbsp;";
			$data .= "<td>$val</td>";
		}
		if (isset ( $da )) {
			$val = $row ["da"] > 0 ? $row ["da"] : "&nbsp;";
			$data .= "<td>$val</td>";
		}
		$am = $row ["student_am"];
		$tmimata_array = gettmimata4student ( $parent, $am );
		$tmimachek = false;
		$mytmima = '';
		foreach ( $tmimata_array as $key => $value ) {
			$mytmima .= " $value ,";
			if (! isset($_SESSION['tmima']) || $value == $tmima){
				$tmimachek = true;
			}
		}
		$mytmima = substr ( $mytmima, 0, - 1 );
		
		$epitheto = $row ["epitheto"];
		$onoma = $row ["onoma"];
		$patronimo = $row ["patronimo"];
		$mydate = ( string ) $row ["mydate"];
		$date2print = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
		$rowspan = $countarray [$am];
		if ($rowspan > 1)
			$epitheto = "(<b>$rowspan</b>) $epitheto";
		
		if ($tmimachek)	{
		echo "<tr><td rowspan=$rowspan >$k</td><td  rowspan=$rowspan style='text-align:left'>$epitheto</td><td  rowspan=$rowspan style='text-align:left'>$onoma</td><td  rowspan=$rowspan style='text-align:left'>$patronimo</td><td  rowspan=$rowspan >$mytmima</td><td>$date2print</td>$data</tr>";
		
		if ($rowspan > 1) {
			for($y = $i + 1; $y < $i + $rowspan; $y ++) {
				$row = mysqli_fetch_assoc ( $result );
				$data = '';
				if (isset ( $fh )) {
					$val = $row ["fh"] > 0 ? $row ["fh"] : "&nbsp;";
					$data .= "<td>$val</td>";
				}
				if (isset ( $lh )) {
					$val = $row ["lh"] > 0 ? $row ["lh"] : "&nbsp;";
					$data .= "<td>$val</td>";
				}
				if (isset ( $mh )) {
					$val = $row ["mh"] > 0 ? $row ["mh"] : "&nbsp;";
					$data .= "<td>$val</td>";
				}
				if (isset ( $oa )) {
					$val = $row ["oa"] > 0 ? $row ["oa"] : "&nbsp;";
					$data .= "<td>$val</td>";
				}
				if (isset ( $da )) {
					$val = $row ["da"] > 0 ? $row ["da"] : "&nbsp;";
					$data .= "<td>$val</td>";
				}
				$mydate = ( string ) $row ["mydate"];
				$date2print = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
				echo "<tr><td>$date2print</td>$data</tr>";
			}
		}
		}
		$i += $rowspan;
		$k += 1;
	}
	
	mysqli_close ( $link );
}
?>
                    </table>
			</div>
		</div>
	</div>
</body>
</html>
