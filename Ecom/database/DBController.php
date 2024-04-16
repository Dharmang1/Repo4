<?php

// This class handles the database connection using MySQLi
class DBController
{
    // Database connection properties
    protected $host = 'localhost';  // Host name
    protected $user = 'root';       // Database user
    protected $password = '';       // Database password
    protected $database = "shopee"; // Database name

    // Connection property
    public $con = null;

    // Constructor to establish the database connection
    public function __construct()
    {
        // Attempt to connect to the database
        $this->con = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        
        // Check if the connection was successful
        if ($this->con->connect_error) {
            // If not, display an error message
            echo "Connection failed: " . $this->con->connect_error;
        }
    }

    // Destructor to close the database connection when the object is destroyed
    public function __destruct()
    {
        $this->closeConnection();
    }

    // Method to close the database connection
    protected function closeConnection()
    {
        // Check if the connection is still open
        if ($this->con != null) {
            // Close the connection
            $this->con->close();
            // Set the connection property to null
            $this->con = null;
        }
    }
}
