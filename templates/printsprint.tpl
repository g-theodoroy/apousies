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
        
        {$extra_style}
        
        {$extra_javascript}

    </head>
    <body >
        <div class="container">
            <!-- HEADER -->
            <div class="block">
                <div class="column span-24 last" align="center" >
                    <h4 align='center'>Τμήμα {$smarty.session.tmima}</h4> 


             <table cellspacing=0 cellpadding=0 align='center' class="nomargin" >
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
 
 
               </div>
            </div>
        </div>
    </body>
</html>
