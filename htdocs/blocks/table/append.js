(function() {
	
	var FL = getInstance(),
		DOM = FL.DOM;
	
	if ( FL.table_editor2 ) {
		init();
		return;
	}
	
	FL.load.library('table_editor2');

	FL.ready('table_editor2', init);
	
	function init() {
		var that = this;
		
		if ( ! document.getElementById('table-editor-area') ) {
			setTimeout(that, 50);
			return;
		}
		
		FL.table_editor2.init(
				document.getElementById('table-editor-area')
		);
		DOM('div.sz-popup-content').last()
			.getOne('a.button_right')
			.event('click', function(ev) {
				DOM.id('table_data').setValue(FL.table_editor2.buildTableHTML());
			});
	}
	
})();