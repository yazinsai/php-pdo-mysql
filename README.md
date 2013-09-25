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
