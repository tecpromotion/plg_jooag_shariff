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
		$minPHP = '5.5.0';
		$minJoomla = '3.5.0';
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
}
