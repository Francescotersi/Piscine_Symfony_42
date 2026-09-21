<?php

class MyException extends Exception
{
	public function __construct($message = "Error: error invalid Tag\n", $code = 1, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}