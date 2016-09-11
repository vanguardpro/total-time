<?php

namespace src;


/**
 * Created by PhpStorm.
 * User: const
 * Date: 2016-09-11
 * Time: 11:35 AM
 */
class StateTime
{


    /**
     * @const represents key name in logStatus, could be changed if needed
     */

    const KEY_DATE = 'date';
    const KEY_OLD_STATE = 'oldState';
    const KEY_NEW_STATE = 'newState';


    /**
     * @const represents key name in object used to get difference between dates for object when status has changed given to opposite
     */
    const FIRST_ARGUMENT = 'first_argument';
    const SECOND_ARGUMENT = 'second_argument';
    const OTHER_THAN_PREFIX = 'other_than_';
    /**
     * @var string
     * this value is added as the bonus part of the task, to be able do task for different states
     */
    private $state;

    /**
     * @var string
     * this value startDate of the targeted date range
     */
    private $startDate;


    /**
     * @var string
     * this value stopDate of the targeted date range
     */
    private $stopDate;


    /**
     * StateTime constructor.
     * @param string $state - could be changed to
     * change targeting of the RUNNING state to PAUSED OR COMPLETED
     */

    public function __construct($state = Settings::CAMPAIGN_STATUS_RUNNING)
    {

        $this->state = $state;

    }


    /**
     * single entry point to teg result
     * @param array $obj
     * @param null $startDate
     * @param null $stopDate
     * @return int - number of seconds object was in given state - default RUNNING
     */
    public function getResult($obj = [], $startDate = null, $stopDate = null)

    {

        //init

        $date = [];

        //assigning to private variables startDate and stopDate for convenience
        $this->startDate = $startDate;
        $this->stopDate = $stopDate;


        // combine array by start and stop in one group

        foreach ($obj as $key => $value) {


            //this is to keep array consistent to avoid error in array_multisort.
            $value = $this->keepObjectConsistent($value);

            //to remove all objects states where new or old state is not given or both states are given and it is not affecting state changes
            if ($value[self::KEY_DATE] != null
                && ($value[self::KEY_NEW_STATE] === $this->state || $value[self::KEY_OLD_STATE] === $this->state)
                && !($value[self::KEY_NEW_STATE] === $this->state && $value[self::KEY_OLD_STATE] === $this->state)

            ) {
                $date[$key] = $value[self::KEY_DATE];

            } else {

                unset($obj[$key]);
            }


        }
        // Sort the obj with date descending
        array_multisort($date, SORT_DESC, $obj);

        if (count($obj) == 0) {

            return 0;

        } else {

            $pairedObj = $this->pairStates($obj);
            return $this->doMath($pairedObj);


        }

    }

