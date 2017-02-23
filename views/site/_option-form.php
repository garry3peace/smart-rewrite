<?php
use app\components\Html;

?>

<hr/>
<div class="panel panel-info configuration">
	<div class="panel-heading">
		<svg class="glyph stroked gear"><use xlink:href="#stroked-gear"/></svg> Konfigurasi
		<div class="close" ng-click="toggleConfigurationPanel()">
			<div ng-if="configurationStatus=='up'">
				<svg class="glyph stroked chevron up">
					<use xlink:href="#stroked-chevron-up"/>
				</svg>
			</div>
			
			<div ng-if="configurationStatus=='down'">
				<svg class="glyph stroked chevron down">
					<use xlink:href="#stroked-chevron-down"/>
				</svg>
			</div>
		</div>
	</div>

	<div class="panel-body status-{{configurationStatus}}">
		<div class="form option-form">
			<div class="row">

				<div class="col-lg-12">
					<?= Html::checkbox('Options[unique]', $options['unique']); ?> <?= Html::label('Unik');?>
					<p class="help-block">Jika dicentang, SmartRewrite akan selalu memilih kata lain jika ada.</p>
				</div>
				<div class="col-lg-12 form-group">
					<?= Html::label('Perkecualian') ?>
					<?= Html::textInput('Options[exception]', $options['exception'],['class'=>'form-control']); ?>
					<p class="help-block">Masukkan kata-kata dipisah titik koma (;) agar tidak diproses oleh SmartRewrite.</p>
				</div>
				<div class="col-lg-12">
					<?= Html::checkbox('Options[paragraph_reorder]', $options['paragraph_reorder']); ?> <?= Html::label('Acak Paragraf');?>
					<p class="help-block">Mengacak paragraf.</p>
				</div>
				<div class="col-lg-12">
					<?= Html::label('Paragraf Perkecualian') ?>
					<?= Html::textInput('Options[paragraph_exclude_reorder]', $options['paragraph_exclude_reorder'],['class'=>'form-control']); ?>
					<p class="help-block">Hanya berfungsi jika "Acak Paragraf" dicentang. 
						Digunakan untuk tidak mengacak paragraf tertentu. Pisah dengan koma. 
						Masukkan paragraf ke berapa yang menjadi perkecualian. Untuk paragraf
					dihitung dari terakhir bisa gunakan negatif. Contoh, paragraf paling terakhir bisa isi "-1", paragraf kedua dari terakhir "-2".</p>
				</div>
			</div>
		</div>
	</div>
</div>