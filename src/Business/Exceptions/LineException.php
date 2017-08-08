<?php
namespace Line\Business\Exceptions;

use App\Exceptions\CustomizeException;

class LineException extends CustomizeException{
	public function __construct($msg, $code=''){
		parent::__construct($msg, '28'.$code);
	}
}
