<body {$body_attributes|default:''} >
    <div class="container">{*<!--BODY MAIN CONTAINER-->*}
        <div class="block" style="min-height : 800px;">{*<!--BODY MAIN BLOCK-->*}
            <div class="block" >{*<!--BODY HEADER TOP BAR-->*}
                <div class="column span-24">
                    <div>
                        <div align="left" class="column span-5 " ><IMG src='{$smarty.session.images_prefix}evolution.gif' height="70" align="left" class='nomargin' ></div> 
                        <div class="column span-14" align="center" >
                            <!--
                            <div style=' float: left'><span style='color:red; '><b>ΝΕΑ VERSION</b></span></div>
                            <div style=' float: right'><span style='color:red;'><b>ΝΕΑ VERSION</b></span></div>
                            -->
                            <h1>{$h1_title}</h1>
                        </div> 
                        <div class="column span-5 last"  align="right" >
            {if isset($smarty.session.userName)}{if isset($smarty.session.userName)}<span style='color:white;background-color:#222;' >&nbsp;{$smarty.session.userName}&nbsp;</span>{/if}{if isset($smarty.session.tmima)}<span style='color:white;background-color:#222;' >:&nbsp;{$smarty.session.tmima}&nbsp;</span>{/if}| <a href="useraccount.php" tabindex=-1>Λογαριασμός</a> | <a href="logout.php" tabindex=-1> Έξοδος</a>{else}<a href="demologin.php" tabindex=-1>Δοκιμή (demo)</a> | <a href="login.php" tabindex=-1>Είσοδος</a>{if $smarty.session.allowregister} | <a href="register.php" tabindex=-1>Εγγραφή</a>{/if}{/if}
            
        </div> 
        {if isset($smarty.session.userName)}
            <div class="column span-14 last"  align="center" style="font-size:xx-small;font-weight:bold;">
                   {if isset($smarty.session.sel_tmima)}
                   		{foreach from=$smarty.session.sel_tmima item='tmima'}
                    		{if isset($smarty.session.tmima) && $tmima == $smarty.session.tmima}
                        		<span style='color:white;background-color:#222;' >&nbsp;{$tmima}&nbsp;</span>
                    		{else}
                        		<a href='set_tmima.php?t={$tmima}'>&nbsp;{$tmima}&nbsp;</a>
                    		{/if}
		                {/foreach}
		           {/if}
            </div> 
        {/if}
    </div>
</div>
</div>{*<!--END OF BODY HEADER TOP BAR-->*}

<hr>

<div class="block">{*<!--CENTRAL CONTAINER FOR CONTENT-->*}
    <div class="column span-3 " id="menucontainer" align="center" style="min-height:25em;">{*<!--LEFT MENU-->*}
        <script language="JavaScript">
        		{if isset($smarty.session.parentUser) && $smarty.session.parentUser == false}
                	new menu (MENU_ITEMS_SUB, MENU_TPL);
                {else}
                	new menu (MENU_ITEMS_PARENT, MENU_TPL);
                {/if}
        </script>
    </div>{*<!--END OF LEFT MENU-->*}

    <div class="column span-21 last" >{*<!--CONTAINER FOR CONTENT-->*}
        <div class="block">
            <div class="column span-21 last" align="center" >
                {*<!--CONTENT-->*}
