<?php

namespace Sproj\DriveUpload;

use \BadMethodCallException;

/**
 * Represents a "NULL-object" for FileResource Iterator (it is something like iterator not able to be iterated.)
 */
final class EmptyFileResource extends FileResource {
	public function __construct( array $ids = array() ) {  }

	/** @throws BadMethodCallException */
	public function current() {
		throw new BadMethodCallException;
	}

	/** @throws BadMethodCallException */
	public function key() {
		throw new BadMethodCallException;
	}

	/** @throws BadMethodCallException */
	public function next() {
		throw new BadMethodCallException;
	}

	/** @throws BadMethodCallException */
	public function rewind() {
		throw new BadMethodCallException;
	}

	/** @throws BadMethodCallException */
	public function valid() {
		throw new BadMethodCallException;
	}

	/** @throws BadMethodCallException */
	public function setApiKey($key) {
		throw new BadMethodCallException;
	}
}