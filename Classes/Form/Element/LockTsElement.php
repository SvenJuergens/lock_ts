<?php
namespace SvenJuergens\Form\Element;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * lockts FormEngine widget
 */
class LockTsElement extends AbstractFormElement
{

    /**
     * Path to the locallang file
     *
     * @var string
     */
    const LLPATH = 'LLL:EXT:lock_ts/Resources/Private/Language/locallang_db.xlf:';

    public $resultArray;

    /**
     * Render t3editor element
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $this->resultArray = $this->initializeResultArray();
        if ((int)$this->data['databaseRow']['tx_lockts_lock'] === 1) {
            $replaceText = '<strong class="btn">';
            $replaceText .= htmlspecialchars($GLOBALS['LANG']->sL(self::LLPATH . 'sys_template.replacedSubmitText'));
            $replaceText .= '</strong>';
            $replaceText = GeneralUtility::quoteJSvalue($replaceText);

            $this->resultArray['additionalJavaScriptPost'][] = '
		    	TYPO3.jQuery(document).ready(function(){
		    		TYPO3.jQuery(".t3js-splitbutton").text(" ").next(".btn-group").text(" ").after(' . $replaceText . ');
		    	});
	    	';
        }

        return $this->resultArray;
    }
}
