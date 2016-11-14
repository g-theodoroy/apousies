function showHint(){
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            var str = xmlhttp.responseText;
            var strArray = str.split(",")

            for (i=0;i<strArray.length;i+=2){
                document.getElementById(strArray[i]).value  = strArray[i+1];
                if ( strArray[i].substring(0, 4) == 'from' ){
                        document.getElementById(strArray[i]).onchange()
                }        
            }
            obj = document.getElementById("daysadded");
            var mydate = document.getElementById("myyear").value +  document.getElementById("mymonth").value + document.getElementById("myday").value;
            obj.innerHTML.indexOf('value="' + mydate + '"')>-1?document.getElementById("daysadded").value = mydate:document.getElementById("daysadded").selectedIndex=-1;
        }
    }
    var myquery = "?chkdate=" + document.getElementById("myyear").value  + document.getElementById("mymonth").value  + document.getElementById("myday").value ;
    xmlhttp.open("GET", "get_apousies_for_date.php" + myquery , true);
    xmlhttp.send();
}

function update_date(){
    var day_value;
    day_value = document.getElementById("daysadded").value;
    document.getElementById("myday").value = day_value.substr(6,2);
    document.getElementById("mymonth").value = day_value.substr(4,2);
    document.getElementById("myyear").value = day_value.substr(0,4);
    document.getElementById("myday").onchange();
}

function day_plus(){
    var chkdate;
    var iDay;
    var iMonth;
    var d = new Date();

    document.getElementById('myday').selectedIndex++;
    if(document.getElementById('myday').selectedIndex==document.getElementById('myday').length-1)
    {
        document.getElementById("myday").selectedIndex=1;
        document.getElementById("mymonth").selectedIndex++;
        if(document.getElementById("mymonth").selectedIndex==document.getElementById("mymonth").length-1)
        {
            document.getElementById("mymonth").selectedIndex=1;
            document.getElementById("myyear").selectedIndex++;
        }
    }

    chkdate = document.getElementById("myday").value + "/" + document.getElementById("mymonth").value + "/" + document.getElementById("myyear").value;

    if (isDate(chkdate) == false){
        day_plus();
        return;
    }

    //έλεχος αν η ημνια είναι Σάββατο ή Κυριακή
    var dateString =  document.getElementById("myyear").value + "/" + document.getElementById("mymonth").value + "/" + document.getElementById("myday").value;  // yyyy/MM/dd
    var d = new Date(dateString);


    if (d.getDay() == 0 || d.getDay() == 6){
        day_plus();
        return;
    }


    document.getElementById("myday").onchange();
}


function day_minus(){
    var chkdate;
    var iDay;
    var iMonth;

    document.getElementById("myday").selectedIndex--;
    if(document.getElementById("myday").selectedIndex==0)
    {
        document.getElementById("myday").selectedIndex=document.getElementById("myday").length-2;
        document.getElementById("mymonth").selectedIndex--;
        if(document.getElementById("mymonth").selectedIndex==0)
        {
            document.getElementById("mymonth").selectedIndex=document.getElementById("mymonth").length-2;
            document.getElementById("myyear").selectedIndex--;
        }
    }
    chkdate = document.getElementById("myday").value + "/" + document.getElementById("mymonth").value + "/" + document.getElementById("myyear").value;

    if (isDate(chkdate) == false){
        day_minus();
        return;
    }

    //έλεχος αν η ημνια είναι Σάββατο ή Κυριακή
    var dateString =  document.getElementById("myyear").value + "/" + document.getElementById("mymonth").value + "/" + document.getElementById("myday").value;  // yyyy/MM/dd
    var d = new Date(dateString);

    if (d.getDay() == 0 || d.getDay() == 6){
        day_minus();
        return;
    }

    document.getElementById("myday").onchange();
}



function send_delete(){
    var answer;
    if (document.getElementById("daysadded").selectedIndex == -1){
        alert("Επιλέξτε ημέρα για διαγραφή");
        return false;
    }
    answer=confirm("Θα διαγραφούν οι απουσίες για τις " + document.getElementById("daysadded").value.substr(6,2) + "/" + document.getElementById("daysadded").value.substr(4,2) + "/" +document.getElementById("daysadded").value.substr(0,4) +  ". Επιβεβαιώστε παρακαλώ.")
    if (answer == false){
        return false;
    }
    document.frm.todo.value = "delete";
    document.frm.submit();
    return true;
}


//ελέγχει αν η ημνια έχει ξανακαταχωρηθεί και
//αν ναι στέλνει εντολή για αντικατάσταση
function date_exists(chkdate){
    var eleghos = false;

    for (var x = 0; x < document.getElementById("daysadded").length; x++)
    {
        if (chkdate  ===  document.getElementById("daysadded").options[x].value){
            eleghos = true;
            break;
        }
    }
    return eleghos;
}


