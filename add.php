<!--
Date Submitted April 1 2013
-->
<?php
session_start();
if (!$_SESSION['user']) {
	header("location:login.php");
	die();
}
?>
<!DOCTYPE >
<html>
	<head>
		<?php
		require_once 'library.php';
		head();
		?>
	</head>
	<body>
		<?php
		//Validates Infrormation from the form
		$itemNameErr ="";
		$supplierErr = "";
		$descriptionErr = "";
		$numOnHandErr = "";
		$reorderErr = "";
		$costErr = "";
		$sellPriceErr = "";
		$dataValid = TRUE;
		$itemName = trim($_POST['ItemName']);
		//submit with post
		if($_POST){//test
		if($_POST['ItemName'] == ""){
		$itemNameErr = "Error - you must fill in item name";
		$dataValid = FALSE;
		}
		if(!preg_match('/^[A-Za-z][A-Za-z\s]*$/', $itemName)){
		$itemNameErr = "Error - Item name can only include leters and spaces";
		//echo "Test";
		$dataValid = FALSE;
		}
		if($_POST['Supplier'] == ""){
		$supplierErr  = "Error - you must fill in supplier";
		$dataValid = FALSE;
		}
		if(!preg_match('/^\s*[A-Za-z\-][A-Za-z\-\s]*$/',$_POST['Supplier'])){ /////FIX THIS
		$supplierErr  = "Error - Supplier can only include leters,dashes and spaces";
		$dataValid = FALSE;
		}
		if($_POST['Description'] == ""){
		$descriptionErr  = "Error - you must fill in Description";
		$dataValid = FALSE;
		}
		if(!preg_match('/^[A-Za-z0-9][A-Za-z0-9\s\-\'\,\.\n]*$/',$_POST['Description'])){ /////FIX THIS
		$descriptionErr = "Error-Description can only include letters, digits, \".\" \",\" \" ' \" \"-\" and spaces ";
		$dataValid = FALSE;
		}
		if($_POST['NumOnHand'] == ""){
		$numOnHandErr  = "Error - you must fill in number on hand";
		$dataValid = FALSE;
		}
		if(!preg_match('/(^\s*[0-9]{1,3}$)/',$_POST['NumOnHand'])){ /////FIX THIS
		$numOnHandErr  = "Error - Number on hand can only be digits";
		$dataValid = FALSE;
		}
		if($_POST['Reorder'] == ""){
		$reorderErr  = "Error - you must fill in reorder level";
		$dataValid = FALSE;
		}
		if(!preg_match('/^(^\s*[0-9]{1,3}$)/',$_POST['Reorder'])){ /////FIX THIS
		$reorderErr = "Error - Reorder  can only be digits";
		$dataValid = FALSE;
		}
		if($_POST['Cost'] == ""){
		$costErr  = "Error - you must fill in cost";
		$dataValid = FALSE;
		}
		if(!preg_match('/^\s*[0-9]*(\.)?[0-9]?[0-9]?$/',$_POST['Cost'])){ /////FIX THIS
		$costErr = "Error - Cost monetary can only bee numbers ex. 1.00";
		$dataValid = FALSE;
		}
		if($_POST['SellPrice'] == ""){
		$sellPriceErr = "Error - you must fill in price";
		$dataValid = FALSE;
		}
		if(!preg_match('/^\s*[0-9]*(\.)?[0-9]?[0-9]?$/',$_POST['SellPrice'])){ /////FIX THIS
		$sellPriceErr = "Error - Selling price can only bee numbers ex. 1.00";
		$dataValid = FALSE;
		}
		}
		if(!empty($_GET)){///loads all entries into the form
		$link = new Connect;
        $myconn = $link->connect();
		$sql_query2 = "SELECT * FROM inventory WHERE id =". $_GET['id2'];
		$result2 = mysqli_query($myconn, $sql_query2) or die('query failed'. mysql_error());
		$row = mysqli_fetch_assoc($result2);
		}
		if($_POST && $dataValid){
		///puts data into database
		if(!empty($_GET)){///to edit entry
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
		$sql_query = "UPDATE inventory SET itemName='".$itemName."',supplier='".$supplier."',descrip=\"".$description."\",onhand='".$numOnHand."',reorder='".$reorder."', cost='".$cost."',price='".$sellPrice."',sale='". $sale."' WHERE id =". $_GET['id2'];
		//echo $sql_query;//test
		$result = mysqli_query($myconn, $sql_query) or die('query failed'. mysql_error());
		header('Location: view.php');
		}else{
		data();
		header('Location: view.php');
		}

		?>

		<?php
        }else{
		?>
		<h1>Fedor's Gun Store</h1>
		<img src="img/logo.jpg" width="400px" />
		<div class="menu">
			<form>
				<?php
                  //calls menu
                  $menu=new Menu;
				?>
			</form>
		</div>

		<!--Body-->
		<div class="text">
			<form method="post" action="">
				<table>
					<?php
					if(!empty($_GET)){
						echo "<tr>";
						echo  "<td>ID:<input readonly=\"readonly\" value=\"". $_GET['id2']."\"/></td>";
						echo "</tr>";
					}
					?>
					<tr>
						<td> Item name:<input type="text"  name = "ItemName" value="<?php if (isset($_POST['ItemName'])) {echo $_POST['ItemName'];}else{echo $row['itemName'];} ?>" /></td>
						<td> <?php if ($itemNameErr != "") {echo "<p class=\"red\">" . $itemNameErr . "</p>"; }?></td>
					</tr>
					<tr>
						<td>Supplier: <input type="text" name="Supplier" value="<?php if (isset($_POST['Supplier'])) {echo $_POST['Supplier']; }else{echo $row['supplier'];}?>"/></td>
						<td><?php if ($supplierErr != "") {echo "<p class=\"red\">" . $supplierErr . "</p>";}?></td>
					</tr>
					<tr>
						<td>Description:<textarea rows="8" cols="23" name="Description" ><?php if (isset($_POST['Description'])) { echo $_POST['Description'];}else{echo $row['descrip'];} ?></textarea></td>
						<td><?php if ($descriptionErr != "") {echo "<p class=\"red\">" . $descriptionErr . "</p>"; } ?></td>
					</tr>
					<tr>
						<td>Number on hand:<input type="text" name="NumOnHand" value="<?php if (isset($_POST['NumOnHand'])) {echo $_POST['NumOnHand'];}else{echo $row['onhand'];}?>" /></td>
						<td><?php if ($numOnHandErr != "") {echo "<p class=\"red\">" . $numOnHandErr . "</p>"; } ?></td>
					</tr>
					<tr>
						<td>Reorder level:<input type="text" name="Reorder" value="<?php if (isset($_POST['Reorder'])) {echo $_POST['Reorder']; }else{echo $row['reorder'];}?>" /></td>
						<td><?php if ($reorderErr != "") {echo "<p class=\"red\">" . $reorderErr . "</p>"; } ?></td>
					</tr>
					<tr>
						<td>Cost:<input type="text" name="Cost" value="<?php if (isset($_POST['Cost'])) {echo $_POST['Cost'];}else{echo $row['cost'];}?>" /></td>
						<td><?php if ($costErr) {echo "<p class=\"red\">" . $costErr . "</p>"; } ?></td>
					</tr>
					<tr>
						<td>Selling price:<input type="text" name="SellPrice" value="<?php if (isset($_POST['SellPrice'])) {echo $_POST['SellPrice']; }else{echo $row['price'];}?>" /></td>
						<td><?php if ($sellPriceErr) {echo "<p class=\"red\">" . $sellPriceErr . "</p>"; } ?></td>
					</tr>
					<tr>
						<td>On Sale:<input type="checkbox" name="Sale" <?php if (isset($_POST['Sale'])) {echo "checked"; } if(isset($row['sale']) && $row['sale'] == 'y') {echo "checked";}?>/></td>
						<td></td>
					</tr>
					<tr>
						<td><input type="Submit" /></td>
					</tr>
				</table>
			</form>
		</div>
		<div id="copyright">
			<?php
			footer();
			?>
		</div>
		<?php
		}
		?>
	</body>
</html>
