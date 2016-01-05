<!--
Date Submitted April 1 2013
-->
<?php
//user
function user(){
	$username = $_SESSION['user'];
	//echo $username;
	$link = new Connect;
    $myconn = $link->connect();
	$sql_query = "SELECT username,role FROM users WHERE username = '$username'";
	$result = mysqli_query($myconn, $sql_query) or die('query failed'. mysql_error());
	$row = mysqli_fetch_assoc($result);
	$role = $row['role'];
	$user = array($username,$role);
	return $user;
}
//header
function head(){
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"CSS/add.css\" />";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"CSS/footer.css\" />";
	echo "<title>" . "</title>";
}
///menu
class Menu{
	public $user;
	public $array;
	function menu(){
		$user=user();
		$array = array("<a href=\"add.php\">Add</a>","<a href=\"view.php\">View All</a>","User: ".$user[0],"Role: ".$user[1],"<a href=\"logout.php\">Logout</a>");
		echo "<ul>\n";
		for($i=0;$i < count($array);$i++){
		   echo "<li>".$array[$i]."</li>\n";
		}
	}
}
//Footer
function footer(){
	echo "<p>Copyright (". date('Y') .") Fedor Barannik </p>";
}
//connects to database
class Connect{
  private $lines;
  private $uid;
  private $pw;
  private $dbserver;
  private $dbname;
  private $link;
  function connect(){
    $this->lines= file('pass');
    $uid = trim($this->lines[0]);
    $pw = trim($this->lines[1]);
    $dbserver = trim($this->lines[2]);
    $dbname = trim($this->lines[3]);
    $this->link = mysqli_connect($dbserver,$uid, $pw, $dbname)
                or die('Could not connect: ' . mysql_error());
    return $this->link;
  }
}
function data(){
	        $itemName =mysql_real_escape_string(stripcslashes(trim($_POST['ItemName'])));
			$supplier =mysql_real_escape_string(stripcslashes(trim($_POST['Supplier'])));
			$description =mysql_real_escape_string(stripcslashes(trim($_POST['Description'])));
	    	$numOnHand = mysql_real_escape_string(stripcslashes(trim($_POST['NumOnHand'])));
		    $reorder = mysql_real_escape_string(stripcslashes(trim($_POST['Reorder'])));
			$cost = mysql_real_escape_string(stripcslashes(trim($_POST['Cost'])));
			$sellPrice = mysql_real_escape_string(stripcslashes(trim($_POST['SellPrice'])));
			$sale = mysql_real_escape_string(stripcslashes(trim($_POST['Sale'])));
			$deleted = 'n';
			if($sale == 'on'){
				$sale='y';
			}else{
			  $sale='n';
			}
			$link = new Connect;
 			$myconn = $link->connect();
	        $sql_query = "INSERT INTO inventory (itemName,supplier,descrip,onhand,reorder, cost , price, sale, deleted) VALUES('" . $itemName . "', '" . $supplier . "', \"" . $description . "\", '" . $numOnHand . "', '" . $reorder . "', '" . $cost . "', '" . $sellPrice . "', '" . $sale . "', '" . $deleted . "')";
					//Run our sql query
 		    $result = mysqli_query($myconn, $sql_query) or die('query failed'. mysql_error());
 		    echo $sql_query;
}
function display(){
	$link = new Connect;
 	$myconn = $link->connect();
	// Get all records now in DB
	if(!empty($_GET)){
		$sql_query = "SELECT * FROM inventory ORDER BY ".$_GET['orderby']." ASC";
	}else{
     $sql_query = "SELECT * FROM inventory ORDER BY id ASC";
	}
	//Run our sql query
 	$result = mysqli_query($myconn, $sql_query) or die('query failed'. mysql_error());
    while($row = mysqli_fetch_assoc($result)){
       print "<tr>\n"."<td>" .'<a href="add.php?id2='. $row['id'].'">'.$row['id']."</a></td>\n"
	                 . "<td>" . $row['itemName']. "</td>\n"
					 . "<td>" .$row['supplier']. "</td>\n"
					 . "<td>" .$row['descrip']. "</td>\n"
					 . "<td>". $row['onhand']. "</td>\n"
					 . "<td>" .$row['reorder'] . "</td>\n"
					 . "<td>" .$row['cost'] . "</td>\n"
					 . "<td>" .$row['price'] . "</td>\n"
					 . "<td>" .$row['sale'] ."</td>\n"
					 .  "<td>" .$row['deleted'] ."</td>\n"
					 . "<td>";
        if($row['deleted']==='n'){
         echo '<a href="delete.php?id=' . $row['id'] .'">Delete'. '</a></td>' . "</tr>\n";
     }else{
     	echo '<a href="delete.php?id=' . $row['id'] .'">Restore'. '</a></td>' . "</tr>\n";
     }

    }
}

?>
