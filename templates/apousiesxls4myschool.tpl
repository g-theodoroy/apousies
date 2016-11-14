{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="column span-15 prepend-4 last" align="center" >


    <h4 align="center"> Επιλέξτε από ποιά ημερομηνία έως ποιά θέλετε να εξάγετε τις απουσίες</h4>
    <form action="" method="post" name="form">
        <table  align="center" >
            <tr><td>Από:</td><td> <input name="apo" type="text" size=8></td><td> (κενό = από την πρώτη ημέρα)</td></tr>
            <tr><td>Έως:</td><td> <input name="eos" type="text" size=8></td><td> (κενό = μέχρι την τελευταία) </td></tr>
            <tr><td colspan="3" align="center">&nbsp;</td></tr>
             <tr><td colspan="3" ><BUTTON type="submit" name="submitBtn" value="ΕΞΑΓΩΓΗ" >ΕΞΑΓΩΓΗ</BUTTON>&nbsp;<button type="button" name="back" value="ΕΠΙΣΤΡΟΦΗ" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button></td></tr>
        </table>
    </form>

</div>                                

{include file='footer.tpl'}
