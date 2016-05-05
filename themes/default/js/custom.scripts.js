(function($) {
	
	$(document).ready(function() {
		$.get(
			'/api/v/1/app/9d01d99ac4b9a2a5f0344ca0465f2b3cf',
			{
				accept: 'json',
				text: 'cat'
			},
			function(data) {
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
			}
		);
	});
	
})(jQuery);

