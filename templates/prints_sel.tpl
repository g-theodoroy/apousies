{include file='header.tpl'}
{include file='body_header.tpl'}

<form name="frm" action="prints.php" method="POST" onsubmit="return validateform(this);">

    <table >
        <TR >
            <Th colspan="16">ΕΠΙΛΟΓΗ ΠΕΔΙΩΝ ΓΙΑ ΕΜΦΑΝΙΣΗ</Th>
        </TR>
        <Tr><TD colspan="16"><HR></Tr></tr>
        <tr>
            <th >ΣΥΝ</th>
            {foreach from=$apous_fld item=fld}
                <th >{$fld.label}</th>
            {/foreach}
            <th >ΑΔΙΚ</th>
            <th >ΔΙΚ</th>
            <th >ΗΜΕ<br>ΚΗΔ</th>
                {foreach from=$dik_fld item=fld}
                <th >{$fld.label}</th>
            {/foreach}
            <th>ΩΡ<br>ΑΠΟΒ</th>
            <th>ΗΜ<br>ΑΠΟΒ</th>
            <th>ΣΥΝ<br>ΑΠΟΒ</th>
            <th >1η<br>ΩΡΑ</th>
            <th >ΕΝΔ<br>ΩΡΑ</th>
            <th >ΤΕΛ<br>ΩΡΑ</th>
        </tr>
        <tr>
            <td ><input type="checkbox" name="fldtot" value="fldtot" checked/></td>
                {foreach from=$apous_fld item=fld}
                <td ><input type="checkbox" name="{$fld.kod}" value="{$fld.kod}"/></td>
                {/foreach}
            <td ><input type="checkbox" name="fldadik" value="fldadik" checked /></td>
            <td ><input type="checkbox" name="flddik" value="flddik" checked/></td>
            <td ><input type="checkbox" name="flddaysp" value="flddaysp" /></td>
                {foreach from=$dik_fld item=fld}
                <td><input type="checkbox" name="{$fld.kod}" value="{$fld.kod}" /></td>
                {/foreach}
            <td><input type="checkbox" name="fldoa" value="fldoa" checked /></td>
            <td><input type="checkbox" name="fldda" value="fldda"/></td>
            <td><input type="checkbox" name="fldoada" value="fldoada"/></td>
            <td ><input type="checkbox" name="fldfh" value="fldfh" checked /></td>
            <td ><input type="checkbox" name="fldmh" value="fldmh" checked /></td>
            <td ><input type="checkbox" name="fldlh" value="fldlh" checked /></td>
        </tr>
    </table>

    <table  align="center" border="0" cellpadding="20" >
        <tr>
            <td >
                <TABLE style="width:27em;">
                    <TR>
                        <Th colspan="2">ΜΑΘΗΤΕΣ</Th>
                    </TR>
                    <tr><TD colspan="2"><HR></Tr></tr>
        <TR>
            <TD>Επιλογή όλων</TD>
            <TD align="center"><INPUT type="button" name="all" value="ΝΑΙ" onclick="selectall()"></input></TD>
        </TR>
        <Tr><TD colspan="2"><HR></Tr></tr>
                {foreach from=$studentsdata item=studata}
            <TR>
                <TD>{$studata.name}</TD>
                <TD align="center"><INPUT type="checkbox" name="stu{$studata.am}" value="{$studata.am}"></TD>
            </TR>
        {/foreach}
    </TABLE>
