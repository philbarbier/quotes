<?

/*

  quotes.seepies.net - Admin Functions file.
  
  Initial creation time: 5th May 2004
  
*/

$dbhost = "localhost";
//$dbhost = "127.0.0.1";
$dbport = 3306;
$dbuser = "devuser";
$dbpass = "revdev";

$dbname = "quotes";


define("debug", 0);

// Status definitions - these should never change, ever.

define("stat_pend", 1);
define("stat_appr", 2);
define("stat_suck", 3);
define("stat_reje", 4);

// Table/column declaration

// Moderator table

define("tbl_mod", "tbl_moderator");

define("col_modid", "modid");
define("col_user", "username");
define("col_pass", "qpassword");
define("col_email", "email");

// Quotes table

define("tbl_quote", "tbl_quote");

define("col_qid", "quoteid");
define("col_vis", "visible");
define("col_qstat", "qstatus");
define("col_ipadd", "ipaddress");
define("col_date", "datetime");
define("col_qtext", "quotetext");
define("col_score", "score");
define("col_mksk", "mksuck");
define("col_moddate", "datemod");
define("col_assto", "assignedto");

// Status table

define("tbl_status", "tbl_status");

define("col_sid", "statusid");
define("col_tstat", "status");

// set up a system wide datestring format

define("datefmt", "d/M/Y H:i T");

// set up the database connection

$dbconn = @mysql_connect($dbhost . ":" . $dbport, $dbuser, $dbpass);

$dbsel = @mysql_select_db($dbname);

if ($dbconn == FALSE || $dbsel == FALSE || mysql_error()) {

print "<font color=\"#FF0000\">Database server unavailable.</font>";
if(debug == 1) {

print "<br />error: " . mysql_error();

}

exit;

}


/********************************************

*** Utility functions

********************************************/

function doquery_fa($sql) {

  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  for ($i=0; $i < mysql_numrows($res); $i++) {
  
    $resarr[] = mysql_fetch_assoc($res);
  
  }

  return $resarr;

}

/********************************************

Function to print out a formatted array

********************************************/

function debug_array($a) {

  if (is_array($a)) {

    print "<br /><pre>";
    print_r($a);
    print "</pre><br />";
    
  } else {
  
    print "<br />" . $a . " is not an array.<br />";
    
  }
  
}

/*******************************************

Function to check if a variable is empty,
set, null or an empty string

********************************************/

function check_empty($v) {

  if(empty($v) || $v == "" || !isset($v) || is_null($v)) {
  
    return true;
    
  } else {
  
    return false;
    
  }

}

/******************************************

Function to help debug a function that
uses SQL

********************************************/

function debug_sqlfnc($sql, $dberr) {

  print "<br /><br /><pre>error: " . $dberr . "<br />";
  print "SQL is: " . $sql;
  print "</pre><br /><br />";
  
}

/*******************************************

Function to get the user IP

********************************************/

function get_ip() {

  $ip = "unknown";
 
  $clientip = getenv("HTTP_CLIENT_IP");
  $forwardedip = getenv("HTTP_X_FORWARDED_FOR");
  $remoteaddr = getenv("REMOTE_ADDR");
  $spos = strpos($forwardedip,",");
    
  if($spos) { 
    
    $forwardedip = substr($forwardedip, 0, $spos); 
    
  }
   
  if ($clientip) { 
      
    $ip = $clientip;
    
  } elseif ($forwardedip) { 
      
    $ip = $forwardedip; 
    	
  } else { 
      
    $ip = $remoteaddr; 
    
  }
      
  return $ip;

}

/*******************************************

Function to format the entry text for output

********************************************/

function formatentry($text) {

  $newtext = stripslashes(nl2br($text));

  return $newtext;

} /* formatentry */

/********************************************

*** Site functions

********************************************/

/****************************************************

Function checklogin - does a check against the database
to authenticate the login.

****************************************************/

function checklogin($user, $pass) {

  // Firstly, if either are empty, we'll return right away.
  
  if ($user=="" || $pass=="") {
  
    return false;
    
  }
  
  // We can now assume at least a (somewhat) legal 
  // attempt has been made
  
  $user = addslashes(strip_tags($user));
  $pass = md5(addslashes(strip_tags($pass)));
  
  global $dbconn;
  
  // Firstly, we'll authenticate, then we'll update the table
  // to enter last known login details.
  
  $sql = "select " . col_modid . ", " . col_user .", " . col_pass . " from " . tbl_mod;
  $sql .= " where " . col_user . "='" . $user . "' and " . col_pass . "='" . $pass . "';";

  $res = @mysql_query($sql, $dbconn);

  if(debug == 1) {

    debug_sqlfnc($sql, mysql_error());
    print $res;

  }   
  
  if (mysql_num_rows($res)==1) {  
    
    return mysql_result($res,0,col_modid);
    
  } else {
    
    return false;
    
  }

} /* checklogin */

