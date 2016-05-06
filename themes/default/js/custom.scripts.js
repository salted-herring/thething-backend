(function($) {
	
	$(document).ready(function() {
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
		/*var url = '/api/v/1/siteapp/a8a623997a94fad5b7d25009df324c5d846b0a9efb224a7c4782c8c376b461cbc';
		var nut = {
			"departure": 1024768,
            "arrival": 1920960,
            "departure-loc": "99,33",
            "arrival-loc": "77,88"
		};
		$.get(
			url,
			{
				accept: 'json',
				get: 'structure'
			},
			
			function(data) {
				shell = data.app_structure;
				for (var key in shell.fields) {
					shell.fields[key] = {
						type: shell.fields[key],
						value: nut[key]
					}
				}
				$.post(
					url,
					{
						accept: 'json',
						access_token: 'foobarbaz',
						submission: shell
					},
					function(data) {
						console.log(data);
					}
				);
			}
			
		);*/
		

	});
	
})(jQuery);

