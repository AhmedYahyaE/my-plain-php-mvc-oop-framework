<?php
    // DocBlock: (to let the IDE be able to reference the $this->title to the View.php class)
    /** @var \app\Core\View $this */

    // Note: $this variable inside any view denotes the View.php Class.

    // echo '<pre>', var_dump($this), '</pre>'; // this page or $this is a View.php class object
    $this->title = 'SELECT database operation -- Show one product from database'; // this page or $this is a View.php class object    // $title is a View.php class property    // $title variable shouble be assigned in every View file like here    // $title is echo-ed inside the mainLayout.php inside the <title> HTML tag
    // echo '<pre>', var_dump($this), '</pre>';
?>


<div>Show one product data</div>