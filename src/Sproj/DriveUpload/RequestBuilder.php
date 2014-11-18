<?php

namespace Sproj\DriveUpload;


/**
 * Trieda, ktorá má za úlohu vytvoriť stav požiadavku (jednu z inštancií *Request tried).
 *
 * @author   Kubo2
 */
final class RequestBuilder {
	/**
	 * Creates a Request object of an appropriate type.
	 * 
	 * @return Request
	 */
	public function createRequest() {
		if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') { // case insensitive
			if(
				isset($_SERVER['HTTP_X_REQUESTED_WITH']) // because the dialog may only be built with JavaScript
				&& (
					$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'
					|| $_SERVER['HTTP_X_REQUESTED_WITH'] == 'xhr'
				) && (
					isset($_SERVER['HTTP_X_DRIVE_DIALOG']) // our JavaScript also sends
				)
			) {
				return new UploadRequest;
			} else {
				return new PostRequest;
			}
		} else {
			return new FirstRequest;
		}
	}
}
