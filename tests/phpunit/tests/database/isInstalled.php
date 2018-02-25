<?php

class Tests_Database_isInstalled extends SimpleDocumentation_DB_UnitTestCase {

	public function test_is_not_installed() {
		$this->assertFalse( \Simple_Documentation\is_installed() );
	}

	public function test_is_installed() {
		\Simple_Documentation\create_table();

		$this->assertTrue( \Simple_Documentation\is_installed() );
	}
}
