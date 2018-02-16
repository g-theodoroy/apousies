{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="column span-15 prepend-4 last" align="center" >

    <form action="" method="post" name="frm">

        <table  align="center" >
            <tr><td colspan='2'><h3 class="c">Όρια απουσιών</h3></td></tr>
            <tr><td ><h4 class="r nomargin">Αδικαιολόγητες απουσίες:</h4></td><td > <input name="orio_adik" type="text"  size="2" value="{$orio_adik}"></td></tr>
            <tr><td ><h4 class="r nomargin">Δικαιολογημένες απουσίες:</h4></td><td > <input name="orio_dik" type="text"   size="2" value="{$orio_dik}"></td></tr>
            <tr><td ><h4 class="r nomargin">Αποστολή ειδοποιητηρίων:</h4></td><td ><input name="orio_paper" type="text"   size="2" value="{$orio_paper}"></td></tr>
            <tr><td colspan="2" align="center"><hr></td></tr>
            <tr><td colspan='2'><h3 class="c">Στοιχεία για ειδοποιητήριο</h3></td></tr>
            <tr><td ><h4 class="r nomargin">Όνομα σχολείου:</h4></td><td > <input name="sch_name" type="text"  size="20" value="{$sch_name}"></td></tr>
            <tr><td ><h4 class="r nomargin">Τηλ σχολείου:</h4></td><td > <input name="sch_tel" type="text"  size="20" value="{$sch_tel}"></td></tr>
            <tr><td ><h4 class="r nomargin">Σχολικό έτος:</h4></td><td > <input name="sch_year" type="text"  size="20" value="{$sch_year}"></td></tr>
            <tr><td ><h4 class="r nomargin">Τάξη:</h4></td><td > <input name="sch_class" type="text"   size="5" value="{$sch_class}"></td></tr>
            <tr><td ><h4 class="r nomargin">Τμήμα:</h4></td><td ><input name="sch_tmima" type="text"   size="5" value="{$sch_tmima}"></td></tr>
            <tr><td colspan="2" align="center"><hr></td></tr>
            <tr><td colspan='2'><h3 class="c">Στοιχεία υπεύθυνου καθηγητή</h3></td></tr>
            <tr><td ><h4 class="r nomargin">Πρόθεμα:</h4></td><td > <input name="teach_arthro" type="text"  size="1" value="{$teach_arthro}"> ΚΑΘΗΓΗ<input name="teach_last" type="text"  size="3" value="{$teach_last}"></td></tr>
            <tr><td ><h4 class="r nomargin">Ονοματεπώνυμο:</h4></td><td > <input name="teach_name" type="text"  size="20" value="{$teach_name}"></td></tr>
            <tr><td colspan="2" align="center"><hr></td></tr>
        </table>

        <h4 class="c"><button type="submit" name="submitBtn" value="save" >ΑΠΟΘΗΚΕΥΣΗ</button> &nbsp;<button type="button" name="back" value="ΕΠΙΣΤΡΟΦΗ" onclick="window.location='index.php'" >ΕΠΙΣΤΡΟΦΗ</button></h4>

    </form>

</div>                                

{include file='footer.tpl'}
