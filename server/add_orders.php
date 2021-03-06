<html>

<head>
    <!--meta charset="utf-8"-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Making Order</title>
    <link rel="stylesheet" type="text/css" href="../style/quickServeStyle.css" />
</head>

<body class="bgChef">
    <h1 class="orderSuccess">ORDER SUCCESFUL</h1>

    <?php 

$servername = "localhost";
$username = "root";
$password = "";
$database = "restaurant_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
} 
$section_id = $_POST["section_id"];
//echo number_format($section_id);

$query = "INSERT INTO orders (section_id, order_complete) VALUES (".$section_id.", false)";
//echo $query."<br><br>";
$insert_order_results = $conn->query($query);
if (!$insert_order_results) 
	echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
else
	$query = "SELECT LAST_INSERT_ID()";
	$order_id_results = $conn->query($query);
	$order_id_results->data_seek(1);
	$order_id = $order_id_results->fetch_array(MYSQLI_NUM);
	$order_id = $order_id[0];
	//echo "Order ID: ".$order_id[0];
	
	
//echo $insertOrderResults;
	$insert_successful = true;
	foreach ($_POST as $x => $x_value) 
	{
		// So that you dont accidentally add the section_id to the order
		if ($x == "section_id")
			continue;
		$query = "INSERT INTO ordered_items (order_id, item_id, item_complete) VALUES (".$order_id.",".$x_value.",false)";
		$insert_order_item = $conn->query($query);
		if (!$insert_order_item) 
		{
			echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
			$insert_successful = false;
			break;
		}
	}
	if ($insert_successful)
	{
		echo "<div class='successfulOrderContainer'><h2 class='successfulTitle'><strong>Successfully Ordered: </strong></h2>";
		$query = "SELECT item_name FROM menu_items NATURAL JOIN ordered_items WHERE order_id =".$order_id; 
		$order_results = $conn->query($query);
		$rows = $order_results->num_rows;
		for ($j = 0; $j < $rows; ++$j)
		{
			$order_results->data_seek($j);
			$order_row = $order_results->fetch_array(MYSQLI_NUM);
			echo $order_row[0]."</strong><br>";
		}
	}
	
    echo "</div>"
	
	


?>

    <form action="select_section.php">
        <button class="bigAnotherOrderButton" type="submit">Make Another Order</button>
    </form>

    <form action="server_homepage.php">
        <button class="bigServerButton" type="submit">Server Main</button>
    </form>

</body>

</html>
