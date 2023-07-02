<?php
    // DocBlock: (to let the IDE be able to reference the $this->title to the View.php class)
    /** @var \app\Core\View $this */

    // Note: $this variable inside any view denotes the View.php Class.

    // echo '<pre>', var_dump($this), '</pre>'; // this page or $this is a View.php class object
    $this->title = 'UPDATE database operation -- Edit page'; // this page or $this is a View.php class object    // $title is a View.php class property    // $title variable shouble be assigned in every View file like here    // $title is echo-ed inside the mainLayout.php inside the <title> HTML tag
    // echo '<pre>', var_dump($this), '</pre>';
?>

        <h1>UPDATE/Edit a product - 'UPDATE' SQL operation or statement</h1>



<?php require_once 'form.php' ?>