<?php
    // DocBlock: (to let the IDE be able to reference the $this->title to the View.php class)
    /** @var \app\Core\View $this */

    // Note: $this variable inside any view denotes the View.php Class

    // echo '<pre>', var_dump($this), '</pre>'; // this page or $this is a View.php class object
    $this->title = 'Register / Create an account'; // this page or $this is a View.php class object    // $title is a View.php class property    // $title variable shouble be assigned in every View file like here    // $title is echo-ed inside the mainLayout.php inside the <title> HTML tag
    // echo '<pre>', var_dump($this), '</pre>';
?>

        <h1>Register / Creat an account</h1>

        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="POST"> <!-- The enctype="multipart/form-data" attribute is used for uploading files --> <!-- Here we send data to the same page -->
            <div class="mb-3">
                <label class="form-label">Firstname:</label>
                <input
                    class="form-control <?php echo $userModelObject->inputFieldHasError('firstname') ? 'is-invalid' : '' ?>"
                    type="text"
                    name="firstname"
                    value="<?php echo $userModelObject->firstname ?>"
                >
                
                <!-- Show the <input> field `Firstname` Error Bootstrap div -->
                <?php if ($userModelObject->inputFieldHasError('firstname')):
                        foreach($userModelObject->inputFieldHasError('firstname') as $validationRuleSpecifiedInModel):
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
                <label class="form-label">Lastname:</label>
                <input
                    class="form-control <?php echo $userModelObject->inputFieldHasError('lastname') ? 'is-invalid' : '' ?>"
                    type="text"
                    name="lastname"
                    value="<?php echo $userModelObject->lastname ?>"
                >

                <!-- Show the <input> field `Lastname` Error Bootstrap div -->
                <?php if ($userModelObject->inputFieldHasError('lastname')):
                        foreach($userModelObject->inputFieldHasError('lastname') as $validationRuleSpecifiedInModel):
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
                <label class="form-label">Email:</label>
                <input
                    class="form-control <?php echo $userModelObject->inputFieldHasError('email') ? 'is-invalid' : '' ?>"
                    type="text"
                    name="email"
                    value="<?php echo $userModelObject->email ?>"
                >
                
                <!-- Show the <input> field `Email` Error Bootstrap div -->
                <?php if ($userModelObject->inputFieldHasError('email')):
                        foreach($userModelObject->inputFieldHasError('email') as $validationRuleSpecifiedInModel):
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
                    class="form-control <?php echo $userModelObject->inputFieldHasError('password') ? 'is-invalid' : '' ?>"
                    type="password"
                    name="password"
                    value="<?php echo $userModelObject->password ?>"
                >

                <!-- Show the <input> field `Password` Error Bootstrap div -->
                <?php if ($userModelObject->inputFieldHasError('password')):
                        foreach($userModelObject->inputFieldHasError('password') as $validationRuleSpecifiedInModel):
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
                <label class="form-label">Confirm password:</label>
                <input
                    class="form-control <?php echo $userModelObject->inputFieldHasError('confirmPassword') ? 'is-invalid' : '' ?>"
                    type="password"
                    name="confirmPassword"
                    value="<?php echo $userModelObject->confirmPassword ?>"
                >

                <!-- Show the <input> field `Confirm password` Error Bootstrap div -->
                <?php if ($userModelObject->inputFieldHasError('confirmPassword')):
                        foreach($userModelObject->inputFieldHasError('confirmPassword') as $validationRuleSpecifiedInModel):
                ?>
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