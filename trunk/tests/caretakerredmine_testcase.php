<?php

require_once (t3lib_extMgm::extPath('caretaker_rsml').'/services/class.tx_caretakerrsmlTestService.php');

class tx_caretakersnmp_testcase extends tx_phpunit_testcase  {

	function testReturnsErrorIfConfigurationIsMissing (){

		$rsml_test_service = $this->getMock(
			'tx_caretakerrsmlTestService',
			array('executeHttpRequest', 'getConfiguration')
		);

		$rsml_test_service->expects($this->once())
			->method('getConfiguration')
			->with()
			->will($this->returnValue(
				array(
					"rsmlUrl"             => '',
					"expectedRsmlId"      => '',
					"expectedRsmlVersion" => '',
					"expectedStatus"      => '',
					"expectedValue"       => ''
				)
			));

		$result = $rsml_test_service->runTest();
		$this->assertEquals( tx_caretaker_Constants::state_error,  $result->getState()  );
	}

	function testRunsOkIfConfigAndResultAreFine (){

		$rsml_test_service = $this->getMock(
			'tx_caretakerrsmlTestService',
			array('executeHttpRequest', 'getConfiguration')
		);

		$rsml_test_service->expects($this->once())
			->method('getConfiguration')
			->with()
			->will($this->returnValue(
				array(
					"rsmlUrl"             => 'foo.htm',
					"expectedRsmlId"      => 'test',
					"expectedRsmlVersion" => '1.2.3',
					"expectedStatus"      => 0,
					"expectedValue"       => '>9'
				)
			));
		
		$rsml_test_service->expects($this->once())
			->method('executeHttpRequest')
			->with('foo.htm')
			->will($this->returnValue(
				array(
					'response'=>'<?xml version="1.0" encoding="UTF-8"?><rsml><rsmlId>test</rsmlId><rsmlVersion>1.2.3</rsmlVersion><status>0</status><value>10</value></rsml>',
					'info' => array('http_code' => 200)
				)
			));


		$result = $rsml_test_service->runTest();
		$this->assertEquals( tx_caretaker_Constants::state_ok,  $result->getState()  );		
	}

	function testFailsIfHttpFails(){

		$rsml_test_service = $this->getMock(
			'tx_caretakerrsmlTestService',
			array('executeHttpRequest', 'getConfiguration')
		);

		$rsml_test_service->expects($this->once())
			->method('getConfiguration')
			->with()
			->will($this->returnValue(
				array(
					"rsmlUrl"             => 'foo.htm',
					"expectedRsmlId"      => 'test',
					"expectedRsmlVersion" => '1.2.3',
					"expectedStatus"      => 0,
					"expectedValue"       => '>9'
				)
			));

		$rsml_test_service->expects($this->once())
			->method('executeHttpRequest')
			->with('foo.htm')
			->will($this->returnValue(
				array(
					'response'=>'<?xml version="1.0" encoding="UTF-8"?><rsml><rsmlId>test</rsmlId><rsmlVersion>1.2.3</rsmlVersion><status>0</status><value>10</value></rsml>',
					'info' => array('http_code' => 404)
				)
			));


		$result = $rsml_test_service->runTest();
		$this->assertEquals( tx_caretaker_Constants::state_error,  $result->getState()  );
	}

	function testFailsIfScriptIdIsWrong(){

		$rsml_test_service = $this->getMock(
			'tx_caretakerrsmlTestService',
			array('executeHttpRequest', 'getConfiguration')
		);

		$rsml_test_service->expects($this->once())
			->method('getConfiguration')
			->with()
			->will($this->returnValue(
				array(
					"rsmlUrl"             => 'foo.htm',
					"expectedRsmlId"      => 'test',
					"expectedRsmlVersion" => '1.2.3',
					"expectedStatus"      => 0,
					"expectedValue"       => '>9'
				)
			));

		$rsml_test_service->expects($this->once())
			->method('executeHttpRequest')
			->with('foo.htm')
			->will($this->returnValue(
				array(
					'response'=>'<?xml version="1.0" encoding="UTF-8"?><rsml><rsmlId>not_test</rsmlId><rsmlVersion>1.2.3</rsmlVersion><status>0</status><value>10</value></rsml>',
					'info' => array('http_code' => 200)
				)
			));


		$result = $rsml_test_service->runTest();
		$this->assertEquals( tx_caretaker_Constants::state_error,  $result->getState()  );
	}

