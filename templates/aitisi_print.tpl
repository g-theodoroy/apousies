<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>{$title}</title>
        <style type="text/css">
            table.out{
                vertical-align:top;
            }
            td
            {
                font-size : 1em;
                padding-left: 5px;
                vertical-align:top;
            }
            th
            {
                font-size : 1em ;
            }
            .nomargin
            {
                margin : 0 0 0 0 ;                
            }
            p
            {
                margin : 5px 0 5px 0;                
            }
         </style>

    </head>
    <body>
        {assign var=totalwidth value=210-2*15} 
        {assign var=totalheight value=297-2*15} 

        <div style="margin : 0 auto 0 auto; width :{$totalwidth}mm; height:{$totalheight}mm; ">
            <table class='out' border="0" cellpadding=0 cellspacing=0 width="100%">
                <tbody>
                    <tr>
                        <td colspan=1 align="center"  ><h2 >&nbsp;</h2></td>
                        <td colspan=2 align="center"  ><h2 >ΑΙΤΗΣΗ</h2></td>
                        <td width='20%' align="left"  ><b>Αρ.Πρ:</b> {$protok}<br><b>Ημνια:</b> {$protok_date}</td>
                    </tr>
                    <tr><td colspan=4 align="center"  >&nbsp;</td></tr>
                    <tr>
                        <td colspan=2  >&nbsp;</td>
                        <td  colspan=2 align="left"   ><h4 class='nomargin' >ΠΡΟΣ: {if isset($txtdata.sch_name)}{$txtdata.sch_name}{/if}</h4></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="left-padding :10mm;"><h4 class='nomargin' >ΣΤΟΙΧΕΙΑ ΑΙΤΟΥΝΤΟΣ</h4></td>
                        <td  colspan=2 >&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='right' width='15%'>
                            ΕΠΩΝΥΜΟ:
                        </td>
                        <td width='35%' >
                            {$studentsdata.ep_ki}
                         </td >
                        <td  colspan=2 rowspan=2 >
                            Παρακαλώ να δικαιολογήσετε την απουσία που έκανε {$student_arthro_2}
                        </td>
                    </tr>
                    <tr>
                        <td align='right' width='15%'>
                            ΟΝΟΜΑ:
                        </td>
                        <td width='35%' >
                            {$studentsdata.on_ki}
                        </td >
                    </tr>
                    <tr>
                        <td align='right' width='15%'>
                            ΣΧΕΣΗ ΜΕ
                        </td>
                        <td width='35%' >
                            &nbsp;
                        </td >
                        <td  colspan=2 style='text-align:center' >
                            <b>{$studentsdata.ep} {$studentsdata.on}</b>
                        </td>
                    </tr>
                    <tr>
                        <td align='right' width='15%'>
                            {$student_arthro_0}:
                        </td>
                        <td width='35%' >
                            ΚΗΔΕΜΟΝΑΣ
                        </td >
                        <td  colspan=2 >
                            του τμήματος <b>{$smarty.session.tmima}</b> η οποία έγινε
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 >&nbsp;</td>
                        <td  colspan=2 style='text-align:center' >
                            από <b>{$firstday}</b> μέχρι <b>{$lastday}</b>
                        </td>

                    </tr>
                    <tr>
                        <td colspan=2 style="left-padding :10mm;"><h4 class='nomargin' >ΣΤΟΙΧΕΙΑ {$student_arthro_1}</h4></td>
                        <td  colspan=2 style='text-align:center' >
                            Ημέρες απουσίας: <b>{$countdays}</b>
                        </td>
                    </tr>
                    <tr>
                        <td align='right' width='15%'>
                            ΕΠΩΝΥΜΟ:<br>
                            ΟΝΟΜΑ:<br>
                            ΤΑΞΗ:<br>
                            ΤΜΗΜΑ:
                        </td>
                        <td width='35%' >
                            {$studentsdata.ep}<br>
                            {$studentsdata.on}<br>
                            {if isset($txtdata.sch_class)}{$txtdata.sch_class}{/if}<br>
                            {$smarty.session.tmima}
                        </td >
                        <td  colspan=2 >
                            και οφείλεται σε:
                            <ul class='nomargin' style="list-style-type: none;" >
                                <li>{$logos}</li>
                            </ul>


                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 >&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan=2 ><b>Θέμα:</b> Δικαιολόγηση απουσιών</td>
                        <td colspan=2 rowspan=4 style='text-align:center' ><h4 class="nomargin">Ο/Η ΑΙΤΩΝ/ΟΥΣΑ</h4></td>
                    </tr>
                    <tr>
                        <td colspan=2 ><b>Ημνια:</b> {$smarty.now|date_format:"%e/%-m/%Y"}</td>
                    </tr>
                    <tr>
                        <td colspan=2 >&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan=2 >
                            <ul class='nomargin' style="list-style-type: none;">
                                <li>{$bebaiosi}</li>
                            </ul>
                        </td>
                    </tr>
            </table>

        </div>

    </body>
</html>
