document.addEventListener('DOMContentLoaded', function() {
    var areaImage = document.getElementById('areaImage');
    var preview = document.getElementById('imagePreview');
    var previewModal = document.getElementById('imagePreviewModal');
    var submitButton = document.getElementById('submitImage');
    var selectImageButton = document.getElementById('selectImage');
    var modal = new bootstrap.Modal(document.getElementById('previewModal'));
    var imageSelected = false;

    areaImage.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                if (previewModal) {
                    previewModal.src = e.target.result;
                }
                submitButton && submitButton.classList.remove('d-none');
                selectImageButton && selectImageButton.classList.remove('d-none');
                modal.show();
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.src = '#';
            if (previewModal) {
                previewModal.src = '#';
            }
            submitButton && submitButton.classList.add('d-none');
            selectImageButton && selectImageButton.classList.add('d-none');
        }
    });

    document.getElementById('previewModal').addEventListener('hidden.bs.modal', function() {
        if (!imageSelected) {
            areaImage.value = '';
            preview.src = '#';
            if (previewModal) {
                previewModal.src = '#';
            }
            submitButton && submitButton.classList.add('d-none');
            selectImageButton && selectImageButton.classList.add('d-none');
        }
        imageSelected = false;
    });

    submitButton && submitButton.addEventListener('click', function() {
        areaImage.closest('form').submit();
    });

    selectImageButton && selectImageButton.addEventListener('click', function() {
        imageSelected = true;
        modal.hide();
    });
});
