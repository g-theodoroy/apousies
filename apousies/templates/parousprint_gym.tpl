<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="el">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- Liquid Blueprint CSS -->
        <link rel="stylesheet" href="{$smarty.session.style_prefix}blueprint/reset.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="{$smarty.session.style_prefix}blueprint/liquid.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="{$smarty.session.style_prefix}blueprint/typography.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="{$smarty.session.style_prefix}blueprint/fancy-type.css" type="text/css" media="screen, projection">
        <!--[if IE]><link rel="stylesheet" href="{$smarty.session.style_prefix}blueprint/lib/ie.css" type="text/css" media="screen, projection"><![endif]-->


        <title>{$title}</title>
        {literal}
            <style type="text/css">
                input[type=text], input.text {margin:0;padding:0;}
                table { border:solid; border-color:#222; }
                th,td {border:solid; border-color:#232; border-width:1px;text-align: center; vertical-align:middle;padding: 0;width:1.7em;}
                hr {width:1.5em;padding:0px;margin:0px;}
                p.breakhere {page-break-before: always}
                td#grey{ background:lightgrey;}
                td#green{ background:lightgreen;}
                td#red{ background:red;}
                td#white{ background:white;}
                td#yellow{ background:yellow;}
                td#blue{ background:lightblue;}
                td.green{ background:lightgreen;}
                td.grey{ background:lightgrey;}
                td.red{ background:red;}
                td.white{ background:white;}
                td.yellow{ background:yellow;}
                td.blue{ background:lightblue;}
                td.marked{border-left-width:3px; border-right-width:3px;}
                td.bigfont{font-size : 1.8em;font-weight : bolder;}
                td.demifont{font-size : 1.2em;font-weight : bolder;}
                td.semifont{font-size : 1em;font-weight : bolder;}
            </style>
        {/literal}

    </head>
    <body >
        <div class="container">

            <div class="block">
                <div class="column span-24 last" align="center">

                    {foreach from=$studentspinakas item='studentdata' name='for'}
                        <h3 align="center">{$studentdata.studata}</h3>	

                        <table  align="center" border="1" cellpadding="0" cellspacing="0"  frame="box">
                            <tbody>
                                <tr>
                                    <th >ΤΕΤ</th>
                                    <th align="center" colspan="1">ΜΗΝ</th>
                                    {for $x = 1 to 31 step 1}
                                    <th align='center'><b>{$x}</b></th>
                                    {/for}
                                </tr>
                                {assign var='mymonths' value=','|explode:"9,10,11"}

                                {foreach from=$mymonths item='month' name='for1'}
                                    <tr>
                                        {if $smarty.foreach.for1.index == 0}
                                            <th rowspan=3 >Α΄</th>
                                        {/if}
                                        <th align='center' rowspan='1' ><b>{$month}ος</b></th>
                                        {for $i = 1 to 31 step 1}
                                {if $i mod 5 == 0}{assign var='background' value='lightgrey'}{else}{assign var='background' value='white'}{/if}
                                {if isset($studentdata.detaildata[$month][$i][0])}
                                    <td align='center' rowspan='1' class='{$studentdata.detaildata[$month][$i][2]}' ><b>{$studentdata.detaildata[$month][$i][0]}</b></td>       
                                {else}
                                    <td align='center' rowspan='1' class='{$background}'>&nbsp;</td>
                                {/if}
                                {/for}
                            </tr>
                        {/foreach}
                        {assign var='mymonths' value=','|explode:"12,1,2"}

                        {foreach from=$mymonths item='month' name='for2'}
                            <tr>
                                {if $smarty.foreach.for2.index == 0}
                                    <th rowspan=3 >Β΄</th>
                                {/if}
                                <th align='center' rowspan='1' ><b>{$month}ος</b></th>
                                {for $i = 1 to 31 step 1}
                        {if $i mod 5 == 0}{assign var='background' value='lightgrey'}{else}{assign var='background' value='white'}{/if}
                        {if isset($studentdata.detaildata[$month][$i][0])}
                            <td align='center' rowspan='1' class='{$studentdata.detaildata[$month][$i][2]}' ><b>{$studentdata.detaildata[$month][$i][0]}</b></td>       
                        {else}
                            <td align='center' rowspan='1' class='{$background}'>&nbsp;</td>
                        {/if}
                        {/for}
                    </tr>
                {/foreach}

                {assign var='mymonths' value=','|explode:"3,4,5"}

                {foreach from=$mymonths item='month' name='for3'}
                    <tr>
                        {if $smarty.foreach.for3.index == 0}
                            <th rowspan=3 >Γ΄</th>
                        {/if}
                        <th align='center' rowspan='1' ><b>{$month}ος</b></th>
                        {for $i = 1 to 31 step 1}
                {if $i mod 5 == 0}{assign var='background' value='lightgrey'}{else}{assign var='background' value='white'}{/if}
                {if isset($studentdata.detaildata[$month][$i][0])}
                    <td align='center' rowspan='1' class='{$studentdata.detaildata[$month][$i][2]}' ><b>{$studentdata.detaildata[$month][$i][0]}</b></td>       
                {else}
                    <td align='center' rowspan='1' class='{$background}'>&nbsp;</td>
                {/if}
                {/for}
            </tr>
        {/foreach}

    </tbody>
</table>
<br>

{$studentdata.totaldata}

{if not $smarty.foreach.for.last}<p class="breakhere"/>{/if}


{/foreach}
</div>
</div>
</div>
</body>
</html>
