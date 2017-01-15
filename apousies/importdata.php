<?php
ini_set ( 'memory_limit', '64M' );

require_once ('common.php');
checkUser ();
checkParent();
// checktmima();

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
// isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima ='';
// παίρνω το τμήμα που επιλέχτηκε

/*
 * foreach($_POST as $key => $value){
 * $valuearray[]=$value;
 * echo "$key = $value<hr>";
 * }
 */
isset ( $_POST ['save'] ) ? $save = true : $save = false;
isset ( $_POST ['final'] ) ? $final = true : $final = false;

if (isset ( $_FILES ["file"] )) {
	if ($_FILES ["file"] ["error"] > 0) {
		// echo "Λάθος: " . $_FILES["file"]["error"] . "<br />";
		$save = false;
		$final = false;
	}
}

if ($save) {
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	move_uploaded_file ( $_FILES ["file"] ["tmp_name"], "upload/" . $_FILES ["file"] ["name"] );
	
	$filename = "upload/" . $_FILES ["file"] ["name"];
	
	$extention = substr ( $filename, strrpos ( $filename, '.' ) + 1 );
	
	$errorText = '';
	
	if ($extention == "zip") {
		
		// αποσυμπίεση του αρχείου base.xlsx σοτ φάκελο upload/$filename
		$obj = new PclZip ( "upload/" . $_FILES ["file"] ["name"] ); // name of zip file
		$zip_files = $obj->listContent (); // κρατάω τη λίστα αρχείων
		$path_to_remove = dirname ( $zip_files [0] ["filename"] ) . "/";
		$obj->extract ( "upload/", $path_to_remove );
		
		unlink ( "upload/" . $_FILES ["file"] ["name"] );
		
		$filename = "upload/" . basename ( $zip_files [0] ["filename"] );
		$extention = substr ( $filename, strrpos ( $filename, '.' ) + 1 );
	}
	
	if ($extention != "sql" && $extention != "csv" && $extention != "xls" && $extention != "xlsx") { // && $extention != "ods"
		$errorText = "Μη υποστηριζόμενος τύπος αρχείου!";
		unlink ( $filename );
	}
	
	if ($extention == "sql") {
		$sql2import = str_replace ( "_usr_", "$parent", trim ( file_get_contents ( $filename ) ) );
		unlink ( $filename );
		
		if ($sql2import == "") {
			$errorText = "Το αρχείο δεν περιέχει δεδομένα.";
		}
		
		if ($errorText == '') {
			$pindata = explode ( ";\r\n", $sql2import );
			
			$pinquery = Array ();
			
			foreach ( $pindata as $myquery ) {
				$start = stripos ( $myquery, "`" ) + 1;
				$str = substr ( $myquery, $start, stripos ( $myquery, "`", $start ) - $start );
				
				switch ($str) {
					case 'tmimata' :
						$pinquery [0] = $myquery . ';';
						break;
					case 'students' :
						$pinquery [1] = $myquery . ';';
						break;
					case 'apousies' :
						$pinquery [2] = $myquery . ';';
						break;
					case 'paperhistory' :
						$pinquery [3] = $myquery . ';';
						break;
					case 'apousies_pre' :
						$pinquery [4] = $myquery . ';';
						break;
					case 'parameters' :
						$pinquery [5] = $myquery . ';';
						break;
					case 'dikaiologisi' :
						$pinquery [6] = $myquery . ';';
						break;
					case 'studentstmimata' :
						$pinquery [7] = $myquery . ';';
						break;
				}
			}
			
			// echo $pinquery[0] . "<hr>";
			// echo $pinquery[1] . "<hr>";
			// echo $pinquery[2] . "<hr>";
			// echo $pinquery[3] . "<hr>";
			// echo $pinquery[4] . "<hr>";
			// echo $pinquery[5] . "<hr>";
			// echo $pinquery[6] . "<hr>";
			// echo $pinquery[7] . "<hr>";
		} // if($errorText == '')
	} // if ($extention = "sql")
	
	if ($extention == "csv") {
		
		$csv2import = file ( $filename );
		
		unlink ( $filename );
		
		if (! $csv2import) {
			$errorText = "Το αρχείο δεν περιέχει δεδομένα.";
		}
		
		// print_r($csv2import);
		
		if ($errorText == '') {
			
			$pinquery = array ();
			
			// tmimata 'tmima','type','lastselect'
			$pinquery [0] = "INSERT INTO `" . trim ( $csv2import [0] ) . "` (" . str_replace ( "'", "`", trim ( $csv2import [1] ) ) . ", `username`) VALUES ";
			if (trim ( $csv2import [$i + 2] ) == '')
				$pinquery [0] = '';
			
			if ($pinquery [0] != '') {
				for($i = 2; $i < count ( $csv2import ); $i ++) {
					if (trim ( $csv2import [$i] ) != '') {
						$pinquery [0] .= "(" . trim ( $csv2import [$i] ) . ", '$parent')";
					}
					if (trim ( $csv2import [$i + 1] ) == '') {
					} elseif (trim ( $csv2import [$i + 1] ) == 'students') {
						$pinquery [0] .= ";";
						$k = $i + 1;
						break;
					} else {
						$pinquery [0] .= ", ";
					}
				}
			}
			
			// students 'am','epitheto','onoma','patronimo','ep_kidemona','on_kidemona','dieythinsi','tk','poli','til1','til2','email','filo','tmima','tmima-kat','tmima-epi'
			$pinquery [1] = "INSERT INTO `" . trim ( $csv2import [$k] ) . "` (" . str_replace ( "'", "`", trim ( $csv2import [$k + 1] ) ) . ", `user`) VALUES ";
			if (trim ( $csv2import [$k + 2] ) == '')
				$pinquery [1] = '';
			
			if ($pinquery [1] != '') {
				for($i = $k + 2; $i < count ( $csv2import ); $i ++) {
					if (trim ( $csv2import [$i] ) != '') {
						$pinquery [1] .= "(" . trim ( $csv2import [$i] ) . ", '$parent')";
					}
					if (trim ( $csv2import [$i + 1] ) == '') {
					} elseif (trim ( $csv2import [$i + 1] ) == 'apousies') {
						$pinquery [1] .= ";";
						$k = $i + 1;
						break;
					} else {
						$pinquery [1] .= ", ";
					}
				}
			} else {
				$k += 3;
			}
			
			// apousies 'date','ap','apk','ape','dk','dd','dm','fh','mh','lh','oa','da','student_am'
			$pinquery [2] = "INSERT INTO `" . trim ( $csv2import [$k] ) . "` (" . str_replace ( "'", "`", trim ( $csv2import [$k + 1] ) ) . ", `user`) VALUES ";
			if (trim ( $csv2import [$k + 2] ) == '')
				$pinquery [2] = '';
			
			if ($pinquery [2] != '') {
				for($i = $k + 2; $i < count ( $csv2import ); $i ++) {
					$mydata = explode ( ',', trim ( $csv2import [$i] ), 2 );
					$datestamp = makedatestamp ( substr ( $mydata [0], 1, - 1 ) );
					if (trim ( $csv2import [$i] ) != '') {
						$pinquery [2] .= "( '$datestamp', " . trim ( $mydata [1] ) . ", '$parent')";
					}
					if (trim ( $csv2import [$i + 1] ) == '') {
					} elseif (trim ( $csv2import [$i + 1] ) == 'paperhistory') {
						$pinquery [2] .= ";";
						$k = $i + 1;
						break;
					} else {
						$pinquery [2] .= ", ";
					}
				}
			} else {
				$k += 3;
			}
			
			// paperhistory 'protok','date','am','apous'
			$pinquery [3] = "INSERT INTO `" . trim ( $csv2import [$k] ) . "` (" . str_replace ( "'", "`", trim ( $csv2import [$k + 1] ) ) . ", `user`) VALUES ";
			if (trim ( $csv2import [$k + 2] ) == '')
				$pinquery [3] = '';
			
			if ($pinquery [3] != '') {
				for($i = $k + 2; $i < count ( $csv2import ); $i ++) {
					$mydata = explode ( ',', trim ( $csv2import [$i] ), 3 );
					$datestamp = makedatestamp ( substr ( $mydata [1], 1, - 1 ) );
					if (trim ( $csv2import [$i] ) != '') {
						$pinquery [3] .= "(" . trim ( $mydata [0] ) . ", '$datestamp', " . trim ( $mydata [2] ) . ", '$parent')";
					}
					if (trim ( $csv2import [$i + 1] ) == '') {
					} elseif (trim ( $csv2import [$i + 1] ) == 'apousies_pre') {
						$pinquery [3] .= ";";
						$k = $i + 1;
						break;
					} else {
						$pinquery [3] .= ", ";
					}
				}
			} else {
				$k += 3;
			}
			
			// apousies_pre 'date','ap','apk','ape','dk','daysk','dd','dm','fh','mh','lh','oa','da','student_am'
			$pinquery [4] = "INSERT INTO `" . trim ( $csv2import [$k] ) . "` (" . str_replace ( "'", "`", trim ( $csv2import [$k + 1] ) ) . ", `user`) VALUES ";
			if (trim ( $csv2import [$k + 2] ) == '')
				$pinquery [4] = '';
			
			if ($pinquery [4] != '') {
				for($i = $k + 2; $i < count ( $csv2import ); $i ++) {
					$mydata = explode ( ',', trim ( $csv2import [$i] ), 2 );
					$datestamp = makedatestamp ( substr ( $mydata [0], 1, - 1 ) );
					if (trim ( $csv2import [$i] ) != '') {
						$pinquery [4] .= "( '$datestamp', " . trim ( $mydata [1] ) . ", '$parent')";
					}
					if (trim ( $csv2import [$i + 1] ) == '' || $i == count ( $csv2import )) {
						$pinquery [4] .= ";";
						$k = $i + 2;
						break;
					} else {
						$pinquery [4] .= ", ";
					}
				}
			} else {
				$k += 3;
			}
			
			// parameters 'tmima','key','value'
			$pinquery [5] = "INSERT INTO `" . trim ( $csv2import [$k] ) . "` (" . str_replace ( "'", "`", trim ( $csv2import [$k + 1] ) ) . ", `user`) VALUES ";
			if (trim ( $csv2import [$k + 2] ) == '')
				$pinquery [5] = '';
			
			if ($pinquery [5] != '') {
				for($i = $k + 2; $i < count ( $csv2import ); $i ++) {
					$mydata = explode ( ',', trim ( $csv2import [$i] ), 4 );
					if (trim ( $csv2import [$i] ) != '') {
						$pinquery [5] .= "(" . trim ( $csv2import [$i] ) . ", '$parent')";
					}
					if (trim ( $csv2import [$i + 1] ) == '' || $i == count ( $csv2import )) {
						$pinquery [5] .= ";";
						$k = $i + 2;
						break;
					} else {
						$pinquery [5] .= ", ";
					}
				}
			} else {
				$k += 3;
			}
			
			// dikaiologisi 'protokolo','mydate','firstday','lastday','countdays','iat_beb','am'
			$pinquery [6] = "INSERT INTO `" . trim ( $csv2import [$k] ) . "` (" . str_replace ( "'", "`", trim ( $csv2import [$k + 1] ) ) . ", `user`) VALUES ";
			if (trim ( $csv2import [$k + 2] ) == '')
				$pinquery [6] = '';
			
			if ($pinquery [6] != '') {
				for($i = $k + 2; $i < count ( $csv2import ); $i ++) {
					$mydata = explode ( ',', trim ( $csv2import [$i] ), 3 );
					$datestamp = makedatestamp ( substr ( $mydata [1], 1, - 1 ) );
					if (trim ( $csv2import [$i] ) != '') {
						$pinquery [6] .= "( " . trim ( $mydata [0] ) . ", '$datestamp' , " . trim ( $mydata [2] ) . ", '$parent')";
					}
					if (trim ( $csv2import [$i + 1] ) == '' || $i == count ( $csv2import )) {
						$pinquery [6] .= ";";
						$k = $i + 1;
						break;
					} else {
						$pinquery [6] .= ", ";
					}
				}
			} else {
				$k += 3;
			}
			
			// studentstmimata 'student_am','tmima'
			$pinquery [7] = "INSERT INTO `" . trim ( $csv2import [$k] ) . "` (" . str_replace ( "'", "`", trim ( $csv2import [$k + 1] ) ) . ", `user`) VALUES ";
			if (trim ( $csv2import [$k + 2] ) == '')
				$pinquery [7] = '';
			
			if ($pinquery [7] != '') {
				for($i = $k + 2; $i < count ( $csv2import ); $i ++) {
					$mydata = explode ( ',', trim ( $csv2import [$i] ), 2 );
					if (trim ( $csv2import [$i] ) != '') {
						$pinquery [7] .= "( " . trim ( $mydata [0] ) . ", " . trim ( $mydata [1] ) . ", '$parent')";
					}
					if (trim ( $csv2import [$i + 1] ) == '' || $i == count ( $csv2import )) {
						$pinquery [7] .= ";";
						$k = $i + 1;
						break;
					} else {
						$pinquery [7] .= ", ";
					}
				}
			}
			
			// echo "<pre>$pinquery[0]</pre><hr>";
			// echo "<pre>$pinquery[1]</pre><hr>";
			// echo "<pre>$pinquery[2]</pre><hr>";
			// echo "<pre>$pinquery[3]</pre><hr>";
			// echo "<pre>$pinquery[4]</pre><hr>";
			// echo "<pre>$pinquery[5]</pre><hr>";
			// echo "<pre>$pinquery[6]</pre><hr>";
			// echo "<pre>$pinquery[7]</pre><hr>";
		} // if ($errorText == '' ){
	} // if ($extention = "csv")
	
	if ($extention == "xlsx") {
		
		// αποσυμπίεση του αρχείου base.xlsx σοτ φάκελο upload/$filename
		$obj = new PclZip ( $filename ); // name of zip file
		$name_of_file = substr ( $filename, 0, strlen ( $filename ) - 5 );
		$obj->extract ( $name_of_file . "/" );
		
		$strxml = simplexml_load_file ( "$name_of_file/xl/sharedStrings.xml" );
		
		$pinquery [0] = "INSERT INTO `tmimata` ";
		$pinquery [1] = "INSERT INTO `students` ";
		$pinquery [2] = "INSERT INTO `apousies` ";
		$pinquery [3] = "INSERT INTO `paperhistory` ";
		$pinquery [4] = "INSERT INTO `apousies_pre` ";
		$pinquery [5] = "INSERT INTO `parameters` ";
		$pinquery [6] = "INSERT INTO `dikaiologisi` ";
		$pinquery [7] = "INSERT INTO `studentstmimata` ";
		
		$parent_field_name = array (
				'`username`',
				'`user`',
				'`user`',
				'`user`',
				'`user`',
				'`user`',
				'`user`',
				'`user`' 
		);
		
		for($x = 0; $x < count ( $pinquery ); $x ++) {
			
			$f = $x + 1;
			$xml = simplexml_load_file ( "$name_of_file/xl/worksheets/sheet$f.xml" );
			$num_of_rows = count ( $xml->sheetData->row );
			
			if ($num_of_rows < 2)
				$pinquery [$x] = '';
			
			$dummyarray = array ();
			$fieldsarray = array ();
			$check = 0;
			
			foreach ( $xml->sheetData as $row ) {
				foreach ( $row as $c ) {
					foreach ( $c as $key => $data ) {
						if ($check == 0) {
							if ($data->attributes ()->t == 's') {
								$fieldsarray [] = htmlspecialchars_decode ( ( string ) $strxml->si [( int ) $data->v]->t );
							} else {
								$fieldsarray [] = ( string ) $data->v;
							}
						} else {
							if ($data->attributes ()->t == 's') {
								$dummyarray [] = htmlspecialchars_decode ( ( string ) $strxml->si [( int ) $data->v]->t );
							} else {
								$dummyarray [] = ( string ) $data->v;
							}
						}
					}
					$check ++;
				}
			}
			
			// print_r($fieldsarray);
			// echo "<hr>";
			// print_r($dummyarray);
			// echo "<hr>";
			
			$fields = '';
			for($i = 0; $i < count ( $fieldsarray ); $i ++) {
				$fields .= '`' . $fieldsarray [$i] . '`,';
			}
			$fields .= $parent_field_name [$x];
			
			$mynum = count ( $fieldsarray );
			$mydata = "";
			
			for($i = 0; $i < count ( $dummyarray ); $i += $mynum) {
				$mydata .= "(";
				for($k = $i; $k < $i + $mynum - 1; $k ++) {
					
					if (($x == 2 && $k % $mynum == 0) || ($x == 3 && $k % $mynum == 1) || ($x == 4 && $k % $mynum == 0) || ($x == 6 && $k % $mynum == 1)) {
						$mydate = makedatestamp ( $dummyarray [$k] );
						$mydata .= "'" . $mydate . "',";
					} else {
						// ελεγχω αν το πρωτο γραμμα είναι ' που χρησιμοποιείται για μορφοποίηση σε κείμενο στο xlsx και το βγάζω
						substr ( $dummyarray [$k], 0, 1 ) == "'" ? $mydummydata = substr ( $dummyarray [$k], 1 ) : $mydummydata = $dummyarray [$k];
						$mydata .= "'" . $mydummydata . "',";
					}
				}
				$mydata .= "'" . $dummyarray [$i + $mynum - 1] . "', '$parent'),";
			}
			if ($pinquery [$x]) {
				$pinquery [$x] .= "($fields) VALUES " . substr ( $mydata, 0, strlen ( $mydata ) - 1 ) . ";";
			}
			// echo $pinquery[$x] . "<hr>";
		}
		
		unlink ( $filename );
		rmdir_r ( $name_of_file );
	}
	
	if ($extention == "xls") { // || $extention == "ods"memory
		
		/**
		 * PHPExcel *
		 */
		
		$inputFileType = PHPExcel_IOFactory::identify ( $filename );
		$objReader = PHPExcel_IOFactory::createReader ( $inputFileType );
		$objReader->setReadDataOnly ( true );
		
		$sheetname = 'tmimata';
		$objReader->setLoadSheetsOnly ( $sheetname );
		$objPHPExcel = $objReader->load ( $filename );
		
		$objPHPExcel->setActiveSheetIndexByName ( $sheetname );
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		
		$highestRow = $objWorksheet->getHighestRow (); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn (); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn ); // e.g. 5
		
		$pinquery = array ();
		
		$pinquery [0] = "INSERT INTO `tmimata` (`tmima`, `type`, `lastselect`, `username`)  VALUES ";
		if (trim ( $objWorksheet->getCellByColumnAndRow ( 0, 2 )->getValue () ) == '')
			$pinquery [0] = '';
		
		if ($pinquery [0] != '') {
			for($row = 2; $row <= $highestRow; ++ $row) {
				$pinquery [0] .= "(";
				for($col = 0; $col < $highestColumnIndex; ++ $col) {
					$pinquery [0] .= "'" . trim ( $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue () ) . "', ";
				}
				$row == $highestRow ? $pinquery [0] .= "'$parent' );" : $pinquery [0] .= "'$parent' ),";
			}
		}
		
		$objPHPExcel->disconnectWorksheets ();
		unset ( $objPHPExcel );
		
		// #############################
		
		$sheetname = 'students';
		$objReader->setLoadSheetsOnly ( $sheetname );
		$objPHPExcel = $objReader->load ( $filename );
		
		$objPHPExcel->setActiveSheetIndexByName ( $sheetname );
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		
		$highestRow = $objWorksheet->getHighestRow (); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn (); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn ); // e.g. 5
		
		$fields = '';
		for($col = 0; $col < $highestColumnIndex; ++ $col) {
			$fields .= "`" . trim ( $objWorksheet->getCellByColumnAndRow ( $col, 1 )->getValue () ) . "`, ";
		}
		$fields .= '`user`';
		
		$pinquery [1] = "INSERT INTO `students` ( $fields ) VALUES ";
		if (trim ( $objWorksheet->getCellByColumnAndRow ( 0, 2 )->getValue () ) == '')
			$pinquery [1] = '';
			
			// echo $objWorksheet->getCellByColumnAndRow(0, 2)->getValue() . "<hr>";
		
		if ($pinquery [1] != '') {
			for($row = 2; $row <= $highestRow; ++ $row) {
				$pinquery [1] .= "(";
				for($col = 0; $col < $highestColumnIndex; ++ $col) {
					$pinquery [1] .= "'" . trim ( $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue () ) . "', ";
				}
				$row == $highestRow ? $pinquery [1] .= "'$parent');" : $pinquery [1] .= "'$parent'),";
			}
		}
		
		$objPHPExcel->disconnectWorksheets ();
		unset ( $objPHPExcel );
		
		// ################################################
		// #############################
		// #############################
		/**
		 * Define a Read Filter class implementing PHPExcel_Reader_IReadFilter
		 */
		class chunkReadFilter implements PHPExcel_Reader_IReadFilter {
			private $_startRow = 0;
			private $_endRow = 0;
			
			/**
			 * Set the list of rows that we want to read
			 */
			public function setRows($startRow, $chunkSize) {
				$this->_startRow = $startRow;
				
				$this->_endRow = $startRow + $chunkSize;
			}
			public function readCell($column, $row, $worksheetName = '') {
				
				// Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow
				if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) {
					
					return true;
				}
				
				return false;
			}
		}
		
		// #############################################
		// #############################################
		
		$sheetname = 'apousies';
		$objReader->setLoadSheetsOnly ( $sheetname );
		
		$final_data_array = array ();
		$dummy_data_array = array ();
		
		/**
		 * Define how many rows we want to read for each "chunk" *
		 */
		$chunkSize = 1000;
		
		$chunkFilter = new chunkReadFilter ();
		$objReader->setReadFilter ( $chunkFilter );
		
		// $mem = 0;
		/**
		 * Loop to read our worksheet in "chunk size" blocks *
		 */
		/**
		 * $startRow is set to 2 initially because we always read the headings in row #1 *
		 */
		for($startRow = 2; $startRow <= 65536; $startRow += $chunkSize) { // 65536
			$endRow = $startRow + $chunkSize - 1;
			$myrange = "A" . $startRow . ":J" . $endRow;
			$chunkFilter->setRows ( $startRow, $chunkSize );
			$objPHPExcel = $objReader->load ( $filename );
			
			$objPHPExcel->setActiveSheetIndexByName ( $sheetname );
			$dataarray = $objPHPExcel->getActiveSheet ()->rangeToArray ( $myrange, null, false, false, false );
			
			if (! $dataarray [0] [0])
				break;
			
			$final_data_array = array_merge ( $dummy_data_array, $dataarray );
			$dummy_data_array = $final_data_array;
			
			// if ($mem < memory_get_usage())$mem = memory_get_usage();
			
			$objPHPExcel->disconnectWorksheets ();
			unset ( $objPHPExcel );
		}
		
		// echo $mem . "<hr>";
		
		$query_str = '';
		
		foreach ( $final_data_array as $row ) {
			if ($row [0]) {
				$query_str .= "('" . makedatestamp ( $row [0] ) . "', ";
				for($col = 1; $col < 10; $col ++) {
					$query_str .= "'" . $row [$col] . "', ";
				}
				$query_str .= "'" . $parent . "'),\r\n";
			}
		}
		
		if ($query_str) {
			$pinquery [2] = "INSERT INTO `apousies` (`mydate`, `apous`, `dik`, `from`, `fh`, `mh`, `lh`, `oa`, `da`, `student_am`, `user` ) VALUES \r\n" . substr ( trim ( $query_str ), 0, strlen ( trim ( $query_str ) ) - 1 ) . ";";
		}
		
		// #############################
		
		$sheetname = 'paperhistory';
		$objReader->setLoadSheetsOnly ( $sheetname );
		$objReader->setReadFilter ( new PHPExcel_Reader_DefaultReadFilter () );
		$objPHPExcel = $objReader->load ( $filename );
		
		$objPHPExcel->setActiveSheetIndexByName ( $sheetname );
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		
		$highestRow = $objWorksheet->getHighestRow (); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn (); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn ); // e.g. 5
		
		$pinquery [3] = "INSERT INTO `paperhistory` ( `protok`, `mydate`, `am`, `apous`, `user`) VALUES ";
		if (trim ( $objWorksheet->getCellByColumnAndRow ( 0, 2 )->getValue () ) == '')
			$pinquery [3] = '';
			
			// echo $objWorksheet->getCellByColumnAndRow(0, 2)->getValue() . "<hr>";
		
		if ($pinquery [3] != '') {
			for($row = 2; $row <= $highestRow; ++ $row) {
				$pinquery [3] .= "(";
				for($col = 0; $col < $highestColumnIndex; ++ $col) {
					if ($col == 1) {
						$pinquery [3] .= "'" . makedatestamp ( trim ( $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue () ) ) . "', ";
					} else {
						$pinquery [3] .= "'" . trim ( $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue () ) . "', ";
					}
				}
				$row == $highestRow ? $pinquery [3] .= "'$parent');" : $pinquery [3] .= "'$parent'),";
			}
		}
		
		$objPHPExcel->disconnectWorksheets ();
		unset ( $objPHPExcel );
		
		// #############################
		
		$sheetname = 'apousies_pre';
		$objReader->setLoadSheetsOnly ( $sheetname );
		$objPHPExcel = $objReader->load ( $filename );
		
		$objPHPExcel->setActiveSheetIndexByName ( $sheetname );
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		
		$highestRow = $objWorksheet->getHighestRow (); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn (); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn ); // e.g. 5
		
		$pinquery [4] = "INSERT INTO `apousies_pre` (`mydate`, `apous`, `daysk`, `dik`, `fh`, `mh`, `lh`, `oa`, `da`, `student_am`, `user`) VALUES ";
		if (trim ( $objWorksheet->getCellByColumnAndRow ( 0, 2 )->getValue () ) == '')
			$pinquery [4] = '';
			
			// echo $objWorksheet->getCellByColumnAndRow(0, 2)->getValue() . "<hr>";
		
		if ($pinquery [4] != '') {
			for($row = 2; $row <= $highestRow; ++ $row) {
				$pinquery [4] .= "(";
				$pinquery [4] .= "'" . makedatestamp ( trim ( $objWorksheet->getCellByColumnAndRow ( 0, $row )->getValue () ) ) . "', ";
				for($col = 1; $col < $highestColumnIndex; ++ $col) {
					$pinquery [4] .= "'" . trim ( $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue () ) . "', ";
				}
				$row == $highestRow ? $pinquery [4] .= "'$parent' );" : $pinquery [4] .= "'$parent'),";
			}
		}
		
		$objPHPExcel->disconnectWorksheets ();
		unset ( $objPHPExcel );
		
		// #############################
		
		$sheetname = 'parameters';
		$objReader->setLoadSheetsOnly ( $sheetname );
		$objPHPExcel = $objReader->load ( $filename );
		
		$objPHPExcel->setActiveSheetIndexByName ( $sheetname );
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		
		$highestRow = $objWorksheet->getHighestRow (); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn (); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn ); // e.g. 5
		
		$pinquery [5] = "INSERT INTO `parameters` (`tmima`, `key`, `value`, `user`) VALUES ";
		if (trim ( $objWorksheet->getCellByColumnAndRow ( 0, 2 )->getValue () ) == '')
			$pinquery [5] = '';
			
			// echo $objWorksheet->getCellByColumnAndRow(0, 2)->getValue() . "<hr>";
		
		if ($pinquery [5] != '') {
			for($row = 2; $row <= $highestRow; ++ $row) {
				$pinquery [5] .= "(";
				for($col = 0; $col < $highestColumnIndex; ++ $col) {
					$pinquery [5] .= "'" . trim ( $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue () ) . "', ";
				}
				$row == $highestRow ? $pinquery [5] .= "'$parent' );" : $pinquery [5] .= "'$parent'),";
			}
		}
		
		$objPHPExcel->disconnectWorksheets ();
		unset ( $objPHPExcel );
		
		// #############################
		
		$sheetname = 'dikaiologisi';
		$objReader->setLoadSheetsOnly ( $sheetname );
		$objPHPExcel = $objReader->load ( $filename );
		
		$objPHPExcel->setActiveSheetIndexByName ( $sheetname );
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		
		$highestRow = $objWorksheet->getHighestRow (); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn (); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn ); // e.g. 5
		
		$pinquery [6] = "INSERT INTO `dikaiologisi` (`protokolo`,`mydate`,`firstday`,`lastday`,`countdays`,`iat_beb`,`am`,`user`) VALUES ";
		if (trim ( $objWorksheet->getCellByColumnAndRow ( 0, 2 )->getValue () ) == '')
			$pinquery [6] = '';
			
			// echo $objWorksheet->getCellByColumnAndRow(0, 2)->getValue() . "<hr>";
		
		if ($pinquery [6] != '') {
			for($row = 2; $row <= $highestRow; ++ $row) {
				$pinquery [6] .= "(";
				for($col = 0; $col < $highestColumnIndex; ++ $col) {
					$pinquery [6] .= "'" . trim ( $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue () ) . "', ";
				}
				$row == $highestRow ? $pinquery [6] .= "'$parent' );" : $pinquery [6] .= "'$parent'),";
			}
		}
		
		// #############################
		
		$sheetname = 'studentstmimata';
		$objReader->setLoadSheetsOnly ( $sheetname );
		$objPHPExcel = $objReader->load ( $filename );
		
		$objPHPExcel->setActiveSheetIndexByName ( $sheetname );
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		
		$highestRow = $objWorksheet->getHighestRow (); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn (); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn ); // e.g. 5
		
		$pinquery [7] = "INSERT INTO `studentstmimata` (`student_am`,`tmima`,`user`) VALUES ";
		if (trim ( $objWorksheet->getCellByColumnAndRow ( 0, 2 )->getValue () ) == '')
			$pinquery [7] = '';
			
			// echo $objWorksheet->getCellByColumnAndRow(0, 2)->getValue() . "<hr>";
		
		if ($pinquery [7] != '') {
			for($row = 2; $row <= $highestRow; ++ $row) {
				$pinquery [7] .= "(";
				for($col = 0; $col < $highestColumnIndex; ++ $col) {
					$pinquery [7] .= "'" . trim ( $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue () ) . "', ";
				}
				$row == $highestRow ? $pinquery [7] .= "'$parent' );" : $pinquery [7] .= "'$parent'),";
			}
		}
		
		// echo "$pinquery[0]<hr>";
		// echo "$pinquery[1]<hr>";
		// echo "$pinquery[2]<hr>";
		// echo "$pinquery[3]<hr>";
		// echo "$pinquery[4]<hr>";
		// echo "$pinquery[5]<hr>";
		// echo "$pinquery[6]<hr>";
		// echo "$pinquery[7]<hr>";
		
		$objPHPExcel->disconnectWorksheets ();
		unset ( $objPHPExcel );
		
		unlink ( $filename );
	} // $extention == "xls" || $extention == "ods"
	
	begin ();
	
	if (isset ( $pinquery [0] )) {
		if ($errorText == '' && trim ( $pinquery [0] ) != '') {
			
			$query = "DELETE  FROM `tmimata` WHERE `username` = '$parent' ;";
			$result = mysqli_query ( $link, $query );
			
			$result = mysqli_query ( $link, $pinquery [0] );
			if (! $result) {
				$errorText = "tmimata: " . mysqli_errno ( $link ) . " " . mysqli_error ( $link ) . "<br>";
			} else {
				$numtmimata = mysqli_affected_rows ( $link );
			}
		}
	}
	
	if (isset ( $pinquery [1] )) {
		if ($errorText == '' && trim ( $pinquery [1] ) != '') {
			$query = "DELETE  FROM `students` WHERE `user` = '$parent' ;";
			$result = mysqli_query ( $link, $query );
			
			$result = mysqli_query ( $link, $pinquery [1] );
			if (! $result) {
				$errorText = "students: " . mysqli_error ( $link ) . "<br>";
			} else {
				$numstudents = mysqli_affected_rows ( $link );
			}
		} // if ($errorText == '')
	}
	
	if (isset ( $pinquery [2] )) {
		if ($errorText == '' && trim ( $pinquery [2] ) != '') {
			$query = "DELETE  FROM `apousies` WHERE `user` = '$parent';";
			$result = mysqli_query ( $link, $query );
			
			$result = mysqli_query ( $link, $pinquery [2] );
			if (! $result) {
				$errorText = "apousies: " . mysqli_error ( $link ) . "<br>";
			} else {
				$numapousies = mysqli_affected_rows ( $link );
			}
		}
	}
	
	if (isset ( $pinquery [3] )) {
		if ($errorText == '' && trim ( $pinquery [3] ) != '') {
			$query = "DELETE  FROM `paperhistory` WHERE `user` = '$parent' ;";
			$result = mysqli_query ( $link, $query );
			
			$result = mysqli_query ( $link, $pinquery [3] );
			if (! $result) {
				$errorText = "history: " . mysqli_error ( $link ) . "<br>";
			} else {
				$numhistory = mysqli_affected_rows ( $link );
			}
		}
	}
	
	if (isset ( $pinquery [4] )) {
		if ($errorText == '' && trim ( $pinquery [4] ) != '') {
			$query = "DELETE  FROM `apousies_pre` WHERE `user` = '$parent' ;";
			$result = mysqli_query ( $link, $query );
			
			$result = mysqli_query ( $link, $pinquery [4] );
			if (! $result) {
				$errorText = "apousies_pre: " . mysqli_error ( $link ) . "<br>";
			} else {
				$numapousies_pre = mysqli_affected_rows ( $link );
			}
		}
	}
	
	if (isset ( $pinquery [5] )) {
		if ($errorText == '' && trim ( $pinquery [5] ) != '') {
			$query = "DELETE  FROM `parameters` WHERE `user` = '$parent' ;";
			$result = mysqli_query ( $link, $query );
			
			$result = mysqli_query ( $link, $pinquery [5] );
			if (! $result) {
				$errorText = "parameters: " . mysqli_error ( $link ) . "<br>";
			} else {
				$numparameters = mysqli_affected_rows ( $link );
			}
		}
	}
	
	if (isset ( $pinquery [6] )) {
		if ($errorText == '' && trim ( $pinquery [6] ) != '') {
			$query = "DELETE  FROM `dikaiologisi` WHERE `user` = '$parent' ;";
			$result = mysqli_query ( $link, $query );
			
			$result = mysqli_query ( $link, $pinquery [6] );
			if (! $result) {
				$errorText = "dikaiologisi: " . mysqli_error ( $link ) . "<br>";
			} else {
				$numdikaiologisi = mysqli_affected_rows ( $link );
			}
		}
	}
	
	if (isset ( $pinquery [7] )) {
		if ($errorText == '' && trim ( $pinquery [7] ) != '') {
			$query = "DELETE  FROM `studentstmimata` WHERE `user` = '$parent' ;";
			$result = mysqli_query ( $link, $query );
			
			$result = mysqli_query ( $link, $pinquery [7] );
			if (! $result) {
				$errorText = "studentstmimata: " . mysqli_error ( $link ) . "<br>";
			} else {
				$numstudentstmimata = mysqli_affected_rows ( $link );
			}
		}
	}
	
	rollback ();
	
	mysqli_close ( $link );
	
	if (! $errorText)
		$_SESSION ['pinquery'] = $pinquery;
} // if ($save)

