# Installation

1 To install project clone it from github.com

* run "git clone https://github.com/ITinarray/state-time.git" to install project
* run "cd running-time"
* run "composer update" to install phpunit
* run "./vendor/bin/phpunit --bootstrap src/autoload.php tests/StateTimeTest" to test code

# File description

1 Code is in "src/StateTime.php"

2 Test id in "tests/StateTimeTest.php"

3 Constant defined in "src/Settings.php" 

# Task


Write some code that will solve the following:

We want to add up the total time a given object was in a RUNNING state; lets ignore what the object is, or what the states mean, irrelevant to this challenge.   The changes to the objects state are recorded in an array, that would be structured as follows:

```php
array(
   array(
      'oldState' => STATE,
      'newState' => STATE,
      'date' => UNIX_TIMESTAMP
   ),
   array(
      'oldState' => STATE,
      'newState' => STATE,
      'date' => UNIX_TIMESTAMP
   )
);

```

There are three possible states.  PAUSED, RUNNING, and COMPLETE.  The array records the unix timestamp (seconds since epoch) of when the object went into the 'newState', leaving 'oldState'.  So we want a total sum of the time it spent in a RUNNING state.   There may be several state changes, or thousands.  They are not necessarily in order.  An object may have never entered into a RUNNING state.  A COMPLETE state can be treated just as PAUSED, i.e. the object is NOT running.  No guarantees that once an object enters into a COMPLETE state, that it won't then go back into RUNNING!  It's the wild west!

To further complicate things, the object may have hard defined 'start' and 'stop' dates (also in seconds since epoch).  We may have state changes outside of these dates, but want to make sure we only include run times within these ranges (if given).  The start/stop dates are optional.  An object might have a hard start, but no stop, or a hard stop, but no start.  Or none.  Or both.  A null will be passed in to indicate the absence of a date.

The method should take the array of states, an optional start, and an optional stop as parameters, returning the number of seconds within RUNNING state.  Use constants for the three states so they can easily be changed to match our tests.

Please solve using a function, (or collection) of functions, written in PHP.

Bonus challenge:  Make your function easily adapt to running the same calculation for the PAUSED or COMPLETE states.

======================================

###The following is a collection of data, and expected responses from the function, that I will be testing against. 

-----------

```php
$statusLog = array(
	array(
		'date' => date("U", strtotime("2015-10-15")),
		'oldState' => null,
		'newState' => Settings::CAMPAIGN_STATUS_PAUSED
	)
);



$startDate = null;
$stopDate = null;
$Answer = 0;
```

---------
```php
$statusLog = array(
	array(
		'date' => date("U", strtotime("2015-10-15")),
		'oldState' => null,
		'newState' => Settings::CAMPAIGN_STATUS_PAUSED
	)
);

$startDate = date("U", strtotime("next week"));
$stopDate = null;
$Answer = 0;

```
--------------

```php
$statusLog = array(
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
);

$startDate = null;
$stopDate = null;
$Answer = time() - date("U", strtotime("2015-10-16"));

```
-------------

```php
$statusLog = array(
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
);

$startDate = null;
$stopDate = null;
$Answer = time() - date("U", strtotime("2015-10-18")) + (24 * 60 * 60);

```
--------------

```php
$statusLog = array(
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
);

$startDate = null;
$stopDate = null;
$Answer = time() - date("U", strtotime("2015-10-19")) + (24 * 60 * 60 * 1.5);

```
-------------

```php
$statusLog = array(
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
);

$startDate = null;
$stopDate = null;
$Answer = (24 * 60 * 60 * 2.5);

```
----------

```php
$statusLog = array(
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
);

$startDate = date("U", strtotime("2015-10-15"));
$stopDate = null
$Answer = time() - date("U", strtotime("2015-10-15"));

```
-----------

```php
$statusLog = array(
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
);

$startDate = date("U", strtotime("2015-10-15"));
$stopDate = null;
$Answer = time() - date("U", strtotime("2015-10-16"));

```
----------

```php
$statusLog = array(
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
);

$startDate = date("U", strtotime("2015-10-17"));
$stopDate = null;
$Answer = (time() - date("U", strtotime("2015-10-19"))) + (12 * 60 * 60);

```
-----------

```php
$statusLog = array(
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
);

$startDate = null;
$stopDate = date("U", strtotime("2015-10-15"));
$Answer = 24 * 60 * 60;

```
--------------

```php
$statusLog = array(
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
);

$startDate = null;
$stopDate = date("U", strtotime("2015-10-18"));
$Answer = 24 * 60 * 60;

```
--------------

```php
$statusLog = array(
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
);

$startDate = null;
$stopDate = null;
$Answer = time() - date("U", strtotime("2015-10-16"));

```
-------------------

```php
$statusLog = array(
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
);

$startDate = null;
$stopDate = null;
$Answer = 0;

```
-------------
