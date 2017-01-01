<?php

namespace wcf\system\style;

use wcf\data\option\Option;
use wcf\system\exception\SystemException;
use wcf\util\StringUtil;

/**
 * Provides access to the SCSS PHP compiler.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2016 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\Style
 */
class ExtendedStyleCompiler extends StyleCompiler {
	/**
	 * @inheritDoc
	 */
	protected function compileStylesheet($filename, array $files, array $variables, $individualScss, callable $callback) {
		foreach ($variables as &$value) {
			if (StringUtil::startsWith($value, '../')) {
				$value = '~"'.$value.'"';
			}
		}
		unset($value);

		$variables['wcfFontFamily'] = $variables['wcfFontFamilyFallback'];
		if (!empty($variables['wcfFontFamilyGoogle'])) {
			$variables['wcfFontFamily'] = '"' . $variables['wcfFontFamilyGoogle'] . '", ' . $variables['wcfFontFamily'];
		}

		// add options as SCSS variables
		if (PACKAGE_ID) {
			foreach (Option::getOptions() as $constantName => $option) {
				if (in_array($option->optionType, static::$supportedOptionType)) {
					$variables['wcf_option_'.mb_strtolower($constantName)] = is_int($option->optionValue) ? $option->optionValue : '"'.$option->optionValue.'"';
				}
			}
		}
		else {
			// workaround during setup
			$variables['wcf_option_attachment_thumbnail_height'] = '~"210"';
			$variables['wcf_option_attachment_thumbnail_width'] = '~"280"';
			$variables['wcf_option_signature_max_image_height'] = '~"150"';
		}

		// build SCSS bootstrap
		$scss = $this->bootstrap($variables);
		foreach ($files as $file) {
			$scss .= $this->prepareFile($file);
		}

		// append individual CSS/SCSS
		if ($individualScss) {
			$scss .= $individualScss;
		}

		try {
			$this->compiler->setFormatter('Leafo\ScssPhp\Formatter\Crunched');
			$this->compiler->compile($scss);
		}
		catch (\Exception $e) {
			throw new SystemException("Could not compile SCSS: ".$e->getMessage(), 0, '', $e);
		}
	}
}
