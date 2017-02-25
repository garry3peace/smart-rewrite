<?php
use yii\helpers\Url;
$user = Yii::$app->user->identity;
?>

<?php if(!Yii::$app->user->isGuest): ?>
<ul class="user-menu">
	<li class="dropdown pull-right">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"/></svg> <?= $user->profile->name ?><span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="<?= Url::to('/user/settings/profile') ?>"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"/></svg> Profile</a></li>
			<li><a href="<?= Url::to('/user/settings/account') ?>"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"/></svg> Settings</a></li>
			<li><a href="<?= Url::to('/user/security/logout')?>" data-method="post"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"/></svg> Logout</a></li>
		</ul>
	</li>
</ul>
<?php else: ?>
<ul class="user-menu">
	<li>
		<a href="<?= Url::to('/user/security/login') ?>">Sign In</a>
	</li>
	<li>
		<a href="<?= Url::to('/user/register')?> ">Register</a>
	</li>
</ul>
<?php endif; ?>
