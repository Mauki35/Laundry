const form = document.querySelector("#orderForm"),
    continueBtn = form.querySelector("#submitOrder");

form.onsubmit = (e) => {
    e.preventDefault();
}

continueBtn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "place_order.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                if (data === "success") {
                    alert("Order successfully placed!");
                    window.location.href = "success_page.php";
                } else {
                    alert("Order NOT successfully placed!");
                }
            }
        }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}