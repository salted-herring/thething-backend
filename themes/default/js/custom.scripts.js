(function($) {
	
	$(document).ready(function() {
		$.get(
			'/api/v/1/app/69a6aa67be3163e6dd0d17175713da32ffc13973c046b2a50b8c3c54cc0380de8',
			{
				accept: 'json',
				text: 'cat'
			},
			function(data) {
				data.app_data.forEach(function(o) {
					$(o.search.results).each(function(index, element) {
                    	$('body').append('<h2>' + element.title + '</h2>');
						$('body').append('<p>URL: <a href="' + element.landing_url + '" target="_blank">' + element.landing_url + '</a></p>');
						if (element.thumbnail_url && element.thumbnail_url.length > 0) {
							$('body').append('<img src="' + element.thumbnail_url  + '" />');
							$('body').append('<hr />');
						}
                    });
				});
			}
		);
	});
	
})(jQuery);

