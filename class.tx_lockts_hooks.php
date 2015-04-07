<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Sven Juergens <post@t3area.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 *
 * Thanks to Steffen Kamper for his inspiring Extensions TSBrowser and lock_element
 */

$GLOBALS['LANG']->includeLLFile('EXT:lock_ts/locallang_db.xml');

class tx_lockts_hooks {

	public function getSingleField_postProcess($table, $field, $row, &$out, $PA, $pObj) {
	
		if($table['sys_template'] && $field =='tx_lockts_lock' && $row['tx_lockts_lock'] == 1){
			$out = $this->replaceButtonsJS(1);
		}
	
	}	

   public function checklock($parameters, $pObj) {

        if ($parameters['e']['config'] || $parameters['e']['constants']) {
		
            $rec = t3lib_BEfunc::getRecord('sys_template', $parameters['tplRow']['uid'], 'tx_lockts_lock');
            if($rec['tx_lockts_lock'] == 1) {
                 $pObj->pObj->doc->JScodeArray[] = $this->replaceButtonsJS();
            }
			
		 }else {
		
            // we are in the Tempplate overview

            // check if there was send the command to lock/ unlock the template
            $this->updateLock = t3lib_div::_GET('lock_ts');

            // update template record
            if(isset($this->updateLock) && $this->canBeInterpretedAsInteger( $this->updateLock )) {
                $GLOBALS['TYPO3_DB']->exec_UpdateQuery(
                    'sys_template',
                    'uid = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($parameters['tplRow']['uid'], 'sys_template'),
                    array('tx_lockts_lock' => ($this->updateLock == 1 ? 1 : 0))
                );
            }
			
            // get templaterecord and check if we must set the "LOCK TS" field "checked"
            $rec = t3lib_BEfunc::getRecord('sys_template', $parameters['tplRow']['uid'], 'tx_lockts_lock');
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

	/** copy from MathUtility TYPO3 6.1.7
	 * Tests if the input can be interpreted as integer.
	 *
	 * Note: Integer casting from objects or arrays is considered undefined and thus will return false.
	 *
	 * @see http://php.net/manual/en/language.types.integer.php#language.types.integer.casting.from-other
	 * @param mixed $var Any input variable to test
	 * @return boolean Returns TRUE if string is an integer
	 */
	public function canBeInterpretedAsInteger($var) {
		if ($var === '' || is_object($var) || is_array($var)) {
			return FALSE;
		}
		return (string) intval($var) === (string) $var;
	}

}
if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/lock_ts/class.tx_lockts_hooks.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/lock_ts/class.tx_lockts_hooks.php']);
}

?>