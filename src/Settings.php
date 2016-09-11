<?php
/**
 * Created by PhpStorm.
 * User: const
 * Date: 2016-09-11
 * Time: 11:37 AM
 */

namespace src;


class Settings
{
    /**
     * @const represents running state of the object
     */
    const CAMPAIGN_STATUS_RUNNING = 'running';

    /**
     * @const represents paused state of the object
     */

    const CAMPAIGN_STATUS_PAUSED = 'paused';

    /**
     * @const represents complete state of the object
     */

    const CAMPAIGN_STATUS_COMPLETE = 'complete';

    /**
     * @return int
     */

    public static function start(){

        return time() - 3600 * 24;
    }

}