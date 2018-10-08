<?php
/**
* @class Adsense
*
* This class is used to render adsense blocks.
*
* @author Leano Martinet <info@asterion-cms.com>
* @package Asterion
* @version 3.0.1
*/
class Adsense {

	static public function top() {
		if (DEBUG) return '<div class="adsense adsenseTop adsenseTest">ad</div>';
		return '<div class="adsense adsenseTop">'.Params::param('adsense-top').'</div>';
	}

	static public function inline() {
		if (DEBUG) return '<div class="adsense adsenseInline adsenseTest">ad</div>';
		return '<div class="adsense adsenseInline">'.Params::param('adsense-inline').'</div>';
	}

	static public function links() {
		if (DEBUG) return '<div class="adsenselinks adsenseTest">adlinks</div>';
		return '<div class="adsenselinks">'.Params::param('adsense-links').'</div>';
	}

	static public function linksAll() {
		return '<div class="adsenselinksWrapper">
					'.Adsense::links().'
					'.Adsense::links().'
					'.Adsense::links().'
				</div>';
	}

	static public function page() {
		return '<script>(adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-7429223453905389", enable_page_level_ads: true });</script>';
	}

	static public function code() {
		return '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
	}

	static public function ampTop() {
		return '<div class="adsense">
					<amp-ad layout="fixed-height"
	   					height=100
						type="adsense"
						data-ad-client="ca-pub-7429223453905389"
						data-ad-slot="3066154144"
						data-auto-format="rspv">
							<div overflow></div>
					</amp-ad>
				</div>';
	}

	static public function amp() {
		return '<div class="adsense">
					<amp-ad layout="fixed-height"
						height=320
						type="adsense"
						data-ad-client="ca-pub-7429223453905389"
						data-ad-slot="3066154144"
						data-auto-format="rspv">
						<div overflow></div>
					</amp-ad>
				</div>';
	}

}
?>