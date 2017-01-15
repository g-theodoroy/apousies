{include file='header.tpl'}
{include file='body_header.tpl'}


<form name="frm" id="frm" method=post action=''>
    <INPUT type="hidden" name="tmima" value="{$smarty.session.tmima}">
    <INPUT type="hidden" name="todo" value="">
    <INPUT type="hidden" name="oldstudent" id="oldstudent" value="{$newstudent}">

    <h4>Επιλογή μαθητή:<select name="newstudent" id="newstudent" onchange="check_form_submit();" >
            <option value="">απουσίες -> Επώνυμο Όνομα Πατρώνυμο</option>
            {foreach from=$selectdata item=seldata}
                {$seldata}
            {/foreach}
            <option value=""></option>
            <option value="all">&nbsp;&nbsp;&nbsp;Εκτύπωση όλων για εύκολο έλεγχο σε html</option>
            <!--
            <option value="allpdf">&nbsp;&nbsp;&nbsp;Εκτύπωση όλων για εύκολο έλεγχο σε pdf</option>
            -->
           <option value=""></option>
        </select></h4>


    <table  align="center" border="1" cellpadding="0" cellspacing="0"  >
        <tr>
            <th >ΤΕΤ</th>
            <th align="center" colspan="2">ΜΗΝ</th>
            {for $x = 1 to 31 step 1}
            <th align='center'><b>{$x}</b></th>
            {/for}
        </tr>
        <tr>
            <th rowspan="{5 * $showinputs}">Α΄</th>
            {assign var='mymonths' value=','|explode:"9,10,11,12,1"}

            {foreach from=$mymonths item=month}
        {if $month < 7}{assign var='addonindex' value= (($month+12) mod 9) * 31}{else}{assign var='addonindex' value= ($month mod 9) * 31}{/if}
        <th align='center' rowspan='{$showinputs}' ><b>{$month}ος</b></th>
        <th align='center'><b>{$row_names_array[0]}</b></th>
        {for $i = 1 to 31 step 1}
        {if $i mod 5 == 0}{assign var='background' value='lightgrey'}{else}{assign var='background' value='white'}{/if}
        {if $month == 1 && $i>20}
            <td id='grey' rowspan='{$showinputs}'>&nbsp;</td>
        {else}
            <td align='center' rowspan='{$showinputs}' ><b>
            {if isset($dataarray[$month][$i][0])}{assign var='myvalue' value = $dataarray[$month][$i][0]}{else}{assign var='myvalue' value =$novalue}{/if}
            {assign var='index' value = $i + $addonindex}
            <INPUT type='hidden' name='stdata{$index}' readonly maxlength='1' value='{$myvalue}'>
            {for $x = 0 to $apous_count-1 step 1} 
            {if isset($dataarray[$month][$i][2][$x]) and $dataarray[$month][$i][2][$x]>0}{assign var='myvalue' value = $dataarray[$month][$i][2][$x]}{else}{assign var='myvalue' value=""}{/if}
            {if $x < $showinputs}           
                <INPUT type='text' name='stap{$index}' size='1' disabled maxlength='1' class='{if $myvalue}{$dataarray[$month][$i][3]}{else}{$background}{/if}' value='{$myvalue}'><br>
			{else}
                <INPUT type='hidden' name='stap{$index}' size='1' disabled maxlength='1' class='{if $myvalue}{$dataarray[$month][$i][3]}{else}{$background}{/if}' value='{$myvalue}'>
			{/if}
            {/for}
            </td>
        {/if}
{/for}
</tr>
{for $x=1 to $apous_count-1 step 1}
{if $x < $showinputs}           
<tr>
    <th align='center'><b>{$row_names_array[$x]}</b></th>
</tr>
{/if}
{/for}

{/foreach}

<th rowspan="{5 * $showinputs}">Β΄</th>
{assign var='mymonths' value=','|explode:"1,2,3,4,5"}

{foreach from=$mymonths item=month}
{if $month < 7}{assign var='addonindex' value= (($month+12) mod 9) * 31}{else}{assign var='addonindex' value= ($month mod 9) * 31}{/if}
<th align='center' rowspan='{$showinputs}' ><b>{$month}ος</b></th>
<th align='center'><b>{$row_names_array[0]}</b></th>
{for $i = 1 to 31 step 1}
    {if $month == 1 && $i<21}
        <td id='grey' rowspan='{$showinputs}'>&nbsp;</td>
    {else}
        {if $i mod 5 == 0}{assign var='background' value='lightgrey'}{else}{assign var='background' value='white'}{/if}
        <td align='center' rowspan='{$showinputs}' ><b>
            {if isset($dataarray[$month][$i][0])}{assign var='myvalue' value = $dataarray[$month][$i][0]}{else}{assign var='myvalue' value=$novalue}{/if}
            {assign var='index' value = $i + $addonindex}
            <INPUT type='hidden' name='stdata{$index}' readonly maxlength='1' value='{$myvalue}'>
            {for $x = 0 to $apous_count-1 step 1}            
            {if isset($dataarray[$month][$i][2][$x]) and $dataarray[$month][$i][2][$x]>0}{assign var='myvalue' value = $dataarray[$month][$i][2][$x]}{else}{assign var='myvalue' value =""}{/if}
            {if $x < $showinputs}           
                <INPUT type='text' name='stap{$index}' size='1' disabled maxlength='1' class='{if $myvalue}{$dataarray[$month][$i][3]}{else}{$background}{/if}' value='{$myvalue}'><br>
			{else}
                <INPUT type='hidden' name='stap{$index}' size='1' disabled maxlength='1' class='{if $myvalue}{$dataarray[$month][$i][3]}{else}{$background}{/if}' value='{$myvalue}'>
			{/if}
            {/for}
        </td>
    {/if}
{/for}

</tr>
{for $x=1 to $apous_count-1 step 1}
{if $x < $showinputs}           
<tr>
    <th align='center'><b>{$row_names_array[$x]}</b></th>
</tr>
{/if}
{/for}
{/foreach}

</tbody>
</table>

<h4 align="center">
    <button type="button" name="eisagogi" value="edit" onclick="{literal}if(document.frm.newstudent.value==''){alert('Επιλέξτε πρώτα ένα μαθητή-τρια.');}else{window.open('epexergasia.php','insertwin','width=450 , height=700,left=350,top=200');}{/literal}">ΕΠΕΞΕΡΓΑΣΙΑ ΑΠΟΥΣΙΩΝ</button>&nbsp;
    <button type="button" name="save" value="save" onclick="if(document.frm.todo.value=='save')frm.submit();">ΑΠΟΘΗΚΕΥΣΗ ΑΛΛΑΓΩΝ</button>&nbsp; 
    <button type="button" name="print" value="print" onclick="window.open('{$parous_print}&st={$newstudent}', '_blank')">ΕΚΤΥΠΩΣΗ</button>&nbsp; 
    <button type="button" name="pdf" value="pdf" onclick="window.open('{$parous_print}&st={$newstudent}&do=pdf', '_self')">PDF</button>&nbsp; 
    <button type="button" name="save" value="save" onclick="showHint();">ΣΥΝΟΛΑ</button>&nbsp; 
    <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
</h4>

<span id="txtHint"></span>

</form>

{include file='footer.tpl'}
