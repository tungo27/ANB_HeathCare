// File JS dùng chung cho Module Bác sĩ
document.addEventListener("DOMContentLoaded", function () {
    const deleteForms = document.querySelectorAll(".delete-form");

    deleteForms.forEach((form) => {
        form.addEventListener("submit", function (event) {
            if (
                !confirm(
                    "Bạn có chắc chắn muốn xóa bác sĩ này? Hành động này không thể hoàn tác.",
                )
            ) {
                event.preventDefault();
            }
        });
    });
});
