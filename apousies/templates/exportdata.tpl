{include file='header.tpl'}
{include file='body_header.tpl'}

{if isset($smarty.post.delete)} 
    <h1 align="center"><u>Τα δεδομένα διαγράφηκαν</u></h1>
{else}
    {if isset($okmail)}
        <h4 style="width:30em;">Σας έχει αποσταλεί ένα e-mail στη ηλεκτρονική διεύθυνση "{$email}" με επισυναπτόμενο ένα συμπιεσμένο αρχείο zip το οποίο περιέχει το αρχείο "{$filename}" με τα δεδομένα σας μέχρι τις {$backupdate}.</h4><h4 style="width:30em;">Aν θέλετε μπορείτε τώρα να διαγράψετε τα στοιχεία από τη βάση δεδομένων.</h4>
    {else}
        {if isset($smarty.post.send)} 
            {if $contentchk}
                <h4   align="center" style="width:30em;">Προέκυψε σφαλμα. Δεν κατέστη δυνατή η αποστολή e-mail.</h4>

            {else}
                <h4   align="center" >Δεν υπάρχουν δεδομένα.</h4>
            {/if}
        {/if}
    {/if}
{/if}

<form name="frm"  method="post" action="">

    <table>
        <tr><TD><input type="radio" name="extention" value="sql" checked="true" ></TD><th>Αρχείο εντολών SQL</th></tr>
        {if $smarty.session.parentUser}
        <tr><TD><input type="radio" name="extention" value="xls" ></TD><th>Αρχείο excel 97/2000/xp</th></tr>
        <tr><td><input type="radio" name="extention" value="xlsx"></TD><th>Αρχείο excel 2007</th></tr>
        <tr><TD><input type="radio" name="extention" value="csv"></TD><th>Αρχείο csv </th></tr>
        {/if}
    </table>
    {if isset($smarty.get.e)}
        <h4  align="center" style='color : red;'>
            Λόγω όγκου δεδομένων δεν είναι δυνατή η δημιουργία αρχείου xls.<br>Επιλέξτε μια από τις άλλες επεκτάσεις αρχείων: (xlsx, sql, csv).
        </h4>
    {/if}
    {if $smarty.session.parentUser}
    <h4  align="center">
        <button type="submit" name="delete" value="delete" onclick="return check_delete();">ΔΙΑΓΡΑΦΗ ΔΕΔΟΜΕΝΩΝ</button>
    </h4>
    {/if}
    <h4  align="center">
    {if $smarty.session.parentUser}
        <button type="submit" name="send" value="send" >ΕΞΑΓΩΓΗ</button>&nbsp;
	{/if}
        <button type="submit" name="sendmail" value="sendmail" >ΜΕ e-mail</button>&nbsp;
        <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button><br>
    </h4>

</form>


{include file='footer.tpl'}
