<?

/*

  quotes.seepies.net - Functions file.
  
  Initial creation time: 24 April 2004 at 00:18 EDT

*/

$siteversion = "1.2.8";

$dbhost = "localhost";
//$dbhost = "127.0.0.1";
$dbport = 3306;
$dbuser = "devuser";
$dbpass = "revdev";

$dbname = "quotes";

define("debug", 0);

// set up the database connection

$dbconn = @mysql_connect($dbhost . ":" . $dbport, $dbuser, $dbpass);


if (debug == 1) { print "\n" . mysql_error() . "\n"; } 


$dbsel = @mysql_select_db($dbname);

if ($dbconn == FALSE || $dbsel == FALSE || mysql_error()) {

  print "<font color=\"#FF0000\">Database server unavailable.</font>";
  if(debug == 1) {
    
    print "<br />error: " . mysql_error();
      
  }
    
  exit;

}

// Status definitions - these should never change, ever.

define("stat_pend", 1);
define("stat_appr", 2);
define("stat_suck", 3);
define("stat_reje", 4);

// Defining the quote limit that we get on pages

define("qlim", 25);

// Table/column declaration

// Moderator table

define("tbl_mod", "tbl_moderator");

define("col_modid", "modid");
define("col_user", "username");
define("col_pass", "password");
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

/********************************************

Function to handle the submission of a quote

********************************************/

function addquote($postarr) {

  $ipaddress = get_ip();
  
  $quotetext = trim(addslashes(htmlentities($_POST['quotetext'])));
  
  $currdate = date("U");

  // first, we want to get the max quoteid + 1 so we can return the new quoteid
  
  $sql = "select max(" . col_qid . ") as mxqid from " . tbl_quote . ";";

  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {

    debug_sqlfnc($sql, mysql_error());

  }
  
  $newqid = @mysql_result($res, 0, "mxqid") + 1;
  
  $sql  = "insert into " . tbl_quote;
  $sql .= " (" . col_ipadd . ", " . col_date . ", " . col_qtext . ") ";
  $sql .= "values ('" . $ipaddress . "', '" . $currdate . "', '" . $quotetext . "');";
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {

    debug_sqlfnc($sql, mysql_error());

  }  
  
  return $newqid;

} /* addquote */

/********************************************

Function to check if a quote exists in the
system already

********************************************/

function checkquoteexist($quotetext) {

  $quotetext = trim(addslashes(htmlentities($quotetext)));
  
  // first we check if the quote has been added before:
  
  $sql = "select " . col_qid . " from " . tbl_quote . " where " . col_qtext . "='" . $quotetext . "';";
  
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {

    debug_sqlfnc($sql, mysql_error());

  }  

  return @mysql_result($res, 0, col_qid);

}

/********************************************

Function to retrieve the number of quotes
given a status

********************************************/

function getstatnum($status) {

  $sql = "select count(*) as resnum from " . tbl_quote . " where " . col_qstat . "=" . $status . " and " . col_vis . "=0;";

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

Function to retrieve the latest top <qlim> quotes
that have been approved by moderation

********************************************/

function getlatest() {

  $sql = "select " . col_qid . ", " . col_qtext . ", " . col_score . " from " . tbl_quote . " where " . col_qstat . "=2 order by " . col_moddate . " desc limit 0, " . qlim . ";";
  
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }
  
  for ($i=0; $i < mysql_numrows($res); $i++) {
  
    $resarr[] = mysql_fetch_assoc($res);
  
  }
  
  return $resarr;

} /* getlatest() */

/********************************************

Function to retrieve one quote at a time, 
given a quoteid

********************************************/

function getquote($qid) {

  if(!is_numeric($qid)) {
  
    $resarr[] = array();
    return $resarr;
  
  }

  $sql = "select " . col_qid . ", " . col_qtext . ", " . col_score . " from " . tbl_quote . " where " . col_qstat . "=2 and " . col_qid . "=" . $qid . ";";
  
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }
  
  for ($i=0; $i < mysql_numrows($res); $i++) {
  
    $resarr[] = mysql_fetch_assoc($res);
  
  }
  
  return $resarr;

} /* getlatest() */

/********************************************

Function to increase a quotes score

********************************************/

function scorequoteup($qid) {

  $sql = "select " . col_score . " from " . tbl_quote . " where " . col_qstat . "=" . stat_appr . " and " . col_qid . "=" .$qid . ";";
  
  global $dbconn;
  
  $res1 = @mysql_query($sql, $dbconn);
  
  if (debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }

  if(mysql_numrows($res1)==0) {
  
    return false;
    
  }
  
  $currscore = mysql_result($res1, 0);
  
  $newscore = $currscore + 1;
  
  $sql = "update " . tbl_quote . " set " . col_score . "=" . $newscore . " where " . col_qstat . "=" . stat_appr . " and " . col_qid . "=" . $qid . ";";
  
  $res2 = @mysql_query($sql, $dbconn);
  
  if (debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }
  
  return $res2;

} /* scorequoteup() */

/********************************************

Function to decrease a quotes score

********************************************/

