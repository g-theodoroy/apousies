{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="column span-13 prepend-4 last" align="center" >

    
    <form action="" method="post" >
        <table>
             <tr><td colspan=3 ><h4>
                         Ρυθμίστε τις στήλες-κατηγορίες μαθημάτων-απουσιών καθώς<br>
                         και από ποιους μπορούν να δικαιολογούνται οι απουσίες.</h4></td></tr>
            {$apoustr}
            <tr><td colspan=3 >&nbsp;</td></tr>
            {$dikstr}
        </table>
        <p>
            <BUTTON type="submit" name="submitBtn" value="ok" >ΑΠΟΘΗΚΕΥΣΗ</BUTTON>&nbsp;
            <button type="button" name="back" value="ΕΠΙΣΤΡΟΦΗ" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
        </p>
    </form>

</div>                                

{include file='footer.tpl'}
