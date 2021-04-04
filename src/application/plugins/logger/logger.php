<?php
declare(strict_types=1);

class Logger
{
    protected string $_file;
    protected array $_entries;
    protected int $_start;
    protected int $_end;

    protected function _sum(array $values): int
    {
        $count = 0;

        foreach ($values as $value)
        {
            $count += $value;
        }

        return $count;
    }

    protected function _average($values): int
    {
        return $this->_sum($values) / sizeof($values);
    }

    public function __construct(array $options)
    {
        if (!isset($options["file"]))
        {
            throw new Exception("Log file invalid.");
        }

        $this->_file = $options["file"];
        $this->_entries = array();
        $this->_start = microtime();
    }

    public function log(string $message): void
    {
        $this->_entries[] = array(
            "message" => "[" . date("Y-m-d H:i:s") . "]" . $message,
            "time" => microtime()
        );
    }

    public function __destruct()
    {
        $messages = "";
        $last = $this->_start;
        $times = array();

        foreach ($this->_entries as $entry)
        {
            $messages .= $entry["message"] . "\n";
            $times[] = $entry["time"] - $last;
            $last = $entry["time"];
        }

        $messages .= "Average: " . $this->_average($times);
        $messages .= ", Longest: " . max($times);
        $messages .= ", Shortest: " . min($times);
        $messages .= ", Total: " . (microtime() - $this->_start);
        $messages .= "\n";

        file_put_contents($this->_file, $messages, FILE_APPEND);
    }
}
