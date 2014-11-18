<?php

namespace Sproj\DriveUpload;

use \DomainException;

class NonFile {
	private $fileId;
	private $contentType;
	private $originName;
	private $contents;

	public function __construct( $fileId, $apiKey ) {  }

	public function getFileId() {
		throw new DomainException;
	}

	public function getMIMEType() {
		throw new DomainException;
	}

	public function getName() {
		throw new DomainException;
	}

	public function getFIle() {
		throw new DomainException;
	}

	public function __toString() {
		throw new DomainException;
	}
}
