{include file='header.tpl'}
{include file='body_header.tpl'}

<form name="frm"  method="post" onsubmit="return valid(this)" action="">

    <INPUT type="hidden" name="tmima" value="{$tmima}">

    <table  align="center" cellpadding="0" cellspacing="0" border="1"  width="100%">
        <tbody  >

            <tr>
                <TD style="width:3%"></TD>
                <TD ></TD>
                {for $x = 1 to $apou_count step 1}
                <TD style="width:5%"></TD>
                {/for}
                <TD style="width:5%"></TD>
                <TD style="width:5%"></TD>
                <TD style="width:5%"></TD>
                <TD style="width:5%"></TD>
                <TD style="width:5%"></TD>
                <TD style="width:5%"></TD>
                {for $x = 1 to $dik_count step 1}
                <TD style="width:5%"></TD>
                {/for}

                <TD style="width:12%"></TD>
            </tr>


            <tr >
                <th colspan="2" rowspan="3" align="center" >ΟΝΟΜΑΤΕΠΩΝΥΜΟ<br>ΜΑΘΗΤΗ</th>
                <th colspan="{$apou_count + 6 + $dik_count}" align="center">ΑΠΟΥΣΙΕΣ</th>
                <th rowspan="3" align="center">ΕΓΙΝΑΝ<br>ΜΕΧΡΙ<BR>ΤΗΝ ΗΜΝΙΑ</th>
            </tr>
            <tr>
                <th  rowspan="1" colspan="{$apou_count}">
                    ΑΠΟΥΣΙΕΣ ΗΜΕΡΑΣ
                </th>
                </th>
                <th colspan="3">ΜΕΜΟΝΩΜΕΝΕΣ</th>
                <th colspan="2">ΑΠΟΒΟΛΕΣ
                <th colspan="{$dik_count+1}">
                    ΔΙΚΑΙΟΛΟΓΗΜΕΝΕΣ
                </th>
            </tr>
            <tr>
                {foreach from=$apou_define item=def}
                <th>{$def.label}</th>
                {/foreach}
           <th>
                    1η 
                    <br>
                    ΩΡΑ
                </th>
                <th>
                    ΕΝΔΙΑ <br>ΜΕΣΑ
                    <br>
                </th>
                <th>
                    ΤΕΛ 
                    <br>
                    ΩΡΑ
                </th>
                <th>
                    ΩΡΙ
                    <br>
                    ΑΙΕΣ
                </th>
                <th>  ΗΜΕΡ<br>ΗΣΙΕΣ</th>
                <th>  ΗΜΕΡ<br>ΚΗΔΕ</th>
                 {foreach from=$dik_define item=def}
                <th>{$def.label}</th>
                {/foreach}
           </tr>

        {foreach from=$studentsdata item=studata }
                <tr >
                    <td align="center" id="tdnum{$studata.am}">&nbsp;{$studata.x}&nbsp;</td>
                    <td id="tdname{$studata.am}" ><input type="hidden"  name="am{$studata.am}" id="am{$studata.am}" value="{$studata.am}" ><h4 align="left" style="margin:0px;">{$studata.epitheto} {$studata.onoma}</h4></td>
                {for $x = 0 to $apou_count-1 step 1}
                    <td align="center"><input type="text" name="ap{$apou_define[$x].kod}{$studata.am}"  id="ap{$apou_define[$x].kod}{$studata.am}" size="1"  class="white" maxlength="3"  value="{$studata.apous[$x]}" onkeypress="navigate(event)" ></td>
                {/for}

                    <td align="center" ><input type="text" name="fh{$studata.am}" id="fh{$studata.am}" size="1" class="white"  maxlength="3"  value="{$studata.fh}" onkeypress="navigate(event)"</td>
                    <td align="center" ><input type="text" name="mh{$studata.am}" id="mh{$studata.am}" size="1"  class="white"  maxlength="3"  value="{$studata.mh}" onkeypress="navigate(event)"</td>
                    <td align="center" ><input type="text" name="lh{$studata.am}" id="lh{$studata.am}" size="1"  class="white"  maxlength="3"  value="{$studata.lh}" onkeypress="navigate(event)"</td>

                    <td align="center"><input type="text" name="oa{$studata.am}" id="oa{$studata.am}" size="1"   class="gray" maxlength="3" value="{$studata.oa}" onkeypress="navigate(event)"</td>
                    <td align="center"><input type="text" name="da{$studata.am}" id="da{$studata.am}" size="1"   class="gray" maxlength="3" value="{$studata.da}" onkeypress="navigate(event)"</td>

                    <td align="center" ><input type="text" name="daysp{$studata.am}" id="daysp{$studata.am}" size="1" class="green"  maxlength="3"  value="{$studata.daysp}" onkeypress="navigate(event)"></td>
                {for $x = 0 to $dik_count-1 step 1}
                    <td align="center"><input type="text" name="di{$dik_define[$x].kod}{$studata.am}"  id="di{$dik_define[$x].kod}{$studata.am}" size="1"  class="{$dik_define[$x].color}" maxlength="3"  value="{$studata.dik[$x]}" onkeypress="navigate(event)" ></td>
                {/for}
                    <td align="center" ><input type="text" name="date{$studata.am}" id="date{$studata.am}" class="white" value="{$studata.date}" style="width:100%;" onkeypress="navigate(event)"></td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <p  align="center">
        <button type="submit" name="save" value="insert" >ΕΙΣΑΓΩΓΗ</button>&nbsp;
        <button type="reset" name="clear" value="clear" onclick="">ΚΑΘΑΡΙΣΜΑ</button>&nbsp;
        <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
    </p>
</form>


{include file='footer.tpl'}
