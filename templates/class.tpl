{include file='header.tpl'}
{include file='body_header.tpl'}

<form action="" name="frm" method="POST" >

    <table align="center" border="0" cellpadding="2">
        <tbody align="center">
        
        {if isset($smarty.session.parentUser) && $smarty.session.parentUser == true}

            <tr><th colspan="{$colspan}"><h3>ΕΙΣΑΓΩΓΗ ΝΕΟΥ ΤΜΗΜΑΤΟΣ</h3></th></tr>
            <tr>
                <td colspan="{$colspan}"><INPUT type="text" name="newtmima"   style="text-align : center;" size="10">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select name="type">
                        {foreach from=$tmimata item=tmima}
                            <option value="{$tmima.kod}">{$tmima.perigrafi}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>

            <tr><TD colspan="{$colspan}"><hr></TD></tr>
            <tr><td colspan="{$colspan}"><h3>ΕΠΙΛΟΓΗ ΤΜΗΜΑΤΟΣ</h3></td></tr>
            {if isset($smarty.session.tmima)}
                <tr><TD colspan="{$colspan}"><INPUT type="radio" name="seltmima" value="allstu" >Ακύρωση επιλογής τμήματος {$smarty.session.tmima}</TD></tr>
                <tr><TD colspan="{$colspan}"><hr></TD></tr>
            {/if}
            {/if}

             <tr>
                {foreach from=$tmimata item=tmima}
                    <th>{$tmima.perigrafi}</th>
                {/foreach}
            </tr>
            <tr>
                {foreach from=$tmimatalist item=list}
                    <td>{$list}</td>
                {/foreach}
            </tr>

            <tr><TD colspan="{$colspan}"><hr></TD></tr>

            <tr>
                <td colspan="{$colspan}" align="center">
                    <button type="submit" name="submitBtn" value="ΕΠΙΛΟΓΗ" >ΕΠΙΛΟΓΗ</button>&nbsp;
 			        {if isset($smarty.session.parentUser) && $smarty.session.parentUser == true}
	                     <button type="submit" name="submitBtn" value="ΔΙΑΓΡΑΦΗ"  onclick="return confirm_delete();">ΔΙΑΓΡΑΦΗ</button>&nbsp;
					{/if}
                    <button  type="button" onclick="window.location='index.php'" value="ΕΠΙΣΤΡΟΦΗ" name="back">ΕΠΙΣΤΡΟΦΗ</button>&nbsp;
 			        {if isset($smarty.session.parentUser) && $smarty.session.parentUser == true}
                    	<button  type="button" onclick="alert('Για να αλλάξετε τον τύπο τμήματος :\n\n1. πληκτρολογείστε το τμήμα στο textbox\n\n2. επιλέξτε: ΓΕΝ.ΠΑΙΔΕΙΑΣ - ΚΑΤΕΥΘΥΝΣΗΣ - ΕΠΙΛΟΓΗΣ \n\n3. πατήστε το κουμπί &quot;ΕΠΙΛΟΓΗ&quot;.')" value="help" name="help">?</button><br>&nbsp;
					{/if}
                </td>
            </tr>
        </tbody>
    </table>
</form>


{include file='footer.tpl'}
