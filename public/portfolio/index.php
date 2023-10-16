<?php 
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(1);
?> 
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Marceau Rodrigues">

        <title>Marceau Rodrigues</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/grayscale.min.css" rel="stylesheet">

    </head>

    <body id="page-top">

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top">PORTFOLIO</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="#about">Présentation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="#skills">Mes compétences</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="../">Projets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="#cv">Curriculum vitæ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="#contact">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- En-tête à propos -->
        <header class="masthead">
            <div class="container d-flex h-100 align-items-center" id="about">
                <div class="mx-auto text-center">
                    <h1 class="mx-auto my-0 text-uppercase">Présentation</h1>
                    <h2 class="text-white-50 mx-auto mt-2 mb-2">Marceau Rodrigues, étudiant en BTS SIO (Services Informatiques aux Organisations) souhaitant devenir programmeur.</h2>
                    <h2 class="text-white-50 mx-auto mt-2 mb-5">Motivé et consciencieux, j'accomplirai les tâches que vous me confierez avec le plus grand soin.</h2>
                    <h2 class="text-white-50 mx-auto mt-2 mb-5">Utilisation de divers langages orientés objet.</h2>
                    <a href="#skills" class="btn btn-primary js-scroll-trigger">En apprendre davantage</a>
                </div>
            </div>
        </header>

        <!-- Section des compétences -->
        <section id="skills" class="about-section text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="text-white mb-4">Liste de mes compétences</h2><br>


                        <!-- Ligne 1 -->
                        <div class="row justify-content-center no-gutters mb-5 mb-lg-0">

                            <div class="col-lg-6">
                                <img class="img-fluid" src="img/php.jpg" alt="Logo PHP 7.0">
                            </div>
                            <div class="col-lg-6">
                                <div class="bg-black text-center h-100 project">
                                    <div class="d-flex h-100">
                                        <div class="project-text w-100 my-auto text-center text-lg-right">
                                            <h4 class="text-white">Programmation en PHP</h4>
                                            <p class="mb-0 text-white-50">Elaboration d'un <a class="view" href="img/siteGM.PNG">site complexe</a> avec Symfony, en PHP et lié à une BDD.<br>&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:90%;">90%
                                                </div>
                                            </div> 

                                            <hr class="d-none d-lg-block mb-0 ml-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ligne 2 -->
                        <div class="row justify-content-center no-gutters">
                            <div class="col-lg-6">
                                <img class="img-fluid" src="img/mysql.jpg" alt="Logo MySQL">
                            </div>
                            <div class="col-lg-6 order-lg-first">
                                <div class="bg-black text-center h-100 project">
                                    <div class="d-flex h-100">
                                        <div class="project-text w-100 my-auto text-center text-lg-left">
                                            <h4 class="text-white">Gestion/Création MySQL&nbsp;</h4>
                                            <p class="mb-0 text-white-50">Création, modification et maintenance de bases de données MySQL&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;; width: 90%;">90%
                                                </div>
                                            </div>
                                            <hr class="d-none d-lg-block mb-0 mr-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ligne 3 -->
                        <div class="row justify-content-center no-gutters mb-5 mb-lg-0">
                            <div class="col-lg-6">
                                <img class="img-fluid" src="img/java.jpg" alt="Tasse JAVA">
                            </div>
                            <div class="col-lg-6">
                                <div class="bg-black text-center h-100 project">
                                    <div class="d-flex h-100">
                                        <div class="project-text w-100 my-auto text-center text-lg-right">
                                            <h4 class="text-white">JAVA&nbsp;</h4>
                                            <p class="mb-0 text-white-50">Maîtrise du langage Java<br>&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;; width: 90%;">90%
                                                </div>
                                            </div>
                                            <hr class="d-none d-lg-block mb-0 mr-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ligne 4 -->
                        <div class="row justify-content-center no-gutters mb-5 mb-lg-0">
                            <div class="col-lg-6">
                                <img class="img-fluid" src="img/RubyOnRails.png" alt="Logo RubyOnRails">
                            </div>
                            <div class="col-lg-6 order-lg-first">
                                <div class="bg-black text-center h-100 project">
                                    <div class="d-flex h-100">
                                        <div class="project-text w-100 my-auto text-center text-lg-left">
                                            <h4 class="text-white">Ruby&nbsp;</h4>
                                            <p class="mb-0 text-white-50">Maîtrise du langage Ruby<br>&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;; width: 94%;">94%
                                                </div>
                                            </div>
                                            <p class="mb-0 text-white-50">Maîtrise du framework RubyOnRails<br></p><br>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;; width: 6%;">6%
                                                </div>
                                            </div>
                                            <hr class="d-none d-lg-block mb-0 mr-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ligne 5 -->
                        <div class="row justify-content-center no-gutters mb-5 mb-lg-0">
                            <div class="col-lg-6">
                                <img class="img-fluid" src="img/html-css.jpg" alt="Logos HTML5 et CSS3">
                            </div>
                            <div class="col-lg-6">
                                <div class="bg-black text-center h-100 project">
                                    <div class="d-flex h-100">
                                        <div class="project-text w-100 my-auto text-center text-lg-right">
                                            <h4 class="text-white">&nbsp;Gestion de sites web</h4>
                                            <p class="mb-0 text-white-50">&nbsp;Connaissances en CSS et HTML<br>&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;; width: 100%;">100%
                                                </div>
                                            </div>
                                            <hr class="d-none d-lg-block mb-0 ml-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ligne 6 -->
                        <div class="row justify-content-center no-gutters">
                            <div class="col-lg-6">
                                <img class="img-fluid" src="img/Csharp.jpg" alt="Logo CSharp">
                            </div>
                            <div class="col-lg-6">
                                <div class="bg-black text-center h-100 project">
                                    <div class="d-flex h-100">
                                        <div class="project-text w-100 my-auto text-center text-lg-right">
                                            <h4 class="text-white">C#&nbsp;</h4>
                                            <p class="mb-0 text-white-50">Maîtrise du langage C#<br>&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;; width: 45%;">45%
                                                </div>
                                            </div>
                                            <hr class="d-none d-lg-block mb-0 mr-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section du CV et de la lettre de motivation -->
        <section id="cv" class="projects-section bg-light">
            <div class="container">

                <!-- Row -->
                <div class="row align-items-center no-gutters mb-4 mb-lg-3">
                    <div class="col-xl-8 col-lg-5">
                        <img class="img-fluid mb-3 mb-lg-0" src="img/cv.png" alt="">
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="featured-text text-center text-lg-left">
                            <h4>Curriculum Vitæ</h4>
                            <p class="text-black-50 mb-0">Pour télécharger mon cv en pdf, cliquez sur le bouton.<br><br><a href="other/CV_Marceau_Rodrigues_2022.pdf" target="_blank"><button class="btn btn-primary mx-auto">Télécharger en PDF</button></a></p><br>
                        </div>
                    </div>
                </div> 
            </div>
        </section>


        <!-- Signup Section -->
        <section id="contact" class="signup-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-lg-8 mx-auto text-center">
                        <h2 class="text-white mb-3">Si vous avez des questions et/ou si mon profil vous plaît, vous pouvez me contacter par :</h2>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section bg-black">
            <div class="container">

                <div class="row">

                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card py-4 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-map-marked-alt text-primary mb-2"></i>
                                <h4 class="text-uppercase m-0">Adresse</h4>
                                <hr class="my-4">
                                <div class="small text-black-50">2 rue du Grand Large, 34200 Sète</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card py-4 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope text-primary mb-2"></i>
                                <h4 class="text-uppercase m-0">Email</h4>
                                <hr class="my-4">
                                <div class="small text-black-50">
                                    <a href="#">marceau0707@gmail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card py-4 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-mobile-alt text-primary mb-2"></i>
                                <h4 class="text-uppercase m-0">Téléphone</h4>
                                <hr class="my-4">
                                <div class="small text-black-50">+33 6 38 26 56 41</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="social d-flex justify-content-center">
                    <a href="../" class="mx-2">
                        <i class="far fa-folder-open"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/marceau-rodrigues-66a216182/" class="mx-2">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="https://gitlab.com/mrodrigues18" class="mx-2">
                        <i class="fab fa-gitlab"></i>
                    </a>
                </div>

            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-black small text-center text-white-50">
            <div class="container">
                Copyright &copy; Rodrigues Marceau 2019
            </div>
        </footer>

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Plugin JavaScript -->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for this template -->
        <script src="js/grayscale.min.js"></script>

    </body>

</html>
