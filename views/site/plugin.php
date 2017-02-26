<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Plugin WordPress - Smart Rewrite';
Yii::$app->view->params['metaKeyword'] = 'spinner, rewriter, auto spin, spun, wordpress, plugin';
Yii::$app->view->params['metaDescription'] = 'Smart Rewrite akan menulis ulang artikel bahasa Indonesiamu menjadi artikel baru
	tanpa merusak tata bahasa dan tetap enak dibaca.';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-plugin site col-sm-9">
    <h1><?= Html::encode($this->title) ?></h1>

	<p><em>
		Malas copy paste dari SmartRewrite ke WordPress? Ribet buka tab banyak-banyak
		hanya untuk copy sana sini?
		</em></p>
	<p>
		Sekarang SmartRewrite menyediakan plugin WordPress, <strong>WP-SmartRewrite</strong>
		untuk membantu kamu yang perlu menulis ulang secara cepat.
	<p>
		Dengan mengggunakan plugin ini, kamu tidak perlu membuka lagi aplikasi SmartRewrite.
		Kamu bisa langsung menulis teks yang ada di WordPress, dan dengan satu klik, otomatis akan menulis
		ulang artikel tersebut.
	</p>
	<p>
		Kelebihan menggunakan WP-SmartRewrite: 
	</p>
	<ul>
		<li> Sederhana & gampang digunakan. Satu klik langsung rewrite.</li>
		<li> Bisa mengatur konfigurasi sesuai kebutuhan.</li>
		<li> Ada opsi revert untuk mengembalikan ke teks semula.</li>		
		<li> Mendukung rewrite untuk teks maupun rich text.</li>		
	</ul>
	<p>
		<?= Html::img('/img/wordpress-smartrewrite-plugin-1.jpg', ['alt'=>'WordPress Plugin SmartRewrite: Rewrite Button']) ?>
	</p>
	<p>
		<?= Html::img('/img/wordpress-smartrewrite-plugin-2.jpg', ['alt'=>'WordPress Plugin SmartRewrite: Configuration Form']) ?>
	</p>
	
	<p>
		<a href="<?= Url::to('/plugins/wp-smartrewrite.zip')?>" class="btn btn-primary btn-large"> Download <strong>WP-SmartRewrite</strong> Sekarang</a>
	</p>
	<br/>
	<br/>
	<p>Untuk petunjuk penggunaan silahkan cek di halaman <a href="<?= Url::to('/site/plugin-documentation') ?>">Petunjuk Penggunaan WP-SmartRewrite</a>.</p>
	<br/><br/><br/><br/><br/><br/>