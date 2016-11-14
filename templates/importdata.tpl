{include file='header.tpl'}
{include file='body_header.tpl'}

{if isset($errorText) and $errorText !== ''}
    <h3 align="center">Πρόβλημα στην καταχώρηση δεδομένων!<br><br>{$errorText}</h3>
    <h4  align="center">
        <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
    </h4>

{else}

    {if $save}

        {if isset($errorchk) && $errorchk == 0}
            <h3 align='center'>Δεδομένα έτοιμα για καταχώρηση</h3>
            <h4 align='center'>Επιλέξτε τι θα καταχωρηθεί</h4>
            <form action="" name="frm1" method="post">
                <table>
                    <tr><td><h4>εγγραφές τμημάτων:</h4></td><td><h3 style='text-align:center;'>{if isset($numtmimata)}{$numtmimata}{/if}</h3></td><td><INPUT type="checkbox" name="tmichk" {if isset($tmichecked)}{$tmichecked}{/if} value="1"></td></tr>
                    <tr><td><h4>εγγραφές μαθητών:</h4></td><td><h3 style='text-align:center;'>{if isset($numstudents)}{$numstudents}{/if}</h3></td><td><INPUT type="checkbox" name="stuchk" {if isset($stuchecked)}{$stuchecked}{/if} value="1"></td></tr>
                    <tr><td><h4>εγγραφές απουσιών:</h4></td><td><h3 style='text-align:center;'>{if isset($numapousies)}{$numapousies}{/if}</h3></td><td><INPUT type="checkbox" name="apouchk" {if isset($apouchecked)}{$apouchecked}{/if} value="1"></td></tr>
                    <tr><td><h4>εγγραφές ιστορικού:</h4></td><td><h3 style='text-align:center;'>{if isset($numhistory)}{$numhistory}{/if}</h3></td><td><INPUT type="checkbox" name="histchk" {if isset($histchecked)}{$histchecked}{/if} value="1"></td></tr>
                    <tr><td><h4>εγγραφές προϋπαρχουσών απουσιών:</h4></td><td><h3 style='text-align:center;'>{if isset($numapousies_pre)}{$numapousies_pre}{/if}</h3></td><td><INPUT type="checkbox" name="apou_prechk" {if isset($apou_prechecked)}{$apou_prechecked}{/if} value="1"></td></tr>
                    <tr><td><h4>εγγραφές ρυθμίσεων:</h4></td><td><h3 style='text-align:center;'>{if isset($numparameters)}{$numparameters}{/if}</h3></td><td><INPUT type="checkbox" name="paramchk" {if isset($paramchecked)}{$paramchecked}{/if} value="1"></td></tr>
                    <tr><td><h4>αιτήσεις δικαιολογητικών:</h4></td><td><h3 style='text-align:center;'>{if isset($numdikaiologisi)}{$numdikaiologisi}{/if}</h3></td><td><INPUT type="checkbox" name="dikchk" {if isset($dikchecked)}{$dikchecked}{/if} value="1"></td></tr>
                    <tr><td><h4>μαθητές και τμήματα:</h4></td><td><h3 style='text-align:center;'>{if isset($numstudentstmimata)}{$numstudentstmimata}{/if}</h3></td><td><INPUT type="checkbox" name="stutmichk" {if isset($stutmichecked)}{$stutmichecked}{/if} value="1"></td></tr>
                </table>
                <h4  align="center">
                    <button type="submit" name="final" value="final"  >ΚΑΤΑΧΩΡΗΣΗ</button>&nbsp;
                    <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
                </h4>
            </form>
        {elseif isset($errorchk) && $errorchk == 1}
            <h3 align="center">Πρόβλημα στο ανέβασμα (upload) του αρχείου δεδομένων.</h3>
            <h4  align="center">
                <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
            </h4>
        {else}
            <h3 align="center">Πρόβλημα στην καταχώρηση δεδομένων!<br><br>{$errorText}</h3>
            <h4  align="center">
                <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
            </h4>
        {/if}

    {elseif $final}

        {if not isset($errorchk)}
            <h3 align='center'>Καταχωρήθηκαν με επιτυχία</h3>
            <table>
                <tr><td><h4>εγγραφές τμημάτων:</h4></td><td><h3 style='text-align:center;'>{if isset($numtmimata)}{$numtmimata}{/if}</h3></td></tr>
                <tr><td><h4>εγγραφές μαθητών:</h4></td><td><h3 style='text-align:center;'>{if isset($numstudents)}{$numstudents}{/if}</h3></td></tr>
                <tr><td><h4>εγγραφές απουσιών:</h4></td><td><h3 style='text-align:center;'>{if isset($numapousies)}{$numapousies}{/if}</h3></td></tr>
                <tr><td><h4>εγγραφές ιστορικού:</h4></td><td><h3 style='text-align:center;'>{if isset($numhistory)}{$numhistory}{/if}</h3></td></tr>
                <tr><td><h4>εγγραφές προϋπαρχουσών απουσιών:</h4></td><td><h3 style='text-align:center;'>{if isset($numapousies_pre)}{$numapousies_pre}{/if}</h3></td></tr>
                <tr><td><h4>εγγραφές ρυθμίσεων:</h4></td><td><h3 style='text-align:center;'>{if isset($numparameters)}{$numparameters}{/if}</h3></td></tr>
                <tr><td><h4>αιτήσεις δικαιολογητικών:</h4></td><td><h3 style='text-align:center;'>{if isset($numdikaiologisi)}{$numdikaiologisi}{/if}</h3></td></tr>
                <tr><td><h4>μαθητές και τμήματα:</h4></td><td><h3 style='text-align:center;'>{if isset($numstudentstmimata)}{$numstudentstmimata}{/if}</h3></td></tr>
            </table>
            <h4  align="center">
                <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
            </h4>
        {else}
            <h3 align="center">Πρόβλημα στην καταχώρηση δεδομένων!<br><br>Δεν έγινε καμιά μεταβολή.</h3>
            {/if}

    {else}

        <H4 align="center">Επιλέξτε το αρχείο με τα δεδομένα για καταχώρηση</H4>
        <form action="" name="frm" method="post" enctype="multipart/form-data">
            <h4  align="center">
                <input  size="30" type="file" name="file" id="file" />
            </h4>

            <h4  align="center">
                <button type="submit" name="save" value="insert"  onclick="return check_submit();">ΕΙΣΑΓΩΓΗ</button>&nbsp;
                <button type="reset" name="clear" value="clear" >ΚΑΘΑΡΙΣΜΑ</button>&nbsp;
                <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
            </h4>
        </form>

    {/if}
{/if}


{include file='footer.tpl'}
