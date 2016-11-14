{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="block">
    <div class="column span-17 prepend-2 last" align="center">
        {if not isset($smarty.post.submitBtn) || $error}
            <form action="" method="post" name="registerform">
                <table  align="center" >
                    <tr><td align="right">Username:</td><td>{$smarty.session.userName}</td></tr>
                    <tr><td align="right">password:</td><td><input name="oldpass" type="password" ></td></tr>
                    <tr><td align="right">email:</td><td> <input name="email" type="text" value='{$email}'></td></tr>
                    <tr><td align="right">Κάτι που εύκολα θυμάμαι:</td><td><input name="check" type="text" value='{$check}'></td></tr>
                    <tr><td align="right">Ημερομηνία Δημιουργίας:</td><td>{$mydate}</td></tr>
                    <tr><td colspan="2" align="center">&nbsp;</td></tr>
                    <tr><td colspan="2" align="center">Για αλλαγή του password συμπληρώστε:</td></tr>
                    <tr><td align="right">Νέο password:</td><td><input name="newpass1" type="password" ></td></tr>
                    <tr><td align="right">Ξανά το νέο password:</td><td><input name="newpass2" type="password" ></td></tr>
                    <tr><td colspan="2" align="center">&nbsp;</td></tr>
                    <tr><td colspan="2" align="center"><h4 align="center">{if $smarty.session.parentUser}<button type="button" name="delme" value="ΔΙΑΓΡΑΨΦΗ" onclick="delete_me();" tabindex=-1>ΔΙΑΓΡΑΦΗ</button>&nbsp;{/if}<button type="submit" name="submitBtn" value="ΚΑΤΑΧΩΡΗΣΗ" >ΚΑΤΑΧΩΡΗΣΗ</button>&nbsp;<button type="button" name="back" value="ΕΠΙΣΤΡΟΦΗ" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button><br>&nbsp;</h4></td></tr>
                </table>
            </form>
        {/if}
        {if isset($smarty.post.submitBtn)}
                {if not $error}
                    <h4 align="center">Οι αλλαγές αποθηκεύτηκαν.</h4>
                    <h4 align="center">Ευχαριστούμε.</h4>
                    <script language="javascript">
                        window.setTimeout("window.location='index.php'",2000);
                    </script >
                {else}
                    <h4 style='color : red;'>{$error}</h4>
                {/if}
        {/if}
    </div>
</div>

{include file='footer.tpl'}
