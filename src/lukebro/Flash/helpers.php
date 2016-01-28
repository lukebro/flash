<?php


// Determine if function already exists
if (! function_exists('flash')) {

	/**
	 * Flash a message to the screen.
	 * 
	 * @param  string $message
	 * @param  string $level
	 * @return Lukebro\Flash\FlashFactory|void
	 */ 
    function flash($level = null, $message = null)
    {
        $flash = app('flash');

        if (func_num_args() == 0) {
            return $flash;
        }

        $flash->create($level, $message);
    }
}
