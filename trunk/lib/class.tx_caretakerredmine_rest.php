<?php

/***************************************************************
* Copyright notice
*
* (c) 2011 by n@work GmbH
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
 */

/**
 * The caretaker Redmine Restinterface.
 *
 * @author Thomas Hempel <thomas@work.de>
 *
 * @package TYPO3
 * @subpackage caretaker_redmine
 */
class tx_caretakerredmine_rest {

	/**
	 * The complete host including API key
	 *
	 * @var string
	 */
	private $host = '';

	/**
	 * host setter
	 *
	 * @param string $host: The host incl. the redmine API key
	 */
	public function setHost($host) {
		$this->host = $host;
	}

	/// --- USER --- ///

	/**
	 * Return a list of users from REST API.
	 *
	 * @param integer	$limit: The number of total users returned (DEFAULT: 25)
	 * @param string	$format: The format that should be returned (json or xml / DEFAULT: json)
	 * @return string
	 */
	public function getUsersRaw($limit = 25, $format = 'json') {
		return $this->sendRequest('users', array('limit' => $limit), $format);
	}

	/**
	 * Return an array with all users and some meta information from REST API.
	 *
	 * @param integer	$limit: The number of total users returned (DEFAULT: 25)
	 * @return array
	 */
	public function getUsers($limit = 25) {
		return json_decode($this->getUsersRaw($limit));
	}

	/**
	 * Returns a single user from REST API. The result format is defined by the parameter $format
	 *
	 * @param integer	$id:		The id of the user
	 * @param string	$format:	The format that should be returned (json or xml / DEFAULT: json)
	 * @return string
	 */
	public function getUserRaw($id, $format = 'json') {
		return $this->sendRequest('users/'.$id, array(), $format);
	}

	/**
	 * Returns a single user from REST API as array.
	 *
	 * @param integer	$id: The id of the user
	 * @return array
	 */
	public function getUser($id) {
		return json_decode($this->getUserRaw($id, 'json'));
	}

	/// --- ISSUES --- ///

	/**
	 * Returns the open issues over all projects. Output format is defined by parameter $format.
	 *
	 * @param string	$status:	The status of the tickets that should be returned (e.g. open, closed / DEFAULT: open)
	 * @param integer	$limit:		The maximum number of returned issues (DEFAULT: 25)
	 * @param string	$format:	The format that should be returned (json or xml / DEFAULT: json)
	 * @return string
	 */
	public function getIssuesRaw($status = 'open', $limit = 25, $format = 'json') {
		return $this->sendRequest(
			'issues',
			array(
				'status_id' => $status,
				'limit' => $limit
			),
			$format);
	}

	/**
	 * Returns all open issues over all projects as array
	 *
	 * @param string	$status:	The status of the tickets that should be returned (e.g. open, closed / DEFAULT: open)
	 * @param integer	$limit:		The maximum number of returned issues (DEFAULT: 25)
	 * @return array
	 */
	public function getIssues($status = 'open', $limit = 25) {
		return json_decode($this->getIssuesRaw($status, $limit, 'json'));
	}

	/**
	 * Returns the open issues for a given project. Output format is defined by parameter $format.
	 *
	 * @param string	$projectId: The Redmine id of the project can either be an integer or the id defined in Redmine itself
	 * @param string	$status:	The status of the tickets that should be returned (e.g. open, closed / DEFAULT: open)
	 * @param integer	$limit:		The maximum number of returned issues (DEFAULT: 25)
	 * @param string	$format:	The format that should be returned (json or xml / DEFAULT: json)
	 * @return string
	 */
	public function getIssuesForProjectRaw($projectId, $status = 'open', $limit = 25, $format = 'json') {
		// project_id
		return $this->sendRequest(
			'issues',
			array(
				'status_id' => $status,
				'project_id' => $projectId,
				'limit' => $limit
			),
			$format);
	}

	/**
	 * Returns the open issues for a given project as array.
	 *
	 * @param string	$projectId: The Redmine id of the project can either be an integer or the id defined in Redmine itself
	 * @param string	$status:	The status of the tickets that should be returned (e.g. open, closed / DEFAULT: open)
	 * @param integer	$limit:		The maximum number of returned issues (DEFAULT: 25)
	 * @return array
	 */
	public function getIssuesForProject($projectId, $status = 'open', $limit = 25) {
		return json_decode($this->getIssuesForProjectRaw($projectId, $status, $limit, 'json'));
	}

	/**
	 * Returns a list of all issues that are assigend to a certain user. The result format is
	 * defined by the parameter $format
	 *
	 * @param integer	$userId:	The id of the user
	 * @param string	$status:	The status of the tickets that should be returned (e.g. open, closed / DEFAULT: open)
	 * @param integer	$limit:		The maximum number of returned issues (DEFAULT: 25)
	 * @param string	$format:	The format that should be returned (json or xml / DEFAULT: json)
	 * @return string
	 */
	public function getIssuesForUserIdRaw($userId, $status = 'open', $limit = 25, $format = 'json') {
		return $this->sendRequest(
			'issues',
			array(
				'status_id' => $status,
				'assigned_to_id' => $userId,
				'limit' => $limit),
			$format);
	}

	/**
	 * Returns a list of all issues that are assigend to a certain user as array
	 *
	 * @param integer	$userId:	The id of the user
	 * @param string	$status:	The status of the tickets that should be returned (e.g. open, closed / DEFAULT: open)
	 * @param integer	$limit:		The maximum number of returned issues (DEFAULT: 25)
	 * @return array
	 */
	public function getIssuesForUserId($userId, $status = 'open', $limit = 25) {
		return json_decode($this->getIssuesForUserIdRaw($userId, $status, $limit, 'json'));
	}

	/// --- COMMUNICATION --- ///
	/**
	 * This method handles all requests as abstract accessor. It takes the necessarry information
	 * and prepares a request and returns the data of that request.
	 *
	 * @param string	$path:		The path in the REST api (*without* the format)
	 * @param array		$params:	An key => value storage of all parameters that shall be passed to the REST service
	 * @param string	$format:	The format the REST service should return the data (DEFAULT: json)
	 * @return string (XML or JSON string)
	 */
	protected function sendRequest($path = '', $params = array(), $format = 'json') {
		$url = $this->host.$path.'.'.$format;
		return $this->makeCurlRequest($url, $params);
	}

	/**
	 * Takes an URL and sends a request to that URL via curl. The data send via GET to the server.
	 *
	 * @param string	$url:	The target URL
	 * @param array		$data:	A key => value storage of the data that will be passed via URL
	 * @return Raw return data from REST api (depending on format defined in URL)
	 */
	private function makeCurlRequest($url, $data) {
		if (is_array($data) && !empty($data)) {
			$url .= '?'.http_build_query($data);
		}

		$options = array (
	        CURLOPT_HEADER => 0,
	        CURLOPT_URL => $url,
	        CURLOPT_FRESH_CONNECT => 1,
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_FORBID_REUSE => 1,
	        CURLOPT_TIMEOUT => 4
	    );

	    $ch = curl_init();
		curl_setopt_array($ch, $options);

		if (!$result = curl_exec($ch)) {
			$result = curl_error($ch);
		}

		curl_close($ch);
		return $result;
	}
}

?>
