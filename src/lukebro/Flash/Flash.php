<?php

namespace Lukebro\Flash;

use ArrayAccess;
use Illuminate\Contracts\Support\Jsonable;

class Flash implements ArrayAccess, Jsonable
{

    /**
     * The level of the flash.
     * 
     * @var null
     */
    public $level = null;

    /**
     * The message of the flash.
     * 
     * @var null
     */
    public $message = null;

    /**
     * Create a new instance of a flash.
     * 
     * @param string $level
     * @param string $message
     */
    public function __construct($level, $message)
    {
        $this->level = $level;
        $this->message = $message;
    }

    /**
     * Check to see if certain offset exists.
     * 
     * @param  mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
    	return isset($this->$offset);
    }

    /**
     * Get a certain offset.
     * 
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
    	return isset($this->$offset) ? $this->$offset : null;
    }

    /**
     * Set a certain offset with a value.
     * 
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
    	$this->$offset = $value;
    }

    /**
     * Unset a certain offset.
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
    	unset($this->$offset);
    }

    /**
     * Convert object to array.
     * 
     * @return array
     */
    public function toArray()
    {
        return ['level' => $this->level, 'message' => $this->message];
    }

    /**
     * Convert object to json.
     * 
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
