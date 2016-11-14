<?php
require_once('common.php');
checkUser();
checktmima();

isset($_SESSION['userName']) ? $user = $_SESSION['userName'] : $user ='';
isset ( $_SESSION ['parent'] ) ? $parent = $_SESSION ['parent'] : $parent = '';
isset($_SESSION['tmima']) ? $tmima = $_SESSION['tmima'] : $tmima ='';

$apous_count = count($apousies_define);
$dik_count = count($dikaiologisi_define);
$novalue = str_repeat('0', $apous_count) . '0-00000';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="el">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- Liquid Blueprint CSS -->
<link rel="stylesheet" href="{$style_prefix}blueprint/reset.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="{$style_prefix}blueprint/liquid.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="{$style_prefix}blueprint/typography.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="{$style_prefix}blueprint/fancy-type.css" type="text/css" media="screen, projection">
<!--[if IE]><link rel="stylesheet" href="../blueprint/lib/ie.css" type="text/css" media="screen, projection"><![endif]-->
	
<style type="text/css">
	td, th {vertical-align:middle;border:none;}
</style>



<script language="javascript" type="text/javascript">
  // <!--

// Declaring valid date character, minimum year and maximum year
var dtCh= "/";
var minYear=1900;
var maxYear=2100;

var apou_def_array = new Array();

<?php
$x = 0;
foreach ($apousies_define as $key => $value){
    print   "apou_def_array[$x] = 'ap" . $value['kod'] . "'\n" ; 
    $x++;
}
?>

var dik_def_array = new Array();
var color_def_array = new Array();

<?php
$x = 0;
foreach ($dikaiologisi_define as $key => $value){
    print   "dik_def_array[$x] ='" . $value['kod'] . "'\n" ; 
    print   "color_def_array[$x] ='" . $value['color'] . "'\n" ; 
    $x++;
}
?>


function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strDay=dtStr.substring(0,pos1)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
//		alert("The date format should be : mm/dd/yyyy")
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
//		alert("Please enter a valid month")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
//		alert("Please enter a valid day")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
//		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
//		alert("Please enter a valid date")
		return false
	}
return true
}




function day_plus(){
var d = new Date();
var iDay;
var iMonth;
var nMonth;
var nYear;

document.frm.myday.selectedIndex++;
if(document.frm.myday.selectedIndex==document.frm.myday.length-1)
{
	document.frm.myday.selectedIndex=1;
	document.frm.mymonth.selectedIndex++;
	if(document.frm.mymonth.selectedIndex==document.frm.mymonth.length-1)
	{
		document.frm.mymonth.selectedIndex=1;
	}
}
iDay = document.frm.myday.value;
iMonth = parseInt(document.frm.mymonth.value);
iMonth+=9;
iMonth > 12 ? iMonth-=12 : iMonth;

nMonth= parseInt(d.getMonth()+1);
nYear = parseInt(d.getFullYear());

if(iMonth < 7 && nMonth > 6) nYear++;
if(iMonth > 6 && nMonth < 7 )nYear--;


iDay = (iDay < 10)? "0" + iDay : iDay;
iMonth = (iMonth < 10)? "0" + iMonth : iMonth;

chkdate = iDay + "/" + iMonth + "/" + nYear;

if (isDate(chkdate) == false){
	day_plus();
	return;
}

//έλεχος αν η ημνια είναι Σάββατο ή Κυριακή
var dateString =  nYear + "/" + iMonth + "/" + iDay;  // yyyy/MM/dd
var myday = new Date(dateString);


if (myday.getDay() == 0 || myday.getDay() == 6){
	day_plus();
	return;
}
document.frm.myday.onchange();
}


