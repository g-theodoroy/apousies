{include file='header.tpl'}
{include file='body_header.tpl'}

<div>
    <form name="frm" id="frm" method="POST" action="" >
    	{assign var=tmimacount value=0}
        <table cellspacing="10"  cellpadding="0" border=1 align="center" >
            <tbody>
                <tr>
                    <th colspan="2"  width="5%"><INPUT name="checkusernum" type="hidden" value="{$num}"><INPUT name="checktmimanum" type="hidden" value="{$tmimanum}">Α/Α</th>
                    <th "width="15%">USERNAME</th>
                    <th ">PASSWORD</th>
                    <th width="15%"">REMINDER</th>
                    <th width="15%"">EMAIL</th>
                      {foreach from=$tmimata_def item=tmima}
                        <th width="10%" colspan=2 >{$tmima.label}</th>
                    {/foreach}
                </tr>
                {foreach from=$data item=stu}
                    <tr>
                        <td align="center"><INPUT type="checkbox" id="chkusr{$stu.i}" name="chkusr{$stu.i}" value="{$stu.username}" ></td>
                        <td align="center">{$stu.k}</td>
                        <td  title="{$stu.username}">{$stu.username}</td>
                        <td  title="{$stu.password}">{$stu.password}</td>
                        <td  title="{$stu.reminder}">{$stu.reminder}</td>
                        <td  title="{$stu.email}">{$stu.email}</td>
                        {foreach from=$tmimata_def item=tmima}
                        	{if isset($stu.tmimata.{$tmima.kod})}
                            	<td style='text-align:right' >
                         	{foreach from=$stu.tmimata.{$tmima.kod} item=subtmima}
                           		<INPUT type="checkbox" id="chktmi{$tmimacount}" name="chktmi{$tmimacount}-{$stu.username}" value="{$subtmima}" ><br>
                           		{assign var=tmimacount value=$tmimacount+1}
                        	 {/foreach}
                            	</td>
                            	<td style='text-align:left' >
                         	{foreach from=$stu.tmimata.{$tmima.kod} item=subtmima}
                           		{$subtmima}<br>
                        	 {/foreach}
                            	</td>
                           {else}
                            	<td colspan=2 align='center' title="">&nbsp;</td>
                            {/if}
                        {/foreach}
                    </tr>
                {/foreach}
                     <tr>
                        <th colspan=2 align="center">Νέος</th>
                        <td><INPUT type="text" name="username" id="username" value="" ></td>
                        <td><INPUT type="text" name="password" id="password" value="" ></td>
                        <td><INPUT type="text" name="reminder" id="reminder" value="" ></td>
                        <td><INPUT type="text" name="email" id="email" value="" ></td>
        				{for $y = 0 to $loopcount step 1}
			            	<td colspan=2 >{if isset($tmimata_select_boxes[$y])}{$tmimata_select_boxes[$y]}{/if}</td>
 				        {/for}
                    </tr>
            </tbody>
        </table>

        {if not isset($smarty.post.pdf)}
            <p align="CENTER">
                <button  type="submit" onclick="return check_form(this.id)" value="new" name="new" id="new">ΕΙΣΑΓΩΓΗ ΧΡΗΣΤΗ</button>&nbsp;
                <button  type="submit" onclick="return check_form(this.id)" value="update" name="update" id="update">ΤΡΟΠΟΠΟΙΗΣΗ ΧΡΗΣΤΗ</button>&nbsp;
                <button  type="submit" onclick="return check_form(this.id)" value="deluser" name="deluser" id="deluser">ΔΙΑΓΡΑΦΗ ΧΡΗΣΤΗ</button>&nbsp;
                <button  type="submit" onclick="return check_form(this.id)" value="deltmima" name="deltmima" id="deltmima">ΔΙΑΓΡΑΦΗ ΤΜΗΜΑΤΟΣ</button>&nbsp;
                <button  type="button" onclick="window.location='index.php'" value="back" name="back">ΕΠΙΣΤΡΟΦΗ</button>&nbsp;
            </p>
        {/if}
    </form>
    
    {if $errorTextRegister}
		<h4 style='color : red;'><br>{$errorTextRegister}</h4>
	{/if}
    
</div>

{include file='footer.tpl'}
