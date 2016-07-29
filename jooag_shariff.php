<?php
/**
 * @package    JooAg_Shariff
 *
 * @author     Joomla Agentur <info@joomla-agentur.de>
 * @copyright  Copyright (c) 2009 - 2015 Joomla-Agentur All rights reserved.
 * @license    GNU General Public License version 2 or later;
 * @description A small Plugin to share Social Links without compromising their privacy!
 * # JoomlaEvents
 * ## Plugin Access
 * ### Output Position
 * #### Output Generation
 * ##### Backend
 **/
defined('_JEXEC') or die;

/**
 * Class PlgContentJooag_Shariff
 *
 * @since  1.0.0
 **/
class plgSystemJooag_Shariff extends JPlugin
{	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	/**
	 * Display the buttons before the article
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   mixed    &$article  An object with a "text" property
	 * @param   mixed    &$params   Additional parameters. See {@see PlgContentContent()}.
	 * @param   integer  $page      Optional page number. Unused. Defaults to zero.
	 *
	 * @return  string
	 **/
	public function onContentBeforeDisplay($context, &$article, &$params, $page = 0)
	{
		if($this->getAccessGeneral($context, $article, 'top') == 1)
		{
			$article->introtext = str_replace('{noshariff}', '', $article->introtext, $stringCount); //Fix for Newsflash Module
			
			if($stringCount == 0)
			{
				return $this->generateHTML($config = array());
			}
			
		}
	}

	/**
	 * Display the buttons after the article
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   mixed    &$article  An object with a "text" property
	 * @param   mixed    &$params   Additional parameters. See {@see PlgContentContent()}.
	 * @param   integer  $page      Optional page number. Unused. Defaults to zero.
	 *
	 * @return  string
	 **/
	public function onContentAfterDisplay($context, &$article, &$params, $page = 0)
	{			
		if($this->getAccessGeneral($context, $article, 'bottom') == 1)
		{
			$article->introtext = str_replace('{noshariff}', '', $article->introtext, $stringCount); //Fix for Newsflash Module
			
			if($stringCount == 0)
			{
				return $this->generateHTML($config = array());
			}
		}
	}

	//Show Everywhere
	public function onBeforeRender()
	{
		$app = JFactory::getApplication();
		
		if($app->isSite())
		{
			$doc = JFactory::getDocument();
			$buffer = $doc->getBuffer('component');
			$buffering = '';
			
			if($this->getAccessGeneral('com_everywhere', '', 'top') == 1)
			{
				$buffering .= $this->generateHTML($config = array());
			}
			
			$buffering .= $buffer;
			
			if($this->getAccessGeneral('com_everywhere', '', 'bottom') == 1)
			{
				$buffering .= $this->generateHTML($config = array());
			}

			$doc->setBuffer($buffering, 'component');
		}
	}
	
	/**
	 * Place shariff in your aticles and modules via {shariff} shorttag
	 **/
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{		
		$app = JFactory::getApplication();
		
		if(preg_match_all('/{shariff\ ([^}]+)\}|\{shariff\}/', $article->text, $matches) and $app->isSite() and $this->getAccessGeneral('com_shorttag', $article, 'top') == 1)
		{
			$params = explode(' ', trim($matches[0][0],'}'));
			$config = array ();

			foreach ($params as $key => $item)
			{
				if($key != 0)
				{
					list($k, $v) = explode("=", $item);
					$config[ $k ] = $v;
				}
			}
			
			$this->params->get('com_shorttag') ? $config['shorttag'] = 1 : $config['shorttag'] = 0;
			$article->text = str_replace($matches[0][0], $this->generateHTML($config), $article->text);
		}

		//Fix for Newsflash Module
		if	($context == 'mod_articles_news.content')
		{ 
			$article->text .= '{noshariff}';
		}
	}
	
	//###############Access::Section->START
	private function getAccessGeneral($context, $article, $position)
	{
		$app = JFactory::getApplication();
		$access = 0;
		
		if(in_array($position, $this->params->get('output_position')) and $app->isSite())
		{
			//For Com_content Articles
			if($this->getAccessComContent($context ,$article) == 1 and $this->getAccessMenu($context) == 1)
			{
				$access = 1;
			}

			//For getbuffer('component') aka com_everywhere
			if($this->params->get('com_everywhere') == 1 and $context == 'com_everywhere' and $this->getAccessMenu('com_everywhere.placeholder') == 1)
			{
				$access = 1;
			}
		}
		
		if($this->params->get('com_shorttag') == 1 and $context == 'com_shorttag' and $app->isSite())
		{
			$access = 1;
		}
		
		return $access;
	}
	
	private function getAccessMenu($context)
	{
		$menuAccess = 0;
		$app = JFactory::getApplication();
		$menu = $app->getMenu()->getActive();
		is_object($menu) ? $actualMenuId = $menu->id : $actualMenuId = $app->input->getInt('Itemid', 0);
		$context = explode('.', $context);
		$menuIds = (array)$this->params->get($context[0].'_menu_select');
		$this->params->get($context[0].'_menu_assignment') == 0 ? $menuAccess = 0 : '';
		$this->params->get($context[0].'_menu_assignment') == 1 ? $menuAccess = 1 : '';
		
		if($this->params->get($context[0].'_menu_assignment') == 2)
		{
			$menuAccess = 0;
			in_array($actualMenuId, $menuIds) ? $menuAccess = 1 : '';
		}
		
		if($this->params->get($context[0].'_menu_assignment') == 3)
		{
			$menuAccess = 1;
			in_array($actualMenuId, $menuIds) ? $menuAccess = 0 : '';
		}
		
		return $menuAccess;
	}
	
