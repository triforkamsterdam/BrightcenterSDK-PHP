<html>
<head>
<title>BrigthcenterSDK</title>
</head>
<body style="background-color: orange;">
<div id="container" style="width: 500px; margin: auto; padding: auto; background-color: orange">
	<div style="width: 200px; height: auto; margin: auto; padding: auto; background-color: green; margin-top: 100px;" >
		<form id='login' action='login.php' method='post' accept-charset='UTF-8' style="background-color: white; border-radius:5px;">
		<fieldset >
		<legend>Login to Brightcenter</legend>
		<input type='hidden' name='submitted' id='submitted' value='1'/>

		<label for='username' >Username:</label>
		<input type='text' name='username' id='username'  maxlength="50" />
		 		<br>
		<label for='password' >Password:</label>
		<input type='password' name='password' id='password' maxlength="50" />
		<br>
		<input type='submit' name='Submit' value='Submit' />
		 
		</fieldset>
		</form>
	<div>
</div>

<?php
?>

</body>
</html>