if ($final) {
	
	$pinquery = $_SESSION ['pinquery'];
	
	// συνδέομαι με τη βάση
	include ("includes/dbinfo.inc.php");
	
	begin ();
	$errorcheck = false;
	
	$query = "DELETE  FROM `tmimata` WHERE `username` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	
	if (isset ( $_POST ['tmichk'] ) && $_POST ['tmichk'] == '1') {
		
		$insertquery = $pinquery [0];
		
		if ($insertquery != '') {
			// echo "$insertquery<hr>";
			
			$result = mysqli_query ( $link, $insertquery );
			if (! $result)
				$errorcheck = true;
			
			$numtmimata = mysqli_affected_rows ( $link );
		} // if ($insertquery)
	} // if(isset($_POST['stuchk']) && $_POST['stuchk'] == '1')
	
	$query = "DELETE  FROM `students` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	
	if (isset ( $_POST ['stuchk'] ) && $_POST ['stuchk'] == '1') {
		
		$insertquery = $pinquery [1];
		
		if ($insertquery != '') {
			// echo "$insertquery<hr>";
			
			$result = mysqli_query ( $link, $insertquery );
			if (! $result)
				$errorcheck = true;
			
			$numstudents = mysqli_affected_rows ( $link );
		} // if ($insertquery)
	} // if(isset($_POST['stuchk']) && $_POST['stuchk'] == '1')
	
	$query = "DELETE  FROM `apousies` WHERE `user` = '$parent';";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	
	if (isset ( $_POST ['apouchk'] ) && $_POST ['apouchk'] == '1') {
		
		$insertquery = $pinquery [2];
		
		if ($insertquery != '') {
			// echo "$insertquery<hr>";
			
			$result = mysqli_query ( $link, $insertquery );
			if (! $result)
				$errorcheck = true;
			
			$numapousies = mysqli_affected_rows ( $link );
		} // if ($insertquery)
	} // if(isset($_POST['apouchk']) && $_POST['apouchk'] == '1')
	
	$query = "DELETE  FROM `paperhistory` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	
	if (isset ( $_POST ['histchk'] ) && $_POST ['histchk'] == '1') {
		
		$insertquery = $pinquery [3];
		
		if ($insertquery != '') {
			// echo "$insertquery<hr>";
			
			$result = mysqli_query ( $link, $insertquery );
			if (! $result)
				$errorcheck = true;
			
			$numhistory = mysqli_affected_rows ( $link );
		} // if ($insertquery)
	} // if(isset($_POST['histchk']) && $_POST['histchk'] == '1')
	
	$query = "DELETE  FROM `apousies_pre` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	
	if (isset ( $_POST ['apou_prechk'] ) && $_POST ['apou_prechk'] == '1') {
		
		$insertquery = $pinquery [4];
		
		if ($insertquery != '') {
			// echo "$insertquery<hr>";
			
			$result = mysqli_query ( $link, $insertquery );
			if (! $result)
				$errorcheck = true;
			
			$numapousies_pre = mysqli_affected_rows ( $link );
		} // if ($insertquery)
	} // if(isset($_POST['apouchk']) && $_POST['apouchk'] == '1')
	
	$query = "DELETE  FROM `parameters` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	
	if (isset ( $_POST ['paramchk'] ) && $_POST ['paramchk'] == '1') {
		
		$insertquery = $pinquery [5];
		
		if ($insertquery != '') {
			// echo "$insertquery<hr>";
			
			$result = mysqli_query ( $link, $insertquery );
			if (! $result)
				$errorcheck = true;
			
			$numparameters = mysqli_affected_rows ( $link );
		} // if ($insertquery)
	} // if(isset($_POST['apouchk']) && $_POST['apouchk'] == '1')
	
	$query = "DELETE  FROM `dikaiologisi` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	if (! $result)
		$errorcheck = true;
	
	if (isset ( $_POST ['dikchk'] ) && $_POST ['dikchk'] == '1') {
		
		$insertquery = $pinquery [6];
		
		if ($insertquery != '') {
			// echo "$insertquery<hr>";
			
			$result = mysqli_query ( $link, $insertquery );
			if (! $result)
				$errorcheck = true;
			
			$numdikaiologisi = mysqli_affected_rows ( $link );
		} // if ($insertquery)
	} // if(isset($_POST['apouchk']) && $_POST['apouchk'] == '1')
	
	$query = "DELETE  FROM `studentstmimata` WHERE `user` = '$parent' ;";
	$result = mysqli_query ( $link, $query );
	
	if (isset ( $_POST ['stutmichk'] ) && $_POST ['stutmichk'] == '1') {
		
		$insertquery = $pinquery [7];
		
		if ($insertquery != '') {
			// echo "$insertquery<hr>";
			
			$result = mysqli_query ( $link, $insertquery );
			if (! $result)
				$errorcheck = true;
			
			$numstudentstmimata = mysqli_affected_rows ( $link );
		} // if ($insertquery)
	} // if (isset($_POST['stutmichk']) && $_POST['stutmichk'] == '1')
	
	if ($errorcheck == true) {
		rollback ();
	} else {
		commit ();
	}
	
	mysqli_close ( $link );
	
	unset ( $_SESSION ['pinquery'] );
} // if ($final )


