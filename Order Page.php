<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Order page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="interface-1 p-3 border rounded">
                    <h5 class="text-center">Order</h5>
                    <form id="orderForm" action="place_order.php" method="POST">
                        <div class="form-group">
                            <label for="pickUpDate">Pick Up Date:</label>
                            <input type="date" id="pickUpDate" name="pickUpDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="pickUpTime">Pick Up Time:</label>
                            <input type="time" id="pickUpTime" name="pickUpTime" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="pickUpAddress">Pick Up Address:</label>
                            <input type="text" id="pickUpAddress" name="pickUpAddress" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="mobileNumber">Mobile Number:</label>
                            <input type="text" id="mobileNumber" name="mobileNumber" class="form-control" required>
                        </div>
                        <div id="serviceContainer">
                            <div class="service">
                                <div class="form-group">
                                    <label for="typeOfService">Type Of Service:</label>
                                    <select class="typeOfService form-control" name="typeOfService[]" required onchange="handleServiceChange(this)">
                                        <option value="express">Express Service</option>
                                        <option value="premium">Premium Service</option>
                                        <option value="luxury">Luxury Service</option>
                                        <option value="exclusive">Exclusive Service</option>
                                    </select>
                                </div>

                                <div class="laundryAmountDiv form-group" style="display: none;">
                                    <label for="amountOfLaundry">Amount of Laundry (in kgs):</label>
                                    <input type="number" class="amountOfLaundry form-control" name="amountOfLaundry[]" min="1" step="1" onchange="calculateTotalAmount()">
                                </div>

                                <div class="exclusiveOptions" style="display: none;">
                                    <div class="exclusiveItemsContainer">
                                        <div class="exclusiveItem">
                                            <div class="form-group">
                                                <label for="exclusiveItemType1">Item Type:</label>
                                                <select class="exclusiveItemType form-control" name="exclusiveItemType[]" onchange="calculateTotalAmount()">
                                                    <option value="carpets">Carpets</option>
                                                    <option value="mats">Mats</option>
                                                    <option value="curtains">Curtains</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="exclusiveItemSize1">Size:</label>
                                                <select class="exclusiveItemSize form-control" name="exclusiveItemSize[]" onchange="calculateTotalAmount()">
                                                    <option value="small">Small</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="large">Large</option>
                                                    <option value="extra large">Extra Large</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="numberOfItems1">Number of Items:</label>
                                                <input type="number" class="numberOfItems form-control" name="numberOfItems[]" min="1" step="1" onchange="calculateTotalAmount()">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary" onclick="addExclusiveItem(this)">Add Another Item</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="interface-2 p-3 border rounded mt-3">
                    <button type="button" class="btn btn-secondary mb-3" onclick="addService()">Add Another Service</button>
                    <div class="form-group">
                       <label for="modeOfPayment">Mode of Payment:</label>
                       <input type="text" id="modeOfPayment" name="modeOfPayment" value="MPESA">
                       <p id="modeOfPayment">MPESA</p>
                    </div>
                   
                    <!-- <div class="form-group">
                     <label for="modeOfPayment">Mode of Payment:</label>
                     <p id="modeOfPayment">MPESA</><p></p>
                    </div> -->

                    <div class="form-group">
                         <label for="totalAmount">Total Amount:</label>
                         <input type="text" id="totalAmount" name="totalAmount" class="form-control" readonly>
                    </div>


                </div>
                
            </div>

            <div class="col-md-6">
                <div class="interface-3 p-3 border rounded">
                    <div class="btn-group mb-3" role="group">
                        <button type="button" class="btn btn-primary" onclick="viewOrder()">View Order</button>
                        <button type="button" class="btn btn-danger" onclick="deleteOrder()">Delete Order</button>
                        <button type="submit" class="btn btn-success" onclick="saveOrder()">Place Order</button>
                        <a href="logout.php" class="btn btn-warning">Log Out</a>
                    </div>
                </div>
                <div class="interface-4 p-3 border rounded mt-3" id="orderDetails" style="display:none;">
                    <h5>Order Details</h5>
                    <pre id="orderDetailsContent"></pre>
                </div>
            </div>
        </div>
    </div>