function day_minus(){
var d = new Date();
var myday = new Date();
var iDay;
var iMonth;
var nMonth;
var nYear;

document.frm.myday.selectedIndex--;
if(document.frm.myday.selectedIndex==0)
{
	document.frm.myday.selectedIndex=document.frm.myday.length-2;
	document.frm.mymonth.selectedIndex--;
	if(document.frm.mymonth.selectedIndex==0)
	{
		document.frm.mymonth.selectedIndex=document.frm.mymonth.length-2;
	}
}
iDay = document.frm.myday.value;
iMonth = parseInt(document.frm.mymonth.value);
iMonth+=9;
iMonth > 12 ? iMonth-=12 : iMonth;

nMonth= parseInt(d.getMonth()+1);
nYear = parseInt(d.getFullYear());

if(iMonth < 7 && nMonth > 6) nYear++;
if(iMonth > 6 && nMonth < 7 )nYear--;

iDay = (iDay < 10)? "0" + iDay : iDay;
iMonth = (iMonth < 10)? "0" + iMonth : iMonth;

chkdate = iDay + "/" + iMonth + "/" + nYear;

if (isDate(chkdate) == false){
	day_minus();
	return;
}

//έλεχος αν η ημνια είναι Σάββατο ή Κυριακή
var dateString =  nYear + "/" + iMonth + "/" + iDay;  // yyyy/MM/dd
var myday = new Date(dateString);

if (myday.getDay() == 0 || myday.getDay() == 6){
	day_minus();
	return;
}
document.frm.myday.onchange();
}


function IsNumeric(strString)
   //  check for valid numeric strings	
   {
   var strValidChars = "123456789";
   var strChar;
   var blnResult = true;

//   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         blnResult = false;
         }
      }
   return blnResult;
   }



