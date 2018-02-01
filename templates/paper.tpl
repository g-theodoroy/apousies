<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>{$title}</title>
        <style type="text/css">
            td
            {
		font-size : 1em;
		padding-left: 5px;
            }
            th
            {
		font-size : 0.9em ;
            }
            .wide
            {
                width:25%;
            }
            .narrow
            {
                width:4%;
            }
            P.breakhere
            {
                page-break-before: always
            }

        </style>

    </head>
    <body>
        {assign var=totalwidth value=210-2*15}
        {assign var=totalheight value=297-2*15}
        {foreach from=$mypd item=foo}

            <div style="margin : 0 auto 0 auto; width :{$totalwidth}mm; height:{$totalheight}mm; ">

                <div style="width :50%; height:15mm;float: left ; ">ΣΧΟΛΙΚΗ ΜΟΝΑΔΑ: {if isset($foo.txtdata.sch_name)}{$foo.txtdata.sch_name}{/if}</div>
                <div style="width :50%; height:15mm;float: right ; text-align: right;">ΣΧΟΛΙΚΟ ΕΤΟΣ: {if isset($foo.txtdata.sch_year)}{$foo.txtdata.sch_year}{/if}</div>

                <div style="width :100%; height:15mm;clear:both;text-align:center;"><h2>ΔΕΛΤΙΟ ΕΠΙΚΟΙΝΩΝΙΑΣ  ΣΧΟΛΕΙΟΥ - ΓΟΝΕΩΝ</h2></div>

                <div style="width :40%; height:6mm; float: left ;padding-left:10%;" ><u>ΜΑΘΗΤΗΣ/ΤΡΙΑ</u></div>
                <div style="width :40%; height:6mm; float: right ;padding-left:10%;"><u>ΠΑΡΑΛΗΠΤΗΣ</u></div>

                <div style="width :15%; height:35mm;float: left ;text-align: right;text-indent:0mm;" >
                    ΕΠΩΝΥΜΟ:<br>
                    ΟΝΟΜΑ:<br>
                    ΤΑΞΗ:<br>
                    ΤΜΗΜΑ:
                </div>
                <div style="width :35%;height:35mm; float: left ;text-indent:0mm;" >
                    {$foo.studentsdata[0]}<br>
                    {$foo.studentsdata[1]}<br>
                    {if isset($foo.txtdata.sch_class)}{$foo.txtdata.sch_class}{/if}<br>
                    {if isset($foo.txtdata.sch_tmima)}{$foo.txtdata.sch_tmima}{/if}
                </div>
                <div style="width :45%;height:35mm; float: right ;padding-left:5%;">
                    {$foo.studentsdata[4]} {$foo.studentsdata[5]}<br>
                    {$foo.studentsdata[6]}<br>
                    {$foo.studentsdata[7]}<br>
                    {$foo.studentsdata[8]}
                </div>

                <div style="width :100%; clear:both;">
                    1. Σας πληροφορούμε ότι {$foo.keimeno0} {$foo.studentsdata[0]} {$foo.studentsdata[1]} της {if isset($foo.txtdata.sch_class)}{$foo.txtdata.sch_class}{/if} τάξης του {if isset($foo.txtdata.sch_tmima)}{$foo.txtdata.sch_tmima}{/if} τμήματος του σχολείου μας από την έναρξη της σχολικής χρονιάς μέχρι τις {$foo.mydate},  σημείωσε &nbsp;&nbsp;&nbsp;<b>{$foo.totalap}</b>&nbsp;&nbsp;&nbsp; απουσίες.
                </div>
                <div style="width :100%;">&nbsp;</div>
{if $paperdetails}
                <div style="width :100%;">Αναλυτικά:</div>
                <div style="width :100%;">&nbsp;</div>
                <div style="width :100%;">
                    <table border="1" cellpadding="0" cellspacing="0" width="100%" style="outline : solid;">
                        <tbody>
                            <tr>
                                <th colspan="2" rowspan="2" style="width:33%">ΔΙΚΑΙΟΛΟΓΗΜΕΝΕΣ<br>ΑΠΟΥΣΙΕΣ</th>
                                <th colspan="4" style="">ΑΔΙΚΑΙΟΛΟΓΗΤΕΣ ΑΠΟΥΣΙΕΣ</th>
                                <th colspan="3" rowspan="2">ΑΠΟΥΣΙΕΣ<br>ΑΠΟ ΠΟΙΝΕΣ</th>
                            </tr>
                            <tr>
                                <th colspan="3">ΜΕΜΟΝΩΜΕΝΕΣ<br>ΑΠΟΥΣΙΕΣ</th>
                                <th >ΑΠΟΥΣ<br>ΟΛΟΚΛ<br>ΜΕΡΑΣ</th>
                            </tr>
                            <tr>
                                <td  rowspan=3 colspan=2 style="padding:0 0 0 0;" valign="top">

                                    <table cellpadding="0" cellspacing="0" width="100%" style="outline : none;">
                                        {assign var='letters' value=','|explode:"α,β,γ,δ,ε,στ,η,θ"}
                                        {for $x = 0 to $dik_count-1}
                                        {if $x==0}{assign var=style value='border-bottom:1px solid;'}{/if}
                                        {if $x<$dik_count-1}{assign var=style value='border-top:1px solid;border-bottom:1px solid;'}{/if}
                                        {if $x==$dik_count-1}{assign var=style value='border-top:1px solid;'}{/if}
                                        {if $paper_dik_define[$x]['perigrafi'] !=  ''}
                                        <tr >
                                            <td class="narrow" style="{$style}border-right:1px solid;" >{$letters[$x]} </td><td class="wide" style="{$style}border-left:1px solid;border-right:1px solid;" >{$paper_dik_define[$x]['perigrafi']}</td><th class="narrow" style="{$style}border-left:1px solid;" >{$foo[$dik_kod[$x]]}</th>
                                        </tr >
                                        {/if}
                                        {/for}
                                    </table>



                                </td>
                                <td class="narrow">α</td>
                                <td class="wide">πρωινή<br>απουσία</td>
                                <th class="narrow">{$foo.totalfh}</th>
                                <th rowspan="4" align="center">{$foo.totalfulldayadik}</th>
                               <td class="narrow">α</td>
                                <td class="wide">από ωριαίες<br>αποβολές</td>
                                <th class="narrow">{$foo.totaloa}</th>
                            </tr>
                            <tr>
                                <td class="narrow">β</td>
                                <td>ενδιάμεσες<br>απουσίες</td>
                                <th>{$foo.totalmh}</th>
                               <td class="narrow">β</td>
                                 <td>από ημερήσιες<br>αποβολές</td>
                                <th>{$foo.totalda}</th>
                            </tr>
                            <tr>
                               <td class="narrow">γ</td>
                                 <td>απουσία τελευ-<br>ταίας ώρας</td>
                                <th>{$foo.totallh}</th>
                                <th rowspan="3" colspan="3">ΣΥΝΟΛΟ : {$foo.totaloada}</th>
                            </tr>
                            <tr>
                                <th colspan="2" rowspan="2">ΣΥΝΟΛΟ :{$foo.totaldik}</th>
                                <th colspan="2" >ΣΥΝ ΜΕΜΟΝ</tH>
                                <th>{$foo.totalmemon}</th>
                            </tr>
                            <tr>
                                <th colspan="2" >ΣΥΝΟΛΟ ΑΔΙΚ/ΤΩΝ</th><th colspan="2" >{$foo.totaladik}</th>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div style="width :100%;">&nbsp;</div>
{/if}
                <div style="width :100%; ">
                    2. Στο χρονικό διάστημα απο 1/{$foo.lastmonthindex9} έως {$foo.mydate} <u>{$foo.aformi}</u> για πειθαρχικό έλεγχο.</td>
                </div>
                <div style="width :100%;height:6mm;">&nbsp;</div>
                <div style="width :100%;">
                    3. Παρακαλούμε να προσέλθετε στο σχολείο κατά το διάστημα από 1 έως 10 του μήνα {$foo.lastmonth} για ενημέρωσή σας σχετικά με τη φοίτηση {$foo.keimeno1} προσκομίζοντας την παρούσα επιστολή.</td>
                </div>
                <div style="width :100%;height:6mm;">&nbsp;</div>
                <div style="width :15%; float: left ;text-align: right;text-indent:0mm;" >
                    ΑΡ.ΠΡΩΤ:<br>
                    ΗΜ/ΝΙΑ:
                </div>
                <div style="width :35%; float: left ; text-indent:0mm;" >
                    {$foo.protok}<br>
                    {$foo.nowdate}
                </div>
                <div style="width :45%; float: right ;padding-left:5%;">
                    {if isset($foo.txtdata.teach_arthro)}{$foo.txtdata.teach_arthro}{/if} ΚΑΘΗΓΗ{if isset($foo.txtdata.teach_last)}{$foo.txtdata.teach_last}{/if}<br>
                    &nbsp;<br>
                    &nbsp;<br>
                    &nbsp;<br>
                    {if isset($foo.txtdata.teach_name)}{$foo.txtdata.teach_name}{/if}
                </div>
                <div style="width :100%;clear:both;">
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  {if !$paperdetails}
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  {/if}
                </div>

                <div style="width :100%; height:20mm;padding-top:4mm;border-style:solid;">
                    ΠΑΡΕΛΗΦΘΗ ΑΠΟ ΤΟΝ ΚΗΔΕΜΟΝΑ<br><br>Ονοματεπώνυμο: ..............................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ημερομηνία : .../.../......
                </div>
            </div>
            <p class='breakhere'>

            {/foreach}
    </body>
</html>
