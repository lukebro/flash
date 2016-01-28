<?php

namespace Lukebro\Flash;

use Illuminate\Support\Collection;

class FlashFactory
{
    /**
     * Key of flash stored in session.
     * 
     * @var string
     */
    public $key = 'flashes';

    /**
     * The current flashes.
     * 
     * @var Collection
     */
    private $current;

    /**
     * The next flashes.
     * 
     * @var array of array
     */
    private $next = [];

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
    public function __construct(FlashStoreInterface $session)
    {
        $this->session = $session;
        $this->current = new Collection;

        $this->getFromSession();
    }

    /**
     * Create a new flash message.
     * 
     * @param  string $message
     * @param  string $level
     * @return this
     */
    public function create($level, $message)
    {
        $this->next[] = ['level' => $level, 'message' => $message];

        $this->session->flash($this->key, $this->next);

        return $this;
    }

    /**
     * Determine if the flash has the level.
     * 
     * @param  string $level
     * @return boolean      
     */
    public function has($level)
    {
        return $this->current->contains('level', $level);
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
     * Reflash message to next session.
     *   
     * @return void
     */
    public function again()
    {
        $this->next = $this->current->map(function ($item) {
            return $item->toArray();
        })->merge($this->next)->toArray();

        $this->session->keep([$this->key]);
    }
    
    /**
     * Get the current flash level.
     * 
     * @return string
     */
    public function getLevel()
    {
        return $this->current->last()->level;
    }

    /**
     * Get the current flash message.
     * 
     * @return string
     */
    public function getMessage()
    {
        return $this->current->last()->message;
    }

    /**
     * Get the current flash message.
     * 
     * @return string
     */
    public function get($level = null)
    {
        if (isset($level)) {
            return $this->getMessage();
        }

        return $this->current->where('level', $level);
    }

    /**
     * Get the all flash messages.
     * 
     * @return Collection
     */
    public function all()
    {
        return $this->current;
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
        if (count($args) == 0) {
            return $this->create($level, null);
        }
        
        return $this->create($level, $args[0]);
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
        if ($this->current->isEmpty()) {
            return null;
        }

        $getter = 'get' . $attribute;

        if (method_exists($this, $getter)) {
            return $this->$getter();
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
        if ($this->exists()) {
            $flashes = $this->session->get($this->key);

            foreach ($flashes as $flash) {
                $this->current->push(new Flash($flash['level'], $flash['message']));
            }
        }
    }
}