function set_value(){
var stoxos;
var max_apo;
var answer;
var totaldik;
var totalmemon;
var totalapov;
var totsumap;
var d = new Date();
var iDay;
var iMonth;
var nMonth;
var nYear;
var pre_date_array = new Array();
var apouscount = <?php echo count($apousies_define) ?>;
<?php
//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

$query =  "SELECT  `student_am`,`mydate` FROM `apousies_pre`  WHERE `user` = '$parent' ;";
	
$result= mysqli_query($link, $query);

if (!$result) {
	$errorText= mysqli_error($link);
	echo "5 $errorText<hr>";
}

$num= mysqli_num_rows($result);

while ($row = mysqli_fetch_assoc($result)) {
	$kod = $row["student_am"];
        $checkdate = $row["mydate"];
	print "pre_date_array['$kod'] = '" . substr($checkdate, 0, 4) . "/" . substr($checkdate, 4, 2) . "/" . substr($checkdate, 6, 2) . "'\n";
}
mysqli_close($link);

?>

iDay = document.frm.myday.value;
iMonth = parseInt(document.frm.mymonth.value);
iMonth+=9;
iMonth > 12 ? iMonth-=12 : iMonth;

nMonth= parseInt(d.getMonth()+1);
nYear = parseInt(d.getFullYear());

if(iMonth < 7 && nMonth > 6) nYear++;
if(iMonth > 6 && nMonth < 7 )nYear--;

iDay = (iDay < 10)? "0" + iDay : iDay;
iMonth = (iMonth < 10)? "0" + iMonth : iMonth;

chkdate = iDay + "/" + iMonth + "/" + nYear;

if (isDate(chkdate) == false){
	alert ("Η ημερομηνία " + chkdate + " δεν είναι έγκυρη!");
	return false;
}

//έλεχος αν η ημνια είναι Σάββατο ή Κυριακή
var dateString =  nYear + "/" + iMonth + "/" + iDay;  // yyyy/MM/dd
var myday = new Date(dateString);

if (myday.getDay() == 0 || myday.getDay() == 6){
	alert ("Δεν μπορείτε να καταχωρήσετε απουσίες Σάββατο ή Κυριακή!");
	return false;
}

//βρίσκω τη σειρά του κουμπιού - ο μήνας έχει 31χ4=124 - η κάθε μέρα 4
stoxos =   document.frm.mymonth.value*(apouscount + 1)*31 + (document.frm.myday.value -1)* (apouscount + 1) + 4;

var errormesage = "Πληκτρολογείστε μόνο αριθμούς παρακαλώ!";
for (x=0 ; x < apou_def_array.length ; x++){
    if (!IsNumeric(document.getElementById(apou_def_array[x]).value)){alert(errormesage);document.getElementById(apou_def_array[x]).focus();return;}
}

if (IsNumeric(document.frm.dik.value)==false){alert(errormesage);document.frm.dik.focus();return;}
if (IsNumeric(document.frm.oa.value)==false){alert(errormesage);document.frm.oa.focus();return;}
if (IsNumeric(document.frm.da.value)==false){alert(errormesage);document.frm.da.focus();return;}
if (IsNumeric(document.frm.fh.value)==false){alert(errormesage);document.frm.fh.focus();return;}
if (IsNumeric(document.frm.mh.value)==false){alert(errormesage);document.frm.mh.focus();return;}
if (IsNumeric(document.frm.lh.value)==false){alert(errormesage);document.frm.lh.focus();return;}


//άθροισμα απουσιών ημέρας πάνω από 7
totalsumap = 0;
for (x=0 ; x<apou_def_array.length ; x++){
    if (document.getElementById(apou_def_array[x]).value!="")totalsumap += parseInt(document.getElementById(apou_def_array[x]).value);
}
 
if (totalsumap > 7){alert ("Οι συνολικές απουσίες της ημέρας δεν μπορούν να ξεπερνούν τις 7 !");document.getElementById(apou_def_array[0]).focus();return;}
max_apo = totalsumap;

var pre_kod = opener.document.frm.newstudent.value;
var pre_date = new Date(pre_date_array[pre_kod])

if (totalsumap && pre_date>=myday){
alert("Έχουν καταχωρηθεί προυπάρχουσες απουσίες για το μαθητή-τρια μέχρι τις "  + pre_date.getDate() + "/" + (pre_date.getMonth() + 1 ) +"/" +  + pre_date.getFullYear() + ". Δεν γίνεται να εισάγετε αναλυτικές απουσίες πρίν από αυτή την ημερομηνία.");
document.frm.apousies.focus();return;
}

//αν το άθροισμα δικαιολογημένων πάνω από τις απουσίες
if (document.frm.dik.value > totalsumap){alert ("Οι δικαιολογημένες απουσίες δεν μπορούν να ξεπερνούν τις απουσίες ημέρας!");document.frm.dik.focus();return;}

if (document.frm.da.value !=  ""  &&  document.frm.da.value != totalsumap){alert ("Οι απουσίες από ημερήσια αποβολή πρέπει να είναι ίσες με τις απουσίες της ημέρας!");document.frm.da.focus();return;}

if (document.frm.fh.value > 1){alert ("Μπορείτε να χρεώσετε μόνο μία απουσία την 1η ώρα!");document.frm.fh.focus();return;}
if (document.frm.lh.value > 1){alert ("Μπορείτε να χρεώσετε μόνο μία απουσία την τελευταία ώρα!");document.frm.lh.focus();return;}

totalapov = 0;
if (document.frm.oa.value!="")totalapov += parseInt(document.frm.oa.value);
if (document.frm.da.value!="")totalapov += parseInt(document.frm.da.value);
if (totalapov > totalsumap){alert ("Ελέγξτε τις απουσίες από αποβολές. Ξεπερνούν τις απουσίες της ημέρας!");document.frm.oa.focus();return;}

totalmemon = 0;
if (document.frm.fh.value!="")totalmemon += parseInt(document.frm.fh.value);
if (document.frm.mh.value!="")totalmemon += parseInt(document.frm.mh.value);
if (document.frm.lh.value!="")totalmemon += parseInt(document.frm.lh.value);
if (totalmemon > totalsumap){alert ("Ελέγξτε τις μεμονωμένες απουσίες. Ξεπερνούν τις απουσίες της ημέρας!");document.frm.fh.focus();return;}

if (totalmemon + totalapov > totalsumap){alert ("Οι απουσίες από αποβολές αθροιζόμενες με τις μεμονωμένες απουσίες δε μπορούν να υπερβαίνουν τις απουσίες της ημέρας!");document.frm.oa.focus();return;}


if ( totalsumap == 0 ){
	opener.document.frm.elements[stoxos].value = "<?php echo $novalue; ?>";
        
        for (x=0 ; x< apou_def_array.length ; x++){
                index = stoxos+1+x;
                opener.document.frm.elements[index].value = "";
                opener.document.frm.elements[index].className = "white";
         }
	document.frm.reset();
	document.frm.myday.focus();
	document.frm.changecheck.value='1';
	opener.document.frm.todo.value='save';
	return;
}

if (opener.document.frm.elements[ stoxos ].value != "<?php echo $novalue; ?>" ){
	answer = confirm ("Η ημερομηνία που επεξεργάζεστε δεν είναι κενή.\nΝα γίνει αντικατάσταση;")
	if (!answer) return;
}

document.frm.changecheck.value='1';
opener.document.frm.todo.value='save';


var newvalue=''; 
var newclass=''; 

for (x=0 ; x< apou_def_array.length ; x++){
    if(document.getElementById(apou_def_array[x]).value==""){newvalue += "0";}else{newvalue += document.getElementById(apou_def_array[x]).value;}
}


if (document.frm.dik.value =="" || document.frm.dik.value==0){
newvalue= newvalue + "0-";
newclass ='white';
}else{
        for (x=0 ; x< dik_def_array.length ; x++){
            if(document.frm.apo[x].checked == true){
                newvalue = newvalue +  document.frm.dik.value + document.frm.apo[x].value;
                newclass = color_def_array[x];
                break;
            }
        }
}

if(document.frm.fh.value==""){newvalue= newvalue + "0";}else{newvalue= newvalue + document.frm.fh.value;}
if(document.frm.mh.value==""){newvalue= newvalue + "0";}else{newvalue= newvalue + document.frm.mh.value;}
if(document.frm.lh.value==""){newvalue= newvalue + "0";}else{newvalue= newvalue + document.frm.lh.value;}

if(document.frm.oa.value==""){newvalue= newvalue + "0";}else{newvalue= newvalue + document.frm.oa.value;newclass= newclass + ' marked';}
if(document.frm.da.value==""){newvalue= newvalue + "0";}else{newvalue= newvalue + document.frm.da.value;newclass= newclass + ' marked';}


opener.document.frm.elements[ stoxos ].value = newvalue;

for (x=0 ; x< apou_def_array.length ; x++){
            index =  stoxos+1+x;
    if(document.getElementById(apou_def_array[x]).value!="" && document.getElementById(apou_def_array[x]).value!="0"){
            opener.document.frm.elements[index ].value = document.getElementById(apou_def_array[x]).value;
            opener.document.frm.elements[index ].className = newclass;
        }else{
            opener.document.frm.elements[ index ].value = '';
            opener.document.frm.elements[ index ].className = 'white';
        }
}

document.frm.submit();
}


