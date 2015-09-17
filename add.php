
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
              <h1>Add a quote</h1>
              <form action="/?add" method="POST">
              
              <?
                if (count($_POST) > 0) {
                
                  // we can at least assume that the submit button was pressed...
                  
                  if(check_empty($_POST['quotetext'])) {
                  
		    // Do NAHFIN'!
		    
		    // Quote box was empty, we'll ignore they pressed submit.
                  
                  } else {
                  
                    // Do IT!
                    
                    $exist = checkquoteexist($_POST['quotetext']);
                    
                    if(is_numeric($exist)) {
                    
                      print "<br /><center><font color=\"red\">The quote has already been entered as <a href=\"/?" . $exist . "\">Quote #" . $exist . "</a></font></center>";
                      
                    } else { 
                    
                      $newquote = addquote($_POST);
                    
                      // printf("<br>NQ: %s", $newquote);
                    
                      if(is_numeric($newquote)) {
                    
                        print "<br /><center><font color=\"red\">Your quote has been submitted as Quote #" . $newquote . "</font></center><br />";
                        
                      }
                    
                    }
                  
                  }
                
                  //print "<font color=red>post exists</font>";
                
                }
              ?>              
                
                <textarea cols="100%" rows="10" name="quotetext"></textarea>
                
                <br /><br />
                <input type="submit" name="cmdSubmit" value="Submit">
                  
                <p>The usual guidelines apply:<br />
                You can submit quotes from any kind of chat medium (IRC, AOL, ICQ, MSN, Yahoo, etc.), provided:</p>
                <ul>
                  <li>They're not inside jokes</li>
                  <li>As much useless text/crap is stripped from it as possible.</li>
                  <li>Unless part of the joke, <b>timestamping should be stripped</b></li>
                </ul>
                Assuming you follow these guidelines, then you'll have a good chance of having your
                quote approved.<br />
                <br />
                <b>Your IP address is never displayed publically</b>
              </form>
            </td>
          </tr>
        </table>
              