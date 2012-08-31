<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ============================================================================
 * Seezoo フォーム設置ブロックコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto<neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */
class Form_block extends Block
{
	/** common lock settings */
	protected $table = 'sz_bt_forms';
	protected $sub_table = 'sz_bt_questions';
	protected $answer_table = 'sz_bt_question_answers';
	protected $block_name = 'お問い合わせフォームブロック';
	protected $description = 'お問い合わせフォームを設置します。';
	protected $interface_width = 700;
	protected $interface_height = 600;

	/** block properties **/
	protected $route;
	protected $upload_exists = FALSE;
	protected $uploaded_file = '';
	protected $validated_errors = array();
	protected $validated_values = array();
	protected $ignore_escape = array('thanks_msg');

	public $ticket_name = 'sz_form_ticket';
	public $forms = array();
	public $ticket_miss = FALSE;
	public $ticket = '';
	
	// default mail subject/body
	const DEFAULT_MAIL_SUBJECT = 'お問い合わせありがとうございます。';
	const DEFAULT_MAIL_BODY    = "お問い合わせ頂きありがとうございます。\n\n以下の内容で送信されました：\n\n{{FORM_DATA}}";
	

	public function db()
	{
		$dbst = array(
			'block_id'	=> array(
							'type'			=> 'INT',
							'constraint'	=> 11
						),
			'question_key'	=> array(
							'type'			=> 'VARCHAR',
							'constraint'	=> 255,
							'default'		=> 0
						),
			'form_title'	=> array(
							'type'			=> 'VARCHAR',
							'constraint'	=> 255,
							'null'			=> TRUE
						),
			'use_captcha'	=> array(
							'type'			=> 'INT',
							'constraint'	=> 1,
							'default'		=> 0
						),
			'is_remail'	=> array(
							'type'			=> 'INT',
							'constraint'	=> 1,
							'default'		=> 0
						),
			're_mail'		=> array(
							'type'			=> 'VARCHAR',
							'constraint'	=> 255,
							'default'		=> 0
						),
			'thanks_msg'	=> array(
							'type'			=> 'TEXT'
						),
			'form_class_name' => array(
							'type'			=> 'VARCHAR',
							'constraint'	=> 255
						),
			'auto_reply_mailbody' => array(
							'type'			=> 'TEXT'
						),
			'auto_reply_mail_subject'	=> array(
							'type'			=> 'VARCHAR',
							'constraint'	=> 255
						)
		);

		$dbst_sub = array(
			'question_id'	=> array(
								'type'			=> 'INT',
								'constraint'	=> 1,
								'key'			=> TRUE,
								'auto_increment'=> TRUE
							),
			'question_key'	=> array(
								'type'			=> 'VARCHAR',
								'constraint'	=> 255,
								'default'		=> 0
							),
			'question_name'	=> array(
								'type'			=> 'VARCHAR',
								'constraint'	=> 255,
								'default'		=> 0
							),
			'question_type'	=> array(
								'type'			=> 'VARCHAR',
								'constraint'	=> 255,
								'default'		=> 0
							),
			'validate_rules'=> array(
								'type'			=> 'VARCHAR',
								'constraint'	=> 255,
								'null'			=> TRUE
							),
			'rows'			=> array(
								'type'			=> 'INT',
								'constraint'	=> 5,
								'null'			=> TRUE
							),
			'cols'			=> array(
								'type'			=> 'INT',
								'constraint'	=> 5,
								'null'			=> TRUE
							),
			'options'		=> array(
								'type'			=> 'TEXT'
							),
			'accept_ext'	=> array(
								'type'			=> 'VARCHAR',
								'constraint'	=> 255,
								'null'			=> TRUE
							),
			'max_file_size'	=> array(
								'type'			=> 'INT',
								'constraint'	=> 5,
								'default'		=> 100
							),
			'display_order'	=> array(
								'type'			=> 'INT',
								'constraint'	=> 3,
								'default'		=> 1
							),
			'class_name'	=> array(
								'type'			=> 'VARCHAR',
								'constraint'	=> 255,
								'null'				=> TRUE
							),
			'caption'	=> array(
								'type'			=> 'VARCHAR',
								'constraint'	=> 255,
								'null'				=> TRUE
							)
		);

		$q_answer = array(
			'question_key'	=> array(
								'type'			=> 'VARCHAR',
								'constraint'	=> 255,
								'default'		=> 0,
							),
			'question_id'	=> array(
								'type'			=> 'INT',
								'constraint'	=> 11,
								'default'		=> 0
							),
			'answer'		=> array(
								'type'			=> 'VARCHAR',
								'constraint'	=> 255,
								'null'			=> TRUE
							),
			'answer_text'	=> array(
								'type'			=> 'TEXT',
								'null'			=> TRUE
							),
			'post_date'		=> array(
								'type'			=> 'DATETIME',
								'default'		=> '0000-00-00 00:00:00'
							)
		);

		return array(
				$this->table			=> $dbst,
				$this->sub_table		=> $dbst_sub,
				$this->answer_table	=> $q_answer
		);
	}

