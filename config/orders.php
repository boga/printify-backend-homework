<?php

return [
    /* N of orders allowed to be ordered from a single country */
    'country_limit' => env('COUNTRY_LIMIT', 10),
    /* Timeframe for a previous setting. Use MYSQL interval syntax: https://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html */
    'country_limit_timeframe' => env('COUNTRY_LIMIT_TIMEFRAME', "1 week"),
];
