<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>{$title}</title>

        {$extra_style}

    </head>
    <body>

        <table cellspacing="0" cellpadding="0" align="center" border="0" class="fixedlayout">

            {foreach from=$labelsdata item='labelrow' name='foo'}

                <tr>

                    {foreach from=$labelrow item='label' }
                        <td class="mylabel">{$label.name}<br>{$label.dieythinsi}<br>ΤΚ:&nbsp;{$label.tk}<br>{$label.poli}</td>
                    {/foreach}

                    {if $smarty.foreach.foo.last}
                        {for $i= 1 to  $addcells}
                            <td class="mylabel">&nbsp;</td>
                        {/for}
                    {/if}
                </tr>
                {if $smarty.foreach.foo.index == $rows}
                </table>
                <p class='breakhere'>
                <table cellspacing="0" cellpadding="0" align="center" border="0" class="fixedlayout">
                {/if}
            {/foreach}
        </table>
    </body>
</html>

