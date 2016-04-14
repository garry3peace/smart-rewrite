<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Tentang Smart Rewrite Indonesia - Smart Rewrite';
Yii::$app->view->params['metaKeyword'] = 'spinner, rewriter, auto spin, spun';
Yii::$app->view->params['metaDescription'] = 'Smart Rewrite akan menulis ulang artikel bahasa Indonesiamu menjadi artikel baru
	tanpa merusak tata bahasa dan tetap enak dibaca.';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about site">
    <h1><?= Html::encode($this->title) ?></h1>

	<p>
		Salah satu hal yang terpenting di dunia Internet Marketing dan SEO adalah
		konten. Sampai hari ini saya masih percaya bahwa <strong>konten adalah raja</strong>.
		Konten yang baik dan banyak tentunya akan disenangi mesin pencari. Mesin pencari 
		senang otomatis posisi website akan tinggi. Betapa berapa banyak orang sudah 
		merasakan nikmatnya uang dari internet. Rahasia utamanya? Konten yang padat!
	</p>
	<p>
		Masalahnya untuk menciptakan konten membutuhkan waktu yang tidak sedikit. Apalagi
		ketika kamu sedang membuat konten untuk Web Tier 1, Tier 2 dan ke bawah. Copy
		paste mentah-mentah sudah pasti akan membuat kamu dipenalti Google Panda. Oleh 
		karena itulah orang biasanya ada beberapa opsi:
	</p>
	<ul>
		<li>Mengarang Sendiri</li>
		<li>Beli artikel</li>
		<li>Menulis ulang (rewrite) artikel yang sudah ada</li>
	</ul>
	<p>
		Dari tiga cara tersebut, mengarang sendiri biasanya paling hemat tetapi memakan waktu. 
		Umumnya kualitasnya paling baik. Opsi membeli artikel juga akan mendapatkan hasil
		yang memuaskan (tergantung content writer). Tetapi membeli artikel ada beberapa kelemahan yakni
		biaya yang relatif tinggi. Dan belum tentu penulis langgananmu selalu ada waktu untukmu.
	</p>
	<p>
		Oleh karena itulah digunakan jalan langkah tengah. Cara ini bisa dibilang paling umum,
		karena tidak terlalu banyak menghabiskan waktu dan kualitas yang diberikan juga relatif 
		dapat diterima.
	</p>
	<p>
		Smart Spinner merupakan aplikasi pembantu untuk membuat menulis ulang artikel.
	</p>
    <p>
        Smart Rewrite Indonesia tidak sekedar spinner biasa. Smart Rewrite secara pintar
		akan memberi beberapa alternatif berupa sinonim dan antonim 
		untuk kata-kata sehingga pilihannya lebih banyak.
		Contohnya, kalimat seperti:
	</p>
	
	<pre>Andi rajin membaca buku </pre>
	
	<p>	bisa menghasilkan teks seperti:</p>
	
	<pre>Andi <strong>tidak malas</strong> membaca buku</pre>
	
	<p>Ataupun me-rewrite kalimat seperti:</p>
	
	<pre>Andi juara kelas karena dia paling rajin belajar.</pre>
	
	<p>menjadi:</p>
	
	<pre>Karena dia paling rajin belajar, Andi juara kelas.</pre>
	
	<p>
		Tools ini merupakan aplikasi yang dikembangkan saya sendiri karena saya membutuhkannya dalam
		mengurus blog. Jika kalian merasa terbantu dengan aplikasi ini, bisa share ke teman-temanmu untuk mencoba menggunakannya.
		Feedback dan masukan akan sangat saya harapkan. Jangan sungkan-sungkan <?= Html::a('tinggalkan pesan',['site/contact'])?>. 
	</p>
	
	<p>
		<br/><br/>
		Saat ini Smart Rewrite memiliki : <b><?= $wordCount; ?></b> kosa kata Indonesia.
	</p>
</div>
