<?php

namespace Sproj\DriveUpload;

final class SafeHTMLPatch {
	private $html;

	public function __construct($patch) {
		$this->html = $patch;
	}

	public function merge(SafeHTMLPatch $patch) {
		$this->html .= $patch;
	}

	public function __toString() {
		return $this->html;
	}
}
