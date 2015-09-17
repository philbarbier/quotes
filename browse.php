
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
	      <h1>Browse Quotes</h1>
	      
	      <?
	        $numquote = getstatnum(stat_appr);
	        
	        $numpage = ceil($numquote / qlim);
	        
	        $lastpage = ceil($numpage);
	        
	        $navlim = 4;
	        
	        // printf("NQ: %s<br>NP: %s<br>QL: %s<br>LP: %s<br>", $numquote, $numpage, qlim, $lastpage);
	        
	      include("browsenav.php");
	      
	        // calculate offset
	        
	        $os = ($currpage - 1) * qlim;
	      
	        // go get those quotes, baby
	      
	        $res = getbrowsequote($os);
	      
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
	          
	        include("browsenav.php");
	      
	      ?>
	      
	    </td>
	  </tr>
	</table>
