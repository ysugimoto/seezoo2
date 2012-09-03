<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 * ===============================================================================
 * 
 * seezoo テンプレート管理モデル
 *
 * @package seezoo-cms Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class TemplateModel extends SZ_Kennel
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getTemplateList()
	{
		return ActiveRecord::finder('templates')->findAll();
	}
}
