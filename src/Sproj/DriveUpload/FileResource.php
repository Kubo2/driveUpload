<?php

namespace Sproj\DriveUpload;

use \LogicException;
use \DomainException;
use \RuntimeException;
use \InvalidArgumentException;

class FileResource implements \Iterator {
	/** @var array identifikátory súborov na Google Drive */
	private $fileIds;

	/** @var array(string(id) => File) objekty súborov*/
	private $files;

	/** @var string Aktuálne ID súboru (z tých poskytnutých v konštruktotre, takže nemusí existovať) */
	private $currentFile;

	/** @var string */
	private $apiKey;

	/**
	 * Konštruktor.
	 * 
	 * @param array Pole identifikátorov súborov vybratých z Google Drive (<del.pomocou picker-u>)
	 */
	public function __construct(array $fileIds) {
		if(!count($fileIds)) {
			throw new \InvalidArgumentException("If you want to represent FileIterator of nothing, please use EmptyFileResource instead");
		}

		$this->fileIds = array_values($fileIds);
		reset($this->fileIds);

		$this->files = array();
		$this->currentFile = current($fileIds);
	}

	/**
	 * [current description]
	 * @return File
	 */
	public function current() {
		if(!isset($this->apiKey)) {
			$c = get_class($this);
			throw new LogicException("You must call $c::setApiKey(string)  before any iteration.");
		}

		if(!isset($this->files[$this->currentFile])) {
			//try {
			//	try {
			//		$this->files[$this->currentFile] = new File( $this->currentFile );
			//	} catch(AuthorizationException $exc) {
			//		$this->files[$this->currentFile] = new File( $this->currentFile, $this->apiKey );
			//	}
			//} catch(DomainException $exc) {
			//	$this->files[$this->currentFile] = new NonFile;
			//} catch(AuthorizationException $exc) {
			//	//throw $exc;
			//	throw new RuntimeException(sprintf("Authorization failed for file %s", $this->currentFile), 0, $exc);
			//}

			try {
				$this->files[$this->currentFile] = new File( $this->currentFile, $this->apiKey ); // api key can be null
			} catch(DomainException $e) {
				$this->files[$this->currentFile] = new NonFile;
			}
		}

		return clone $this->files[$this->currentFile];
	}

	/**
	 * [current description]
	 * @return string
	 */
	public function key() {
		return $this->currentFile;
	}

	/**
	 * [current description]
	 * @return void
	 */
	public function next() {
		$next = next($this->fileIds);

		if($next === FALSE) {
			$this->currentFile = NULL;
			return;
		}
		
		$this->currentFile = $next;
	}

	/**
	 * [current description]
	 * @return void
	 */
	public function rewind() {
		$this->currentFile = reset($this->fileIds);		
	}

	/**
	 * [current description]
	 * @return bool
	 */
	public function valid() {
		if(!isset($this->apiKey)) return false;

		return !($this->currentFile === NULL);
	}

	/**
	 * MUST be called before any iteration.
	 * 
	 * @param string $key
	 */
	public function setApiKey($key) {
		if(!is_string($key)) {
			throw new InvalidArgumentException("API KEy must be string");
		} elseif(isset($this->apiKey)) {
			throw new LogicException("API key may be set only once");
		}

		$this->apiKey = $key;
	}
}
