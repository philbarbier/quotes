<?

  // quotes.seepies.net - main content file
  
  // First, we need to access functions.

  require_once("funcs.php");

  // If we're doing a public site debug, we don't want everyone and their
  // dog seeing it, so we'll have this crude check to allow one IP in (to be changed perhaps later)

  $myip = "192.168.0.1";

  if(debug==1 && get_ip() != $myip) {
  
    print "<center><font color=\"red\">Site is under going maintenance, some sections might not be available.</font></center>";
    include ("home.php");
    exit;
        
  }
  
  //$swvar = $_SERVER['argv'][0];

  $swvar = $_SERVER['QUERY_STRING'];

  $seperator = "&";
  
  // ?markdn&q=14
  
  $newswvar = strrev(stristr(strrev($swvar), $seperator));
  
  $newswvar = substr($newswvar, 0, strlen($newswvar)-1);
  
  if(check_empty($newswvar)) {
  
    $newswvar = $swvar;
    
  }
  
  $qr = substr(stristr($swvar, $seperator), 1);
  
  if(check_empty($qr)) {
  
    // can we assume a quote # has been entered?
    
    $qr = $newswvar;
    
  }

  $qra = explode("=", $qr);
  
  $newqr = explode($seperator, $swvar);
  
  foreach ($newqr as $k => $v) {
  
    $newv = explode("=", $v);
    
    // debug_array($newv);
    
    $qsarr[$newv[0]] = $newv[1];
    
    //printf("<br>k: %s --- v: %s", $k, $v);
    
  }
  
  $pgreq = substr($swvar, 0, strpos($swvar, "="));
  
  if(check_empty($pgreq)) {
  
    $pgreq = $newswvar;
    
  }
  
  //$pgreq = substr($pgreq, 0, strlen($pgreq));  
  
  $qsarr[] = $pgreq;
  
  // debug_array($qsarr);
 
  // printf("<br>pgreq: %s<br>new :%s<br>qr: %s<br>sw: %s<br>", $pgreq, $newswvar, $qr, $swvar);
 
  // debug_array($qra);
  
  //debug_array($newqr);
  
  switch($newswvar) {
  
  case "add":
  
    include("add.php");
    
  break;
  
  case "latest":
  
    include("getquote.php");
  
  break;
  
  case "markdn":
  
    $mark = "down";
    include("markquote.php");
  
  break;
  
  case "markup":
  
    $mark = "up";
    include("markquote.php");
    
  break;
  
  case "markbd":
  
    $mark = "sucks";
    include("markquote.php");
    
  break;
  
  case "top":
  
    $so = "desc";
    include("getquote.php");
  
  break;
  
  case "bottom":
  
    $so = "asc";
    include("getquote.php");
    
  break;
  
  case "random":
  
    include("getquote.php");
    
  break;
  
  case "browse":
  
    include("browse.php");
  
  break;
  
  default:
  
    switch($qsarr[0]) {
    
      case "quote":
    
        $qr = $qra[1];
        include("quote.php");
      
      break;
      
      case "search":
      
        include("search.php");
        
      break;
      
      default:
          
        if (is_numeric($newswvar)) {
    
          include("quote.php");
      
        } else {
  
          // include home page
  
          include("home.php");
      
        }
        
      }
    
  }
  
?>
