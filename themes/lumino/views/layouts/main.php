<?php

use app\assets\AppAsset;
use app\themes\lumino\assets\AppAsset as LuminoAsset;
use app\themes\lumino\assets\IEAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
$this->title = $this->title . (empty($this->title) ? '' : ' | ') . Yii::$app->name;

LuminoAsset::register($this);
IEAsset::register($this);
AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?= Html::csrfMetaTags() ?>
<link rel="icon" type="image/png" href="<?=Url::base() ?>/favicon.png">
<title><?= Html::encode($this->title) ?></title>
<?php $this->head() ?>
</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?= Url::home();?>"><span>Smart</span>Rewrite</a>
				<?php echo Yii::$app->controller->renderPartial('//layouts/_user-menu') ?>
			</div>
							
		</div><!-- /.container-fluid -->
	</nav>
		
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<br/>
		<?php echo Menu::widget([
			'options' => ['class' => 'nav menu'],
			'items' => [
				['label' => '<svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg> Beranda', 'url' => ['/site/index'],'encode'=>false],
				['label' => '<svg class="glyph stroked star"><use xlink:href="#stroked-star"></use></svg> Tentang Smart Rewrite', 'url' => ['/site/about'],'encode'=>false],
				['label' => '<svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg>Hubungi Kami', 'url' => ['/site/contact'],'encode'=>false],
			],
		]);?>

	</div><!--/.sidebar-->
		
	<?php $this->beginBody() ?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<br/>
		<?= $content ?>
		
	</div>	<!--/.main-->
	<?php $this->endBody() ?>

	<script>
		
		!function ($) {
		    $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
		        $(this).find('em:first').toggleClass("glyphicon-minus");      
		    }); 
		    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>	
</body>
</html>
<?php $this->endPage() ?>