<?php

/***************************************************************
* Copyright notice
*
* (c) 2009 by n@work GmbH and networkteam GmbH
*
* All rights reserved
*
* This script is part of the Caretaker project. The Caretaker project
* is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * This is a file of the caretaker project.
 * http://forge.typo3.org/projects/show/extension-caretaker
 *
 * Project sponsored by:
 * n@work GmbH - http://www.work.de
 * networkteam GmbH - http://www.networkteam.com/
 *
 */

/**
 * The caretaker Redmine Test.
 *
 * @author Thomas Hempel <thomas@work.de>
 *
 * @package TYPO3
 * @subpackage caretaker
 */
class tx_caretakerredmineTestService extends tx_caretaker_TestServiceBase {
	
	private $state = tx_caretaker_Constants::state_ok;

	private $restClient = null;
	/**
	 * 
	 * @return tx_caretaker_TestResult
	 */
	public function runTest() {

		$config = $this->getConfiguration();
		
		$host = $config['protocol'].'://'.$config['api_key'].':foobar@'.$config['redmine_url'].'/';
		t3lib_div::debug($host);
		/**
		 * @var tx_caretakerredmine_rest
		 */
		$this->restClient = t3lib_div::makeInstance('tx_caretakerredmine_rest');
		$this->restClient->setHost($host);
		$result = null;

		switch ($config['method']) {
			case 'allOpenTickets':
				$result = $this->checkAllOpenTickets();
				break;
			case 'openTicketsInProject':
				$result = $this->checkOpenTicketsInProject();
				break;
			default:
				$result = tx_caretaker_TestResult::create(tx_caretaker_Constants::state_error, 0, 'No request method defined!');
				break;
		}

		return $result;
	}

	protected function checkAllOpenTickets() {
		$issues = $this->restClient->getIssues('open', 1);
		$issueCount = intval($issues->total_count);

		$warningThreshold = $this->getConfigValue('optWarningThreshold');
		$errorThreshold = $this->getConfigValue('optErrorThreshold');

		$state = tx_caretaker_Constants::state_ok;
		$message = 'A total of '.$issueCount.' open issues found.';

		if (!empty($warningThreshold) && $issueCount > $warningThreshold) {
			$state = tx_caretaker_Constants::state_warning;
			$message = 'WARNING! '.$message.' Expected a value under '.$warningThreshold.'.';
		}

		if (!empty($errorThreshold) && $issueCount > $errorThreshold) {
			$state = tx_caretaker_Constants::state_error;
			$message = 'ERROR! '.$message.' Expected a value under '.$errorThreshold.'.';
		}

		return tx_caretaker_TestResult::create($state, $issueCount, $message);

	}

	protected function checkOpenTicketsInProject() {
		$state = tx_caretaker_Constants::state_warning;
		$message = 'Something went wrong. ^^';
		$issueCount = 0;

		$projectId = $this->config['optProjectId'];

		if (empty($projectId)) {
			$state = tx_caretaker_Constants::state_error;
			$message = 'No project id given in test setup.';
		} else {
			$state = tx_caretaker_Constants::state_ok;
			$issues = $this->restClient->getIssuesForProject($projectId, 'open', 1);
			$issueCount = intval($issues->total_count);
			$message = $issueCount.' open issues found for project "'.$projectId.'".';

			if (!empty($warningThreshold) && $issueCount > $warningThreshold) {
				$state = tx_caretaker_Constants::state_warning;
				$message = 'WARNING! '.$message.' Expected a value under '.$warningThreshold.'.';
			}

			if (!empty($errorThreshold) && $issueCount > $errorThreshold) {
				$state = tx_caretaker_Constants::state_error;
				$message = 'ERROR! '.$message.' Expected a value under '.$errorThreshold.'.';
			}
		}

		return tx_caretaker_TestResult::create($state, $issueCount, $message);
	}

	/**
	 * Get the configuration for the Test
	 * 
	 * @return array 
	 */
	protected function getConfiguration() {
		$config = array (
			'redmine_url'	=> $this->getConfigValue('redmine_url'),
			'protocol'		=> $this->getConfigValue('protocol'),
			'api_key'		=> $this->getConfigValue('api_key'),
			'method'		=> $this->getConfigValue('method'),
			'parameters'	=> $this->parseAdditionalParameters($this->getConfigValue('optAdditionalParams'))
		);

		return $config;
	}

	protected function parseAdditionalParameters($stringInput) {
		$result = array();

		$pairs = t3lib_div::trimExplode(',', $stringInput);
		if (is_array($pairs) && count($pairs) > 0) {
			foreach ($pairs as $pair) {
				$keyValue = t3lib_div::trimExplode('=', $pair);
				$result[$keyValue[0]] = $keyValue[1];
			}
		}

		return $result;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caretaker_redmine/services/class.tx_caretakerredmineTestService.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caretaker_redmine/services/class.tx_caretakerredmineTestService.php']);
}
?>