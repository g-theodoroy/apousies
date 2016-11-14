{include file='header.tpl'}
{include file='body_header.tpl'}

<form action="statisticsprint.php" method="post" name="form" onsubmit="return valid(this)" target="_blank" >
    <h3 align="center">Επιλέξτε παρακάτω για να δείτε τους μαθητές</h3>
    <table  align="center">
        <tr><td align="right">με απουσία την 1η ώρα:</td><td align="center"> <input name="fh" id="fh" type="checkbox"  ></td></tr>
        <tr><td align="right">με απουσία ενδιάμ ώρα:</td><td align="center"><input name="mh" id="mh" type="checkbox" ></td></tr>
        <tr><td align="right">με απουσία την τελ ώρα:</td><td align="center"> <input name="lh" id="lh" type="checkbox" ></td></tr>
        <tr><td align="right">με ωριαία αποβολή:</td><td align="center"><input name="oa" id="oa" type="checkbox" ></td></tr>
        <tr><td align="right">με ημερήσια αποβολή:</td><td align="center"><input name="da" id="da" type="checkbox" ></td></tr>
        <tr><td colspan="2" align="center">&nbsp;</td></tr>
        <tr><td align="center">από Ημερομηνία:</td><td align="center"><input name="apo" type="text" size=8 ></td></tr>
        <tr><td align="center">έως Ημερομηνία:</td><td align="center"><input name="eos" type="text" size=8 ></td></tr>
        <tr><td colspan="2" align="center">&nbsp;</td></tr>
        <tr><td colspan="2" align="center"><button type="submit" name="submitBtn" value="submit" >ΑΙΤΗΣΗ</button> &nbsp;<button type="button" name="back" value="ΕΠΙΣΤΡΟΦΗ" onclick="window.location = 'index.php'" >ΕΠΙΣΤΡΟΦΗ</button></td></tr>
    </table>
</form>

{include file='footer.tpl'}
