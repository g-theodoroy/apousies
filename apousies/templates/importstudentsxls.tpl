{include file='header.tpl'}
{include file='body_header.tpl'}

{if isset($smarty.post.save)} 
    {if $check == 1}
        <h3 align="center">Kαταχωρήθηκαν {$numstudents} εγγραφές μαθητών.</h3>
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

    <H4 align="center">Επιλέξτε το αρχείο excell με τους μαθητές</H4>
    <form action="" name="frm" method="post" enctype="multipart/form-data">
        <h4  align="center">
            <input  size="30" type="file" name="file" id="file" />
        </h4>

        <h4  align="center">
            <button type="submit" name="save" value="insert"  onclick="return check_submit();">ΕΙΣΑΓΩΓΗ</button>&nbsp;
            <button type="reset" name="clear" value="clear" >ΚΑΘΑΡΙΣΜΑ</button>&nbsp;
            <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
        </h4>
    </form>

    <hr class="space">
    <hr class="space">
    <hr class="space">

    <h4>Κατεβάστε το αρχείο excell από &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="useful/eisagogi-mathiton.xls"><b>εδώ</b></a></h4>
    <p style="width:50%;text-align:left;"><b>ΟΔΗΓΙΕΣ:</b><br>
        Συμπληρώστε τα στοιχεία των μαθητών χωρίς να αφήνετε κενές γραμμές.
        <br>Μη πειράζετε τη πρώτη γραμμη με τις επικεφαλίδες.
        <br>Στη στήλη ΦΥΛΟ εισάγετε "Α" για ΑΡΕΝ ή "Θ" για ΘΗΛΥ.</p>
    <hr class="space">


    {if $warning}
        <p  style="width:50%;text-align:left;color:red;"><b>ΠΡΟΣΟΧΗ!!!</b><br>Έχετε ήδη καταχωρήσει απουσίες!<br>Τα δεδομένα θα αντικαταστήσουν όλους τους ήδη καταχωρημένους μαθητές με αυτούς στο αρχείο.<br>Βεβαιωθείτε ότι οι Αριθμοί Μητρώου ταιριάζουν απόλυτα με τους προηγούμενους αλλιώς θα υπάρξει ασυνέχεια των δεδομένων.</p>
    {/if}

{/if}


{include file='footer.tpl'}
