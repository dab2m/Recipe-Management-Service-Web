<?php
session_start();
if (!isset($_SESSION['username']))
	header("location:login.php");	
include 'db.php';
global $sql;

function searchKeyWord($row, $key) //ismin icerisinde $key varsa ya da aciklamanin icerisinde varsa true donuyor
{
	global $db;
	if (strpos($row['isim'], $key) !== false || strpos($row['aciklama'], $key) !== false || strpos($row['username'], $key) !== false)
		return true;
	$tagsql = "SELECT * FROM `tarif_tag` WHERE tarif_id = " . $row['id'];
	$tagres = mysqli_query($db, $tagsql);

	while ($tagrow = mysqli_fetch_assoc($tagres)) {
		$innersql = "SELECT * FROM `tag` WHERE tag_id = " . $tagrow['tag_id'];
		$innerres = mysqli_query($db, $innersql);
		$innerrow = mysqli_fetch_assoc($innerres);
		if (strpos($innerrow['isim'], $key) !== false)
			return true;
	}
	return false;
}

if (isset($_POST['key']) && isset($_POST['page']) && isset($_POST['user'])) {

	$key = $_POST['key'];
	$page = $_POST['page'];
	$user_name = $_POST['user'];

	if ($key == "") {
		if ($page == 'anasayfa') {
			$sql = "SELECT * FROM `tarif`";
			echo "
			<script type=\"text/javascript\">
			[].forEach.call(document.querySelectorAll('.userInfo'), function (el) {
				el.style.visibility = 'visible';
			  });
			</script>
		";
		} else if ($page == 'tariflerim') {
			$sql = "SELECT * FROM `tarif` WHERE username= '$user_name'";
			echo "
			<script type=\"text/javascript\">
			[].forEach.call(document.querySelectorAll('.userInfo'), function (el) {
				el.style.visibility = 'hidden';
			  });
			</script>
		";
		}
		//$sql = "SELECT * FROM `tarif`";
		$result = mysqli_query($db, $sql);
		if (mysqli_affected_rows($db) > 0)
			while ($row = mysqli_fetch_assoc($result)) {
				?>
			<div class="row">
				<div class="col-md-4 blog-img blog-tag-data">
					<img src="<?php echo $row['fotograf']; ?>" alt="" class="img-responsive">
					<ul class="list-inline">
						<p style="color:#0099cc" ;><i class="fa fa-check" aria-hidden="true"></i>

							<?php echo $row['username']; ?>
							<?php echo " bu tarifi ekledi" ?>
						</p>
					</ul>
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
						<a href="#">
							<?php echo $row['isim']; ?></a>
					</h3>
					<p>
						<?php echo $row['aciklama']; ?>
					</p>
					<?php
					if ($_SESSION['username'] != $row['username']) /* Eger tiklanan tarif kullanicinin kendisine ait degilse begenilebilir */ {
						$like_sql = "SELECT * FROM `begeni` WHERE `kullanici_id` = " . $_SESSION['user_id'] . " AND `tarif_id` = " . $row['id'];
						mysqli_query($db, $like_sql);
						if (mysqli_affected_rows($db) == 0) {

							?>
							<a class="btn red" href=" <?php echo "begen.php?tarif_id_like=" . $row['id'] . "&anasayfa=1"; ?>">
								<i class="icon-like"></i> &nbsp Begen
							</a>
						<?php
						} else {
							?>
							<a class="btn blue" href=" <?php echo "begen.php?tarif_id_dislike=" . $row['id'] . "&anasayfa=1"; ?>">
								<i class="icon-dislike"></i> &nbsp Begeniyi Kaldir
							</a>
						<?php
						}
					} else /* Kendi tarifi ise begenemez */ {

						?>
						<button class="btn red disabled" title="Kendi tarifinizi begenemezsiniz...">
							<i class="glyphicon glyphicon-heart"></i> &nbsp Begen
						</button>
					<?php
					}
					?>
				</div>
			</div>
			<hr>
		<?php
		} else
		echo "<h1>Gosterilecek tarif yok...</h1>";
} else {
	if ($page == 'anasayfa') {
		$sql = "SELECT * FROM `tarif`";
		echo "
		<script type=\"text/javascript\">
		[].forEach.call(document.querySelectorAll('.userInfo'), function (el) {
			el.style.visibility = 'visible';
		  });
		</script>
	";
	}
	//$sql = "SELECT * FROM `tarif`";
	else if ($page == 'tariflerim') {
		$sql = "SELECT * FROM `tarif` WHERE username= '$user_name'";
		echo "
		<script type=\"text/javascript\">
		[].forEach.call(document.querySelectorAll('.userInfo'), function (el) {
			el.style.visibility = 'hidden';
		  });
		</script>
	";
	}
	$result = mysqli_query($db, $sql);
	if (mysqli_affected_rows($db) > 0)
		while ($row = mysqli_fetch_assoc($result)) {
			if (searchKeyWord($row, $key) != false) {
				?>
				<div class="row">
					<div class="col-md-4 blog-img blog-tag-data">
						<img src="<?php echo $row['fotograf']; ?>" alt="" class="img-responsive">
						<ul class="list-inline">
							<p style="color:#0099cc" ;><i class="fa fa-check" aria-hidden="true"></i>

								<?php echo $row['username']; ?>
								<?php echo " bu tarifi ekledi" ?>
							</p>
						</ul>
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
						<?php
					if ($_SESSION['username'] != $row['username']) /* Eger tiklanan tarif kullanicinin kendisine ait degilse begenilebilir */ {
						$like_sql = "SELECT * FROM `begeni` WHERE `kullanici_id` = " . $_SESSION['user_id'] . " AND `tarif_id` = " . $row['id'];
						mysqli_query($db, $like_sql);
						if (mysqli_affected_rows($db) == 0) {

							?>
							<a class="btn red" href=" <?php echo "begen.php?tarif_id_like=" . $row['id'] . "&anasayfa=1"; ?>">
								<i class="icon-like"></i> &nbsp Begen
							</a>
						<?php
						} else {
							?>
							<a class="btn blue" href=" <?php echo "begen.php?tarif_id_dislike=" . $row['id'] . "&anasayfa=1"; ?>">
								<i class="icon-dislike"></i> &nbsp Begeniyi Kaldir
							</a>
						<?php
						}
					} else /* Kendi tarifi ise begenemez */ {

						?>
						<button class="btn red disabled" title="Kendi tarifinizi begenemezsiniz...">
							<i class="glyphicon glyphicon-heart"></i> &nbsp Begen
						</button>
					<?php
					}
					?>
					</div>
				</div>
				<hr>
			<?php
			}
		} else
		echo "<h1>Gosterilecek tarif yok...</h1>";
}
}
?>