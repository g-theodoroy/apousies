{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="column span-17 prepend-3 last " align="center" >
    
    <form name="frm" method="POST" action='' onsubmit=" return validate_form(this);">
        <IMG class="tour dropshadow" src="{$smarty.session.images_prefix}sms-logo.png" height="150px" >

        <table>
            <tr>
                <td colspan='3'>
                    <h4 class="nomargin" align="center">
                        <SELECT name="student" id="student" onchange="select_control_apover();">
                            <option value="">ΕΠΙΛΟΓΗ ΜΑΘΗΤΗ ΓΙΑ ΑΠΟΣΤΟΛΗ SMS</option>
                            <option value="">&nbsp;</option>
                            <option value="all">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ΟΛΟΙ ΟΣΟΙ ΕΧΟΥΝ ΚΑΝΕΙ ΑΠΟΥΣΙΕΣ ΠΑΝΩ ΑΠΟ</option>
                            <option value="new">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ΜΟΝΟ ΤΑ ΝΕΑ ΕΙΔΟΠΟΙΗΤΗΡΙΑ</option>
                            <option value="">&nbsp;</option>
                            {foreach from=$selectdata item=value}
                                {$value}
                            {/foreach}
                            <option value="">&nbsp;</option>
                        </SELECT>
                        <input type="text" name="apover" size="2" style="visibility : hidden;text-align: center;" value="{$orio_paper}">
                    </h4>
                </td>
            </tr>
            <!--
            <tr>
                <td > <h4 class="nomargin" align="center">Αριθμός Πρωτοκόλλου:</h4></td>
                <td><INPUT type="text" name="protok" size="4" value="" style="text-align : center;background-color : #fff;"  tabindex="-1"></td>
                <td><select name="protokctrl" id="protokctrl"><option value="1">ΝΑ ΑΥΞΑΝΕΙ +1</option><option value="0">ΙΔΙΟΣ ΓΙΑ ΟΛΑ</option>{if $smarty.session.parentUser and $prot_con}<option value="2">ΗΛ. ΠΡΩΤΟΚΟΛΛΟ</option>{/if}</select></td>
            </tr>
            -->
            <tr>
                <td colspan='2'><h4 class="nomargin" align="center">Υπολόγισε τις απουσίες μέχρι τις</h4></td>
                <td><input name='lastdate' id='lastdate' value="{$smarty.now|date_format:"%e/%-m/%Y"}" size="10" style="text-align : center; background-color : #fff;"/></td>
            </tr>
            <tr>
                <td colspan='2'><h4 class="nomargin" align="center">Να αποθηκευτούν οι αποστολες sms στο ιστορικό</h4></td>
                <td><select name='history' id='history'><option value="0">ΟΧΙ</option><option value="1">ΝΑΙ</option></select></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <h4 class="nomargin" align="center">
                    Στείλε σε&nbsp;
                        <SELECT name="smssendto" id="smssendto" >
                           <option value="sms2mob">ΜΟΝΟ ΜΕ ΚΙΝΗΤΟ ΧΩΡΙΣ EMAIL</option>
                            <option value="sms2all">ΟΛΟΥΣ ΑΝΕΞΑΙΡΕΤΩΣ</option>
                        </SELECT>
                    </h4>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <h4 class="nomargin" align="center">
                    Επιλογή παρόχου&nbsp;
                        <SELECT name="paroxos" id="paroxos" onchange="showforparoxos(this.value)">
                           <option value="">ΠΑΡΟΧΟΣ</option>
                            <option value="easysms.gr">easysms.gr</option>
                            <option value="sms4u.eu">sms4u.eu</option>
                            <option value="sms-marketing.gr">sms-marketing.gr</option>
                        </SELECT>
                    </h4>
                </td>
            </tr>
            <tr id="smslabelstr" style="display: none;">
                <th >username</th>
                <th id="passApitd">password</th>
                <th >Αποστολέας</th>
            </tr>
            <tr id="smsinputstr" style="display: none;">
                <td><input name='smsusername' id='smsusername' value="" size="15" style="text-align : center; background-color : #fff;"/></td>
                <td><input name='smspassword' id='smspassword' type="password" value="" size="15" style="text-align : center; background-color : #fff;"/></td>
                <td><input name='smssender' id='smssender' value="" size="15" style="text-align : center; background-color : #fff;" title="Μόνο λατινικοί χαρακτήρες, αριθμοί, κάτω παύλα (_), όχι κενά,  έως 11 χαρακτήρες." oninput="checksmssender(this)"/></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <h4 class="nomargin" align="center">
                    Aναφορά αποστολής sms σε email&nbsp;
                    <input type="checkbox" name="emailsmsreport" checked />
                   </h4>
                </td>
            </tr>
        </table>
        <h4 align="center">
            <button type="submit" name="submitBtn" value="sendsms" onclick="frm.action='paper.php';frm.target='';">ΑΠΟΣΤΟΛΗ SMS</button>&nbsp;
            <button type="button" name="exit" value="exit" onclick="window.location='index.php'">ΕΠΙΣΤΡΟΦΗ</button>
        </h4>
    </form>
    
    <h4><b>Αποποίηση ευθυνών</b><br>Δεν προτείνω κάποιον από τους παρόχους.<br>Εσείς θα πρέπει να επιλέξετε με τα δικά σας κριτήρια.<br>Αν κρίνετε ότι πρέπει να προστεθεί και άλλος στείλτε ενα email.</h4>

</div>

{include file='footer.tpl'}
