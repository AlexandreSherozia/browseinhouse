function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

function loadscroler(){
		
}
$(document).on('ready',function(){ 
	"use strict";
	loadscroler();
	
	
	/*slideshow script code start here*/
	$('.slideshow').owlCarousel({
		items: 1,
		autoPlay: 5000,
		singleItem: true,
		navigation: false,
		navigationText: ['<i class="fa fa-long-arrow-left fa1"></i>', '<i class="fa fa-long-arrow-right fa2"></i>'],
		pagination: false,
	});
	/*slideshow script code end here*/
	
	
	/*ad-single script code start here*/
	$('#ad-single').owlCarousel({
		items: 1,
		itemsDesktop : [1199, 1],
		itemsDesktopSmall : [979, 1],
		itemsTablet : [768, 1],
		itemsMobile : [479, 1],
		navigation : true,
		slideSpeed : 300,
		paginationSpeed : 400,
		singleItem : false,
		navigationText: ['Back', 'Next'],
		pagination: false,
	});
	/*ad-single script code end here*/

	
	
	// Product Grid
	$('#grid-view').on('click',function(){
		
		$('.maincategory .row > .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12 cols');
		localStorage.setItem('display', 'grid');
	});
	$('#list-view').on('click',function(){
		
		$('.maincategory .row > .product-grid').attr('class', 'product-layout product-list col-xs-12 cols');
		localStorage.setItem('display', 'list');
	});
	
	// Product Grid
	$('#grid-view').on('click',function(){
		
		$('.maincategory1 .row > .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-12 cols');
		localStorage.setItem('display', 'grid');
	});
	$('#list-view').on('click',function(){
		
		$('.maincategory1 .row > .product-grid').attr('class', 'product-layout product-list col-xs-12 cols');
		localStorage.setItem('display', 'list');
	});
	
	// collapse
	$('.btn-group').on('shown.bs.btn-group', function(){
	$(this).parent().removeClass("active").addClass("active");
	}).on('hidden.bs.btn-group', function(){
	$(this).parent().removeClass("active").addClass("");
	});
	
	// collapse
	$('.collapse').on('shown.bs.collapse', function(){
	$(this).parent().removeClass("active").addClass("active");
	$(this).parent().find(".fa-angle-right").removeClass("fa-angle-right").addClass("fa-angle-down");
	}).on('hidden.bs.collapse', function(){
	$(this).parent().find(".fa-angle-down").removeClass("fa-angle-down").addClass("fa-angle-right");
	$(this).parent().removeClass("active").addClass("");
	});
	
			
});
