<?php

namespace Sproj\DriveUpload;

use \DomainException;

class File {
	private $fileId;
	private $contentType;
	private $originName;
	private $contents;

	public function __construct( $fileId, $apiKey ) {
		// attempt to get a temporary directory pathname
		$storeTo = (
			is_writable(sys_get_temp_dir())
			&& is_readable(sys_get_temp_dir())
				? sys_get_temp_dir()
				: dirname(dirname(dirname(dirname(__FILE__)))) . '/temp'
			) . '/'
		;

		// if this directory isnt present, we creater it
		if(!is_dir($storeTo)) mkdir($storeTo);

		// new instance of Uploader
		$drive = new Uploader($apiKey);

		// retrieve an information about this instance - may throw DomainException
		$information = $drive->get($fileId);

		// export into function scope
		extract($information);

		$this->fileId = $fileId;
		$this->contentType = $mime;
		$this->originName = $name;
		$this->contents = $content;
	}

	public function getFileId() {
		return $this->fileId;
	}

	public function getMIMEType() {
		return $this->contentType;
	}

	public function getName() {
		return $this->originName;
	}

	public function getFIle() {
		return $this->contents;
	}

	public function __toString() {
		return $this->getFile();
	}
}

/**
 * The exception that is thrown when authorization fails for something.
 */
//class AuthorizationException extends DomainException {  }
