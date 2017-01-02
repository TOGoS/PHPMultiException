<?php

class TOGoS_MultiException extends Exception
{
	protected $causes;
	public function __construct( $message, $code, $causes ) {
		$previous = null; foreach($causes as $previous) break;
		parent::__construct($message, $code, $previous);
		$this->causes = $causes;
	}
	
	public function getCauses() {
		return $this->causes;
	}
	
	public static function throwIfAny( $exceptions, $message="Some stuff went wrong" ) {
		if( count($exceptions) > 0 ) {
			throw new self( $message, 0, $exceptions );
		}
	}
}