</body>

</html>


<script>
    function handleServiceChange(selectElement) {
        var serviceDiv = selectElement.closest('.service');
        var selectedService = selectElement.value;
        var laundryAmountDiv = serviceDiv.querySelector('.laundryAmountDiv');
        var exclusiveOptions = serviceDiv.querySelector('.exclusiveOptions');

        if (selectedService === "exclusive") {
            exclusiveOptions.style.display = "block";
            laundryAmountDiv.style.display = "none";
        } else {
            exclusiveOptions.style.display = "none";
            laundryAmountDiv.style.display = "block";
        }

        calculateTotalAmount();
    }

    function calculateTotalAmount() {
        var services = document.querySelectorAll('.service');
        var totalAmount = 0;

        services.forEach(service => {
            var selectedService = service.querySelector('.typeOfService').value;
            if (selectedService === "express") {
                totalAmount += 500; // Fixed price for Express Service
            } else if (selectedService === "premium") {
                totalAmount += 250; // Premium Service charge
            } else if (selectedService === "luxury") {
                totalAmount += 100; // Luxury Service charge
            } else if (selectedService === "exclusive") {
                totalAmount += calculateExclusiveServicePrice(service);
            }

            if (selectedService !== "exclusive") {
                var amountOfLaundry = parseInt(service.querySelector('.amountOfLaundry').value) || 0;
                totalAmount += calculateLaundryCharge(amountOfLaundry);
            }
        });

        document.getElementById("totalAmount").value = "Ksh " + totalAmount;
    }

    function calculateExclusiveServicePrice(serviceDiv) {
        var exclusiveItems = serviceDiv.querySelectorAll('.exclusiveItem');
        var totalExclusivePrice = 0;

        exclusiveItems.forEach(item => {
            var itemType = item.querySelector('.exclusiveItemType').value;
            var itemSize = item.querySelector('.exclusiveItemSize').value;
            var numberOfItems = parseInt(item.querySelector('.numberOfItems').value) || 0;
            var pricePerItem = 0;

            if (itemType === "carpets") {
                if (itemSize === "small") {
                    pricePerItem = 1000;
                } else if (itemSize === "medium") {
                    pricePerItem = 2000;
                } else if (itemSize === "large") {
                    pricePerItem = 3000;
                } else if (itemSize === "extra large") {
                    pricePerItem = 4000;
                }
            } else if (itemType === "mats") {
                if (itemSize === "small") {
                    pricePerItem = 500;
                } else if (itemSize === "medium") {
                    pricePerItem = 1000;
                } else if (itemSize === "large") {
                    pricePerItem = 1500;
                } else if (itemSize === "extra large") {
                    pricePerItem = 2000;
                }
            } else if (itemType === "curtains") {
                if (itemSize === "small") {
                    pricePerItem = 800;
                } else if (itemSize === "medium") {
                    pricePerItem = 1600;
                } else if (itemSize === "large") {
                    pricePerItem = 2400;
                } else if (itemSize === "extra large") {
                    pricePerItem = 3200;
                }
            }

            totalExclusivePrice += pricePerItem * numberOfItems;
        });

        return totalExclusivePrice;
    }

    function calculateLaundryCharge(amount) {
        return Math.ceil(amount / 5) * 500;
    }

    function addExclusiveItem(button) {
        var exclusiveItemsContainer = button.closest('.exclusiveOptions').querySelector('.exclusiveItemsContainer');
        var newItem = document.createElement('div');
        newItem.className = 'exclusiveItem';
        newItem.innerHTML = `
            <div class="form-group">
                <label for="exclusiveItemType">Item Type:</label>
                <select class="exclusiveItemType form-control" name="exclusiveItemType[]" onchange="calculateTotalAmount()">
                    <option value="carpets">Carpets</option>
                    <option value="mats">Mats</option>
                    <option value="curtains">Curtains</option>
                </select>
            </div>
            <div class="form-group">
                <label for="exclusiveItemSize">Size:</label>
                <select class="exclusiveItemSize form-control" name="exclusiveItemSize[]" onchange="calculateTotalAmount()">
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                    <option value="extra large">Extra Large</option>
                </select>
            </div>
            <div class="form-group">
                <label for="numberOfItems">Number of Items:</label>
                <input type="number" class="numberOfItems form-control" name="numberOfItems[]" min="1" step="1" onchange="calculateTotalAmount()">
            </div>
        `;
        exclusiveItemsContainer.appendChild(newItem);
    }

    // function addService() {
    //     var serviceContainer = document.getElementById('serviceContainer');
    //     var newService = document.createElement('div');
    //     newService.className = 'service mt-3';
    //     newService.innerHTML = `
    //         <div class="form-group">
    //             <label for="typeOfService">Type of Service:</label>
    //             <select class="typeOfService form-control" name="typeOfService[]" required onchange="handleServiceChange(this)">
    //                 <option value="express">Express Service</option>
    //                 <option value="premium">Premium Service</option>
    //                 <option value="luxury">Luxury Service</option>
    //                 <option value="exclusive">Exclusive Service</option>
    //             </select>
    //         </div>
    //         <div class="laundryAmountDiv form-group" style="display: none;">
    //             <label for="amountOfLaundry">Amount of Laundry (in kgs):</label>
    //             <input type="number" class="amountOfLaundry form-control" name="amountOfLaundry[]" min="1" step="1" onchange="calculateTotalAmount()">
    //         </div>
    //         <div class="exclusiveOptions" style="display: none;">
    //             <div class="exclusiveItemsContainer">
    //                 <div class="exclusiveItem">
    //                     <div class="form-group">
    //                         <label for="exclusiveItemType">Item Type:</label>
    //                         <select class="exclusiveItemType form-control" name="exclusiveItemType[]" onchange="calculateTotalAmount()">
    //                             <option value="carpets">Carpets</option>
    //                             <option value="mats">Mats</option>
    //                             <option value="curtains">Curtains</option>
    //                         </select>
    //                     </div>
    //                     <div class="form-group">
    //                         <label for="exclusiveItemSize">Size:</label>
    //                         <select class="exclusiveItemSize form-control" name="exclusiveItemSize[]" onchange="calculateTotalAmount()">
    //                             <option value="small">Small</option>
    //                             <option value="medium">Medium</option>
    //                             <option value="large">Large</option>
    //                             <option value="extra large">Extra Large</option>
    //                         </select>
    //                     </div>
    //                     <div class="form-group">
    //                         <label for="numberOfItems">Number of Items:</label>
    //                         <input type="number" class="numberOfItems form-control" name="numberOfItems[]" min="1" step="1" onchange="calculateTotalAmount()">
    //                     </div>
    //                 </div>
    //             </div>
    //             <button type="button" class="btn btn-secondary" onclick="addExclusiveItem(this)">Add Another Item</button>
    //         </div>
    //     `;
    //     serviceContainer.appendChild(newService);
    // }
    function addService() {
    var serviceContainer = document.getElementById('serviceContainer');
    var newService = document.createElement('div');
    newService.className = 'service mt-3';
    newService.innerHTML = `
        <div class="form-group">
            <label for="typeOfService">Type Of Service:</label>
            <select class="typeOfService form-control" name="typeOfService[]" required onchange="handleServiceChange(this)">
                <option value="express">Express Service</option>
                <option value="premium">Premium Service</option>
                <option value="luxury">Luxury Service</option>
                <option value="exclusive">Exclusive Service</option>
            </select>
        </div>
        <div class="laundryAmountDiv form-group" style="display: none;">
            <label for="amountOfLaundry">Amount of Laundry (in kgs):</label>
            <input type="number" class="amountOfLaundry form-control" name="amountOfLaundry[]" min="1" step="1" onchange="calculateTotalAmount()">
        </div>
        <div class="exclusiveOptions" style="display: none;">
            <div class="exclusiveItemsContainer">
                <div class="exclusiveItem">
                    <div class="form-group">
                        <label for="exclusiveItemType">Item Type:</label>
                        <select class="exclusiveItemType form-control" name="exclusiveItemType[]" onchange="calculateTotalAmount()">
                            <option value="carpets">Carpets</option>
                            <option value="mats">Mats</option>
                            <option value="curtains">Curtains</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exclusiveItemSize">Size:</label>
                        <select class="exclusiveItemSize form-control" name="exclusiveItemSize[]" onchange="calculateTotalAmount()">
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                            <option value="extra large">Extra Large</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numberOfItems">Number of Items:</label>
                        <input type="number" class="numberOfItems form-control" name="numberOfItems[]" min="1" step="1" onchange="calculateTotalAmount()">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addExclusiveItem(this)">Add Another Item</button>
        </div>
    `;
    serviceContainer.appendChild(newService);
}


    // function viewOrder() {
    //     var formData = new FormData(document.getElementById("orderForm"));
    //     var orderDetailsContent = document.getElementById("orderDetailsContent");
    //     var detailsHtml = "";

    //     detailsHtml += `<strong>Pick Up Date:</strong> ${formData.get("pickUpDate")}<br>`;
    //     detailsHtml += `<strong>Pick Up Time:</strong> ${formData.get("pickUpTime")}<br>`;
    //     detailsHtml += `<strong>Pick Up Address:</strong> ${formData.get("pickUpAddress")}<br>`;
    //     detailsHtml += `<strong>Mobile Number:</strong> ${formData.get("mobileNumber")}<br>`;
        
    //     var services = document.querySelectorAll('.service');
    //     services.forEach((service, index) => {
    //         var serviceType = service.querySelector('.typeOfService').value;
    //         detailsHtml += `<strong>Service ${index + 1} Type:</strong> ${serviceType}<br>`;
    //         if (serviceType !== "exclusive") {
    //             var amountOfLaundry = service.querySelector('.amountOfLaundry').value;
    //             detailsHtml += `<strong>Service ${index + 1} Amount of Laundry:</strong> ${amountOfLaundry} kgs<br>`;
    //         } else {
    //             var exclusiveItems = service.querySelectorAll('.exclusiveItem');
    //             exclusiveItems.forEach((item, itemIndex) => {
    //                 var itemType = item.querySelector('.exclusiveItemType').value;
    //                 var itemSize = item.querySelector('.exclusiveItemSize').value;
    //                 var numberOfItems = item.querySelector('.numberOfItems').value;
    //                 detailsHtml += `<strong>Service ${index + 1} Exclusive Item ${itemIndex + 1} Type:</strong> ${itemType}<br>`;
    //                 detailsHtml += `<strong>Service ${index + 1} Exclusive Item ${itemIndex + 1} Size:</strong> ${itemSize}<br>`;
    //                 detailsHtml += `<strong>Service ${index + 1} Exclusive Item ${itemIndex + 1} Number of Items:</strong> ${numberOfItems}<br>`;
    //             });
    //         }
    //     });

    //     detailsHtml += `<strong>Mode of Payment:</strong> ${formData.get("modeOfPayment")}<br>`;
    //     detailsHtml += `<strong>Total Amount:</strong> ${document.getElementById("totalAmount").value}<br>`;

    //     orderDetailsContent.innerHTML = detailsHtml;
    //     document.getElementById("orderDetails").style.display = "block";
    // }
    function viewOrder() {
        var formData = new FormData(document.getElementById("orderForm"));
        var orderDetailsContent = document.getElementById("orderDetailsContent");
        var detailsHtml = "";

        detailsHtml += `<strong>Pick Up Date:</strong> ${formData.get("pickUpDate")}<br>`;
        detailsHtml += `<strong>Pick Up Time:</strong> ${formData.get("pickUpTime")}<br>`;
        detailsHtml += `<strong>Pick Up Address:</strong> ${formData.get("pickUpAddress")}<br>`;
        detailsHtml += `<strong>Mobile Number:</strong> ${formData.get("mobileNumber")}<br>`;
        
        var services = document.querySelectorAll('.service');
        services.forEach((service, index) => {
            var serviceType = service.querySelector('.typeOfService').value;
            detailsHtml += `<strong>Service ${index + 1} Type:</strong> ${serviceType}<br>`;
            if (serviceType !== "exclusive") {
                var amountOfLaundry = service.querySelector('.amountOfLaundry').value;
                detailsHtml += `<strong>Service ${index + 1} Amount of Laundry:</strong> ${amountOfLaundry} kgs<br>`;
            } else {
                var exclusiveItems = service.querySelectorAll('.exclusiveItem');
                exclusiveItems.forEach((item, itemIndex) => {
                    var itemType = item.querySelector('.exclusiveItemType').value;
                    var itemSize = item.querySelector('.exclusiveItemSize').value;
                    var numberOfItems = item.querySelector('.numberOfItems').value;
                    detailsHtml += `<strong>Service ${index + 1} Exclusive Item ${itemIndex + 1} Type:</strong> ${itemType}<br>`;
                    detailsHtml += `<strong>Service ${index + 1} Exclusive Item ${itemIndex + 1} Size:</strong> ${itemSize}<br>`;
                    detailsHtml += `<strong>Service ${index + 1} Exclusive Item ${itemIndex + 1} Number of Items:</strong> ${numberOfItems}<br>`;
                });
            }
        });

        // Capture the mode of payment from the static element
        var modeOfPayment = document.getElementById("modeOfPayment").value;
        detailsHtml += `<strong>Mode of Payment:</strong> ${modeOfPayment}<br>`;
        detailsHtml += `<strong>Total Amount:</strong> ${document.getElementById("totalAmount").value}<br>`;

        orderDetailsContent.innerHTML = detailsHtml;
        document.getElementById("orderDetails").style.display = "block";
    }
    function deleteOrder() {
        if (confirm("Are you sure you want to delete the order?")) {
            document.getElementById("orderForm").reset();
            document.getElementById("orderDetails").style.display = "none";
        }
    }
    

    // function saveOrder() {
    //     var orderForm = document.getElementById("orderForm");
    //     orderForm.submit();
        
    // }
    // function saveOrder() {
    //         var orderForm = document.getElementById("orderForm");
    //         var formData = new FormData(orderForm);

    //         fetch('place_order.php', {
    //             method: 'POST',
    //             body: formData
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 showAlert("Order placed successfully!");
    //                 orderForm.reset();
    //             } else {
    //                 showAlert("Failed to place order.");
    //             }
    //         })
    //         .catch(error => {
    //             console.error("Error:", error);
    //             showAlert("An error occurred while placing the order.");
    //         });
    //     }
  function saveOrder() {
    var orderForm = document.getElementById("orderForm");
    var formData = new FormData(orderForm);

    // Manually append modeOfPayment and totalAmount
    // Get the mobile number
    var mobileNumber = document.getElementById("mobileNumber").value;
    var modeOfPayment = document.getElementById("modeOfPayment").value;
    var totalAmount = document.getElementById("totalAmount").value.replace('Ksh ', ''); // Remove 'Ksh ' for correct value
    formData.append("modeOfPayment", modeOfPayment);
    formData.append("totalAmount", totalAmount);

    // Collect the form data into a string for alert
    var formDataEntries = [];
    formData.forEach((value, key) => {
        formDataEntries.push(`${key}: ${value}`);
    });
    var formDataString = formDataEntries.join('\n');

    // Display the form data
    alert("Data being sent to the backend:\n" + formDataString);

    fetch('place_order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert("Order placed successfully!");
            orderForm.reset();
            // Redirect to success page
            window.location.href = `success_page.php?mobileNumber=${encodeURIComponent(mobileNumber)}&totalAmount=${encodeURIComponent(totalAmount)}`;
        } else {
            showAlert("Failed to place order: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        showAlert("An error occurred while placing the order.");
    });

}




</script>
