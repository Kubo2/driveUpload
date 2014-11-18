<?php

namespace Sproj\DriveUpload;

/**
 * Rozhranie typu {@code AConfiguration} poskytuje verejné rozhranie 
 * všetkých objektov predstavujúcich konfiguráciu systému DriveUpload 
 * (používateľ systému si tak môže definovať vlastný konfiguračný objekt).
 *
 * Objekt implementujúci toto rozhranie MUSÍ poskytnúť všetky verejne 
 * prístupné vlastnosti deklarované prístupovými metódami s názvami 
 * BEZ prefixov v názvoch metód (ako 'get', ''is' apod).
 * 
 * @internal O toto sa po novom postará abstraktná verzia rozhrania - trieda {@code AConfiguration}.
 *
 * @author   Kubo2
 * @version  0.0.0
 */
abstract class AConfiguration {
	/** @var array */
	private $accessorPrefixes = array('get', 'is', 'has');

	/**
	 * Setting a value for configuration option.
	 *
	 * @param string $option
	 * @param mixed $value
	 * @throws \LogicException
	 */
	final public function __set($option, $value) {
		$this->canNotModify();
	}

	/**
	 * Unsetting a value for configuration option.
	 * @param string $option
	 * @throws \LogicException
	 */
	final public function __unset($option) {
		$this->canNotModify();
	}

	/**
	 * Whether there is configuration option of this name
	 * 
	 * @param  string  $option
	 * @return boolean
	 * 
	 */
	final public function __isset($option) {
		foreach($this->accessorPrefixes as $prefix) {
			if(!method_exists($this, $prefix . $option)) {
				continue;
			}

			return true;
		}

		return false;
	}

	/**
	 * Provides an interface to access 
	 * @param  string $option
	 * @return mixed
	 */
	final public function __get($option) {
		if(isset($this->$option)) {
			foreach($this->accessorPrefixes as $prefix) {
				if(!method_exists($this, $prefix . $option)) {
					continue;
				}

				return call_user_func(array($this, $prefix . $option));
			}
		}

		throw new LogicException("Configuration option $option does not exist ");
	}

	/**
	 * [canNotModify description]
	 * 
	 * @throws \LogicException
	 */
	private function canNotModify() {
		throw new \LogicException("Can not modify configuration object at run-time");
	}

	// ==============================================================================
	// =================================== Accessors ===================================

	/**
	 * [getOAuthKey description]
	 * @return string
	 */
	abstract public function getOAuthKey();

	/**
	 * [getBrowserApiKey description]
	 * @return string
	 */
	abstract public function getBrowserApiKey();

	/**
	 * [getServerApiKey description]
	 * @return string
	 */
	abstract public function getServerApiKey();

	/**
	 * [getPickerScope description]
	 * @return array
	 */
	abstract public function getPickerScope();
}