function dologin($modid) {

  // sets the cookie up, etc. after a successful login
  
  $_SESSION['loginid'] = md5($_REQUEST['username'] . date("U"));
  $_SESSION[col_modid] = $modid;  
  
  return true;

}

function dologout() {

  // unset the cookie

  unset($_SESSION['loginid']);
  unset($_SESSION[col_modid]);
  
}

function is_logged_in() {

  if(isset($_SESSION['loginid']) && isset($_SESSION[col_modid])) {
  
    return true;
    
  } else {
  
    return false;
  
  }

}

/********************************************

Function to retrieve the number of quotes
given a status

********************************************/

function getstatnum($status) {

  $sql = "select count(*) as resnum from " . tbl_quote . " where " . col_qstat . "=" . $status . ";";

  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {

    debug_sqlfnc($sql, mysql_error());

  }   

  return mysql_result($res, 0, "resnum");

} /* getstatnum() */

/********************************************

Function to retrieve the number of quotes that
are marked for review, as well as approved.

********************************************/

function getstatrev() {

  $sql = "select count(*) as resnum from " . tbl_quote  . " where " . col_mksk . "=1 and " . col_qstat . "=" . stat_appr . ";";
  
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {

    debug_sqlfnc($sql, mysql_error());

  }   

  return mysql_result($res, 0, "resnum");

} /* getstatrev */

/********************************************

Function to retrieve pending quotes from the
database

Takes moderator ID as parameter

********************************************/

function getpending($modid) {

  $sql = "select " . col_qid . ", " . col_qtext . " from " . tbl_quote . " where " . col_qstat . "=" . stat_pend . " and " . col_assto . "=" . $modid . ";";
 
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }

  for ($i=0; $i < mysql_numrows($res); $i++) {
  
    $resarr[] = mysql_fetch_assoc($res);
  
  }
  
  return $resarr;

} /* getpending() */

/********************************************

Function to approve a quote

Takes quoteid and moderatorid as parameters

********************************************/

function approvequote($qid, $modid) {

  $moddate = date("U");
  
  $sql = "update " . tbl_quote . " set " . col_qstat . "=" . stat_appr . ", " . col_assto . "=" . $modid;
  $sql .= ", " . col_moddate . "=" . $moddate . " where " . col_qid . "=" . $qid . ";";

  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }
  
  return $res;

} /* approvequote() */

/********************************************

Function to reject a quote

Takes quoteid and moderatorid as parameters

********************************************/

function rejectquote($qid, $modid) {

  $moddate = date("U");
  
  $sql = "update " . tbl_quote . " set " . col_qstat . "=" . stat_reje . ", " . col_assto . "=" . $modid;
  $sql .= ", " . col_moddate . "=" . $moddate . " where " . col_qid . "=" . $qid . ";";

  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }
  
  return $res;

} /* rejectquote() */

/********************************************

Function to return a quote to the moderating
queue

********************************************/

function returnquote($qid) {

  $sql = "update " . tbl_quote . " set " . col_assto . "=NULL where " . col_qid . "=" . $qid . ";";
  
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }
  
  return $res;  

} /* returnquote() */

/********************************************

Function to return the number of moderators
in the system

********************************************/

function getnummod() {

  $sql = "select count(*) as modnum from " . tbl_mod . ";";
  
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {

    debug_sqlfnc($sql, mysql_error());

  }   

  return mysql_result($res, 0, "modnum");
  

} /* getnummod() */

/********************************************

Function to retrieve all new quotes into the
system, these should be pending and assigned
to no-one.

parameter is the number of quotes to get

********************************************/

function getnewpend($numtoget) {

  $sql = "select " . col_qid . " from " . tbl_quote . " where " . col_qstat . "=" . stat_pend . " and " . col_assto . " is NULL order by " . col_date . " limit 0," . $numtoget . ";";
 
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  for ($i=0; $i < mysql_numrows($res); $i++) {
  
    $resarr[] = mysql_result($res, $i, col_qid);
  
  }

  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    debug_array($resarr);
  
  }
  
  // Just to avoid implode causing a Warning
  
  if(count($resarr) > 0) {
  
    $resimp = implode(",", $resarr);
  
    return $resimp;
    
  } else {
  
    return false;
    
  }

} /* getnewpend() */

/********************************************

Function to retrieve a count of all new quotes 
in the system, these should be pending and 
assigned to no-one.

More or less identical to getnewpend()

********************************************/

function getnewpendc() {

  $sql = "select count(*) as numpend from " . tbl_quote . " where " . col_qstat . "=" . stat_pend . " and " . col_assto . " is NULL;";
 
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }

  return mysql_result($res, 0, "numpend");

} /* getnewpendc() */

