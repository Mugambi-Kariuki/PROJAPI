document.addEventListener("DOMContentLoaded", function () {
    let messages = document.querySelectorAll(".message");
    messages.forEach((msg) => {
        setTimeout(() => {
            msg.style.display = "none";
        }, 5000);
    });
});
