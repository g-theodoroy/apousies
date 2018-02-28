<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>{$title}</title>
        <style type="text/css">
            table.out{
            vertical-align:top;
            }

        </style>

    </head>
    <body>


            <div style="margin : 0 auto 0 auto; width : 200mm; height:277mm; border:none">

                <table class='out' border=0 width="100%">
                    <tbody>
                        <tr>
                            <td colspan=2 >ΣΧΟΛΙΚΗ ΜΟΝΑΔΑ: {$sch_name}</td>
                            <td  align="right"   >ΣΧΟΛΙΚΟ ΕΤΟΣ: {$sch_year}</td>
                        </tr>
                          <tr><td colspan=3 >&nbsp;</td></tr>
                        <tr>
                            <td colspan=3 align="center" ><h3>ΑΝΑΦΟΡΑ ΑΠΟΣΤΟΛΗΣ SMS ΣΕ ΚΗΔΕΜΟΝΕΣ</h3></td>
                        </tr>
                          <tr><td>Τμήμα: {$smarty.session.tmima}</td><td colspan=2 style="text-align:right" >{$smarty.now|date_format:"%d/%m/%Y"}</td></tr>
                          <tr><td colspan=3 >&nbsp;</td></tr>
                          {if $sms_good and $sms_good|@count >1}
                        <tr>
                          <td colspan=3 ><span style="color:green">Επιτυχημένη</span> αποστολή sms σε κηδεμόνες:</td></tr>
                        <tr>
                        <tr><td colspan=3 >
                        <table cellpadding=0 cellspacing=0 border=1 width="100%">
                        {assign var="aa" value=0}
                        {foreach from=$sms_good item=smsone }
                        <tr>
                          <td style="text-align:center">{if $aa==0}Α/Α{else}{$aa}{/if}</td>
                        {foreach from=$smsone item=smsfield }
                          <td>{$smsfield}</td>
                        {/foreach}
                        <tr>
                        {assign var="aa" value=$aa+1}
                        {/foreach}
                        </tr>
                        </table >
                        </td></tr>
                        {/if}
                        <tr><td colspan=3 >&nbsp;</td></tr>
                        {if $sms_bad and $sms_bad|@count >1}
                        <tr>
                          <td colspan=3 ><span style="color:red">Αποτυχημένη</span> αποστολή sms σε κηδεμόνες:</td></tr>
                        <tr>
                        <tr><td colspan=3 >
                        <table cellpadding=0 cellspacing=0 border=1  width="100%">
                        {assign var="aa" value=0}
                        {foreach from=$sms_bad item=smsone }
                        <tr>
                          <td style="text-align:center">{if $aa==0}Α/Α{else}{$aa}{/if}</td>
                        {foreach from=$smsone item=smsfield }
                          <td>{$smsfield}</td>
                        {/foreach}
                        <tr>
                        {assign var="aa" value=$aa+1}
                        {/foreach}
                        </tr>
                         </table >
                        </td></tr>
                        {/if}
                        <tr><td colspan=3 >&nbsp;</td></tr>
                        <tr><td colspan=3 >Πάροχος: {$paroxos}</td></tr>
                        {if $balance}
                        <tr><td colspan=3 >Υπόλοιπo λογαριασμού: {$balance}</td></tr>
                        {/if}
                    </tbody>
                </table>

            </div>
    </body>
</html>
