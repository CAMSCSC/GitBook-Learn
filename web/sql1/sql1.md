# SQL Injections

### What is SQL?

SQL or Structured Query Language is a special language used to communicate with a database (usually located on the web server). It is specifically designed to manage data located in a relational database management system (DBMS). Most web servers or database systems utilize SQL databases. Each version of the SQL databases is different and some versions are proprietary. However, all of them contain quite a few inherent commands.

### What can SQL do?

* Retrieve records from a database (articles, posts, comments, etc.)
* Insert records into a database (registration, grades, survey response, etc.)
* Update pre-existing records (change of address, preference change, Le Fail Counter, etc.)
* Create new databases and tables
* Execute malicious queries to DROP all of the tables (it's very possible)
* Many other useful tasks not mentioned here

### Basic Structure

A server contains databases. Each database contains tables. Each table contains rows and columns. I hope you know what a table looks like. But to be safe, I'll show you an example. For the purpose of this lesson, let's create an imaginary database called **tutorial_1** with a table named **secrets**. The data below shows the structure of the table.



ID  |  Name      |  Secret  
----|------------|----------  
1   |  Karina    |  uBarr   
2   |  Bob       |  chicken 
3   |  Mark      |  600     
4   |  Andy      |  windows 
5   |  Nicolas   |  sqlsql  

As you can see, the table contains three columns and five rows. I explicitly stated this so you do not get confused when I refer to columns or rows later on.

### Queries

Queries allow users to tell the DBMS what the desired data is. The DBMS will plan and optimize the search operations, which makes this extremely easy for the users. Note: most queries take under a few milliseconds. The most common and crucial (might be debatable?) statement in SQL is **SELECT**. Examples and general formatting is shown below. Quotes are there for clarification purposes. Database and column names do no need single quotes around them (unless you want case sensitivity). However, text values, especially those after WHERE optional clause do require single quotes.

```sql
SELECT 'column_name(s)' FROM 'table(s)' WHERE 'criterion'
```

Below are some sample queries.

```sql
--Get all columns from the table.
SELECT * FROM secrets

--Get only the column name
SELECT Name FROM secrets

--Get the name of the person whose ID is 4
SELECT Name FROM secrets WHERE ID=4

--Whose secret is uBarr?
SELECT Name FROM secrets WHERE Secret='uBarr'

--Get the ID if the person is Bob or his secret 600
SELECT ID FROM secrets WHERE Name='Bob' AND Secret='600'
--Note that 600 is not a number because it is in a text field 
```

### MySQL and PHP

The fun part begins here. You can execute queries on an SQL server, but it would be pointless if there was no integration or interaction with website viewers and physical web pages. Here is where PHP and MySQL comes into play. As I mentioned before, there are many versions of SQL databases (Oracle, MySQL, Microsoft SQL Server, PostgreSQL, DB2, SQlite, etc.). However, the one we will be focusing on is MySQL. MySQL is open source and it functions well with PHP. What makes it even better is that the syntax is easy to understand and horrible queries leave a lot of room for exploitations.

We already looked at basic queries. Now let's look at how those queries are used with PHP.

```php
<?php

//Create a new database
//$database = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );
$database = new mysqli( 'localhost', 'DB_USER', 'DB_PASS', 'tutorial_1' );

//For debugging purposes in case the connection failed
if( !$database )
{
	die( 'MySQL: Wrong credentials' );
}

//Execute a query
$result = $database->query
(
	"SELECT Secret FROM secrets"
);

//For debugging purposes in case the query failed
if( !$result )
{
	die( 'MySQL: Syntax error' );
}

//Print out all of the secrets in the order in which they were retrieved
while( $row = mysqli_fetch_assoc( $result ) )
{
	echo( "Secrets: " . $row['Secret'] );
}
?>
```

### Further Readings and References

"Back to Basics: Writing SQL Queries" - http://robots.thoughtbot.com/back-to-basics-sql

"SQL Injection" - http://www.w3schools.com/sql/sql_injection.asp
