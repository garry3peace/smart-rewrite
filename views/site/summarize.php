<?php
use yii\helpers\Html;
use app\assets\HomeAsset;

HomeAsset::register($this);

$this->title = 'Summarize - Mesin rewrite dan spinner Indonesia terbaik';
Yii::$app->view->params['metaKeyword'] = 'spinner, rewriter, auto spin, spun';
Yii::$app->view->params['metaDescription'] = 'Smart Rewrite akan menulis ulang artikel bahasa Indonesiamu menjadi artikel baru
	tanpa merusak tata bahasa dan tetap enak dibaca.';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site home">
    

	<div class="row">
		<div class="col-lg-12">
			<p>
				Masukkan teks ke dalam kotak di bawah ini untuk diambil kesimpulannya
			</p>
			<?= Html::beginForm(); ?>
			<?= Html::textarea('Summarize[content]',$content,['class'=>'form-control','rows'=>10]); ?>
			<?= Html::submitButton('Submit',['class'=>'btn-primary btn']); ?>
			<?= Html::endForm(); ?>
		</div>
	</div>
	<hr/>
	<h2>Rangkuman</h2>
	<div class="row">
		<div class="col-lg-12 result">
			<?= Html::textarea('Summarize[summary]',$summary,['id'=>'summary', 'class'=>'form-control','rows'=>10,'locked'=>'locked']); ?>
		</div>
		
		<div class="col-lg-12">
			<br/>
			<button data-clipboard-target="#Spin_spinTax" class="btn btn-success">Copy</button>
		</div>
	</div>
	<hr/>
</div>