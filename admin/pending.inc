
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
              <h1>Pending quotes</h1>
              
              <?
              
                $modid = $_SESSION[col_modid];
              
                // debug_array($_SESSION);
              
                switch($_REQUEST['modact']) {
                
                  case "Approve":
                  
                    if(approvequote($_POST['qid'], $modid)) {
                    
                      print "<center><font color=\"green\">Quote " . $_POST['qid'] . " approved.</font></center>";
                      
                    }
                    
                  break;
                  
                  case "Reject":
                  
                    if(rejectquote($_POST['qid'], $modid)) {
                    
                      print "<center><font color=\"red\">Quote " . $_POST['qid'] . " rejected.</font></center>";
                      
                    }                  
                  
                  break;
                  
                  case "Return to queue":
                  
                    if(returnquote($_POST['qid'])) {
                    
                      print "<center><font color=\"red\">Quote " . $_POST['qid'] . " returned to queue.</font></center>";
                    
                    }
                  
                  break;
                
                }
              
                $res = getpending($modid);
                
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
                        <input type="hidden" name="act" value="pend">
                        <input type="hidden" name="qid" value="<?=$res[$i][col_qid] ?>">
                        <input type="submit" name="modact" value="Approve">
                        <input type="submit" name="modact" value="Reject">
                        <input type="submit" name="modact" value="Return to queue">
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
