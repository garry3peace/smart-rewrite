<?php
use yii\helpers\Html;
use app\assets\HomeAsset;

HomeAsset::register($this);

$this->title = 'Smart Rewrite - Mesin rewrite dan spinner Indonesia terbaik';
Yii::$app->view->params['metaKeyword'] = 'spinner, rewriter, auto spin, spun';
Yii::$app->view->params['metaDescription'] = 'Smart Rewrite akan menulis ulang artikel bahasa Indonesiamu menjadi artikel baru
	tanpa merusak tata bahasa dan tetap enak dibaca.';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site home" ng-app="app">
    

	<div class="row">
		<div class="col-lg-12">
			<p>
				Masukkan teks ke dalam kotak di bawah ini untuk ditulis ulang oleh 
				Smart Rewrite
			</p>
			<?= Html::beginForm(); ?>
			<?= Html::textarea('Spin[content]',$content,['class'=>'form-control','rows'=>10]); ?>
			<?= Yii::$app->controller->renderPartial('_option-form', ['options'=>$options]); ?>
			<?= Html::submitButton('Submit',['class'=>'btn-primary btn']); ?>
			<?= Html::endForm(); ?>
		</div>
	</div>
	<hr/>
	<h2>Spintax</h2>
	<div class="row">
		<div class="col-lg-12 result">
			<?= Html::textarea('Spin[spinTax]',$spinTax,['id'=>'Spin_spinTax', 'class'=>'form-control','rows'=>10,'locked'=>'locked']); ?>
			<p class="small">Spintax biasanya dipakai di aplikasi seperti GSA Search Engine Ranker atau Scrapebox</p>
		</div>
		
		<div class="col-lg-12">
			<br/>
			<button data-clipboard-target="#Spin_spinTax" class="btn btn-success">Copy</button>
		</div>
	</div>
	<hr/>
	<h2>Hasil</h2>
	<div class="row">
		<div class="col-lg-12 result">
			<?= Html::textarea('Spin[result]',$result,['id'=>'Spin_result', 'class'=>'form-control','rows'=>10,'locked'=>'locked']); ?>
		</div>
		<div class="col-lg-12">
			<br/>
			<button data-clipboard-target="#Spin_result" class="btn btn-success">Copy</button>
		</div>
	</div>
</div>