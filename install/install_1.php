<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="el">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- Liquid Blueprint CSS -->
<link rel="stylesheet" href="../blueprint/reset.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="../blueprint/liquid.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="../blueprint/typography.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="../blueprint/fancy-type.css" type="text/css" media="screen, projection">
<!--[if IE]><link rel="stylesheet" href="../blueprint/lib/ie.css" type="text/css" media="screen, projection"><![endif]-->

  <title>ΕΓΚΑΤΑΣΤΑΣΗ ΔΙΑΧΕΙΡΙΣΗΣ ΑΠΟΥΣΙΩΝ</title>

<style type="text/css">
	.box { background-color:#fff}
	td { border-style:none;margin:0px;padding:0px;padding-right:25px;}
	h4.nomargin{ margin:0px;padding:0px;}
	h4.r{text-align:right;}
</style>

<script language="JavaScript">
function emailsetvalues(value){
	switch(value) {
    case "1": //ΠΔΣ
			document.getElementById('mail-server').style.display = 'table-row';
			document.getElementById('mailserver').value = 'mail.sch.gr';
			document.getElementById('mail-port').style.display = 'table-row';
			document.getElementById('mailport').value = '465';
			document.getElementById('secure-').style.display = 'table-row';
			document.getElementById('secure').value = 'ssl';
			document.getElementById('mail-username').style.display = 'table-row';
			document.getElementById('mailusername').value = '';
			document.getElementById('mail-password').style.display = 'table-row';
			document.getElementById('mailpassword').value = '';
			document.getElementById('client-ID').style.display = 'none';
			document.getElementById('clientID').value = '';
			document.getElementById('client-Secret').style.display = 'none';
			document.getElementById('clientSecret').value = '';
			document.getElementById('token-').style.display = 'none';
			document.getElementById('token').value = '';
			document.getElementById('from-').style.display = 'table-row';
			document.getElementById('from').value = '';
			document.getElementById('from-name').style.display = 'table-row';
			document.getElementById('fromname').value = '';
      break;
    case "2": //GMAIL
			document.getElementById('mail-server').style.display = 'table-row';
			document.getElementById('mailserver').value = 'smtp.gmail.com';
			document.getElementById('mail-port').style.display = 'table-row';
			document.getElementById('mailport').value = '587';
			document.getElementById('secure-').style.display = 'table-row';
			document.getElementById('secure').value = 'tls';
			document.getElementById('mail-username').style.display = 'none';
			document.getElementById('mailusername').value = '';
			document.getElementById('mail-password').style.display = 'none';
			document.getElementById('mailpassword').value = '';
			document.getElementById('client-ID').style.display = 'table-row';
			document.getElementById('clientID').value = '';
			document.getElementById('client-Secret').style.display = 'table-row';
			document.getElementById('clientSecret').value = '';
			document.getElementById('token-').style.display = 'table-row';
			document.getElementById('token').value = '';
			document.getElementById('from-').style.display = 'table-row';
			document.getElementById('from').value = '';
			document.getElementById('from-name').style.display = 'table-row';
			document.getElementById('fromname').value = '';
      break;
		case "3": //ΑΛΛΟ
			document.getElementById('mail-server').style.display = 'table-row';
			document.getElementById('mailserver').value = '';
			document.getElementById('mail-port').style.display = 'table-row';
			document.getElementById('mailport').value = '';
			document.getElementById('secure-').style.display = 'table-row';
			document.getElementById('secure').value = '';
			document.getElementById('mail-username').style.display = 'table-row';
			document.getElementById('mailusername').value = '';
			document.getElementById('mail-password').style.display = 'table-row';
			document.getElementById('mailpassword').value = '';
			document.getElementById('client-ID').style.display = 'none';
			document.getElementById('clientID').value = '';
			document.getElementById('client-Secret').style.display = 'none';
			document.getElementById('clientSecret').value = '';
			document.getElementById('token-').style.display = 'none';
			document.getElementById('token').value = '';
			document.getElementById('from-').style.display = 'table-row';
			document.getElementById('from').value = '';
			document.getElementById('from-name').style.display = 'table-row';
			document.getElementById('fromname').value = '';
		  break;
		default:
		document.getElementById('mail-server').style.display = 'none';
		document.getElementById('mailserver').value = '';
		document.getElementById('mail-port').style.display = 'none';
		document.getElementById('mailport').value = '';
		document.getElementById('secure-').style.display = 'none';
		document.getElementById('secure').value = '';
		document.getElementById('mail-username').style.display = 'none';
		document.getElementById('mailusername').value = '';
		document.getElementById('mail-password').style.display = 'none';
		document.getElementById('mailpassword').value = '';
		document.getElementById('client-ID').style.display = 'none';
		document.getElementById('clientID').value = '';
		document.getElementById('client-Secret').style.display = 'none';
		document.getElementById('clientSecret').value = '';
		document.getElementById('token-').style.display = 'none';
		document.getElementById('token').value = '';
		document.getElementById('from-').style.display = 'none';
		document.getElementById('from').value = '';
		document.getElementById('from-name').style.display = 'none';
		document.getElementById('fromname').value = '';
	}
}

</script>
</head>
<body >
<div class="container">
<div class="block" style="min-height : 700px;">
	<!-- HEADER -->
	<div class="block">
		<div class="column span-24">
			<div>
				<div align="left" class="column span-5 "><IMG src="../images/evolution.gif" height="70" align="left" border="0"></div> 
				<div class="column span-19 ">&nbsp;</div>

				<div class="column span-14 last" align="center">


<h1>Εγκατάσταση</h1>

			</div> 

			</div>
		</div>
	</div>

	<hr>

	<!-- END HEADER -->

	<div class="block">

		<!-- MENU -->
		<div class="column span-3 " >&nbsp;</div>


		<!-- CONTENT -->
		<div class="column span-18 last" >

			<!-- SELECT -->
			<div class="block">
				<div class="column span-24 last" >
					<h1 align="center" >Διαχείριση απουσιών</h1>
					<hr class="space">

<h3 align="center">
      <form action="install_2.php" method="post" name="frm">
        <table  align="center" width="80%">
					<tr><td style="text-align: center;"><h3>Βάση δεδομένων</h3></td><td></td></tr>
					<tr><td align="right">Όνομα server:</td><td> <input name="host" type="text" value="localhost" ></td></tr>
          <tr><td align="right">Όνομα χρήστη mysql:</td><td> <input name="username" type="text" value="root"></td></tr>
          <tr><td align="right">Password χρήστη mysql:</td><td> <input name="password" type="text" value=""></td></tr>
         <tr><td align="right">Όνομα βάσης δεδομένων:</td><td> <input name="database" type="text" value="apousies_db"></td></tr>
         <tr><td align="right">Να αντικατασταθεί η υπάρχουσα βάση δεδομένων:</td>
             <td>
                 <select name="replacedatabase">
                     <option value=0  >ΟΧΙ</option>
                     <option value=1>ΝΑΙ</option>
                 </select>
             </td></tr>
            <tr><td colspan="2" align="center">&nbsp;</td></tr>
            <tr><td colspan="2" align="center">&nbsp;</td></tr>
					<tr><td style="text-align: center;"><h3>Αποστολή Email</h3></td>
						<td>
							<select name="select-email-provider" onchange="emailsetvalues(this.value)">
									<option  value=0 selected >Επιλέξτε ρύθμιση</option>
									<option value=1>ΠΑΝ ΣΧΟΛ ΔΙΚΤΥΟ</option>
									<option value=2>GMAIL</option>
									<option value=3>ΑΛΛΗ ΡΥΘΜΙΣΗ</option>
							</select>
						</td>
					</tr>
          <tr id="mail-server" style="display: none;"><td align="right">Όνομα mail-server:</td><td> <input id="mailserver" name="mailserver" type="text" value="" ></td></tr>
          <tr id="mail-port" style="display: none;"><td align="right">Θύρα mail-server:</td><td> <input id="mailport"  name="mailport" type="text" value="587"></td></tr>
          <tr id="secure-" style="display: none;"><td align="right">Ασφάλεια σύνδεσης mail-server:</td><td> <input id="secure"  name="secure" type="text" value="tls"></td></tr>
					<tr id="mail-username" style="display: none;"><td align="right">Όνομα χρήστη mail-server:</td><td> <input id="mailusername" name="mailusername" type="text" value="" ></td></tr>
          <tr id="mail-password" style="display: none;"><td align="right">Password χρήστη mail-server:</td><td> <input id="mailpassword" name="mailpassword" type="text" value=""></td></tr>
					<tr id="client-ID" style="display: none;"><td align="right"><b>Gmail client ID</b>: <a href="https://github.com/g-theodoroy/apousies/blob/master/useful/apousies-email-settings.pdf" target="_blank">&nbsp;&nbsp;?&nbsp;&nbsp;</a></td><td> <input id="clientID" name="clientID" type="text" value="" ></td></tr>
					<tr id="client-Secret" style="display: none;"><td align="right"><b>Gmail client Secret</b>:</td><td> <input id="clientSecret" name="clientSecret" type="text" value=""></td></tr>
					<tr id="token-" style="display: none;"><td align="right"><b>Gmail Token</b>:</td><td> <input id="token" name="token" type="text" value=""></td></tr>
          <tr id="from-" style="display: none;"><td align="right">E-mail αποστολέα:</td><td> <input id="from" name="from" type="text" value=""></td></tr>
          <tr id="from-name" style="display: none;"><td align="right">Όνομα αποστολέα:</td><td> <input id="fromname" name="fromname" type="text" value=""></td></tr>
            <tr><td colspan="2" align="center">&nbsp;</td></tr>
           <tr><td colspan="2" align="center">&nbsp;</td></tr>
          <tr><td colspan="2" ><center><button type="submit" name="continue" value="continue" >ΣΥΝΕΧΕΙΑ</button> &nbsp;<button type="button" name="cancel" value="cancel" onclick="window.location='../index.php'" >ΑΚΥΡΩΣΗ</button> </center></td></tr>
        </table>
      </form>
</h3>


			</div>

				</div>
			</div>
		</div>
	</div>
</div>

<hr>
	<!-- FOOTER -->
	<div class="block">
		<div class="column span-4">
			<div align="left">
				&nbsp;
			</div>
		</div>
		<div class="column span-16">
			<div align="center">
				&nbsp;
			</div>
		</div>
		<div class="column span-4 last">
			<div align="right">
				<a href="mailto:g.theodoroygmail.com?subject=Διαχείριση Απουσιών" >GΘ@2017</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>
