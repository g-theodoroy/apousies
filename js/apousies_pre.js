
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
        alert("Η μορφή ημερομηνίας πρέπει να είωαι : ηη/μμ/εεεε")
        return false
    }
    if (strMonth.length<1 || month<1 || month>12){
        alert("Εισάγετε έγκυρο μήνα")
        return false
    }
    if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
        alert("Εισάγετε έγκυρη ημέρα")
        return false
    }
    if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
        alert("Εισάγετε έγκυρο 4ψήφιο έτος μεταξύ "+minYear+" και "+maxYear)
        return false
    }
    if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
        alert("Εισάγετε έγκυρη ημερομηνία")
        return false
    }
    return true
}

function changedatestamp(dtStr){
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
    return strYr + "/" + strMonth + "/" + strDay
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


    //έλεγχος για όχι αριθμούς
    for (i=0;i<form.elements.length;i++){
        if ((form.elements[i].name.substring(0,2) == "ap" || form.elements[i].name.substring(0,2) == "d1" || form.elements[i].name.substring(0,2) == "oa" || (form.elements[i].name.substring(0,2) == "da" &&  form.elements[i].name.substring(0,3) != "dat" ) || form.elements[i].name.substring(0,2) == "fh" || form.elements[i].name.substring(0,2) == "mh" || form.elements[i].name.substring(0,2) == "lh") && IsNumeric(form.elements[i].value) == false )  {
            alert ("Πληκτρολογείστε μόνο αριθμούς παρακαλώ!" );
            form.elements[i].focus();
            return false;
        }
    }

     // ή αν έγινε λάθος στην πληκτρολόγηση
    for (i=0;i<myStudentsAm.length;i++){
    

            totalapday=0;
            for (x=0;x<apous_def_array.length;x++){
                if (document.getElementById(apous_def_array[x] + myStudentsAm[i]).value!="")totalapday += parseInt(document.getElementById(apous_def_array[x] + myStudentsAm[i] ).value);
            }


            totalapov = 0;
            if (document.getElementById( "oa" + myStudentsAm[i]).value!="")totalapov += parseInt(document.getElementById( "oa" + myStudentsAm[i]).value);
            if (document.getElementById( "da" + myStudentsAm[i]).value!="")totalapov += parseInt(document.getElementById( "da" + myStudentsAm[i]).value);
            if (totalapov > totalapday){
                alert ("Ελέγξτε τις απουσίες από αποβολές. Ξεπερνούν τις απουσίες της ημέρας!");
                document.getElementById( "oa" + myStudentsAm[i]).focus();
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
            for (x=0;x<dik_def_array.length;x++){
                if (document.getElementById(dik_def_array[x] + myStudentsAm[i]).value!="")totaldik += parseInt(document.getElementById(dik_def_array[x] + myStudentsAm[i] ).value);
            }
            if (totaldik > totalapday){
                alert ("Ελέγξτε τις δικαιολογημένες απουσίες. Ξεπερνούν τις απουσίες της ημέρας!");
                document.getElementById(dik_def_array[0] + myStudentsAm[i]).focus();
                return false;
            }

            chkdate = document.getElementById( 'date' + myStudentsAm[i]).value;

            if (totalapday > 0 && chkdate == ""){
                alert("Πληκτρολογείστε την ημερομηνία!")
                document.getElementById( 'date' + myStudentsAm[i]).focus();
                return false;
            }

            if (chkdate != "" && isDate(chkdate) == false){
                alert("Η ημερομηνία '" + chkdate  + "' δεν είναι έγκυρη!")
                document.getElementById( 'date' + myStudentsAm[i]).focus();
                return false;
            }
            var myday = new Date(changedatestamp(chkdate));
            var myam  = document.getElementById( 'date' + myStudentsAm[i]).name.substr(4);
            var myfirstday = new Date(myfirstdayarray[myam]);
            if (myday>=myfirstday){
                alert("Υπάρχουν καταχωρημένες αναλυτικά απουσίες από τις " + myfirstday.getDate() + "/" + (myfirstday.getMonth() + 1 ) +"/" +  + myfirstday.getFullYear() + ". Οι προυπάρχουσες απουσίες δύναται να καταχωρηθούν μέχρι άλλη προηγούμενη ημερομηνία!")
                document.getElementById( 'date' + myStudentsAm[i]).focus();
                return false;
            }
            if (myday.getDay() == 0 || myday.getDay() == 6){
                alert("Δεν μπορείτε να καταχωρήσετε απουσίες Σάββατο ή Κυριακή!");
                document.getElementById( 'date' + myStudentsAm[i]).focus();
                return false;
            }


        }
}


function navigate(ev){

    var front;
    var myfront;
    var back;
    var str;
    var index;
    var k;

    var key = ev.keyCode || ev.which;

    str = document.activeElement.name;

    if (str.substr(0,2)=="ap" || str.substr(0,2)== "di" ){
        front = str.substr(0,2);
        myfront = str.substr(0,3);
        back = str.substr(3);
    }else if(str.substr(0,5)=="daysp"){
        front = str.substr(0,5);
        back = str.substr(5);
    }else if(str.substr(0,4)=="date"){
        front = str.substr(0,4);
        back = str.substr(4);
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

                if (k < apous_def_array.length){
                        front = apous_def_array[k]
                }else{
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
                front="daysp"
                break;		
            case "daysp":
                 front = dik_def_array[0];
                break;		
            case "di":
                 for (x=0;x<dik_def_array.length;x++){
                    if (myfront == dik_def_array[x]){
                        k = x+1
                        break;
                    }
                 } 

                if (k < dik_def_array.length){
                        front = dik_def_array[k]
                }else{
                        front = "date";
                }
                break;
        }
    }

    if (key==37){
        switch(front){
            case "date":
                front = dik_def_array[dik_def_array.length-1];
                break;
            case "di":
                for (x=dik_def_array.length-1;x>=0;x--){
                    if (myfront == dik_def_array[x]){
                        k = x-1
                        break;
                    }
                } 
                if (k >= 0){
                        front = dik_def_array[k]
                }else{
                        front = "daysp";
                }
                break;		
             case "daysp":
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
                front = apous_def_array[apous_def_array.length-1];
                break;		
            case "ap":
                for (x=apous_def_array.length-1;x>=0;x--){
                    if (myfront == apous_def_array[x]){
                        k = x-1
                        break;
                    }
                } 
                if (k >= 0){
                        front = apous_def_array[k]
                }else{
                        front = apous_def_array[0]
                }
                break;		
        }
    }


    if (key==38 || key==40){

        if(front == "ap")front = myfront;
        if(front == "di")front = myfront;


        for (i=0;i<myStudentsAm.length;i++){
            if (myStudentsAm[i]==back){
                index = i;
                break;
            }
        }

        if (key==38){
            back = myStudentsAm[index-1];
                index--;
             if (index<0)index=0;
               back = myStudentsAm[index];
        }	

        if (key==40){
            back = myStudentsAm[index+1];
                index++;
         if (index>myStudentsAm.length-1)index=myStudentsAm.length-1;
                back = myStudentsAm[index];
        }	

    }

if (key==37 || key==38 || key==39 || key==40){
    document.getElementById(front + back).focus();
}	

}
