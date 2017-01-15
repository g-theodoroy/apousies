{include file='header.tpl'}
{include file='body_header.tpl'}

<form name="frm"  method="post" onsubmit="return valid(this)" onreset="setdikwhite()" action="">

    <INPUT type="hidden" name="tmima" value="{$smarty.session.tmima}">
    <INPUT type="hidden" name="todo" value="new">

    <button type="button" name="minus1" value="-" onclick="day_minus();"> &lt;&lt; </button>
    &nbsp;
    <SELECT name="myday" id="myday" onchange="showHint();">
        <option >&nbsp;</option>
        {for $x = 1 to 31 step 1}
        {if $x == $myday}
            <option value='{if $x lt 10}0{/if}{$x}' selected >{$x}</option>
        {else}
            <option value='{if $x lt 10}0{/if}{$x}' >{$x}</option>
        {/if}
        {/for}
        <option >&nbsp;</option>
    </SELECT >
    &nbsp;
    <SELECT name="mymonth" id="mymonth" onchange="showHint();">
        <option >&nbsp;</option>
        {assign var='monthnames' value=','|explode:"Ιανουαρίου,Φεβρουαρίου,Μάρτίου,Απριλίου,Μαΐου,Ιουνίου,Ιουλίου,Αυγούστου,Σεπτεμβρίου,Οκτωβρίου,Νοεμβρίου,Δεκεμβρίου"}
        {for $x = 1 to 12 step 1}
        {if $x == $mymonth}
            <option value='{if $x < 10}0{/if}{$x}' selected >{$monthnames[$x-1]}</option>
        {else}
            <option value='{if $x < 10}0{/if}{$x}' >{$monthnames[$x-1]}</option>
        {/if}
        {/for}

        <option >&nbsp;</option>
    </SELECT >
    &nbsp;
    <SELECT name="myyear" id="myyear" onchange="showHint();">
        <option >&nbsp;</option>
    {assign var='curmonth' value=$smarty.now|date_format:"%m"}
    {if $curmonth < 8 }{assign var='curyear' value=$smarty.now|date_format:"%Y"-1}{else}{assign var='curyear' value=$smarty.now|date_format:"%Y"}{/if}
    {for $x = $curyear to $curyear+1 step 1}
    {if $x== $myyear}
        <option value='{$x}' selected >{$x}</option>
    {else}
        <option value='{$x}' >{$x}</option>
    {/if}
    {/for}
    <option >&nbsp;</option>
</SELECT >
&nbsp;
<button type="button"  name="plus1" value="+" onclick="day_plus();"> &gt;&gt; </button>

<hr class="space">


