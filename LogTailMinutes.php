<?php
class LogTailMinutes {
    private $logfile;
    private $date_format;
    private $minutes_back;
    private $egrep_location;
    private $start_time;
    private $filter_regex;

    public function __construct(
            $logfile, $date_format, $minutes_back, 
            $start_time = 'now', $egrep_location = '/bin/egrep'
    ) {
        $this->logfile = $logfile;
        $this->date_format = $date_format;
        $this->minutes_back = $minutes_back;
        $this->start_time = $start_time;
        $this->egrep_location = $egrep_location;
    }

    public function buildEgrepRegex() {
        $intervals = array();
        $start = strtotime($this->start_time);            

        for($i = 0; $i <= $this->minutes_back; $i++) {
            $intervals[] = date(
                $this->date_format, 
                strtotime("$i minutes ago", $start)
            );
        }

        return "'".join('|', $intervals)."'";
    }

    public function setFilterRegex($filter_regex) {
        $this->filter_regex = $filter_regex;
    }

    public function buildEgrepCmd() {
        $egrep_args = '-h';
        $regex = $this->buildEgrepRegex();

        $cmd = join(' ', array(
                $this->egrep_location, 
                $egrep_args, 
                $regex, 
                $this->logfile
            )
        );

        if(isset($this->filter_regex)) {
            $cmd .= ' | ' . join(' ', array(
                    $this->egrep_location, 
                    $egrep_args, 
                    "'".$this->filter_regex."'"
                )
            );
        }

        return $cmd;
    }

    public function getLines() {
        $cmd = $this->buildEgrepCmd();

        $lines = explode("\n", shell_exec($cmd));
        array_pop($lines); // remove trailing empty line

        return $lines;
    }
}