	function testFailsIfScriptVersionIsTooLow(){

		$rsml_test_service = $this->getMock(
			'tx_caretakerrsmlTestService',
			array('executeHttpRequest', 'getConfiguration')
		);

		$rsml_test_service->expects($this->once())
			->method('getConfiguration')
			->with()
			->will($this->returnValue(
				array(
					"rsmlUrl"             => 'foo.htm',
					"expectedRsmlId"      => 'test',
					"expectedRsmlVersion" => '1.2.3',
					"expectedStatus"      => 0,
					"expectedValue"       => '>9'
				)
			));

		$rsml_test_service->expects($this->once())
			->method('executeHttpRequest')
			->with('foo.htm')
			->will($this->returnValue(
				array(
					'response'=>'<?xml version="1.0" encoding="UTF-8"?><rsml><rsmlId>test</rsmlId><rsmlVersion>1.2.2</rsmlVersion><status>0</status><value>10</value></rsml>',
					'info' => array('http_code' => 200)
				)
			));


		$result = $rsml_test_service->runTest();
		$this->assertEquals( tx_caretaker_Constants::state_error,  $result->getState()  );
	}

	function testReturnsWarningIfStatusIsOne(){

		$rsml_test_service = $this->getMock(
			'tx_caretakerrsmlTestService',
			array('executeHttpRequest', 'getConfiguration')
		);

		$rsml_test_service->expects($this->once())
			->method('getConfiguration')
			->with()
			->will($this->returnValue(
				array(
					"rsmlUrl"             => 'foo.htm',
					"expectedRsmlId"      => 'test',
					"expectedRsmlVersion" => '1.2.3',
					"expectedStatus"      => 0,
					"expectedValue"       => '>9'
				)
			));

		$rsml_test_service->expects($this->once())
			->method('executeHttpRequest')
			->with('foo.htm')
			->will($this->returnValue(
				array(
					'response'=>'<?xml version="1.0" encoding="UTF-8"?><rsml><rsmlId>test</rsmlId><rsmlVersion>1.2.3</rsmlVersion><status>1</status><value>10</value></rsml>',
					'info' => array('http_code' => 200)
				)
			));


		$result = $rsml_test_service->runTest();
		$this->assertEquals( tx_caretaker_Constants::state_warning,  $result->getState()  );
	}

	function testReturnsErrorIfStatusIsTwo(){

		$rsml_test_service = $this->getMock(
			'tx_caretakerrsmlTestService',
			array('executeHttpRequest', 'getConfiguration')
		);

		$rsml_test_service->expects($this->once())
			->method('getConfiguration')
			->with()
			->will($this->returnValue(
				array(
					"rsmlUrl"             => 'foo.htm',
					"expectedRsmlId"      => 'test',
					"expectedRsmlVersion" => '1.2.3',
					"expectedStatus"      => 0,
					"expectedValue"       => '>9'
				)
			));

		$rsml_test_service->expects($this->once())
			->method('executeHttpRequest')
			->with('foo.htm')
			->will($this->returnValue(
				array(
					'response'=>'<?xml version="1.0" encoding="UTF-8"?><rsml><rsmlId>test</rsmlId><rsmlVersion>1.2.3</rsmlVersion><status>2</status><value>10</value></rsml>',
					'info' => array('http_code' => 200)
				)
			));

		$result = $rsml_test_service->runTest();
		$this->assertEquals( tx_caretaker_Constants::state_error,  $result->getState()  );
		
	}

}
	
?>