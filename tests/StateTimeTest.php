<?php

/**
 * Created by PhpStorm.
 * User: const
 * Date: 2016-09-11
 * Time: 11:36 AM
 */

use src\StateTime;
use src\Settings;

class RunningTimeTest extends PHPUnit_Framework_TestCase
{



    public function setUp(){}
    public function tearDown(){ }

    /**
     * @dataProvider additionProvider
     */
    public function testOne($testName, $statusLog, $startDate, $stopDate, $answer)

    {


        $stateTime = new StateTime();
        $result = $stateTime->getResult($statusLog, $startDate, $stopDate);
        $this->assertTrue($result == $answer);
        echo $testName.PHP_EOL;




    }

    public function additionProvider()
    {
        $start = Settings::start();

        return [

            //First Test
            ['First Test Case',
                array(
                    array(
                        'date' => date("U", strtotime("2015-10-15")),
                        'oldState' => null,
                        'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                    )
                ),null, null, 0
            ],

            //Second Test
            ['Second Test Case',array(
                array(
                    'date' => date("U", strtotime("2015-10-15")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                )
            ),date("U", strtotime("next week")),null,0],

            //Third Test
            ['Third Test Case',array(
                array(
                    'date' => date("U", strtotime("2015-10-15")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-16")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                )
            ),null, null, time() - date("U", strtotime("2015-10-16"))],

            //Fourth Test
            ['Fourth Test Case', array(
                array(
                    'date' => date("U", strtotime("2015-10-15")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-16")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                ),
                array(
                    'date' => date("U", strtotime("2015-10-17")),
                    'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-18")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                ),
            ),null, null, time() - date("U", strtotime("2015-10-18")) + (24 * 60 * 60)],

            //Fifth Test
            ['Fifth Test Case', array(
                array(
                    'date' => date("U", strtotime("2015-10-15")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-16")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                ),
                array(
                    'date' => date("U", strtotime("2015-10-17")),
                    'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-18")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                ),
                array(
                    'date' => date("U", strtotime("2015-10-18 12:00:00")),
                    'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-19")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                ),
            ), null, null, time() - date("U", strtotime("2015-10-19")) + (24 * 60 * 60 * 1.5)],

            //Sixth Test
            ['Sixth Test Case',

                array(
                    array(
                        'date' => date("U", strtotime("2015-10-15")),
                        'oldState' => null,
                        'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                    ),
                    array(
                        'date' => date("U", strtotime("2015-10-16")),
                        'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                        'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                    ),
                    array(
                        'date' => date("U", strtotime("2015-10-17")),
                        'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                        'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                    ),
                    array(
                        'date' => date("U", strtotime("2015-10-18")),
                        'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                        'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                    ),
                    array(
                        'date' => date("U", strtotime("2015-10-18 12:00:00")),
                        'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                        'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                    ),
                    array(
                        'date' => date("U", strtotime("2015-10-19")),
                        'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                        'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                    ),
                    array(
                        'date' => date("U", strtotime("2015-10-20")),
                        'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                        'newState' => Settings::CAMPAIGN_STATUS_COMPLETE
                    )
                ),null, null, (24 * 60 * 60 * 2.5)],

            //Seventh Test
            ['Seventh Test Case', array(
                array(
                    'date' => date("U", strtotime("2015-10-13")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'debug' => 'date("U", strtotime("2015-10-13"))'
                ),
                array(
                    'date' => date("U", strtotime("2015-10-14")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'debug' => 'date("U", strtotime("2015-10-14"))'
                )
            ),date("U", strtotime("2015-10-15")), null, time() - date("U", strtotime("2015-10-15"))],

            //Eighth Test
            ['Eighth Test Case',array(
                array(
                    'date' => date("U", strtotime("2015-10-13")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-16")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                )
            ),date("U", strtotime("2015-10-15")), null, time() - date("U", strtotime("2015-10-16"))],



            //Ninth Test
            ['Ninth Test Case',array(
                array(
                    'date' => date("U", strtotime("2015-10-15")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-16")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'debug' => 'date("U", strtotime("2015-10-16"))',
                ),
                array(
                    'date' => date("U", strtotime("2015-10-17")),
                    'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'debug' => 'date("U", strtotime("2015-10-17"))',
                ),
                array(
                    'date' => date("U", strtotime("2015-10-18")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'debug' => 'date("U", strtotime("2015-10-18"))',
                ),
                array(
                    'date' => date("U", strtotime("2015-10-18 12:00:00")),
                    'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'debug' => 'date("U", strtotime("2015-10-18 12:00:00"))',
                ),
                array(
                    'date' => date("U", strtotime("2015-10-19")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'debug' => 'date("U", strtotime("2015-10-19"))',
                ),
            ),date("U", strtotime("2015-10-17")), null, (time() - date("U", strtotime("2015-10-19"))) + (12 * 60 * 60) ],


            //Tenth Test
            ['Tenth Test Case',array(
                array(
                    'date' => date("U", strtotime("2015-10-13")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-14")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                )
            ),null, date("U", strtotime("2015-10-15")), 24 * 60 * 60],

            //Eleventh Test
            ['Eleventh Test Case',array(
                array(
                    'date' => date("U", strtotime("2015-10-13")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-14")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                ),
                array(
                    'date' => date("U", strtotime("2015-10-15")),
                    'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'newState' => Settings::CAMPAIGN_STATUS_COMPLETE
                )
            ),null, date("U", strtotime("2015-10-18")), 24 * 60 * 60],

            //Twelfth Test
            ['Twelfth Test Case', array(
                array(
                    'date' => date("U", strtotime("2015-10-15")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => date("U", strtotime("2015-10-16")),
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                ),
                array(
                    'date' => date("U", strtotime("2015-10-17")),
                    'oldState' => Settings::CAMPAIGN_STATUS_RUNNING,
                    'newState' => Settings::CAMPAIGN_STATUS_RUNNING
                )
            ), null, null, time() - date("U", strtotime("2015-10-16"))],

            ['Thirteenth Test Case',array(
                array(
                    'date' => date("U", strtotime("2015-10-15")),
                    'oldState' => null,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                ),
                array(
                    'date' => $start + 1800,
                    'oldState' => Settings::CAMPAIGN_STATUS_PAUSED,
                    'newState' => Settings::CAMPAIGN_STATUS_PAUSED
                )
            ), null, null, 0]






        ];
    }


}