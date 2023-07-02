<?php
    // DocBlock: (to let the IDE be able to reference the $this->title to the View.php class)
    /** @var \app\Core\View $this */

    // Note: $this variable inside any view denotes the View.php Class.

    // echo '<pre>', var_dump($this), '</pre>'; // this page or $this is a View.php class object
    $this->title = 'Login to your account'; // this page or $this is a View.php class object    // $title is a View.php class property    // $title variable shouble be assigned in every View file like here    // $title is echo-ed inside the mainLayout.php inside the <title> HTML tag
    // echo '<pre>', var_dump($this), '</pre>';
?>

        <h1>Login to your account</h1>

        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="POST"> <!-- The enctype="multipart/form-data" attribute is used for uploading files --> <!-- Here we send data to the same page -->
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input
                    class="form-control <?php echo $loginFormModelObject->inputFieldHasError('email') ? 'is-invalid' : '' ?>"
                    type="text"
                    name="email"
                    value="<?php echo $loginFormModelObject->email ?>"
                >


                <!-- Show the <input> field `Email` Error Bootstrap div -->
                <?php if ($loginFormModelObject->inputFieldHasError('email')):
                        foreach ($loginFormModelObject->inputFieldHasError('email') as $validationRuleSpecifiedInModel):
                ?>
                            <div class="invalid-feedback">
                                <?php echo $validationRuleSpecifiedInModel ?>
                            </div>
                <?php
                        endforeach;
                      endif;
                ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input
                    class="form-control <?php echo $loginFormModelObject->inputFieldHasError('password') ? 'is-invalid' : '' ?>"
                    type="password"
                    name="password"
                    value="<?php echo $loginFormModelObject->password ?>"
                >

                <!-- Show the <input> field `Password` Error Bootstrap div -->
                <?php if ($loginFormModelObject->inputFieldHasError('password')):
                        foreach ($loginFormModelObject->inputFieldHasError('password') as $validationRuleSpecifiedInModel):
                ?>
                            <!-- <div class="alert alert-danger"> -->
                            <div class="invalid-feedback">
                                <?php echo $validationRuleSpecifiedInModel ?>
                            </div>
                <?php
                        endforeach;
                      endif;
                ?>
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>