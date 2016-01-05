<!--
Date Submitted April 1 2013
-->
<?php
    require_once 'library.php';
	session_start();
	if(!$_SESSION['user']){
	header("location:login.php");
	die();
    }else{
    	user();
    }
?>
<!DOCTYPE >
<html>
	<head>
		<?php
		head();
		?>
		<body>
			<h1>Fedor's Gun Store</h1>
			<img src="img/logo.jpg" width="400px" />
			<div class="menu">
				<form method="post">
				<ul>
				<?php
				require_once 'library.php';
				$menu=new Menu;
				?>
				<li>Search in the description:<input type="text" name="Search" value="<?php if (isset($_POST['Search'])) {echo $_POST['Search'];} if (isset($_COOKIE['Search'])) {echo $_COOKIE['Search'];}?>"/><input type="Submit"/></li>
				</ul>
				</form>
			</div>
			<?php
			//test
			//print_r($_GET);
			//echo $_GET['orderby'];
			?>
			<div class=".ta">
				<table>
					<tr>
						<th><a href="view.php?orderby=id">ID</a></th>
						<th><a href="view.php?orderby=itemName">Item Name</a></th>
						<th><a href="view.php?orderby=supplier">Supplier</a></th>
						<th><a href="view.php?orderby=descrip">Description</a></th>
						<th><a href="view.php?orderby=onhand">Number ON Hand</a></th>
						<th><a href="view.php?orderby=reorder">Reorder Level</a></th>
						<th><a href="view.php?orderby=cost">Cost</a></th>
						<th><a href="view.php?orderby=price">Price</a></th>
						<th><a href="view.php?orderby=sale">On Sale</a></th>
						<th><a href="view.php?orderby=deleted">Delete?</a></th>
						<th><a href="view.php?orderby=deleted">Delete/Restore</a></th>
					</tr>
					<?php
					if($_POST && !empty($_POST ) || isset($_COOKIE['Search'])){//Search
					 $search = mysql_real_escape_string(stripcslashes(trim($_POST['Search'])));
					 if(isset($_COOKIE['Search'])){
					 	setcookie("Search",$search);
					 	$search = $_COOKIE['Search'];
					 }
					 $link = connect();
					 $sql_query = "SELECT * FROM inventory WHERE upper(descrip) LIKE upper('%" .  $search."%')";
					 //echo $sql_query;//test
					 $result = mysqli_query($link, $sql_query) or die('query failed'. mysql_error());
					 $count=mysqli_num_rows($result);
						 if($count > 0){
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
						}else{
						  echo"<tr><td colspan=\"11\">No entry</td></tr>";
						 }
					}else{
					 display();
					}
					?>
				</table>
			</div>
			<div id="copyright">
				<?php
				footer();
				?>
			</div>
		</body>
</html>