function get_value(){
var stoxos ;
var totsumap ;
var index ;
var apouscount = <?php echo count($apousies_define) ?>;
stoxos =   document.frm.mymonth.value*(apouscount+1)*31 + (document.frm.myday.value -1)*(apouscount+1) + 4;


for (i=0 ; i< <?php echo $apous_count; ?> ; i++){
    if(opener.document.frm.elements[stoxos].value.substr(i,1)==0){
        document.getElementById(apou_def_array[i]).value = '';
    }else{
        document.getElementById(apou_def_array[i]).value = opener.document.frm.elements[ stoxos ].value.substr(i,1);
    }
}

index = <?php echo $apous_count ; ?>;

if (opener.document.frm.elements[ stoxos ].value.substr(index,2) == "0-" ){
	for (i=0 ;i< <?php echo $dik_count; ?> ; i++){
            document.frm.apo[i].checked = false;
        }
	document.frm.dik.value = "";
}else{
	for (i=0 ;i< <?php echo $dik_count; ?> ; i++){
            if(opener.document.frm.elements[stoxos].value.substr(index+1,1) == dik_def_array[i]){
                document.frm.apo[i].checked = true;
            }else{
                document.frm.apo[i].checked = false;
            }
        }
	document.frm.dik.value = opener.document.frm.elements[ stoxos].value.substr(index,1);
}    

if(document.frm.dik.value==0)document.frm.dik.value="";

index += 2;

if(opener.document.frm.elements[ stoxos ].value.substr(index,1)==0){document.frm.fh.value="";}else{document.frm.fh.value = opener.document.frm.elements[ stoxos ].value.substr(index,1);}
index++;
if(opener.document.frm.elements[ stoxos ].value.substr(index,1)==0){document.frm.mh.value="";}else{document.frm.mh.value = opener.document.frm.elements[ stoxos ].value.substr(index,1);}
index++;
if(opener.document.frm.elements[ stoxos ].value.substr(index,1)==0){document.frm.lh.value="";}else{document.frm.lh.value = opener.document.frm.elements[ stoxos ].value.substr(index,1);}
index++;
if(opener.document.frm.elements[ stoxos ].value.substr(index,1)==0){document.frm.oa.value="";}else{document.frm.oa.value = opener.document.frm.elements[ stoxos ].value.substr(index,1);}
index++;
if(opener.document.frm.elements[ stoxos ].value.substr(index,1)==0){document.frm.da.value="";}else{document.frm.da.value = opener.document.frm.elements[ stoxos ].value.substr(index,1);}

document.frm.myday.focus();
}