	/*
	 * This block has db relations.
	 * So that, manual duplicate in override super class
	 */
	public function duplicate()
	{
		$new_key = $this->generate_key();
		// update key
		$this->_block_record['question_key'] = $new_key;

		// duplicate base DB
		$new_bid = parent::duplicate();

		// duplicate question [relations duplicate]
		$sql = 'SELECT * FROM ' . $this->sub_table . ' WHERE question_key = ?';
		$query = $this->ci->db->query($sql, array($this->question_key));

		foreach ($query->result_array() as $v)
		{
			$v['question_key'] = $new_key;
			unset($v['question_id']);
			$this->ci->db->insert($this->sub_table, $v);
		}

		return $new_bid;
	}

	public function generate_key()
	{
		return sha1(microtime());
	}

	public function accept_file_ext()
	{
		$ext = array(
			'gif'	=> '.gif',
			'jpg'	=> '.jpg',
			'jpeg'	=> '.jpeg',
			'png'	=> '.png',
			'bmp'	=> '.bmp',
			'doc'	=> '.doc',
			'rft'	=> '.rft',
			'pdf'	=> '.pdf'
		);
		return $ext;
	}

	public function get_user_email()
	{
		$uid = $this->ci->session->userdata('user_id');
		$sql = 'SELECT email FROM users WHERE user_id = ? LIMIT 1';
		$query = $this->ci->db->query($sql, array((int)$uid));

		if ($query->row())
		{
			$result = $query->row();
			return $result->email;
		}
		return '';
	}

	public function set_routing()
	{
		$this->ticket_name = 'sz_form_ticket_' . $this->block_id;
		$this->forms = $this->get_forms();
		
		// action routing
		$p = $this->ci->input->post('action' . $this->block_id);
		
		if ($p === 'confirm')
		{
			$this->route = 'confirm';
			$tch = $this->_check_ticket();
			if (!$tch)
			{
				$this->route = 'init';
			}
		}
		else if ($p === 'send')
		{
			$this->route = 'send';
			$tch = $this->_check_ticket();
			if (!$tch)
			{
				$this->route = 'init';
			}
		}
		else
		{
			$this->route = 'init';
		}
		
		// If routing result is "init", stop process
		if ( $this->route === 'init' )
		{
			return $this->route;
		}

		// validation set is One form only!
		$this->_set_validation();

		if ($this->route !== 'init')
		{
			$ret = $this->ci->form_validation->run();
//			if ($this->upload_exists !== FALSE)
//			{
//				$ret = $this->_try_upload();
//			}
		}
		else
		{
			$ret = TRUE;
		}

		// is use captcha?
		if ($this->use_captcha > 0 && $this->route === 'confirm')
		{
			$ret2 = $this->_check_captcha();
		}
		else
		{
			$ret2 = TRUE;
		}

		$this->_set_validated_data();

		if (!$ret || !$ret2)
		{
			if ($this->route === 'confirm')
			{
				$this->ticket = $this->ci->session->userdata($this->ticket_name);
				$this->route = 'init';
			}
			else if ($this->route === 'send')
			{
				exit('データの相違がありました。');
			}
		}
		else if ($this->route === 'send')
		{
			$this->_regist_process();
		}

		return $this->route;
	}

	public function validation_error($id)
	{
		if (array_key_exists($id, $this->validated_errors))
		{
			return $this->validated_errors[$id];
		}
		return '';
	}

	public function validation_value($id)
	{
		if (array_key_exists($id, $this->validated_values))
		{
			return $this->validated_values[$id];
		}
		return '';

	}

