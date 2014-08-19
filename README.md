PHP PDO MySQL Wrapper
=====================

This PHP Class makes it *ridiculously easy* to use Prepared Statements in your MySQL projects.

## How does it work?

Just start by including the class in your script:

```php
include Database.php
```
Now use it like so:

```php
$DB = new Database();
$DB->query("SELECT * FROM `mytable` WHERE `myfield`=:myvalue",
   array(":myvalue" => $myvalue));
$results = $DB->fetchAll();
```

You now have all of the results in your `$results` variable! You can iterate through the results like so:

```php
foreach($results as $result) {
   // $result contains an associative array with all the fields
   echo "Result: " . $result['myfield']
}
```

## Setup

Just edit the `$CONFIG` variable in the `Database.php` file with the
connection details:

```php
$CONFIG = array(
  "database" => array(
    "host"     => 'localhost',
    "database" => 'database',
    "username" => 'username',
    "password" => 'password'
    )
  );
```

## Why use this class?

Coz it makes yo' life a whole lot easier. And prepared statements are about 1,000,000 times more secure than plain SQL.
