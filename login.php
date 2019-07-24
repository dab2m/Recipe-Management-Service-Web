<?php
    include 'db.php';
    
    if(isset($_POST['username']) && isset($_POST['password'])) 
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM `kullanici` WHERE `username` = '$username' AND `password` = '$password'";
        $res = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($res);
        if(mysqli_affected_rows($db) > 0) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['user_id'] = $row['id'];
            header("location:anasayfa.php");
        }
    }
?>

<!DOCTYPE html>

<html lang="en">

<head>
<meta charset="utf-8"/>
<title>Login</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>


<link href="assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>


<link href="assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>

<link rel="shortcut icon" href="favicon.ico"/>
</head>

<body class="login">

<div class="logo">

</div>

<div class="menu-toggler sidebar-toggler">
</div>

<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form class="login-form" action="login.php" method="post">
		<h3 class="form-title">Login</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
			</div>
		</div>
		<div class="form-actions">
			<label class="checkbox"></label>
			<button type="submit" class="btn green-haze pull-right">
			Login <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
		<div class="create-account">
			<p>
			<a href="javascript:;" id="register-btn">Register here </a>
			</p>
		</div>
	</form>

	<form class="register-form" action="kayit.php" method="post">
		<h3>Sign Up</h3>

		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password"/>
			</div>
		</div>
		<div class="form-actions">
			<button id="register-back-btn" type="button" class="btn">
			<i class="m-icon-swapleft"></i> Login </button>
			<button type="submit" id="register-submit-btn" class="btn green-haze pull-right">
			 Register <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
	</form>
</div>

<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>

<script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/login.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {    
  Metronic.init(); 
  Layout.init(); 
  Login.init();
  Demo.init();
});
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/6.3.1/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
	 https://firebase.google.com/docs/web/setup#config-web-app -->

<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
	   apiKey: "AIzaSyA076zh9_4yCBQJzxkJ4EsDGvdsr6hN_g4",
	authDomain: "bil495-dab2mm.firebaseapp.com",
	databaseURL: "https://bil495-dab2mm.firebaseio.com",
	projectId: "bil495-dab2mm",
	storageBucket: "",
	messagingSenderId: "363743013122",
	appId: "1:363743013122:web:ef983e0a27193b59"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
</script>
</body>
</html>