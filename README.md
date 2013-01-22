logtailminutes
==============

Dead simple, tiny utility to extract lines from log files based on time range. Usage:

    $tail = new LogTailMinutes(
        '/path/to/logfile',  // log file to extract files from
        'd/m/Y',            // date format used in the log file, see php.net/date
        10,                 // how many minutes to go back
        'now',              // time expression to start from, see php.net/strtotime
        '/path/to/egrep'    // path to egrep binary
    );

    // optionally, set a filter on the matching results (egrep compatible regex)
    $tail->setFilterRegex('HTTP/1.1" (4|5)[0-9]{2}');

    // get matched lines
    $lines = $tail->getLines();