function IsNumeric(strString)
//  check for valid numeric strings
{
    var strValidChars = "0123456789";
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


// έλεγχος για το αν οι ημερομηνίες που εισάγονται είναι έγκυρες
// Declaring valid date character, minimum year and maximum year
var dtCh= "/";
var minYear=1900;
var maxYear=2100;

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
        if (i==4 || i==6 || i==9 || i==11) {
            this[i] = 30
        }
        if (i==2) {
            this[i] = 29
        }
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


//έλεγχος της φόρμας
function valid(form) {
    var elegxos = false;
    var  i = 0;
    var d = 0;
    var chkdate;
    var iDay;
    var iMonth;
    var answer;
    var totaldik;
    var totalmemon;
    var totalapov;
    var totalapday;
    var pre_kod;


    //έλεγχος συμπλήρωσης των τιμών
    for (i=0;i<form.elements.length;i++){
        if (form.elements[i].name.substring(0,2) == "ap" && form.elements[i].value != "" && form.elements[i].value != null)  {
            elegxos = true;
        }
    }
    //αν δεν είναι πατημένο κανένα δίνω οδηγίες
    if ( elegxos == false) {
        alert("Συμπληρώστε τις απουσίες");
        for (i=0;i<form.elements.length;i++){
            if (form.elements[i].name.substring(0,2) == "ap" && (form.elements[i].value == "" || form.elements[i].value == null ))  {
                d = i;
                break;
            }
        }
        form.elements[d].focus();
        return elegxos;
    }

    //έλεγχος για όχι αριθμούς
    for (i=0;i<form.elements.length;i++){
        if ((form.elements[i].name.substring(0,2) == "ap" || form.elements[i].name.substring(0,3) == "dik" || form.elements[i].name.substring(0,2) == "oa" || (form.elements[i].name.substring(0,2) == "da" &&  form.elements[i].name.substring(0,3) != "day" ) || form.elements[i].name.substring(0,2) == "fh" || form.elements[i].name.substring(0,2) == "mh" || form.elements[i].name.substring(0,2) == "lh") && IsNumeric(form.elements[i].value) == false )  {
            alert ("Πληκτρολογείστε μόνο αριθμούς παρακαλώ!");
            form.elements[i].focus();
            return false;
        }
    }

    //έλεγχος αν η ημνια για καταχώρηση είναι έγκυρη
    chkdate = document.getElementById("myday").value + "/" + document.getElementById("mymonth").value + "/" + document.getElementById("myyear").value;

    if (isDate(chkdate) == false){
        alert("Η ημερομηνία '" + chkdate  + "' δεν είναι έγκυρη!")
        return false
    }

    //έλεχος αν η ημνια είναι Σάββατο ή Κυριακή
    var dateString =  document.getElementById("myyear").value + "/" + document.getElementById("mymonth").value + "/" + document.getElementById("myday").value;  // yyyy/MM/dd
    var myday = new Date(dateString);

    if (myday.getDay() == 0 || myday.getDay() == 6){
        alert("Δεν μπορείτε να καταχωρήσετε απουσίες Σάββατο ή Κυριακή!");
        return false;
    }


    // ελέγχει αν υπάρχουν πάνω από 7 απουσίες
    // ή αν έγινε λάθος στην πληκτρολόγηση
    for (i=0;i<myStudentsAm.length;i++){
    
            //αν καταχωρήθηκαν πάνω από 7 απουσίες
            totalapday=0;
            for (x=0;x<apous_def_array.length;x++){
                if (document.getElementById(apous_def_array[x] + myStudentsAm[i]).value!="")totalapday += parseInt(document.getElementById(apous_def_array[x] + myStudentsAm[i] ).value);
            }
            if (totalapday > 7){
                alert ("Ελέγξτε τις απουσίες της ημέρας. Δεν μπορεί να ξεπερνούν τις 7!");
                document.getElementById(apous_def_array[0] + myStudentsAm[i]).focus();
                return false;
            }


             totalapov = 0;
            if (document.getElementById( "oa" + myStudentsAm[i]).value!="")totalapov += parseInt(document.getElementById( "oa" + myStudentsAm[i]).value);
            if (document.getElementById( "da" + myStudentsAm[i]).value!="")totalapov += parseInt(document.getElementById( "da" + myStudentsAm[i]).value);
            if (totalapov > totalapday){
                alert ("Ελέγξτε τις απουσίες από αποβολές. Ξεπερνούν τις απουσίες της ημέρας!");
                document.getElementById( "oa" + myStudentsAm[i]).focus();
                return false;
            }

            //έλεγχος απο αποβολες
            if (document.getElementById( "oa" + myStudentsAm[i]).value !=  ""  &&  document.getElementById( "da" + myStudentsAm[i]).value != ""){
                alert ("Ελέγξτε τις απουσίες από αποβολές. Μπορείτε να καταχωρήσετε είτε ωριαίες είτε ημερήσιες αποβολές, όχι όμως και τα δυο μαζί!");
                document.getElementById( "oa" + myStudentsAm[i]).focus();
                return false;
            }
            if (document.getElementById( "da" + myStudentsAm[i]).value !=  "" &&  document.getElementById( "da" + myStudentsAm[i]).value != totalapday){
                alert ("Οι απουσίες από ημερήσια αποβολή πρέπει να είναι ίσες με τις απουσίες της ημέρας!");
                document.getElementById( "da" + myStudentsAm[i]).focus();
                return false;
            }

            //ελεγχος απουσιών 1ης και τελ ώρας
            if (document.getElementById( "fh" + myStudentsAm[i]).value > 1){
                alert ("Μπορείτε να χρεώσετε μόνο μία απουσία την 1η ώρα!");
                document.getElementById( "fh" + myStudentsAm[i]).focus();
                return false;
            }
            if (document.getElementById( "lh" + myStudentsAm[i]).value > 1){
                alert ("Μπορείτε να χρεώσετε μόνο μία απουσία την τελευταία ώρα!");
                document.getElementById( "lh" + myStudentsAm[i]).focus();
                return false;
            }

            totalmemon = 0;
            if (document.getElementById( "fh" + myStudentsAm[i]).value!="")totalmemon += parseInt(document.getElementById( "fh" + myStudentsAm[i]).value);
            if (document.getElementById( "mh" + myStudentsAm[i]).value!="")totalmemon += parseInt(document.getElementById( "mh" + myStudentsAm[i]).value);
            if (document.getElementById( "lh" + myStudentsAm[i]).value!="")totalmemon += parseInt(document.getElementById( "lh" + myStudentsAm[i]).value);
            if (totalmemon > totalapday){
                alert ("Ελέγξτε τις μεμονωμένες απουσίες. Ξεπερνούν τις απουσίες της ημέρας!");
                document.getElementById( "fh" + myStudentsAm[i]).focus();
                return false;
            }

            if (totalmemon + totalapov > totalapday){
                alert ("Οι απουσίες από αποβολές αθροιζόμενες με τις μεμονωμένες απουσίες δε μπορούν να υπερβαίνουν τις απουσίες της ημέρας!");
                document.getElementById( "fh" + myStudentsAm[i]).focus();
                return false;
            }
            totaldik = 0;
            if (document.getElementById( "dik" + myStudentsAm[i]).value !="" )totaldik = parseInt(document.getElementById( "dik" + myStudentsAm[i]).value);

            if (totaldik == 0 ){
                document.getElementById( "from" + myStudentsAm[i]).value = "";
                document.getElementById( "from" + myStudentsAm[i]).onchange();
            }
 
           if (totaldik > 0 && document.getElementById( "from" + myStudentsAm[i]).value==""){
                alert ("Επιλέγξτε από ποιον είναι δικαιολογημένες οι απουσίες.");
                document.getElementById( "from" + myStudentsAm[i]).focus();
                return false;
            }
            
            if (totaldik > totalapday){
                alert ("Ελέγξτε τις δικαιολογημένες απουσίες. Ξεπερνούν τις απουσίες της ημέρας!");
                document.getElementById( "dik" + myStudentsAm[i]).focus();
                return false;
            }
            if (totaldik  && totaldik !== totalapday){
                answer =  confirm("Οι δικαιολογημένες απουσίες ("  + totaldik + ") διαφέρουν από τις απουσίες της ημέρας (" + totalapday + ").\nΘέλετε παρόλα αυτά να συνεχίσετε;");
                if (answer == false){
                    document.getElementById( "dik" + myStudentsAm[i]).focus();
                    return false;
                }
            }

            pre_kod = myStudentsAm[i];
            var pre_date = new Date(pre_date_array[pre_kod])
            if (totalapday && pre_date>=myday){
                alert("Έχουν καταχωρηθεί προυπάρχουσες απουσίες για το μαθητή-τρια μέχρι τις "  + pre_date.getDate() + "/" + (pre_date.getMonth() + 1 ) +"/" +  + pre_date.getFullYear() + ". Δεν γίνεται να εισάγετε αναλυτικές απουσίες πρίν από αυτή την ημερομηνία.");
                document.getElementById( "apg" + myStudentsAm[i]).focus();
                return false;
            }

    }


    //ελέγχει αν η ημνια έχει ξανακαταχωρηθεί και
    //αν ναι στέλνει εντολή για αντικατάσταση
    var mychkdate = chkdate.substr(6,4)+chkdate.substr(3,2)+chkdate.substr(0,2);
    if (date_exists(mychkdate) ==  true){
        answer =  confirm("Η Ημνια έχει ήδη καταχωρηθεί. Θέλετε αντικατάσταση;");
        if (answer == false){
            form.myday.focus();
        }else{
            form.todo.value = "replace";
        }
        return answer;
    }

}

function unlockboxes(){

    for (i=0;i<myStudentsAm.length;i++){
            for (x=0;x<apous_def_array.length;x++){
                document.getElementById(apous_def_array[x] + myStudentsAm[i]).readOnly=false;
            }
    }
}

function navigate(ev){

    var front;
    var myfront;
    var back;
    var str;
    var index;
    var frontchanged;
    var k;

    var key = ev.keyCode || ev.which;

    str = document.activeElement.name;
    if ( str.substr(0,3)=="dik"){
        front = str.substr(0,3);
        back = str.substr(3);
    }else if (str.substr(0,4)=="from" ){
        front = str.substr(0,4);
        back = str.substr(4);
    }else if (str.substr(0,2)=="ap"){
        front = str.substr(0,2);
        myfront = str.substr(0,3);
        back = str.substr(3);
    }else{
        front = str.substr(0,2);
        back = str.substr(2);
    }

    if (key==39){
        switch(front){
            case "ap":
                for (x=0;x<apous_def_array.length;x++){
                    if (myfront == apous_def_array[x]){
                        k = x+1
                        break;
                    }
                 } 

                frontchanged = false;
                for (x=k;x<apous_def_array.length;x++){
                    if(document.getElementById(apous_def_array[x] + back).readOnly == false){
                        front = apous_def_array[x];
                        frontchanged = true
                        break;
                    }
                }
                if (frontchanged == false){
                        front = "fh";
                }
                break;
            case "fh":
                front="mh"
                break;
            case "mh":
                front="lh"
                break;
            case "lh":
                front="oa"
                break;
            case "oa":
                front="da"
                break;
            case "da":
                front="dik"
                break;
         }
        document.getElementById(front + back).focus();
    }

    if (key==37){
        switch(front){
            case "dik":
                front="da"
                break;
            case "da":
                front="oa"
                break;
            case "oa":
                front="lh"
                break;
            case "lh":
                front="mh"
                break;
            case "mh":
                front="fh"
                break;
            case "fh":
                for (x=apous_def_array.length-1;x>-1;x--){
                    if(document.getElementById(apous_def_array[x] + back).readOnly == false ){
                        front = apous_def_array[x];
                        break;
                    }
                } 
                break;
            case "ap":
                for (x=apous_def_array.length-1;x>=0;x--){
                    if (myfront == apous_def_array[x]){
                        k = x
                        break;
                    }
                 } 
                for (x=k;x>0;x--){
                    if(document.getElementById(apous_def_array[x-1] + back).readOnly == false ){
                        front = apous_def_array[x-1];
                        break;
                    }
                 } 

        }
    }

    if (key==38 || key==40){

        if(front == "ap")front = myfront;

        for (i=0;i<myStudentsAm.length;i++){
            if (myStudentsAm[i]==back){
                index = i;
                break;
            }
        }

        if (key==38){
             index--;
             if (index<0)index=0;
            back = myStudentsAm[index];
        }
    }

    if (key==40){
        index++;
         if (index>myStudentsAm.length-1)index=myStudentsAm.length-1;
        back = myStudentsAm[index];
    }

if (key==37 || key==38 || key==39 || key==40){
    document.getElementById(front + back).focus();
}

}

function selectfrom_onchange(am){
    var sumap = 0;
    if (document.getElementById('from' + am ).value == ""){
        document.getElementById('dik' + am ).value = ""
    } else {
        for (x=0;x<apous_def_array.length;x++){
            if (parseInt(document.getElementById(apous_def_array[x] + am ).value)){sumap+=parseInt(document.getElementById(apous_def_array[x] + am ).value)} 
        }
        if (sumap==0){sumap=""}
    }
    if (sumap){
    document.getElementById('dik' + am ).className = document.getElementById('from' + am )[document.getElementById('from' + am ).selectedIndex].label ;
    if( !document.getElementById('dik' + am ).value)document.getElementById('dik' + am ).value = sumap
   }else{
    document.getElementById('dik' + am ).className = 'white'
    document.getElementById('from' + am ).value = ''
    }

}

function setdikwhite(){

    for (i=0;i<myStudentsAm.length;i++){
                document.getElementById("dik" + myStudentsAm[i]).className="white";
    }

}  

function mark4tmima(tmima){
    
    for (i=0;i<myStudentsAm.length;i++){
           if (document.getElementById("tdname" + myStudentsAm[i]).title.indexOf(tmima + " ") !== -1){
                document.getElementById("tdname" + myStudentsAm[i]).className="lightgrey";
            }else{
              document.getElementById("tdname" + myStudentsAm[i]).className="white";
            }
    }
    
    
}