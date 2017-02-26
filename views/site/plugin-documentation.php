<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Petunjuk Penggunaan Plugin WordPress - Smart Rewrite';
Yii::$app->view->params['metaKeyword'] = 'spinner, rewriter, auto spin, spun, wordpress, plugin, tutorial, documentation, dokumentasi, manual';
Yii::$app->view->params['metaDescription'] = 'Smart Rewrite akan menulis ulang artikel bahasa Indonesiamu menjadi artikel baru
	tanpa merusak tata bahasa dan tetap enak dibaca.';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-plugin site col-sm-9">
    <h1><?= Html::encode($this->title) ?></h1>

	<h2> Petunjuk Instalasi Plugin</h2>
	<ol>
		<li>Download satu file <a href="<?= Url::to('/plugins/wp-smartrewrite.zip')?>">WP-SmartRewrite</a> apabila belum.</li>
		<li>Buka WordPress dan login ke Dashboard</li>
		<li>Pilih <strong>Plugins</strong> > <strong>Add New</strong></li>
		<li>Klik tombol <strong>Upload Plugin</strong> di bagian atas yang akan memunculkan form untuk upload file.</li>
		<li>Carilah file wp-smartrewrite.zip, dan tekan <strong>Install Now</strong> </li> 
		<li>Setelah itu tekanlah <strong>Activate Plugin</strong></li>
	</ol>
	
	<h2> Cara Penggunaan Plugin WP-SmartRewrite</h2>
	<p>
		Untuk menggunakan plugin WP-SmartRewrite, silahkan buatkan artikel seperti biasa. Kamu akan melihat di atas tombol
		<strong>Publish </strong> ada tombol baru tertulis <strong>Smart Rewrite!</strong>. Itulah tombol yang akan menulis ulang 
		artikel yang ada. Cobalah copy suatu artikel ke text editor, kemudian tekan tombol. Perhatikan artikel yang barusan di copy di text editor
		akan berubah!
	</p>
	<p>
		Untuk mengatur opsi-opsi penulisan ulang, kamu bisa atur di bagian bawah text editor.
		Cobalah scroll ke bawah, kamu akan melihat sebuah form berjudul <strong>Smart Rewrite</strong>.
	</p>
	<p>
		<img src="<?= Url::to('/img/tutorial-wp-smartrewrite.jpg') ?>"
	</p>
	<p>
		Selesai! Sederhana bukan?
	</p>
	<br/><br/><br/><br/><br/>