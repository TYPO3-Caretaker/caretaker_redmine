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
 * $Id: class.tx_caretaker_Cli.php 28420 2010-01-05 16:51:51Z martoro $
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

	/**
	 * 
	 * @return tx_caretaker_TestResult
	 */
	public function runTest(){

		$config = $this->getConfiguration();
		var_dump($config);

		/*
		$rsmlUrl   = $config['rsmlUrl'];
		if (strpos( $rsmlUrl, '://' ) === false  && $this->instance) {
			$rsmlUrl = $this->instance->getUrl() . '/' . $rsmlUrl;
		}

		$expectedRsmlId      = $config['expectedRsmlId'];
		$expectedRsmlVersion = $config['expectedRsmlVersion'];
		$expectedStatus      = $config['expectedStatus'];
		$expectedValue       = $config['expectedValue'];
		
		if ( ! ( $rsmlUrl && $expectedRsmlId && $expectedRsmlVersion ) ) {
			return tx_caretaker_TestResult::create(tx_caretaker_Constants::state_error, 0, 'You have to define URL ID and Version conditions for this test'.chr(10).var_export($config, true) );
		} else {
			$httpResult = $this->executeHttpRequest($rsmlUrl);
			if ( $httpResult['response'] && $httpResult['info']['http_code'] == 200 ){

				try {
					$xml = new SimpleXMLElement( $httpResult['response'] );
				} catch (Exception $e ){
					return tx_caretaker_TestResult::create( tx_caretaker_Constants::state_error, 0, 'Returened result xml could not be parsed. ' . chr(10) . htmlspecialchars($httpResult['response']) );
				}
				
				$returnedRsmlId      = ( isset($xml->rsmlId) ) ? (string)$xml->rsmlId : false;
				$returnedRsmlVersion = ( isset($xml->rsmlVersion) ) ?  (string)$xml->rsmlVersion : false;

				$returnedStatus  = ( isset($xml->status)  ) ? (string)$xml->status  : false;
				$returnedValue   = ( isset($xml->value)   ) ? (string)$xml->value   : 0;
				$returnedMessage = ( isset($xml->message) ) ? (string)$xml->message : false;
				$returnedDescription = ( isset($xml->description) ) ? (string)$xml->description : false;

				$message = '';
				$submessages = array();
				
					// script id is wrong
				if ( !$returnedRsmlId || $returnedRsmlId != $expectedRsmlId ) {
					$this->decreaseState( tx_caretaker_Constants::state_error );
					$submessages[] = new tx_caretaker_ResultMessage(
						'Script ID was wrong. Expected ###VALUE_EXPECTED### returned ###VALUE_RETURNED###',
						array( 'expected'=>$expectedRsmlId, 'returned' =>$returnedRsmlId )
					);
				}

					// script version is wrong
				if ( !$returnedRsmlVersion || t3lib_div::int_from_ver($returnedRsmlVersion) != t3lib_div::int_from_ver( $expectedRsmlVersion ) ) {
					$this->decreaseState( tx_caretaker_Constants::state_error );
					$submessages[] = new tx_caretaker_ResultMessage(
						'Script Version was wrong. Expected ###VALUE_EXPECTED### returned ###VALUE_RETURNED###',
						array( 'expected'=>$expectedRsmlVersion, 'returned' =>$returnedRsmlVersion )
					);
				}

					// if the checks until now were ok
				if ( $this->status == 0  ){
					
						// show description
					if ($returnedDescription) {
						$message = $returnedDescription;
					}

					if ( $expectedStatus || (int)$returnedStatus !== (int)$expectedStatus ){
						if ($returnedStatus){
							$this->decreaseState( $returnedStatus );
						} else {
							$this->decreaseState( tx_caretaker_Constants::state_error );
						}
						$submessages[] = new tx_caretaker_ResultMessage(
							'Status was wrong. Expected ###VALUE_EXPECTED### returned ###VALUE_RETURNED###',
							array( 'expected'=>$expectedStatus, 'returned' =>$returnedStatus )
						);
					}

						// value
					if ( $expectedValue && !$this->isValueInRange( $returnedValue, $expectedValue)  ){
						$this->decreaseState( tx_caretaker_Constants::state_error );
						$submessages[] = new tx_caretaker_ResultMessage(
							'Value was wrong. Expected ###VALUE_EXPECTED### returned ###VALUE_RETURNED###',
							array( 'expected'=>$expectedValue, 'returned' =>$returnedValue )
						);
					}

						// submessages
					if ($returnedMessage){
						$submessages[] = new tx_caretaker_ResultMessage( 'Messages:' . chr(10) . $returnedMessage );
					}
				}

				return tx_caretaker_TestResult::create( $this->state, $returnedValue, $message, $submessages );

			} else {

				return tx_caretaker_TestResult::create( tx_caretaker_Constants::state_error, 0, 'Unexpected Script Response' . chr(10) . $rsmlUrl . chr(10).var_export($httpResult, true) );
				
			}
		}
		*/

	}

	/**
	 * Get the configuration for the Test
	 * 
	 * @return array 
	 */
	public function getConfiguration() {
				
		$config = array(
			'redmine_url'	=> $this->getConfigValue('redmine_url'),
			'protocol'		=> $this->getConfigValue('protocol'),
			'api_key'		=> $this->getConfigValue('api_key'),
			'method'		=> $this->getConfigValue('method')
		);

		return $config;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caretaker_redmine/services/class.tx_caretakerredmineTestService.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caretaker_redmine/services/class.tx_caretakerredmineTestService.php']);
}
?>