
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
            <?
            
              switch($mark) {
              
                case "down":
            
                  $mkres = scorequotedown($qra[1]);
                  
                break;
                
                case "up":
                
                  $mkres = scorequoteup($qra[1]);
                  
                break;
                
                case "sucks":
                
                  $mkres = quotesucks($qra[1]);
                
                break;
                
              }
            
            ?>
           
            <center>
            <?
              if ($mkres) {
            ?>
              <a href="/?<?=$qra[1] ?>">Quote #<?=$qra[1] ?></a> marked <?=$mark ?>!<br />
            <?
              } else {
            ?>
              Quote could not be marked (does not exist?)<br />
            <?
              }
            ?>
              <br />
              <a href="javascript:history.go(-1);">Go back</a>
            </center>
            
            </td>
          </tr>
        </table>