    private function pairStates($obj)
    {

        //init
        $pairedObj = [];
        $latestStopDate = time();
        $i = 0;


        foreach ($obj as $key => $value) {


            //this statement needs to create guideline for pairedArray by state change event
            if ($this->state != $value[self::KEY_NEW_STATE]) {

                $pairedObj[$i][self::FIRST_ARGUMENT] = $obj[$key];

                //check if it is not the last iteration
                if (isset($obj[$key + 1])) {
                    $pairedObj[$i][self::SECOND_ARGUMENT] = $obj[$key + 1];

                }
                // @TODO if object was in given state before status log we could assume that it was in this state before startDate:
                // e.g we have object in running state and date is min date in array of object and
                //could be uncommented if it is a case: *Not affecting test cases
                /*
               else {
                  if (null != $this->startDate && $this->startDate < $value[self::KEY_NEW_STATE]) {
                       $pairedObj[$i][self::SECOND_ARGUMENT][self::KEY_DATE] = $this->startDate;
                    }
               }
                */

                $i++;
            } else {

                //this is to check if it is first iteration and no previous key exists
                if (isset($obj[$key - 1])) {
                    //this is to protect duplicates insertion due to loop nature:
                    if (isset($pairedObj[$i - 1][self::FIRST_ARGUMENT]) && $pairedObj[$i - 1][self::FIRST_ARGUMENT] != $obj[$key - 1]) {
                        $pairedObj[$i][self::FIRST_ARGUMENT] = $obj[$key - 1];
                        $pairedObj[$i][self::SECOND_ARGUMENT] = $obj[$key];

                    }


                } else {
                    //this part is changes or creating date for some cases where object was in given state on the moment of stop date
                    if (null != $this->stopDate && $this->stopDate >= $value[self::KEY_DATE]) {

                        $latestStopDate = $this->stopDate;

                    }

                    $pairedObj[$i][self::FIRST_ARGUMENT][self::KEY_DATE] = $latestStopDate;
                    $pairedObj[$i][self::FIRST_ARGUMENT][self::KEY_OLD_STATE] = $this->state;
                    $pairedObj[$i][self::FIRST_ARGUMENT][self::KEY_NEW_STATE] = self::OTHER_THAN_PREFIX . $this->state;


                    //this part is changes or creating date for some cases where object was not in given state on the moment of start date

                    if (null != $this->startDate && $this->startDate > $value[self::KEY_DATE]) {

                        $latestStartDate = $this->startDate;
                        $pairedObj[$i][self::SECOND_ARGUMENT][self::KEY_DATE] = $latestStartDate;
                        $pairedObj[$i][self::SECOND_ARGUMENT][self::KEY_NEW_STATE] = $this->state;
                        $pairedObj[$i][self::SECOND_ARGUMENT][self::KEY_OLD_STATE] = self::OTHER_THAN_PREFIX . $this->state;

                    } else {

                        $pairedObj[$i][self::SECOND_ARGUMENT] = $obj[$key];

                    }


                }


                $i++;

            }


        }


        return $pairedObj;

    }

    /**
     * This method checks if pair of arguments valid for subtraction
     * @param array $pairOfArguments
     * @return bool
     */
    private function checkConditionsBeforeDoMath($pairOfArguments = [])
    {


        if (!isset($pairOfArguments[self::FIRST_ARGUMENT])
            || !isset($pairOfArguments[self::SECOND_ARGUMENT])
            || (count($pairOfArguments) != 2)
            || ($pairOfArguments[self::FIRST_ARGUMENT][self::KEY_NEW_STATE] === $this->state)
            || ($pairOfArguments[self::SECOND_ARGUMENT][self::KEY_NEW_STATE] !== $this->state)
            || (null != $this->stopDate && $pairOfArguments[self::FIRST_ARGUMENT][self::KEY_DATE] > $this->stopDate)
            || (null != $this->startDate && $pairOfArguments[self::SECOND_ARGUMENT][self::KEY_DATE] < $this->startDate)

        ) {

            return false;
        }


        return true;


    }

    /**
     * This method do sum of all subtractions in prepared paired object by first-second argument
     * @param array $pairedObj
     * @return int
     */
    private function doMath($pairedObj = [])
    {

        $sub = 0;

        if (count($pairedObj) <= $sub) {

            return $sub;
        }

        foreach ($pairedObj as $key => $value) {

            if ($this->checkConditionsBeforeDoMath($value)) {

                $sub += ($value[self::FIRST_ARGUMENT][self::KEY_DATE] - $value[self::SECOND_ARGUMENT][self::KEY_DATE]);

            }

        }

        return $sub;


    }


    /**
     * this method used to keep state log object consistent if one or more  properties are missing
     * @param array $value
     * @return array
     *
     */
    private function keepObjectConsistent($value = [])
    {

        if (!isset($value[self::KEY_NEW_STATE])) {
            $value[self::KEY_NEW_STATE] = null;
        }

        if (!isset($value[self::KEY_OLD_STATE])) {
            $value[self::KEY_OLD_STATE] = null;
        }

        if (!isset($value[self::KEY_DATE])) {
            $value[self::KEY_DATE] = null;
        }

        return $value;
    }

}