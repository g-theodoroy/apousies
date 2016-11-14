{include file='header.tpl'}
{include file='body_header.tpl'}

<form name="frm"  method="post" onsubmit=" return check_form(this)" action="">
        <h4 align="center"><select name="selstudent" id="selstudent" onchange='showHint(this.value,document.getElementById("overapousies").value,document.getElementById("backtime").value)' >
                <option value="none">Επιλογή μαθητή</option>
                {foreach from=$students item=stu}
                    {$stu}
                {/foreach}
            </select>
        </h4>
        <h4 align="center">
            <select name="overapousies" id="overapousies" onchange='showHint(document.getElementById("selstudent").value,this.value,document.getElementById("backtime").value)' >
                <option value="0">Πάνω από 0 απουσίες</option>
                <option value="1" selected >Πάνω από 1 απουσίες</option>
                <option value="2">Πάνω από 2 απουσίες</option>
                <option value="3">Πάνω από 3 απουσίες</option>
            </select>
            &nbsp;
            <select name="backtime" id="backtime" onchange='showHint(document.getElementById("selstudent").value,document.getElementById("overapousies").value,this.value)' >
                <option value="-1" >1 Μήνα πίσω</option>
                <option value="-2" selected >2 Μήνες πίσω</option>
                <option value="-3">3 Μήνες πίσω</option>
                <option value="0">Από την αρχή</option>
            </select>
        </h4>
 
    <span id="txtHint"></span>


    <h4 align="center">
        <span id="buttonHint"></span>
        <button type="button" name="exit" value="exit" onclick="window.location='{if isset($smarty.get.am)}students{else}index{/if}.php'">ΕΠΙΣΤΡΟΦΗ</button></h4>
</form>

{include file='footer.tpl'}
