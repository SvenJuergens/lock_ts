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

$GLOBALS['LANG']->includeLLFile('EXT:lock_ts/locallang_db.xml');

/**
 * Administration controller
 *
 * @package TYPO3
 * @subpackage tx_lockts
 */
class Tceforms {

	public function getSingleField_postProcess($table, $field, $row, &$out, $PA, $pObj) {
	
		if($table['sys_template'] && $field =='tx_lockts_lock' && $row['tx_lockts_lock'] == 1){
			$out = $this->replaceButtonsJS(1);
		}
	
	}	

   public function checklock($parameters, $pObj) {

		if ($parameters['e']['config'] || $parameters['e']['constants']) {
		
			$rec = BackendUtility::getRecord('sys_template', $parameters['tplRow']['uid'], 'tx_lockts_lock');
			if($rec['tx_lockts_lock'] == 1) {
				 $pObj->pObj->doc->JScodeArray[] = $this->replaceButtonsJS();
			}
			
		 }else {
		
			// we are in the Tempplate overview
			// check if there was send the command to lock/ unlock the template
			$this->updateLock = GeneralUtility::_GET('lock_ts');

			// update template record
			if(isset($this->updateLock) && MathUtility::canBeInterpretedAsInteger( $this->updateLock )) {
				$GLOBALS['TYPO3_DB']->exec_UpdateQuery(
					'sys_template',
					'uid = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($parameters['tplRow']['uid'], 'sys_template'),
					array('tx_lockts_lock' => ($this->updateLock == 1 ? 1 : 0))
				);
			}
			
			// get templaterecord and check if we must set the "LOCK TS" field "checked"
			$rec = BackendUtility::getRecord('sys_template', $parameters['tplRow']['uid'], 'tx_lockts_lock');
			$additionalInput = '
				<div id="lock-ts">
						<input type="checkbox" class="checkbox" id="lock_ts" onclick="window.location.href=window.location.href+\'&lock_ts=\'+(this.checked?1:2)" value="1" '
						 . ($rec['tx_lockts_lock'] == 1 ? ' checked="checked"' : '') . '/>
					<label for="lock_ts"> ' . $GLOBALS['LANG']->getLL('sys_template.locktstemplate', 1) . ' </label><br />
				</div>	
			';

			$parameters['theOutput'] .= $additionalInput;
	   
	   }
	}
	// Javasript for replacing the buttons
	public function replaceButtonsJS($wrap=0) {
			$out = '';
			$replaceText = '';
			$replaceText = '<div style=\"font-weight:bold;height:20px;line-height:20px;\">'; 
			$replaceText .= $GLOBALS['LANG']->getLL('sys_template.replacedSubmitText');
			$replaceText .= '</div>';
			
			$out .= '
				document.observe(\'dom:loaded\', function () {
				$$(".buttongroup").each( function ( element, Index ) {
					if(Index == 1){
						element.innerHTML = "' . $replaceText . '";
					}
					if(Index == 2){
						element.innerHTML = "";
					}
				});
			});
			
			';
		
		if($wrap == 1){
				$out = '<script type="text/javascript">
					/*<![CDATA[*/
					' . $out . '
					/*]]>*/
					</script>
				';
		}
		
		return $out;

	}
}