function SumDikApousies(){
var sumap;
sumap=0;
for (i=0 ; i< <?php echo $apous_count; ?> ; i++){
    if(document.getElementById(apou_def_array[i]).value!="")sumap+=parseInt(document.getElementById(apou_def_array[i]).value);
}
document.frm.dik.value = sumap;
}

function unlockboxes(){
    for (i=0 ; i< <?php echo $apous_count; ?> ; i++){
        document.getElementById(apou_def_array[i]).readOnly=false;
    }
}

  // -->
</script>

</head>
<body onload="get_value();" >

<div class="container">
	<!-- HEADER -->
	<div class="block">
		<div class="column span-9 prepend-2 last">

<?php
if (isset($_POST["mymonth"])){
	$mymonth = trim($_POST["mymonth"]); 
}else{
	if (date("m")>8) $mymonth = date("m")- 9; else  $mymonth = date("m") + 12 - 9;
}
if (isset($_POST["myday"])) $myday = trim($_POST["myday"]);else $myday = date("d");
?>
<div class="corners">

<form name="frm" method="post" action='' >
<input type="hidden" name="changecheck" value="<?php echo isset($_POST["changecheck"])?$_POST["changecheck"]:"0" ?>">
<table border="0"   cellpadding = "2" cellspacing="0" align="center" >
  <tbody>
    <tr>
      <td  align="center" colspan="3">
<button type="button" tabindex="1" name="minus1" value="minus" onclick="day_minus()">&nbsp;&lt;&lt;&nbsp;</button>
&nbsp;
	<SELECT name="myday" tabindex="2" onchange="get_value()">
	<option>&nbsp;</option>
<?php
if (isset($_POST["mymonth"])) $myday = trim($_POST["myday"]);

for ($i = 1 ;$i<32 ;$i++ ) {
	if ($myday == $i)		
		echo "<option value='$i' selected>$i</option>\n";
	else
		echo "<option value='$i' >$i</option>\n";
}
?>
	<option>&nbsp;</option>
	</SELECT >
&nbsp;
	<SELECT name="mymonth" tabindex="3" onchange="get_value()">
	<option>&nbsp;</option>
<?php
if (isset($_POST["mymonth"])) $mymonth = trim($_POST["mymonth"]);

$monthnames = array("Σεπτέμβριος","Οκτώβριος","Νοέμβριος","Δεκέμβριος","Ιανουάριος","Φεβρουάριος","Μάρτιος","Απρίλιος","Μάιος");
for ($i = 0 ;$i<9 ;$i++ ) {
	if ($mymonth == $i)		
		echo "<option value='$i' selected>$monthnames[$i]</option>\n";
	else
		echo "<option value='$i' >$monthnames[$i]</option>\n";
}
?>
	<option>&nbsp;</option>
	</SELECT >&nbsp;
<button type="button" name="plus1" tabindex="5" value="plus" onclick="day_plus();">&nbsp;&gt;&gt;&nbsp;</button>
	</td>
</TR>
<?php

//συνδέομαι με τη βάση
include ("includes/dbinfo.inc.php");

//ελεγχος για τον τύπο του τμήματος
$query1 =  "SELECT `type` FROM `tmimata` WHERE `username` = '$parent' AND `tmima`= '$tmima'  ;";
$result1= mysqli_query($link, $query1);
if (!$result1) {
 	$errorText= mysql1_error($link);
	echo "7 $errorText<hr>";
}
$row1 = mysqli_fetch_assoc($result1);
$type = $row1["type"];

$state = array();

for ($x = 0; $x < count($apousies_define); $x++) {
    if ($apousies_define[$x]["kod"] == $type){
        $state[$x] = '';
    }else{
        $state[$x] = 'readOnly=true';
    }
}

mysqli_close($link);

?>
<tr><td colspan="3"><hr></td></tr>
<tr>
      <td rowspan="3" ><h3 align="center">ΑΠΟΥΣΙΕΣ <br>ΗΜΕΡΑΣ</h3></td>

<?php
echo '<td align="center" >' . substr($apousies_define[0]['perigrafi'],0,6)  . '</td>
       <td align="center">
<INPUT type="text" name="ap' . $apousies_define[0]['kod'] . '" id="ap' . $apousies_define[0]['kod'] . '" size="1" maxlength="1" tabindex="6" style="font-family :serif ; font-size : 1.5em; font-weight : bold; text-align : center;" ' . $state[0] . ' >
</td>
   </tr>
';

