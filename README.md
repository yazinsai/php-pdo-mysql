PHP PDO MySQL Wrapper
=====================

This PHP Class makes it ridiculously easy to use Prepared Statements
in your MySQL projects.

## How does it work?

Just start by `include`ing the class into your document:

    include Database.php

Then use it like so:

    $DB = new Database();
    $DB->query("SELECT * FROM `mytable` WHERE `myfield`=:myvalue",
        array(":myvalue"=>$myvalue));
    $results = $DB->fetchAll();

You now have all of the results in your `$results` variable.

## Setup

Just edit the `$CONFIG` variable in the `Database.php` file with the
connection details:

    $CONFIG = array(
    	"database" => array(
			"host"=> 'localhost',
			"database" => 'DB_NAME',
			"username" => 'username',
			"password" => 'password'
			)
		);

## Why use this class?

Coz it makes yo' life a whole lot easier.