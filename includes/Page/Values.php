<?php

namespace Catalyst\Page;

class Values {
	// [keyword (for navbar), title]
	const HOME = ["home", "Home"];
	const ABOUT_US = ["about", "About Us"];
	const API_DOCS = ["about", "API Docs"];
	const FAQ = ["about", "FAQ"];
	const FEATURE_BOARD = ["about", "Feature Board"];
	const FEATURE = ["about", "{name} | Feature"];
	const LOGIN = ["login", "Login"];
	const TOTP_LOGIN = ["login", "2FA Login"];
	const LOGOUT = ["logout", "Logout"];
	const REGISTER = ["register", "Register"];
	const EMAIL_VERIFICATION = ["user", "Email Verification"];
	const DASHBOARD = ["user", "Dashboard | {name}"];
	const SETTINGS = ["user", "Settings | {name}"];
	const USER_PROFILE = ["null", "{name}"];
	const NEW_CHARACTER = ["user", "New Character"];
	const CHARACTERS = ["user", "My Characters"];
	const EDIT_CHARACTER = ["user", "Edit {name}"];
	const VIEW_CHARACTER = ["user", "{name}"];
	const MARKDOWN = ["about", "Markdown Help"];
	const EMOJI = ["about", "Emoji"];
	const VIEW_ARTIST = ["browse", "{name}"];
	const NEW_ARTIST_PAGE = ["artist", "New Artist"];
	const EDIT_ARTIST_PAGE = ["artist", "Edit Artist"];
	const EDIT_ARTIST_PAGE_COMMISSION_TYPES = ["artist", "Commission Types"];
	const NEW_COMMISSION_TYPE = ["artist", "New Commission Type"];
	const EDIT_COMMISSION_TYPE = ["artist", "Edit Commission Type"];
	const EDIT_COMMISSION_TYPE_IMAGES = ["artist", "Edit Commission Type Images"];
	const NEW_COMMISSION = ["browse", "New {type} Commission | {artist}"];

	const DEFAULT_COLOR = "1b5e20";

	const BCRYPT_COST = 14;

	const ROOT_TITLE = "Catalyst";

	const HEAD_INC = __DIR__."/Header/header.inc.php";
	const FOOTER_INC = __DIR__."/Footer/footer.inc.php";

	const DEBUG_CAPTCHA_KEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
	const DEBUG_CAPTCHA_SECRET = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

	public static function createTitle(string $title, array $values=[]) : string {
		return preg_replace_callback("/{([^}]+)}/", function($in) use ($values) : string {
			return $values[$in[1]];
		}, $title);
	}

	public static function createTOC(array $items) : string {
		$str = '<div class="pushpin toc"><ul class="browser-default">';
		foreach ($items as list($id, $name)) {
			$str .= '<li><p class="no-margin"><a href="#'.htmlspecialchars($id).'">'.htmlspecialchars($name).'</a></p></li>';
		}
		$str .= '</ul></div>';
		return $str;
	}

	public static function createInlineTOC(array $items) : string {
		$str = '<h4>Table of Contents</h4><ul class="browser-default">';
		foreach ($items as list($id, $name)) {
			$str .= '<li><p class="flow-text no-margin"><a href="#'.htmlspecialchars($id).'">'.htmlspecialchars($name).'</a></p></li>';
		}
		$str .= '</ul>';
		return $str;
	}
}
