<?php

define("ROOTDIR", "../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Character\Character;
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Message\Message;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

$userExists = false;

if (isset($_GET["q"])) {
	$id = User::getIdFromUsername($_GET["q"]); 
	if ($id !== -1) {
		$user = new User($id);
		$userExists = true;
	}
} else {
	header($_SERVER["SERVER_PROTOCOL"]." 301 Moved permanently");
	header("Location: ".ROOTDIR."Dashboard");
	die("Redirecting to your dashboard...");
}

define("PAGE_KEYWORD", Values::USER_PROFILE[0]);
define("PAGE_TITLE", Values::createTitle(Values::USER_PROFILE[1], ["name" => (isset($user) ? $user->getNickname() : "Invalid User")]));

if ($userExists) {
	define("PAGE_COLOR", $user->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("User Profile");

?>
<?php if (!$userExists): ?>
<?= User::getInvalidHTML() ?>
<?php else: ?>
			<div class="section">
				<div class="row">
					<div class="col s6 offset-s3 m4 center force-square-contents">
						<?= UniversalFunctions::getStrictCircleImageHTML($user->getProfilePicturePath(), $user->getProfilePictureNsfw()) ?>
					</div>
					<div class="col s12 m7 offset-m1">
						<div class="col s12 center-on-small-only">
							<h2 class="header hide-on-small-only no-margin"><?= $user->getNickname() ?></h2>
							
							<br class="hide-on-med-and-up">
							<h3 class="header hide-on-med-and-up no-margin"><?= $user->getNickname() ?></h3>

							<p class="flow-text no-margin"><?= $user->getUsername() ?></p>

<?php if (User::isLoggedIn() && $_SESSION["user"]->getId() == $id): ?>
							<p class="flow-text no-margin"><a href="<?=ROOTDIR?>Dashboard">View Dashboard</a></p>
<?php else: ?>
							<br>
<?php endif; ?>
<?php if (!is_null($user->getArtistPage())): ?>
							<p class="flow-text no-margin"><?= $user->getNickname() ?> takes commissions: <a href="<?= ROOTDIR."Artist/".$user->getArtistPage()->getUrl() ?>"><?= $user->getArtistPage()->getName() ?></a></p>
<?php endif; ?>

							<br>

							<?= Message::createMessageButton(Message::USER, $user->getUsername(), $user->getNickname()) ?>

							<br>

							<?= SocialMedia::getUserChipHTML($user) ?>
						</div>
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<h4>Characters</h4>
<?php
$characters = Character::getPublicCharactersFromUser($user);
$cards = [];
foreach ($characters as $character) {
	$img = $character->getPrimaryImage();
	$cards[] = '<div class="col s8 m4 l3">'.UniversalFunctions::renderImageCard(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::CHARACTER_IMAGE]."/".$img[0], $img[2], $character->getName(), "", ROOTDIR."Character/".$character->getToken()."/").'</div>';
}
?>
<?php if (count($cards) === 0): ?>
				<p class="flow-text">This user has no public characters</p>
<?php else: ?>
				<div class="horizontal-scrollable-container row">
<?= implode("", $cards) ?>
				</div>
<?php endif; ?>
			</div>
			<div class="divider"></div>
			<div class="section">
				<h4>Wishlist</h4>
<?php
$types = $user->getWishlistAsObjects();
$types = array_filter($types, function($type) {
	if (User::isLoggedIn() && $_SESSION["user"]->isNsfw()) {
		return true;
	}
	if (!in_array("MATURE", $type->getAttrs()) && !in_array("EXPLICIT", $type->getAttrs())) {
		return true;
	}
	return in_array("SFW", $type->getAttrs());
});
$cards = [];
foreach ($types as $type) {
	$img = $type->getPrimaryImage();
	$cards[] = '<div class="col s8 m4 l3">'.UniversalFunctions::renderImageCard(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::COMMISSION_TYPE_IMAGE]."/".$img[0], $img[2], $type->getName(), ($artist = $type->getArtistPage())->getName()."\n".$type->getBlurb(), ROOTDIR."Artist/".$artist->getUrl()."/").'</div>';
}
?>
<?php if (count($cards) === 0): ?>
				<p class="flow-text">This user's wishlist is empty!</p>
<?php else: ?>
				<div class="horizontal-scrollable-container row">
<?= implode("", $cards) ?>
				</div>
<?php endif; ?>
			</div>
<?php endif; ?>
<?php
require_once Values::FOOTER_INC;
