<?php
session_start();
if (!isset($_SESSION['username']))
	header("location:login.php");
include 'db.php';  // db scriptini bu scripte ekliyors
$user_name = $_SESSION['username'];
?>
<!DOCTYPE html>

<html lang="tr">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
	<meta charset="utf-8">
	<title>Tariflerim</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta content="" name="description" />
	<meta content="" name="author" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
	<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
	<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="assets/admin/pages/css/blog.css" rel="stylesheet" type="text/css" />
	<link href="assets/global/css/likeButton.css" rel="stylesheet" type="text/css">
	<link href="assets/global/css/editButton.css" rel="stylesheet" type="text/css">
	<link href="assets/admin/pages/css/news.css" rel="stylesheet" type="text/css" />
	<link href="assets/global/css/popup.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- END PAGE LEVEL STYLES -->
	<!-- BEGIN THEME STYLES -->
	<link href="assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css" />
	<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
	<link href="assets/admin/layout2/css/layout.css" rel="stylesheet" type="text/css" />
	<link id="style_color" href="assets/admin/layout2/css/themes/grey.css" rel="stylesheet" type="text/css" />
	<link href="assets/admin/layout2/css/custom.css" rel="stylesheet" type="text/css" />
	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />

	<script type="text/javascript">
		function search() {
			var key = $('#yemeksearchbar').val();
			var user = "<?php echo $user_name; ?>";
			var page= "tariflerim";
			$.ajax({
				url: "arama.php",
				type: 'POST',
				data: {
					"key": key,
					"user" : user,
					"page" : page
				},
				success: function(response) {
					$('#tarifler').empty();
					$('#tarifler').append(response);
				}
			});
		}
	</script>
	<script type=text/javascript> 
	function myFunction() {
		 var days=$('#Nday').val(); 
		 $.ajax({
			  url: "delDay.php" , 
			  type: 'POST' ,
			  data: { "days" : days}, 
			  success: function(response) {
				   console.log("success"); 
				   } 
				});
	} 
	</script>
</head> 
	<div class="form-popup" id="myForm">
	<form action="tariflerim.php" class="form-container">
    <h1 style="font-size:24px;">Tarifleriniz kaç gün içinde silinsin?</h1>

    <label for="day"></label>
    <input id="Nday" type="text" placeholder="Gün.." name="day" required>

    <button type="submit" class="btn" onclick="myFunction()">Submit</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
  </form>
</div>

</head>

