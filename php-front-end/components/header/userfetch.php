<!-- header-top.php -->

<?php
// Assuming you have a database connection already established
$config = array(
    'db_hostname' => 'localhost',
    'db_name' => 'reactcrud-non-jwt',
    'db_username' => 'root',
    'db_password' => '',
);

try 
{
    $connection = new PDO("mysql:host=" . $config['db_hostname'] . ";dbname=" . $config['db_name'], $config['db_username'], $config['db_password']);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} 
catch(PDOException $e) 
{
    die("Connection failed: " . $e->getMessage());
}


// Session start (if not already started)
session_start();

// Get userId from the session
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

// Check if userId is available
if ($userId) {
    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM customers WHERE id = :userId";
    $query = $connection->prepare($sql);
    $query->bindParam(':userId', $userId, PDO::PARAM_INT);
    $query->execute();
    
    // Fetch the user data
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists
    if ($user) {
        // Access user data, e.g., $user['username'], $user['email'], etc.
        $username = $user['username'];
        $userPhoto = $user['photo']; // Assuming there's a column named 'photo' in your 'customers' table
    } 
} 

?>
