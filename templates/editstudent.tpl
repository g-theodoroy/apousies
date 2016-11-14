{include file='header.tpl'}
{include file='body_header.tpl'}

<form name="frm"  method="post" onsubmit=" return validate_form(this)" action="">
    <INPUT type="hidden" name="tmima" value="{$smarty.session.tmima}">
    <INPUT type="hidden" name="oldamget" id="oldamget" value="" >
    {if not isset($smarty.get.am)}
        <h4 align="center"><select name="selstudent" id="selstudent" onchange='document.getElementById("oldamget").value=this.value; showHint(this.value)' >
                <option value="none">Επιλογή μαθητή</option>
                {foreach from=$students item=stu}
                    {$stu}
                {/foreach}
            </select>
        </h4>
    {/if}

    <span id="txtHint"></span>


    <h4 align="center">
        <span id="buttonHint"></span>
        <button type="button" name="exit" value="exit" onclick="window.location='{if isset($smarty.get.am)}students{else}index{/if}.php'">ΕΠΙΣΤΡΟΦΗ</button></h4>
</form>

{include file='footer.tpl'}
