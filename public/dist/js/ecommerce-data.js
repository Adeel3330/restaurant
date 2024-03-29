/*Ecommerce Init*/

/*****Ready function start*****/
$(document).ready(function(){
	"use strict"; 
	
	$('#statement').DataTable();
	if( $('#world_map_marker_1').length > 0 ){
		$('#world_map_marker_1').vectorMap(
		{
			map: 'world_mill_en',
			backgroundColor: '#fff',
			borderColor: '#fff',
			borderOpacity: 0.25,
			borderWidth: 0,
			color: '#e6e6e6',
			regionStyle : {
				initial : {
				  fill : 'rgba(86,111,201,.4)'
				}
			  },

			markerStyle: {
			  initial: {
							r: 10,
							'fill': '#fff',
							'fill-opacity':1,
							'stroke': '#000',
							'stroke-width' : 1,
							'stroke-opacity': 0.4
						},
				},
		   
			markers : [{
				latLng : [21.00, 78.00],
				name : 'INDIA : 350'
			  
			  },
			  {
				latLng : [-33.00, 151.00],
				name : 'Australia : 250'
				
			  },
			  {
				latLng : [36.77, -119.41],
				name : 'USA : 250'
			  
			  },
			  {
				latLng : [55.37, -3.41],
				name : 'UK   : 250'
			  
			  },
			  {
				latLng : [25.20, 55.27],
				name : 'UAE : 250'
			  
			  }],
			
			hoverOpacity: null,
			normalizeFunction: 'linear',
			zoomOnScroll: false,
			scaleColors: ['#000000', '#000000'],
			selectedColor: '#000000',
			selectedRegions: [],
			enableZoom: false,
			hoverColor: '#fff',
		});
	}
});
/*****Ready function end*****/

/*****Load function start*****/
// $(window).load(function(){
// 	window.setTimeout(function(){
// 		$.toast({
// 			heading: 'Welcome to kenny',
// 			text: 'Use the predefined ones, or specify a custom position object.',
// 			position: 'top-right',
// 			loaderBg:'#ea65a2',
// 			icon: 'success',
// 			hideAfter: 3000, 
// 			stack: 6
// 		});
// 	}, 3000);
// });
/*****Load function* end*****/

var sparklineLogin = function() { 
	if( $('#sparkline_1').length > 0 ){
		$("#sparkline_1").sparkline([2,4,4,6,8,5,6,4,8,6,6,2 ], {
			type: 'line',
			width: '100%',
			height: '45',
			lineColor: '#566FC9',
			fillColor: '#566FC9',
			maxSpotColor: '#566FC9',
			highlightLineColor: 'rgba(0, 0, 0, 0.2)',
			highlightSpotColor: '#566FC9'
		});
	}	
	if( $('#sparkline_2').length > 0 ){
		$("#sparkline_2").sparkline([0,2,8,6,8], {
			type: 'bar',
			width: '100%',
			height: '50',
			barWidth: '10',
			resize: true,
			barSpacing: '10',
			barColor: '#3cb878',
			highlightSpotColor: '#3cb878'
		});
	}	
	if( $('#sparkline_6').length > 0 ){
		$("#sparkline_6").sparkline([0, 23, 43, 35, 44, 45, 56, 37, 40, 45, 56, 7, 10], {
			type: 'line',
			width: '100%',
			height: '50',
			lineColor: '#fcb03b',
			fillColor: 'transparent',
			spotColor: '#fff',
			minSpotColor: undefined,
			maxSpotColor: undefined,
			highlightSpotColor: undefined,
			highlightLineColor: undefined
		});
	}
}
var sparkResize;

	$(window).resize(function(e) {
		clearTimeout(sparkResize);
		sparkResize = setTimeout(sparklineLogin, 200);
	});
	sparklineLogin();

	