/********************************************

Function that returns a count of quotes
that are assigned to a moderator and not yet
been processed.

********************************************/

function getnumass($modid) {

  $sql = "select count(*) as numass from " . tbl_quote . " where " . col_qstat . "=" . stat_pend . " and " . col_assto . "=" . $modid . ";";
 
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }

  return mysql_result($res, 0, "numass");

} /* getnumass() */

/********************************************

Function that returns a count of quotes
that have been approved by a moderator

********************************************/

function getnumapp($modid) {

  $sql = "select count(*) as numapp from " . tbl_quote . " where " . col_qstat . "=" . stat_appr . " and " . col_assto . "=" . $modid . ";";
 
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }

  return mysql_result($res, 0, "numapp");

} /* getnumapp() */

/********************************************

Function to assign quotes to a moderator upon
request.

Moderator ID is the parameter taken

********************************************/

function assignquote($modid) {

  $nummod = getnummod();
  
  $numnew = getnewpendc();
  
  $numtoass = ceil($numnew / $nummod); // number to assign to $modid
  
  $qlist = getnewpend($numtoass); // get a list (array) of quotes to assign
  
  // need to format the above array before we allow it into the database
  
  //debug_array($qlist);
  
  // Careful now, let's unleash this beast on the database!
  
  $sql = "update " . tbl_quote . " set " . col_assto . "=" . $modid . " where " . col_qid . " in (" . $qlist . ");";

  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    printf("<br>NM: %s<br>NN: %s<br>NA: %s<br>QL: %s", $nummod, $numnew, $numtoass, $qlist);
  
  }
  
  return $res;

} /* assignquote */

/********************************************

Function to retrieve the quotes that have been
marked for review

********************************************/

function getsuck() {

  $sql = "select " . col_qid . ", " . col_qtext . " from " . tbl_quote . " where " . col_mksk . "=1 and " . col_qstat . "=" . stat_appr . ";";

  $resarr = doquery_fa($sql);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }

  return $resarr;

} /* getsuck */

/********************************************

Function to remove a quote

********************************************/

function removequote($qid) {

  if(check_empty($qid)) {
  
    // We don't want to go in full force with an empty quote id!
  
    return false;
    
  }

  $sql = "delete from " . tbl_quote . " where " . col_qid . "=" . $qid . ";";
  
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }
  
  return $res;  

} /* removequote() */

/********************************************

Function to make a quote not-suck anymore

Takes quoteid and moderatorid as parameters

********************************************/

function unsuckquote($qid, $modid) {

  // $moddate = date("U");
  
  /***** 
  We don't want to edit the moddate here, it buggers
  up the quote ordering, and just isn't right.
  ****/
  
  $sql = "update " . tbl_quote . " set " . col_qstat . "=" . stat_appr . ", " . col_assto . "=" . $modid;
  $sql .= ", " . col_mksk . "=0 where " . col_qid . "=" . $qid . ";";

  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }
  
  return $res;

} /* unsuckquote() */

/********************************************

Function to retrieve the quotes that have been
rejected by moderation

********************************************/

function getreject() {

  $sql = "select " . col_qid . ", " . col_qtext . ", " . col_assto . ", " . col_moddate . " from " . tbl_quote . " where " . col_qstat . "=" . stat_reje . ";";
 
  $resarr = doquery_fa($sql);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }
  
  return $resarr;

} /* getsuck */

/********************************************

Function to get a moderator username based
on an ID given

********************************************/

function getmoderator($modid) {

  $sql = "select " . col_user . " from " . tbl_mod . " where " . col_modid . "=" . $modid . ";";

  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
  
  }
  
  return mysql_result($res, 0, col_user);

}


/********************************************

Function to change a moderators password

********************************************/

function changepass($op, $np) {

  $modid = $_SESSION['modid'];
  
  if(check_empty($modid)) {
  
    return false;
    
  }
  
  // first check the oldpass is valid
  
  $ophash = md5($op);
  
  $sql = "select " . col_modid . " from " . tbl_mod . " where " . col_modid . "=" . $modid . " and " . col_pass . "='" . $ophash . "';";
  
  global $dbconn;
  
  $qres = @mysql_query($sql);
  
  if(mysql_numrows($qres)==1) {
  
    // do change
    
    $nphash = md5($np);
    
    $sql = "update " . tbl_mod . " set " . col_pass . "='" . $nphash . "' where " . col_modid . "=" . $modid . ";";
    
    $qres2 = @mysql_query($sql);

    if(debug == 1) {
    
      debug_sqlfnc($sql, mysql_error());
      debug_array($qres);
      debug_array($qres2);
    
    }  
     
    if($qres2) {
    
      return true;
      
    } else {
    
      return false;
    
    }
  
  } else {
  
    return false;
    
  }
  
} /* changepass */

?>
