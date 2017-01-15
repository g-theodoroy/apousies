{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="block">
    <div class="column span-13 prepend-5 last" align="center" >

        {if not isset($smarty.post.submitBtn) || $error }
            <form action="" method="post" name="registerform">
                <table  align="center" width="30em">
                    <tr><td colspan="2" align="center">
                            <h4>Πληκτρολογείστε το username και ότι γράψατε όταν κάνατε εγγραφή στο πεδίο "Κάτι που εύκολα θυμάμαι".</h4>
                        </td></tr>
                    <tr><td colspan="2" align="center">&nbsp;</td></tr>
                    <tr><td align="right">Username:</td><td><input name="user" type="text" ></td></tr>
                    <tr><td align="right">Κάτι που εύκολα θυμάμαι:</td><td><input name="check" type="text"></td></tr>
                    <tr><td colspan="2" align="center">&nbsp;</td></tr>
                    <tr><td colspan="2" align="center"><button type="submit" name="submitBtn" value="ΥΠΟΒΟΛΗ" >ΥΠΟΒΟΛΗ</button>&nbsp;<button type="button" name="back" value="ΕΠΙΣΤΡΟΦΗ" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button><br>&nbsp;</td></tr>
                </table>
            </form>
        {/if}

        {if isset($smarty.post.submitBtn)}
            <table align="center">
                <tr>
                    <td>
                        {if not $error }
                            <h4 align="center">Σας έχει σταλέι email με το καινούριο password</h4>
                            <h4 align="center">Ευχαριστούμε.</h4>
                            {literal}
                            <script language="javascript">
                            window.setTimeout("window.location='index.php'",5000);
                            </script >
                            {/literal}
                        {else}
                            <h4 style='color : red;'>{$error}</h4>
                        {/if}
                    </td>
                </tr>
            </table>
        {/if}

{include file='footer.tpl'}
