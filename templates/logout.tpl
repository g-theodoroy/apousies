{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="block">
    <div class="column span-15 prepend-4 last" align="center" >
        
        <h2 align="center">Τα δεδομένα σας έχουν αλλάξει.</h2>
        <h3 align="center">Συνίσταται να κρατήσετε back up των δεδομένων σας.</h3>

        <form action="" method="post" name="frm">
            <HR class="space">
            <h3>
                <BUTTON type="submit" name="submitBtn" value="no" >OXI ΔΕΝ ΘΕΛΩ ΤΩΡΑ</BUTTON>&nbsp;
                <button type="button" name="back" value="yes" onclick="window.location='exportdata.php'">BACK UP ΔΕΔΟΜΕΝΩΝ</button>
            </h3> 
        </form>
        
    </div>
</div>

{include file='footer.tpl'}
