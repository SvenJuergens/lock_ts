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
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Administration controller
 *
 */
class TceForms
{
    public function checkLock($parameters, $pObj)
    {
        if ($parameters['e']['config'] || $parameters['e']['constants']) {
            $record = $parameters['tplRow']['tx_lockts_lock'];
            if ((int)$record['tx_lockts_lock'] === 1) {
                $parameters['theOutput'] .= $this->replaceButtonsJS();
            }
        } else {
            // we are in the Template overview
            // check if there was send the command to lock/ unlock the template
            $updateLock = GeneralUtility::_GET('lock_ts');
            if ($updateLock !== null) {
                GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable('sys_template')
                    ->update(
                        'sys_template',
                        ['tx_lockts_lock' => (int)$updateLock === 1 ? 1 : 0],
                        ['uid' => (int)$parameters['tplRow']['uid']],
                        [Connection::PARAM_INT]
                    );
            }

            // get templateRecord and check if we must set the "LOCK TS" field "checked"
            $record = $parameters['tplRow']['tx_lockts_lock'];
            $additionalInput = '
				<div class="lock-ts">
						<input type="checkbox" style="display:inline-block;margin:0 2px 0 0;vertical-align:middle" class="checkbox" id="lock_ts" onclick="window.location.href=window.location.href+\'&lock_ts=\'+(this.checked?1:2)" value="1" '
                         . ($record['tx_lockts_lock'] === 1 ? ' checked="checked"' : '') . '/>
						<label for="lock_ts"> ' . $this->getLanguageService()->sL(
						    'LLL:EXT:lock_ts/Resources/Private/Language/locallang_db.xlf:sys_template.locktstemplate'
                ) . ' </label><br />
				</div>
			';
            $parameters['theOutput'] .= $additionalInput;
        }
    }
    public function replaceButtonsJS() : string
    {
        $replaceText = '<strong class="btn">';
        $replaceText .= htmlspecialchars(
            $this->getLanguageService()->sL('LLL:EXT:lock_ts/Resources/Private/Language/locallang_db.xlf:sys_template.replacedSubmitText')
        );
        $replaceText .= '</strong>';

        $replaceText = GeneralUtility::quoteJSvalue($replaceText);
        return '
			<script type="text/javascript" charset="utf-8">
				(function(){
					TYPO3.jQuery(".t3editor").attr("data-ajaxsavetype","");
                    TYPO3.jQuery(".t3js-splitbutton").hide().after("' . $replaceText . '");
				})()
			</script>
		';
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
