<?php

define('FCPATH', dirname(__FILE__) . '/');

class TemplateConverter
{
	protected $filepath;
	protected $rootpath;
	protected $dom;
	protected $movefiles = array();
	protected $errors    = array();
	
	public function __construct()
	{
	}
	
	public function load($filepath)
	{
		if ( ! file_exists($filepath) )
		{
			throw new LogicException('Target file is not exists.');
		}
		
		libxml_use_internal_errors(TRUE);
		
		$this->dom = new DOMDocument();
		$this->dom->encoding = 'UTF-8';
		$this->dom->formatOutput = TRUE;
		
		$html = file_get_contents($filepath);
		if ( ! mb_check_encoding($html, 'UTF-8') )
		{
			$html = mb_convert_encoding($html, 'UTF-8');
		}
		
		if ( ! $this->dom->loadHTML($html) )
		{
			foreach ( libxml_get_errors() as $error )
			{
				$this->_setError($error);
			}
			$ret = FALSE;
		}
		else
		{
			$this->rootpath = rtrim(dirname(realpath($filepath)), '/') . '/';
			$ret = TRUE;
		}
		libxml_clear_errors();
		return $ret;
	}
	
	public function getError()
	{
		return implode("\n", $this->errors);
	}
	
	public function detectInfo()
	{
		return $this->_parse();
	}
	
	public function convert()
	{
		$info = $this->_parse();
		// <img>などのパスを解決
		$this->_resolvePathLinks();
		
		$info->name        = htmlspecialchars($info->name, ENT_QUOTES, 'UTF-8');
		$info->description = htmlspecialchars($info->description, ENT_QUOTES, 'UTF-8');
		$info->handle      = htmlspecialchars($info->handle, ENT_QUOTES, 'UTF-8');
		
		$template = $this->dom->saveHTML();
		
		// metaタグを置換する
		$template = preg_replace('/<meta[^>]+?>\n/s', '', $template);
		
		// ヘッダー、フッター、テンプレートパスを置換
		$grep = array('__SZHEADER__', '</body>', '__SZTMPL__');
		$sed  = array(
			"\n" . '<?php echo $this->load->view(\'header_required\');?>' . "\n",
			'<?php echo $this->load->view(\'footer_required\');?>' . "\n</body>",
			'<?php echo $template_path;?>'
		);
		$template = str_replace($grep, $sed, $template);
		
		// エリア
		$template = preg_replace_callback('/{{__SZAREA__(.+)}}/', array($this, '_setArea'), $template);
		// リンク
		$template = preg_replace_callback('/__SZLINK__([^"]+)/', array($this, '_setLink'), $template);
		
		// テンプレートセット作成
		return $this->_createTemplateSet($template, $info);
	}
	
	protected function _setArea($matches)
	{
		return "\n" . '<?php echo $this->load->area(\'' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8') . '\');?>' . "\n";
	}
	
	protected function _setLink($matches)
	{
		return '<?php echo page_link(\'' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8') . '\');?>';
	}
	
	protected function _setError($error)
	{
		$errorString = $this->dom[$error->line - 1] . "\n";
		switch ( $error->level )
		{
			case LIBXML_ERR_WARNING:
				$errorString .= 'Warning ' . $error->code . ': ';
				break;
			case LIBXML_ERR_ERROR:
				$errorString .= 'Error ' . $error->code . ': ';
				break;
			case LIBXML_ERR_FATAL:
				$errorString .= 'Fatal Error ' . $error->code . ': ';
				break;
		}
		$errorString .= trim($error->message) . 'Line: ' . $error->line . ', Column: ' . $error->column;
		return $errorString;
	}

	protected function _parse()
	{
		$head = $this->dom->getElementsByTagName('head')->item(0);
		$body = $this->dom->getElementsByTagName('body')->item(0);
		$data = new stdClass;
		
		list($data->name, $data->description, $data->handle) = $this->_parseHeader($head);
		$data->areas = $this->_parseElements($body);
		return $data;
	}
	
