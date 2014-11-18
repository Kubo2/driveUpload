<?php


namespace Sproj\DriveUpload;

/**
 * Trieda {@code Request} je abstraktným rodičom každého jednotlivého stavu objektu 
 * {@ DriveUpload} či všeoibecnejšie stavu požiadavku. Množina stavov je obmedzená, 
 * preto akékoľvek zmeny pridávajúce nový stav NEBUDÚ spätne kompatibilné a pri 
 * podobných zmenách treba zasiahnuť do KAŽDÉHO priameho potomka tejto triedy.
 *
 * @author   Kubo2
 */
abstract class Request {
	/**
	 * Či je toto "prvý" požiadavok (má za úlohu vykresliť nový dialóg/picker).
	 *
	 * @return bool
	 */
	public function isFirst() {
		return false;
	}

	/**
	 * Či je toto požiadavok na nahratie súboru z Google Drive.
	 *
	 * @return bool
	 */
	public function isUpload() {
		return false;
	}

	/**
	 * Či je toto požiadavok POST metódou (akýkoľvek post).
	 */
	public function isPost() {
		return false;
	}

	/**
	 * Či požiadavok obsahuje nejaké priložené dáta (v prípade POST požiadavku tzv. "POSTDATA" apod)
	 * Každý stav požiadavku môže mať vlastný systém na získavanie dát, nemusí ich mať nutne ani uložené.
	 * 
	 * @return boolean
	 */
	public abstract function hasData();

	/**
	 * Oddeľuje dáta od samotnej logiky/cesty k ich získaniu
	 *
	 * @return array
	 */
	public abstract function getData();
}
