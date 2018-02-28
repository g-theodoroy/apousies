{include file='header.tpl'}
{include file='body_header.tpl'}

<div>
    <form name="frm" id="frm" method="POST" action="" >
        <INPUT type="hidden" name="tmima" value="{$smarty.session.tmima}">

        <table cellspacing="0"  cellpadding="0" align="center" >
            <tbody>
                <tr>
                    <th colspan="2"  width="5%"><INPUT name="checknum" type="hidden" value="{$num}">Α/Α</th>
                    <th width="8%">ΕΠΙΘΕΤΟ</th>
                    <th width="6%">ΟΝΟΜΑ</th>
                    <th width="5%">ΠΑΤΡΩΝΥΜΟ</th>
                    <th width="3%">ΑΜ</th>
                    <th width="5%">ΕΠΙΘΕΤΟ<br>ΚΗΔΕΜΟΝΑ</th>
                    <th width="5%">ΟΝΟΜΑ<br>ΚΗΔΕΜΟΝΑ</th>
                    <th>ΔΙΕΥΘΥΝΣΗ</th>
                    <th width="4%">ΤΚ</th>
                    <th width="5%">ΠΟΛΗ</th>
                    <th width="7%">ΤΗΛ1</th>
                    <th width="7%">KINHTO</th>
                    <th width="7%">EMAIL</th>
                    <th width="2%">ΦΥ<br>ΛΟ</th>
                        {foreach from=$tmimata_def item=tmima}
                        <th width="3%">{$tmima.label}</th>
                    {/foreach}
                </tr>
                {foreach from=$data item=stu}
                    <tr>
                        <td align="center"><INPUT type="checkbox" name="chk{$stu.i}" value="{$stu.am}" ></td>
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
                        <td align='center' title="{$stu.email}">{$stu.email}</td>
                        <td align='center' >{$stu.filo}</td>
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

        {if not isset($smarty.post.pdf)}
            <p align="CENTER">
            	{if $smarty.session.parentUser}
                <button  type="submit" onclick="return check_form(this.id)" value="alter" name="replace" id="alt">ΕΠΕΞΕΡΓΑΣΙΑ ΜΑΘΗΤΗ</button>&nbsp;
                <button  type="submit" onclick="return check_form(this.id)" value="delete" name="delete" id="del">ΔΙΑΓΡΑΦΗ ΜΑΘΗΤΗ</button>&nbsp;
				{/if}
                <button  type="submit" onclick="return check_form(this.id)" value="pdf" name="pdf" id="pdf">ΕΞΑΓΩΓΗ PDF</button>&nbsp;
                <button  type="button" onclick="window.location='index.php'" value="back" name="back">ΕΠΙΣΤΡΟΦΗ</button>&nbsp;
            </p>
        {/if}
    </form>
</div>

{include file='footer.tpl'}
