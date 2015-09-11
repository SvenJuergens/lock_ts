<?php
namespace SvenJuergens\LockTs\Hooks;

/**
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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\MathUtility;


/**
 * Administration controller
 *
 * @package TYPO3
 * @subpackage tx_lockts
 */
class Tceforms {

	/**
	 * Path to the locallang file
	 *
	 * @var string
	 */
	const LLPATH = 'LLL:EXT:lock_ts/Resources/Private/Language/locallang_db.xlf:';


	public function getSingleField_postProcess($table, $field, $row, &$out, $PA, $pObj) {

		if( $table == 'sys_template' && $field == 'tx_lockts_lock' && $row['tx_lockts_lock'] == 1){
			$out = $this->replaceButtonsJS();
		}
	}

	public function checkLock($parameters, $pObj) {
		if ($parameters['e']['config'] || $parameters['e']['constants']) {
			$record = BackendUtility::getRecord('sys_template', (int)$parameters['tplRow']['uid'], 'tx_lockts_lock');
			if($record['tx_lockts_lock'] == 1) {
				$pObj->pObj->doc->postCode .= $this->replaceButtonsJS();
			}
		 }else {
			// we are in the Tempplate overview
			// check if there was send the command to lock/ unlock the template
			$this->updateLock = GeneralUtility::_GET('lock_ts');

			// update template record
			if(isset($this->updateLock) && MathUtility::canBeInterpretedAsInteger( $this->updateLock )) {
				$GLOBALS['TYPO3_DB']->exec_UpdateQuery(
					'sys_template',
					'uid = ' . (int)$parameters['tplRow']['uid'],
					array(
						'tx_lockts_lock' => ( $this->updateLock == 1 ? 1 : 0 )
					)
				);
			}

			// get templaterecord and check if we must set the "LOCK TS" field "checked"
			$record = BackendUtility::getRecord('sys_template', (int)$parameters['tplRow']['uid'], 'tx_lockts_lock');
			$additionalInput = '
				<div class="lock-ts">
						<input type="checkbox" style="display:inline-block;margin:0 2px 0 0;vertical-align:middle"; class="checkbox" id="lock_ts" onclick="window.location.href=window.location.href+\'&lock_ts=\'+(this.checked?1:2)" value="1" '
						 . ($record['tx_lockts_lock'] == 1 ? ' checked="checked"' : '') . '/>
						<label for="lock_ts"> ' . $GLOBALS['LANG']->sL( self::LLPATH . 'sys_template.locktstemplate') . ' </label><br />
				</div>
			';
			$parameters['theOutput'] .= $additionalInput;
		}
	}
	// Javasript for replacing the buttons
	public function replaceButtonsJS( ) {
			$replaceText = '<strong>';
			$replaceText .= htmlspecialchars( $GLOBALS['LANG']->sL( self::LLPATH . 'sys_template.replacedSubmitText'));
			$replaceText .= '</strong>';

			$out .= '
			<script type="text/javascript">
				(function(){
					var buttonGroups = document.querySelectorAll(".buttongroup");
					buttonGroups[1].innerHTML = "' . $replaceText . '";
					buttonGroups[2].innerHTML="";
				})()
			</script>
			';
		return $out;
	}
}
