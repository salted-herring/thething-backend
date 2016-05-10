(function($) {
	
	$(document).ready(function() {
		if ($('body').hasClass('page-home')) {
			$.get(
				'/api/v/1/app/9d01d99ac4b9a2a5f0344ca0465f2b3cf',
				{
					accept: 'json',
					text: 'cat'
				},
				function(data) {
					if (data.app_data) {
						data.app_data.forEach(function(o) {
							$(o).each(function(index, element) {
								$('body').append('<h2>' + element.title + '</h2>');
								if (element.description) { $('body').append('<p>' + element.description + '</p>'); }
								$('body').append('<p>URL: <a href="' + element.url + '" target="_blank">' + element.url + '</a></p>');
								if (element.thumbnail && element.thumbnail.length > 0) {
									$('body').append('<img src="' + element.thumbnail + '" />');
									$('body').append('<hr />');
								}
							});
						});
					}else{
						$('body').append('<h2>no data</h2>');
					}
				}
			);
			var url = '/api/v/1/siteapp/a57e6f22ba8e2348f1631ccb37974c317f3da9d20365264433e9c89987179f04e';
			
			$.get(
				url,
				{
					accept: 'json'
				},
				
				function(data) {
					console.log(data);
				}
				
			);	
		}
		
		
		
		if ($('body').hasClass('page-sonny')) {
			$('html, body').css({
				width: '100%',
				height: '100%',
				overflow: 'hidden'
			});
			if (navigator.geolocation) {
				$('body').html(
					'<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMSeA8SKA2w34X2ojTYKjeCbXsUCtxpms&callback=initMap" async defer></script>'
				);
				
				$('body').append('<div id="gmap" style="position: fixed; width: 100%; height: 100%;"></div>');				
			}else{
				$('body').html('browser not supported');
			}
		}
		
		
		
	});
	
})(jQuery);

function digit2(i) {
	var s = i.toString();
	if (parseInt(i) < 10) {
		s = '0'+s;
	}
	return s;
}

function getLoc (callback) {
	navigator.geolocation.getCurrentPosition(function(pos) {
		lat		=	pos.coords.latitude;
		lng		=	pos.coords.longitude;
		callback( {lat: lat, lng: lng} );
	});
}

function initMap() {
	document.ontouchmove = function(event){
		event.preventDefault();
	}
	
	var $ 			=	jQuery,
		location	=	'',
		duration	=	'',
		url			=	'/api/v/1/siteapp/a57e6f22ba8e2348f1631ccb37974c317f3da9d20365264433e9c89987179f04e',
		skeleton	=	null,
		map			=	null,
		marker		=	null,
		noPoi		=	[{
							featureType: "poi",
							stylers: [
							  { visibility: "off" }
							]
						}];
		
		$.get(url, { accept: 'json', get: 'structure'}, function( data ) {
			if (data && data.app_structure) {
				skeleton = data.app_structure;
				console.log(skeleton);
			}
		});
		
		getLoc(function(loc) {
			location = loc.lat + ',' + loc.lng;
			map = new google.maps.Map(document.getElementById('gmap'), {
				center: loc,
				zoom: 18,
				zoomControl: false,
				mapTypeControl: false,
				scaleControl: false,
				streetViewControl: false,
			});
			
			map.setOptions({styles: noPoi});
			marker = new google.maps.Marker({
				position: loc,
				map: map
			});
			
			
			var btnPee		=	$('<button />').attr('id','btn-here-now').html('Update location'),
				btnShort	=	$('<button />').attr('id','btn-short').addClass('btn-submit').html('Short'),
				btnLong	=	$('<button />').attr('id','btn-long').addClass('btn-submit').html('Long'),
				baseStyle	=	{
									'position': 'fixed',
									'border': '1px solid #000',
									'background-color': '#fff',
									'font-size': '20px',
									'padding': '0.5em 0'
								};
			btnPee.css(baseStyle).css(
				{
					
					'top': '2%',
					'left': '50%',
					'width': '60%',
					'transform': 'translateX(-50%)',
					'-moz-transform': 'translateX(-50%)',
					'-webkit-transform': 'translateX(-50%)'
				}
			);
			
			btnShort.css(baseStyle).css(
				{
					'bottom': '5%',
					'left': '5%',
					'width': '40%'
				}
			);
			
			btnLong.css(baseStyle).css(
				{
					'bottom': '5%',
					'right': '5%',
					'width': '40%'
				}
			);
			
			$('body').append(btnPee, btnShort, btnLong);
			
			btnPee.click( function(e) {
				btnPee.hide();
				if (marker) {
					marker.setMap(null);
				}
				
				getLoc(function(loc) {
					btnPee.show();
					marker = new google.maps.Marker({
						position: loc,
						map: map
					});
				});
			});
			
			$('.btn-submit').click(function(e) {
                if ($(this).is($('#btn-short'))) {
					console.log('short');
					skeleton.fields.duration.value = 'short';
				}else{
					console.log('long');
					skeleton.fields.duration.value = 'long';
				}
				
				skeleton.fields.location.value = location;
				
				var now			=	new Date(),
					nowString	=	now.getFullYear() + '-' + digit2(now.getMonth()+1) + '-' + digit2(now.getDate()) + ' ' + digit2(now.getHours()) + ':' + digit2(now.getMinutes()) + ':' + digit2(now.getSeconds());
				skeleton.fields.datetime.value = nowString;
				
				$('.btn-submit').hide();
				if (confirm('Submit?')) {
					$.post(
						url,
						{
							access_token: 'foobarbaz',
							submission: skeleton
						},
						function(data) {
							$('.btn-submit').show();
							alert(data.message);
						}
					);
				}else{
					$('.btn-submit').show();
				}
            });
			
		});
}