for ($i = 1 ; $i < $apous_count ; $i++){
    $tabindex =  6 + $i;
 echo '
<tr>
      <td align="center" > ' .  substr($apousies_define[$i]['perigrafi'],0,6)  . ' </td>
      <td align="center">
<INPUT type="text" name="ap' . $apousies_define[$i]['kod'] . '" id="ap' . $apousies_define[$i]['kod'] . '" size="1" maxlength="1" tabindex="' . $tabindex . '" style="font-family :serif ; font-size : 1.5em; font-weight : bold; text-align : center;" ' . $state[$i] . ' >
</td>
   </tr>
     
';
    
}

?>
      
<tr><td colspan="3"><hr></td></tr>
    <tr>
      <td colspan="3" align="center">ΔΙΚΑΙΟΛΟΓΗΜΕΝΕΣ ΑΠΟ</td>
    </tr>
<?php
$tabindex =  9 + $dik_count;
 echo '
    <tr>
      <td  align="right">' .  $dikaiologisi_define[0]['perigrafi'] . '</td>
      <td align="center"><INPUT type="radio" name="apo" tabindex="9" value="' .  $dikaiologisi_define[0]['kod'] . '"  onclick="SumDikApousies();"></td>
      <td align="center" rowspan="3"><INPUT type="text" name="dik"  tabindex="' . $tabindex . '" size="1" maxlength="1" style="font-family :serif ; font-size : 1.5em; font-weight : bold; text-align : center;"></td>
    </tr>
';
 
 for ($i = 1 ; $i < $dik_count ; $i++){
    $tabindex =  9 + $i;
    $value =  $dikaiologisi_define[$i]['kod'];
 echo '
<tr>
      <td align="right" > ' .  $dikaiologisi_define[$i]['perigrafi']  . ' </td>
      <td align="center"><INPUT type="radio" name="apo"  tabindex="' . $tabindex . '" value="' . $value . '" onclick="SumDikApousies()"></td>
</tr>
     
';
}
?>
<tr><td colspan="3"><hr></td></tr>
    <tr>
      <td align="center" colspan="2">
              ΑΠΟ ΩΡΙΑΙΕΣ ΑΠΟΒΟΛΕΣ
            </td>
      <td align="center"><INPUT type="text" name="oa"  tabindex="14" size="1" maxlength="1" style="font-family :serif ; font-size : 1.5em; font-weight : bold; text-align : center;"></td>
    </tr>
    <tr>
      <td align="center" colspan="2">
              ΑΠΟ ΗΜΕΡΗΣΙΑ ΑΠΟΒΟΛΗ
            </td>
      <td align="center"><INPUT type="text" name="da"  tabindex="15" size="1" maxlength="1" style="font-family :serif ; font-size : 1.5em; font-weight : bold; text-align : center;"></td>
    </tr>
	<tr><td colspan="3"><hr></td></tr>
    <tr>
      <td align="center" colspan="2">1η ΩΡΑ</td>
      <td align="center"><INPUT type="text" name="fh"  tabindex="16" size="1" maxlength="1" style="font-family :serif ; font-size : 1.5em; font-weight : bold; text-align : center;"></td>
    </tr>
    <tr>
      <td align="center" colspan="2">
              ΕΝΔΙΑΜΕΣΕΣ ΩΡΕΣ
            </td>
      <td align="center"><INPUT type="text" name="mh"  tabindex="17" size="1" maxlength="1" style="font-family :serif ; font-size : 1.5em; font-weight : bold; text-align : center;"></td>
    </tr>
    <tr>
      <td align="center" colspan="2">ΤΕΛΕΥΤΑΙΑ ΩΡΑ</td>
      <td align="center"><INPUT type="text" name="lh"  tabindex="18" size="1" maxlength="1" style="font-family :serif ; font-size : 1.5em; font-weight : bold; text-align : center;"></td>
    </tr>
	<tr><td colspan="3"><hr></td></tr>
  </tbody>
</table>
<h4 align="center">
<button type="button" name="insert" value="insert"  onclick="set_value();">ΕΙΣΑΓΩΓΗ</button>&nbsp;
<button type="button" name="unlock" value="unlock" onclick="unlockboxes()"  >ΞΕΚΛΕΙΔΩΜΑ</button>&nbsp;
<button type="button" name="close" value="exit" onclick="if(document.frm.changecheck.value=='1'){opener.document.frm.submit();}self.close();">ΕΞΟΔΟΣ</button>
</h4>
</form>
</div>
</div>
</body>
</html>