	public function validation_values()
	{
		return $this->validated_values;
	}

	public function generate_token()
	{
		if (!empty($this->ticket))
		{
			return $this->ticket;
		}
		$ticket = md5(uniqid(mt_rand(), TRUE));
		$this->ci->session->set_userdata($this->ticket_name, $ticket);
		return $ticket;
	}

	public function generate_captcha()
	{
		$this->ci->load->plugin('captcha');
		$vals = array(
			'img_path'   => './files/captcha/',
			'img_url'    => file_link() . 'files/captcha/',
			'font_path'  => './system/fonts/mikachan.ttf',
			'font_size'  => 12
		);

		$captcha = create_captcha($vals);

		$this->ci->session->set_userdata('captcha_value' . $this->block_id, $captcha['word']);

		return $captcha['image'];
	}

	public function get_self_path()
	{
		return (($this->ci->is_ssl_page) ? ssl_page_link() : page_link()) . ltrim($this->ci->uri->uri_string, '/');
	}

	public function get_site_data()
	{
		return 'お問い合わせフォーム';
	}

	public function is_required($q)
	{
		return (preg_match('/required/', $q->validate_rules)) ? TRUE : FALSE;
	}

	public function get_forms()
	{
		$sql =
			'SELECT '
			.	'* '
			.'FROM '
			. $this->sub_table . ' '
			.'WHERE '
			.	'question_key = ? '
			.'ORDER BY '
			.	'display_order ASC'
			;
		$query = $this->ci->db->query($sql, array($this->question_key));

		return $query->result();
	}

	public function edit_forms()
	{
		$sql =
			'SELECT '
			.	'* '
			.'FROM '
			. $this->sub_table . ' '
			.'WHERE '
			.	'question_key = ? '
			.'ORDER BY '
			.	'display_order ASC'
			;
		$query = $this->ci->db->query($sql, array($this->question_key));

		return $query->result();

	}

	public function build_form_parts($form)
	{
		$v = $this->ci->input->post('question_' . $form->question_id);
		$parts = '';
		switch ($form->question_type)
		{
			case 'text':
			case 'email':
				$parts = form_input(array(
									'name' => 'question_' . $form->question_id,
									'class'	=> prep_str($form->class_name),
									'value' => $v
								));
				break;
			case 'radio':
				$parts = $this->_parse_radio($form, $v);
				break;
			case 'checkbox':
				$parts = $this->_parse_checkbox($form, $v);
				break;
			case 'textarea':
				$parts = form_textarea(array(
									'name' => 'question_' . $form->question_id,
									'rows' => $form->rows,
									'cols' => $form->cols,
									'value' => $v
								));
				break;
			case 'select':
				$parts = $this->_parse_select($form, $v);
				break;
			case 'pref':
				$form->options = $this->_build_pref_list();
				$parts = $this->_parse_select($form, $v);
				break;
			case 'birth_year':
				$form->options = $this->_build_birth_year_list();
				$parts = $this->_parse_select($form, $v, '年');
				break;
			case 'month':
				$form->options = $this->_build_month_list();
				$parts = $this->_parse_select($form, $v, '月');
				break;
			case 'day':
				$form->options = $this->_build_day_list();
				$parts = $this->_parse_select($form, $v, '日');
				break;
			case 'hour':
				$form->options = $this->_build_hour_list();
				$parts = $this->_parse_select($form, $v, '時');
				break;
			case 'minute':
				$form->options = $this->_build_minute_list();
				$parts = $this->_parse_select($form, $v, '分');
				break;
			case 'file':
				break;
				// sorry, not implement for security problems.
				
				//return form_upload(array('name' => 'question_' . $form->question_id));
			default : return '';
		}
		if ( ! empty($form->caption) )
		{
			$parts .= '&nbsp;<span>' . prep_str($form->caption) . '</span>';
		}
		return $parts;
	}