<table  align="center" cellpadding="0" cellspacing="0" border="1"  width="100%">
    <tbody  >

        <tr>
            <TD style="width:3%"></TD>
            <TD ></TD>
            {for $x = 1 to $apousies_columns step 1}
            <TD style="width:5%"></TD>
            {/for}
             <TD style="width:5%"></TD>
            <TD style="width:5%"></TD>
            <TD style="width:5%"></TD>
            <TD style="width:5%"></TD>
            <TD style="width:5%"></TD>
            <TD style="width:5%"></TD>
            <TD style="width:10%"></TD>
             <TD style="width:14%"></TD>
        </tr>


        <tr >
            <th colspan="2" rowspan="3" align="center" >{if isset($mark4tmima)}{$mark4tmima}{/if}ΟΝΟΜΑΤΕΠΩΝΥΜΟ<br>ΜΑΘΗΤΗ</th>
            <th colspan="{$apousies_columns+7}" align="center">ΑΠΟΥΣΙΕΣ</th>
            <th rowspan="3" align="center">ΗΜΕΡΕΣ ΠΟΥ<BR>ΚΑΤΑΧΩΡΗΘΗΚΑΝ</th>
        </tr>
        <tr>
            <th  rowspan="1" colspan="{$apousies_columns}">
                ΑΠΟΥΣΙΕΣ ΗΜΕΡΑΣ
            </th>
            </th>
            <th colspan="3">ΜΕΜΟΝΩΜΕΝΕΣ</th>
            <th colspan="2">ΑΠΟΒΟΛΕΣ
            <th colspan="2">
                ΔΙΚΑΙΟΛΟΓΗΜΕΝΕΣ
            </th>
        </tr>
        <tr>
            {foreach from=$apousies_def item=def}
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
            <th>  ΗΜΕΡ<br>ΗΣΙΕΣ</span>

            </th>
            <th>
                ΑΡΙ<br>ΘΜΟΣ
            </th>
            <th>
                ΑΠΟ
            </th>
         </tr>

        {foreach from=$students item=student name=foo}   
            <tr >
                <td align="center" id="tdnum{$student.am}">&nbsp;{$student.x}&nbsp;</td>
                <td id="tdname{$student.am}" title="{$student.showtmima}" ><input type="hidden"  name="am{$student.am}" id="am{$student.am}" value="{$student.am}" ><h4 align="left" style="margin:0px;" >{$student.epitheto} {$student.onoma}</h4></td>
                {assign var="index" value=0 }
                {foreach from=$apousies_def item=def}
                    <td align="center"><input type="text" name="ap{$def.kod}{$student.am}"  id="ap{$def.kod}{$student.am}" size="1"  class="white" maxlength="1"  value="" onkeypress="navigate(event)" {$state[$index]} ></td>
                    {assign var=index value=$index+1}
                {/foreach}

                <td align="center" ><input type="text" name="fh{$student.am}" id="fh{$student.am}" size="1" class="white"  maxlength="1"  value="" onkeypress="navigate(event)"</td>
                <td align="center" ><input type="text" name="mh{$student.am}" id="mh{$student.am}" size="1"  class="white"  maxlength="1"  value="" onkeypress="navigate(event)"</td>
                <td align="center" ><input type="text" name="lh{$student.am}" id="lh{$student.am}" size="1"  class="white"  maxlength="1"  value="" onkeypress="navigate(event)"</td>

                <td align="center"><input type="text" name="oa{$student.am}" id="oa{$student.am}" size="1"   class="gray" maxlength="1" value="" onkeypress="navigate(event)"</td>
                <td align="center"><input type="text" name="da{$student.am}" id="da{$student.am}" size="1"   class="gray" maxlength="1" value="" onkeypress="navigate(event)"</td>

                <td align="center" ><input type="text" name="dik{$student.am}" id="dik{$student.am}" size="1" class="white"  maxlength="1"  value="" onkeypress="navigate(event)"></td>
                <td align="center" >
                    <select name="from{$student.am}" id="from{$student.am}"  onchange="selectfrom_onchange('{$student.am}');" >
                        <option label="white" value=""></option>
                            {foreach from=$selectfrom item=data}   
                                <option label="{$data.color}" value="{$data.kod}" >{$data.perigrafi}</option>
                            {/foreach} 
                     </select>    
                </td>



 
                {if $smarty.foreach.foo.first}
                    <td align="center" rowspan="{$num}">
                        <SELECT name="daysadded"  id="daysadded" size="{$sizenum}" tabindex="-1" onchange="update_date();" style="font-size : 1em;width:97%;text-align:center;">
                            {foreach from=$daysadded item=dayadded}   
                                <option value="{$dayadded.checkdate}" >{$dayadded.date2show}</option>
                            {/foreach}
                        </SELECT >
                    </td>
                {/if}
            </tr>
        {/foreach}
    </tbody>
</table>


<p  align="center">
    <button type="submit" name="save" value="insert" >ΕΙΣΑΓΩΓΗ</button>&nbsp;
    <button type="button" name="delete" value="delete" onclick="send_delete()">ΔΙΑΓΡΑΦΗ</button>&nbsp;
    <button type="reset" name="clear" value="clear" onclick="">ΚΑΘΑΡΙΣΜΑ</button>&nbsp;
    <button type="button" name="unlock" value="unlock" onclick="unlockboxes()"  >ΞΕΚΛΕΙΔΩΜΑ</button>&nbsp;
    <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
</p>
</form>

{include file='footer.tpl'}
