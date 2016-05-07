<?php
use yii\helpers\Html;

$this->title = 'Import Exclusion';
Yii::$app->view->params['metaKeyword'] = 'spinner, rewriter, auto spin, spun';
Yii::$app->view->params['metaDescription'] = 'Smart Rewrite akan menulis ulang artikel bahasa Indonesiamu menjadi artikel baru
	tanpa merusak tata bahasa dan tetap enak dibaca.';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site import">
    <h1><?= Html::encode($this->title) ?></h1>

        <div class="row">
            <div class="col-lg-12">
				
				<?php echo Html::beginForm(); ?>
				<?php echo Html::textarea('import[exclusion]','',['class'=>'form-control','rows'=>10]); ?>
				<br/>
				<?php echo Html::submitButton('Submit',['class'=>'btn-primary btn']); ?>
				<?php echo Html::endForm(); ?>
            </div>
        </div>
</div>
