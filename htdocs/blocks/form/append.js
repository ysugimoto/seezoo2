
// form edit function
(function() {

	var FL = getInstance(), doc = document;
	FL.load.ajax();

	// types array
	var types = DOM.byName('question_type'), types_edit = DOM.byName('question_type_edit');

	// question name
	var qName = DOM.id('question_name');

	// current show
	var current, currentE;

	// edit target
	var editTarget;

	// event set
	types.event('click', function(ev) {

		var v = this.value;
		if (current) { current.addClass('init_hide');}
		if (v === 'radio') {
			current = DOM.id('option_area_radio').removeClass('init_hide');
		} else if (v === 'checkbox') {
			current = DOM.id('option_area_checkbox').removeClass('init_hide');
		} else if (v === 'select') {
			current = DOM.id('option_area_select').removeClass('init_hide');
		} else if (v === 'textarea') {
			current = DOM.id('option_textarea').removeClass('init_hide');
		} else if (v === 'file') {
			//current = DOM.id('file_option').removeClass('init_hide');
		}
	});
	
	// event set
	types_edit.event('click', function(ev) {

		var v = this.value;
		if (currentE) { currentE.addClass('init_hide');}
		if (v === 'radio') {
			currentE = DOM.id('option_area_radio_edit').removeClass('init_hide');
		} else if (v === 'checkbox') {
			currentE = DOM.id('option_area_checkbox_edit').removeClass('init_hide');
		} else if (v === 'select') {
			currentE = DOM.id('option_area_select_edit').removeClass('init_hide');
		} else if (v === 'textarea') {
			currentE = DOM.id('option_textarea_edit').removeClass('init_hide');
		} else if (v === 'file') {
			//currentE = DOM.id('file_option_edit').removeClass('init_hide');
		}
	});


	// getter functions
	var getType = function(type) {
		var rds = doc.getElementsByName((!type) ? 'question_type' : 'question_type_edit'), len = rds.length, i = 0;
		for (i; i < len; i++) {
			if (rds[i].checked === true) {
				return rds[i].value;
			}
		}
		return '';
	};

	var serializeValidateRules = function(type) {
		var rules = [], chs = doc.getElementsByName((!type) ? 'validate_rules[]' : 'validate_rules_edit[]'), len = chs.length, i = 0;
		for (i; i < len; i++) {
			if (chs[i].checked === true) {
				rules.push(chs[i].value);
			}
		}
		// max length
		var maxL = doc.getElementsByName((!type) ? 'max_length' : 'max_length_edit')[0];
		// min length
		var minL = doc.getElementsByName((!type) ? 'min_length' : 'min_length_edit')[0];

		if (maxL.value !== '') {
			rules.push('max_length[' + maxL.value + ']');
		}
		if (minL.value !== '') {
			rules.push('min_length[' + minL.value + ']');
		}
		return rules.join('|');
	};

	var getRadioOptions = function(type) {
		var ops = [];
		var ps = doc.getElementsByName((!type) ? 'radio_option_label[]' : 'radio_option_label_edit[]'),//, psv = doc.getElementsByName((!type) ? 'radio_option_value[]' : 'radio_option_value_edit[]'),
			len = ps.length, i = 0;
		for (i; i < len; i++) {
			ops.push(ps[i].value);// + '|' + psv[i].value);
		}
		return ops.join(':');
	};

	var getCheckBoxOptions = function(type) {
		var ops = [];
		var ps = doc.getElementsByName((!type) ? 'checkbox_option_label[]' : 'checkbox_option_label_edit[]'),//, psv = doc.getElementsByName((!type) ? 'checkbox_option_value[]' : 'checkbox_option_value_edit[]'),
			len = ps.length, i = 0;
		for (i; i < len; i++) {
			ops.push(ps[i].value);// + '|' + psv[i].value);
		}
		return ops.join(':');
	};

	var getSelectOptions = function(type) {
		var ops = [];
		var ps = doc.getElementsByName((!type) ? 'select_option_label[]' : 'select_option_label_edit[]'),// psv = doc.getElementsByName((!type) ? 'select_option_value[]' : 'select_option_value_edit[]'),
			len = ps.length, i = 0;
		for (i; i < len; i++) {
			ops.push(ps[i].value);// + '|' + psv[i].value);
		}
		return ops.join(':');
	};

	var getTextAreaOptions = function(type) {
		return 'rows:' + doc.getElementsByName('rows')[(!type) ? 0 : 1].value + '|cols:' + doc.getElementsByName('cols')[(!type) ? 0 : 1].value;
	};

//	var getFileOptions = function(type) {
//		var opt = [];
//		var fo = doc.getElementsByName((!type) ? 'file_ext[]' : 'file_ext_edit[]'), len = fo.length, i = 0;
//		for (i; i < len; i++) {
//			if (fo[i].checked === true) {
//				opt.push(fo[i].value);
//			}
//		}
//		return opt.join('|');
//	};

	// validation
	var validate = function(type) {
		var name = doc.getElementById((!type) ? 'question_name' : 'question_name_edit').value,
			types = doc.getElementsByName((!type) ? 'question_type' : 'question_type_edit'),
			error = [], len = types.length, chFlag = false,
			minL = doc.getElementById('rule_min_length').value,
			maxL = doc.getElementById('rule_max_length').value,
			numReg = /^[0-9]+$/;

		if (name == '') {
			error.push('質問名は必須入力です。');
		}
		for (var i = 0; i < len; i++) {
			if (types[i].checked === true) {
				chFlag = true;
			}
		}
		if (chFlag === false) {
			error.push('入力タイプは必須選択です。');
		}
		
		if (minL != '') {
			if ( !numReg.test(minL)) {
				error.push('入力文字数制限は数値で入力してください。');
			}
		}
		if (maxL != '') {
			if ( !numReg.test(maxL)) {
				error.push('入力文字数制限は数値で入力してください。');
			}
		}
		
		if (maxL != '' && minL != '') {
			if (maxL >= minL) {
				error.push('入力文字数制限設定が不正です。');
			}
		}

		if (error.length > 0) {
			alert(error.join('\n'));
			return false;
		} else {
			return true;
		}
	};

	// set empty
	var addFormInitialize = function() {
		DOM('div#content1 input').foreach(function() {

			switch(this.type) {
			case 'text': this.value = '';break;
			case 'radio': this.checked = false;break;
			case 'checkbox': this.checked = false;break;
			}
		});
//		qName.setValue('');
//		DOM('div#content1 input[name=question_type][value=' + p.question_type + ']').get(0).checked = false;//.foreach(function() { this.checked = false;});

		DOM('tr#option_area_radio p').foreach(function(num) {
			if (num === 0) {
				DOM(this).detect('input').foreach(function() { this.value = '';});
			} else {
				DOM(this).remove();
			}
		});
		DOM('tr#option_area_checkbox p').foreach(function(num) {
			if (num === 0) {
				DOM(this).detect('input').foreach(function() { this.value = '';});
			} else {
				DOM(this).remove();
			}
		});
		DOM('tr#option_area_select p').foreach(function(num) {
			if (num === 0) {
				DOM(this).detect('input').foreach(function() { this.value = '';});
			} else {
				DOM(this).remove();
			}
		});
	};

	// add question
	DOM.id('question_add').event('click', function() {
		if (!validate()) { return;}
		var param = {
			question_name : qName.getValue(),
			question_type : getType(),
			validate_rules : serializeValidateRules(),
			question_key : document.getElementsByName('question_key')[0].value,
			class_name : document.getElementById('class_name').value,
			caption : document.getElementById('question_caption').value
		};
		switch (param.question_type) {
		case 'radio':
			param.option = getRadioOptions();
			break;
		case 'checkbox':
			param.option = getCheckBoxOptions();
			break;
		case 'select':
			param.option = getSelectOptions();
			break;
//		case 'textarea':
//			param.option = getTextAreaOptions();
//			break;
		case 'file':
			break; // we not use for our security policy.
			
			param.option = getFileOptions();
			param.size = doc.getElementsByName('file_size')[0].value;
			break;
		}
		param.rows = doc.getElementsByName('sz_form_rows')[0].value;
		param.cols = doc.getElementsByName('sz_form_cols')[0].value;

		FL.ajax.post('ajax/set_form_question/' + FL.config.item('sz_token'), {
			param : param,
			success : function(resp) {
				if (!isNaN(parseInt(resp.responseText, 10))) {
					window.scrollTo(0, DOM.id('form_msg').absDimension().top - 30);
					DOM.id('form_msg').html('質問を追加しました。').animate('appear', {speed : 20, callback : function() {
						setTimeout(function() { DOM.id('form_msg').animate('fade', { spped : 20, afterHide : false});}, 2000);
					}});
					createPreview(param, parseInt(resp.responseText, 10));
					addFormInitialize();
				} else {
					alert('質問の追加に失敗しました。');
				}
				param = {};
				
			}
		});


	});

	// edit question
	DOM.id('question_edit').event('click', function() {
		if (!validate('edit') || !editTarget) { return;}
		var param = {
			edit_target_id : editTarget,
			question_name : DOM.id('question_name_edit').getValue(),
			question_type : getType('edit'),
			validate_rules : serializeValidateRules('edit'),
			question_key : document.getElementById('q_key').value,
			class_name : document.getElementById('class_name_edit').value,
			caption : document.getElementById('question_caption_edit').value
		};
		switch (param.question_type) {
		case 'radio':
			param.option = getRadioOptions('edit');
			break;
		case 'checkbox':
			param.option = getCheckBoxOptions('edit');
			break;
		case 'select':
			param.option = getSelectOptions('edit');
			break;
//		case 'textarea':
//			param.option = getTextAreaOptions('edit');
//			break;
		case 'file':
			break; // we not use for our secutry policy.
			
			param.option = getFileOptions('edit');
			param.size = doc.getElementsByName('file_size')[1].value;
			break;
		}
		param.rows = doc.getElementsByName('sz_form_rows')[1].value;
		param.cols = doc.getElementsByName('sz_form_cols')[1].value;

		FL.ajax.post('ajax/edit_form_question/' + FL.config.item('sz_token'), {
			param : param,
			success : function(resp) {
				if (resp.responseText === 'complete') {
					window.scrollTo(0, DOM.id('form_msg').absDimension().top - 30);
					DOM.id('form_msg').html('質問を編集しました。').animate('appear', {speed : 20, callback : function() {
						setTimeout(function() { DOM.id('form_msg').animate('fade', { spped : 20, afterHide : false});}, 2000);
					}});
					updatePreview(param);
				} else {
					alert('質問の編集に失敗しました。');
				}
				param = {};
			}
		});

	});

	var saveDisplayOrder = function(ev) {
		// make display order object
		var displays = {}, qs = DOM('ul#form_preview li');
		qs.foreach(function(num) {
			displays[this.getAttribute('question_id')] = num + 1;
		});
		FL.ajax.post('ajax/sort_question/' + document.getElementsByName('question_key')[0].value + '/' + FL.config.item('sz_token'), {
			param : displays,
			success : function(resp) {
				if (resp.responseText === 'complete') {
					DOM.id('form_msg').html('表示順を変更しました。').animate('appear', {speed : 20, callback : function() {
						setTimeout(function() { DOM.id('form_msg').animate('fade', { spped : 20, afterHide : false});}, 2000);
					}});
				} else {
					alert('処理に失敗しました。');
				}
			}
		});
	}

	var createPreview = function(p, num) {
		DOM.create('li').addClass('sz_form_list').html('<span>' + p.question_name + '</span>').attr({'question_id' : num, 'is_pending' : 1})
						.append('<p class="sz_form_list_edit"><a href="javascript:void(0)" class="q_edit" qid="' + num + '">編集</a></p>')
						.append('<p class="sz_form_list_delete"><a href="javascript:void(0)" class="q_delete" qid="' + num + '">削除</a></p>')
						.append('<p class="sz_form_list_control"><a href="javascript:void(0)" class="fc_up">▲</a><br /><a href="javascript:void(0)" class="fc_down">▼</a></p>')
						.appendTo(DOM.id('form_preview'));
		if (!DOM.id('sort')) {
			var ipt = DOM.create('input', {id : 'sort', value : '配置順を保存する', type : 'button'}).appendTo(DOM.id('sort_wrapper'));
			ipt.event('click', saveDisplayOrder);
		}
	}

	var updatePreview = function(p) {
		var target = DOM.origCSS('div#content2 li[question_id=' + p.edit_target_id + ']');
		target.get(0, true).detect('span').get(0, true).html(p.question_name);
		
	};

	// add or delete event handler
	var addOption = function(e) {

		var html;
		if (DOM(e).hasClass('radio')) {
			if (DOM(e).hasClass('edit')) {
				e.parent().append('<p>ラベル：<input type="text" name="radio_option_label_edit[]" value="" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete radio">削除</a></p>', 'after');
			} else {
				e.parent().append('<p>ラベル：<input type="text" name="radio_option_label[]" value="" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete radio">削除</a></p>', 'after');
			}
		} else if (DOM(e).hasClass('checkbox')) {
			if (DOM(e).hasClass('edit')) {
				e.parent().append('<p>ラベル：<input type="text" name="checkbox_option_label_edit[]" value="" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete checkbox">削除</a></p>', 'after');
			} else {
				e.parent().append('<p>ラベル：<input type="text" name="checkbox_option_label[]" value="" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete checkbox">削除</a></p>', 'after');
			}
		} else if (DOM(e).hasClass('select')) {
			if (DOM(e).hasClass('edit')) {
				e.parent().append('<p>ラベル：<input type="text" name="select_option_label_edit[]" value="" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete select">削除</a></p>', 'after');
			} else {
				e.parent().append('<p>ラベル：<input type="text" name="select_option_label[]" value="" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete select">削除</a></p>', 'after');
			}
		}
	};

	var deleteOption = function(e) {
		if (DOM(e).hasClass('radio') || DOM(e).hasClass('checkbox') || DOM(e).hasClass('select')) {
			if (e.parent().parent().detect('p').length === 1) {
				alert('これ以上は削除できません。');
				return;
			}
			e.parent().remove();
		}
	};

	DOM('table.add_forms').event('click', function(ev) {
		var e = DOM(ev.target);
		
		if (e.tag !== 'a' || !(e.hasClass('option_add') || e.hasClass('option_delete'))) { return;}
		if (e.hasClass('option_add')) {
			addOption(e);
		} else if (e.hasClass('option_delete')) {
			deleteOption(e);
		}
	});

	var sortDisplay = function(e, m) {
		if (m === 1) { // case 'UP'
			if (e.prev()) {
				e.appendTo(e.prev(), 'before');
				Animation.highLight(e);
			}
		} else if (m === 2){ // case 'DOWN'
			if (e.next()) {
				e.appendTo(e.next(), 'after');
				Animation.highLight(e);
			}
		}
	};

	var deleteQuestion = function(e) {
		if (!confirm('選択した質問を削除します。よろしいですか？')) {
			return;
		}
		FL.ajax.get('ajax/delete_form_question/' + e.readAttr('qid') + '/' + FL.config.item('sz_token'), {
			success : function(resp) {
				if (resp.responseText === 'complete') {
					DOM.id('form_msg').html('質問を削除しました。').animate('appear', {speed : 20, callback : function() {
						setTimeout(function() { DOM.id('form_msg').animate('fade', { spped : 20, afterHide : false});}, 2000);
					}});
					var p = e.parent().parent();
					Animation.fade(p, {to : 0, callback : function(){ p.remove();}});
				} else {
					alert('処理に失敗しました。');
				}
			}
		});
	};

	var editQuestion = function(e) {
		// tab change
		tabs.foreach(function(){this.className = '';});
		currentTab = tabs.get(1, true).addClass('active');
		currnetBox = DOM.id('content4').removeClass('init_hide');
		DOM.id('content2').addClass('init_hide');
		DOM.id('edit_table').removeClass('init_hide');
		DOM.id('edit_select_msg').hide();
		editTarget = e.readAttr('qid');

		// get question_data and set form
		FL.ajax.get('ajax/get_form_parts_data/' + e.readAttr('qid') + '/' + FL.config.item('sz_token'), {
			success : function(resp) {
				eval('var data=' + resp.responseText);

				// set input values
				DOM.id('question_name_edit').setValue(data.question_name);
				DOM.id('class_name_edit').setValue(FL.ut.clean(data.class_name));
				DOM.id('question_caption_edit').setValue(FL.ut.clean(data.caption));
				var types = DOM.byName('question_type_edit').foreach(function(){ this.checked = false;});
				DOM('#content4 tr[id]').foreach(function() { DOM(this).addClass('init_hide');});
				switch (data.question_type) {
				case 'text':
					types.get(0).get().checked = true;
					break;
				case 'textarea':
					types.get(1).get().checked = true;
					DOM.id('option_textarea_edit').removeClass('init_hide')
					.detect('input').get(0, true).setValue(data.rows)
					.parent().detect('input').get(1, true).setValue(data.cols);
					break;
				case 'radio':
					types.get(2).get().checked = true;
					var node = DOM.id('option_area_radio_edit').removeClass('init_hide');
					var opt = (FL.ut.grep(data.options, ':')) ? data.options.split(':') : [data.options];
					var html = [], i = 0, len = opt.length, d = node.detect('div').get(0, true), lv;
					for (i; i < len; i++) {
						lv = opt[i];
						html.push('<p>ラベル：<input type="text" name="radio_option_label_edit[]" value="' + lv + '" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete radio">削除</a></p>');
					}
					d.html(html.join('\n'));
					break;
				case 'checkbox':
					types.get(3).get().checked = true;
					var node = DOM.id('option_area_checkbox_edit').removeClass('init_hide');
					var opt = (FL.ut.grep(data.options, ':')) ? data.options.split(':') : [data.options];
					var html = [], i = 0, len = opt.length, d = node.detect('div').get(0, true), lv;
					for (i; i < len; i++) {
						lv = opt[i];
						html.push('<p>ラベル：<input type="text" name="checkbox_option_label_edit[]" value="' + lv + '" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete checkbox">削除</a></p>');
					}
					d.html(html.join('\n'));
					break;
				case 'select':
					types.get(4).get().checked = true;
					var node = DOM.id('option_area_select_edit').removeClass('init_hide');
					var opt = (FL.ut.grep(data.options, ':')) ? data.options.split(':') : [data.options];
					var html = [], i = 0, len = opt.length, d = node.detect('div').get(0, true), lv;
					for (i; i < len; i++) {
						lv = opt[i];
						html.push('<p>ラベル：<input type="text" name="select_option_label_edit[]" value="' + lv + '" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete select">削除</a></p>');
					}
					d.html(html.join('\n'));
					break;
				case 'file':
					break; // we not use for our security policy.
					
					//types.get(5).get().checked = true;
					//var node = DOM.id('file_option_edit').removeClass('init_hide'), chs = node.detect('p').get(0, true).detect('input');
					//var filetypes = data.accept_ext.split('|'), i = 0, len = filetypes.length, ob = {};
					//for (i; i < len; i++) { ob[filetypes[i]] = true;}
					//chs.foreach(function() {
					//	if (this.value in ob) { this.checked = true;}
					//});
					//node.detect('p').get(1, true).detect('input').get(0, true).setValue(data.max_file_size);
					break;
				case 'pref':
					types.get(6).get().checked = true;
					break;
				case 'email':
					types.get(5).get().checked = true;
					break;
				case 'birth_year':
					types.get(7).get().checked = true;
					break;
				case 'month':
					types.get(8).get().checked = true;
					break;
				case 'day':
					types.get(9).get().checked = true;
					break;
				case 'hour':
					types.get(10).get().checked = true;
					break;
				case 'minute':
					types.get(11).get().checked = true;
					break;

				default : break;
				}

				// set validate rules
				var rules = (FL.ut.grep(data.validate_rules, '|')) ? data.validate_rules.split('|') : [data.validate_rules];
				var r = 0, rlen = rules.length, rs = DOM.byName('validate_rules_edit[]'), rob = {}, maxL, minL;
				for (r; r < rlen; r++) {
					if (FL.ut.grep(rules[r], 'min_length')) { minL = rules[r].replace(/min_length\[(.*)\]/, '$1'); }
					else if (FL.ut.grep(rules[r], 'max_length')) { maxL = rules[r].replace(/max_length\[(.*)\]/, '$1');}
					else {rob[rules[r]] = true;}
				}
				rs.foreach(function() {
					if (this.value in rob) { this.checked = true;}
					else {this.checked = false;}
				});
				if (minL) {
					document.getElementsByName('min_length_edit')[0].value = minL;
				}
				if (maxL) {
					document.getElementsByName('max_length_edit')[0].value = maxL;
				}
			}
		});
	};

	DOM.id('form_preview').event('click', function(ev) {
		var e = DOM(ev.target);
		if (e.tag !== 'a') { return;}
		if (e.hasClass('fc_up')) {
			sortDisplay(e.parent().parent(), 1);
		} else if (e.hasClass('fc_down')) {
			sortDisplay(e.parent().parent(), 2);
		} else if (e.hasClass('q_edit')) {
			editQuestion(e);
		} else if (e.hasClass('q_delete')) {
			deleteQuestion(e);
		}
	});

	DOM.id('is_remail').event('click', function() {
		if (this.checked === true) {
			DOM.id('remail').show().detect('input').get(0).disabled = false;
		} else {
			DOM.id('remail').hide().detect('input').get(0).disabled = true;
		}
	})

	// for edit type
	if (DOM.id('sort')) {
		DOM.id('sort').event('click', saveDisplayOrder);
	}
//	// option add or delete event
//	FL.event.exprLive('div#content1 a.option_add', 'click', addOption);
//	FL.event.exprLive('div#content1 a.option_delete', 'click', deleteOption);

	var tabs = DOM('ul#sz_form_tab a');
	// current tab and box
	var currentTab, currentBox;

	tabs.foreach(function() {
		if (this.className === 'active') {
			currentTab = DOM(this);
			currentBox = DOM.id(this.href.slice(this.href.indexOf('#') + 1));
		}
	});

	tabs.event('click', function(ev) {
		ev.preventDefault();
		var href = this.href;
		var flag = (href.indexOf('content4') !== -1);
		currentTab.removeClass('active');
		currentBox.addClass('init_hide');
		if (flag) {
			
			DOM.id('edit_select_msg').show();
		}else {
			
			//DOM.id('edit_select_msg').show();
		}
		DOM.id('edit_table').addClass('init_hide');
		currentTab = DOM(this).addClass('active');
		currentBox = DOM.id(href.slice(href.indexOf('#') + 1)).removeClass('init_hide')
	});

	// row decorate
	DOM.id('form_preview').event('mouseover', function(ev) {
		var e = ev.target;
		while(e && e.tagName && e.tagName.toLowerCase() !== 'li') {
			e = e.parentNode;
		}
		if (!e || !e.style) { return;}
		e.style.backgroundColor = '#eaf7ff';
	})
	.event('mouseout', function(ev) {
		var e = ev.target;
		while(e && e.tagName && e.tagName.toLowerCase() !== 'li') {
			e = e.parentNode;
		}
		if (!e || !e.style) { return;}
		e.style.backgroundColor = '#fff';
	});
	
//	// pending hookes
//	DOM('a.sz-blockform-close').event('click', function(ev) {
//		var pendings = [];
//		
//		DOM('ul#form_preview li[is_pending=1]').foreach(function() {
//			pendings.push(this.getAttribute('is_pending'));
//		});
//		
//		if (pendings.length > 0) {
//			if (confirm('このウインドウを閉じると追加した質問は破棄されます。\n\nよろしいですか？')) {
//				FL.ajax.post('ajax/delete_pending_forms/' + FL.config.item('sz_token'), {
//					param : {pendings : pendings.join(':')}
//				});
//			} else {
//				ev.preventDefault();
//			}
//		}
//	});
})();