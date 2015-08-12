<?php

namespace Lukebro\Flash;

use Lukebro\Flash\FlashStoreInterface;
use Illuminate\Session\Store;

class FlashStore implements FlashStoreInterface {

	/**
	 * Store object.
	 * 
	 * @var Store
	 */
	private $session;

	/**
	 * Create a new instance of Store.
	 * 
	 * @param Store $session
	 */
	public function __construct(Store $session)
	{
		$this->session = $session;
	}

	/**
	 * Flash data to the session.
	 * 
	 * @param $name
	 * @param $data
	 */
	public function flash($name, $data)
	{
		$this->session->flash($name, $data);
	}

	/**
	 * Determine if flash key/name exists.
	 * 
	 * @param $name
	 * @return boolean
	 */
	public function has($name)
	{
		return $this->session->has($name);
	}

	/**
	 * Get the flash message by name.
	 * 
	 * @param $name
	 * @return string|null
	 */
	public function get($name)
	{
		return $this->session->get($name);
	}
	
}