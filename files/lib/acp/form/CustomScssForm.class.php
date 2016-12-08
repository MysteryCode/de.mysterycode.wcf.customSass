<?php

namespace wcf\acp\form;
use wcf\data\style\StyleList;
use wcf\form\AbstractForm;
use wcf\system\style\StyleHandler;
use wcf\system\WCF;
use wcf\util\FileUtil;
use wcf\util\StringUtil;

/**
 * Shows the custom scss form
 *
 * @author	Florian Gail
 * @copyright	2014-2016 Florian Gail <https://www.mysterycode.de/>
 * @license	Kostenlose Plugins <https://downloads.mysterycode.de/index.php/License/6-Kostenlose-Plugins/>
 * @package	de.mysterycode.wcf.customScss
 * @category	WCF
 */
class CustomScssForm extends AbstractForm {
	/**
	 * @see	\wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.style.customScss';
	
	/**
	 * @see	\wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.style.canManageStyle');
	
	/**
	 * custom scss
	 * @var	string
	 */
	public $customScss = '';
	
	/**
	 * @see	\wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['individualScss'])) $this->customScss = StringUtil::trim($_POST['individualScss']);
	}
	
	/**
	 * @see	\wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		$file = FileUtil::getRealPath(WCF_DIR) . 'style/ui/customMysterycode.scss';
		
		$string = '';
		
		if (empty($this->customScss)) {
		$string = '// DO NOT EDIT!
';
		}
		
		$string .= $this->customScss;
		
		file_put_contents($file, $string);
		
		$styleList = new StyleList();
		$styleList->readObjects();
		
		foreach ($styleList->getObjects() as $style) {
			StyleHandler::getInstance()->resetStylesheet($style);
		}
		
		$this->saved();
		
		// show success
		WCF::getTPL()->assign(array(
			'success' => true
		));
	}
	
	/**
	 * @see	\wcf\form\IForm::readData()
	 */
	public function readData() {
		parent::readData();
		
		$file = FileUtil::getRealPath(WCF_DIR) . 'style/ui/customMysterycode.scss';
		
		if (file_exists($file)) {
			$this->customScss = file_get_contents($file);
		}
	}
	
	/**
	 * @see	\wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'individualScss' => $this->customScss
		));
	}
}
