{include file='header.tpl'}
{include file='body_header.tpl'}

{assign var=totalwidth value=210-2*15} 
{assign var=totalheight value=297-2*15} 

<form name="frm"  method="post" onsubmit=" return check_form(this)" action="">
    <table  border="1" cellpadding=0 cellspacing=0 style='width:{$totalwidth}mm' >
        <tbody>
            <tr>
                <td  align="center"  >&nbsp</td>
                <td  colspan=2 style='text-align:center'><h2 >ΑΙΤΗΣΗ</h2></td>
                <td  style='text-align:right' width='25%' ><b>Αρ.Πρ:</b><input type="text" name="protok" value='{$protok}' style="border-style:solid;border-color:#222;width:6em;text-align:center;" /><br><b>Ημνια:</b><input type="text" name="protok_date" value="{$smarty.now|date_format:"%e/%-m/%Y"}" style="width:6em;text-align:center;" /></td>
            </tr>
            <tr><td colspan=4 align="center"  >&nbsp;</td></tr>
            <tr>
                <td colspan=2  >&nbsp;</td>
                <td  colspan=2 align="left"   ><h4 class='nomargin' >ΠΡΟΣ: {if isset($txtdata.sch_name)}<b>{$txtdata.sch_name}</b>{/if}</h4></td>
            </tr>
            <tr>
                <td colspan=2 style="left-padding :10mm;"><h4 class='nomargin' >ΣΤΟΙΧΕΙΑ ΑΙΤΟΥΝΤΟΣ</h4></td>
                <td  colspan=2 rowspan=8 style='vertical-align:top;'>
                    <p>
                        Παρακαλώ να δικαιολογήσετε την απουσία που έκανε {$student_arthro_2}
                    </p>
                    <p align=center>
                        <input type="hidden" name="am" value="{$studentsdata.am}" /><b>{$studentsdata.ep} {$studentsdata.on}</b>
                    </p>
                    <p>
                        του τμήματος <b>{$smarty.session.tmima}</b> η οποία έγινε
                    </p>
                    <p align=center>
                        από <input type="text" name="firstday" value="{$firstday}" style="width:8em;text-align:center;font-weight:bold;" readonly /> μέχρι <input type="text" name="lastday" value="{$lastday}" style="width:8em;text-align:center;font-weight:bold;" readonly />
                    </p>
                    <p align=center>
                        Ημέρες απουσίας: <input type="text" name="countdays" value="{$countdays}" style="width:2em;text-align:center;font-weight:bold;" readonly />
                    </p>
                    <p>
                        και οφείλεται σε:</p>
                        {for $x = 0 to $dik_count-1 step 1}
                        {if $logos_list[$x] != ''}
                        <input type="radio" name="astheneia-logos" value="{$kod_array[$x]}" {$field_array[$x]} /> {$logos_list[$x]}<br>
                    {/if}
                    {/for}
                        <input type="radio" name="astheneia-logos" value="allo" /> <input type="text" name="allos-logos" value="" style="border-style:solid;border-color:#222;width:20em;" /><br>
                    </p>


                </td>
            </tr>
            <tr>
                <td style='text-align:right' width='15%'>
                    ΕΠΩΝΥΜΟ:
                </td>
                <td width='35%' >
                    {$studentsdata.ep_ki}
                </td >
            </tr>
            <tr>
                <td style='text-align:right' width='15%'>
                    ΟΝΟΜΑ:
                </td>
                <td width='35%' >
                    {$studentsdata.on_ki}
                </td >
            </tr>
            <tr>
                <td style='text-align:right' width='15%'>
                    ΣΧΕΣΗ ΜΕ<br>{$student_arthro_0}:
                </td>
                <td width='35%' >
                    ΚΗΔΕΜΟΝΑΣ

                </td >
            </tr>
            <tr><td colspan=2 >&nbsp;</td></tr>
            <tr>
                <td colspan=2 style="left-padding :10mm;"><h4 class='nomargin' >ΣΤΟΙΧΕΙΑ {$student_arthro_1}</h4></td>
            </tr>
            <tr>
                <td style='text-align:right' width='15%'>
                    ΕΠΩΝΥΜΟ:<br>
                    ΟΝΟΜΑ:<br>
                    ΤΑΞΗ:<br>
                    ΤΜΗΜΑ:
                </td>
                <td width='35%' >
                    {$studentsdata.ep}<br>
                    {$studentsdata.on}<br>
                    {if isset($txtdata.sch_class)}{$txtdata.sch_class}{/if}<br>
                    {$smarty.session.tmima}
                </td >
            </tr>
            <tr>
                <td colspan=2 >&nbsp;</td>
            </tr>
            <tr>
                <td colspan=2 ><b>Θέμα:</b> Δικαιολόγηση απουσιών</td>
                <td colspan=2 rowspan=4 style='vertical-align:top;'><h4 align="center" class="nomargin" >Ο/Η ΑΙΤΩΝ/ΟΥΣΑ</h4></td>
            </tr>
            <tr>
                <td colspan=2 ><b>Ημνια:</b> {$smarty.now|date_format:"%e/%-m/%Y"}</td>
            </tr>
            <tr>
                <td colspan=2 >&nbsp;</td>
            </tr>
            <tr>
                <td colspan=2 >
                    {for $x = 0 to $dik_count-1 step 1}
                    {if $dik_me_list[$x] != ''}
                        <input type="radio" name="dil_kid-iat_beb" value="{$kod_array[$x]}" {$field_array[$x]} /> {$dik_me_list[$x]}<br>
                    {/if}
                    {/for}
                </td>
            </tr>
    </table>

    {if isset($smarty.get.m)}
        {if $smarty.get.m == 1}
            <h4 style='color : green;'>Επιτυχημένη αποστολή αίτησης δικαιολόγησης απουσιών με email</h4>
        {else}
            <h4 style='color : red;'>Αποτυχημένη αποστολή αίτησης δικαιολόγησης απουσιών με email</h4>
        {/if}
    {/if}

    <h4 align="center">Να αποθηκευτεί η αίτηση στο ιστορικό: 
        <select name='history' id='history'><option value="0">ΟΧΙ</option><option value="1">ΝΑΙ</option></select></td>
    </h4>

    <p align='center' >
        <button type="submit" name="submitBtn" value="print" onclick="frm.action='aitisi_print.php';frm.target='_blank';">ΕΚΤΥΠΩΣΗ</button>&nbsp;
        <button type="submit" name="submitBtn" value="pdf" onclick="frm.action='aitisi_print.php';frm.target='';">ΕΞΑΓΩΓΗ PDF</button>&nbsp;
		<!--
        <button type="submit" name="submitBtn" value="email" onclick="frm.action='aitisi_print.php';frm.target='';">PDF σε E-mail </button>&nbsp;
		-->
        <button type='button' name='back' onclick='window.location="dikaiologisi.php?st={$studentsdata.am}"'>ΕΠΙΣΤΡΟΦΗ</button>
    </p>
</form>
{include file='footer.tpl'}