	protected function _parseHeader($head)
	{
		$children    = $head->childNodes;
		$name        = 'no name';
		$description = '';
		$handle      = '';
		$idx         = -1;
		
		while ( $node = $children->item(++$idx) )
		{
			switch ( $node->tagName )
			{
				case 'meta':
					$metaName = $node->getAttribute('name');
					if ( $metaName === 'sz_template_name' )
					{
						$name = $node->getAttribute('content');
					}
					else if ( $metaName === 'sz_template_description' )
					{
						$description = $node->getAttribute('content');
					}
					else if ( $metaName === 'sz_template_handle' )
					{
						$handle = $node->getAttribute('content');
					}
					
					if ( ! $node->getAttribute('http-equiv') )
					{
						$head->removeChild($node);
						--$idx;
					}
					break;
				case 'link':
				case 'script':
				case 'base':
					break;
				default:
					$head->removeChild($node);
					--$idx;
					break;
			}
		}
		
		$marker = $this->dom->createTextNode('__SZHEADER__');
		$head->insertBefore($marker, $head->firstChild);
		
		return array($name, $description, $handle);
	}
	
	protected function _parseElements($base)
	{
		$children = $base->getElementsByTagName('*');
		$areas    = array();
		$idx      = -1;
		
		while ( $node = $children->item(++$idx) )
		{
			if ( $node->hasAttribute('data-areaname') )
			{
				$areaName = $node->getAttribute('data-areaname');
				$node->removeAttribute('data-areaname');
				$areas[] = $areaName;
				while ( $node->firstChild )
				{
					$node->removeChild($node->firstChild);
				}
				$text = $this->dom->createTextNode('{{__SZAREA__' . $areaName . '}}');
				$node->appendChild($text);
				$children = $base->getElementsByTagName('*');
				$idx      = -1;
			}
		}
		
		return $areas;
	}
	
	protected function _resolvePathLinks()
	{
		$httpRegex = '/^https?:/';
		
		foreach ( $this->dom->getElementsByTagName('link') as $link )
		{
			$href = $link->getAttribute('href');
			if ( $href && ! preg_match($httpRegex, $href) )
			{
				if ( file_exists($this->rootpath . $href) )
				{
					$this->movefiles[] = $href;
				}
				$link->setAttribute('href', '__SZTMPL__' . $href);
			}
		}

		foreach ( $this->dom->getElementsByTagName('img') as $img )
		{
			$src = $img->getAttribute('src');
			if ( $src && ! preg_match($httpRegex, $src) )
			{
				if ( file_exists($this->rootpath . $src) )
				{
					$this->movefiles[] = $src;
				}
				$img->setAttribute('src', '__SZTMPL__' . $src);
			}
		}
		
		foreach ( $this->dom->getElementsByTagName('script') as $script )
		{
			$src = $script->getAttribute('src');
			if ( $src && ! preg_match($httpRegex, $src) )
			{
				if ( file_exists($this->rootpath . $src) )
				{
					$this->movefiles[] = $src;
				}
				$script->setAttribute('src', '__SZTMPL__' . $src);
			}
		}
		
		foreach ( $this->dom->getElementsByTagName('a') as $a )
		{
			$href = $a->getAttribute('href');
			if ( $href && ! preg_match($httpRegex, $href) )
			{
				$a->setAttribute('href', '__SZLINK__' . $href);
			}
		}
		
		foreach ( $this->dom->getElementsByTagName('form') as $form )
		{
			$action = $form->getAttribute('action');
			if ( $action && ! preg_match($httpRegex, $action) )
			{
				$form->setAttribute('action', '__SZLINK__' . $action);
			}
		}
		
	}
	
	protected function _createTemplateSet($template, $info)
	{
		if ( empty($info->handle) )
		{
			return FALSE;
		}
		$dir = FCPATH . 'templates/' . $info->handle;
		// 先にディレクトリ削除
		if ( is_dir($dir) )
		{
			@rmdir($dir);
		}
		
		// 再度作成
		@mkdir($dir, 0777);
		// view.php作成
		file_put_contents($dir . '/view.php', $template);
		// attribute.php作成
		$attribute = <<<END
<?php if ( ! defined('BASEPATH') ) exit('access denied.');

\$attribute = array(
	'name' => '{$info->name}',
	'description' => '{$info->description}'
);
END;
		
		file_put_contents($dir . '/attribute.php', $attribute);
		
		// リンクファイルの移動
		foreach ( $this->movefiles as $file )
		{
			if ( strpos($file, '/') !== FALSE )
			{
				@mkdir($dir . '/' . dirname($file), 0777, TRUE);
			}
			copy($this->rootpath . $file, $dir . '/' . $file);
		}
		return TRUE;
	}
}

$t = new TemplateConverter();
if ( $t->load('./statics/index.html') )
{
	var_dump($t->convert());
}
else
{
	echo $t->getError();
}
