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
          <tr><td align="right">Όνομα mail-server:</td><td> <input name="mailserver" type="text" value="smtp.googlemail.com" ></td></tr>
          <tr><td align="right">Θύρα mail-server:</td><td> <input name="mailport" type="text" value="587"></td></tr>
          <tr><td align="right">Ασφάλεια σύνδεσης mail-server:</td><td> <input name="secure" type="text" value="tls"></td></tr>
          <tr><td align="right">Όνομα χρήστη mail-server:</td><td> <input name="mailusername" type="text" value="" ></td></tr>
          <tr><td align="right">Password χρήστη mail-server:</td><td> <input name="mailpassword" type="text" value=""></td></tr>
         <tr><td align="right">E-mail αποστολέα:</td><td> <input name="from" type="text" value=""></td></tr>
         <tr><td align="right">Όνομα αποστολέα:</td><td> <input name="fromname" type="text" value=""></td></tr>
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
				<a href="mailto:g.theodoroygmail.com?subject=Διαχείριση Απουσιών" >GΘ2011</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>
