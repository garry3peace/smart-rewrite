<?php
use yii\helpers\Html;

?>

<div class="form option-form">
	<hr/>
	<div class="row">
		<h3>Konfigurasi</h3>
		<div class="col-lg-12">
			<?= Html::checkbox('Spin[options][unique]', $options['unique']); ?> Unik
			<p class="help-block">Spinner tidak akan menyertakan kata asal di hasil.</p>
		</div>
		<div class="col-lg-12 form-group">
			<?= Html::label('Perkecualian') ?>
			<?= Html::textInput('Spin[options][exception]', $options['exception'],['class'=>'form-control']); ?>
			<p class="help-block">Masukkan kata-kata dipisah titik koma (;) agar tidak diproses</p>
		</div>
	</div>
</div>