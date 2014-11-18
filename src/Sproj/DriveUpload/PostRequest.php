<?php

namespace Sproj\DriveUpload;

final class PostRequest extends Request {
	final public function isPost() {
		return true;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @override
	 * @return boolean
	 */
	public function hasData() {
		return ((bool) count($_POST));
	}

	/**
	 * Retrieve a reference to data arrray
	 *
	 * @override
	 * @return array
	 */
	public function & getData() {
		return $_POST;
	}
}