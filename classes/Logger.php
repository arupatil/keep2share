<?php
class Logger {
	private $filename;
	private $mode;
	private $tsFormat;
	private $linebreak;
	private $loglevel;
	private $contents;
	private $customDatacontents;
	private $sNoLog;
	const EMERG = 0; // Emergency: system is unusable
	const ALERT = 1; // Alert: action must be taken immediately
	const CRIT = 2; // Critical: critical conditions
	const ERR = 3; // Error: error conditions
	const WARN = 4; // Warning: warning conditions
	const NOTICE = 5; // Notice: normal but significant condition
	const INFO = 6; // Informational: informational messages
	const DEBUG = 7; // Debug: debug messages
	function __construct($fileprefix = "LOG", $deflevel = self::INFO, $tsFormat = "H:i:s", $linebreak = "\r\n", $mode = "a+") {
		$this->filename = $fileprefix . "_" . date ( "dmY" ) . ".log";
		$this->mode = $mode;
		$this->tsFormat = $tsFormat;
		$this->linebreak = $linebreak;
		$this->contents = "";
		$this->customDatacontents = "";
		$this->sNoLog = "";
		$this->loglevel = $deflevel;
		$this->write ( "*********Logging Started" );
	}
	function __destruct() {
	}
	function debug($line)
	{
		$this->write("DEBUG::" . $line, self::DEBUG);
	}
	function error($line)
	{
		$this->write("ERROR::" . $line, self::ERR);
	}
	function info($line)
	{
		$this->write("INFO::" . $line, self::INFO);
	}
	function write($line, $level = self::INFO, $customData = false) {
		if ($level <= $this->loglevel) {
			if ($customData)
				$this->customDatacontents .= "{$line} {$this->linebreak}";
			else
				$this->contents .= date ( $this->tsFormat ) . substr ( ( string ) microtime (), 1, 8 ) . ":: {$line} {$this->linebreak}";
		}
		$this->sNoLog .= date ( $this->tsFormat ) . substr ( ( string ) microtime (), 1, 8 ) . ":: {$line} {$this->linebreak}";
	}
	function flush($customContent = false) {
		if (($this->contents != "") || ($this->customDatacontents != '')) {
			if ($customContent) {
				$handle = fopen ( "/var/www/html/ss.admofi.com/logs/CustomData" . "_" . date ( "dmY" ) . ".log", $this->mode );
				fwrite ( $handle, $this->customDatacontents );
				fclose ( $handle );
				$this->customDatacontents = "";
			} else {
				$handle = fopen ( $this->filename, $this->mode );
				fwrite ( $handle, $this->contents );
				fclose ( $handle );
				$this->contents = "";
			}
		}
	}
	function flushNoLog() {
		if ($this->sNoLog != "") {
			$handle = fopen ( $this->filename, $this->mode );
			fwrite ( $handle, $this->sNoLog );
			fclose ( $handle );
			$this->sNoLog = "";
		}
	}
}
?>
