/*$('[href="/omnia/login"]').on('click', function (e) {
	e.preventDefault();
	e.stopPropagation();
	
	console.log('Log In', e);

	$.ajax({
		"url": $(this).attr('href'), 
		"error": function(xhr, options, error) {
			console.log('error', xhr, options, error);
		},
		"success": function(xhr, options, data) {
			console.log('success', xhr, options, data);
		},
		"complete": function(xhr, options) {
			console.log('complete', xhr, options);
		}
	});
	
	return false;
});
*/