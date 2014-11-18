<?php

namespace Sproj\DriveUpload;

/**
 * Trieda {@code DriveUpload} poskytuje odľahčené a zároveň jediné použiteľné 
 * rozhranie pre manipuláciu so systémom DriveUpload.
 * Táto trieda sa snaží čo najviac zviazať prácu k sebe tak, aby ostatné triedy pokiaľ 
 * možno nemali prístup k inštancii konfigurácie.
 *
 * @author   Kubo2
 */
final class DriveUpload {
	/** @var Request Objekt reprezentujúci momentálny stav inštancie DriveUpload */
	private $request;

	/** @var AConfiguration Objekt uchovávajúci konfiguračné hodnoty potrebné k väčšine akcií */
	private $config;

	/** @var DialogBuilder Objekt starajúci sa o správne vytvorenie objektu, ktorý má na klientovi vykresliť dialóg */
	private $dialogBuilder;

	/**
	 * Vytvorí nový objekt fasády {@code DriveUpload}.
	 *
	 * @param AConfiguration objekt rozhrania AConfiguration, uchovávajúci konfiguráčné hodnoty
	 */
	public function __construct(AConfiguration $cfg) {
		// configuration
		$this->config = clone $cfg;

		// object state
		$rb = new RequestBuilder();
		$this->request = $rb->createRequest();

		// the dialog
		$this->dialogBuilder = new DialogBuilder(
			$this->config->oAuthKey,
			$this->config->browserApiKey // developer application API key for browsers
		);

		if(count($this->config->pickerScope)) {
			$this->dialogBuilder->setPickerScope($this->config->pickerScope);
		}
	}

	/**
	 * @return mixed
	 */
	public function handle() {
		if($this->request->isFirst()) {
			$this->dialogBuilder->createDialog()->render();
		} else if($this->request->isUpload()) {
			return $this->getFiles();
		}
	}

	/**
	 * Vráti stav objektu.
	 *
	 * @return Request
	 */
	public function getState() {
		return clone $this->request;
	}

	/**
	 * Vráti iterátor obsahujúci súbory, ktoré užívateľ nahral.
	 *
	 * @return FileResource
	 * @throws RequestTypeException if request does not allow to retrieve information about files
	 */
	public function getFiles() {
		if(!$this->request->isUpload()) {
			throw new RequestTypeException(sprintf( "%s is not appropriate request type for this information", $this->request));
		}

		if($this->request->hasData()) {
			$_FR = new FileResource($this->request->getData());
			$_FR->setApiKey($this->config->serverApiKey);

			return $_FR; // ============>
		}

		return new EmptyFileResource;
	}

	/**
	 * Sets allowed mime types for the dialog.
	 * 
	 * @param array $mimes
	 */
	public function setMIMETypes(array $mimes) {
		$this->dialogBuilder->setMimes($mimes);
	}

	/**
	 * Sets dialog scope (what parts of drive to show in dialog).
	 * When setting scope, it does not reset actual scopes, but adds 
	 * passed scope to the end of scopes list.
	 *
	 * @param array
	 */
	public function setDialogScope(array $scope) {
		$this->dialogBuilder->setDialogScope($scope);
	}
}

/**
 * The exception that is thrown when the request type/state is of an inappropriate type.
 */
class RequestTypeException extends \RuntimeException {  }
