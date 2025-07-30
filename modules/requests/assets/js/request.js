(function ($, yii) {
  $(document).ready(function() {
    function uploadFormChange() {
      let filesArray = [];
      function updateFileInput(input) {
        const dt = new DataTransfer();
        filesArray.forEach((file) => dt.items.add(file));
        input.files = dt.files;
      }
      function updatePreview(form, input) {
        const filePreview = $(form).find(".files-preview");
        filePreview.empty();
        filesArray.forEach((file, index) => {
          const img = document.createElement("img");
          img.src = URL.createObjectURL(file);
          const link = document.createElement("a");
          link.href = "#";
          link.onclick = function (e) {
            e.preventDefault();
            filesArray.splice(index, 1);
            updateFileInput(input);
            updatePreview(form, input);
          };
          link.append(img);
          filePreview.append(link);
        });
      }
      $("#images-upload-form").on("change", function (e) {
        const input = e.target;
        const newFiles = Array.from(e.target.files);
        filesArray = filesArray.concat(newFiles);
        updatePreview(this, input);
      });
    }

    function deleteImage() {
      $(".btn-delete").on("click.pjax", function (e) {
        e.preventDefault();
        // Disable firing the yii js click event on data-confirm links.
        e.stopPropagation();
        const element = e.target;
        const confirmText = element.dataset.confirm || "Вы уверены?";
        if (!confirm(confirmText)) {
          return;
        }
        $.ajax({
          url: element.href,
          type: "POST",
          data: { _csrf: yii.getCsrfToken() },
          success(data) {
            if (data?.status === "success") {
              $.pjax.reload({ container: "#images-list" });
            }
          },
          error(error) {
            console.error(error.responseText);
          },
        });
      });
    }
    function reinitPjaxForm(container, formSelector, options) {
      const defOpt = {
        push: false,
        replace: false,
        timeout: 1000,
        scrollTo: false,
        container,
      };
      const opt = { ...defOpt, ...options };
      $(document)
        .off("submit", `${container} ${formSelector}`)
        .on("submit", `${container} ${formSelector}`, function (event) {
          $.pjax.submit(event, opt);
        });
    }
    uploadFormChange();
    deleteImage();
    // Update images list on pjax end
    $("#upload-images-form").on("pjax:end", function () {
      $.pjax.reload({ container: "#images-list" });
      uploadFormChange();
      reinitPjaxForm("#upload-images-form", "form[data-pjax]");
    });
    // Update comment list on pjax end
    $("#add-comment-form").on("pjax:end", function () {
      $.pjax.reload({ container: "#comments-list" });
      reinitPjaxForm("#add-comment-form", "form[data-pjax]");
    });
    $("#images-list").on("pjax:end", function () {
      deleteImage();
    });
  });
})(window.jQuery, window.yii);