</td>
<td >
    <TABLE style="width:38em;">
        <TR>
            <Th colspan="5">ΧΡΟΝΙΚΗ ΠΕΡΙΟΔΟΣ</Th>
        </TR><TD colspan="5"><HR></TR>
        <TR>
            <TD>Σεπτέμβριος</TD>
            <TD><INPUT type="checkbox" name="month09" value="09"></TD>
            <TD width="40">&nbsp;</TD>
            <TD>Α ΤΕΤΡ (αρχή - <INPUT type="text" name="periodendAtetr" value="20/01" size="3">)</TD>
            <TD><INPUT type="checkbox" name="tetrA" value="A"></TD>
        </TR>
        <TR>
            <TD>Οκτώβριος</TD>
            <TD><INPUT type="checkbox" name="month10" value="10"></TD>
            <TD>&nbsp;</TD>
            <TD>Β ΤΕΤΡ (<INPUT type="text" name="periodbegBtetr" value="21/01" size="3"> - τέλος)</TD>
            <TD><INPUT type="checkbox" name="tetrB" value="B"></TD>
        </TR>
        <TR>
            <TD>Νοέμβριος</TD>
            <TD><INPUT type="checkbox" name="month11" value="11"></TD>
            <TD>&nbsp;</TD>
            <TD colspan="2"><HR></TD>
        </TR>
        <TR>
            <TD>Δεκέμβριος</TD>
            <TD><INPUT type="checkbox" name="month12"  value="12"></TD>
            <TD>&nbsp;</TD>
            <TD>Α ΤΡΙΜ (αρχή - <INPUT type="text" name="periodendAtrim" value="30/11" size="3">)</TD>
            <TD><INPUT type="checkbox" name="trimA" value="A"></TD>
        </TR>
        <TR>
            <TD>1-20 Ιανουαρίου</TD>
            <TD><INPUT type="checkbox" name="month01f" value="-1"></TD>
            <TD>&nbsp;</TD>
            <TD>Β ΤΡΙΜ (<INPUT type="text" name="periodbegBtrim" value="01/12" size="3"> - <INPUT type="text" name="periodendBtrim" value="28/02" size="3">)</TD>
            <TD><INPUT type="checkbox" name="trimB" value="B"></TD>
        </TR>
        <TR>
            <TD>Ιανουάριος</TD>
            <TD><INPUT type="checkbox" name="month01" value="01"></TD>
            <TD>&nbsp;</TD>
            <TD>Γ ΤΡΙΜ (<INPUT type="text" name="periodbegGtrim" value="01/03" size="3"> - τέλος)</TD>
            <TD><INPUT type="checkbox" name="trimG" value="G"></TD>
        </TR>
        <TR>
            <TD>21-31 Ιανουαρίου</TD>
            <TD><INPUT type="checkbox" name="month01l" value="+1"></TD>
            <TD>&nbsp;</TD>
            <TD>Α+Β ΤΡΙΜ</TD>
            <TD><INPUT type="checkbox" name="trimAB" value="AB"></TD>
        </TR>
        <TR>
            <TD>Φεβρουάριος</TD>
            <TD><INPUT type="checkbox" name="month02" value="02"></TD>
            <TD>&nbsp;</TD>
            <TD>Β+Γ ΤΡΙΜ</TD>
            <TD><INPUT type="checkbox" name="trimBG" value="BG"></TD>
        </TR>
        <TR>
            <TD>Μάρτιος</TD>
            <TD><INPUT type="checkbox" name="month03" value="03"></TD>
            <TD>&nbsp;</TD>
            <TD colspan="2"><HR></TD>
        </TR>
        <TR>
            <TD>Απρίλιος</TD>
            <TD><INPUT type="checkbox" name="month04" value="04"></TD>
            <TD>&nbsp;</TD>
            <TD rowspan="2">Όλη η χρονιά</TD>
            <TD rowspan="2"><INPUT type="checkbox" name="total" value="total" checked ></TD>
        </TR>
        <TR>
            <TD>Μάιος</TD>
            <TD><INPUT type="checkbox" name="month05" value="05"></TD>
            <TD>&nbsp;</TD>
        </TR>
        <TR><TD colspan="5"><HR></TD></TR>
        <TR>
            <TD colspan="3" align="center">Από <INPUT type="text" name="st2ststart" size="10"></TD>
            <TD colspan="1" align="center">ΣΥΝΟΠΤΙΚΑ</TD>
            <TD><INPUT type="checkbox" name="st2stsum" value="st2stsum"></TD>
        </TR>
        <TR>			
            <TD colspan="3" align="center">Έως <INPUT type="text" name="st2ststop" size="10"></TD>
            <TD colspan="1" align="center">ΑΝΑΛΥΤΙΚΑ</TD>
            <TD><INPUT type="checkbox" name="st2stdet" value="st2stdet"></TD>
        </TR>
    </TABLE>

</td>
</tr>
</table>



<p align="center">
    <button type="submit" name="submitBtn" value="view" onclick="frm.target='' ">ΠΡΟΒΟΛΗ</button>&nbsp;
    <button type="submit" name="submitBtn" value="print" onclick="frm.target='_blank' ">ΕΚΤΥΠΩΣΗ</button>&nbsp;
    <button type="submit" name="submitBtn" value="pdf" onclick="frm.target=''" title='Παρακαλώ, χρησιμοποιείτε αυτή την επιλογή με σύνεση γιατί καταναλωνει πολλή μνήμη!!!' >PDF</button>&nbsp;
    <button type="submit" name="submitBtn" value="xls" onclick="frm.target=''" title='Παρακαλώ, χρησιμοποιείτε αυτή την επιλογή με σύνεση γιατί καταναλωνει πολλή μνήμη!!!' >XLS</button>&nbsp;
    <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
</p>

</form>


{include file='footer.tpl'}
