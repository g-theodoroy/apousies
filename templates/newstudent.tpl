{include file='header.tpl'}
{include file='body_header.tpl'}


<form name="frm"  method="post" onsubmit=" return validate_form(this)" action="">
    <INPUT type="hidden" name="tmima" value="{$smarty.session.tmima}">


    <table align="center" cellpadding="2">
        <tbody  >
            <tr>
                <td align="right">ΑΜ</td>
                <td><input type="text" name="am" id="am" value="{$maxam}">*</td>
            </tr>
            <tr>
            <tr>
                <td align="right">ΕΠΙΘΕΤΟ</td>
                <td><input type="text" name="epitheto" id="epitheto" value="" >*</td>
            </tr>
            <tr>
                <td align="right">ΟΝΟΜΑ</td>
                <td><input type="text" name="onoma" id="onoma" value="">*</td>
            </tr>
            <tr>
                <td align="right">ΠΑΤΡΩΝΥΜΟ</td>
                <td><input type="text" name="patronimo" id="patronimo" value=""></td>
            </tr>
        <td align="right">ΕΠΙΘΕΤΟ ΚΗΔΕΜΟΝΑ</td>
        <td><input type="text" name="epitheto_kidemona" id="epitheto_kidemona" value=""></td>
        </tr>
        <tr>
            <td align="right">ΟΝΟΜΑ ΚΗΔΕΜΟΝΑ</td>
            <td><input type="text" name="onoma_kidemona" id="onoma_kidemona" value=""></td>
        </tr>
        <tr>
            <td align="right">ΔΙΕΥΘΥΝΣΗ</td>
            <td><input type="text" name="dieythinsi" id="dieythinsi" value=""></td>
        </tr>
        <tr>
            <td align="right">ΤΚ</td>
            <td><input type="text" name="tk" id="tk" value=""></td>
        </tr>
        <tr>
            <td align="right">ΠΟΛΗ</td>
            <td><input type="text" name="poli" id="poli" value=""></td>
        </tr>
        <tr>
            <td align="right">ΤΗΛ1</td>
            <td><input type="text" name="til1" id="til1" value=""></td>
        </tr>
        <tr>
            <td align="right">ΚΙΝΗΤΟ</td>
            <td><input type="text" name="til2" id="til2" value=""></td>
        </tr>
        <tr>
            <td align="right">EMAIL</td>
            <td><input type="text" name="email" id="email" value=""></td>
        </tr>
        <tr>
            <td align="right">ΦΥΛΟ</td>
            <td>
                <SELECT name="gender" id="gender">
                    <option value='Α'>ΑΡΕΝ</option>
                    <option value='Θ'>ΘΗΛΥ</option>
                </SELECT>
            </td>
        </tr>
        {for $x = 0 to $loopcount step 1}
        <tr>
            <td align="right">{$tmimata_define[$x].perigrafi}</td>
            <td>{if isset($tmimata_select_boxes[$x])}{$tmimata_select_boxes[$x]}{/if}</td>
        </tr>
        {/for}
       </tbody>
    </table>
    <h4 align="center">
        <button type="submit" name="save" value="insert" >ΕΙΣΑΓΩΓΗ</button>&nbsp;
        <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button></h4>
</form>


{include file='footer.tpl'}
