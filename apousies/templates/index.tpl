{include file='header.tpl'}
{include file='body_header.tpl'}

<div>
    <div class="column span-12 prepend-3" align="left" >
        {if isset($smarty.session.userName)}
            <h2>Καλώς ήλθατε <b>{$smarty.session.userName}</b></h2>
            <h4>Τελευταία σύνδεση: {$smarty.session.lastlogin}</h4>
            {if isset($smarty.session.tmima)}
                <h2>Επιλεγμένο τμήμα: <b>{$smarty.session.tmima}</b></h2>
                {if $parameters_not_set}
                    <h2>Πρέπει να κάνετε <a href="parameters.php">ρυθμίσεις</a></h2>
                {/if}
                {if $studentscount > 0}
                    <table>
                        <tr><td><h4 class="nomargin">μαθητές</h4></td><td><h4 class="nomargin r"><b>{$studentscount}</b></h4></td></tr>
                    {if $dayscount > 0}<tr><td><h4 class="nomargin">ημέρες</h4></td><td><h4 class="nomargin r"><b>{$dayscount}</b></h4></td></tr>{/if}
                {if $sumapousies > 0}<tr><td><h4  class="nomargin">απουσίες</h4></td><td><h4 class="nomargin r"><b>{$sumapousies}</b></h4></td></tr>{/if}
            {if $sumpapers > 0}<tr><td><h4  class="nomargin">ειδοποιητήρια</h4></td><td><h4 class="nomargin r"><b>{$sumpapers}</b></h4></td></tr>{/if}
        </table>
    {/if}
    <h4>
        {if $newpapers}
            <a href="javascript:newpapers();">Νέα ειδοποιητήρια!</a><br>
        {/if}

        {if $almost_orio_adik}
            <a href="javascript:almost_orio_adik();">Μαθητές με αδικαιολόγητες στο όριο!</a><br>
        {/if}

        {if $over_orio_adik}
            <a href="javascript:over_orio_adik();">Μαθητές με αδικαιολόγητες εκτός ορίου!</a><br>
        {/if}

        {if $almost_orio_total}
            <a href="javascript:almost_orio_total();">Μαθητές στο όριο απουσιών!</a><br>
        {/if}

        {if $over_orio_total}
            <a href="javascript:over_orio_total();">Μαθητές εκτός ορίου απουσιών!</a><br>
        {/if}
    </h4>
       {if $check_many_apousies}
           <h4 >
            <a href="javascript:check_many_apousies();" style='color : red;' >Διπλές καταχωρήσεις απουσιών!</a>
            </h4>
        {/if}

{/if}
{else}
    <h2>Παρακολουθείστε τις απουσίες των μαθητών σας!</h2>
    <h2>Πλήρης αντιστοιχία με τα επίσημα βιβλία!</h2>
    <h2>Αυτόματα αθροίσματα, εκτυπώσεις, ...!</h2>
{/if}
</div>
<div class="column span-8  last" align="center" >
    <div class="box">
        {if isset($smarty.session.userName)}
            <IMG src="{$smarty.session.images_prefix}hapy.gif" height="200" align="center" border="0" >
        {else}
            <IMG src="{$smarty.session.images_prefix}xartia.gif" height="200" align="center" border="0" >
        {/if}
    </div>
</div>
</div>

<hr class="space">

{if not isset($smarty.session.userName)}
    <h3 ><b>{$smarty.session.usercount}</b> εγγεγραμμένοι χρήστες, <b>{$smarty.session.tmimacount}</b> τμήματα.</h3>
    <!-- <h4><a href="http://apousies.gr">Επιστροφή στη παλια version</a></h4> -->
    <h4 align="right"><a href="tour.php">Περισσότερα...&nbsp;&nbsp;</a><br><a href="useful/odigies_apousies.pdf">Οδηγίες Χρήσης</a></h4>  
{/if}

{include file='footer.tpl'}
