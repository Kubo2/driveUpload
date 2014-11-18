<?php


namespace Sproj\DriveUpload;

class FirstRequest extends Request {
	final public function isFirst() {
		return true;
	}

	public function hasData() {
		return false;
	}

	public function getData() {
		throw new \BadMethodCallException;
		
	}
}
