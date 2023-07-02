        <?php
            // DocBlock: (to let the IDE be able to reference the $this->title to the View.php class)
            /** @var \app\Core\View $this */

            // Note: $this variable inside any view denotes the View.php Class.

            // Using DocBlock in order for the IDE to be able to reference the $productModelObject variable
            /** @var \app\models\ProductModel $productModelObject */
        ?>
        
        <!-- Go back to Products Button -->
        <div>
            <a href="/products/index" class="btn btn-secondary">Go back to Products</a> 
        </div>


        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="POST" enctype="multipart/form-data"> <!-- The enctype="multipart/form-data" attribute is used for uploading files --> <!-- Here we send data to the same page -->
            <?php if ($productModelObject->image_path): ?> <!-- If the user uploaded an image in create.php (if there' an image_path column value for the product in the database table), or in update.php, show the already existing image -->
                <img class="update-image" src="/<?php echo $productModelObject->image_path ?>">  <!-- A forward slash '/' is typed in before the path to denote the root domain/path/directory (Absolute path) -->
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input
                    class="form-control <?php echo $productModelObject->inputFieldHasError('title') ? 'is-invalid' : '' ?>"
                    type="text"
                    name="title"
                    value="<?php echo $productModelObject->title ?>"
                >
                
                <!-- Show the <input> field `Title` Error Bootstrap div -->
                <?php
                    if ($productModelObject->inputFieldHasError('title')):
                        foreach ($productModelObject->inputFieldHasError('title') as $validationRuleSpecifiedInModel):
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
                <label class="form-label">Description:</label>
                <textarea
                    class="form-control <?php echo $productModelObject->inputFieldHasError('description') ? 'is-invalid' : '' ?>"
                    name="description"><?php echo $productModelObject->description ?></textarea
                >

                <!-- Show the <input> field `Description` Error Bootstrap div -->
                <?php
                    if ($productModelObject->inputFieldHasError('description')):
                        foreach ($productModelObject->inputFieldHasError('description') as $validationRuleSpecifiedInModel):
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
                <label class="form-label">Price:</label>
                <input
                    step="0.01"
                    class="form-control <?php echo $productModelObject->inputFieldHasError('price') ? 'is-invalid' : '' ?>"
                    type="number"
                    name="price"
                    value="<?php echo $productModelObject->price == 0 ? null : $productModelObject->price ?>"
                >

                    <!-- Show the <input> field `Price` Error Bootstrap div -->
                    <?php
                        if ($productModelObject->inputFieldHasError('price')):
                            foreach ($productModelObject->inputFieldHasError('price') as $validationRuleSpecifiedInModel):

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
                <label class="form-label">Upload your image:</label>
                <br>
                <input type="file" name="uploadedImage"> <!--   type="file"   attribute is used for uploading files -->
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>