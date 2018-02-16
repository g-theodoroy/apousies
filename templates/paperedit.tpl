{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="column span-17 prepend-3 last " align="center" >

    <form name="frm" method="POST" action='' onsubmit=" return validate_form(this);">
        <IMG class="tour dropshadow" src="{$smarty.session.images_prefix}myletter.png" height="200px" >
        <table>
            <tr>
                <td colspan='3'>
                    <h4 class="nomargin" align="center">
                        <SELECT name="student" id="student" onchange="select_control_apover();">
                            <option value="">ΕΠΙΛΟΓΗ ΜΑΘΗΤΗ ΓΙΑ ΕΚΤΥΠΩΣΗ ΕΙΔΟΠΟΙΗΤΗΡΙΟΥ</option>
                            <option value="">&nbsp;</option>
                            <option value="all">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ΟΛΟΙ ΟΣΟΙ ΕΧΟΥΝ ΚΑΝΕΙ ΑΠΟΥΣΙΕΣ ΠΑΝΩ ΑΠΟ</option>
                            <option value="new">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ΜΟΝΟ ΤΑ ΝΕΑ ΕΙΔΟΠΟΙΗΤΗΡΙΑ</option>
                            <option value="">&nbsp;</option>
                            {foreach from=$selectdata item=value}
                                {$value}
                            {/foreach}
                            <option value="">&nbsp;</option>
                        </SELECT>
                        <input type="text" name="apover" size="2" style="visibility : hidden;text-align: center;" value="{$orio_paper}">
                    </h4>
                </td>
            </tr>
            <tr>
                <td > <h4 class="nomargin" align="center">Αριθμός Πρωτοκόλλου:</h4></td>
                <td><INPUT type="text" name="protok" size="4" value="" style="text-align : center;background-color : #fff;"  tabindex="-1"></td>
                <td><select name="protokctrl" id="protokctrl"><option value="1">ΝΑ ΑΥΞΑΝΕΙ +1</option><option value="0">ΙΔΙΟΣ ΓΙΑ ΟΛΑ</option>{if $smarty.session.parentUser and $prot_con}<option value="2">ΗΛ. ΠΡΩΤΟΚΟΛΛΟ</option>{/if}</select></td>
            </tr>
            <tr>
                <td colspan='2'><h4 class="nomargin" align="center">Υπολόγισε τις απουσίες μέχρι τις</h4></td>
                <td><input name='lastdate' id='lastdate' value="{$smarty.now|date_format:"%e/%-m/%Y"}" size="10" style="text-align : center; background-color : #fff;"/></td>
            </tr>
            <tr>
                <td colspan='2'><h4 class="nomargin" align="center">Ημερομηνία έκδοσης ειδοποιητηρίου</h4></td>
                <td><input name='paperdate' id='paperdate' value="{$smarty.now|date_format:"%e/%-m/%Y"}" size="10" style="text-align : center; background-color : #fff;"/></td>
            </tr>
            <tr>
                <td colspan='2'><h4 class="nomargin" align="center">Παρουσίαση απουσιών</h4></td>
                <td><select name="paperdetails" id="paperdetails"><option value="0">ΠΕΡΙΛΗΠΤΙΚΑ</option><option value="1">ΑΝΑΛΥΤΙΚΑ</option></select></td>
            </tr>
            <tr>
                <td colspan='2'><h4 class="nomargin" align="center">Να αποθηκευτούν τα ειδοποιητήρια στο ιστορικό</h4></td>
                <td><select name='history' id='history'><option value="0">ΟΧΙ</option><option value="1">ΝΑΙ</option></select></td>
            </tr>
        </table>
        {if isset($smarty.get.m)}
            {if $smarty.get.m == 1}
                <h4 style='color : green;'>Επιτυχημένη αποστολή email ειδοποιητηρίων{if isset($mail_good)} στους γονείς των μαθητών:<br>{foreach from=$mail_good item=value}{$value}<br>{/foreach}{/if}</h4>
                {if isset($mail_bad)}<h4 style='color : red;'>Αποτυχημένη αποστολή email ειδοποιητηρίων στους γονείς των μαθητών:<br>{foreach from=$mail_bad item=value}{$value}<br>{/foreach}</h4>{/if}
           {else}
                <h4 style='color : red;'>Αποτυχημένη αποστολή email ειδοποιητηρίων</h4>
            {/if}
        {/if}
        <h4 align="center">
            <button type="submit" name="submitBtn" value="print" onclick="frm.action='paper.php';frm.target='_blank';">ΕΚΤΥΠΩΣΗ</button>&nbsp;
            <button type="submit" name="submitBtn" value="pdf" onclick="frm.action='paper.php';frm.target='';">ΕΞΑΓΩΓΗ PDF</button>
            {if $smarty.session.parentUser}
            &nbsp;
            <button type="submit" name="submitBtn" value="email" onclick="frm.action='paper.php';frm.target='';">PDF σε E-mail </button>
            {/if}
        </h4>
        <h4>
            <button type="submit" name="submitBtn" value="parents" onclick="frm.action='paper.php';frm.target='';return check_mail_to_parents();" title="Αποστολή e-mail στους κηδεμόνες που έχουν καταχωρημένο e-mail" >E-mail ΣΕ ΚΗΔΕΜΟΝΕΣ</button>&nbsp;
            Κοινοποίηση σε: <input type="checkbox" name="cc_sch" checked>σχολείο</input>&nbsp;<input type="checkbox" name="cc_teacher" checked>καθηγητή</input>

        </h4>
        <h4>
            <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
        </h4>
    </form>

</div>

{include file='footer.tpl'}
