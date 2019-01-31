<?php 
include  'connect_db.php';
 ?>
 
<html>
    <head>
        <meta charset="utf-8">
        <title>Главная</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/main.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    </head>

    <body>
  
 
<section>
            <div class="container">
                <div class="row">

<div class="col-sm-9 padding-right">
                        <div class="features_items"><!--features_items-->
                            <h2 class="title text-center">Товары</h2>
		<?php 
		$num = 6; // количество вывода услуг
		$page = (int)$_GET['page'];
		$count = mysqli_query($link,"SELECT COUNT(*) FROM tovar");
		$temp = mysqli_fetch_array($count);
			if ($temp[0]>0){
				$tempcount = $temp[0];
				// общее число страниц
			$total = (($tempcount - 1) / $num) + 1;
			$total = intval($total);
			$page = intval($page);
			if (empty($page) or $page < 0) $page = 1;
				if ($page > $total) $page = $total;
				
				// c какого номера выводитьтовар
			$start = $page * $num - $num;
			$qury_start_num = "LIMIT $start, $num";
			}
	 $query   =  "SELECT * FROM tovar  $qury_start_num";
				$result  =  mysqli_query( $link,  $query );
                
               while (  $row  =  mysqli_fetch_assoc($result)  )	{
				   
				   echo '
				   <div class="col-sm-4">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                         <img src="images/'.$row["foto"].'" alt="" /> 
                                           <h2>'.$row["name"].'</h2>
                                            <p>'.$row["mini_description"].'</p>
                                        </div>
                                    </div>	
                                </div>
                            </div>
				   ';
			   }
			   echo '      </div><!--features_items-->';
if ($page != 1){ $pstr_prev = '<li><a class="pstr-prev" href="index.php?page='.($page - 1).'">&lt; </a></li>';}
if ($page != $total) $pstr_next = '<li><a class="pstr-next" href="index.php?page='.($page + 1).'">&gt; </a></li>';

if ($page - 5 > 0) $page5left = '<li><a href="index.php?page='.($page - 5).'">'.($page - 5).'</a></li>';		   
if ($page - 4 > 0) $page4left = '<li><a href="index.php?page='.($page - 4).'">'.($page - 4).'</a></li>';		   
if ($page - 3 > 0) $page3left = '<li><a href="index.php?page='.($page - 3).'">'.($page - 3).'</a></li>';		   
if ($page - 2 > 0) $page2left = '<li><a href="index.php?page='.($page - 2).'">'.($page - 2).'</a></li>';	   
if ($page - 1 > 0) $page1left = '<li><a href="index.php?page='.($page - 1).'">'.($page - 1).'</a></li>';
	
if ($page + 5 <= $total) $page5right = '<li><a href="index.php?page='.($page + 5).'">'.($page + 5).'</a></li>';		   
if ($page + 4 <= $total) $page4right = '<li><a href="index.php?page='.($page + 4).'">'.($page + 4).'</a></li>';		   
if ($page + 3 <= $total) $page3right = '<li><a href="index.php?page='.($page + 3).'">'.($page + 3).'</a></li>';		   
if ($page + 2 <= $total) $page2right = '<li><a href="index.php?page='.($page + 2).'">'.($page + 2).'</a></li>';		   
if ($page + 1 <= $total) $page1right = '<li><a href="index.php?page='.($page + 1).'">'.($page + 1).'</a></li>';		 

  if ($page+5 < $total){
	  $strtotal ='<li><p class="nav-point">...</p></li><li><a href="index.php?page='.$total.'">'.$total.'</a></li>';
  }else{
	  $strtotal = "";
  }
  
  if ($total > 1){
	  echo'<div style="clear:both;"></div>
	  <center><div class="pstrnav">
	  <ul>
	  ';
	  echo $pstr_prev.$page5left.$page4eft.$page3left.$page2left.$page1left."<li><a class='pstr-active' href='index.php?page=".$page."'>".$page."</a></li>".$page1right.$page2right.$page3right.$page4right.$page5right.$strtotal.$pstr_next;
	  echo '
	  </ul>
	  </div></center>
	  ';
  }
                        ?>
	
                  

                        

                    </div>
                </div>
            </div>
        </section>
    </body>
</html>