	public function build_form_parts_confirm($form, $is_mail = FALSE)
	{
		switch ($form->question_type)
		{
			case 'text':
			case 'email':
				return form_prep((string)$this->validation_value($form->question_id));
			case 'textarea':
				$v = form_prep((string)$this->validation_value($form->question_id));
				return ($is_mail === FALSE ) ? nl2br($v) : $v;
			case 'radio':
			case 'select':
			case 'pref':
			case 'birth_year':
			case 'month':
			case 'day':
			case 'hour':
			case 'minute':
				$key = $this->validation_value($form->question_id);
				
				if ( method_exists($this, '_build_' . $form->question_type . '_list') )
				{
					$form->options = $this->{'_build_' . $form->question_type . '_list'}();
				}

				if ($key === FALSE)
				{
					return '';
				}
				$list = explode(':', $form->options);
				if ($list !== FALSE)
				{
					if (array_key_exists($key, $list) && $list[$key] !== '')
					{
						return form_prep($list[$key]);
					}
					else
					{
						return '';
					}
				}
				else
				{
					return form_prep($form->options);
				}
			case 'checkbox':
				$key = $this->validation_value($form->question_id);

				if ( $key === FALSE )
				{
					return '';
				}
				if ( ! is_array($key) )
				{
					$key = array($key);
				}
				$list = explode(':', $form->options);
				$out_stack = array();
				
				if ($list !== FALSE)
				{
					foreach ($key as $v)
					{
						if (array_key_exists($v, $list) && $list[$v] !== '')
						{
							$out_stack[] = form_prep($list[$v]);
						}
					}
					return implode(', ', $out_stack);
				}
				else
				{
					foreach ($key as $v)
					{
						if ($v === $from->options)
						{
							$out_stack[] = form_prep($form->options);
						}
					}
					return implode(', ', $out_stack);
				}
			case 'file':
				//return $this->uploaded_file;
			default : break;
		}
	}

	protected function _parse_radio($form, $v = FALSE)
	{
		$ret = array();
		$opt = $form->options;
		if (strpos($opt, ':') === FALSE)
		{
			$data = array($opt);
		}
		else
		{
			$data = explode(':', $opt);
		}
		foreach ($data as $key => $value)
		{
			// exploded value is empty?
			if ($value == '')
			{
				continue; // skip
			}

			$checked = ($key == $v) ? TRUE : FALSE;
			$ret[] = '<label>' . form_radio('question_' . $form->question_id, $key, $checked, 'class="' . $form->class_name . '"') . '&nbsp;' . $value . '</label>';
		}

		return implode("\n", $ret);
	}

	protected function _parse_checkbox($form, $v = array())
	{
		$ret = array();
		$opt = $form->options;
		$in = (!$v) ? array() : $v;
		if (strpos($opt, ':') === FALSE)
		{
			$data = array($opt);
		}
		else
		{
			$data = explode(':', $opt);
		}

		foreach ($data as $key => $value)
		{
			// exploded value is empty?
			if ($value == '')
			{
				continue; // skip
			}
			// checked value?
			$checked = (in_array($key, $in)) ? TRUE : FALSE;
			$ret[] = '<label>' . form_checkbox('question_' . $form->question_id . '[]', $key, $checked, 'class="' . $form->class_name . '"') . '&nbsp;' . $value . '</label>';
		}

		return implode("\n", $ret);
	}

	protected function _parse_select($form, $v = '', $suffix = '')
	{
		$opt = $form->options;
		$dropdowns = array();
		$selected = 0;
		if (strpos($opt, ':') === FALSE)
		{
			$data = array($opt);
		}
		else
		{
			$data = explode(':', $opt);
		}
		foreach ($data as $key=> $value)
		{
			// exploded value is empty?
			if ($value === '')
			{
				continue; // skip
			}

			if ($key == $v)
			{
				$selected = $key;
			}
			$dropdowns[$key] = $value;
		}
		return form_dropdown('question_' . $form->question_id, $dropdowns, $selected, 'class="' . $form->class_name . '"') . $suffix;
	}

//	// temporary upload file and display
//	protected function _try_upload()
//	{
//		$form = $this->upload_exists;
//
//		// load CI upload Library
//		$this->ci->load->library('upload');
//		// upload config
//		$conf = array(
//			'upload_path'		=> 'files/upload_tmp/',
//			'allowed_types'	=> $form->accept_ext,
//			'overwrite'		=> FALSE,
//			'encrypt_name'	=> TRUE,
//			'remove_spaces'	=> TRUE,
//			'max_size'			=> $form->max_file_size
//		);
//
//		$this->ci->upload->initialize($conf);
//
//		// try Upload!
//		$up = $this->ci->upload->do_upload('question_' . $form->question_id);
//
//		// upload missed...
//		if (!$up)
//		{
//			// set validated data and Error
//			$this->validated_errors[$form->question_id] = $this->ci->upload->display_errors('', '');
//			$this->uploaded_file = '';
//			return FALSE;
//		}
//		$this->validated_errors[$form->question_id] = ''; // no error!
//
//		// get uploaded_file informations
//		$data = $this->ci->upload->data();
//
//		// upload file is image, display <img> tag
//		if ($data['is_image'] > 0)
//		{
//			$this->uploaded_file = '<img src="' . file_link() . 'files/upload_tmp/'
//										. $data['raw_name'] . $data['file_ext']
//										. '" width="' . $data['image_width']
//										. '" height="' . $data['image_height']
//										. '" />';
//		}
//		// else, display filename only.
//		else
//		{
//			$this->uploaded_file = $data['orig_name'];
//		}
//		$this->validated_values[$form->question_id] = $data['raw_name'] . $data['file_ext'];
//
//		return TRUE;
//	}
	
	
	/**
	 * build prefecture list
	 */
	private function _build_pref_list()
	{
		$pref_list = array(
			'北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県',
			'埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県',
			'岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
			'鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県',
			'佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
		);
		return implode(':', $pref_list);
	}
	
