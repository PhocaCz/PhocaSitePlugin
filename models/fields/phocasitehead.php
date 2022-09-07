<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('JPATH_BASE') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldPhocaSiteHead extends JFormField
{
	protected $type = 'PhocaSiteHead';

	protected function getInput() {


		$document = Factory::getDocument();

		$style = '
.ph-options-head {
	background: #dbe4f0;
	font-weight: bold;
	padding:15px 10px;
	margin:5px 0;
	display:block;
	font-size: 110%;
	color: #2e486b;
	border-radius: 3px;
}
@media (min-width: 992px) {
    .ph-options-head,
    .ph-options-head-expert {
        margin-left: -240px;
    }
}';


		$document->addCustomTag('<style type="text/css">'.$style.'</style>');


		if ($this->element['default']) {
			return '<div class="tab-header ph-options-head"">'
			.'<strong>'. Text::_($this->element['default']) . '</strong>'
			.'</div>';

		} else {
			return parent::getLabel();
		}
		//echo '<div style="clear:both;"></div>';
	}

}
?>
