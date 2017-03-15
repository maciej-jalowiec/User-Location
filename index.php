<?php

// class that holds information about the user
class User {

    // variables containing IP address, country name and country initials
    public $ip;
    public $country;
    public $initials;

    // class constructor, using all the variables from above
    public function __construct (
    	$ip,
    	$country,
    	$initials) {
    		$this->ip = $ip;
    		$this->country = $country;
    		$this->initials = $initials;
    	}

    // method for obtaining IP address, country and initials
    // please note - this solution may be vulnerable to spoofing 
    public function getInformation() {

        // look for user's IP by checking contents of headers
    	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {

    		// check if a header exists in $_SERVER superglobal
	        if (array_key_exists($key, $_SERVER)) {

	        	// check if a header is a comma-separated list of IP addresses
				foreach (explode(',', $_SERVER[$key]) as $ip) {

					// filter values to validate if they are correct IP addresses and assign to class instance
					if (filter_var($ip, FILTER_VALIDATE_IP)) {
	                  $this->ip = $ip;
					}
				}
			}
		}

        // use external REST service to obtain country and initials
        $json = json_decode(file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn='. $this->ip), true); 

        // assign country and initials to class instance
        $this->country = $json[geobytescountry];
        $this->initials = $json[geobytesinternet];
        }
}

// instantiate User class and get IP, country, initials
$user = new User($ip, $country, $initials);
$user->getInformation();

// sanity check
echo '<p>'.$user->ip.'</p>';
echo '<p>'.$user->country.'</p>';
echo '<p>'.$user->initials.'</p>';

?>