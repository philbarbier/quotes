
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>quotes.seepies.net :: Admin area</title>
  
    <link rel="stylesheet" href="main.css" type="text/css">
    
  </head>
  <body>
  
  <!-- main page title -->
  
  <table border="0" width="95%" align="center">

    <tr>
      <td>
  
        <h1>quotes.seepies.net</h1>
        <p class="quote">Administration area</p>
        <br />
<form action="/admin/index.php" method="post">
<input type="hidden" name="act" value="login">

<table>
  <tr>
    <td>Username:</td>
    <td><input type="text" name="username" maxlength="8" value="<?=stripslashes($_POST['username']) ?>"></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input type="password" name="password" maxlength="20"></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="login" value="Login"></td>
  </tr>
</table>
</center>
</form>
</td>
</tr>
</table>
<a href="/">Back to main page</a>
</body>
</html>