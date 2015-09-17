
<?
  if($_POST['dochg']!="") {
  
    // button was pressed
    
    if(check_empty($_POST['oldpass']) || check_empty($_POST['newpass1']) || check_empty($_POST['newpass2'])) {
    
      // no dice
      $fc = "#FF0000";
      $msg = "Error: Empty value entered";
    
    } elseif($_POST['newpass1']!=$_POST['newpass2']) {
    
      // We can assume that we at least have values to work with here.
    
      $fc = "#FF0000";
      $msg = "Error: Password mismatch";
      
    } elseif(changepass($_POST['oldpass'], $_POST['newpass1'])) {
    
      $fc = "#00FF00";
      $msg = "Password changed successfully";
      
    } else {
    
      $fc = "#FF0000";
      $msg = "Password could not be changed";
    
    }
  
  }
?>
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
              <font color="<?=$fc ?>"><?=$msg ?></font><br />
              <form action="<?=$_SERVER['PHP_SELF'] ?>" method="post">
              <input type="hidden" name="act" value="chgpass">
                <table>
                  <tr>
                    <td>Old Password:</td>
                    <td><input type="password" name="oldpass"></td>
                  </tr>
                  <tr>
                    <td>New Password:</td>
                    <td><input type="password" name="newpass1"></td>
                  </tr>
                  <tr>
                    <td>New Password<br /><i>Confirmation</i></td>
                    <td><input type="password" name="newpass2"></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="dochg" value="Change Password"></td>
                  </tr>
                </table>              
              </form>
            </td>
          </tr>
        </table>
