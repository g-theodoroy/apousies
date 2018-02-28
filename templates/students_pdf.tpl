{if isset($smarty.session.tmima)}
    <h4>Τμήμα {$smarty.session.tmima}</h4>
{else}
    <h4>Όλοι οι μαθητές</h4>
{/if}
<table cellspacing="0"  cellpadding="0" align="center" border='1' >
    <tbody>
        <tr>
            <th >Α/Α</th>
            <th >ΕΠΙΘΕΤΟ</th>
            <th >ΟΝΟΜΑ</th>
            <th >ΠΑΤΡΩΝΥΜΟ</th>
            <th >ΑΜ</th>
            <th >ΕΠΙΘΕΤΟ<br>ΚΗΔΕΜΟΝΑ</th>
            <th >ΟΝΟΜΑ<br>ΚΗΔΕΜΟΝΑ</th>
            <th >ΔΙΕΥΘΥΝΣΗ</th>
            <th >ΤΚ</th>
            <th >ΠΟΛΗ</th>
            <th >ΤΗΛ1</th>
            <th >KINHTO</th>
            <th >EMAIL</th>
            {foreach from=$tmimata_def item=tmima}
                <th width="3%">{$tmima.label}</th>
            {/foreach}
        </tr>
        {foreach from=$data item=stu}
            <tr>
                <td align="center">{$stu.k}</td>
                <td  title="{$stu.epi}">{$stu.epi}</td>
                <td  title="{$stu.ono}">{$stu.ono}</td>
                <td  title="{$stu.pat}">{$stu.pat}</td>
                <td align='center'>{$stu.am}</td>
                <td  title="{$stu.epkid}">{$stu.epkid}</td>
                <td  title="{$stu.onkid}">{$stu.onkid}</td>
                <td  title="{$stu.die}">{$stu.die}</td>
                <td align='center'>{$stu.tk}</td>
                <td  title="{$stu.poli}">{$stu.poli}</td>
                <td align='center' title="{$stu.til1}">{$stu.til1}</td>
                <td align='center' title="{$stu.til2}">{$stu.til2}</td>
                <td  title="{$stu.email}">{$stu.email}</td>
                {foreach from=$tmimata_def item=tmima}
                	{if isset($stu.tmimata.{$tmima.kod})}
                    	<td align='center' title="{$stu.tmimata.{$tmima.kod}}">{$stu.tmimata.{$tmima.kod}}</td>
                    {else}
                    	<td align='center' title="">&nbsp;</td>
                    {/if}
                {/foreach}
            </tr>
        {/foreach}
    </tbody>
</table>
