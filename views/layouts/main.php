<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="WebUni Education Template">
    <meta name="keywords" content="webuni, education, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title;?></title>

    <!-- Favicon -->   
    <link href="../assets/img/favicon.ico" rel="shortcut icon"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="../assets/css/owl.carousel.css"/>
    <link rel="stylesheet" href="../assets/css/style.css"/>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header section -->
    <header class="header-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="site-logo">
                        <img src="../assets/img/logo.png" alt="">
                    </div>
                    <div class="nav-switch">
                        <i class="fa fa-bars"></i>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9">

                    <?php if (IS_AUTH): ?>
                        <a href="/main/logout" class="site-btn header-btn">Выйти</a>
                    <?php else: ?>
                        <a href="/main/auth" class="site-btn header-btn">Авторизация</a>
                    <?php endif; ?>

                    <nav class="main-menu">
                        <ul>
                            <li><a href="./">Главная</a></li>
                            <li><a href="#">О нас</a></li>
                            <li><a href="#">Курсы</a></li>
                            <li><a href="#">Новости</a></li>
                            <li><a href="#">Контакты</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header section end -->


    <!-- Hero section -->
    <section id="form-login" class="hero-section set-bg" data-setbg="../assets/img/bg.jpg">
        <div class="container">
            <div class="hero-text text-white">
                <h2><?=$title;?></h2>
            </div>
            <div id="content" class="row">

                <?php
                    include realpath('./views/'.$templete);
                ?>
                
            </div>
        </div>
    </section>
    <!-- Hero section end -->


    <!--====== Javascripts & Jquery ======-->
    <script src="../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/mixitup.min.js"></script>
    <script src="../assets/js/circle-progress.min.js"></script>
    <script src="../assets/js/owl.carousel.min.js"></script>
    <script src="../assets/js/main.js"></script>

</body>
</html>