$smarty = new Smarty ();

$extra_style = '
<style type="text/css">
    th,td { border:none;}
</style>
';

$extra_javascript = '
<script type="text/javascript" language="javascript" >
    // <!--

    function check_submit(){
        var user ="' . $parent . '";
        var answer;

        if (! document.frm.file.value ){alert("Επιλέξτε ένα αρχείο παρακαλώ!"); return false;}

        answer=confirm("Τα νέα δεδομένα που εισάγετε θα αντικαταστήσουν τα όλα τα καταχωρημένα αυτή τη στιγμή δεδομένα του χρήστη " + user + ".\nΕπιβεβαιώστε παρακαλώ.");
        if (answer == false){return false;}
        return true;
    }
    // -->
</script>
';

$smarty->assign ( 'title', 'ΕΙΣΑΓΩΓΗ ΔΕΔΟΜΕΝΩΝ' );
$smarty->assign ( 'extra_style', $extra_style );
$smarty->assign ( 'body_attributes', '' );
$smarty->assign ( 'extra_javascript', $extra_javascript );

$smarty->assign ( 'h1_title', 'Εισαγωγή δεδομένων' );

if (isset ( $numtmimata ))
	$smarty->assign ( 'numtmimata', $numtmimata );
if (isset ( $numstudents ))
	$smarty->assign ( 'numstudents', $numstudents );
