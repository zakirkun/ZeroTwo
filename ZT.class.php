<?php
/**
* @author Muhammad Zakir Ramadhan
* https://zakirpro.com (zakir@bekasidev.org)
* Don't Change My Name!
*/
class ZT
{
	private $endpoint = "https://api.twilio.com/2010-04-01/";

	public function __construct()
	{
		echo "
	 _____                 ______             
	/__  /  ___  _________/_  __/      ______ 
	  / /  / _ \/ ___/ __ \/ / | | /| / / __ \
	 / /__/  __/ /  / /_/ / /  | |/ |/ / /_/ /
	/____/\___/_/   \____/_/   |__/|__/\____/ 
                                      
	    --- \033[1;33m Twilio Sender\033[0m ---
	    -- \033[1;31m Coded By ZakirDotID\033[0m --\n\n";		
	}

	public function parseCommand($argv)
	{
		if (isset($argv[1])) {
			$action = trim($argv[1]);
			$this->registerAction($action);
		} else {
			$this->_cli_interface();
		}
	}

	public function _cli_interface()
	{

	}

	private function registerAction($action)
	{
		switch ($action) {
			case '--help':
				$this->_show__help();
				break;
			case '--cli-mode':
				$this->_cli_interface();
				break;
			case '--valid':
				$this->validate_sid();
				break;	
			case '--balance':
				$this->balance_check();
				break;	
			default:
				$this->_show__help();
				break;
		}
	}

	public function validate_sid()
	{
		$this->console("Running validate Twillio\n", 1);
		$list 	= readline('Input list? ');

		if (!is_file($list)) {
			$this->console("List not Found~\n", 3);
			exit;
		}

		$pecah  = explode("\r\n", file_get_contents($list));
		foreach ($pecah as $key) {
			$k = explode("|", $key);

			if ($this->_validate__($k[0], $k[1])) {
				$this->console("{$k[0]} => LIVE\n", 1);
			} else {
				$this->console("{$k[0]} => DIE\n", 3);
			}	
		}
	}

	public function balance_check()
	{
		$this->console("Running Check Balance Twillio\n", 1);
		$list 	= readline('Input list? ');

		if (!is_file($list)) {
			$this->console("List not Found~\n", 3);
			exit;
		}

		$pecah  = explode("\r\n", file_get_contents($list));
		foreach ($pecah as $key) {
			$k 		= explode("|", $key);
			$data	= json_decode($this->_balance_($k[0], $k[1]));
			
			if ($data->ok == 1) {
				$this->console("{$k[0]} ({$data->curency}) -> {$data->balance}\n", 1);
			} else {
				$this->console("{$k[0]} => DIE\n", 3);
			}
		}
	}

	private function _validate__($sid, $token)
	{
		$params = "Accounts/";
		$basic 	= base64_encode("{$sid}:{$token}");

		$result = $this->__handler_curl($params, 'GET', $basic);

		if (preg_match("/<Status>active<\/Status>/i", $result)) {
			return true;
		} else {
			return false;
		}
	}

	private function _balance_($sid, $token)
	{
		$params		= "Accounts/{$sid}/Balance";
		$basic 		= base64_encode("{$sid}:{$token}");
		$result 	= $this->__handler_curl($params, 'GET', $basic);

		if (preg_match("/<Currency>(.*)<\/Currency>/i", $result)) {
			$Currency	= ambil_kata('<Currency>','</Currency>', $result);
			$Balance 	= ambil_kata('</Currency><Balance>','</Balance><AccountSid>',$result);

			return json_encode(array('ok'=>1,'curency' => $Currency, 'balance' => $Balance));
		} else {
			return false;
		}
	}

	private function __handler_curl($params, $method = 'GET', $basic, $postdata = null)
	{
		$curl 	= curl_init();
		$url 	= $this->endpoint.$params; 

		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $url,
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 0,
		  	CURLOPT_FOLLOWLOCATION => true,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_CUSTOMREQUEST => $method,
		  	CURLOPT_POSTFIELDS => $postdata,
		  	CURLOPT_HTTPHEADER => array(
		    	"Authorization: Basic {$basic}",
		    	"Content-Type: application/x-www-form-urlencoded"
		  	),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	public function _show__help()
	{
		$this->console("Showing All Command\n", 2);
		echo "{
  --help \t: Show Command\n
  --balance \t: Count Balance Twilio, \n
  --valid \t: Validate Twillio Sid, \n
  --send  \t: Sending Message by random twilio sid (Premium Only),\n
  --cli-mode \t: Cli Interface Mode
}\n";
	}

	private function console($message, $level = 9)
	{
		if ($message == null) {
			return $message;
		}

		switch ($level) {
			case '1':
				$m = $this->___($message, 'green');
				break;
			case '2':
				$m = $this->___($message, 'yellow');
				break;
			case '3':
				$m = $this->___($message, 'red');
				break;	
			default:
				$m = $this->___($message, 'white');
				break;
		}

		echo $m;

	}

	public function ___($text,$color,$mode=true){
		switch ($color) {
        case 'green':
            $warna = "\033[1;32m"; break;
        case 'greenbg':
            $warna = "\033[42m"; break;            
        case 'blue':
            $warna = "\033[1;34m"; break;            
        case 'purple':
            $warna = "\033[1;35m"; break;            
        case 'cyan':
            $warna = "\033[1;36m"; break;            
        case 'red':
            $warna = "\033[1;31m"; break;            
        case 'redbg':
            $warna = "\033[41m"; break;            
        case 'yellow':
            $warna = "\033[1;33m"; break;             
        case 'yellowbg':
            $warna = "\e[43m"; break;             
        default:
            $warna = "\033[0m";break;  
    	}
    	if ($mode == true) {
        	return "[".date("h:i:s")."] ".$warna.$text."\033[0m\n";
	    }else{
    	    return $text;
    	}
	}
}