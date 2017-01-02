<?php

class TOGoS_MultiExceptionTest extends PHPUnit_Framework_TestCase
{
	public function testThrowNothing() {
		TOGoS_MultiException::throwIfAny( array() );
		// If we get here, the test passed.
	}
	
	public function testConstructCauseless() {
		// If you really want to you can throw a MultiException with zero causes.
		// Maybe this can remove a special case from your code somewhere.
		$me = new TOGoS_MultiException( "Nothing went wrong!", 0, array() );
		$this->assertEquals( "Nothing went wrong!", $me->getMessage() );
		$this->assertNull( $me->getPrevious() );
		$this->assertEquals( 0, count($me->getCauses()) );
	}
	
	public function testThrowOne() {
		$caught = null;
		try {
			TOGoS_MultiException::throwIfAny( array(new Exception("Test")) );
		} catch( TOGoS_MultiException $me ) {
			$caught = $me;
		}
		$this->assertNotNull( $caught, "Should've thrown a MultiException!" );
		$this->assertNotNull( $caught->getPrevious() );
		$this->assertEquals( "Test", $caught->getPrevious()->getMessage() );
		$this->assertEquals( 1, count($caught->getCauses()) );
		$this->assertEmpty( $caught->getData() );
	}

	public function testThrowTwo() {
		$caught = null;
		try {
			TOGoS_MultiException::throwIfAny( array(new Exception("Test1"), new Exception("Test2")), "Sew mani sepshins" );
		} catch( TOGoS_MultiException $me ) {
			$caught = $me;
		}
		$this->assertNotNull( $caught, "Should've thrown a MultiException!" );
		$this->assertEquals( "Sew mani sepshins", $caught->getMessage() );
		$this->assertNotNull( $caught->getPrevious() );
		$this->assertEquals( "Test1", $caught->getPrevious()->getMessage() );
		$cazzes = $caught->getCauses();
		$this->assertEquals( 2, count($cazzes) );
		$this->assertEquals( "Test1", $cazzes[0]->getMessage() );
		$this->assertEquals( "Test2", $cazzes[1]->getMessage() );
	}
	
	public function testThrowWithData() {
		$caught = null;
		try {
			TOGoS_MultiException::throwIfAny( array(new Exception("Test1")), "Sew mani sepshins", 'Frog pellets' );
		} catch( TOGoS_MultiException $me ) {
			$caught = $me;
		}
		$this->assertNotNull( $caught, "Should've thrown a MultiException!") ;
		$this->assertEquals( 'Frog pellets', $caught->getData() );
		$this->assertNull( $caught->getData('leafs') );
	}
	
	public function testThrowWithMoreData() {
		$caught = null;
		$data = array('pellets'=>'Frogs', 'leafs'=>'Trees');
		try {
			TOGoS_MultiException::throwIfAny( array(new Exception("Test1")), "Sew mani sepshins", $data );
		} catch( TOGoS_MultiException $me ) {
			$caught = $me;
		}
		$this->assertNotNull( $caught, "Should've thrown a MultiException!") ;
		$this->assertEquals( $data, $caught->getData() );
		$this->assertEquals( 'Frogs', $caught->getData('pellets') );
		$this->assertEquals( 'Trees', $caught->getData('leafs') );
		$this->assertNull( $caught->getData('ostrichs') );
	}
}
