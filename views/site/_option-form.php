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
		<div class="col-lg-12">
			<?= Html::checkbox('Spin[options][paragraph]', $options['paragraph']); ?> Acak Paragraf
			<p class="help-block">Mengacak posisi paragraf.</p>
		</div>
		<div class="col-lg-12">
			<?= Html::checkbox('Spin[options][paragraph_exclude_first_last]', $options['paragraph_exclude_first_last']); ?> Jangan acak paragraf awal & akhir
			<p class="help-block">Hanya berguna jika "Acak Paragraf" dinyalakan. Berguna untuk tidak mengacak paragraf paling pertama dan akhir.</p>
		</div>
	</div>
</div>