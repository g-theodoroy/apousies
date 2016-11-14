{include file='header.tpl'}
{include file='body_header.tpl'}


<table cellspacing='0' cellpadding='0' align='center' class="nomargin" >


    {foreach from=$studentsdata item='studata' name='out'}
        {foreach from=$studata['data'] item='stud'  name='foo'}
            <tr>
                  {if $smarty.foreach.foo.first}<td {if isset($studata['rows'])}rowspan={$studata['rows']}{/if}>{$stud[0]}</td>{/if}
                {for $i=1 to $stud|count-1 step 1}
                    <td>{$stud[$i]}</td>

                {/for}
            </tr>
        {/foreach}
    {/foreach}

</table>

    <br>
<h4 align="center">
    <button type="button" name="exit" value="exit"  onclick="history.back()">ΕΠΙΣΤΡΟΦΗ</button>&nbsp;
    <button type="button" name="exit" value="exit"  onclick="window.location='index.php'">ΤΕΛΟΣ</button>
</h4>


{include file='footer.tpl'}
