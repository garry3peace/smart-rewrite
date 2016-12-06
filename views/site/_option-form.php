<?php
use app\components\Html;

?>

<hr/>
<div class="panel panel-default">
	<div class="panel-heading">
		<svg class="glyph stroked gear"><use xlink:href="#stroked-gear"/></svg> Konfigurasi
	</div>

	<div class="panel-body">
		<div class="form option-form">
			<div class="row">

				<div class="col-lg-12">
					<?= Html::checkbox('Options[unique]', $options['unique']); ?> <?= Html::label('Unik');?>
					<p class="help-block">Jika dicentang, Rewriter akan selalu menggunakan kata penganti.</p>
				</div>
				<div class="col-lg-12 form-group">
					<?= Html::label('Perkecualian') ?>
					<?= Html::textInput('Options[exception]', $options['exception'],['class'=>'form-control']); ?>
					<p class="help-block">Masukkan kata-kata dipisah titik koma (;) agar tidak diproses oleh Rewriter.</p>
				</div>
				<div class="col-lg-12">
					<?= Html::checkbox('Options[paragraph_reorder]', $options['paragraph_reorder']); ?> <?= Html::label('Acak Paragraf');?>
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
	</div>
</div>