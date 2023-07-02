        <?php
            // This is a user-friendly error page for try ... catch (\Exception $e) block inside Application.php class inside run() method, which allows us to create our custom Exception message of our choice to general users (now we can throw Exception-s wherever we want in our application with the message we want, and set the desired HTTP response status code), and additionally, set our HTTP status code

            // DocBlock: (to let the IDE be able to reference the $this->title to the View.php class)
            /** @var \app\Core\View $this */

            // Note: $this variable inside any view denotes the View.php Class.

            // echo '<pre>', var_dump($this), '</pre>'; // this page or $this is a View.php class object
            $this->title = 'Exception Error View Page'; // this page or $this is a View.php class object    // $title is a View.php class property    // $title variable shouble be assigned in every View file like here    // $title is echo-ed inside the mainLayout.php inside the <title> HTML tag
            // echo '<pre>', var_dump($this), '</pre>';

            // DocBlock:
            /** @var \Exception $e Exception object */
        ?>
        
        <h3><?php echo 'Error! HTTP response status code: ' . $e->getCode() ?> - <?php echo 'Error message: ' . $e->getMessage() ?></h3>