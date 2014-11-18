<?php

namespace Sproj\DriveUpload;

class DialogBuilder {
	private $oAuthKey;
	private $apiKey;

	private $scope = array();
	private $mimes = array();

	public function __construct( $oAuthKey, $apiKey ) {
		$this->oAuthKey = $oAuthKey;
		$this->apiKey = $apiKey;
	}

	public function setPickerScope(array $scope) {
		array_merge($this->scope, $scope);
	}

	public function setMimes(array $mimes) {
		$this->mimes = $mimes;
	}

	public function createDialog() {
		return new Dialog(
			$oAuthKey,
			$apiKey,
			$scope,
			$mimes
		);
	}
}
