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
            }
            th
            {
		font-size : 1em ;
            }
            .wide
            {
                width:60%;
            }
            .narrow
            {
                width:20%;
            }
            .inner{
                border-top:1px solid;
                border-right:1px solid;
                border-bottom:1px solid;
                border-left:1px solid;
            }
            .topleft{
                border-top:hidden;
                border-right:1px solid;
                border-bottom:1px solid;
                border-left:hidden;
            }
            .top{
                border-top:hidden;
                border-right:1px solid;
                border-bottom:1px solid;
                border-left:1px solid;
            }
            .topright{
                border-top:hidden;
                border-right:hidden;
                border-bottom:1px solid;
                border-left:1px solid;
            }
            .right{
                border-top:1px solid;
                border-right:hidden;
                border-bottom:1px solid;
                border-left:1px solid;
            }
            .bottomright{
                border-top:1px solid;
                border-right:hidden;
                border-bottom:hidden;
                border-left:1px solid;
            }
            .bottom{
                border-top:1px solid;
                border-right:1px solid;
                border-bottom:hidden;
                border-left:1px solid;
            }
            .bottomleft{
                border-top:1px solid;
                border-right:1px solid;
                border-bottom:hidden;
                border-left:hidden;
            }
            .left{
                border-top:1px solid;
                border-right:1px solid;
                border-bottom:1px solid;
                border-left:hidden;
            }

        </style>

    </head>
    <body>

        {foreach from=$mypd item=foo name='my'}

            <div style="margin : 0 auto 0 auto; width : 200mm; height:277mm; border:none">

                <table class='out' border=0 width="100%">
                    <tbody>
                        <tr>
                            <td colspan=2 >ΣΧΟΛΙΚΗ ΜΟΝΑΔΑ: {if isset($foo.txtdata.sch_name)}{$foo.txtdata.sch_name}{/if}</td>
                            <td  align="right"   >ΣΧΟΛΙΚΟ ΕΤΟΣ: {if isset($foo.txtdata.sch_year)}{$foo.txtdata.sch_year}{/if}</td>
                        </tr>
                          <tr><td colspan=3 >&nbsp;</td></tr>
                        <tr>
                            <td colspan=3 align="center" ><h3>ΔΕΛΤΙΟ ΕΠΙΚΟΙΝΩΝΙΑΣ  ΣΧΟΛΕΙΟΥ - ΓΟΝΕΩΝ</h3></td>
                        </tr>
                          <tr><td colspan=3 >&nbsp;</td></tr>
                        <tr>
                            <td colspan=2 style="left-padding :10mm;"><u>ΜΑΘΗΤΗΣ/ΤΡΙΑ</u></td>
                            <td  ><u>ΠΑΡΑΛΗΠΤΗΣ</u></td>
                        </tr>
                        <tr>
                            <td align='right' width='15%'>
                                ΕΠΩΝΥΜΟ:<br>
                                ΟΝΟΜΑ:<br>
                                ΤΑΞΗ:<br>
                                ΤΜΗΜΑ:
                            </td>
                            <td width='40%' >
                                {$foo.studentsdata[0]}<br>
                                {$foo.studentsdata[1]}<br>
                                {if isset($foo.txtdata.sch_class)}{$foo.txtdata.sch_class}{/if}<br>
                                {if isset($foo.txtdata.sch_tmima)}{$foo.txtdata.sch_tmima}{/if}

                            </td >
                            <td >
                                {$foo.studentsdata[4]} {$foo.studentsdata[5]}<br>
                                {$foo.studentsdata[6]}<br>
                                {$foo.studentsdata[7]}<br>
                                {$foo.studentsdata[8]}

                            </td>
                        </tr>
                          <tr><td colspan=3 >&nbsp;</td></tr>
                        <tr>
                            <td colspan=3 >
                                1. Σας πληροφορούμε ότι {$foo.keimeno0} {$foo.studentsdata[0]} {$foo.studentsdata[1]} της {if isset($foo.txtdata.sch_class)}{$foo.txtdata.sch_class}{/if} τάξης του {if isset($foo.txtdata.sch_tmima)}{$foo.txtdata.sch_tmima}{/if} τμήματος του σχολείου μας από την έναρξη της σχολικής χρονιάς μέχρι τις {$foo.mydate},  σημείωσε &nbsp;&nbsp;&nbsp;<b>{$foo.totalap}</b>&nbsp;&nbsp;&nbsp; απουσίες.
                         </td>
                         </tr>
{if $paperdetails}
                        <tr><td colspan=3 >&nbsp;</td></tr>
                        <tr><td colspan=3 >Αναλυτικά:</td></tr>
                        <tr><td colspan=3 >&nbsp;</td></tr>
                        </tr>
                    </tbody>
                </table>

                <table cellpadding=0 cellspacing=0 width="100%" style="outline : solid;">
                    <tbody>
                        <tr>
                        <td width='10%'/>
                        <td width='10%'/>
                        <td width='10%'/>
                        <td width='5%'/>
                        <td width='20%'/>
                        <td width='5%'/>
                        <td width='10%'/>
                        <td width='5%'/>
                        <td width='20%'/>
                        <td width='5%'/>
                        </tr>
                        <tr>
                            <th  class="inner" colspan=3 rowspan=2 >ΔΙΚΑΙΟΛΟΓΗΜΕΝΕΣ<br>ΑΠΟΥΣΙΕΣ</th>
                            <th  class="inner"  colspan=4 height='30px'>ΑΔΙΚΑΙΟΛΟΓΗΤΕΣ ΑΠΟΥΣΙΕΣ</th>
                            <th class="inner"  colspan=3 rowspan=2 >ΑΠΟΥΣΙΕΣ<br>ΑΠΟ ΠΟΙΝΕΣ</th>
                        </tr>
                        <tr>
                                <th  class="inner" colspan=3>ΜΕΜΟΝΩΜΕΝΕΣ<br>ΑΠΟΥΣΙΕΣ</th>
                                <th class="inner" >ΑΠΟΥΣΙΕΣ<br>ΟΛΟΚΛ.<br>ΗΜΕΡΑΣ</th>
                        </tr>
                        <tr>
                                <td  class="inner" rowspan=3 colspan=3 style="padding:0 0 0 0;" valign="top">

                                    {assign var='letters' value=','|explode:"α,β,γ,δ,ε,στ,η,θ"}
                                    {assign var=class0 value='left'}{assign var=class1 value='inner'}{assign var=class2 value='right'}
                                    <table cellpadding=0 cellspacing=0 width="100%" >
                                        {for $x = 0 to $dik_count-1}
                                        {if $x==0}{assign var=class0 value='topleft'}{assign var=class1 value='top'}{assign var=class2 value='topright'}{/if}
                                        {if $x==$dik_count-1}{assign var=class0 value='bottomleft'}{assign var=class1 value='bottom'}{assign var=class2 value='bottomright'}{/if}
                                        <tr >
                                            <td class="narrow {$class0}">{$letters[$x]}</td><td class="wide {$class1}">{$paper_dik_define[$x]['perigrafi']}</td><th class="narrow {$class2}">{$foo[$dik_kod[$x]]}</th>
                                        </tr >
                                        {/for}
                                    </table>



                            </td>
                                <td class=" inner">α</td>
                                <td class=" inner">πρωινή<br>απουσία</td>
                            <th class=" inner">{$foo.totalfh}</th>
                                <th  class="inner" rowspan=4 align="center">{$foo.totalfulldayadik}</th>
                               <td class=" inner">α</td>
                                <td class=" inner">από ωριαίες<br>αποβολές</td>
                            <th class=" inner">{$foo.totaloa}</th>
                        </tr>
                        <tr>
                                <td class=" inner">β</td>
                                <td  class="inner" >ενδιάμεσες<br>απουσίες</td>
                            <th  class="inner" >{$foo.totalmh}</th>
                               <td class=" inner">β</td>
                                 <td  class="inner" >από ημερήσιες<br>αποβολές</td>
                            <th  class="inner" >{$foo.totalda}</th>
                        </tr>
                        <tr>
                               <td class=" inner">γ</td>
                                 <td  class="inner" >απουσία τελευ-<br>ταίας ώρας</td>
                            <th  class="inner" >{$foo.totallh}</th>
                                <th  class="inner" rowspan=3 colspan=3 >ΣΥΝΟΛΟ : {$foo.totaloada}</th>
                        </tr>
                        <tr>
                                <th  class="inner" colspan=3 rowspan=2>ΣΥΝΟΛΟ :{$foo.totaldik}</th>
                                <th  class="inner" colspan=2 >ΣΥΝ ΜΕΜΟΝ</tH>
                            <th  class="inner" >{$foo.totalmemon}</th>
                        </tr>
                        <tr>
                                <th  class="inner" colspan=2 >ΣΥΝΟΛΟ ΑΔΙΚ/ΤΩΝ</th><th  class="inner" colspan=2 >{$foo.totaladik}</th>
                        </tr>
{/if}
                    </tbody>
                </table>
                        <br>
                <table border=0 width="100%" class='out'>
                    <tbody>
                          <tr><td colspan=2 >&nbsp;</td></tr>
                        <tr>
                            <td colspan=2 >
                                2. Στο χρονικό διάστημα απο 1/{$foo.lastmonthindex9} έως {$foo.mydate} <u>{$foo.aformi}</u> για πειθαρχικό έλεγχο.</td>
                        </tr>
                        <tr><td colspan=2 >&nbsp;</td></tr>
                        <tr>
                            <td colspan=2 >3. Παρακαλούμε να προσέλθετε στο σχολείο κατά το διάστημα από 1 έως 10 του μήνα {$foo.lastmonth} για ενημέρωσή σας σχετικά με τη φοίτηση {$foo.keimeno1} προσκομίζοντας την παρούσα επιστολή.</td>
                        </tr>
                        <tr><td colspan=2 >&nbsp;</td></tr>
                        <tr><td colspan=4 align="left" >ΑΡ.ΠΡΩΤ: {$foo.protok}</td></tr>
                        <tr>
                            <td width="60%" >ΗΜ/ΝΙΑ: {$foo.nowdate}</td>
                            <td width="40%">{if isset($foo.txtdata.teach_arthro)}{$foo.txtdata.teach_arthro}{/if} ΚΑΘΗΓΗ{if isset($foo.txtdata.teach_last)}{$foo.txtdata.teach_last}{/if}</td>
                        </tr>
                          <tr><td colspan=3 >&nbsp;</td></tr>
                          <tr><td colspan=3 >&nbsp;</td></tr>
                          <tr><td colspan=3 >&nbsp;</td></tr>
                        <tr>
                            <td width="60%">&nbsp;</td>
                            <td width="40%">{if isset($foo.txtdata.teach_name)}{$foo.txtdata.teach_name}{/if}</td>
                        </tr>

                        <tr><td colspan=2 >
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
                          &nbsp;<br>
                          &nbsp;<br>
                          &nbsp;<br>
                          &nbsp;<br>
                          &nbsp;<br>
                          &nbsp;<br>
                          &nbsp;<br>
                          {/if}
                        </td></tr>
                    </tbody>
                </table>
                <table width="100%" cellpadding=2 cellspacing=0 border="1" style="outline : solid;">
                    <tr><td >ΠΑΡΕΛΗΦΘΗ ΑΠΟ ΤΟΝ ΚΗΔΕΜΟΝΑ<br><br>Ονοματεπώνυμο: .............................................................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ημερομηνία : ...../...../........</td></tr>
                </table>


            </div>
            {if not $smarty.foreach.my.last}
            <pagebreak />
            {/if}
        {/foreach}
    </body>
</html>
