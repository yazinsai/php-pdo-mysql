<?php
/*
 * Responsible for communicating directly with the database; all queries are
 * passed through this class for processing.
 * 
 */

$CONFIG = array(
	"database" => array(
		"host"=> 'localhost',
		"database" => 'DB_NAME',
		"username" => 'username',
		"password" => 'password'
		)
	);

class Database{
	# Constants
	const FORMAT_SQLDATETIME="Y-m-d H:i:s"; // The date('format') used for storing dates in MySQL
    
    # Attributes
    protected $_connection;
    protected $_statement;

    # Methods
    public function __construct() {
        /* 
         * Constructor establishes the connection with the MySQL database
         */
        $this->_connection = new PDO("mysql:host=".$CONFIG["database"]["host"].";dbname=".$CONFIG["database"]["database"],
                $CONFIG["database"]["username"],$CONFIG["database"]["password"], array(
                    PDO::ATTR_PERSISTENT=>true,
                    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
                        ));
        $this->query("SET NAMES 'utf8';SET CHARACTER SET utf8;");
    }

    public function __destruct() {
        /* 
         * Destructor closes the connection with teh MySQL database by clearing
         * the connection
         */
        if($this->_connection) {
            $this->_connection=null;
        }
    }
    
    public function query($aQuery,$aParams=NULL) {
        /* 
         * Processes a PDO::prepare($query) and a PDO::execute($params)
         * 
         * Example:
         * $db->query("SELECT * FROM `users` WHERE `UserId`=:thisUserId",array(":thisUserId" => $userId, [...] ));
         * 
         * This would allow you to execute the query using the UserId as a Named Parameter. This is more
         * secure than embedding the variables directly in the SQL script, and is more memory efficient.
         */
        if ($this->isConnected()) {
            // The connection is live; send the query through
            $this->_statement=$this->_connection->prepare($aQuery);
            $this->_statement->execute($aParams);
            return true;
        }
        else {
            // The connection is not ready; error out
            throw new Exception("The database connection is not alive. Can't execute query();");
            return false;
        }
    }

    public function fetch($aType='assoc') {
        /* 
         * Returns a single row result as an array (either numeric or associative).
         * Can also be used to go through the entire dataset as part of a while loop,
         * one recordset at a time.
         * 
         * PREREQUISITE: Requires that you've called the query() method before.
         */
        if(isset($this->_statement)) {
            // Looks like the query() method was indeed called; proceed
            if($aType == 'array')
                return $this->_statement->fetch(PDO::FETCH_NUM);
            else if($aType == 'assoc')
                return $this->_statement->fetch(PDO::FETCH_ASSOC);
        }
        else {
            // Looks like the query() method was not called before; raise error
            throw new Exception("The query() method must be called before using fetch();");
            return false;
        }
    }

    public function fetchAll($aType='assoc') {
        /* 
         * Returns the entire query result as a complete array (either numeric or 
         * associative).
         * 
         * PREREQUISITE: Requires that you've called the query() method before.
         * 
         * NOTE: The fetchAll() statement only works once. If you wish to do another
         * fetchAll(), you need to execute() the original query again.
         */
        if(isset($this->_statement)) {
            // Looks like the query() method was indeed called; proceed
            if($aType == 'array')
                return $this->_statement->fetchAll(PDO::FETCH_NUM);
            else if($aType == 'assoc')
                return $this->_statement->fetchAll(PDO::FETCH_ASSOC);    
        }
        else {
            // Looks like the query() method was not called before; raise error
            throw new Exception("The query() method must be called before using fetchAll();");
            return false;
        }
    }
    
    public function isConnected() {
        /* Returns a boolean indicating whether the connection is established or
         * not.
         */
        if($this->_connection)
            return true;
        else 
            return false;
    }
    
    public function lastInsertId() {
        /* 
         * Returns the value for the last AutoIncrement change in an Insert SQL
         * statement
         */
        return $this->_connection->lastInsertId();
    }

    private function cleanInput($aInput) {
        # Untested.
        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );

        $output = preg_replace($search, '', $aInput);
        return $output;
    }
}

/* numRows() is not supported in PDO; as such the fetch() method has to be called and stored as an array. 
 * The number of rows can then be found using the .count() method for arrays.
 */

/* EXAMPLE:
 * 
 * Using fetch():
    $myDB = new Database();
    $myDB->query("SELECT * FROM users WHERE `UserId`=:userId",array(":userId" => "2"));
    echo print_r($myDB->fetch());
 * 
 * Using fetchAll():
    $info=$db->fetchAll();
    foreach ($info as $index => $rowdetails)
        echo "db[$index]={$rowdetails['Type']};<br/>";
 * 
 */
?>