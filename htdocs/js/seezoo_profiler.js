(function() {
	
	// connection
	if ( ! window.opener ) {
		return;
	}
	
	var FL = window.opener.getInstance(),
		DOM = FL.DOM;
	
	function init() {
		var tabs,
			contents,
			sql,
			sqlResult,
			currentSQL,
			activeTab,
			activeContent;

		FL.connect(window);
		tabs      = DOM('ul.cp_tabs a');
		contents  = DOM('div.cp_content');
		sql       = DOM('td.sql_cell');
		sqlResult = DOM.id('query_result');
		FL.disConnect();

		activeTab     = tabs.get(0);
		activeContent = contents.get(0);

		tabs.foreach(function(num) {
			DOM(this).event('click', function(ev) {
				ev.preventDefault();
				activeTab.removeClass('active');
				activeContent.hide();

				activeTab     = DOM(this).addClass('active');
				activeContent = contents.get(num).show();
			});
		});

		sql.event('click', function(ev) {
			var query = DOM(this).getOne('input[type=hidden]').getValue(),
				pre = sqlResult.first();

			if ( /.*(?:insert|update|delete|drop|alter).+/i.test(query) ) {
					if ( ! confirm('実行しようとするSQLの中にデータ/構造を変更するSQL文が含まれる可能性があります。実行してもよろしいですか？') ) {
						return;
					}
			}
			pre.addClass('loading');
			currentSQL && currentSQL.removeClass('active');
			currentSQL = DOM(this).parent().addClass('active');
			
			FL.ajax.post('action/profiler_sql_exec/' + FL.config.item('sz_token'), {
				param : { "query" : query },
				success : function(resp) {
					pre.removeClass('loading');
					sqlResult.show();
					pre.html(FL.ut.clean(resp.responseText));
					window.scrollTo(0, 0);
				}
			});
		});
	}

	init();
	
	
})();
