<?php
ini_set ( 'memory_limit', '64M' );

require_once ('common.php');
checkUser ();
// checktmima();

$user = $_SESSION ['userName'];
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';

$max_cells = 23960;


$smarty = new Smarty ();

if (isset ( $_POST ["extention"] ))
	$extention = $_POST ["extention"];
	
	// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$query = "SELECT `email` FROM `users` WHERE `username` = '$parent' ;";
$result = mysqli_query ( $link, $query );
$row = mysqli_fetch_assoc ( $result );
$email = $row ["email"];

if (isset ( $_POST ["delete"] )) {
	
	begin ();
	$errorcheck = false;
	
	$query = "DELETE  FROM `tmimata` WHERE `username` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	$query = "DELETE  FROM `students` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	$query = "DELETE  FROM `apousies` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	$query = "DELETE  FROM `paperhistory` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	$query = "DELETE  FROM `apousies_pre` WHERE `user` = '$parent'  ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	$query = "DELETE  FROM `parameters` WHERE `user` = '$parent'  ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	$query = "DELETE  FROM `dikaiologisi` WHERE `user` = '$parent'  ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	$query = "DELETE  FROM `studentstmimata` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	
	if ($errorcheck == true) {
		rollback ();
	} else {
		commit ();
	}
}

if (isset ( $_POST ["send"] ) || isset ( $_POST ["sendmail"] )) {
	
	$_SESSION ["havechanges"] = false;
	
	if ($extention == "sql") {
		
		// //////////////////////////τμήματα/////////////////////////////////////////////////////////
		$query = "SELECT * FROM `tmimata` WHERE `username` = '$parent' ORDER BY `type`,`tmima` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$sqltmimata = '';
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$sqltmimata .= "( '" . $row ["tmima"] . "',";
			$sqltmimata .= "'" . $row ["type"] . "',";
			$sqltmimata .= "'" . $row ["lastselect"] . "',";
			$sqltmimata .= "'_usr_')";
			$i < $num - 1 ? $sqltmimata .= ",\r\n" : $sqltmimata .= ";\r\n";
			$i ++;
		}
		
		if ($sqltmimata)
			$sqltmimata = "INSERT INTO `tmimata` (`tmima`, `type`, `lastselect`, `username`) VALUES\r\n" . $sqltmimata;
			
			// //////////////////////////μαθητες/////////////////////////////////////////////////////////
			// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		$query = "SELECT * FROM `students` WHERE `user` = '$parent' ORDER BY  `epitheto`, `onoma` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$sqlstudents = '';
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$sqlstudents .= "( '" . $row ["am"] . "',";
			$sqlstudents .= "'" . $row ["epitheto"] . "',";
			$sqlstudents .= "'" . $row ["onoma"] . "',";
			$sqlstudents .= "'" . $row ["patronimo"] . "',";
			$sqlstudents .= "'" . $row ["ep_kidemona"] . "',";
			$sqlstudents .= "'" . $row ["on_kidemona"] . "',";
			$sqlstudents .= "'" . $row ["dieythinsi"] . "',";
			$sqlstudents .= "'" . $row ["tk"] . "',";
			$sqlstudents .= "'" . $row ["poli"] . "',";
			$sqlstudents .= "'" . $row ["til1"] . "',";
			$sqlstudents .= "'" . $row ["til2"] . "',";
			$sqlstudents .= "'" . $row ["email"] . "',";
			$sqlstudents .= "'" . $row ["filo"] . "',";
			$sqlstudents .= "'_usr_')";
			$i < $num - 1 ? $sqlstudents .= ",\r\n" : $sqlstudents .= ";\r\n";
			$i ++;
		}
		
		if ($sqlstudents)
			$sqlstudents = "INSERT INTO `students` (`am`, `epitheto`, `onoma`, `patronimo`, `ep_kidemona`, `on_kidemona`, `dieythinsi`, `tk`, `poli`, `til1`, `til2`, `email`, `filo`, `user`) VALUES\r\n" . $sqlstudents;
			
			// //////////////////////////apoysies////////////////////////////////////////////////////
			// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		$query = "SELECT * FROM `apousies` WHERE `user` = '$parent' ORDER BY  `mydate`, `student_am` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$sqlapousies = '';
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$sqlapousies .= "( '" . $row ["mydate"] . "',";
			$sqlapousies .= "'" . $row ["apous"] . "',";
			$sqlapousies .= "'" . $row ["dik"] . "',";
			$sqlapousies .= "'" . $row ["from"] . "',";
			$sqlapousies .= "'" . $row ["fh"] . "',";
			$sqlapousies .= "'" . $row ["mh"] . "',";
			$sqlapousies .= "'" . $row ["lh"] . "',";
			$sqlapousies .= "'" . $row ["oa"] . "',";
			$sqlapousies .= "'" . $row ["da"] . "',";
			$sqlapousies .= "'" . $row ["student_am"] . "',";
			$sqlapousies .= "'_usr_')";
			$i < $num - 1 ? $sqlapousies .= ",\r\n" : $sqlapousies .= ";\r\n";
			$i ++;
		}
		
		if ($sqlapousies)
			$sqlapousies = "INSERT INTO `apousies` (`mydate`, `apous`, `dik`, `from`, `fh`, `mh`, `lh`, `oa`, `da`, `student_am`, `user` ) VALUES \r\n" . $sqlapousies;
			
			// //////////////////////////history////////////////////////////////////////////////////
			// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		$query = "SELECT * FROM `paperhistory` WHERE `user` = '$parent' ORDER BY `aa` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$sqlhistory = '';
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			// $sqlhistory .= "( '" .$row ["aa"] . "',";
			$sqlhistory .= "( '" . $row ["protok"] . "',";
			$sqlhistory .= "'" . $row ["mydate"] . "',";
			$sqlhistory .= "'" . $row ["am"] . "',";
			$sqlhistory .= "'" . $row ["apous"] . "',";
			$sqlhistory .= "'_usr_')";
			$i < $num - 1 ? $sqlhistory .= ",\r\n" : $sqlhistory .= ";\r\n";
			$i ++;
		}
		
		if ($sqlhistory)
			$sqlhistory = "INSERT INTO `paperhistory` (`protok`, `mydate`, `am`, `apous`, `user`) VALUES \r\n" . $sqlhistory;
			
			// //////////////////////////apoysies-πρε////////////////////////////////////////////////////
			// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		$query = "SELECT * FROM `apousies_pre` WHERE `user` = '$parent' ORDER BY  `student_am` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$sqlapousies_pre = '';
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$sqlapousies_pre .= "( '" . $row ["mydate"] . "',";
			$sqlapousies_pre .= "'" . $row ["apous"] . "',";
			$sqlapousies_pre .= "'" . $row ["daysk"] . "',";
			$sqlapousies_pre .= "'" . $row ["dik"] . "',";
			$sqlapousies_pre .= "'" . $row ["fh"] . "',";
			$sqlapousies_pre .= "'" . $row ["mh"] . "',";
			$sqlapousies_pre .= "'" . $row ["lh"] . "',";
			$sqlapousies_pre .= "'" . $row ["oa"] . "',";
			$sqlapousies_pre .= "'" . $row ["da"] . "',";
			$sqlapousies_pre .= "'" . $row ["student_am"] . "',";
			$sqlapousies_pre .= "'_usr_')";
			$i < $num - 1 ? $sqlapousies_pre .= ",\r\n" : $sqlapousies_pre .= ";\r\n";
			$i ++;
		}
		
		if ($sqlapousies_pre)
			$sqlapousies_pre = "INSERT INTO `apousies_pre` (`mydate`, `apous`, `daysk`, `dik`, `fh`, `mh`, `lh`, `oa`, `da`, `student_am`, `user`) VALUES \r\n" . $sqlapousies_pre;
			
			// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$parameters = get_all_parameters ( $parent );
		
		$sqlparameters = '';
		
		if ($parameters) {
            foreach ( $parameters as $tmi => $row ) {
                foreach ( $row as $key => $value ) {
                    $sqlparameters .= "('$tmi',";
                    $sqlparameters .= "'$key',";
                    $sqlparameters .= "'$value',";
                    $sqlparameters .= "'_usr_')";
                    $sqlparameters .= ",\r\n";
                }
            }
		}
		
		if ($sqlparameters)
			$sqlparameters = "INSERT INTO `parameters` (`tmima`, `key`, `value`, `user`) VALUES \r\n" . substr ( rtrim ( $sqlparameters ), 0, - 1 ) . ";\r\n";
			
			// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		$query = "SELECT * FROM `dikaiologisi` WHERE `user` = '$parent' ORDER BY  `am`, `mydate` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "dikaiologisi: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$sqldikaiologisi = '';
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$sqldikaiologisi .= "( '" . $row ["protokolo"] . "',";
			$sqldikaiologisi .= "'" . $row ["mydate"] . "',";
			$sqldikaiologisi .= "'" . $row ["firstday"] . "',";
			$sqldikaiologisi .= "'" . $row ["lastday"] . "',";
			$sqldikaiologisi .= "'" . $row ["countdays"] . "',";
			$sqldikaiologisi .= "'" . $row ["iat_beb"] . "',";
			$sqldikaiologisi .= "'" . $row ["am"] . "',";
			$sqldikaiologisi .= "'_usr_')";
			$i < $num - 1 ? $sqldikaiologisi .= ",\r\n" : $sqldikaiologisi .= ";\r\n";
			$i ++;
		}
		
		if ($sqldikaiologisi)
			$sqldikaiologisi = "INSERT INTO `dikaiologisi` (`protokolo`, `mydate`, `firstday`, `lastday`, `countdays`, `iat_beb`, `am`, `user`) VALUES \r\n" . substr ( rtrim ( $sqldikaiologisi ), 0, - 1 ) . ";\r\n";
			
			// //////////////////////////μαθητες-τμήματα/////////////////////////////////////////////////////////
			// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		$query = "SELECT * FROM `studentstmimata` WHERE `user` = '$parent' ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "stu-tmi $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$sqlstudentstmimata = '';
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$sqlstudentstmimata .= "( '" . $row ["student_am"] . "',";
			$sqlstudentstmimata .= "'" . $row ["tmima"] . "',";
			$sqlstudentstmimata .= "'_usr_')";
			$i < $num - 1 ? $sqlstudentstmimata .= ",\r\n" : $sqlstudentstmimata .= ";\r\n";
			$i ++;
		}
		
		if ($sqlstudentstmimata)
			$sqlstudentstmimata = "INSERT INTO `studentstmimata` (`student_am`, `tmima`, `user`) VALUES\r\n" . $sqlstudentstmimata;
		
		// #########################################################################################################
		// #########################################################################################################
	} elseif ($extention == "xlsx") { // αν το αρχείο είναι .xlsx
	                                  // #########################################################################################################
	                                  // αν το αρχείο είναι xlsx έχω αποθηκευμένο ένα αρχείο στο useful/base.xlsx γιατί δεν δουλεύει η κλάση phpExcell
	                                  // με τη συμπίεση
	                                  // #########################################################################################################
		$backupdate = date ( 'j-m-Y' );
		$filename = "$parent-$backupdate";
		
		// αποσυμπίεση του αρχείου base.xlsx σοτ φάκελο upload/$filename
		$obj = new PclZip ( "useful/base.xlsx" ); // name of zip file
		$zip_files = $obj->listContent (); // κρατάω τη λίστα αρχείων
		$obj->extract ( "upload/$filename" );
		
		// ανοίγω τα αρχεία xml και συμπληρώνω δεδομένα
		$sharedstrings = array ();
		$col_letters = array (
				"A",
				"B",
				"C",
				"D",
				"E",
				"F",
				"G",
				"H",
				"I",
				"J",
				"K",
				"L",
				"M",
				"N",
				"O",
				"P",
				"Q",
				"R",
				"S",
				"T",
				"U",
				"V",
				"W",
				"X",
				"Y",
				"Z" 
		);
		
		// ENHMEROSH TON TMHMATON
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `tmimata` WHERE `username` = '$parent' ORDER BY `type`,`tmima` ASC ;";
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$tmixml = simplexml_load_file ( "upload/$filename/xl/worksheets/sheet1.xml" );
		
		$sheetData = $tmixml->sheetData;
		// tmima
		$sheetData->row->c [0]->v = 0;
		$sheetData->row->c [0]->addAttribute ( "t", 's' );
		$sharedstrings ['tmima'] = 0;
		// type
		$sheetData->row->c [1]->v = 1;
		$sheetData->row->c [1]->addAttribute ( "t", 's' );
		$sharedstrings ['type'] = 1;
		// lastselect
		$sheetData->row->c [2]->v = 2;
		$sheetData->row->c [2]->addAttribute ( "t", 's' );
		$sharedstrings ['lastselect'] = 2;
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			$tmima = $row ["tmima"];
			$type = $row ["type"];
			$lastselect = $row ["lastselect"];
			
			$newrow = $sheetData->addchild ( "row" );
			$newrow->addAttribute ( "r", "$k" );
			$newrow->addAttribute ( "spans", "1:3" );
			
			$col_data = array (
					$tmima,
					$type,
					$lastselect 
			);
			
			$newrow = $sheetData->addchild ( "row" );
			$newrow->addAttribute ( "r", "$k" );
			$newrow->addAttribute ( "spans", "1:15" );
			
			for($x = 0; $x < count ( $col_data ); $x ++) {
				$val = $col_data [$x];
				if (is_numeric ( $val )) {
					$my = $val;
					$flag = "n";
				} else {
					if (isset ( $sharedstrings [$val] )) {
						$my = $sharedstrings [$val];
					} else {
						$myindex = count ( $sharedstrings );
						$sharedstrings [$val] = $myindex;
						$my = $myindex;
					}
					$flag = "s";
				}
				$newc = $newrow->addchild ( "c" );
				$col_letter = $col_letters [$x] . $k;
				$newc->addAttribute ( "r", $col_letter );
				$newc->addAttribute ( "s", "0" );
				$newc->addAttribute ( "t", $flag );
				$newc->addchild ( "v", $my );
			}
			$i ++;
		}
		
		file_put_contents ( "upload/$filename/xl/worksheets/sheet1.xml", $tmixml->asXML () );
		
		// ################################################
		// ENHMEROSH TON ΜΑΘΗΤΩΝ
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `students` WHERE `user` = '$parent' ORDER BY  `epitheto`, `onoma` ASC ;";
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$tmixml = simplexml_load_file ( "upload/$filename/xl/worksheets/sheet2.xml" );
		
		$sheetData = $tmixml->sheetData;
		
		$col_titles = array (
				"am",
				"epitheto",
				"onoma",
				"patronimo",
				"ep_kidemona",
				"on_kidemona",
				"dieythinsi",
				"tk",
				"poli",
				"til1",
				"til2",
				"email",
				"filo" 
		);
		
		// ετικέτες πρώτης γραμμής
		for($i = 0; $i < count ( $col_titles ); $i ++) {
			$val = $col_titles [$i];
			if (is_numeric ( $val )) {
				$my = $val;
				$flag = "n";
			} else {
				if (isset ( $sharedstrings [$val] )) {
					$my = $sharedstrings [$val];
				} else {
					$myindex = count ( $sharedstrings );
					$sharedstrings [$val] = $myindex;
					$my = $myindex;
				}
				$flag = "s";
			}
			$sheetData->row->c [$i]->v = $my;
			$sheetData->row->c [$i]->addAttribute ( "t", $flag );
			$sharedstrings [$val] = $my;
		}
		
		// δεδομένα
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			
			$am = $row ["am"];
			$epitheto = $row ["epitheto"];
			$onoma = $row ["onoma"];
			$patronimo = $row ["patronimo"];
			$ep_kidemona = $row ["ep_kidemona"];
			$on_kidemona = $row ["on_kidemona"];
			$dieythinsi = $row ["dieythinsi"];
			$tk = $row ["tk"];
			$poli = $row ["poli"];
			$til1 = $row ["til1"];
			$til2 = $row ["til2"];
			$email = $row ["email"];
			$filo = $row ["filo"];
			
			$col_data = array (
					$am,
					$epitheto,
					$onoma,
					$patronimo,
					$ep_kidemona,
					$on_kidemona,
					$dieythinsi,
					$tk,
					$poli,
					$til1,
					$til2,
					$email,
					$filo 
			);
			
			$newrow = $sheetData->addchild ( "row" );
			$newrow->addAttribute ( "r", "$k" );
			$newrow->addAttribute ( "spans", "1:16" );
			
			for($x = 0; $x < count ( $col_data ); $x ++) {
				$val = $col_data [$x];
				if (is_numeric ( $val )) {
					$my = $val;
					$flag = "n";
				} else {
					if (isset ( $sharedstrings [$val] )) {
						$my = $sharedstrings [$val];
					} else {
						$myindex = count ( $sharedstrings );
						$sharedstrings [$val] = $myindex;
						$my = $myindex;
					}
					$flag = "s";
				}
				$newc = $newrow->addchild ( "c" );
				$col_letter = $col_letters [$x] . $k;
				$newc->addAttribute ( "r", $col_letter );
				$newc->addAttribute ( "s", "0" );
				$newc->addAttribute ( "t", $flag );
				$newc->addchild ( "v", $my );
			}
			$i ++;
		}
		
		// ενημέρωση του αρχείου
		file_put_contents ( "upload/$filename/xl/worksheets/sheet2.xml", $tmixml->asXML () );
		
		// ################################################
		// ENHMEROSH TON απουσιων
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `apousies` WHERE `user` = '$parent' ORDER BY  `mydate`, `student_am` ASC ;";
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$tmixml = simplexml_load_file ( "upload/$filename/xl/worksheets/sheet3.xml" );
		
		$sheetData = $tmixml->sheetData;
		
		$col_titles = array (
				"mydate",
				"apous",
				"dik",
				"from",
				"fh",
				"mh",
				"lh",
				"oa",
				"da",
				"student_am" 
		);
		
		for($i = 0; $i < count ( $col_titles ); $i ++) {
			$val = $col_titles [$i];
			if (is_numeric ( $val )) {
				$my = $val;
				$flag = "n";
			} else {
				if (isset ( $sharedstrings [$val] )) {
					$my = $sharedstrings [$val];
				} else {
					$myindex = count ( $sharedstrings );
					$sharedstrings [$val] = $myindex;
					$my = $myindex;
				}
				$flag = "s";
			}
			$sheetData->row->c [$i]->v = $my;
			$sheetData->row->c [$i]->addAttribute ( "t", $flag );
			$sharedstrings [$val] = $my;
		}
		
		// ####################################
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			
			$mydate = $row ["mydate"];
			$formateddate = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
			$apous = "'" . $row ["apous"];
			$dik = $row ["dik"];
			$from = $row ["from"];
			$fh = $row ["fh"];
			$mh = $row ["mh"];
			$lh = $row ["lh"];
			$oa = $row ["oa"];
			$da = $row ["da"];
			$student_am = $row ["student_am"];
			
			$col_data = array (
					$formateddate,
					$apous,
					$dik,
					$from,
					$fh,
					$mh,
					$lh,
					$oa,
					$da,
					$student_am 
			);
			
			$newrow = $sheetData->addchild ( "row" );
			$newrow->addAttribute ( "r", "$k" );
			$newrow->addAttribute ( "spans", "1:13" );
			
			for($x = 0; $x < count ( $col_data ); $x ++) {
				$val = $col_data [$x];
				if (is_numeric ( $val )) {
					$my = $val;
					$flag = "n";
				} else {
					if (isset ( $sharedstrings [$val] )) {
						$my = $sharedstrings [$val];
					} else {
						$myindex = count ( $sharedstrings );
						$sharedstrings [$val] = $myindex;
						$my = $myindex;
					}
					$flag = "s";
				}
				$newc = $newrow->addchild ( "c" );
				$col_letter = $col_letters [$x] . $k;
				$newc->addAttribute ( "r", $col_letter );
				$newc->addAttribute ( "s", "0" );
				$newc->addAttribute ( "t", $flag );
				$newc->addchild ( "v", $my );
			}
			$i ++;
		}
		
		file_put_contents ( "upload/$filename/xl/worksheets/sheet3.xml", $tmixml->asXML () );
		
		// ################################################
		// ENHMEROSH TON paperhistory
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `paperhistory` WHERE `user` = '$parent' ORDER BY `aa` ASC ;";
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		mysqli_close ( $link );
		
		$tmixml = simplexml_load_file ( "upload/$filename/xl/worksheets/sheet4.xml" );
		
		$sheetData = $tmixml->sheetData;
		
		$col_titles = array (
				"protok",
				"mydate",
				"am",
				"apous" 
		);
		
		for($i = 0; $i < count ( $col_titles ); $i ++) {
			$val = $col_titles [$i];
			if (is_numeric ( $val )) {
				$my = $val;
				$flag = "n";
			} else {
				if (isset ( $sharedstrings [$val] )) {
					$my = $sharedstrings [$val];
				} else {
					$myindex = count ( $sharedstrings );
					$sharedstrings [$val] = $myindex;
					$my = $myindex;
				}
				$flag = "s";
			}
			$sheetData->row->c [$i]->v = $my;
			$sheetData->row->c [$i]->addAttribute ( "t", $flag );
			$sharedstrings [$val] = $my;
		}
		
		// ####################################
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			
			$protok = $row ["protok"];
			$mydate = $row ["mydate"];
			$formateddate = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
			$am = $row ["am"];
			$apous = $row ["apous"];
			
			$col_data = array (
					$protok,
					$formateddate,
					$am,
					$apous 
			);
			
			$newrow = $sheetData->addchild ( "row" );
			$newrow->addAttribute ( "r", "$k" );
			$newrow->addAttribute ( "spans", "1:4" );
			
			for($x = 0; $x < count ( $col_data ); $x ++) {
				$val = $col_data [$x];
				if (is_numeric ( $val )) {
					$my = $val;
					$flag = "n";
				} else {
					if (isset ( $sharedstrings [$val] )) {
						$my = $sharedstrings [$val];
					} else {
						$myindex = count ( $sharedstrings );
						$sharedstrings [$val] = $myindex;
						$my = $myindex;
					}
					$flag = "s";
				}
				$newc = $newrow->addchild ( "c" );
				$col_letter = $col_letters [$x] . $k;
				$newc->addAttribute ( "r", $col_letter );
				$newc->addAttribute ( "s", "0" );
				$newc->addAttribute ( "t", $flag );
				$newc->addchild ( "v", $my );
			}
			$i ++;
		}
		
		file_put_contents ( "upload/$filename/xl/worksheets/sheet4.xml", $tmixml->asXML () );
		
		// ################################################
		// ENHMEROSH TON απουσιων_pre
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `apousies_pre` WHERE `user` = '$parent' ORDER BY  `student_am` ASC ;";
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		mysqli_close ( $link );
		
		$tmixml = simplexml_load_file ( "upload/$filename/xl/worksheets/sheet5.xml" );
		
		$sheetData = $tmixml->sheetData;
		
		$col_titles = array (
				"mydate",
				"apous",
				"daysk",
				"dik",
				"fh",
				"mh",
				"lh",
				"oa",
				"da",
				"student_am" 
		);
		
		for($i = 0; $i < count ( $col_titles ); $i ++) {
			$val = $col_titles [$i];
			if (is_numeric ( $val )) {
				$my = $val;
				$flag = "n";
			} else {
				if (isset ( $sharedstrings [$val] )) {
					$my = $sharedstrings [$val];
				} else {
					$myindex = count ( $sharedstrings );
					$sharedstrings [$val] = $myindex;
					$my = $myindex;
				}
				$flag = "s";
			}
			$sheetData->row->c [$i]->v = $my;
			$sheetData->row->c [$i]->addAttribute ( "t", $flag );
			$sharedstrings [$val] = $my;
		}
		
		// ####################################
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			
			$mydate = $row ["mydate"];
			$formateddate = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
			$apous = "'" . $row ["apous"];
			$daysk = $row ["daysk"];
			$dik = "'" . $row ["dik"];
			$fh = $row ["fh"];
			$mh = $row ["mh"];
			$lh = $row ["lh"];
			$oa = $row ["oa"];
			$da = $row ["da"];
			$student_am = $row ["student_am"];
			
			$col_data = array (
					$formateddate,
					$apous,
					$daysk,
					$dik,
					$fh,
					$mh,
					$lh,
					$oa,
					$da,
					$student_am 
			);
			
			$newrow = $sheetData->addchild ( "row" );
			$newrow->addAttribute ( "r", "$k" );
			$newrow->addAttribute ( "spans", "1:14" );
			
			for($x = 0; $x < count ( $col_data ); $x ++) {
				$val = $col_data [$x];
				if (is_numeric ( $val )) {
					$my = $val;
					$flag = "n";
				} else {
					if (isset ( $sharedstrings [$val] )) {
						$my = $sharedstrings [$val];
					} else {
						$myindex = count ( $sharedstrings );
						$sharedstrings [$val] = $myindex;
						$my = $myindex;
					}
					$flag = "s";
				}
				$newc = $newrow->addchild ( "c" );
				$col_letter = $col_letters [$x] . $k;
				$newc->addAttribute ( "r", $col_letter );
				$newc->addAttribute ( "s", "0" );
				$newc->addAttribute ( "t", $flag );
				$newc->addchild ( "v", $my );
			}
			$i ++;
		}
		
		file_put_contents ( "upload/$filename/xl/worksheets/sheet5.xml", $tmixml->asXML () );
		
		// ################################################
		// ENHMEROSH TON parameters
		$parameters = get_all_parameters ( $parent );
		
		$tmixml = simplexml_load_file ( "upload/$filename/xl/worksheets/sheet6.xml" );
		
		$sheetData = $tmixml->sheetData;
		
		$col_titles = array (
				"tmima",
				"key",
				"value" 
		);
		
		for($i = 0; $i < count ( $col_titles ); $i ++) {
			$val = $col_titles [$i];
			if (is_numeric ( $val )) {
				$my = $val;
				$flag = "n";
			} else {
				if (isset ( $sharedstrings [$val] )) {
					$my = $sharedstrings [$val];
				} else {
					$myindex = count ( $sharedstrings );
					$sharedstrings [$val] = $myindex;
					$my = $myindex;
				}
				$flag = "s";
			}
			$sheetData->row->c [$i]->v = $my;
			$sheetData->row->c [$i]->addAttribute ( "t", $flag );
			$sharedstrings [$val] = $my;
		}
		
		// ####################################
		$k = 2;
		foreach ( $parameters as $tmi => $row ) {
			foreach ( $row as $key => $value ) {
				$newrow = $sheetData->addchild ( "row" );
				$newrow->addAttribute ( "r", "$k" );
				$newrow->addAttribute ( "spans", "1:3" );
				$col_data = array (
						$tmi,
						$key,
						$value 
				);
				for($x = 0; $x < count ( $col_data ); $x ++) {
					$val = $col_data [$x];
					if (is_numeric ( $val )) {
						$my = $val;
						$flag = "n";
					} else {
						if (isset ( $sharedstrings [$val] )) {
							$my = $sharedstrings [$val];
						} else {
							$myindex = count ( $sharedstrings );
							$sharedstrings [$val] = $myindex;
							$my = $myindex;
						}
						$flag = "s";
					}
					$newc = $newrow->addchild ( "c" );
					$col_letter = $col_letters [$x] . $k;
					$newc->addAttribute ( "r", $col_letter );
					$newc->addAttribute ( "s", "0" );
					$newc->addAttribute ( "t", $flag );
					$newc->addchild ( "v", $my );
				}
				$k ++;
			}
		}
		
		file_put_contents ( "upload/$filename/xl/worksheets/sheet6.xml", $tmixml->asXML () );
		
		// ################################################
		// ENHMEROSH TON dikaiologisi
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `dikaiologisi` WHERE `user` = '$parent' ORDER BY  `am`, `mydate` ASC ;";
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "dikaiologisi: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		mysqli_close ( $link );
		
		$tmixml = simplexml_load_file ( "upload/$filename/xl/worksheets/sheet7.xml" );
		
		$sheetData = $tmixml->sheetData;
		
		$col_titles = array (
				"myprotokolo",
				"mydate",
				"firstday",
				"lastday",
				"countdays",
				"iat_beb",
				"am" 
		);
		
		for($i = 0; $i < count ( $col_titles ); $i ++) {
			$val = $col_titles [$i];
			if (is_numeric ( $val )) {
				$my = $val;
				$flag = "n";
			} else {
				if (isset ( $sharedstrings [$val] )) {
					$my = $sharedstrings [$val];
				} else {
					$myindex = count ( $sharedstrings );
					$sharedstrings [$val] = $myindex;
					$my = $myindex;
				}
				$flag = "s";
			}
			$sheetData->row->c [$i]->v = $my;
			$sheetData->row->c [$i]->addAttribute ( "t", $flag );
			$sharedstrings [$val] = $my;
		}
		
		// ####################################
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			
			$protokolo = $row ["protokolo"];
			$mydate = $row ["mydate"];
			$formateddate = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
			$firstday = $row ["firstday"];
			$lastday = $row ["lastday"];
			$countdays = $row ["countdays"];
			$iat_beb = $row ["iat_beb"];
			$am = $row ["am"];
			
			$col_data = array (
					$protokolo,
					$formateddate,
					$firstday,
					$lastday,
					$countdays,
					$iat_beb,
					$am 
			);
			
			$newrow = $sheetData->addchild ( "row" );
			$newrow->addAttribute ( "r", "$k" );
			$newrow->addAttribute ( "spans", "1:7" );
			
			for($x = 0; $x < count ( $col_data ); $x ++) {
				$val = $col_data [$x];
				if (is_numeric ( $val )) {
					$my = $val;
					$flag = "n";
				} else {
					if (isset ( $sharedstrings [$val] )) {
						$my = $sharedstrings [$val];
					} else {
						$myindex = count ( $sharedstrings );
						$sharedstrings [$val] = $myindex;
						$my = $myindex;
					}
					$flag = "s";
				}
				$newc = $newrow->addchild ( "c" );
				$col_letter = $col_letters [$x] . $k;
				$newc->addAttribute ( "r", $col_letter );
				$newc->addAttribute ( "s", "0" );
				$newc->addAttribute ( "t", $flag );
				$newc->addchild ( "v", $my );
			}
			$i ++;
		}
		
		file_put_contents ( "upload/$filename/xl/worksheets/sheet7.xml", $tmixml->asXML () );
		
		// ################################################
		// ENHMEROSH TON studentstmimata
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `studentstmimata` WHERE `user` = '$parent' ;";
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "studentstmimata: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		mysqli_close ( $link );
		
		$tmixml = simplexml_load_file ( "upload/$filename/xl/worksheets/sheet8.xml" );
		
		$sheetData = $tmixml->sheetData;
		
		$col_titles = array (
				"student_am",
				"tmima" 
		);
		
		for($i = 0; $i < count ( $col_titles ); $i ++) {
			$val = $col_titles [$i];
			if (is_numeric ( $val )) {
				$my = $val;
				$flag = "n";
			} else {
				if (isset ( $sharedstrings [$val] )) {
					$my = $sharedstrings [$val];
				} else {
					$myindex = count ( $sharedstrings );
					$sharedstrings [$val] = $myindex;
					$my = $myindex;
				}
				$flag = "s";
			}
			$sheetData->row->c [$i]->v = $my;
			$sheetData->row->c [$i]->addAttribute ( "t", $flag );
			$sharedstrings [$val] = $my;
		}
		
		// ####################################
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			
			$am = $row ["student_am"];
			$mytmima = $row ["tmima"];
			
			$col_data = array (
					$am,
					$mytmima 
			);
			
			$newrow = $sheetData->addchild ( "row" );
			$newrow->addAttribute ( "r", "$k" );
			$newrow->addAttribute ( "spans", "1:7" );
			
			for($x = 0; $x < count ( $col_data ); $x ++) {
				$val = $col_data [$x];
				if (is_numeric ( $val )) {
					$my = $val;
					$flag = "n";
				} else {
					if (isset ( $sharedstrings [$val] )) {
						$my = $sharedstrings [$val];
					} else {
						$myindex = count ( $sharedstrings );
						$sharedstrings [$val] = $myindex;
						$my = $myindex;
					}
					$flag = "s";
				}
				$newc = $newrow->addchild ( "c" );
				$col_letter = $col_letters [$x] . $k;
				$newc->addAttribute ( "r", $col_letter );
				$newc->addAttribute ( "s", "0" );
				$newc->addAttribute ( "t", $flag );
				$newc->addchild ( "v", $my );
			}
			$i ++;
		}
		
		file_put_contents ( "upload/$filename/xl/worksheets/sheet8.xml", $tmixml->asXML () );
		
		// #########################################################
		// δημιουργία του xml με τα shared strings
		$strxml = simplexml_load_file ( "upload/$filename/xl/sharedStrings.xml" );
		
		$unique = count ( $sharedstrings );
		// echo "$unique<hr>";
		$strxml->attributes ()->uniqueCount = $unique;
		
		foreach ( $sharedstrings as $key => $str ) {
			$si = $strxml->addchild ( "si" );
			$escapedkey = htmlspecialchars ( $key );
			$si->addchild ( "t", $escapedkey );
		}
		file_put_contents ( "upload/$filename/xl/sharedStrings.xml", $strxml->asXML () );
		
		// συμπίεση του αρχείου
		$obj = new PclZip ( "upload/$filename.xlsx" ); // name of zip file
		$files_to_zip = array ();
		foreach ( $zip_files as $zip_file ) {
			$files_to_zip [] = "upload/$filename/" . $zip_file ['filename'];
		}
		$obj->create ( $files_to_zip, "", "upload/$filename/" );
		
		// διαγραφή όσων δεν χρειάζονται
		rmdir_r ( "upload/$filename" );
		
		// ######################################################################
	} elseif ($extention == 'csv') {
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// τμήμα
		$query = "SELECT CONCAT_WS('\',\'', `tmima`,`type`,`lastselect`) as `data` 
                FROM `tmimata`  
                WHERE `username` = '$parent'  
                ORDER BY `type`,`tmima` ASC ;";
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "tmimata cvs: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		$fileContent = "tmimata\r\n";
		$fileContent .= "'tmima','type','lastselect'\r\n";
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$fileContent .= "'" . $row ["data"] . "'\r\n";
		}
		
		// students
		$query = "SELECT CONCAT_WS('\',\'', `am`,`epitheto`,`onoma`,`patronimo`,`ep_kidemona`,`on_kidemona`,`dieythinsi`,`tk`,`poli`,`til1`,`til2`,`email`,`filo`) as `data` 
                FROM `students`  
                WHERE `user` = '$parent'
                ORDER BY  `epitheto`, `onoma` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "students cvs: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		$fileContent .= "\r\nstudents\r\n";
		$fileContent .= "'am','epitheto','onoma','patronimo','ep_kidemona','on_kidemona','dieythinsi','tk','poli','til1','til2','email','filo'\r\n";
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$fileContent .= "'" . $row ["data"] . "'\r\n";
		}
		
		// apousies
		$query = "SELECT CONCAT_WS('\',\'', DATE_FORMAT(`mydate` , '%d/%m/%Y'), `apous`,`dik`,`from`,`fh`,`mh`,`lh`,`oa`,`da`,`student_am` ) as `data` 
                FROM `apousies`  
                WHERE `user` = '$parent'
                ORDER BY  `mydate`, `student_am` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "apousies cvs: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		$fileContent .= "\r\napousies\r\n";
		$fileContent .= "'mydate','apous','dik','from','fh','mh','lh','oa','da','student_am'\r\n";
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$fileContent .= "'" . $row ["data"] . "'\r\n";
		}
		
		// paperhistory
		$query = "SELECT CONCAT_WS('\',\'', `protok`, DATE_FORMAT(`mydate` , '%d/%m/%Y'), `am`,`apous` ) as `data` 
                FROM `paperhistory`  
                WHERE `user` = '$parent'
                ORDER BY `aa` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "paperhistory cvs: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		$fileContent .= "\r\npaperhistory\r\n";
		$fileContent .= "'protok','mydate','am','apous'\r\n";
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$fileContent .= "'" . $row ["data"] . "'\r\n";
		}
		
		// apousies_pre
		$query = "SELECT CONCAT_WS('\',\'', DATE_FORMAT(`mydate` , '%d/%m/%Y'), `apous`,`daysk`,`dik`,`fh`,`mh`,`lh`,`oa`,`da`,`student_am` ) as `data` 
                 FROM `apousies_pre`  
                WHERE `user` = '$parent'
                ORDER BY  `mydate`, `student_am` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "apousies_pre cvs: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		$fileContent .= "\r\napousies_pre\r\n";
		$fileContent .= "'mydate','apous','daysk','dik','fh','mh','lh','oa','da','student_am'\r\n";
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$fileContent .= "'" . $row ["data"] . "'\r\n";
		}
		
		// parameters
		$query = "SELECT CONCAT_WS('\',\'', `tmima`,`key`,`value` ) as `data` 
                FROM `parameters`  
                WHERE `user` = '$parent'
                ORDER BY  `tmima`, `key` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "parameters cvs: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		$fileContent .= "\r\nparameters\r\n";
		$fileContent .= "'tmima','key','value'\r\n";
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$fileContent .= "'" . $row ["data"] . "'\r\n";
		}
		
		// dikaiologisi
		$query = "SELECT CONCAT_WS('\',\'', `protokolo`,DATE_FORMAT(`mydate` , '%d/%m/%Y'),`firstday`,`lastday`,`countdays`,`iat_beb`,`am` ) as `data` 
                FROM `dikaiologisi`  
                WHERE `user` = '$parent'
                ORDER BY  `am`, `mydate`  ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "dikaiologisi cvs: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		$fileContent .= "\r\ndikaiologisi\r\n";
		$fileContent .= "'protokolo','mydate','firstday','lastday','countdays','iat_beb','am'\r\n";
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$fileContent .= "'" . $row ["data"] . "'\r\n";
		}
		
		// studentstmimata
		$query = "SELECT CONCAT_WS('\',\'', `student_am`,`tmima`) as `data` 
                FROM `studentstmimata`  
                WHERE `user` = '$parent' ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "studentstmimata cvs: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		$fileContent .= "\r\nstudentstmimata\r\n";
		$fileContent .= "'student_am','tmima'\r\n";
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$fileContent .= "'" . $row ["data"] . "'\r\n";
		}
		
		// echo "<pre>$fileContent</pre><hr>";
		$backupdate = date ( 'j-m-Y' );
		$filename = "$parent-$backupdate.csv";
		
		$pfile = fopen ( "upload/$filename", "w" );
		fwrite ( $pfile, $fileContent );
		fclose ( $pfile );
	} else { // .xls
		
		$query = "select sum(cells) as num from
(
select 'tmimata' as mytable ,count(*)+1 as num, 3  as fields, (count(*)+1)*3 as cells from `tmimata` where `username`='$parent'
union all
select 'students' as mytable ,count(*)+1 as num, 15  as fields, (count(*)+1)*16 as cells from `students` where `user`='$parent'
union all
select 'apousies' as mytable ,count(*)+1 as num, 13  as fields, (count(*)+1)*13 as cells from `apousies` where `user`='$parent'
union all
select 'apousies_pre' as mytable ,count(*)+1 as num, 14  as fields, (count(*)+1)*14 as cells from `apousies_pre` where `user`='$parent'
union all
select 'parameters' as mytable ,count(*)+1 as num, 3  as fields, (count(*)+1)*3 as cells from `parameters` where `user`='$parent'
union all
select 'paperhistory' as mytable ,count(*)+1 as num, 5  as fields, (count(*)+1)*5 as cells from `paperhistory` where `user`='$parent'
union all
select 'dikaiologisi' as mytable ,count(*)+1 as num, 7  as fields, (count(*)+1)*7 as cells from `dikaiologisi` where `user`='$parent'
union all
select 'studentstmimata' as mytable ,count(*)+1 as num, 2  as fields, (count(*)+1)*2 as cells from `studentstmimata` where `user`='$parent'
) t1 ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		$row = mysqli_fetch_assoc ( $result );
		$cells = $row ["num"];
		
		if ($cells > $max_cells) {
			header ( 'Location: exportdata.php?e' );
		}
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `tmimata` WHERE `username` = '$parent' ORDER BY `type`,`tmima` ASC ;";
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		mysqli_close ( $link );
		
		// #####################################################
		/**
		 * Error reporting
		 */
		error_reporting ( E_ALL );
		
		/**
		 * PHPExcel
		 */
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel ();
		
		// Set properties
		$objPHPExcel->getProperties ()->setCreator ( "Apousies" )->setLastModifiedBy ( "Apousies" )->setTitle ( "backup-$parent.xls" )->setSubject ( "" )->setDescription ( "" )->setKeywords ( "" )->setCategory ( "" );
		
		// Add some data
		$styleArray = array (
				'font' => array (
						'bold' => true 
				),
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER 
				),
				'borders' => array (
						'bottom' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN 
						),
						'right' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN 
						) 
				),
				'fill' => array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array (
								'argb' => 'DDDDDDDD' 
						) 
				) 
		);
		
		$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:C1' )->applyFromArray ( $styleArray );
		
		$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'A' )->setAutoSize ( true );
		$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setAutoSize ( true );
		$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'C' )->setAutoSize ( true );
		
		$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 0, 1 )->setValue ( "tmima" );
		$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 1, 1 )->setValue ( "type" );
		$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 2, 1 )->setValue ( "lastselect" );
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 0, $k )->setValue ( $row ["tmima"] );
			$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 1, $k )->setValue ( $row ["type"] );
			$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow ( 2, $k )->setValue ( $row ["lastselect"] );
			$i ++;
		}
		
		// Rename sheet
		$objPHPExcel->getActiveSheet ()->setTitle ( "tmimata" );
		
		// #################################################################################################################################
		
		$objWorksheet1 = $objPHPExcel->createSheet ();
		$objWorksheet1->setTitle ( 'students' );
		
		$objWorksheet1->getStyle ( 'A1:M1' )->applyFromArray ( $styleArray );
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `students` WHERE `user` = '$parent' ORDER BY  `epitheto`, `onoma` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$objWorksheet1->getColumnDimension ( 'A' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'B' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'C' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'D' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'E' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'F' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'G' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'H' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'I' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'J' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'K' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'L' )->setAutoSize ( true );
		$objWorksheet1->getColumnDimension ( 'M' )->setAutoSize ( true );
		
		$objWorksheet1->getCellByColumnAndRow ( 0, 1 )->setValue ( "am" );
		$objWorksheet1->getCellByColumnAndRow ( 1, 1 )->setValue ( "epitheto" );
		$objWorksheet1->getCellByColumnAndRow ( 2, 1 )->setValue ( "onoma" );
		$objWorksheet1->getCellByColumnAndRow ( 3, 1 )->setValue ( "patronimo" );
		$objWorksheet1->getCellByColumnAndRow ( 4, 1 )->setValue ( "ep_kidemona" );
		$objWorksheet1->getCellByColumnAndRow ( 5, 1 )->setValue ( "on_kidemona" );
		$objWorksheet1->getCellByColumnAndRow ( 6, 1 )->setValue ( "dieythinsi" );
		$objWorksheet1->getCellByColumnAndRow ( 7, 1 )->setValue ( "tk" );
		$objWorksheet1->getCellByColumnAndRow ( 8, 1 )->setValue ( "poli" );
		$objWorksheet1->getCellByColumnAndRow ( 9, 1 )->setValue ( "til1" );
		$objWorksheet1->getCellByColumnAndRow ( 10, 1 )->setValue ( "til2" );
		$objWorksheet1->getCellByColumnAndRow ( 11, 1 )->setValue ( "email" );
		$objWorksheet1->getCellByColumnAndRow ( 12, 1 )->setValue ( "filo" );
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			$objWorksheet1->getCellByColumnAndRow ( 0, $k )->setValue ( $row ["am"] );
			$objWorksheet1->getCellByColumnAndRow ( 1, $k )->setValue ( $row ["epitheto"] );
			$objWorksheet1->getCellByColumnAndRow ( 2, $k )->setValue ( $row ["onoma"] );
			$objWorksheet1->getCellByColumnAndRow ( 3, $k )->setValue ( $row ["patronimo"] );
			$objWorksheet1->getCellByColumnAndRow ( 4, $k )->setValue ( $row ["ep_kidemona"] );
			$objWorksheet1->getCellByColumnAndRow ( 5, $k )->setValue ( $row ["on_kidemona"] );
			$objWorksheet1->getCellByColumnAndRow ( 6, $k )->setValue ( $row ["dieythinsi"] );
			$objWorksheet1->getCellByColumnAndRow ( 7, $k )->setValue ( $row ["tk"] );
			$objWorksheet1->getCellByColumnAndRow ( 8, $k )->setValue ( $row ["poli"] );
			$objWorksheet1->getCellByColumnAndRow ( 9, $k )->setValue ( $row ["til1"] );
			$objWorksheet1->getCellByColumnAndRow ( 10, $k )->setValue ( $row ["til2"] );
			$objWorksheet1->getCellByColumnAndRow ( 11, $k )->setValue ( $row ["email"] );
			$objWorksheet1->getCellByColumnAndRow ( 12, $k )->setValue ( $row ["filo"] );
			$i ++;
		}
		
		// //////////////////////////apoysies////////////////////////////////////////////////////
		
		$objWorksheet2 = $objPHPExcel->createSheet ();
		$objWorksheet2->setTitle ( 'apousies' );
		
		$objWorksheet2->getStyle ( 'A1:J1' )->applyFromArray ( $styleArray );
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `apousies` WHERE `user` = '$parent' ORDER BY  `mydate`, `student_am` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$objWorksheet2->getColumnDimension ( 'A' )->setAutoSize ( true );
		$objWorksheet2->getColumnDimension ( 'B' )->setAutoSize ( true );
		$objWorksheet2->getColumnDimension ( 'C' )->setAutoSize ( true );
		$objWorksheet2->getColumnDimension ( 'D' )->setAutoSize ( true );
		$objWorksheet2->getColumnDimension ( 'E' )->setAutoSize ( true );
		$objWorksheet2->getColumnDimension ( 'F' )->setAutoSize ( true );
		$objWorksheet2->getColumnDimension ( 'G' )->setAutoSize ( true );
		$objWorksheet2->getColumnDimension ( 'H' )->setAutoSize ( true );
		$objWorksheet2->getColumnDimension ( 'I' )->setAutoSize ( true );
		$objWorksheet2->getColumnDimension ( 'J' )->setAutoSize ( true );
		
		$objWorksheet2->getCellByColumnAndRow ( 0, 1 )->setValue ( "mydate" );
		$objWorksheet2->getCellByColumnAndRow ( 1, 1 )->setValue ( "apous" );
		$objWorksheet2->getCellByColumnAndRow ( 2, 1 )->setValue ( "dik" );
		$objWorksheet2->getCellByColumnAndRow ( 3, 1 )->setValue ( "from" );
		$objWorksheet2->getCellByColumnAndRow ( 4, 1 )->setValue ( "fh" );
		$objWorksheet2->getCellByColumnAndRow ( 5, 1 )->setValue ( "mh" );
		$objWorksheet2->getCellByColumnAndRow ( 6, 1 )->setValue ( "lh" );
		$objWorksheet2->getCellByColumnAndRow ( 7, 1 )->setValue ( "oa" );
		$objWorksheet2->getCellByColumnAndRow ( 8, 1 )->setValue ( "da" );
		$objWorksheet2->getCellByColumnAndRow ( 9, 1 )->setValue ( "student_am" );
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			$mydate = $row ["mydate"];
			$formateddate = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
			$objWorksheet2->getCellByColumnAndRow ( 0, $k )->setValue ( $formateddate );
			$objWorksheet2->getCellByColumnAndRow ( 1, $k )->setValueExplicit ( $row ["apous"], PHPExcel_Cell_DataType::TYPE_STRING );
			$objWorksheet2->getCellByColumnAndRow ( 2, $k )->setValue ( $row ["dik"] );
			$objWorksheet2->getCellByColumnAndRow ( 3, $k )->setValue ( $row ["from"] );
			$objWorksheet2->getCellByColumnAndRow ( 4, $k )->setValue ( $row ["fh"] );
			$objWorksheet2->getCellByColumnAndRow ( 5, $k )->setValue ( $row ["mh"] );
			$objWorksheet2->getCellByColumnAndRow ( 6, $k )->setValue ( $row ["lh"] );
			$objWorksheet2->getCellByColumnAndRow ( 7, $k )->setValue ( $row ["oa"] );
			$objWorksheet2->getCellByColumnAndRow ( 8, $k )->setValue ( $row ["da"] );
			$objWorksheet2->getCellByColumnAndRow ( 9, $k )->setValue ( $row ["student_am"] );
			$i ++;
		}
		
		// //////////////////////////history////////////////////////////////////////////////////
		
		$objWorksheet3 = $objPHPExcel->createSheet ();
		$objWorksheet3->setTitle ( 'paperhistory' );
		
		$objWorksheet3->getStyle ( 'A1:D1' )->applyFromArray ( $styleArray );
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `paperhistory` WHERE `user` = '$parent' ORDER BY `aa` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$objWorksheet3->getColumnDimension ( 'A' )->setAutoSize ( true );
		$objWorksheet3->getColumnDimension ( 'B' )->setAutoSize ( true );
		$objWorksheet3->getColumnDimension ( 'C' )->setAutoSize ( true );
		$objWorksheet3->getColumnDimension ( 'D' )->setAutoSize ( true );
		
		$objWorksheet3->getCellByColumnAndRow ( 0, 1 )->setValue ( "protok" );
		$objWorksheet3->getCellByColumnAndRow ( 1, 1 )->setValue ( "mydate" );
		$objWorksheet3->getCellByColumnAndRow ( 2, 1 )->setValue ( "am" );
		$objWorksheet3->getCellByColumnAndRow ( 3, 1 )->setValue ( "apous" );
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			$objWorksheet3->getCellByColumnAndRow ( 0, $k )->setValue ( $row ["protok"] );
			$mydate = $row ["mydate"];
			$formateddate = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
			$objWorksheet3->getCellByColumnAndRow ( 1, $k )->setValue ( $formateddate );
			$objWorksheet3->getCellByColumnAndRow ( 2, $k )->setValue ( $row ["am"] );
			$objWorksheet3->getCellByColumnAndRow ( 3, $k )->setValue ( $row ["apous"] );
			$i ++;
		}
		
		// //////////////////////////apoysies////////////////////////////////////////////////////
		
		$objWorksheet4 = $objPHPExcel->createSheet ();
		$objWorksheet4->setTitle ( 'apousies_pre' );
		
		$objWorksheet4->getStyle ( 'A1:J1' )->applyFromArray ( $styleArray );
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `apousies_pre` WHERE `user` = '$parent' ORDER BY  `student_am` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "1 $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$objWorksheet4->getColumnDimension ( 'A' )->setAutoSize ( true );
		$objWorksheet4->getColumnDimension ( 'B' )->setAutoSize ( true );
		$objWorksheet4->getColumnDimension ( 'C' )->setAutoSize ( true );
		$objWorksheet4->getColumnDimension ( 'D' )->setAutoSize ( true );
		$objWorksheet4->getColumnDimension ( 'E' )->setAutoSize ( true );
		$objWorksheet4->getColumnDimension ( 'F' )->setAutoSize ( true );
		$objWorksheet4->getColumnDimension ( 'G' )->setAutoSize ( true );
		$objWorksheet4->getColumnDimension ( 'H' )->setAutoSize ( true );
		$objWorksheet4->getColumnDimension ( 'I' )->setAutoSize ( true );
		$objWorksheet4->getColumnDimension ( 'J' )->setAutoSize ( true );
		
		$objWorksheet4->getCellByColumnAndRow ( 0, 1 )->setValue ( "mydate" );
		$objWorksheet4->getCellByColumnAndRow ( 1, 1 )->setValue ( "apous" );
		$objWorksheet4->getCellByColumnAndRow ( 2, 1 )->setValue ( "daysk" );
		$objWorksheet4->getCellByColumnAndRow ( 3, 1 )->setValue ( "dik" );
		$objWorksheet4->getCellByColumnAndRow ( 4, 1 )->setValue ( "fh" );
		$objWorksheet4->getCellByColumnAndRow ( 5, 1 )->setValue ( "mh" );
		$objWorksheet4->getCellByColumnAndRow ( 6, 1 )->setValue ( "lh" );
		$objWorksheet4->getCellByColumnAndRow ( 7, 1 )->setValue ( "oa" );
		$objWorksheet4->getCellByColumnAndRow ( 8, 1 )->setValue ( "da" );
		$objWorksheet4->getCellByColumnAndRow ( 9, 1 )->setValue ( "student_am" );
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			$mydate = $row ["mydate"];
			$formateddate = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
			$objWorksheet4->getCellByColumnAndRow ( 0, $k )->setValue ( $formateddate );
			$objWorksheet4->getCellByColumnAndRow ( 1, $k )->setValueExplicit ( $row ["apous"], PHPExcel_Cell_DataType::TYPE_STRING );
			$objWorksheet4->getCellByColumnAndRow ( 2, $k )->setValue ( $row ["daysk"] );
			$objWorksheet4->getCellByColumnAndRow ( 3, $k )->setValueExplicit ( $row ["dik"], PHPExcel_Cell_DataType::TYPE_STRING );
			$objWorksheet4->getCellByColumnAndRow ( 4, $k )->setValue ( $row ["fh"] );
			$objWorksheet4->getCellByColumnAndRow ( 5, $k )->setValue ( $row ["mh"] );
			$objWorksheet4->getCellByColumnAndRow ( 6, $k )->setValue ( $row ["lh"] );
			$objWorksheet4->getCellByColumnAndRow ( 7, $k )->setValue ( $row ["oa"] );
			$objWorksheet4->getCellByColumnAndRow ( 8, $k )->setValue ( $row ["da"] );
			$objWorksheet4->getCellByColumnAndRow ( 9, $k )->setValue ( $row ["student_am"] );
			$i ++;
		}
		
		// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$objWorksheet5 = $objPHPExcel->createSheet ();
		$objWorksheet5->setTitle ( 'parameters' );
		
		$objWorksheet5->getStyle ( 'A1:C1' )->applyFromArray ( $styleArray );
		
		$parameters = get_all_parameters ( $parent );
		
		$objWorksheet5->getColumnDimension ( 'A' )->setAutoSize ( true );
		$objWorksheet5->getColumnDimension ( 'B' )->setAutoSize ( true );
		$objWorksheet5->getColumnDimension ( 'C' )->setAutoSize ( true );
		
		$objWorksheet5->getCellByColumnAndRow ( 0, 1 )->setValue ( "tmima" );
		$objWorksheet5->getCellByColumnAndRow ( 1, 1 )->setValue ( "key" );
		$objWorksheet5->getCellByColumnAndRow ( 2, 1 )->setValue ( "value" );
		
		$k = 2;
		
		foreach ( $parameters as $tmi => $row ) {
			foreach ( $row as $key => $value ) {
				
				$objWorksheet5->getCellByColumnAndRow ( 0, $k )->setValue ( $tmi );
				$objWorksheet5->getCellByColumnAndRow ( 1, $k )->setValue ( $key );
				$objWorksheet5->getCellByColumnAndRow ( 2, $k )->setValue ( $value );
				$k ++;
			}
		}
		
		// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$objWorksheet6 = $objPHPExcel->createSheet ();
		$objWorksheet6->setTitle ( 'dikaiologisi' );
		
		$objWorksheet6->getStyle ( 'A1:G1' )->applyFromArray ( $styleArray );
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `dikaiologisi` WHERE `user` = '$parent' ORDER BY  `am`, `mydate` ASC ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "dikaiologisi: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$objWorksheet6->getColumnDimension ( 'A' )->setAutoSize ( true );
		$objWorksheet6->getColumnDimension ( 'B' )->setAutoSize ( true );
		$objWorksheet6->getColumnDimension ( 'C' )->setAutoSize ( true );
		$objWorksheet6->getColumnDimension ( 'D' )->setAutoSize ( true );
		$objWorksheet6->getColumnDimension ( 'E' )->setAutoSize ( true );
		$objWorksheet6->getColumnDimension ( 'F' )->setAutoSize ( true );
		$objWorksheet6->getColumnDimension ( 'G' )->setAutoSize ( true );
		
		$objWorksheet6->getCellByColumnAndRow ( 0, 1 )->setValue ( "protokolo" );
		$objWorksheet6->getCellByColumnAndRow ( 1, 1 )->setValue ( "mydate" );
		$objWorksheet6->getCellByColumnAndRow ( 2, 1 )->setValue ( "firstday" );
		$objWorksheet6->getCellByColumnAndRow ( 3, 1 )->setValue ( "lastday" );
		$objWorksheet6->getCellByColumnAndRow ( 4, 1 )->setValue ( "countdays" );
		$objWorksheet6->getCellByColumnAndRow ( 5, 1 )->setValue ( "iat_beb" );
		$objWorksheet6->getCellByColumnAndRow ( 6, 1 )->setValue ( "am" );
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			$objWorksheet6->getCellByColumnAndRow ( 0, $k )->setValue ( $row ["protokolo"] );
			$mydate = $row ["mydate"];
			$formateddate = substr ( $mydate, 6, 2 ) . "/" . substr ( $mydate, 4, 2 ) . "/" . substr ( $mydate, 0, 4 );
			$objWorksheet6->getCellByColumnAndRow ( 1, $k )->setValue ( $formateddate );
			$objWorksheet6->getCellByColumnAndRow ( 2, $k )->setValue ( $row ["firstday"] );
			$objWorksheet6->getCellByColumnAndRow ( 3, $k )->setValue ( $row ["lastday"] );
			$objWorksheet6->getCellByColumnAndRow ( 4, $k )->setValue ( $row ["countdays"] );
			$objWorksheet6->getCellByColumnAndRow ( 5, $k )->setValue ( $row ["iat_beb"] );
			$objWorksheet6->getCellByColumnAndRow ( 6, $k )->setValue ( $row ["am"] );
			$i ++;
		}
		
		// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$objWorksheet7 = $objPHPExcel->createSheet ();
		$objWorksheet7->setTitle ( 'studentstmimata' );
		
		$objWorksheet7->getStyle ( 'A1:B1' )->applyFromArray ( $styleArray );
		
		// συνδέομαι με τη βάση
		include ("includes/dbinfo.inc.php");
		
		// ελέγχω αν υπάρχει το νέο τμήμα
		$query = "SELECT * FROM `studentstmimata` WHERE `user` = '$parent' ;";
		
		$result = mysqli_query ( $link, $query );
		
		if (! $result) {
			$errorText = mysqli_error ( $link );
			echo "studentstmimata: $errorText<hr>";
		}
		
		$num = mysqli_num_rows ( $result );
		
		mysqli_close ( $link );
		
		$objWorksheet7->getColumnDimension ( 'A' )->setAutoSize ( true );
		$objWorksheet7->getColumnDimension ( 'B' )->setAutoSize ( true );
		
		$objWorksheet7->getCellByColumnAndRow ( 0, 1 )->setValue ( "student_am" );
		$objWorksheet7->getCellByColumnAndRow ( 1, 1 )->setValue ( "tmima" );
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$k = $i + 2;
			$objWorksheet7->getCellByColumnAndRow ( 0, $k )->setValue ( $row ["student_am"] );
			$objWorksheet7->getCellByColumnAndRow ( 1, $k )->setValue ( $row ["tmima"] );
			$i ++;
		}
		
		// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex ( 0 );
	}
	
	$backupdate = date ( 'j-m-Y' );
	$filename = "$parent-$backupdate";
	
	if (isset ( $_POST ["sendmail"] )) {
		
		if ($extention == 'sql') {
			$filename .= ".sql";
			$fileContent = $sqltmimata . $sqlstudents . $sqlapousies . $sqlhistory . $sqlapousies_pre . $sqlparameters . $sqldikaiologisi . $sqlstudentstmimata;
			$pfile = fopen ( "upload/$filename", "w" );
			fwrite ( $pfile, $fileContent );
			fclose ( $pfile );
		} elseif ($extention == 'csv') {
			$filename .= ".csv";
		} elseif ($extention == 'xls') {
			$filename .= ".xls";
			$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
			$objWriter->save ( "upload/$filename" );
			$fileatttype = "application/vnd.ms-excel";
		} elseif ($extention == 'xlsx') {
			$filename .= ".xlsx";
		}
		
		// συμπίεση του αρχείου
		$obj = new PclZip ( "upload/$filename.zip" ); // name of zip file
		$obj->create ( "upload/$filename", "", "upload/" );
		
		/*
		 * //αυτό δουλεύει μόνο τοπικά στο localhost και όχι στις apousies.gr
		 * $zip = new ZipArchive;
		 * if ($zip->open("upload/$parent-$backupdate.zip", ZIPARCHIVE::CREATE) === TRUE) {
		 * $zip->addFile("upload/$filename", "$filename");
		 * $zip->close();
		 *
		 * // echo 'zip ok<br>';
		 * } else {
		 * // echo 'zip failed';
		 * }
		 */
		
		$fileContent = file_get_contents ( "upload/$filename.zip" );
		
		$mail = new MyPHPMailer ();
		
		$mail->Subject = "Διαχείριση Απουσιών. Backup στοιχείων.";
		$mail->Body = "Στο επισυναπτόμενο αρχείο $filename.zip είναι τα δεδομένα σας.";
		$mail->AddAddress ( $email );
		$mail->AddStringAttachment ( $fileContent, "$filename.zip" );
		
		$okmail = $mail->Send ();
		
		unlink ( "upload/$filename.zip" );
		unlink ( "upload/$filename" );
		
		if ($okmail) $smarty->assign ( 'okmail', $okmail );
		$smarty->assign ( 'email', $email );
		$smarty->assign ( 'filename', $filename );
		$smarty->assign ( 'backupdate', $backupdate );
		trim ( $fileContent ) == '' ? $contentchk = false : $contentchk = true;
		$smarty->assign ( 'contentchk', $contentchk );
	} // isset($_POST["sendmail"])
	
	if (isset ( $_POST ["send"] )) {
		
		if ($extention == "xlsx") {
			// excel 2007
			// Redirect output to a client’s web browser (Excel5)
			header ( 'Content-Type: application/vnd.ms-excel' );
			header ( "Content-Disposition: attachment;filename=\"$filename.xlsx\"" );
			header ( 'Cache-Control: max-age=0' );
			
			$fileContent = file_get_contents ( "upload/$filename.xlsx" );
			unlink ( "upload/$filename.xlsx" );
			echo $fileContent;
		}
		
		if ($extention == "xls") {
			// excel 2000
			// Redirect output to a client’s web browser (Excel5)
			header ( 'Content-Type: application/vnd.ms-excel' );
			header ( "Content-Disposition: attachment;filename=\"$filename.xls\"" );
			header ( 'Cache-Control: max-age=0' );
			
			$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
			$objWriter->save ( 'php://output' );
		}
		if ($extention == "csv") {
			// csv
			// Redirect output to a client’s web browser (Excel5)
			header ( 'Content-Type: application/CSV' );
			header ( "Content-Disposition: attachment;filename=\"$filename.csv\"" );
			header ( 'Cache-Control: max-age=0' );
			
			$fileContent = file_get_contents ( "upload/$filename.csv" );
			unlink ( "upload/$filename.csv" );
			echo $fileContent;
		}
		
		if ($extention == "sql") {
			
			header ( 'Content-Type: text/sql' );
			header ( "Content-Disposition: attachment;filename=\"$filename.sql\"" );
			header ( 'Cache-Control: max-age=0' );
			
			echo $sqltmimata;
			echo $sqlstudents;
			echo $sqlapousies;
			echo $sqlhistory;
			echo $sqlapousies_pre;
			echo $sqlparameters;
			echo $sqldikaiologisi;
			echo $sqlstudentstmimata;
		}
		
		/*
		 * //pdf
		 * //$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		 * //$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		 *
		 * // Redirect output to a client’s web browser (Excel2007)
		 * header('Content-Type: application/pdf');
		 * header('Content-Disposition: attachment;filename="students-$parent-$tmima.pdf"');
		 * header('Cache-Control: max-age=0');
		 *
		 * $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
		 * $objWriter->save('php://output');
		 */
		
		exit ();
	} // isset($_POST["send"])
} // if(isset($_POST["send"]) || isset($_POST["sendmail"]))

$extra_javascript = '
    <script type="text/javascript" language="javascript" >
    // <!--

    function check_delete(){
        var user ="' . $parent . '";
        var answer;

        answer=confirm("Θα διαγραφούν όλα τα δεδομένα του χρήστη " + user + ". Επιβεβαιώστε παρακαλώ.");
        if (answer == false){return false;}
        answer=confirm("Η διαδικασία δεν είναι αναστρέψιμη. Βεβαιωθείτε ότι έχετε κρατήσει τα δεδομένα. Να προχωρήσω στη διαγραφή;");
        if (answer == false){return false;}
        return true;
    }
    // -->
</script>

';

$smarty->assign ( 'title', 'ΕΞΑΓΩΓΗ ΔΕΔΟΜΕΝΩΝ' );
$smarty->assign ( 'extra_javascript', $extra_javascript );
$smarty->assign ( 'extra_style', '' );
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'h1_title', 'Εξαγωγή δεδομένων' );

$smarty->display ( 'exportdata.tpl' );

