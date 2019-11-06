<?php
/**
* @version svn:$Id$
*/

/**
* @see pregled
*/
require 'pregled.php';

/**
* Fetch the "Pregled na pechata" from "Investor.bg"
*/
Class pregled_investor_bg Extends pregled {

	/**
	* @var string name of the folder where to store the fetched and parsed HTML
	*/
	protected $archive = 'investor_bg';

	/**
	* @var string starting URL for locating the page that we are looking for
	*/
	protected $url = 'http://www.investor.bg/news/news/last/0.html';

	/**
	* Locate and extract the HTML that we need
	* @param string $html starting page HTML
	* @return string|FALSE
	*/
	protected function page_found($html) {
	
		if (!preg_match('~<a href="(http://www.investor.bg/ikonomika-i-politika/[^"]+/pregled-na-pechata-za-[^"]+)">Преглед на печата за [^<]*</a>~Uis', $html, $R)) {
			return false;
			}

		$html = $this->url($R[1]);
		
		$html = preg_replace('~^.+<div id="c1" class="article">~Uis', '', $html);
		$html = preg_replace('~<div class="a2a_kit" id="bookmarks">.+$~Uis', '', $html);
		
		return $html;
		}

	////--end-of-class----s
	}

/**
* Run the script
*/
new pregled_investor_bg;
