<?php

	/**
	 * Connect to DotMailer via the REST API
	 * @version 0.0.1
	 * @link    https://developer.dotdigital.com/docs/rest-api-quick-reference
	 */
	class DotMailer
	{
		private $username;
		private $password;

		private $endpoint = "https://api.dotmailer.com";
		private $version  = "v2";

		/**
		 * Create a new connection to DotMailer via REST
		 * @param string  $username  API username
		 * @param string  $password  API password
		 */
		public function __construct($username = "", $password = "")
		{
			$this->username = $username;
			$this->password = $password;
		}

		/**
		 * Perform a raw cURL request
		 * @param  string  $url   Endpoint URL
		 * @param  string  $type  Type of request header to send
		 * @param  array   $data  POST/PUT data to send
		 * @return string         Raw response in plaintext
		 */
		private function curl($url = "", $type = "get", $data = array())
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json", "Content-Type: application/json"));
			curl_setopt($ch, CURLAUTH_BASIC, CURLAUTH_DIGEST);
			curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);

			switch(strtolower(trim($type)))
			{
				case "delete":
				{
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				}

				break;

				case "post":
				{
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				}

				break;

				case "put":
				{
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				}

				break;
			}

			$response = trim(curl_exec($ch));

			curl_close($ch);

			return $response;
		}

		/**
		 * Send a DELETE request
		 * @param  string        $action  API action
		 * @return array|object           JSON response
		 */
		public function delete($action = "account-info")
		{
			$response = $this->curl($this->endpoint . "/{$this->version}/" . ltrim($action, "/"), "DELETE");

			return json_decode($response);
		}

		/**
		 * Send a GET request
		 * @param  string        $action  API action
		 * @return array|object           JSON response
		 */
		public function get($action = "account-info")
		{
			$response = $this->curl($this->endpoint . "/{$this->version}/" . ltrim($action, "/"));

			return json_decode($response);
		}

		/**
		 * Send a POST request
		 * @param  string        $action  API action
		 * @param  array         $data    POST data to send
		 * @return array|object           JSON response
		 */
		public function post($action = "account-info", $data = array())
		{
			$response = $this->curl($this->endpoint . "/{$this->version}/" . ltrim($action, "/"), "POST", $data);

			return json_decode($response);
		}

		/**
		 * Send a PUT request
		 * @param  string        $action  API action
		 * @param  array         $data    PUT data to send
		 * @return array|object           JSON response
		 */
		public function put($action = "account-info", $data = array())
		{
			$response = $this->curl($this->endpoint . "/{$this->version}/" . ltrim($action, "/"), "PUT", $data);

			return json_decode($response);
		}
	}
