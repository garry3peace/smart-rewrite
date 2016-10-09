<?php
use app\components\Html;

?>

<div class="form option-form">
	<hr/>
	<div class="row">
		<h3>Konfigurasi</h3>
		<div class="col-lg-12">
			<?= Html::checkbox('Options[unique]', $options['unique']); ?> Unik
			<p class="help-block">Spinner tidak akan menyertakan kata asal di hasil.</p>
		</div>
		<div class="col-lg-12 form-group">
			<?= Html::label('Perkecualian') ?>
			<?= Html::textInput('Options[exception]', $options['exception'],['class'=>'form-control']); ?>
			<p class="help-block">Masukkan kata-kata dipisah titik koma (;) agar tidak diproses</p>
		</div>
		<div class="col-lg-12">
			<?= Html::checkbox('Options[paragraph_reorder]', $options['paragraph_reorder']); ?> Acak Paragraf
			<p class="help-block">Mengacak posisi paragraf.</p>
		</div>
		<div class="col-lg-12">
			<?= Html::label('Paragraf Perkecualian') ?>
			<?= Html::textInput('Options[paragraph_exclude_reorder]', $options['paragraph_exclude_reorder'],['class'=>'form-control']); ?>
			<p class="help-block">Hanya berfungsi jika "Acak Paragraf" dinyalakan. 
				Digunakan untuk tidak mengacak paragraf tertentu. Pisah dengan koma. 
				Masukkan angka urutan paragraf yang jangan disusun. Untuk angka 
			dari belakang bisa pakai negatif. Untuk paragraf paling terakhir bisa isi "-1".</p>
		</div>
	</div>
</div>