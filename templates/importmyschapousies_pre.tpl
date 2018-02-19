{include file='header.tpl'}
{include file='body_header.tpl'}

{if isset($smarty.post.save)} 
    {if $check == 1}
        <h3 align="center">Kαταχωρήθηκαν {$numentries} εγγραφές απουσιών.</h3>
    {elseif $check == 2}
        <h3 align="center">Πρόβλημα στο ανέβασμα (upload) του αρχείου δεδομένων.</h3>
    {elseif $check == 3}
        <h3 align="center">{$errorText}</h3>
    {elseif $check == 4}
        <h3 align="center">Πρόβλημα στην καταχώρηση δεδομένων. Δεν έγινε καμία μεταβολή.</h3>
    {else}
        <h3 align="center">Παρουσιάστηκε κάποιο πρόβλημα.</h3>
    {/if}
    <p  align="center"><button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button></p>

{else}

    <H4 align="center">Επιλέξτε το αρχείο excell με τις απουσίες</H4>
    <form action="" name="frm" method="post" enctype="multipart/form-data">
        <h4  align="center">
            <input  size="30" type="file" name="file" id="file" />
        </h4>
    <hr class="space">
    <H4 align="center">Πληκτρολογείστε τον αριθμό της στήλης με τα δεδομένα για κάθε πεδίο σύμφωνα<br>
    με την αλληλουχία A=1, B=2, C=3, D=4, E=5, F=6, G=7, H=8, I=9, J=10, K=11, ...
    </H4>
    <H4 align="center">
    <table>
    <tr><th>Επώνυμο μαθητή</th><th>Όνομα μαθητή</th><th>Όνομα πατέρα</th><th>Σύνολο απουσιών</th><th>Δικαιολογημένες</th></tr>
    <tr>
        <td ><center><input type="text" name="epitheto" id="epitheto" size=1 style="text-align:center" value="2" oninput="javascript:checkmyvaluenum(this)" /></center></td>
        <td ><center><input type="text" name="onoma" id="onoma" size=1 style="text-align:center" value="3" oninput="checkmyvaluenum(this)" /></center></td>
        <td ><center><input type="text" name="patronimo" id="patronimo" size=1 style="text-align:center" value="5" oninput="checkmyvaluenum(this)" /></center></td>
        <td ><center><input type="text" name="ap" id="ap" size=1 style="text-align:center" value="6" oninput="checkmyvaluenum(this)" /></center></td>
        <td ><center><input type="text" name="dik" id="dik" size=1 style="text-align:center"  value="7" oninput="checkmyvaluenum(this)" /></center></td>
    </tr>
    <tr>
        <td colspan=4 ><center>Πληκτρολογείστε τον αριθμό της γραμής με τα πρώτα δεδομένα</center></td>
        <td ><center><input type="text" name="firstrow" id="firstrow" size=1 style="text-align:center"  value="16" oninput="checkmyvaluenum(this)" /></center></td>
    </tr>
    </table>
    </H4>
    <hr class="space">
        <h4  align="center">
            <button type="submit" name="save" value="insert"  onclick="return check_submit();">ΕΙΣΑΓΩΓΗ</button>&nbsp;
            <button type="reset" name="clear" value="clear" >ΚΑΘΑΡΙΣΜΑ</button>&nbsp;
            <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
        </h4>
    </form>

    <hr class="space">
    <hr class="space">

    <p style="width:50%;text-align:left;"><b>ΟΔΗΓΙΕΣ:</b><br>
        Ανοίγετε στό myschool την αναφορά:
        <br>ΑΝΑΦΟΡΕΣ > Αναφορές Μαθητών > Απουσίες > Συνοπτική κατάσταση απουσιών
        <br>Εξάγετε τα δεδομένα του Τμήματος ή όλων των Τμημάτων σε xls        
        </p>
    <hr class="space">

{/if}


{include file='footer.tpl'}
