<?php
/**
* @version svn:$Id$
*/

/**
* @see pregled
*/
require 'pregled.php';

/**
* Fetch the "Pregled na pechata" from "Kapital Daily"
*/
Class pregled_kapital_daily Extends pregled {

	/**
	* @var string name of the folder where to store the fetched and parsed HTML
	*/
	protected $archive = 'kapital_daily';

	/**
	* @var string starting URL for locating the page that we are looking for
	*/
	protected $url = 'http://www.capital.bg/all/';

	/**
	* Locate and extract the HTML that we need
	* @param string $html starting page HTML
	* @return string|FALSE
	*/
	protected function page_found($html) {
		if (!preg_match('~<a title="Сутрешен блок: [^"]+" href="(/politika_i_ikonomika/sedmicata/sutreshenblok/[^"]+)">Сутрешен блок: [^<]+</a>~Uis', $html, $R)) {
echo "\r\n", date('r'), ' not-found!';
			return false;
			}

		$html = $this->url('http://www.capital.bg' . $R[1]);
		
		$html = preg_replace('~^.+<table class="news_tblrightsmall">.+</table>~Uis', '', $html);
		$html = preg_replace('~<ul class="showPaging">.+$~Uis', '', $html);
		
		return $html;
		}

	////--end-of-class----s
	}

/**
* Run the script
*/
new pregled_kapital_daily;
