$(document).ready(function() {
	$('.save-product-button').click(function(event) {
		$.ajax({
			url: '/template/additionalFiles/recomendedProducts/recomendedProductsController.php',
			type: 'POST',
			data: {
				action 	  : "changeProductBonus",
				productId : $('.product-id').val(),
				bonus     : $('.bonus').val() 
			},
		}) 
		.always(function(response) {
			console.log(response);
		});
		CloseModal();
		getRecomendedProductsList();
	});
});