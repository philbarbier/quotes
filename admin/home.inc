
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
              <h1>admin area</h1>
              <p>There are currently <?=getnewpendc() ?> pending quotes in the queue.<br />
              You have <?=getnumass($_SESSION[col_modid]) ?> quotes currently assigned to you.<br />
              You have approved <?=getnumapp($_SESSION[col_modid]) ?> quotes!<br />
              </p>
              <?
              
              debug_array($_SESSION);
              
              session_id();
              
              if(!check_empty($_POST['cmdgetq'])) {
              
                // get some quotes for this one!
                
                if(assignquote($_SESSION[col_modid])) {
                
                  print "<br />Quotes assigned.<br />";
                  
                } else {
                
                  print "<br />Quotes not assigned.<br />";
                
                }
              
              }
              
              ?>
		<form action="index.php" method="post">
		  <input type="submit" name="cmdgetq" value="Gimme some quotes!">
		</form>

	    </td>
	  </tr>
	</table>