<?php
/**
 * Created by PhpStorm.
 * User: const
 * Date: 2016-09-11
 * Time: 11:36 AM
 */

date_default_timezone_set('America/New_York');//or change to whatever timezone you want


spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(

                'src\\statetime' => '/StateTime.php',
                'src\\settings' => '/Settings.php',

            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    },
    true,
    false
);
// @codeCoverageIgnoreEnd