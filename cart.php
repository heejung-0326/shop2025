<?php 
  include('./db/dbconn.php');

	session_start(); //세션정보 시작

  if(isset($_SESSION['mb_id'])){ //세션 아이디가 있으면
    $userid = $_SESSION['mb_id']; //id에 세션 id저장
    $name = $_SESSION['mb_name']; //name에 세션 name을 저장
  }else{  //세션정보가 없다면 (로그인을 안한경우)
    $userid = ''; //id없음
    $name = ''; //name없음
  }

	$session_id=session_id();  // seesion => session 오타

  //db에 일치하는 사용자가 있는지 확인
	$sql = "SELECT * FROM shop_temp WHERE session_id = '$session_id'";

	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($result);
	
	//echo '현재 세션 ID: ' . $session_id . "<br>";
	//echo 'DB에서 가져온 session_id: ' . $row['session_id'] . "<br>";
?>
<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="반려동물용품 쇼핑몰">
		<meta name="author" content="STORE BOM 쇼핑몰">
		<title>STORE BOM - 장바구니</title>
		<link rel="shortcut icon" href="images/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">

		<link href="./css/bootstrap.min.css" rel="stylesheet">
		<link href="./css/font-awesome.min.css" rel="stylesheet">
		<link href="./css/prettyPhoto.css" rel="stylesheet">
		<link href="./css/price-range.css" rel="stylesheet">
		<link href="./css/animate.css" rel="stylesheet">
		<link href="./css/responsive.css" rel="stylesheet">
		<link href="./css/main.css" rel="stylesheet">
		<link href="./css/swiper.css" rel="stylesheet">
    
    <script src="./js/jquery.js"></script>
    <script src="./js/price-range.js"></script>
    <script src="./js/jquery.scrollUp.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/jquery.prettyPhoto.js"></script>
    <script src="./js/main.js"></script>
  </head>
  <body>
    <header id="header"><!--header-->
			<div class="header-middle"><!--header-middle-->
				<div class="container">
					<div class="row">
						<div class="col-md-4 clearfix">
							<h1 class="logo pull-left">
								<a href="index.php"><img src="./images/logo.png" alt="상단로고" width="220" /></a>
							</h1>
						</div>
						<div class="col-md-8 clearfix">
							<div class="shop-menu clearfix pull-right">
								<ul class="nav navbar-nav">
									<?php
									if(!$userid) {
									?>
										<li><a href="order_list.php"><i class="fa fa-shopping-cart"></i>주문정보</a></li>
										<li><a href="cart.php"><i class="fa fa-shopping-cart"></i>장바구니</a></li>
										<li><a href="login.php"><i class="fa fa-user"></i>로그인</a></li>
										<li><a href="sign.php"><i class="fa fa-lock"></i>회원가입</a></li>
									<?php
									} else {
									?>
										<li><a href="#"><i class="fa fa-lock"></i>
										<?php echo $name; echo '('. $userid; echo ')'; ?>님 환영합니다.</a></li>
										<li><a href="order_list.php"><i class="fa fa-shopping-cart"></i>주문정보</a></li>
										<li><a href="cart.php"><i class="fa fa-shopping-cart"></i>장바구니</a></li>
										<li><a href="./php/logout.php"><i class="fa fa-user"></i>로그아웃</a></li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div><!--/header-middle-->

			<div class="header-bottom"><!--header-bottom-->
				<div class="container">
					<div class="row">
						<div class="col-sm-9">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
							<div class="mainmenu pull-left">
								<ul class="nav navbar-nav collapse navbar-collapse">
									<li><a href="index.php">Home</a></li>
									<li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
									<ul role="menu" class="sub-menu">
									<li><a href="shop.php">Products</a></li>
									<li><a href="product-details.php">Product Details</a></li>
									<li><a href="checkout.php">Checkout</a></li>
									<li><a href="cart.php">Cart</a></li>
									<li><a href="login.php" class="active">Login</a></li>
									</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div><!--/header-bottom-->
		</header><!--/header-->
		
		<main>
		<form name="주문하기" action="./php/order_db.php" method="post">

			<section id="cart_items">
				<div class="container">
					<div class="breadcrumbs">
						<ol class="breadcrumb">
							<li><a href="index.php" title="메인으로 바로가기">Home</a></li>
							<li class="active">Shopping Cart</li>
						</ol>
					</div>
					<div class="table-responsive cart_info">
						<table class="table table-condensed">
							<thead>
								<tr class="cart_menu">
									<td class="image">제품이미지</td>
									<td class="description">제품명</td>
									<td class="price">가격</td>
									<td class="quantity">수량</td>
									<td class="total">전체금액</td>
									<td>&nbsp;</td>
								</tr>
							</thead>

							<tbody>
								<?php while($row=mysqli_fetch_array($result)){?>
									<tr>
										<td class="cart_product">
											<a href="#" title="제품사진">
												<img src="./images/shop/<?=$row['img']?>" alt="" style="width:170px;height:150px;">
											</a>
										</td>
										<td class="cart_description">
											<h4><a href="#" title="상품명"><?=$row['name']?></a></h4>
										</td>
										<td class="cart_price">
											<?=NUMBER_FORMAT($row['price'])?>
										</td>
										<td class="cart_quantity">
											<input class="cart_quantity_input" type="text" name="quantity" value="<?=$row['count']?>" autocomplete="off" size="2">
										</td>
										<td class="cart_total">
											<p class="cart_total_price"><?=NUMBER_FORMAT($row['money'])?></p>
										</td>
										<td class="cart_delete">
											<a href="./php/cart_delete.php?no=<?=$row['no']?>" class="cart_delete" ><i class="fa fa-times"></i></a>
										</td>
									</tr>
								<?php }?>								
									<?php
										$sql = "select sum(money) from shop_temp where session_id='$session_id'";
										$result = mysqli_query($conn, $sql);
										$row = mysqli_fetch_array($result);
										mysqli_close($conn);
									?>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>총금액:</td>
										<td class="cart_total">
											<p class="cart_total_price" style="text-align:right">
												<?= NUMBER_FORMAT($row[0]) ?>
											</p>
										</td>
									</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div style="text-align:center; color: orange;">
					<input type="submit" class="btn btn-default" style="color:orange" value="주문하기">
				</div>
			</section>
		</form>
		</main>

    <footer class="text-center">
			<address>copyright&copy;2025 shoppingmall allrightes resverved.</address>
		</footer>
  </body>
</html>