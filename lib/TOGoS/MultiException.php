<?php

class TOGoS_MultiException extends Exception
{
	protected $causes;
	protected $data;
	
	public function __construct( $message, $code=0, $causes=array(), $data=array() ) {
		$previous = null; foreach($causes as $previous) break;
		parent::__construct($message, $code, $previous);
		$this->causes = $causes;
		$this->data = $data;
	}
	
	public function getCauses() {
		return $this->causes;
	}
	
	public function getData($key=null) {
		if( $key === null ) return $this->data;
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
	
	public static function throwIfAny( $exceptions, $message="Some stuff went wrong", $data=array() ) {
		if( count($exceptions) > 0 ) {
			throw new self( $message, 0, $exceptions, $data );
		}
	}
}
