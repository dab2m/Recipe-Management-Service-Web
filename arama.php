<?php
    include 'db.php';
    
    
    function searchKeyWord($row,$key) //ismin icerisinde $key varsa ya da aciklamanin icerisinde varsa true donuyor
    {
        global $db;
        if (strpos($row['isim'], $key) !== false || strpos($row['aciklama'], $key) !== false)
            return true;
        $tagsql = "SELECT * FROM `tarif_tag` WHERE tarif_id = ".$row['id'];
        $tagres = mysqli_query($db, $tagsql);
        
        while($tagrow = mysqli_fetch_assoc($tagres))
        {
            $innersql = "SELECT * FROM `tag` WHERE id = ".$tagrow['tag_id'];
            $innerres = mysqli_query($db, $innersql);
            $innerrow = mysqli_fetch_assoc($innerres);
            if(strpos($innerrow['isim'], $key) !== false)
                return true;
        }
        return false;
    }

    if(isset($_POST['key']))
    {
        $key = $_POST['key'];
        
        if($key == "")
        {
            $sql = "SELECT * FROM `tarif`";
            $result = mysqli_query($db, $sql);
            if(mysqli_affected_rows($db) > 0)
                while($row = mysqli_fetch_assoc($result))
                {
                    ?>
    				  <div class="row">
    					<div class="col-md-4 blog-img blog-tag-data">
    						<img src="<?php echo $row['fotograf']; ?>" alt="" class="img-responsive">
    						<ul class="list-inline blog-tags">
    							<li>
    								<i class="fa fa-tags"></i>
    								<?php 
    								      $tagsql = "SELECT * FROM `tarif_tag` WHERE tarif_id = ".$row['id'];
    								      $tagres = mysqli_query($db, $tagsql);
    								      while($tagrow = mysqli_fetch_assoc($tagres))
    								      {
    								          $innersql = "SELECT * FROM `tag` WHERE id = ".$tagrow['tag_id'];
    								          $innerres = mysqli_query($db, $innersql);
    								          $innerrow = mysqli_fetch_assoc($innerres);
    								          echo "<a href=\"javascript:;\"> ". $innerrow['isim'] ." </a>";
    								
    								      }
    								?>
    							</li>
    						</ul>
    					</div>
    					<div class="col-md-8 blog-article">
    						<h3>
    						<a href="#">
    							<?php echo $row['isim'];?></a>
    						</h3>
    						<p>
    							<?php echo $row['aciklama'];?>
    						</p>
    					</div>
    				</div>
    				<hr>										      
    		<?php 
    		      }
    		  else 
    		      echo "<h1>Gosterilecek tarif yok...</h1>";
        }
        else 
        {
            $sql = "SELECT * FROM `tarif`";
            $result = mysqli_query($db, $sql);
            if(mysqli_affected_rows($db) > 0)
                while($row = mysqli_fetch_assoc($result))
                {
                    if(searchKeyWord($row, $key) != false)
                    {
                    ?>
    				  <div class="row">
    					<div class="col-md-4 blog-img blog-tag-data">
    						<img src="<?php echo $row['fotograf']; ?>" alt="" class="img-responsive">
    						<ul class="list-inline blog-tags">
    							<li>
    								<i class="fa fa-tags"></i>
    								<?php 
    								      $tagsql = "SELECT * FROM `tarif_tag` WHERE tarif_id = ".$row['id'];
    								      $tagres = mysqli_query($db, $tagsql);
    								      while($tagrow = mysqli_fetch_assoc($tagres))
    								      {
    								          $innersql = "SELECT * FROM `tag` WHERE id = ".$tagrow['tag_id'];
    								          $innerres = mysqli_query($db, $innersql);
    								          $innerrow = mysqli_fetch_assoc($innerres);
    								          echo "<a href=\"javascript:;\"> ". $innerrow['isim'] ." </a>";
    								
    								      }
    								?>
    							</li>
    						</ul>
    					</div>
    					<div class="col-md-8 blog-article">
    						<h3>
    						<a href="#">
    							<?php echo $row['isim'];?></a>
    						</h3>
    						<p>
    							<?php echo $row['aciklama'];?>
    						</p>
    					</div>
    				</div>
    				<hr>										      
    		<?php 
                 }
    		  }
    		  else 
    		      echo "<h1>Gosterilecek tarif yok...</h1>";
        }
    }
?>