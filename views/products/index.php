<?php
    // DocBlock: (to let the IDE be able to reference the $this->title to the View.php class)
    /** @var \app\core\View $this */
    /** @var \app\models\ProductModel[] $allProductsOrSearchResults */
    /** @var \app\models\ProductModel $productModelObject */
    // Note: $this variable inside any view denotes the View.php Class.

    $this->title = 'Products index page (SELECT database operation and Search)'; // this page or $this is a View.php class object    // $title is a View.php class property    // $title variable shouble be assigned in every View file like here    // $title is echo-ed inside the mainLayout.php inside the <title> HTML tag
?>
        <!-- Choosing application language dropdown menu -->
        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="GET" onchange="this.submit()">
            <label for="languages"><b>Choose Language:</b></label>
            <select id="languages" name="language">
                <option <?php echo $languageFile == null ? 'selected' : '' ?> value="English">English</option>
                <option <?php echo $languageFile != null ? 'selected' : '' ?> value="Arabic">Arabic</option>
            </select>
        </form>

        <br>

        <?php // In case the language is Arabic
            if ($languageFile != null) {
                echo '<span style="color: red">Language file is working properly: <b>' . $languageFile['test_arabic'] . '</b></span>';
            }
        ?>

        <br>
        <br>

        <h1>This is READ ('SELECT' SQL statement) database operation page (and Search Bar using AJAX and Direct Database Search)</h1>
        <!-- Go to Home Page -->
        <div>
            <a href="/" class="btn btn-secondary">Go to website HomePage</a> 
        </div>
        <h2>All Products List:</h2>



        <!-- SEARCH BAR <form> (SEARCH is a 'SELECT' CRUD OPERATION) --> <!-- Leaving out the "action" attribute blank means submit data to the same page by default, and leaving out the "method" attribute blank means the method is "GET" by default -->
        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="GET">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search for products using Direct Database Search" name="searchBar" value="<?php echo $searchBarValue ?>"> <!-- use the "value" attribute to always make the <input> field keep/retain the searched data (the submitted data) to show the searched value inside the <input> field -->
                <button class="btn btn-outline-secondary" type="submit">Direct Database Search</button>
            </div>
        </form>


        <!-- AJAX LIVE SEARCH BAR (SEARCH is a 'SELECT' CRUD OPERATION) -->
        <form>
            <div class="input-group mb-3">
                <input id="AJAX_live_search_input_field" type="text" class="form-control" placeholder="Search for products using AJAX Live Search" name="searchBar">
            </div>
        </form>



        <!-- Show a 'Go back to Products' button that appears only if user uses the search bar -->
        <?php if ($searchBarValue): ?>
        <div>
            <a href="/products/index" class="btn btn-secondary">Go back to Products</a> 
        </div>
        <?php endif ?>



        <!-- Create a product Button -->
        <a href="/products/create" class="btn btn-primary">Create a product (INSERT SQL statement)</a> 



        <!-- Sorting Buttons (ASC, DESC) -->
        <div>
            <a href="<?php echo htmlspecialchars($_SERVER['PATH_INFO']) . '?page=' . $productModelObject->currentPageNumber . '&sorting=ASC' ?>" >Asc<i  class="bi bi-sort-up"   style="color:green; font-size: 30px; cursor: pointer"></i></a>
            <a href="<?php echo htmlspecialchars($_SERVER['PATH_INFO']) . '?page=' . $productModelObject->currentPageNumber . '&sorting=DESC' ?>">Desc<i class="bi bi-sort-down" style="color:red;   font-size: 30px; cursor: pointer"></i></a>
        </div>



        <!-- Table to view all products from database (SELECT operation) -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Create Date</th>
                    <th scope="col">Edit or Delete Action</th>
                </tr>
            </thead>
            <tbody id="table_body_for_AJAX">
                <?php foreach ($allProductsOrSearchResults as $arrayIndexNumber => $product): ?>
                <tr>
                    <th scope="row"><?php echo $productModelObject->SQLOffset + ($arrayIndexNumber + 1) ?></th> <!-- The number (ordering) of an item = $SQLOffset + arrayLength      and as is well known, arrayLength = arrayIndex + 1 -->
                    <td>
                        <!-- If there's an image already uploaded for the product (by checking the database table for its file path), show it -->
                        <?php if ($product['image_path']): ?>
                        <img class="thumb-image" src="/<?php echo $product['image_path'] ?>">  <!-- Forward slash '/' to denote the root domain/directory/path -->
                        <?php endif ?>
                    </td>
                    <td><?php echo $product['title'] ?></td>
                    <td><?php echo $product['description'] ?></td>
                    <td><?php echo $product['price'] ?></td>
                    <td><?php echo $product['create_date'] ?></td>
                    <td>


                        <!-- EDIT & DELETE Buttons -->

                        <!-- First: Edit with GET request & Query String (less secure) -->
                        <a href="/products/update?id=<?php echo htmlspecialchars($product['id']) ?>" class="btn btn-sm btn-outline-primary" style="margin: 3px">UPDATE (GET req)</a>  <!-- Don't forget to create an update GET route in index.php -->
                        
                        <!-- Second: Edit with POST request (more secure) -->
                        <form action="/products/update" method="POST" style="display: inline-block"> <!-- Don't forget to create an update POST route in index.php --> 
                            <input type="hidden" name="id" value="<?php $product['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-primary">UPDATE (POST req)</button>
                        </form>



                        <!-- First: Delete with GET request & Query String (less secure) -->
                        <a href="/products/delete?id=<?php echo htmlspecialchars($product['id']) ?>" class="btn btn-sm btn-outline-danger JSconfirmDeletionAnchor">Delete (GET request)</a> <!-- Don't forget to create a delete GET route in index.php --> 

                        <!-- Second: Delete with POST request (more secure) -->
                        <form action="/products/delete" method="POST" style="display: inline-block"> <!-- Don't forget to create a delete POST route in index.php --> 
                            <input type="hidden" name="id" value="<?php echo $product['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger JSconfirmDeletionButton">Delete (POST request)</button>
                        </form>

                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>


        <!-- Bootstrap Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination pagination-lg justify-content-center">

               <!-- First Button -->
               <li class="page-item <?php echo $productModelObject->currentPageNumber == 1 ? 'disabled' : '' ?>"><a class="page-link" href="/products?page=1"><< First</a></li> <!-- If the current $currentPageNumber is equal to the total $numberOfPages (which means we're on the LAST page), add the Bootstrap CSS class '.disabled' to the 'Next' Button --> 

                <!-- Previous Button -->
                <li class="page-item <?php echo $productModelObject->currentPageNumber == 1 ? 'disabled' : '' ?>"><a class="page-link" href="/products?page=<?php echo $productModelObject->currentPageNumber - 1 ?>">Previous</a></li> <!-- If the current $currentPageNumber is 1 (which is the first page), add the Bootstrap CSS class '.disabled' to the 'Previous' Button --> 

                <?php
                    $startOfLoop = $productModelObject->SQLOffset/$productModelObject->recordsLimitNumberPerPage == 0 ? 1 : $productModelObject->SQLOffset / $productModelObject->recordsLimitNumberPerPage;
                    $endOfLoop   = ($startOfLoop + $productModelObject->numberOfPageLinkButtonsWeDesireToShow - 1) >= $productModelObject->numberOfPages ? $productModelObject->numberOfPages : ($startOfLoop + $productModelObject->numberOfPageLinkButtonsWeDesireToShow - 1);
                    for ($i = $startOfLoop; $i <= $endOfLoop; $i++):
                        
                ?>
                    <li class="page-item <?php echo $productModelObject->currentPageNumber === $i ? 'active' : '' ?>"><a class="page-link" href="/products?page=<?php echo $i ?>"><?php echo $i ?></a></li> <!-- If the current $currentPageNumber is equal to the page number in the foor loop, add the Bootstrap class '.active' to highlight the current page number Bootstrap button --> 
                <?php endfor ?>


                <!-- Next Button -->
                <li class="page-item <?php echo $productModelObject->currentPageNumber == $productModelObject->numberOfPages ? 'disabled' : '' ?>"><a class="page-link" href="/products?page=<?php echo $productModelObject->currentPageNumber + 1 ?>">Next</a></li> <!-- If the current $currentPageNumber is equal to the total $numberOfPages (which means we're on the LAST page), add the Bootstrap CSS class '.disabled' to the 'Next' Button --> 

               <!-- Last Button -->
               <li class="page-item <?php echo $productModelObject->currentPageNumber == $productModelObject->numberOfPages ? 'disabled' : '' ?>"><a class="page-link" href="/products?page=<?php echo $productModelObject->numberOfPages ?>">Last >></a></li> <!-- If the current $currentPageNumber is equal to the total $numberOfPages (which means we're on the LAST page), add the Bootstrap CSS class '.disabled' to the 'Next' Button --> 
            </ul>
        </nav>



        <!-- Importing AJAX Live Search JavaScript file -->
        <script src="/js/products/ajax_live_search.js"></script> 


        <!-- Delete a product confirmation message  -->
        <script>
            // Confirm deletion message for delete product buttons:
            let deleteButtonsArray = document.getElementsByClassName('JSconfirmDeletionButton');
            for (i = 0; i < deleteButtonsArray.length; i++) {
                deleteButtonsArray[i].onclick = function() {
                    return confirm('Are you sure you want to delete product?');
                }
            }

            // For the delete <a> link:
            let deleteAnchorsArray = document.getElementsByClassName('JSconfirmDeletionAnchor');
            for (i = 0; i < deleteAnchorsArray.length; i++) {
                deleteAnchorsArray[i].onclick = function() {
                    return confirm('Are you sure you want to delete product?');
                }
            }
        </script>