
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
            <?
            
              switch($qsarr[0]) {
              
                case "latest":
            
            	  $title = "Latest quotes";
                        
                  $res = getlatest();
            
                break;
                  
                case "top":
                
                  $title = "Top quotes";
                  
                  $res = getgroupquote($so, qlim);                  
                
                break;
                
                case "bottom":
                
                  $title = "The dregs!";
                  
                  $res = getgroupquote($so, qlim);
                  
                break;
                
                case "search":
                
                  $title = "Search 'em";
                
                  // do a quick clean up of formdata
                  
                  $srchstr = addslashes($searchstr);
                  
                  if (is_numeric($_REQUEST['lim'])) {
                  
                    $limit = $_REQUEST['lim'];
                    
                  } else {
                  
                    $limit = qlim;
                  
                  }
                
                  $sortby = $_REQUEST['sb'];
                  
                  $res = getsearchquote($srchstr, $limit, $sortby);
                
                break;
                
                case "random":
                
                  $title = "Random quotage";
                  
                  $res = getrandquote();
                
                break;
              
              } // end switch()
              
              // print "<br><pre>New: " . $newswvar . "</pre><br>";
            
            ?>
            <h1><?=$title ?></h1>
            <?
            
              if(is_array($res)) {
              
                for($i=0; $i < count($res); $i++) {
            
            ?>
              
              <p class="quote"><a class="quote" href="/?<?=$res[$i][col_qid] ?>">#<?=$res[$i][col_qid] ?></a> <a class="nlquote" href="/?markdn&q=<?=$res[$i][col_qid] ?>">-</a> (<?=$res[$i][col_score] ?>) <a class="nlquote" href="/?markup&q=<?=$res[$i][col_qid] ?>">+</a> [<a class="quote" href="/?markbd&q=<?=$res[$i][col_qid] ?>">sucks</a>]<br />
              <br/>
              <?=formatentry($res[$i][col_qtext]) ?>
              </p>
              
            <?
            
                }
            
              } else {
              
            ?>
            
              <p>No quotes to be found! :/</p>
            
            <?
              
              }
              
            ?>
              
            </td>
          </tr>
        </table>