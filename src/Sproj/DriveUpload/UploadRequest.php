<?php

namespace Sproj\DriveUpload;

/**
 * Trieda reprezentujúca stav, keď má požiadavok za úlohu stiahnuť 
 * zadaný/é súbor/(y) z Google Driveu.
 *
 * @author   Kubo2
 */
class UploadRequest extends Request {
	/** @var array reference to POST data */
	private $data;

	/**
	 * Konštruktor.
	 */
	public function __construct() {
		// from JavaScript, we retrieve POST data as
		// <code>file[]={fileId}&file[]={fileId}</code> and the "fileId" matters for us
		if(isset($_POST[ Dialog::JSFIELD_FILE ]) && is_array($_POST[ Dialog::JSFIELD_FILE ])) {
			$this->data = & $_POST[ Dialog::JSFIELD_FILE ];
		} else {
			$this->data = array();
		}
	}

	/**
	 * {@inheritDoc}
	 *
	 * @override
	 * @return boolean always true
	 */
	final public function isUpload() {
		return true;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @override
	 * @return boolean
	 */
	public function hasData() {
		return ((bool) count($this->data));
	}

	/**
	 * Retrieve a reference to data arrray
	 *
	 * @override
	 * @return array
	 */
	public function & getData() {
		return $this->data;
	}
}