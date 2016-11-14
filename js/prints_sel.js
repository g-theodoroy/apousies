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





function selectall(){
    var i

    if( document.frm.all.value == "ΝΑΙ"){
        for (i=0;i<document.frm.elements.length;i++){
            if (document.frm.elements[i].name.substring(0,3) == "stu"  )  {
                document.frm.elements[i].checked = true;
            }
        }
        document.frm.all.value = "ΟΧΙ"
    }else{
        for (i=0;i<document.frm.elements.length;i++){
            if (document.frm.elements[i].name.substring(0,3) == "stu"  )  {
                document.frm.elements[i].checked = false;
            }
        }

        document.frm.all.value = "ΝΑΙ"
    }

}

function checkperiod(element){
    var date = element.value;
    var d = new Date();
    var nMonth
    var nYear

    nMonth= parseInt(d.getMonth());
    nYear = parseInt(d.getFullYear());

    if( nMonth > 5 && (element.name != "periodendAtrim" && element.name != "periodbegBtrim")) nYear++;
    if(nMonth < 6 && (element.name == "periodendAtrim" || element.name == "periodbegBtrim")) nYear--;

    if (! isDate(date + "/" + nYear)){
        alert("Μη έγκυρη ημερομηνία. Χρησιμοποιήστε τη μορφή ''ηη/μμ''.");
        return false;
    }
    return true;
}


function validateform(form){
    var stucheck = false;
    var percheck = false;
    var i;

    for (i=0;i<form.elements.length;i++){
        if (form.elements[i].name.substring(0,3) == "stu" && form.elements[i].checked == true){
            stucheck = true;
        }
        if (form.elements[i].name.substring(0,5) == "month" && form.elements[i].checked == true){
            percheck = true;
        }
        if (form.elements[i].name.substring(0,4) == "tetr" && form.elements[i].checked == true){
            percheck = true;
        }
        if (form.elements[i].name.substring(0,4) == "trim" && form.elements[i].checked == true){
            percheck = true;
        }
        if (form.elements[i].name.substring(0,5) == "st2st" && form.elements[i].checked == true){
            percheck = true;
        }
        if (form.elements[i].name == "total" && form.elements[i].checked == true){
            percheck = true;
        }
    }

    if (stucheck == false || percheck == false){
        alert("Επιλέξτε τουλάχιστον ένα μαθητή και μια χρονική περίοδο!");
        return false;
    }

    if(form.tetrA.checked == true){
        if (!checkperiod(form.periodendAtetr)){
            form.periodendAtetr.focus();
            return false;
        }
    }
if(form.tetrB.checked == true){
    if (!checkperiod(form.periodbegBtetr)){
        form.periodbegBtetr.focus();
        return false;
    }
}
if(form.trimA.checked == true){
    if (!checkperiod(form.periodendAtrim)){
        form.periodendAtrim.focus();
        return false;
    }
}
if(form.trimB.checked == true){
    if (!checkperiod(form.periodbegBtrim)){
        form.periodbegBtrim.focus();
        return false;
    }
}
if(form.trimB.checked == true){
    if (!checkperiod(form.periodendBtrim)){
        form.periodendBtrim.focus();
        return false;
    }
}
if(form.trimG.checked == true){
    if (!checkperiod(form.periodbegGtrim)){
        form.periodbegGtrim.focus();
        return false;
    }
}
if (form.st2stsum.checked == true || form.st2stdet.checked == true){
    if (! isDate(form.st2ststart.value)){
        alert("Μη έγκυρη ημερομηνία. Χρησιμοποιήστε τη μορφή ''ηη/μμ/εεεε'' και μόνο αριθμούς");
        form.st2ststart.focus();
        return false;
    }
    if (! isDate(form.st2ststop.value)){
        alert("Μη έγκυρη ημερομηνία. Χρησιμοποιήστε τη μορφή ''ηη/μμ/εεεε'' μόνο αριθμούς");
        form.st2ststop.focus();
        return false;
    }
}

return true;
}
