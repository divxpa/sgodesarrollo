<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="favicon icon" href="favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
      integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
      crossorigin="anonymous"></script>
    <?php $this->head() ?>
</head>
<body>

<div class="wrap">
<header>
    <div class="header">
        <div class="container-fluid">
            <!-- div id="callback_status" style="display:none;">
                <center>
                    <img src="themes/serviciospublicos/images/ajax-loader2.gif" />
                </center>
            </div -->
            <div style="float:right; margin-right: 10px;">
                <img src="img/provinciaBlanco.png" border="0">
            </div>
                <div id="logo" style="float:left;" class="palabra">
                <h1>SGO <span class="subtitle">TABLERO</span></h1>
            </div>
        </div> 
    </div> 
    <nav>
        <div class="container-fluid">

            <?php
            /*NavBar::begin([
                'brandLabel' => '<span style="font-size:32px"><b>S.R.H.</b></span>',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);*/
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'items' => Yii::$app->controller->getMenu(),
            ]);
            //NavBar::end();
            ?>

            <?php /*echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    //['label' => 'Home', 'url' => ['/site/index']],
                    ['label' => 'Obras', 'url' => ['/tablero/obras']],
                    ['label' => 'Proveedores', 'url' => ['/tablero/proveedores']],
                    ['label' => 'Indicadores', 'url' => ['/tablero/indicadores']],
                    Yii::$app->user->isGuest ? (
                        ['label' => 'Login', 'url' => ['/site/login']]
                    ) : (
                        '<li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            'Logout (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>'
                    )
                ],
            ]); */ ?>
        </div> 
    </nav>
</header>
    <!-- 'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ], -->

<?php $this->beginBody() ?>
<div class="espacio"></div>


<div class="breadcrumbs-container container-fluid">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
</div>
<?= $content ?>

</div>

<footer class="footer fixed-x">
    <div class="container-fluid">
        <span style="color:#fff;font-size: 9px; font-family: 'Montserrat',verdana, Arial, Helvetica, sans-serif;">Coordinación Técnica Ministerio de Energía, Servicios Públicos y Recursos Naturales - </span>
        <span style="font-size: 9px; font-family: 'Montserrat',verdana, Arial, Helvetica, sans-serif;">
            <a href="http://www.puntogap.com.ar" target="_blank" style="color:#eee;text-decoration:none;">Desarrollado por PUNTOGAP (2016)</a>
        </span>
    </div>
</footer>

<?php $this->endBody() ?>

<script type="text/javascript">

    $(function() 
    {
        fixedX()
        //ajustarFooter();
    });

    $(window).scroll(function()
    {
        fixedX()
    });

    $(window).resize(function()
    {
        //ajustarFooter();
    });

    function fixedX()
    {
        $(".fixed-x").each(function(i, elem){
            $(elem).css({
                "position": "relative", 
                "left": window.scrollX
            });
        });
    }

    // Innecesario mientras siga funcionando bien el CSS
    function ajustarFooter()
    {
        var $footer = $("footer");
        var footerUbicacionOrigen = $footer.offset().top - $footer.css("top").replace("px","")*1;
        var footerAltura = $footer.css("height").replace("px","")*1;

        $footer.css(
            "top",
            footerUbicacionOrigen < window.innerHeight ? window.innerHeight - ( footerAltura + footerUbicacionOrigen ) : 0
        );
    }

</script>


</body>
</html>
<?php $this->endPage() ?>