	private function getAccessComContent($context, $article)
	{
		$access = 0;
		$context = explode('.', $context);
		if($this->params->get('com_content') == 1 and $context[0] == 'com_content')
		{
			$catIds = (array)$this->params->get('com_content_category_select');
			$this->params->get('com_content_category_assignment') == 0 ? $access = 0 : '';
			$this->params->get('com_content_category_assignment') == 1 ? $access = 1 : '';
			
			if($this->params->get('com_content_category_assignment') == 2)
			{
				$access = 0;
				isset($article->catid) and in_array($article->catid, $catIds) ? $access = 1 : '';
			}

			if($this->params->get('com_content_category_assignment') == 3)
			{
				$access = 1;
				isset($article->catid) and in_array($article->catid, $catIds) ? $access = 0 : '';
			}
		}

		return $access;
	}
	//###############Access::Section->END
	
	/**
	 * Shariff output generation
	 **/
	public function generateHTML($config) //for shorttag
	{	
		if(!$this->params->get('services'))
		{
			return;
		}
		
		JHtml::_('jquery.framework');
		$doc = JFactory::getDocument();
		
		if($this->params->get('shariffcss') != '-1')
		{	
			$doc->addStyleSheet(JURI::root().'media/plg_jooag_shariff/css/'.$this->params->get('shariffcss'));
		}
		
		if($this->params->get('shariffjs') != '-1')
		{
			$doc->addScript(JURI::root().'media/plg_jooag_shariff/js/'.$this->params->get('shariffjs'));
			$doc->addScriptDeclaration('jQuery(document).ready(function() {var buttonsContainer = jQuery(".shariff");new Shariff(buttonsContainer);});');
		}

		//Cache Folder
		jimport('joomla.filesystem.folder');
		if(!JFolder::exists(JPATH_SITE.'/cache/plg_jooag_shariff') and $this->params->get('data_backend_url')){
			JFolder::create(JPATH_SITE.'/cache/plg_jooag_shariff', 0755);
		}
				
		$html  = '<div class="shariff"';
		$html .= ($this->params->get('data_backend_url')) ? ' data-backend-url="/plugins/system/jooag_shariff/backend/"' : '';
		$html .= ' data-lang="'.explode("-", JFactory::getLanguage()->getTag())[0].'"';
		$html .= (array_key_exists('orientation', $config)) ? ' data-orientation="'.$config['orientation'].'"' : ' data-orientation="'.$this->params->get('data_orientation').'"';
		$html .= (array_key_exists('theme', $config)) ? ' data-theme="'.$config['theme'].'"' : ' data-theme="'.$this->params->get('data_theme').'"';		
	
		
		foreach($this->params->get('services') as $service)
		{
			if($service->services)
			{
				$services[] = $service->services;
			}
			
			if($service->services == 'Twitter')
			{
				$html .= ($this->params->get('shariff_twitter_via')) ? ' data-twitter-via="'.$service->shariff_twitter_via.'"' : '';
			}
			
			if($service->services == 'Flattr')
			{	
				$html .= ($this->params->get('shariff_flattr_category')) ? ' data-flattr-category="'.$service->shariff_flattr_category.'"' : '';
				$html .= ($this->params->get('shariff_flattr_user')) ? ' data-flattr-user="'.$service->shariff_flattr_user.'"' : '';
			}

			if($service->services == 'Mail')
			{

				$html .= ($service->data_mail_url) ? ' data-mail-url="mailto:'.$service->data_mail_url.'"' : '';
				$html .= ($service->data_mail_subject) ? ' data-mail-subject="'.$service->data-mail-subject.'"' : '';
				$html .= ($service->data_mail_body) ? ' data-mail-body="'.$service->data-mail-body.'"' : '';
			}
			
			if($service->services == 'Info' and $service->data_info_url)
			{
				jimport('joomla.database.table');
				$item =	JTable::getInstance("content");
				$item->load($service->data_info_url);
				require_once JPATH_SITE . '/components/com_content/helpers/route.php';
				$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language));
				$html .= ' data-info-url="'.$link.'"';
			}	
		}

		//Services output
		$html .= ' data-services="'.htmlspecialchars(json_encode(array_map('strtolower', $services))).'"';	

		$html .= '></div>';
				
		return $html;
		
	}
	
	/**
	 * Generator for shariff.json File
	 *
	 * @return void
	 **/
	public function onExtensionBeforeSave($context, $table, $isNew)
	{
		if($table->name == 'PLG_JOOAG_SHARIFF')
		{
			$params = json_decode($table->params);

			if($params->data_url == 0)
			{
				$json->domains = (array)JURI::getInstance()->getHost();
			}
			else
			{
				
				foreach($params->data_url_custom as $domain)
				{
					$json->domains[] = $domain->custom_domains;				
				}
				
			}

			$services = array('GooglePlus','Facebook','LinkedIn','Reddit','StumbleUpon','Flattr','Pinterest','Xing','AddThis');
			
			foreach($params->services as $service)
			{
				$json->services[] = $service->services;
			}
			
			//Delete unused services
			$json->services = array_intersect($services, $json->services);
			
			if($params->fb_app_id and $params->fb_secret)
			{
				$json->Facebook->app_id = $params->fb_app_id;
				$json->Facebook->secret = $params->fb_secret;
			}

			$json->cache->cacheDir = JPATH_SITE.'/cache/plg_jooag_shariff';
			$json->cache->ttl = $params->cache_time;
			$json->client->timeout = $params->client_timeout;
			
			if($params->cache)
			{
				$json->cache->adapter = $params->cache_handler;

				if($params->cache_handler == 'file'){
					$json->cache->adapter = 'filesystem';
				}
			}

			$json = json_encode($json, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
			JFile::write(JPATH_PLUGINS . '/system/jooag_shariff/backend/shariff.json', $json);
		}
	}
}