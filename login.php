<!--
Date Submitted April 1 2013
-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
		require_once 'library.php';
		?>
	</head>
	<body>
		<?php
		$errors="";
		if(!empty($_GET)){
			echo "<form method=\"post\" action=\"\">";
			echo "Email <input type=\"text\" name=\"email\" />\n";
			echo "\t<input type=\"submit\"/>\n";
			echo "<a href=\"login.php\">Go back</a>";
			echo "</form>\n</body>\n</html>";
			if($_POST){
				$email = $_POST['email'];
				$email = stripcslashes($email);
				$email = mysql_real_escape_string($email);
				$link = new Connect;
 				$myconn = $link->connect();
				$sql_query = "SELECT passwordHint,username FROM users WHERE username = '$email'";
				$result = mysqli_query($myconn, $sql_query) or die('query failed'. mysql_error());
				$row = mysqli_fetch_assoc($result);
				if(!empty($row['passwordHint'])){
					mail($row['username'], 'Password hint(Fedor Barannik)', $row['passwordHint']);
					header("location:login.php");
				}else{
					header("location:login.php");
				}
			}
		}else{
			if($_POST){
				//connects to database
			    $link = new Connect;
 				$myconn = $link->connect();
				//username and password sent from form
				$username = $_POST['username'];
				$password = $_POST['password'];
				//to protect MySQL injection
				$username = stripcslashes($username);
				$password = stripcslashes($password);
				$username = mysql_real_escape_string($username);
				$password = mysql_real_escape_string($password);
				$sql_query = "SELECT * FROM users WHERE username = '$username'";
				//Run our sql query
				$result = mysqli_query($myconn, $sql_query) or die('query failed'. mysql_error());
				$row = mysqli_fetch_assoc($result);
				$count = mysqli_num_rows($result);
				//echo "p".$row['password'];//test
				//echo "u".$row['username'];//test
				if($count == 1 && crypt($password,substr($row['password'],0,12)) == $row['password']){
					//registers username and password
					session_start();
					$_SESSION['user']=$username;
					header("location:view.php");
					//echo "you are in". $_SESSION['user'];
				}else{
					$errors = "Wrong username or password";
				}
			}
		?>
		<form method="post" action="">
		<table>
			<tr>
				<td>Username:<input type="text" name="username" /></td>
				<td><?php if ($errors != "") {echo "<p class=\"red\">" . $errors . "</p>"; } ?></td>
			</tr>
			<tr>
				<td>Password:<input type="password" name="password"/></td>
			</tr>
			<tr>
				<td><input type="submit"/></td>
			</tr>
			<tr>
				<td><a href="login.php?id=1">Forgot your password?</a></td>
			</tr>
		</table>
		</form>
	</body>
</html>
<?php
		}
		?>
