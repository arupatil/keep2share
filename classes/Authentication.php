<?php

class Authentication extends Config{
	public $username;
    public $password;
    public $access_token;
    protected $_ch;
    protected $_auth_token;
  	public $verbose = false;
  	public $logger;
  	/**
     * Keep2ShareAPI constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($log,$username = '', $password = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->logger = $log;
        $this->_ch = curl_init();
        curl_setopt($this->_ch, CURLOPT_POST, true);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, 2);

       // $this->_auth_token = $this->getAuthToken();
        $this->base_url = Config::$baseUrl;
    }

    public function login()
    {
        curl_setopt($this->_ch, CURLOPT_URL, $this->base_url . '/login');

        $params = [
            'username' => $this->username,
            'password' => $this->password,
        ];

        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, json_encode($params));
        $response = curl_exec($this->_ch);

        if ($this->verbose) {
            echo '>> ' . json_encode($params), PHP_EOL;
            echo '<< ' . $response, PHP_EOL;
            echo '-------------------------' . PHP_EOL;
        }
        $data = json_decode($response, true);

        if (!$data || !isset($data['status'])) {
        	echo "failed";
            $this->logger->write('Authentication failed');
            $this->logger->flush();
            return false;
        }

        if ($data['status'] == 'success') {
            //$this->setAuthToken($data['auth_token']);
            //$this->_auth_token = $data['auth_token'];
            return true;
        } else {
            $this->logger->write('Authentication failed: ' . $data['message'], 'warning');
            $this->logger->flush();
            return $data['errorCode'];
        }
    }
}