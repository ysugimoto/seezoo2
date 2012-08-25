<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ====================================================
 * Seezoo EventHook class
 * 
 * call method when CMS action occured.
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * @notice process in these method don't call exit or die and redirect
 * ====================================================
 */
class EventHook
{
	/**
	 * on_page_add
	 * mthod called on page add
	 * @param array $data ($key : $value)
	 *          page_id         : added page_id
	 *          page_path       : added page_path
	 *          page_titile     : added page_title
	 *          meta_title      : added meta_title
	 *          meta_description: added meta description
	 *          template_id     : use template_id
	 *          navigation_show : added page can show navigation?(0:no,1:yes)
	 *          parent          : parent page id
	 *          version_number  : added page version (always 1)
	 */
	public function on_page_add($data = array())
	{
		// Page add Events fired. Write some process codes here.
	}
	
	
	/**
	 * on_page_edit
	 * mthod called on page edit
	 * @param array $data ($key : $value)
	 *          page_id         : edited page_id
	 *          page_path       : edited page_path
	 *          page_titile     : edited page_title
	 *          meta_title      : edited meta_title
	 *          meta_description: edited meta description
	 *          template_id     : use template_id
	 *          navigation_show : edited page can show navigation?(0:no,1:yes)
	 *          parent          : parent page id
	 *          version_number  : edited page version 
	 */
	public function on_page_edit($data = array())
	{
		// Page edit Events fired. Write some process codes here.
	}
	
	
	/**
	 * on_block_add
	 * mthod called on block add
	 * @param array $data ($key : $value)
	 *          page_id         : added target page_id
	 *          area_name       : added target area name
	 *          block_id        : created block id
	 *          collection_name : added collection name
	 *          version_number  : page version number
	 */
	public function on_block_add($data = array())
	{
		// Block add Events fired. Write some process codes here.
	}
	
	
	/**
	 * on_block_edit
	 * mthod called on block edit
	 * @param array $data ($key : $value)
	 *          page_id         : edit target page_id
	 *          block_id        : edited block id
	 *          collection_name : edited collection name
	 */
	public function on_block_edit($data = array())
	{
		// Block edit Events fired. Write some process codes here.
	}
	
	
	/**
	 * on_block_permission
	 * mthod called on block permission
	 * @param array $data ($key : $value)
	 *          page_id         : target page_id
	 *          block_id        : target block id
	 */
	public function on_block_permission($data = array())
	{
		// Block permission Events fired. Write some process codes here.
	}
	
	
	/**
	 * on_edit_out
	 * mthod called on edit_out
	 * @param array $data ($key : $value)
	 *          page_id    : target page_id
	 *          mode       : publish mode (publish or scrap or destroy)
	 */
	public function on_edit_out($data = array())
	{
		// Edit out Events fired. Write some process codes here.
	}
}
