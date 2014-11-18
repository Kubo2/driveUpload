<?php

namespace Sproj\DriveUpload;

final class Uploader {
	public function __construct( $apiKey = null ) {
		$this->urlTemplate = 'https://www.googleapis.com/drive/v2/files/%s';

		//if(isset($apiKey)) {
			$this->urlTemplate .= '?key=$apiKey';
		//}
	}

	public function get($fileId) {
		$url = sprintf($this->urlTemplate, $fileId);
		$res = fopen($url, 'r');
		$response = stream_get_meta_data($res);
		$code = (int) substr($response['wrapper_data'][0], 9, 3);

		if($code != 200) {
			throw new DomainException;
		}

		 $meta = json_decode(
			stream_get_contents($res),
			true
		);
		fclose($res);
		 $information = array(
		 	'fileId' => $meta['id'],
		 	'name' => $meta['title'],
		 	'mime' => $meta['mimeType'],
		);

		if(isset($meta['downloadUrl'])) {
			$information['content'] = file_get_contents($meta['downloadUrl']);
		} else if(!isset($meta['exportLinks']['application/pdf'])) {
			throw new RuntimeException();
		}

		$information['content'] = file_get_contents($meta['exportLinks']['application/pdf']);

		return $information;
	}
}