	/**
	 * build month list
	 */
	private function _build_month_list()
	{
		$month_list = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
		return implode(':', $month_list);
	}
	
	/**
	 * build day list
	 */
	private function _build_day_list()
	{
		$day_list = array();
		for ( $i = 1; $i < 32; $i++ )
		{
			$day_list[] = $i;
		}
		return implode(':', $day_list);
	}
	
	private function _build_birth_year_list()
	{
		$i = (int)date('Y');
		$y = $i - 60;
		while ( $i >= $y )
		{
			$year_list[] = $y++;
		}
		return implode(':', $year_list);
	}
	
	private function _build_hour_list()
	{
		$hour_list = array();
		for ( $i = 1; $i < 25; $i++ )
		{
			$hour_list[] = $i;
		}
		return implode(':', $hour_list);
	}
	
	private function _build_minute_list()
	{
		$minute_list = array();
		for ( $i = 0; $i < 61; $i++ )
		{
			$minute_list[] = ( $i < 10 ) ? '0' . $i : $i;
			$i++;
		}
		return implode(':', $minute_list);
	}
	

	protected function _set_validation()
	{
		$this->ci->load->library('form_validation');
		$this->ci->form_validation->set_error_delimiters('<p class="validation_error">', '</p>');

		$conf = array();

		foreach ($this->forms as $value)
		{
//			if ($value->question_type == 'file')
//			{
//				$this->upload_exists = $value; // upload check flag ON
//				continue;
//			}
			$conf[] = array(
				'field'		=> 'question_' . $value->question_id,
				'label'		=> $value->question_name,
				'rules'		=> $value->validate_rules
			);
		}

		$this->ci->form_validation->set_rules($conf);
	}
	protected function _check_captcha()
	{
		$this->validated_values['captcha_value'] = form_prep($this->ci->input->post('captcha_value'));
		if ($this->ci->input->post('captcha_value') !== $this->ci->session->userdata('captcha_value' . $this->block_id))
		{
			$this->validated_errors['captcha_value'] = '<p class="validation_error">画像認証の値が正しくありません。</p>';
			return FALSE;
		}
		return TRUE;
	}

	protected function _set_validated_data()
	{
		foreach ($this->forms as $value)
		{
//			if ($value->question_type == 'file')
//			{
//				continue; // skip upload
//			}
			$this->validated_errors[$value->question_id] = $this->ci->form_validation->error('question_' . $value->question_id);
			$this->validated_values[$value->question_id] = $this->ci->input->post('question_' . $value->question_id);
		}
	}

	protected function _check_ticket()
	{
		$ticket = $this->ci->input->post($this->ticket_name);
		if (!$ticket || $ticket !== $this->ci->session->userdata($this->ticket_name))
		{
			$this->ticket_miss = TRUE;
			return FALSE;
		}
		return TRUE;
	}