function scorequotedown($qid) {

  $sql = "select " . col_score . " from " . tbl_quote . " where " . col_qstat . "=" . stat_appr . " and " . col_qid . "=" .$qid . ";";
  
  global $dbconn;
  
  $res1 = @mysql_query($sql, $dbconn);
  
  if (debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }
  
  if(mysql_numrows($res1)==0) {
  
    return false;
    
  }
  
  $currscore = mysql_result($res1, 0);
  
  $newscore = $currscore - 1;
  
  $sql = "update " . tbl_quote . " set " . col_score . "=" . $newscore . " where " . col_qstat . "=" . stat_appr . " and " . col_qid . "=" . $qid . ";";
  
  $res2 = @mysql_query($sql, $dbconn);
  
  if (debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }
  
  return $res2;

} /* scorequotedown() */

/********************************************

Function to get a bunch of quotes

params: sort order, offset

********************************************/

function getgroupquote($sorder, $offset) {

  if(check_empty($offset)) {
  
    $offset = 50;
    
  }
  
  $sql = "select " . col_qid . ", " . col_qtext . ", " . col_score . " from " . tbl_quote . " where " . col_qstat . "=2  order by " . col_score . " " . $sorder . " limit 0, " . $offset . ";";

  // global $dbconn;
  
  $res = @mysql_query($sql);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }
  
  for ($i=0; $i < mysql_numrows($res); $i++) {
  
    $resarr[] = mysql_fetch_assoc($res);
  
  }
  
  return $resarr;  

} /* getgroupquote */

/********************************************

Function to get browse quotes, handles pages
through the offset parameter

********************************************/

function getbrowsequote($offset) {

  $sql = "select " . col_qid . ", " . col_qtext . ", " . col_score . " from " . tbl_quote;
  $sql .= " where " . col_qstat . "=2 order by " . col_qid . " asc limit " . $offset . ", " . qlim . ";";
  
  global $dbconn;
  
  $res = @mysql_query($sql);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }
  
  for ($i=0; $i < mysql_numrows($res); $i++) {
  
    $resarr[] = mysql_fetch_assoc($res);
  
  }
  
  return $resarr;

} /* getbrowsequote */

/********************************************

Function to mark a quote as "sucks" - as in
mark it for review

********************************************/

function quotesucks($qid) {

  $sql = "update " . tbl_quote . " set " . col_mksk . "=1 where " . col_qid . "=" . $qid . ";";
  
  global $dbconn;
  
  $res = @mysql_query($sql);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }
  
  return $res;    

} /* quotesucks() */

/********************************************

Function to retrieve quotes from a search 
string

Params: Search String, Limit of quotes,
sort order (score/quote id)

SO:
0 = Quote ID/Number
1 = Score

********************************************/

function getsearchquote($srchstr, $lim, $so) {

  // get the sort order entered

  switch($so) {
  
    case 0:
    
      $so = col_qid . " asc";
      
    break;
    
    case 1:
    
      $so = col_score . " desc";
      
    break;
    
    default:
    
      $so = col_qid . " asc";
    
    break;
    
  }
  
  $sql = "select " . col_qid . ", " . col_qtext . ", " . col_score . " from " . tbl_quote;
  $sql .= " where " . col_qstat . "=" . stat_appr . " and " . col_qtext . " like '%" . $srchstr . "%' order by " . $so . " limit 0, " . $lim . ";";

  global $dbconn;
  
  $res = @mysql_query($sql);
  
  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    
  }
  
  for ($i=0; $i < mysql_numrows($res); $i++) {
  
    $resarr[] = mysql_fetch_assoc($res);
  
  }
  
  return $resarr;

} /* getsearchquote */

/********************************************

Function to retrieve a list (array) of all
approved quotes in the system

********************************************/

function getallappquote() {

  $sql = "select " . col_qid . " from " . tbl_quote . " where " . col_qstat . "=" . stat_appr . " order by " . col_date . ";";
 
  global $dbconn;
  
  $res = @mysql_query($sql, $dbconn);
  
  for ($i=0; $i < mysql_numrows($res); $i++) {
  
    $resarr[] = mysql_result($res, $i, col_qid);
  
  }

  if(debug == 1) {
  
    debug_sqlfnc($sql, mysql_error());
    debug_array($resarr);
  
  }
  
  return $resarr;
  
}

/********************************************

Function to retrieve a set of random quotes
from the database

********************************************/

function getrandquote() {

  // build array of approved quotes, then pick random ids out of that array and build the SQL
  // for that and get the regular text, etc.

  $qlist = getallappquote(); // list of all quotes that are approved
 
  if (!is_array($qlist)) { return false; }

 
  $randlist = array_rand($qlist, qlim); // grab qlim number of array keys from big list

  for ($i=0; $i < count($randlist); $i++) {
  
    /* 
      build an array of actual quoteids from the
      full list, getting the quoteid value using the randomnly
      generated key array ($randlist)    
    */
    
    $quotelist[$i] = $qlist[$randlist[$i]];    
  
  }

//  debug_array($quotelist);

//  debug_array($randlist);

  if(count($quotelist) > 0) {

    // $q will be the final list of quotes to ask the database for

    $q = implode(",", $quotelist);
    // these babies could use up a fair whack of memory I bet
    unset($randlist, $qlist, $quotelist);
        
  } else {
  
    // this will only happen if there are no quotes
    
    return false;
    
  }

  $sql = "select * from " . tbl_quote . " where " . col_qid . " in (" . $q . ");";
  
  $qres = doquery_fa($sql); 

  if(debug == 1) {
    
      debug_sqlfnc($sql, mysql_error());
      debug_array($qres);
    
  }  
  
  return $qres;

} /* getrandquote */

?>
