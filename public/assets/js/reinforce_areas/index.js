function toggleSaveButton(select, areaId, currentStatus) {
    const saveButton = document.getElementById('save-button-' + areaId);
    if (parseInt(select.value) !== currentStatus) {
        saveButton.classList.remove('d-none');
    } else {
        saveButton.classList.add('d-none');
    }
}
