
	      <!-- browse nav start -->

	      <center>
	        <a href="/?browse">Start</a>&nbsp; 
	        <?
	        
	          $currpage = $_GET['p'];
	          
	          if(!is_numeric($currpage)) {
	          
	            $currpage = 1;
	            
	          }
	          
	          $prevstart = $currpage - $navlim;
	          
	          if ($prevstart < 1) {
	          
	            $prevstart = 1;
	            
	          }

	          for($i=$prevstart; $i <= $lastpage; $i++) {
	          
	            $pagenum = $i;
	            
	            //if ($i==$lastpage) {
	              
	            //  break;
	              
	            //}
	          
	            //printf("     <b>PS: %s</b>    ", $prevstart);
	          
	            if($i < 1 - $navlim) {
	            
	              break;
	              
	            }
	            
	            if ($i < $currpage - $navlim) {
	            
	              break;
	              
	            }
	            
	            if($i == $currpage) {
	            
	              break;
	              
	            } else {
	            
                      printf("&nbsp;&nbsp;<a href=\"/?browse&p=%s\">%s</a>", $pagenum, $pagenum);
                    
                    }
	            
	          }
	          
	          
	          for($i=$currpage; $i <= $lastpage; $i++) {
	          
	            $pagenum = $i;
	            
	            //if ($i==$lastpage) {
	              
	            //  break;
	              
	            //}
	          
	            if($i > $lastpage) {
	            
	              break;
	              
	            }
	            
	            if ($i > $currpage + $navlim) {
	            
	              break;
	              
	            }
	            
	            if($i == $currpage) {
	            
	              printf("&nbsp;&nbsp;%s", $currpage);
	              	              
	            } else {
	          
	              printf("&nbsp;&nbsp;<a href=\"/?browse&p=%s\">%s</a>", $pagenum, $pagenum);
	            
	            }
	            
	          }
	        ?>
	        &nbsp;&nbsp;<a href="/?browse&p=<?=$lastpage ?>">End</a>
	      </center>
	      
	      <!-- browse nav end -->