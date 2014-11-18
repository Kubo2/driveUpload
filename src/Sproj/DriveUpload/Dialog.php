<?php

namespace Sproj\DriveUpload;

class Dialog {
  /** View and manage the files and documents in your Google Drive. */
  const DRIVE =
      "https://www.googleapis.com/auth/drive";
  /** View and manage its own configuration data in your Google Drive. */
  const DRIVE_APPDATA =
      "https://www.googleapis.com/auth/drive.appdata";
  /** View your Google Drive apps. */
  const DRIVE_APPS_READONLY =
      "https://www.googleapis.com/auth/drive.apps.readonly";
  /** View and manage Google Drive files that you have opened or created with this app. */
  const DRIVE_FILE =
      "https://www.googleapis.com/auth/drive.file";
  /** View metadata for files and documents in your Google Drive. */
  const DRIVE_METADATA_READONLY =
      "https://www.googleapis.com/auth/drive.metadata.readonly";
  /** View the files and documents in your Google Drive. */
  const DRIVE_READONLY =
      "https://www.googleapis.com/auth/drive.readonly";
  /** Modify your Google Apps Script scripts' behavior. */
  const DRIVE_SCRIPTS =
      "https://www.googleapis.com/auth/drive.scripts";

	private $patch;
	private $oAuthKey;
	private $apiKey;
	private $scope;
	private $mimeTypes;

	public function __construct( $oAuthKey, $apiKey, array $scope, array $mimeTypes ) {
		$this->patch = new SafeHTMLPatch("<script src='scripts/driveUpload.js'></script>\n");

		$this->oAuthKey = $oAuthKey;
		$this->apiKey = $apiKey;
		$this->scope = $scope;
		$this->mimeTypes = $mimeTypes;
	}

	public function render() {
		$thePatch = "<script>window.driveUpload.init(%s, %s, %s); driveUpload.setMimes(%s)</script>";
		$thePatch = new SafeHTMLPatch(
			sprintf( $thePatch,
					json_encode($this->apiKey),
					json_encode($this->oAuthKey),
					json_encode($this->scope),
					json_encode($this->mimeTypes)
			)
		);

		$this->patch->merge($thePatch);

		echo $this->patch;
	}
}
