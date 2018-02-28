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
                            <td colspan=3 align="center" ><h3>ΑΝΑΦΟΡΑ ΑΠΟΣΤΟΛΗΣ EMAIL ΣΕ ΚΗΔΕΜΟΝΕΣ</h3></td>
                        </tr>
                          <tr><td>Τμήμα: {$smarty.session.tmima}</td><td colspan=2 style="text-align:right" >{$smarty.now|date_format:"%d/%m/%Y"}</td></tr>
                          <tr><td colspan=3 >&nbsp;</td></tr>
                          
                        {if $mail_good}
                        <tr>
                          <td colspan=3 ><span style="color:green">Επιτυχημένη</span> αποστολή email σε κηδεμόνες:</td></tr>
                        <tr>
                        <tr><td colspan=3 >
                        <table cellpadding=0 cellspacing=0 border=1 width="100%">
                        <tr>
                          <td style="text-align:center"><b>Α/Α</b></td>
                          <td style="text-align:center"><b>Ημν/ια Ώρα</b></td>
                         <td><b>Μαθητής/τρια</b></td>
                          <td><b>Κηδεμόνας</b></td>
                           <td style="text-align:center"><b>Απουσιες</b></td>
                        </tr>
                        {assign var="aa" value=1}
                        {foreach from=$mail_good item=mg }
                        <tr>
                          <td style="text-align:center">{$aa}</td>
                          <td style="text-align:center">{$mg[0]}</td>
                          <td>{$mg[2]}</td>
                          <td>{$mg[1]}</td>
                          <td style="text-align:center">{$mg[3]}</td>
                        <tr>
                        {assign var="aa" value=$aa+1}
                        {/foreach}
                        </tr>
                        </table >
                        </td></tr>
                        {/if}
                        <tr><td colspan=3 >&nbsp;</td></tr>
                        {if $mail_bad}
                        <tr>
                          <td colspan=3 ><span style="color:red">Αποτυχημένη</span> αποστολή email σε κηδεμόνες:</td></tr>
                        <tr>
                        <tr><td colspan=3 >
                        <table cellpadding=0 cellspacing=0 border=1  width="100%">
                        <tr>
                          <td style="text-align:center"><b>Α/Α</b></td>
                          <td style="text-align:center"><b>Ημν/ια Ώρα</b></td>
                          <td><b>Μαθητής/τρια</b></td>
                          <td><b>Κηδεμόνας</b></td>
                          <td style="text-align:center"><b>Απουσιες</b></td>
                        </tr>
                        {assign var="aa" value=1}
                        {foreach from=$mail_good item=mg }
                        <tr>
                          <td style="text-align:center">{$aa}</td>
                          <td style="text-align:center">{$mg[0]}</td>
                          <td>{$mg[2]}</td>
                          <td>{$mg[1]}</td>
                          <td style="text-align:center">{$mg[3]}</td>
                        <tr>
                        {assign var="aa" value=$aa+1}
                        {/foreach}
                        </tr>
                        </table>
                        </td></tr>
                        {/if}
                        
                    </tbody>
                </table>

            </div>
    </body>
</html>
