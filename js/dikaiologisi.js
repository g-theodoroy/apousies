function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function change_things(element){
    var addname = element.name.substring(3,11);
    var checked = false;
    

    if (element.value == '' || element.value == 0 ){
        for (x=0 ; x<dik_def_array.length; x++){
        document.getElementById( x + 'diktype' + addname ).checked = false;
        element.className ='white';
        }       
    }else{
        for (x=0 ; x<dik_def_array.length; x++){
            if (document.getElementById( x + 'diktype' + addname ).checked == true){
                checked = true;
               break;
            }
        }       
        if (checked == false){
            document.getElementById('0diktype' + addname ).checked = true;
            element.className ='green';
        }
    }

    document.getElementById('chk' + addname ).checked = true 

}

function check_value(element , sumap){
    var addname = element.name.substring(3,11);
    var answer;
    
    if (element.value !=='' && !isNumber(element.value)){
        alert ("Η τιμή ''" + element.value + "'' που πληκτρολογήσατε δεν είναι έγκυρη");
        document.getElementById('chk' + addname ).checked = false; 
        element.focus();
        return false;
    }

    if (element.value > sumap){
        alert ("Οι δικαιολογημένες απουσίες δεν μπορεί να υπερβαίνουν το σύνολο απουσιών της ημέρας (" + sumap + ")");
        element.focus();
        return false;
    }

    if (element.value < sumap && (element.value !=='' || element.value > 0)){
        answer = confirm ("Οι δικαιολογημένες απουσίες (" + element.value + ") είναι λιγότερες από το σύνολο απουσιών της ημέρας (" + sumap + "). Θέλετε να συνεχίσετε;");
        if (answer == false){
            element.focus();
            return false;
        }
    }

}


function showHint(myam,overapousies,backtime)
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
                document.getElementById("buttonHint").innerHTML="<button type=\"button\" name=\"print\" value=\"print\" onclick=\"print_aitisi()\">ΑΙΤΗΣΗ</button>&nbsp;<button type=\"submit\" name=\"save\" value=\"insert\" >ΕΝΗΜΕΡΩΣΗ</button>&nbsp;";
            }
        }
        xmlhttp.open("GET","get_apousies_for_dik.php?am=" + myam + "&oa=" + overapousies + "&bt=" + backtime , true);
        xmlhttp.send();
    }
}

function print_aitisi(){
    var  num = 0;
 
    for (i=0;i<document.frm.elements.length;i++){ 
        if (document.frm.elements[i].type == "checkbox" && document.frm.elements[i].checked){
            num++;
        }
    }
    
    if (num == 0){
        alert ("Επιλέξτε τουλάχιστον μία εγγραφή");
        return; 
    } 
    
    document.frm.action = "aitisi_dik.php";
    document.frm.target="_self";
    document.frm.submit();
    
}

function check_form(){
    var  num = 0;
    var  adddays = 0;
    var check = true;
    var checkvalue = new Array;
 
    for (i=0;i<document.frm.elements.length;i++){ 
        if (document.frm.elements[i].type == "checkbox" && document.frm.elements[i].checked){
            checkvalue[num]=document.frm.elements[i].name.substr(3,11);
            num++;
            if (document.getElementById('0diktype' + document.frm.elements[i].name.substr(3,11) ).checked == true){
                adddays++; 
            }
            if (document.getElementById('dik' + document.frm.elements[i].name.substr(3,11) ).value == ''){
                adddays--; 
            }
        }
    }
    
    if (num == 0) return false;
    
    
    for (i=0;i<checkvalue.length;i++){
        check = check_value(document.getElementById("dik" + checkvalue[i]) , document.getElementById("sumap" + checkvalue[i]).value);
        if(check == false){
            break;
            return false;
        }
    }

    var daysk = document.getElementById('daysk').value;
    var totaldaysk = parseInt(daysk) + parseInt(adddays);
   
    if(totaldaysk > 10 ){
        answer = confirm ("Οι δικαιολογημένες ημέρες από κηδεμόνα είναι " + daysk + ". Μετά την ενημέρωση θα γίνουν " + totaldaysk + ". Θέλετε παρόλα αυτά να συνεχίσετε;" );
        if (answer == false){
            return false;
        }
    }

        
    document.frm.action = "";
    document.frm.target="_self";
    return check;
    
}
