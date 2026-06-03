// Nút toggle con mắt trong password (nó giống như ở login ấy)
function togglePassword(eye) {
    const passwordBox = eye.closest(".password-box");
    const input = passwordBox ? passwordBox.querySelector("input") : eye.previousElementSibling;
    const icon = eye.querySelector("img");

    if (!input || !icon) {
        return;
    }

    if (input.type === "password") {
        icon.src = "../img/eye_open.png";
        input.type = "text";
        eye.setAttribute("aria-label", "Ẩn mật khẩu");
    } else {
        icon.src = "../img/eye_close.png";
        input.type = "password";
        eye.setAttribute("aria-label", "Hiển thị mật khẩu");
    }
}


//Hiển thị cửa sổ xác nhận khi bấm nút xóa tài khoản (UI)
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".password-box .eye-toggle").forEach(function (button) {
        button.addEventListener("click", function () {
            togglePassword(button);
        });
    });

    const popup = document.getElementById("delete-popup");
    const openBtn = document.getElementById("open-delete-popup");
    const closeBtn = document.getElementById("close-delete-popup");
    const cancelBtn = document.getElementById("cancel-delete-popup");

    if (!popup || !openBtn) {
        return;
    }

    function openPopup() {
        popup.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closePopup() {
        popup.classList.remove("show");
        document.body.style.overflow = "";
    }

    openBtn.addEventListener("click", openPopup);

    if (closeBtn) {
        closeBtn.addEventListener("click", closePopup);
    }

    if (cancelBtn) {
        cancelBtn.addEventListener("click", closePopup);
    }

    popup.addEventListener("click", function (event) {
        if (event.target === popup) {
            closePopup();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && popup.classList.contains("show")) {
            closePopup();
        }
    });
});
