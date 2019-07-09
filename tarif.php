<?php 
    
    session_start();
    if(!isset($_SESSION['username']))
        header("location:login.php");
	include 'db.php';
	
	$user_name=$_SESSION['username'];
    if(isset($_POST['name']))
    {
        $name = $_POST['name'];
        $tags = $_POST['select2tags'];
        $tags = $myArray = explode(',', $tags);
		$desc = $_POST['editor1'];
		
		//echo "$user_name";
		
        
        if(isset($_FILES["photo"]) && $_FILES["photo"]["name"] != "")
            $photoname = addslashes("fotograflar\\"). basename($_FILES["photo"]["name"]); //fotograf uzantisini olusturuyor
        else 
            $photoname = addslashes("fotograflar\\no.png" );
            
        $sql = "INSERT INTO `tarif`(`isim`,`fotograf`,`aciklama`,`username`,`creation_date`) VALUES ('$name','$photoname','$desc','$user_name',CURDATE())";
        
        if(!mysqli_query($db, $sql)) // sorguyu calistiramazsa
        {
            echo "<script> alert('Could not record...'); </script>";
            echo mysqli_error($db);
            echo $user_name;
        }
        else // sorguyu calistirabilirse
        {
            $tarif_id = $db -> insert_id; //yeni tarif kaydinin idsi
            foreach ($tags as $tag)
            {
                $sql = "INSERT INTO `tag`(isim) VALUES ('$tag')";
                mysqli_query($db, $sql);
                $tag_id = $db -> insert_id; //yeni tagin idsi
                $sql = "INSERT INTO `tarif_tag`(`tarif_id`,`tag_id`) VALUES ($tarif_id,$tag_id)";
                
                //echo "<script> console.log('$sql') </script>";
                mysqli_query($db, $sql);
            }
            
            if(move_uploaded_file($_FILES["photo"]["tmp_name"], $photoname))
                echo "<script> alert('Uploaded'); </script>";
        }
    }
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8"/>
<title>Tarif Ekleme Sayfası</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>

<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css">
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<link href="assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout2/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="assets/admin/layout2/css/themes/grey.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout2/css/custom.css" rel="stylesheet" type="text/css"/>

<link rel="shortcut icon" href="favicon.ico"/>

<script type="text/javascript">
        
	 function search() {
        var key = $('#yemeksearchbar').val();
        $.ajax({
           url: "arama.php",  
           type: 'POST',
           data: { "key" : key },
           success: function(response){
        	   $('#tarifler').empty();
        	   $('#tarifler').append(response);
           }
        });
	 }
        
</script>
</head>

<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo ">

<div class="page-header navbar navbar-fixed-top">

	<div class="page-header-inner container">

		<div class="page-logo">

		</div>

		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>

		<div class="page-top">


		</div>

	</div>

</div>

<div class="clearfix">
</div>
<div class="container">

	<div class="page-container">

		<div class="page-sidebar-wrapper">

			<div class="page-sidebar navbar-collapse collapse">

				<ul class="page-sidebar-menu page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
					<li class="start ">
						<a href="anasayfa.php">
						<i class="icon-home"></i>
						<span class="title">Ana Sayfa</span>
						</a>
					</li>
					<li class="start ">
						<a href="tarif.php">
						<i class="icon-book-open"></i>
						<span class="title">Yeni Yemek Tarifi</span>
						</a>
					</li>
					<li class="start ">
							<a href="tariflerim.php">
								<i class="icon-book-open"></i>
								<span class="title">Tariflerim</span>
							</a>
						</li>
				</ul>

			</div>
		</div>

		<div class="page-content-wrapper">
			<div class="page-content">

				<h3 class="page-title">
				Yeni Yemek Tarifi
				</h3>
				<div class="page-bar">
					<ul class="page-breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="anasayfa.php">Ana Sayfa</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">Yeni Yemek Tarifi</a>
						</li>
					</ul>
				</div>

				<div class="row">
					<div class="col-md-12">

						<div class="portlet box green">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-gift"></i>Tarif Ekleme
								</div>

							</div>
							<div class="portlet-body form">

								<form action="#" id="form_sample_3" class="form-horizontal" method="POST" enctype="multipart/form-data">
									<div class="form-body">
										<div class="alert alert-danger display-hide">
											<button class="close" data-close="alert"></button>
											You have some form errors. Please check below.
										</div>
										<div class="alert alert-success display-hide">
											<button class="close" data-close="alert"></button>
											Your form validation is successful!
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">Tarif İsmi <span class="required">
											* </span>
											</label>
											<div class="col-md-4">
												<input type="text" name="name" data-required="1" class="form-control"/>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">Tarif Tag'leri<span class="required">
											* </span>
											</label>
											<div class="col-md-4">
												<input type="hidden" class="form-control" id="select2_tags" value="" name="select2tags">
												<span class="help-block">
												Yemek Tagleri </span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">Fotograf<span class="required">
											* </span>
											</label>
											<div class="col-md-4">
												<input type="file" class="form-control" id="photo" value="" name="photo">
												<span class="help-block">
												Tarif Fotoğrafı </span>
											</div>
										</div>
										<div class="form-group last">
											<label class="control-label col-md-3">Tarif Açıklaması <span class="required">
											* </span>
											</label>
											<div class="col-md-9">
												<textarea class="wysihtml5 form-control" rows="6" name="editor1" data-error-container="#editor1_error"></textarea>
												<div id="editor1_error">
												</div>
											</div>
										</div>

									</div>
									<div class="form-actions">
										<div class="row">
											<div class="col-md-offset-3 col-md-9">
												<button type="submit" value="Upload Image" class="btn green">Submit</button>
												<button type="button" class="btn default">Cancel</button>
											</div>
										</div>
									</div>
								</form>

							</div>

						</div>
					</div>
				</div>

			</div>
		</div>

	</div>

	<div class="page-footer">
		<div class="page-footer-inner">
			 2014 &copy; Metronic by keenthemes.
		</div>
		<div class="scroll-to-top">
			<i class="icon-arrow-up"></i>
		</div>
	</div>

</div>

<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>

<script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>

<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="assets/global/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-markdown/lib/markdown.js"></script>

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="assets/admin/layout2/scripts/layout.js" type="text/javascript"></script>
<script src="assets/admin/layout2/scripts/demo.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/form-validation.js"></script>

<script>
jQuery(document).ready(function() {   
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
   FormValidation.init();
});
</script>

</body>

</html>