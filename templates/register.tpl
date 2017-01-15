{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="column span-13 prepend-4 last" align="center" >
    {if not isset($smarty.post.submitBtn) || $error}
        <form action="" method="post" name="registerform">
            <table  align="center" width="25em">
                <tr><td align="right">Username:</td><td> <input name="username" type="text"  ></td></tr>
                <tr><td align="right">email:</td><td> <input name="email" type="text" ></td></tr>
                <tr><td align="right">Κάτι που εύκολα θυμάμαι:</td><td><input name="check" type="text" ></td></tr>
                <tr><td colspan="2" align="center">Θα χρειαστεί αν ξεχάσετε το password για να γίνει επαλήθευση των στοιχείων σας και επαναφορά του λογαριασμού.</td></tr>
                <tr><td colspan="2" align="center">&nbsp;</td></tr>
                <tr><td colspan="2" align="center"><button type="submit" name="submitBtn" value="ΔΗΜΙΟΥΡΓΙΑ" >ΔΗΜΙΟΥΡΓΙΑ</button> &nbsp;<button type="button" name="back" value="ΕΠΙΣΤΡΟΦΗ" onclick="window.location = 'index.php'" >ΕΠΙΣΤΡΟΦΗ</button> <br>&nbsp;</td></tr>
            </table>
        </form>
    {/if}
    {if isset($smarty.post.submitBtn)}
        {if not $error}
            <h3 align="center">Καλώς ήλθατε <strong>{$smarty.post.username}</strong>.</h3>
            <h3 align="center">Σας έχει αποσταλεί ένα <strong>password</strong></h3>
            <h3 align="center">στο email: <strong>{$smarty.post.email}</strong>.</h3>
            <h3 align="center">Ευχαριστούμε.</h3>
            <h4 align="center">Σε 15 δευτερόλέπτα θα μεταφερθείτε αυτόματα στην αρχική σελίδα.</h4>
            <script language="javascript">
                    //μετά από 5 sec γυρνάει πίσω
                    window.setTimeout("window.location='index.php'", 15000);
            </script >
            {else}
                    < h3
                    style = 'color : red;' >{$error} < /h3>
            {/if}
        {/if}
</div>                                

{include file='footer.tpl'}
