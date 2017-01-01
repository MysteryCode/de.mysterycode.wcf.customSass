<?php

namespace wcf\acp\form;

use wcf\data\style\StyleList;
use wcf\form\AbstractForm;
use wcf\system\exception\SystemException;
use wcf\system\exception\UserInputException;
use wcf\system\style\ExtendedStyleCompiler;
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
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.style.customScss';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = array('admin.style.canManageStyle');
	
	/**
	 * custom scss
	 * @var	string
	 */
	public $customScss = '';

	/**
	 * compile-error
	 * @var null
	 */
	protected $error = null;
	
	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['individualScss'])) $this->customScss = StringUtil::trim($_POST['individualScss']);
	}

	/**
	 * @inheritDoc
	 */
	public function validate() {
		parent::validate();

		ExtendedStyleCompiler::getInstance()->setSkipStyleScss(true);
		ExtendedStyleCompiler::getInstance()->setIndividualSCSS($this->customScss);
		ExtendedStyleCompiler::getInstance()->setExcludeFiles([
			WCF_DIR . 'style/ui/customMysterycode.scss'
		]);
		
		if (!empty($this->customScss)) {
			$styleList = new StyleList();
			$styleList->readObjects();
			$styles = $styleList->getObjects();

			try {
				ExtendedStyleCompiler::getInstance()->compileACP();
			}
			catch (SystemException $e) {
				$this->error = $e;
				throw new UserInputException('individualScss', 'inValid');
			}

			foreach ($styles as $style) {
				try {
					ExtendedStyleCompiler::getInstance()->compile($style);
				}
				catch (SystemException $e) {
					$this->error = $e;
					throw new UserInputException('individualScss', 'inValid');
				}
			}
		}
	}

	/**
	 * @inheritDoc
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
	 * @inheritDoc
	 */
	public function readData() {
		parent::readData();
		
		if (empty($_POST)) {
			$file = FileUtil::getRealPath(WCF_DIR) . 'style/ui/customMysterycode.scss';

			if (file_exists($file)) {
				$this->customScss = file_get_contents($file);
			}
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'individualScss' => $this->customScss,
			'error' => $this->error
		));
	}
}
