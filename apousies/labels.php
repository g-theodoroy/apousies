<?php
require_once ('common.php');
checkUser ();
checktmima ();

$apous_count = count ( $apousies_define );

isset ( $_SESSION ['userName'] ) ? $user = $_SESSION ['userName'] : $user = '';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset ( $_SESSION ['tmima'] ) ? $tmima = $_SESSION ['tmima'] : $tmima = '';

isset ( $_POST ['papersize'] ) ? $paper_size = $_POST ['papersize'] : $paper_size = 'A4';

isset ( $_POST ['page_top'] ) ? $page_top = $_POST ['page_top'] : $page_top = 5;
isset ( $_POST ['page_bottom'] ) ? $page_bottom = $_POST ['page_bottom'] : $page_bottom = 5;
isset ( $_POST ['page_left'] ) ? $page_left = $_POST ['page_left'] : $page_left = 5;
isset ( $_POST ['page_right'] ) ? $page_right = $_POST ['page_right'] : $page_right = 5;

isset ( $_POST ['cols'] ) ? $cols = $_POST ['cols'] : $cols = 3;
isset ( $_POST ['rows'] ) ? $rows = $_POST ['rows'] : $rows = 8;

isset ( $_POST ['showborders'] ) ? $showborders = $_POST ['showborders'] : $showborders = 0;

isset ( $_POST ['apousnum'] ) ? $apousnum = $_POST ['apousnum'] : $apousnum = '';

isset ( $_POST ['submit'] ) ? $target = $_POST ['submit'] : $target = 'print';

$showborders == '0' ? $labelborders = 'border:none;' : $labelborders = 'border:1px solid black;';

switch ($paper_size) {
	case 'A4' :
		$page_width = 210;
		$page_height = 297;
		$totalwidth = $page_width - ($page_left + $page_right);
		$totalheight = $page_height - ($page_top + $page_bottom);
		break;
}

$labelwidth = $totalwidth / $cols;
$labelheight = $totalheight / $rows;
$labelpaddind = $labelwidth / 10;

$template = 'labels.tpl';

// συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

if ($apousnum == '' || $apousnum == 0) {
	
	$query = "SELECT `students`.`ep_kidemona` , `students`.`on_kidemona` , `students`.`dieythinsi` , `students`.`tk`, `students`.`poli` 
    FROM `students`  JOIN `studentstmimata` on (`students`.`user` = `studentstmimata`.`user`  and  `am` = `student_am`) 
    WHERE `students`.`user` = '$parent' and  `studentstmimata`.`tmima` = '$tmima' ORDER BY `epitheto`,`onoma` ASC ;";
} else {
	// $query = "SELECT `students`.`am`, `students`.`epitheto`, `students`.`onoma`, `students`.`patronimo` , `students`.`ep_kidemona` , `students`.`on_kidemona` , `students`.`dieythinsi` , `students`.`tk`, `students`.`poli`, `students`.`til1`, `students`.`til2`, `students`.`filo` FROM `students` INNER JOIN (SELECT `student_am` FROM `apousies` where `tmima`= '$tmima' and `user`='$parent' group by `student_am`, `user`,`tmima` having sum(`ap`)+ sum(`apk`)+ sum(`ape`)> $apousnum) as t1 ON (`students`.`am` = `t1`.`student_am` ) WHERE `students`.`user` = '$parent' AND `students`.`tmima`= '$tmima' ORDER BY `epitheto`, `onoma` ASC; ";
	$sumstr = '';
	$presumstr = '';
	for($x = 0; $x <= $apous_count; $x ++) {
		$k = $x + 1;
		$y = $x * 3 + 1;
		$sumstr .= "SUM( MID(`apousies`.`apous`,$k,1)) +";
		$presumstr .= "CONVERT( MID(`apousies_pre`.`apous`,$y,3), UNSIGNED INTEGER) +";
	}
	$sumstr = substr ( $sumstr, 0, - 1 ) . "as `sumap`";
	$presumstr = substr ( $presumstr, 0, - 1 ) . "as `sumap`";
	
	$sumdikstr = '';
	
	$query = "SELECT `students`.`ep_kidemona` , `students`.`on_kidemona` , `students`.`dieythinsi` , `students`.`tk`, `students`.`poli` 
FROM `students`
JOIN 
(SELECT `user`,`student_am`, SUM(`t1`.`sumap`) as `sumap` FROM  
(SELECT `user`, `apousies`.`student_am`,$sumstr  FROM `apousies`
where `apousies`.`user` = '$parent' group by  `apousies`.`student_am`
UNION
SELECT `user`, `apousies_pre`.`student_am`, $presumstr FROM `apousies_pre`
where `apousies_pre`.`user` = '$parent' ) as t1
group by `student_am`
having `sumap`>$apousnum) as `t2`
on `students`.`user` = `t2`.`user` AND  `students`.`am` = `t2`.`student_am` 
JOIN `studentstmimata` on `students`.`user` = `studentstmimata`.`user`  and  `am` = `studentstmimata`.`student_am`
WHERE  `studentstmimata`.`tmima` = '$tmima' 
ORDER BY `epitheto`,`onoma` ASC ;";
}

