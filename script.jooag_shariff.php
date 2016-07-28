<?php
/**
 * @package    JooAg_Shariff
 *
 * @author     Joomla Agentur <info@joomla-agentur.de>
 * @copyright  Copyright (c) 2009 - 2015 Joomla-Agentur All rights reserved.
 * @license    GNU General Public License version 2 or later;
 * @description A small Plugin to share Social Links without compromising their privacy!
 **/
 
defined('_JEXEC') or die();

class PlgSystemJooag_shariffInstallerScript
{
	public function preflight($type, $parent)
	{
		$minPHP = '5.6.0';
		$minJoomla = '3.6.0';
		$errorCount = '0';
	
		if (!version_compare(PHP_VERSION, $minPHP, 'ge'))
		{
			$error = "<p>You need PHP $minPHP or later to install this extension!<br/>Actual PHP Version:".PHP_VERSION."</p>";
			JLog::add($error, JLog::WARNING, 'jerror');
			$errorCount++;
		}

		if (!version_compare(JVERSION, $minJoomla, 'ge'))
		{
			$error = "<p>You need Joomla! $minJoomla or later to install this extension!<br/>Actual Joomla! Version:".JVERSION."</p>";
			JLog::add($error, JLog::WARNING, 'jerror');
			$errorCount++;
		}
		
		if($errorCount != 0)
		{
			return false;
		}

		return true;
	}
	
	function postflight( $type, $parent )
	{
		echo '<div class="alert alert-danger"><h1 class="alert-heading">Attention! Please read carefully.</h1><div class="alert-message"> This is a security release, with an updated Shariff  Backend Library to prevent a CGI application vulnerability. More Informations here: https://httpoxy.org/ . This is a security release and Because of the big changes in Shariff Backend Library and the Plugin itself, you have to make the settings again.</div></div>';
	}
}