	protected function _regist_process()
	{
		// save to report record
		$data = array(
			'question_key'	=> $this->question_key,
			'post_date'		=> db_datetime()
		);
		$auto_reply_targets = array();

		foreach ($this->forms as $value)
		{
			$data['question_id'] = $value->question_id;
			if ($value->question_type === 'textarea')
			{
				$data['answer'] = '';
				$data['answer_text'] = $this->validated_values[$value->question_id];
			}
			else if ($value->question_type === 'checkbox')
			{
				if ($this->validated_values[$value->question_id])
				{
					$data['answer'] = implode(':', $this->validated_values[$value->question_id]);
				}
				else
				{
					$data['answer'] = '';
				}
				$data['answer_text'] = '';
			}
			else
			{
				// email reply_hook
				if ( $value->question_type === 'email' )
				{
					$auto_reply_targets[] = $this->validated_values[$value->question_id];
				}
				$data['answer'] = $this->validated_values[$value->question_id];
				$data['answer_text'] = '';
			}

			$ret = $this->ci->db->insert($this->answer_table, $data);
		}
		
		// create mail data
		foreach ($this->forms as $form)
		{
			$answers[] = array(
				'name'		=> $form->question_name,
				'value'	=> $this->build_form_parts_confirm($form, TRUE)
			);
		}

		// if remail send, load library
		if ((int)$this->is_remail > 0)
		{
			@require_once(APPPATH . 'libraries/thirdparty/qdmail.php');

			// build mail parameters
			// load settings, and set $mail array to this scope.
			$path = 'blocks/form/mail_settings/mail_settings.php';
			// package file exists?
			if (file_exists(SZ_EXT_PATH . $path))
			{
				require(SZ_EXT_PATH . $path);
			}
			else if (file_exists(FCPATH . $path))
			{
				require(FCPATH . $path);
			}
			else 
			{
				return FALSE;
			}
			// require file and create $mail array variable.
			$mail['from'] = ($this->ci->site_data->system_mail_from)
								? $this->ci->site_data->system_mail_from
								: $mail['from'];

			$maildata->answers = $answers;
			$maildata->validated_values = $this->validated_values;
			$maildata->form_title = $this->form_title;
		
			$mailbody = $this->ci->load->block_view('form/mail_settings/mailbody', $maildata, TRUE);

			// safed japanese mail setting.
			mb_language('ja');

			$ret = qd_send_mail($mail['protocol'], $this->re_mail, $mail['subject'], $mailbody, $mail['from']);
			
			// insert mail log
			$LOG =& load_class('Log');
			$LOG->write_mail_log($mail['subject'], $this->re_mail, $mailbody, $ret);
		}
		
		if ( count($auto_reply_targets) > 0 )
		{
			if ( ! function_exists('qd_send_mail') )
			{
				@require_once('qdmail.php');
				// safed japanese mail setting.
				mb_language('ja');
			}
			
			if ( ! isset($mail) )
			{
				// build mail parameters
				// load settings, and set $mail array to this scope.
				$path = 'blocks/form/mail_settings/mail_settings.php';
				// package file exists?
				if (file_exists(SZ_EXT_PATH . $path))
				{
					require(SZ_EXT_PATH . $path);
				}
				else if (file_exists(FCPATH . $path))
				{
					require(FCPATH . $path);
				}
				else 
				{
					return FALSE;
				}
			}

			// require file and create $mail array variable.
			$mail['from'] = ($this->ci->site_data->system_mail_from)
			                 ? $this->ci->site_data->system_mail_from
			                 : $mail['from'];
			
			$answer_string = '';
			foreach ( $answers as $answer )
			{
				$answer_string .= $answer['name'] . ' : ' . $answer['value'] . "\n";
			}
		
			$mailbody = str_replace('{{FORM_DATA}}', $answer_string, $this->auto_reply_mailbody);
			if ( empty($mailbody) )
			{
				$mailbody = str_replace('{{FORM_DATA}}', $answer_string, self::DEFAULT_MAIL_BODY);
			}
			$subject  = preg_replace('/[\r|\n|\r\n]/u', '', $this->auto_reply_mail_subject);
			if ( empty($subject) )
			{
				$subject = self::DEFAULT_MAIL_SUBJECT;
			}
			
			// loop and send
			$LOG =& load_class('Log');
			foreach ( $auto_reply_targets as $to )
			{
				$ret = qd_send_mail($mail['protocol'], $to, $mail['subject'], $mailbody, $mail['from']);
				
				// insert mail log
				$LOG->write_mail_log($mail['subject'], $this->re_mail, $mailbody, $ret);
			}
		}
	}
}
