
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
              <h1>The sucks pile</h1>
              <p><font color="#FF0000">Removing a quote WILL actually remove it, never to be seen again!</font></p>
              <?
              
                $modid = $_SESSION[col_modid];
              
                // debug_array($_SESSION);
              
                switch($_REQUEST['modact']) {
                
                  case "Unsuck":
                  
                    if(unsuckquote($_POST['qid'], $modid)) {
                    
                      print "<center><font color=\"green\">Quote " . $_POST['qid'] . " no longer sucks.</font></center>";
                      
                    }
                    
                  break;
                  
                  case "Remove":
                  
                    if(removequote($_POST['qid'])) {
                    
                      print "<center><font color=\"red\">Quote " . $_POST['qid'] . " removed.</font></center>";
                      
                    }                  
                  
                  break;
                 
                }
              
                $res = getsuck();
                
                // debug_array($res);
                
              ?>
              
                <table>
                  <tr>
                    <td>quoteid</td>
                    <td>quotetext</td>
                    <td>action</td>
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;</td>
                  </tr>
              
              <?
              
                for ($i=0; $i < count($res); $i++) {
                
              ?>
              
                  <tr>
                    <td><?=$res[$i][col_qid] ?></td>
                    <td class="quote"><?=formatentry($res[$i][col_qtext]) ?></td>
                    <td>
                      <form action="<?=$_SERVER['PHP_SELF'] ?>" method="post">
                        <input type="hidden" name="act" value="sucks">
                        <input type="hidden" name="qid" value="<?=$res[$i][col_qid] ?>">
                        <input type="submit" name="modact" value="Unsuck">
                        <input type="submit" name="modact" value="Remove">                        
                      </form>                    
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;</td>
                  </tr>                  
              
              <?
                
                }
              
              ?>
              
                </table>
              
            </td>
          </tr>
        </table>
