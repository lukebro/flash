<?php

namespace Lukebro\Flash;

use Lukebro\Flash\StoreInterface as Store;

class Flash
{
    
    /**
     * Levels that will be overwritten when stored.
     * 
     * @var array
     */
    protected $overwrite = [
        'error' => 'danger'
    ];

    /**
     * Key of flash stored in session.
     * 
     * @var string
     */
    protected $key = 'flash_message';

    /**
     * The current flashed level.
     * 
     * @var string
     */
    private $level;

    /**
     * The current flashed message.
     * 
     * @var string
     */
    private $message;

    /**
     * The session store.
     * 
     * @var Store
     */
    private $session;

    /**
     * Create a new instance of Flash.
     * 
     * @param Store $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;

        $this->getFromSession();
    }

    /**
     * Create a new flash message.
     * @param  string $message
     * @param  string $level
     * @return boolean
     */
    public function create($message, $level)
    {
        if (array_key_exists($level, $this->overwrite)) {
            $level = $this->overwrite[$level];
        }

        return $this->session->flash($this->key, [
            'message' => $message,
            'level' => $level
        ]);
    }

    /**
     * Determine if the flash has the level.
     * 
     * @param  string $level
     * @return boolean      
     */
    public function has($level)
    {
        return $this->level === $level;
    }

    /**
     * Get the current flash message.
     * 
     * @return string
     */
    public function get()
    {
        return $this->message;
    }

    /**
     * Determine if any flashes exist in current session.
     * 
     * @return boolean
     */
    public function exists()
    {
        return $this->session->has($this->key);
    }

    /**
     * Get the current key used in session.
     * 
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get the current flash level.
     * 
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Get the current flash message.
     * 
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Creates a new flash message where the level
     * is the method name and parameter is the message.
     * 
     * @param  string $level
     * @param  array $args
     * @return boolean
     */
    public function __call($level, $args)
    {
        if (count($args) != 1) {
            throw new InvalidArgumentException;
        }

        return $this->create($args[0], $level);
    }

    /**
     * Gets the current flash level
     * and message using accessor methods.
     * 
     * @param  string $attribute
     * @return string|null
     */
    public function __get($attribute)
    {
        $func = 'get' . $attribute;

        if(method_exists($this, $func)) {
            return $this->$func();
        }

        return null;
    }

    /**
     * Get current flash message from session.
     * 
     * @return void
     */
    private function getFromSession()
    {
        if($this->exists()) {
            $flash = $this->session->get($this->key);

            $this->level = $flash['level'];
            $this->message = $flash['message'];
        }
    }
}