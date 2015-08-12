<?php

namespace Lukebro\Flash;

interface FlashStoreInterface {

	/**
	 * Flash data to the session.
	 * 
	 * @param $name
	 * @param $data
	 */
	public function flash($name, $data);

	/**
	 * Determine if flash has key/name.
	 * 
	 * @param $name
	 */
	public function has($name);

	/**
	 * Get the flash message by name.
	 * 
	 * @param $name
	 */
	public function get($name);
	
}