ClassExtend('Controller', function InfoCategoryController() {
	
	this.index = function() {
		DOM('a.delete').event('click', function(evt) {
			return confirm('情報を削除します。よろしいですか？');
		})
	};
	
	this.edit = function() {
		this.load.library('validation');
		
		this.ready('validation', function() {
			this.__validation();
		});
	};
	
	this.confirm = function() {
		if ( document.getElementById('info_form') ) {
			this.edit();
		}
	};
	
	this.__validation = function() {
		var fields, rules;
		
		fields = {
			category_handle : 'カテゴリハンドル',
			category_name   : 'カテゴリ名'
		};
		rules = {
			category_handle : 'alpha_numeric',
			category_name   : 'required'
		};
		
		this.validation.setFields(fields);
		this.validation.setRules(rules);
		
		this.validation.run(DOM.id('info_form'));
	};
});
