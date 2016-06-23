<?php
use yii\helpers\Html;
use app\assets\HomeAsset;

HomeAsset::register($this);

$this->title = 'Post To WordPress';
Yii::$app->view->params['metaKeyword'] = 'spinner, rewriter, auto spin, spun';
Yii::$app->view->params['metaDescription'] = 'Smart Rewrite akan menulis ulang artikel bahasa Indonesiamu menjadi artikel baru
	tanpa merusak tata bahasa dan tetap enak dibaca.';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site scrape">
    
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="row">
		<div class="col-lg-12">
			<p>
				Masukkan link-link ke dalam kotak di bawah ini untuk post ke situs
			</p>
			<?= Html::beginForm(); ?>
			<?= Html::textarea('Scrape[list]',$content,['class'=>'form-control','rows'=>10]); ?>
			<?= Html::submitButton('Submit',['class'=>'btn-primary btn']); ?>
			<?= Html::endForm(); ?>
		</div>
	</div>
</div>