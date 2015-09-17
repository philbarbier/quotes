<?

//  if (!session_id()) {
    session_start();
//    $sess = md5($_POST['user'] . rand(0, 999999));
//    session_id($sess);
//  };

  // really crap security check before we get the login/authentication going

  //if($_SERVER['REMOTE_ADDR']!="66.46.53.2") {
//  if($_SERVER['REMOTE_ADDR']!="192.168.56.134") {

  include("admfuncs.inc");

  if($_POST['act']=="login") {
  
    $logres = checklogin($_POST['username'], $_POST['password']);
  
    // printf("login res is: %s", $logres);
  
    if(is_numeric($logres)) {
    
      dologin($logres);
      
    } else {
    
      print "<font color=\"#FF0000\">Login failure.</font>";
    
    }
  }

  if($_REQUEST['act']=="logout") {
  
    dologout();
    
  }

  // debug_array($_SESSION);
  // print session_id();

  if(!is_logged_in()) {
    
    include("login.inc");
  
  } else {

  include("header.inc");
  
  include("content.inc");
  
  include("footer.inc");
  
  }
  
?>
