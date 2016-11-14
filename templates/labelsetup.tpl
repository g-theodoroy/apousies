{include file='header.tpl'}
{include file='body_header.tpl'}

<form name="frm"  method="POST" action="labels.php" >



    <table cellspacing="0" cellpadding="0" align="center" >
        <tbody >
            <tr>
                <TD colspan='2'>Μέγεθος σελίδας</TD>
                <TD><select name='papersize' ><option value='A4'>A4</option></select></TD>
            </tr>
            <tr><TD colspan="3"><hr ></TD></tr>
            <tr>
                <TD rowspan='4'>Περιθώρια σελίδας</TD>
                <TD>επάνω</TD>
                <TD><INPUT type="text" name="page_top" value="5" size="2" style="text-align : center;">&nbsp;mm</TD>
            </tr>
            <tr>
                <TD>κάτω</TD>
                <TD><INPUT type="text" name="page_bottom" value="5" size="2" style="text-align : center;">&nbsp;mm</TD>
            </tr>
            <tr>
                <TD>αριστερά</TD>
                <TD><INPUT type="text" name="page_left" value="5" size="2" style="text-align : center;">&nbsp;mm</TD>
            </tr>
            <tr>
                <TD>δεξιά</TD>
                <TD><INPUT type="text" name="page_right" value="5" size="2" style="text-align : center;">&nbsp;mm</TD>
            </tr>
     <!--	<tr>
            <TD>Περιθώριο ετικέτας αριστερά &amp; δεξιά:</TD>
            <TD><INPUT type="text" name="labelspace" value="10" size="2" style="text-align : center;">&nbsp;mm</TD>
            </tr> -->
            <tr><TD colspan="3"><hr ></TD></tr>
            <tr >
                <TD colspan='2'>Ετικέτες σε κάθε γραμμή</TD>
                <TD><INPUT type="text" name="cols" value="3" size="2" style="text-align : center;">&nbsp;ετικέτες</TD>
            </tr>
            <tr>
                <TD colspan='2'>Γραμμές σε κάθε σελίδα</TD>
                <TD><INPUT type="text" name="rows" value="8" size="2" style="text-align : center;">&nbsp;γραμμές</TD>
            </tr> 
            <tr><TD colspan="3"><hr ></TD></tr>
                <TD colspan='2'>Περιγράμματα ορατά</TD>
                <TD><select name='showborders' ><option value='0'>ΟΧΙ</option><option value='1'>ΝΑΙ</option></select></TD>
           <tr><TD colspan="3"><hr ></TD></tr>
            <tr >
                <TD colspan='2'>Εκτύπωσε όσους έχουν πάνω από </TD>
                <TD><INPUT type="text" name="apousnum" value="{$orio_paper}" size="2" style="text-align : center;">&nbsp;απουσίες<br>κενό = όλοι</TD>
            </tr>

        </tbody>
    </table>

    <h4 align="center">
        <button type="submit" name="submit" value="print" onclick="frm.target='_blank';">ΕΚΤΥΠΩΣΗ</button>&nbsp;
        <button type="submit" name="submit" value="pdf" onclick="frm.target='';">ΕΞΑΓΩΓΗ PDF</button>&nbsp;
        <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
    </h4>
</form>

{include file='footer.tpl'}
