<?php
use app\core\Application;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Favicon -->
        <link rel="icon" href="/images/favicon.png">

        <!-- Bootstrap CDN CSS -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css"> --> <!-- CDNs Blocked! -->
        <link rel="stylesheet" href="/css/products/Languages/English/bootstrap.min.css">

        <!-- Bootstrap ICON FONTS CDN CSS -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css"> --> <!-- CDNs Blocked! -->

        <title><?php echo $this->title // Note: this page or $this are a View.php class object. $title is a View.php class property which is assigned in every view file ?></title>
    </head>
    <body>
            <!-- Bootstrap Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/contact">Contact</a>
                            </li>
                        </ul>



                        <!-- In case of user is a guest (NOT logged in), show Register and Login buttons, and hide Logout and Profile buttons. And in case user is not a guest (logged in), hide Register and Login buttons, and show Logout and Profile buttons -->
                        <?php
                            if (Application::$app->loggedInUser == null): // if the user is not logged in    // Or the same:    if (!Application::$app->loggedInUser):
                        ?>
                            <!-- Login and Register -->
                            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/login"><b>Login</b></a> 
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/register">Register</a> 
                                </li>
                            </ul>
                        <?php else: // show Logout and Profile buttons if the user is logged in ?>
                            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/profile">Profile</a> 
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/logout">Welcome <b><?php echo Application::$app->loggedInUser->firstname ?></b>, <b>Logout</b></a> 
                                </li>
                            </ul>
                        <?php endif ?>



                    </div>
                </div>
            </nav>
        <!-- End of Bootstrap Navbar -->



        <div class="container">

            <!-- Show $_SESSION Flash Messages: -->
            <?php if (Application::$app->session->getFlashMessageFromSession('registerANewUserSuccessFlashMessage') || Application::$app->session->getFlashMessageFromSession('successfulLoginFlashMessage') || Application::$app->session->getFlashMessageFromSession('successfulLogoutFlashMessage')): ?> 
                <div class="alert alert-success">
                    <?php echo Application::$app->session->getFlashMessageFromSession('registerANewUserSuccessFlashMessage') ?> 
                    <?php echo Application::$app->session->getFlashMessageFromSession('successfulLoginFlashMessage') ?> 
                    <?php echo Application::$app->session->getFlashMessageFromSession('successfulLogoutFlashMessage') ?> 
                </div>
            <?php endif ?>
            
            
            <!-- This is a PLACEHOLDER for the $view which will be replaced using str_replace() function inside the View.php class inside the renderView() method -->
            {{content}}
        </div>


        <!-- Bootstrap CDN JavaScript -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script> --> <!-- CDNs Blocked! -->
        <script src="/js/products/bootstrap.min.js"></script> 
    </body>
</html>