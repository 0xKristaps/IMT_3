<?php

	function total_price($cart){
		$price = 0.0;
		if(is_array($cart)){
		  	foreach($cart as $isbn => $qty){
		  		$bookprice = getbookprice($isbn);
		  		if($bookprice){
		  			$price += $bookprice * $qty;
		  		}
		  	}
		}
		return $price;
	}

	

	function total_items($cart){
		$items = 0;
		if(is_array($cart)){
			foreach($cart as $isbn => $qty){
				$items += $qty;
			}
		}
		return $items;
	}

	function remove_item($cart, $isbn){
		if(is_array($cart) && array_key_exists($isbn, $cart)){
			unset($cart[$isbn]);
		}
		return $cart;
	}

	if(isset($_POST['remove']) && isset($_POST['isbn'])){
		$cart = remove_item($_SESSION['cart'], $_POST['isbn']);
		$_SESSION['cart'] = $cart;
	}
?>
