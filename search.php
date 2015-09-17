
        <table cellspacing="2" cellpadding="0" align="center" border="0" width="99%">
          <tr>
            <td>
            
	      <?
	      
	        // check the search string is over strlen(3) and not padded with spaces
	        
	        $searchstr = $_REQUEST['search'];
	        
	        $errstr = "<font color=\"red\">Error in search string. Try again.</font><br />";
	        
	        if(!check_empty($searchstr)) {
	        
	          if(strlen($searchstr) <= 2) {
	        
	            print $errstr;
	        
	          } elseif (strlen($searchstr) == 3 && stristr($searchstr, " ")) {
	        
	            print $errstr;
	        
	          } else {
	        
	            include("getquote.php");
	        
	          }
	          
	        } else {
	        
	          print "<h1>Search 'em</h1>";
	        
	        }
	      
	      ?>            
            
              <br />
	      <center>
	        Search tip: Ensure your keyword is over 3 letters long.<br />
	        <br />
	        <form action="./?search" method="get">
	          Enter your search phrase:<br /><br />
	          <input type="text" name="search" maxlength="30" value="<?=$searchstr ?>">&nbsp;
	          <input type="submit" value="Search"><br /><br />
	          Number to get:&nbsp;&nbsp;
	            <select name="lim">
	              <option value="25" selected>25</option>
	              <option value="50">50</option>
	              <option value="75">75</option>
	              <option value="100">100</option>
	            </select>
	          <br />
	          <br />
	          Sort by:&nbsp;&nbsp;
	            <select name="sb">
	              <option value="0" selected>Quote Number</option>
	              <option value="1">Quote Score</option>
	            </select>
	        </form>
	      </center>
	      
	    </td>
	  </tr>
	</table>