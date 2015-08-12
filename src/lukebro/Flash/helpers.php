<?php


// Determine if function already exists
if (! function_exists('flash')) {

	/**
	 * Flash a message to the screen.
	 * 
	 * @param  string $message
	 * @param  string $level
	 * @return void
	 */
    function flash($message = null, $level = 'success')
    {
        $flash = app('Lukebro\Flash\Flash');

        if (func_num_args() == 0) {
            return $flash;
        }

        $flash->create($message, $level);
    }
}