if (isset ( $numapousies ))
	$smarty->assign ( 'numapousies', $numapousies );
if (isset ( $numhistory ))
	$smarty->assign ( 'numhistory', $numhistory );
if (isset ( $numapousies_pre ))
	$smarty->assign ( 'numapousies_pre', $numapousies_pre );
if (isset ( $numparameters ))
	$smarty->assign ( 'numparameters', $numparameters );
if (isset ( $numdikaiologisi ))
	$smarty->assign ( 'numdikaiologisi', $numdikaiologisi );
if (isset ( $numstudentstmimata ))
	$smarty->assign ( 'numstudentstmimata', $numstudentstmimata );

if (isset ( $pinquery [0] )) {
	if (trim ( $pinquery [0] ) != '') {
		$tmichecked = "checked";
	}
}
if (isset ( $pinquery [1] )) {
	if (trim ( $pinquery [1] ) != '') {
		$stuchecked = "checked";
	}
}
if (isset ( $pinquery [2] )) {
	if (trim ( $pinquery [2] ) != '') {
		$apouchecked = "checked";
	}
}
if (isset ( $pinquery [3] )) {
	if (trim ( $pinquery [3] ) != '') {
		$histchecked = "checked";
	}
}
if (isset ( $pinquery [4] )) {
	if (trim ( $pinquery [4] ) != '') {
		$apou_prechecked = "checked";
	}
}
if (isset ( $pinquery [5] )) {
	if (trim ( $pinquery [5] ) != '') {
		$paramchecked = "checked";
	}
}
if (isset ( $pinquery [6] )) {
	if (trim ( $pinquery [6] ) != '') {
		$dikchecked = "checked";
	}
}
if (isset ( $pinquery [7] )) {
	if (trim ( $pinquery [7] ) != '') {
		$stutmichecked = "checked";
	}
}

