// This is AJAX Live Search in Products index page
let AJAXLiveSearchInputField = document.getElementById('AJAX_live_search_input_field'); // the DOM element we want to apply the AJAX request on (upon an 'event')

AJAXLiveSearchInputField.onkeyup = function() {
    let myAJAXRequestObject = new XMLHttpRequest();

    myAJAXRequestObject.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let searchResultsResponseFromJSONToJavaScriptObject = JSON.parse(this.responseText); // Convert JSON string (body) to JavaScript object
            let myText = ''; // loop results will be added (CONCATENATED) to 'myText' variable
            let index = 0; // Because the for .. in .. loop in JavaScript doesn't provide the loop index
            for (singleSearchResult of searchResultsResponseFromJSONToJavaScriptObject) { // looping through the .responseText
                index++;

                myText += `
                    <tr>
                        <th scope="row">${index}</th>
                        <td>${singleSearchResult.image_path ? `<img class="thumb-image" src="/${singleSearchResult.image_path}">` : ''}</td>
                        <td>${singleSearchResult.title}</td>
                        <td>${singleSearchResult.description}</td>
                        <td>${singleSearchResult.price}</td>
                        <td>${singleSearchResult.create_date}</td>
                        <td>
                            <a href="/products/update?id=${singleSearchResult.id}" class="btn btn-sm btn-outline-primary" style="margin: 3px">UPDATE (GET req)</a>
                            <a href="/products/delete?id=${singleSearchResult.id}" class="btn btn-sm btn-outline-danger">Delete (GET request)</a>
                        </td>
                    </tr>
                `;
            } // End of for loop


            document.getElementById('table_body_for_AJAX').innerHTML = myText; // As we said earlier, grabbing DOM elements MUST be INSIDE the 'event' (which is .onkeyup in this case), i.e. you can't assign the DOM element outside the 'event' to a variable and then use that variable inside the 'even't, because then the whole thing won't work because grabbing the DOM element must be refreshed inside the 'even't whenever grabbed
        } // End of if statement
    }; // End of .onreadystatechange 'event'
    
    let searchedForValue    = AJAXLiveSearchInputField.value;

    myAJAXRequestObject.open('GET', '/products/ajaxlivesearch?ajaxsearchvalue=' + searchedForValue, true); // check the route in the Entry Script page i.e. index.php    // Here we send the AJAX request (call) to the ProductsController.php page (and that hits the ajaxLiveSearch() method) depending on the route we specified in the Entry Script page (index.php) which is:    $app->router->storeGETRoutes( '/products/ajaxlivesearch' , [ProductsController::class, 'ajaxLiveSearch'] ); // AJAX Live Search (this route will be accessed by AJAX requests from `products` index.php page which 'include's a JavaScript file named 'ajax_live_search.js')
    myAJAXRequestObject.send();
}; // End of .onkeyup 'event'