// echo $query . "<hr>";

$result = mysqli_query ( $link, $query );

if (! $result) {
	$errorText = mysqli_error ( $link );
	echo "1 $errorText<hr>";
}

$num = mysqli_num_rows ( $result );

mysqli_close ( $link );

$labelsdata = array ();

$k = 0;
for($i = 0; $i < $num; $i ++) {
	$row = mysqli_fetch_assoc ( $result );
	$col = $i % $cols;
	$labelsdata [$k] [$col] ['name'] = $row ["ep_kidemona"] . " " . $row ["on_kidemona"];
	$labelsdata [$k] [$col] ['dieythinsi'] = $row ["dieythinsi"];
	$labelsdata [$k] [$col] ['tk'] = $row ["tk"];
	$labelsdata [$k] [$col] ['poli'] = $row ["poli"];
	if ($col == $cols - 1)
		$k ++;
}

$num % 3 != 0 ? $addcells = 3 - ($num % 3) : $addcells = 0;


$paper = new Smarty ();
$paper->assign ( 'title', 'ΕΚΤΥΠΩΣΗ ΕΤΙΚΕΤΩΝ' );
$extra_style = "
        <style type=\"text/css\">
            table.fixedlayout
            {
                table-layout: fixed;
                width : {$totalwidth}mm;
                align : center;
                margin : 0 auto 0 auto; 
            }

            td.mylabel
            {
                font-weight : bold;
                font-size : 12px;
                padding-left : {$labelpaddind}mm;
                padding-right : {$labelpaddind}mm;
                height : {$labelheight}mm;
                text-align : left;
                width : {$labelwidth}mm;
                overflow: hidden;
                {$labelborders}
            }
            p.breakhere 
            {
                page-break-before: always
            }

        </style>
";

$paper->assign ( 'extra_style', $extra_style );
$paper->assign ( 'labelsdata', $labelsdata );
$paper->assign ( 'rows', $rows - 1 );
$paper->assign ( 'addcells', $addcells );

if ($target == 'print') {
	$paper->display ( 'labels.tpl' );
}
if ($target == 'pdf') {
	$html = $paper->fetch ( 'labels.tpl' );
	
	
	
	// $page_top = 5;
	// $page_bottom = 5;
	// $page_left = 5;
	// $page_right = 5;
	$page_format = 'A4';
	$font_size = 8;
	$orientation = 'P';
	
	$mpdf = new mPDF ( '', // mode - default ''
$page_format, // format - A4, for example, default ''
$font_size, // font size - default 0
'', // default font family
$page_left, // margin_left
$page_right, // margin right
$page_top, // margin top
$page_bottom, // margin bottom
0, // margin header
0, // margin footer
$orientation ); // L - landscape, P - portrait
	
	$mpdf->WriteHTML ( $html );
	
	$filename = "Ετικέτες_$parent\_$tmima.pdf";
	
	$mpdf->Output ( $filename, 'D' );
	exit ();
}
?>
