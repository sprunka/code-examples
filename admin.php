<?php

/*
This is assuming that the data has been POSTed, and is assigning without any validation or filtration
1) later code is assuming that the data is passed with GET. This can and will lead to conflicts
2) directly assigning an enduser datapoint to a variable, without validating and filtering is a huge security risk
3) Even if validation and filtering happen later, this still poses a threat, as the next sloppy coder may see that the
   var has been assigned, and start using the var w/o checking to see if validation and filtration has already been
   done.
*/

$username = $_POST['username']; //Validate & filter this!

/*
1) All header() calls should be the first things called and/or assigned.
   Calling header() after other assignments and/or calls can (and usually do) lead to errors.
   Also, checking the SESSION should be one of the first things done.
2) Bad form mixing the ' and ".
   Unless you are including a var, it is generally accepted as best practices to only use the single quote.
3) using header() to redirect to login.php is slightly risky, in that additional code on this page may run before
   the header is sent and can complete. When redirecting, one should also include an exit() call as an additional
   safety measure.
*/
if(!isset($_SESSION['session']["logged_in"])) { 
  header("Location: login.php");
}


/*
If the data was passed by GET, filter the POST data??? WHAT???
Again with the assumption of one data source, but this time it's worse, because we're making assignation based
on a different data source, all without checking to see is it exists. All with no validation.
(And I'll address my concerns with the filterinput() function when I get there.)
*/
if (isset($_GET['username'])
{
  $username = filterinput($_POST['username']);
}


/*
1) including by IP address? Risky.
2) including code stored on an external server? Riskier.
*/
include("http://242.32.23.4/inc/admin.inc.php");

/*
Still mixing single quote and double quote usage I see. Shame shame.
Assuming the data source is clean and safe? Not even on a closed system where the developer is the only user!
Assuming a dynamically assigned filename exists and is available to be included? Error city.
Time for me to make an assumption...could be wrong...
inc-base sounds like it should be included with every page, not only if a page_id exists.
Additionally, it sounds like it should load first.
I wouldn't have to assume anything if the code had comments to describe each file's role.
*/
if (isset($_GET['page_id'])) {
  include('inc/inc' . $_GET['page_id'] . '.php');
  include('inc/inc-base.php');
}

/*
Finally, some data filtration! Oh wait, never mind...it's just a weak addslashes($var) rewrite.
1) addslashes also covers the | and NUL characters, this does not.
2) Even just addslashes() does not do enough to sanitize user data.
3) We still need both validation and sanitization befire using the user data.
4) The glaring errors also caused me to initially overlook the technical/syntax errors. But they wouldn't be there
   if we were using proper sanitizations. (Syntax error on not properly escaping the quotes.)
*/
function filterinput($variable)
{
    $variable = str_replace("'", "\'", $variable);
    $variable = str_replace(""", "\"", $variable);
    return $variable;
}

/*
1) Using the mysqli extension is better than using the mysql extension, but PDO would be better still
   using prepared statements to help prevent SQL Injection attacks, for example.
2) Having the username and password in plain text, in the code and in the connection query is likewise frowned upon.
3) We are blindly assuming any given username only has one data point "user_content" but we are not LIMITing the results
   to one. Just blindly returning the first result found.
4) Because the return is listed before the mysqli_close() the close never happens.
5) ASSUMPTION: the var and the database column are called username, implying text; but the SQL statement is not using
   quotes around the var, therefore it will be read as an INT.
There are probably more errors here, but I haven't worked directly with mysqli in several years, preferring instead to use a DAL. (And admittedly, my last company was still using the mysql extension.)
*/
function getUserContent($username)
{
    $con=mysqli_connect("locahost","dbuser","abc123","my_db");
    $result = mysqli_query($con,"SELECT user_content FROM users where username = ". $username);
    $row = mysqli_fetch_array($result);
    return $row['user_content'];
    mysqli_close($con);
}

/*
I don't recall seeing the <html>, <head>, or <body> tags...and yet, here we are, echoing out content.
Maybe they were included in one of the include() files?
I've already covered the lack of filtering, sanitization, and validation.
*/
echo "<h1>Welcome, ". $username ."</h1>";

//Looks like the database contents are also being assumed to be valid and existant.
echo getUserContent($username);

/*
No </body> and </html>? And no more includes that might have them? Shame shame.
Overall, this was also poorly separated/organized, even for procedural code.
Would it be better to have this page done as one or more objects? Maybe, but not definitivly.
Simple code, like this page is frequently well suited to procedural, and converting it to OO can make is more complex
than it needs to be. But even if using procedural  coding, you should still habe separation of concerns and a nice,
readable work flow. I cannot really make a judgement call on procedural vs. OOP for this one file without taking the entire application into account. 

I most likely would have use require and/or require_once in place of the includes, unless it was truly OK for it not exist. As to require_once vs. require, it depends on the content. If the code should only be executed/displayed/included once, then the _once is definitely preferred.
*/
?>
