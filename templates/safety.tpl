{include file='header.tpl'}
{include file='body_header.tpl'}

<div class="column span-15 prepend-4 last" align="center" >


    <h2 align="center">Προτείνεται η ασφαλής κρυπτογραφημένη σύνδεση</h2>
    <h1 align="center">https</h1>
    <h3 align="center">Για να γίνει αυτό εφικτό πρέπει όταν σας ζητηθεί να αποδεχτείτε το πιστοποιητικό ασφαλείας και να προσθέσετε μια εξαίρεση
    όπως φαίνεται στις παρακάτω εικόνες (φυλομετρητής Firefox):</h3>
 
   <div> <IMG src="images/safety_message.png" width="49%" align="center" border="0" >
   <IMG src="images/safety_accept.png" width="46%" align="center" border="0" >
   </div>

  <p align="center">

  <button type="button"  value="http" name="http" onclick="window.location='http://apousies.gr/login.php'">ΚΑΝΟΝΙΚΗ ΣΥΝΔΕΣΗ http</button>
  &nbsp;
  <button type="button"  value="https" name="https" onclick="window.location='https://apousies.gr/login.php'">ΑΣΦΑΛΗΣ ΣΥΝΔΕΣΗ https</button>
  <br>&nbsp;</p>
 
</div>                                

{include file='footer.tpl'}
