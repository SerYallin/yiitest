(function ($, yii) {
  $(document).ready(function () {
    $('[data-id="addRequestBtn"]').on("click", function (e) {
      e.preventDefault();
      $.ajax({
        url: e.target.href,
        type: "GET",
        success(data) {
          $("#modalContent").html(data);
          $("#modal").modal("show");
          $(document).trigger("modalOpen");
        },
      });
    });

    function chacnheStatus() {
      $('[name="status"]').on("change", function (e) {
        element = e.target;
        $.ajax({
          url: element.dataset.action,
          type: "POST",
          data: {
            status: element.value,
            _csrf: yii.getCsrfToken()
          },
          success(data) {
            element.dataset.value = element.value;
          },
          error(error) {
            e.target.value = e.target.dataset.value;
            console.error(error.responseText);
          },
        });
      });
    }

    // I'm not using the data-pjax attribute because it doesn't work correctly.
    function deleteButtonEvent() {
      $(".btn-delete").on("click.pjax", function (e) {
        e.preventDefault();
        // Disable firing the yii js click event on data-confirm links.
        e.stopPropagation();
        const element =
          e.target.tagName === "A" ? e.target : e.target.parentElement;
        const confirmText = element.dataset.confirm || "Вы уверены?";
        if (!confirm(confirmText)) {
          return;
        }
        $.ajax({
          url: element.href,
          type: "POST",
          data: { _csrf: yii.getCsrfToken() },
          success(data) {
            $.pjax.reload({ container: "#requests-list" });
          },
          error(error) {
            console.error(error.responseText);
          },
        });
      });
    }

    $(document).on("modalOpen", function () {
      $("#create-form").on("beforeSubmit", function (e) {
        e.preventDefault();

        const form = $(this);
        $.ajax({
          url: form.attr("action"),
          type: "post",
          data: form.serialize(),
          success(data) {
            $("#modal").modal("hide");
            form[0].reset();
            $.pjax.reload({ container: "#requests-list" });
          },
          error(data) {
            console.error("Произошла ошибка при отправке формы.");
          },
        });
        return false;
      });
    });

    deleteButtonEvent();
    chacnheStatus();

    $("#requests-list").on("pjax:end", function () {
      chacnheStatus();
      deleteButtonEvent();
    });
  });
})(window.jQuery, window.yii);