<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

use Joomla\CMS\Factory;

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgSystemPhocaSite extends JPlugin
{

	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	function onBeforeRender() {

		$app 	    = Factory::getApplication();
        $document	= Factory::getDocument();
		$input 	= $app->input;
		if ($app->getName() != 'site') {
			return true;
		}

		/*$format = JRequest::getWord('format');
		if ($format=='feed' || $format=='json') {
			return true;
		}*/
		// Check if the highlighter should be activated in this environment.
		if (Factory::getDocument()->getType() !== 'html' || $input->get('tmpl', '', 'cmd') === 'component')
		{
			return true;
		}


		$prm		= array();
		$prm['head_custom_code'] 	= $this->params->get('head_custom_code', '');

		$document->addCustomTag($prm['head_custom_code']);

	}
	function onAfterRender() {

		$app 	= Factory::getApplication();
		if ($app->getName() != 'site') {
			return true;
		}

		$format = $app->input->get('format', '', 'string');
		if ($format=='feed') {
			return true;
		}

		$prm	=	array();
		$head 	= $html = $body = '';

		$prm['head_ga_uaid'] 			= $this->params->get('head_ga_uaid', '');
		$prm['html_xmlns_tags'] 		= $this->params->get('html_xmlns_tags', '');
		$prm['body_custom_code'] 		= $this->params->get('body_custom_code', '');

		if ($prm['html_xmlns_tags'] != '') {
			$html .= str_replace(',', ' ', strip_tags((string)$prm['html_xmlns_tags']));
			//$html[] = explode(',', strip_tags((string)$prm['html_xmlns_tags']));
		}

		if ($prm['body_custom_code'] != '') {
			$body .= $prm['body_custom_code'];
		}

		if($prm['head_ga_uaid'] != '' ) {
		/*	$head .=
'<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push([\'_setAccount\', \''.strip_tags($prm['head_ga_uaid']).'\']);
  _gaq.push([\'_trackPageview\']);

  (function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>';*/

$head .= '
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id='.strip_tags($prm['head_ga_uaid']).'"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \''.strip_tags($prm['head_ga_uaid']).'\', { \'anonymize_ip\': true, \'allow_display_features\': false });
  
</script>';
		}

		$set = false;
		$buffer = $app->getBody();
		if ($html != ''){
			$buffer	= str_replace("<html ", "<html ". $html. " ", $buffer);
			$set = true;
		}
		if ($head != ''){
			$buffer = str_replace ("</head>", $head. "</head>", $buffer);
			$set = true;
		}
		if ($body != ''){
			$buffer = str_replace ("</body>", $body. "</body>", $buffer);
			$set = true;
		}
		if ($set) {
			$app->setBody($buffer);
		}
		return true;
	}
}
?>
