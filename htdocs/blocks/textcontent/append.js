(function() {
	
	if ( getInstance().ua.IE ) {
		DOM('a.sz-blockform-close, a.pp_close').event('click', function() {
			location.reload();
		});
	}
})();