{include file='header.tpl'}
{include file='body_header.tpl'}


<form name="frm" method="POST" action=""  onsubmit="return valid(this)">
    <INPUT type="hidden" name="tmima" value="{$smarty.session.tmima}">

    <table cellspacing="0"  cellpadding="0" align="center" >
        <tr>
            <th colspan="2" ><INPUT name="checknum" type="hidden" value="{$num}">Α/Α</th>
            <th >ΕΠΙΘΕΤΟ</th>
            <th >ΟΝΟΜΑ</th>
            <th>ΑΡ ΠΡΩΤ</th>
            <th>ΗΜ/ΝΙΑ</th>
            <th >ΑΠΟ</th>
            <th >ΕΩΣ</th>
            <th >ΗΜ</th>
            <th >ΙΑΤΡ ΒΕΒ</th>
        </tr>

        {foreach from=$data item='d'}
            <tr>
                <td style='text-align:center;'><INPUT type="checkbox" name="chk{$d.ind}" value="{$d.aa}" ></td>
                <td  style='text-align:right;'>{$d.k}</td>
                <td >{$d.epitheto}</td>
                <td  >{$d.onoma}</td>
                <td   style='text-align:right;'>{if $d.protokolo==0}&nbsp;{else}{$d.protokolo}{/if}</td>
                <td   style='text-align:right;'>{$d.date}</td>
                {if $d.firstday == $d.lastday}
                    <td  colspan=2 style='text-align:center;'>{$d.firstday}</td>
                {else}    
                    <td   style='text-align:center;'>{$d.firstday}</td>
                    <td   style='text-align:center;'>{$d.lastday}</td>
                {/if}
                <td   style='text-align:center;'>{$d.countdays}</td>
                <td   style='text-align:center;'>{if $d.iat_beb==1}&#x2611;{else}&#x2610;{/if}</td>
            </tr>
        {/foreach}
    </table>
    <p align="CENTER">
        <button  type="SUBMIT" onclick="return testdelete(this.form)" value="delete" name="delete" id="del">ΔΙΑΓΡΑΦΗ</button>&nbsp;
        <button  type="button" onclick="window.location='index.php'" value="back" name="back">ΕΠΙΣΤΡΟΦΗ</button>&nbsp;
    </p>

</form>

{include file='footer.tpl'}
