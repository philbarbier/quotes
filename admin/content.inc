<?

  // quotes.seepies.net - main content file (admin section)
  
  switch($_REQUEST['act']) {
  
  case "pend":
  
    include("pending.inc");
  
  break;
  
  case "sucks":
  
    include("review.inc");
    
  break;
  
  case "reje":
  
    include("reject.inc");
    
  break;
  
  case "chgpass":
  
    include("chgpass.inc");
  
  break;
  
  default:
  
    include("home.inc");
      
  }

?>
