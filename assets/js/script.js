document.addEventListener("DOMContentLoaded", function () {
    const loginframe = document.querySelector(".loginframe");
    const registerLink = document.querySelector(".registerlink");
    const loginLink = document.querySelector(".loginlink");

    registerLink.addEventListener("click", (e) => {
        e.preventDefault();
        loginframe.classList.add("active");
    });

    loginLink.addEventListener("click", (e) => {
        e.preventDefault();
        loginframe.classList.remove("active");
    });
});