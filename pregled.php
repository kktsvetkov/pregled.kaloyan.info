<?php
/**
* @version svn: $Id$
*/

date_default_timezone_set('Europe/Sofia');

/**
* The basic abstract class storing all the common functionality
*/
Abstract Class pregled {

	/**
	* @var string name of the folder where to store the fetched and parsed HTML
	*/
	protected $archive = 'archive';

	/**
	* @var string starting URL for looking into what to download
	*/
	protected $url;

	/**
	* @var string email address where to send
	*/
	protected $to = 'каlоуап@gмаil.сом';

	/**
	* Constructor: checks the settings, then starts the whole process
	*/
	Public Function __construct() {

		$this->archive = dirname(__FILE__) . '/' . $this->archive;

		if (!file_exists($this->archive)) {
			mkdir($this->archive, 0777, true);
			}
		if (!is_readable($this->archive)) {
			throw new Exception("The archive folder \"{$this->archive}\" is not readable");
			}

		if (!is_dir($this->archive)) {
			throw new Exception("The archive folder \"{$this->archive}\" is not a directory");
			}

		if (!$this->url || !filter_var($this->url, FILTER_VALIDATE_URL)) {
			throw new Exception("Empty or invalid URL \"{$this->url}\"!");
			}

		$archive = $this->archive
			. DIRECTORY_SEPARATOR . date('Y-m')
			. DIRECTORY_SEPARATOR . date('d')
			. '.html';
		if (file_exists($archive)) {
			return false;
			}

		$html = $this->url($this->url);
		if ($pregled = $this->page_found($html)) {
			$this->page_send($pregled, $archive);
			}

		}

	/**
	* Returns the HTML that needs to be send via email; this
	* method needs to be overridden for each of the ancestors
	* @param string $html the HTML of the starting page; note
	*	that this is not the HTML that is supposed to be sent,
	*	but the one that needs to be analyzed to find the page
	*	you are looking for, download it, and out of its HTML
	*	to extract what this method need to return
	* @return string|FALSE
	*/
	Protected Function page_found($html) {
		return false;
		}

	/**
	* Sends the extracted HTML and records it in the archive folder
	* @param string $html extracted HTML
	* @param string $archive name of the archive filename where to put it
	*/
	Protected Function page_send($html, $archive) {

		if (empty($this->to)) {
			throw new Exception("No recipient set for the emails.");
			}

		$u = parse_url($this->url);
		$u['host'] = str_replace('www.', '', $u['host']);
		$m = mail($this->to, 'Pregled na pechata: ' . date('Y/m/d') . " {$u[host]}",
			'<div style="background:khaki; padding: 1em;">' . $html . '</div>',
			"From: \"Преглед: {$u['host']}\" <pregled@kaloyan.info>;\r\nContent-type: text/html;charset=utf-8\r\n");

		if ($m) {
			$folder = dirname($archive);
			if (!file_exists($folder)) {
				mkdir($folder, 0777, true);
				}
			file_put_contents($archive, $html);
			}
		}

	/**
	* Shortcut method for fetching an URL
	* @param string $url
	* @return string the fetched HTML
	*/
	Protected Function url($url) {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);

		curl_setopt($ch, CURLOPT_URL, $url);

		$u = parse_url($url);
		$cookie_txt = '/tmp/' . strToUpper($u['host']) . '.txt';
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_txt);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_txt);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Pregled na pechata <pregled@kaloyan.info>');

		$response = curl_exec($ch);

		if (false === $response) {
			throw new Exception(curl_error($ch) . ' (' . curl_errno($ch) . ')');
			}

		if (200 != ($code = curl_getinfo($ch, CURLINFO_HTTP_CODE))) {
			throw new Exception("Response code is not 200, but {$code}; "
				. curl_getinfo($ch, CURLINFO_HEADER_OUT));
			}

		curl_close($ch);

		return $response;
		}

	////--end-of-class----s
	}
