<?php
    // DocBlock: (to let the IDE be able to reference the $this->title to the View.php class)
    /** @var \app\Core\View $this */

    // Note: $this variable inside any view denotes the View.php Class.

    // echo '<pre>', var_dump($this), '</pre>'; // this page or $this is a View.php class object
    $this->title = 'Welcome to Home Page'; // this page or $this is a View.php class object    // $title is a View.php class property    // $title variable shouble be assigned in every View file like here    // $title is echo-ed inside the mainLayout.php inside the <title> HTML tag
    // echo '<pre>', var_dump($this), '</pre>';
?>

        <div>This is HTML from inside hompePage.php view</div>
        <?php // echo $testDataPassedInToView;    // this is data passed in from the SiteController.php from homePage() method to the view here (homePage.php view)    // Data passed in from controller to view are analyzed and prepared inside the renderViewOnly() method in View.php class ?>

        <div>
            <a href="/products/index" class="btn btn-primary">Products Database CRUD Operations Application  -- INDEX page</a> 
        </div>