<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo ">
	<!-- BEGIN HEADER -->
	<div class="page-header navbar navbar-fixed-top">
		<!-- BEGIN HEADER INNER -->
		<div class="page-header-inner container">
			<!-- BEGIN LOGO -->
			<div class="page-logo">

			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
			</a>

			<div class="page-top">
				<div class="top-menu">

					<ul class="nav navbar-nav pull-right">
						<li>
							<i class='far fa-sun' onclick="openForm()" style='font-size:19px;color:red;margin-top:31px;'></i>
						</li>
						<li class="dropdown dropdown-extended dropdown-tasks ms-hover" id="header_task_bar">
							<a href="logout.php" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" onclick="window.location.href='logout.php';">
								<i class="icon-power"></i>
							</a>
						</li>
					</ul>

				</div>
				<form class="search-form search-form-expanded" action="#" method="POST">
					<div class="input-group">
						<input id="yemeksearchbar" type="text" class="form-control" placeholder="Arama..." onkeyup="search()">
						<span class="input-group-btn">
							<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
						</span>
					</div>
				</form>

			</div>
			<!-- END PAGE TOP -->
		</div>
		<!-- END HEADER INNER -->
	</div>
	<!-- END HEADER -->
	<div class="clearfix">
	</div>
	<div class="container">
		<!-- BEGIN CONTAINER -->
		<div class="page-container">
			<!-- BEGIN SIDEBAR -->
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
					<!-- END SIDEBAR MENU -->
				</div>
			</div>
			<!-- END SIDEBAR -->
			<!-- BEGIN CONTENT -->
			<div class="page-content-wrapper">
				<div class="page-content">

					<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
					<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<h4 class="modal-title">Modal title</h4>
								</div>
								<div class="modal-body">
									Widget settings form goes here
								</div>
								<div class="modal-footer">
									<button type="button" class="btn blue">Save changes</button>
									<button type="button" class="btn default" data-dismiss="modal">Close</button>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<h3 class="page-title">
						Yemek Tarifleri
					</h3>
					<div class="page-bar">
						<ul class="page-breadcrumb">
							<li>
								<i class="fa fa-home"></i>
								<a href="anasayfa.php">Ana Sayfa</a>
								<i class="fa fa-angle-right"></i>
							</li>
							<li>
								<a href="#">Tariflerim</a>
							</li>
						</ul>
					</div>
					<!-- END PAGE HEADER-->
					<!-- BEGIN PAGE CONTENT-->
					<div class="portlet light">
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-12 blog-page">
									<div class="row">
										<div id="tarifler" class="col-md-9 col-sm-8 article-block">
																				
											<?php
											$sql = "SELECT * FROM `tarif` WHERE username= '$user_name'"; // sorgu
											$result = mysqli_query($db, $sql); //sorgu sonucu

											if (mysqli_affected_rows($db) > 0) //sorgu sonucunda sonuc donuyorsa
											{

												while ($row = mysqli_fetch_assoc($result)) {

													?>
                                                     		<p><button class="myButton" onclick="return Deleteqry(<?php echo $row['id'] ?>);"><i class="w3-margin-left fa fa-trash"></i></button></p>
															<div class="row">
																<div class="col-md-4 blog-img blog-tag-data">

																	<!--<p><button class="myButton" onclick="return Editqry(<?php echo $row['id'] ?>);"><i class="w3-margin-left fa fa-trash">Edit</i></button></p>-->


																	<img src="<?php echo $row['fotograf']; ?>" alt="" class="img-responsive">
																	<ul class="list-inline">
            															<li>
            																<i class="fa fa-heart"></i>
            																<a href="javascript:;">
            														 			<?php
																					$begeni_sayisi_sql = "SELECT * FROM `begeni` WHERE `tarif_id` = " . $row['id'];
																					mysqli_query($db, $begeni_sayisi_sql);
																					echo mysqli_affected_rows($db) . " Begeni";
																					?>
            																</a>
            															</li>
            														</ul>
																	<ul class="list-inline blog-tags">
																		<li>
																			<i class="fa fa-tags"></i>
																			<?php
																			$tagsql = "SELECT * FROM `tarif_tag` WHERE tarif_id = " . $row['id'];
																			$tagres = mysqli_query($db, $tagsql);
																			while ($tagrow = mysqli_fetch_assoc($tagres)) {
																				$innersql = "SELECT * FROM `tag` WHERE tag_id = " . $tagrow['tag_id'];
																				$innerres = mysqli_query($db, $innersql);
																				$innerrow = mysqli_fetch_assoc($innerres);
																				echo "<a href=\"javascript:;\"> " . $innerrow['isim'] . " </a>";
																			}
																			?>
																		</li>
																	</ul>
																</div>
																<div class="col-md-8 blog-article">
																	<h3>
																		<a href="tektarif.php?tarif_id=<?php echo $row['id']; ?>">
																			<?php echo $row['isim']; ?></a>
																	</h3>

																	<p>
																		<?php echo $row['aciklama']; ?>
																	</p>
																	<!--<div class="like-content">
																						<button class="btn-secondary like-review">
																							<i class="fa fa-heart" aria-hidden="true"></i> Like
																						</button>-->
																</div>
															</div>

											
														<hr>
												<?php
												}
											} else {
												echo "<h1>Gosterilecek tarif yok...</h1>";
											}

											?>
									</div>
									<!--end col-md-9-->

									<!--end col-md-3-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- END PAGE CONTENT-->
			</div>
		</div>
	</div>
	</div>

	<!-- css dosyalarini include eden satirlar -->

	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
	<script src="assets/global/scripts/likeButtonJs.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
	<script src="assets/admin/layout2/scripts/layout.js" type="text/javascript"></script>
	<script src="assets/admin/layout2/scripts/demo.js" type="text/javascript"></script>
	<script>
		function Editqry(id) {

			window.location.href = 'editRecipe.php?del_id=' + id + '';
			return true;

		}
	</script>
	<script>
		function Deleteqry(id) {
			if (confirm("Tarifi silmek istiyor musunuz?")) {
				window.location.href = 'delete.php?del_id=' + id + '';
				return true;
			}
		}
	</script>
	<script>
		function openForm() {
			document.getElementById("myForm").style.display = "block";
		}

		function closeForm() {
			document.getElementById("myForm").style.display = "none";
		}
	</script>

	<script>
		jQuery(document).ready(function() {
			Metronic.init(); // init metronic core components
			Layout.init(); // init current layout
			Demo.init(); // init demo features
		});
	</script>

	</body>

</html>