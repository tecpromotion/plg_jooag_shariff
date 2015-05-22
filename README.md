<h2>Shariff integration for Joomla 3.3+</h2>

<h3>Attention:</h3>
<h4>Settings need to be revisited</h4>
After the Update from a previos Version before V3.0.0-RC5, please open the Plugin in "Joomla Administrator -> Extensions -> Plugin -> JooAg Shariff" and set the settings again. This is needed because some of the xml definitions are now more consistent to the shariff definitions and need to be changed.
<hr/>
<h6>Uninstall the Plugin before 3.0.13RC:</h6>
It's important to delete the old Plugin, because of the new Plugin is a System Plugin, the old one was a Content Plugin.
<h6>Share Counter:</h6>
It's really important for the counter to use the url only with www or non-www.
<h6>To redirect www to non-www do the following steps:</h6>
<ol>
<li>Rename the htaccess.txt in your joomla root folder to .htaccess</li>
<li>Add following lines in the end of the .htaccess file</li>
<pre>
# www to non-www
RewriteBase /
RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ http://%1/$1 [R=301,L]
</pre>
or
<pre>
# non-www to www
RewriteCond %{HTTP_HOST} !^www\.
RewriteRule ^(.*)$ http://www.%{HTTP_HOST}/$1 [R=301,L]
</pre>
<li>At least you need to open this plugin and save it again!</li>
</code>
</ol>
</p>
<hr/>
<b>Description:</b>
A Joomla 3 Plugin for Social Media Icons as shariff integration

<b>Required:</b>
PHP 5.4+ and Joomla 3.3+

<b>Comming soon & Problems:</b>
<ul>
<li>Better JS handling</li>
</ul>

Developed by http://joomla-agentur.de

Thanks to Heise.de for this development https://github.com/heiseonline/shariff

Dedicated for Joomla! Deutschland Facebook Group https://www.facebook.com/groups/joomla.deutschland/

and for Joomla User Group Hamburg http://jug-hamburg.de/ (the main reason for this plugin :-)

<h3>Usage as Shortcode</h3>
You can put into your content or custom_html module the following shortcode: <code>{shariff}</code> . If you use a custom_html Module, please set the Option "Prepare Content" to Yes.
