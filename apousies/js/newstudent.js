function check_unique_key(field){
    var eleghos = true;

    with (field){
        for (var x = 0; x < pinakas.length; x++){
            for (var x = 0; x < pinakas.length; x++){
                if (value ==  pinakas[x]){
                    eleghos = false;
                }
            }
        }
    }
    if (eleghos == false){
        alert("Ο ΑΜ που πληκτρολογήσατε είναι ήδη καταχωρημένος.");
    }

    return eleghos;
}

function am_digit_check(field){

    var strNotValidChars = "|&<>";
    var strChar;
    var strToSearch;
    var blnResult = true;

    with (field)
    {
        //   if (strString.length == 0) return false;
        strToSearch = value;
        //  test strString consists of valid characters listed above
        for (i = 0; i < strToSearch.length && blnResult == true; i++)
        {
            strChar = strToSearch.charAt(i);
            if (strNotValidChars.indexOf(strChar) != -1)
            {
                blnResult = false;
            }
        }
        return blnResult;

        }
}



//------κώδικας για έλεγχο συμπλήρωσης των φορμών -------------------------------------------------
function validate_required(field,alerttxt)
{
    with (field)
    {
        if (value==null||value=="")
        {
            alert(alerttxt);
            return false;
        }
        else {
            return true
        }
        }
}

function validate_form(thisform)
{
    with (thisform)
    {
        if (validate_required(epitheto,"Τα πεδία με αστερίσκο * πρέπει να συμπληρωθούν υποχρεωτικά!")==false)
        {
            epitheto.focus();
            return false;
        }
        if (validate_required(onoma,"Τα πεδία με αστερίσκο * πρέπει να συμπληρωθούν υποχρεωτικά!")==false)
        {
            onoma.focus();
            return false;
        }
        //if (validate_required(patronimo,"Τα πεδία με αστερίσκο * πρέπει να συμπληρωθούν υποχρεωτικά!")==false)
        //  {patronimo.focus();return false;}
        //το συμπλήρωσα εγώ για να μην μπαίνουν διπλοί ΑΜ
        if (check_unique_key(am)==false)
        {
            am.focus();
            return false;
        }
        if (validate_required(am,"Τα πεδία με αστερίσκο * πρέπει να συμπληρωθούν υποχρεωτικά!")==false)
        {
            am.focus();
            return false;
        }
        //if (validate_required(epitheto_kidemona,"Τα πεδία με αστερίσκο * πρέπει να συμπληρωθούν υποχρεωτικά!")==false)
        //  {epitheto_kidemona.focus();return false;}
        //if (validate_required(onoma_kidemona,"Τα πεδία με αστερίσκο * πρέπει να συμπληρωθούν υποχρεωτικά!")==false)
        //  {onoma_kidemona.focus();return false;}
        //if (validate_required(dieythinsi,"Τα πεδία με αστερίσκο * πρέπει να συμπληρωθούν υποχρεωτικά!")==false)
        //  {dieythinsi.focus();return false;}
        var strAmCheckMsg = "Παρακαλώ να μη χρησιμοποιείτε τουs χαρακτήρες '|', '&', '<', '>'.";
        if (am_digit_check(epitheto)==false)
        {
            alert(strAmCheckMsg);
            epitheto.focus();
            return false;
        }
        if (am_digit_check(onoma)==false)
        {
            alert(strAmCheckMsg);
            onoma.focus();
            return false;
        }
        if (am_digit_check(patronimo)==false)
        {
            alert(strAmCheckMsg);
            patronimo.focus();
            return false;
        }
        if (am_digit_check(am)==false)
        {
            alert(strAmCheckMsg);
            am.focus();
            return false;
        }
        if (am_digit_check(ep_kidemona)==false)
        {
            alert(strAmCheckMsg);
            ep_kidemona.focus();
            return false;
        }
        if (am_digit_check(on_kidemona)==false)
        {
            alert(strAmCheckMsg);
            on_kidemona.focus();
            return false;
        }
        if (am_digit_check(dieythinsi)==false)
        {
            alert(strAmCheckMsg);
            dieythinsi.focus();
            return false;
        }
        if (am_digit_check(tk)==false)
        {
            alert(strAmCheckMsg);
            tk.focus();
            return false;
        }
        if (am_digit_check(poli)==false)
        {
            alert(strAmCheckMsg);
            poli.focus();
            return false;
        }
        if (am_digit_check(til1)==false)
        {
            alert(strAmCheckMsg);
            til1.focus();
            return false;
        }
        if (am_digit_check(til2)==false)
        {
            alert(strAmCheckMsg);
            til2.focus();
            return false;
        }
    }
}
//συμπληρώνω στη φόρμα  οnsubmit=" return validate_form(this)"
//---------------------------------------------------------------------------------

function showHint(myam)
{
    if(myam == "none")
    {
        document.getElementById("txtHint").innerHTML="";
        document.getElementById("buttonHint").innerHTML="";
    }
    else
    {
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
                document.getElementById("buttonHint").innerHTML="<button type=\"submit\" name=\"save\" value=\"insert\" >ΕΙΣΑΓΩΓΗ</button>&nbsp;";
            }
        }
        xmlhttp.open("GET","getstudent.php?q=" + myam , true);
        xmlhttp.send();
    }
}
