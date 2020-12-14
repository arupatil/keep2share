<?php

class Config {
	/**
	* Setting error params
	**/
	const ERROR_INCORRECT_PARAMS = 1001;
    const ERROR_INCORRECT_PARAM_VALUE = 1002;
    const ERROR_INVALID_REQUEST = 1003;
    /**
	* Setting Authentication error params
	**/
    const ERROR_YOU_ARE_NEED_AUTHORIZED = 1101;
    const ERROR_AUTHORIZATION_EXPIRED = 1102;
    /**
	* Setting file error params
	**/
    const ERROR_FILE_NOT_FOUND = 1201;
    const ERROR_FILE_IS_NOT_AVAILABLE = 1202;
    const ERROR_FILE_IS_BLOCKED = 1203;
    const ERROR_DOWNLOAD_FOLDER_NOT_SUPPORTED = 1204;

    protected static $_allowAuth = true;
    public static $baseUrl = 'https://k2s.cc/api/v2/';
    public $MGLOBAL_LOGSDIR = __DIR__ ."/log";
}