if (isset ( $tmichecked ))
	$smarty->assign ( 'tmichecked', $tmichecked );
if (isset ( $stuchecked ))
	$smarty->assign ( 'stuchecked', $stuchecked );
if (isset ( $apouchecked ))
	$smarty->assign ( 'apouchecked', $apouchecked );
if (isset ( $histchecked ))
	$smarty->assign ( 'histchecked', $histchecked );
if (isset ( $apou_prechecked ))
	$smarty->assign ( 'apou_prechecked', $apou_prechecked );
if (isset ( $paramchecked ))
	$smarty->assign ( 'paramchecked', $paramchecked );
if (isset ( $dikchecked ))
	$smarty->assign ( 'dikchecked', $dikchecked );
if (isset ( $stutmichecked ))
	$smarty->assign ( 'stutmichecked', $stutmichecked );
	
	// $smarty->assign('pinquery', $pinquery);
$smarty->assign ( 'save', $save );
$smarty->assign ( 'final', $final );

if (isset ( $errorcheck ))
	$smarty->assign ( 'errorcheck', $errorcheck );
if (isset ( $errorText ))
	$smarty->assign ( 'errorText', $errorText );

if (isset ( $_FILES ["file"] )) {
	if ($errorText == '' && $_FILES ["file"] ["error"] == 0) {
		$errorchk = 0;
	} elseif ($errorText == '' && $_FILES ["file"] ["error"] > 0) {
		$errorchk = 1;
	}
}

if (isset ( $errorchk ))
	$smarty->assign ( 'errorchk', $errorchk );

$smarty->display ( 'importdata.tpl' );

