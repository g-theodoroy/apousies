{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="column span-13 prepend-4 last" align="center" >


    <h4 align="center">Αν δεν έχετε λογαριασμό δημιουργείστε ένα &nbsp;&nbsp;&nbsp;<a href="register.php"><strong>εδώ</strong></a></h4>
    <form action="" method="post" name="loginform">
        <table  align="center">
            <tr><td>Username:</td><td> <input name="username" type="text" ></td></tr>
            <tr><td>Password:</td><td> <input name="password" type="password"></td></tr>
            <tr><td colspan="2" align="center">&nbsp;</td></tr>
            <tr><td colspan="2" align="center"><h4><a href="restorepasswd.php" tabindex=-1>Ξέχασα το password</a></h4></td></tr>
            <tr><td colspan="2" align="center"><BUTTON type="submit" name="submitBtn" value="ΕΙΣΟΔΟΣ" >ΕΙΣΟΔΟΣ</BUTTON>&nbsp;<button type="button" name="back" value="ΕΠΙΣΤΡΟΦΗ" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button><br>&nbsp;</td></tr>
        </table>
    </form>

    {if isset($smarty.post.submitBtn)}

        {if $error}
            <table align="center" ><tr><td><br>
                        <h4 style='color : red;'>{$error}</h4>
                    </td></tr></table>
                {/if}

    {/if}
</div>                                

{include file='footer.tpl'}
