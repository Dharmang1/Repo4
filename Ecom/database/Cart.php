<?php

// This class handles the shopping cart functionality
class Cart
{
    public $db = null;

    // Constructor to initialize the database connection
    public function __construct(DBController $db)
    {
        // Check if the database connection is set
        if (!isset($db->con)) {
            // If not, return null
            return null;
        }
        // If set, assign the database connection to the class property
        $this->db = $db;
    }

    // Method to insert data into the cart table
    public function insertIntoCart($params = null, $table = "cart")
    {
        // Check if the database connection is available
        if ($this->db->con != null) {
            // Check if parameters are provided
            if ($params != null) {
                // Create a string of column names
                $columns = implode(',', array_keys($params));
                // Create a string of values
                $values = implode(',', array_values($params));

                // Construct the SQL query
                $query_string = sprintf("INSERT INTO %s(%s) VALUES(%s)", $table, $columns, $values);

                // Execute the query
                $result = $this->db->con->query($query_string);
                return $result;
            }
        }
    }

    // Method to add items to the cart
    public function addToCart($userid, $itemid)
    {
        // Check if both user ID and item ID are provided
        if (isset($userid) && isset($itemid)) {
            // Create an associative array with user ID and item ID
            $params = array(
                "user_id" => $userid,
                "item_id" => $itemid
            );

            // Insert the data into the cart
            $result = $this->insertIntoCart($params);
            // If successful, reload the page
            if ($result) {
                header("Location: " . $_SERVER['PHP_SELF']);
            }
        }
    }

    // Method to delete an item from the cart
    public function deleteCart($item_id = null, $table = 'cart')
    {
        // Check if an item ID is provided
        if ($item_id != null) {
            // Execute the delete query
            $result = $this->db->con->query("DELETE FROM {$table} WHERE item_id={$item_id}");
            // If successful, reload the page
            if ($result) {
                header("Location:" . $_SERVER['PHP_SELF']);
            }
            return $result;
        }
    }

    // Method to calculate the total price of items in the cart
    public function getSum($arr)
    {
        // Check if an array is provided
        if (isset($arr)) {
            // Initialize the sum
            $sum = 0;
            // Loop through the array and calculate the sum
            foreach ($arr as $item) {
                $sum += floatval($item[0]);
            }
            // Format the sum to two decimal places
            return sprintf('%.2f', $sum);
        }
    }

    // Method to get the item IDs from the cart
    public function getCartId($cartArray = null, $key = "item_id")
    {
        // Check if an array is provided
        if ($cartArray != null) {
            // Extract the item IDs from the array
            $cart_id = array_map(function ($value) use ($key) {
                return $value[$key];
            }, $cartArray);
            return $cart_id;
        }
    }

    // Method to move an item to the wishlist
    public function saveForLater($item_id = null, $saveTable = "wishlist", $fromTable = "cart")
    {
        // Check if an item ID is provided
        if ($item_id != null) {
            // Construct the SQL query to move the item to the wishlist and delete it from the cart
            $query = "INSERT INTO {$saveTable} SELECT * FROM {$fromTable} WHERE item_id={$item_id};";
            $query .= "DELETE FROM {$fromTable} WHERE item_id={$item_id};";

            // Execute the multiple queries
            $result = $this->db->con->multi_query($query);

            // If successful, reload the page
            if ($result) {
                header("Location :" . $_SERVER['PHP_SELF']);
            }
            return $result;
        }
    }
}
