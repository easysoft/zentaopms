function bindEditEvent() {
    const editButton = document.getElementById('edit-button');
    const saveButton = document.getElementById('save-button');
    const cancelButton = document.getElementById('cancel-button');
    const enabledCheckbox = document.getElementsByName('enabled')[0];
    const domainInput = document.getElementsByName('domain')[0];

    if (!(editButton && saveButton && cancelButton && enabledCheckbox && domainInput)) {
        return;
    }
    editButton.addEventListener('click', function () {
        editButton.style.display = 'none';
        saveButton.style.display = 'inline-block';
        cancelButton.style.display = 'inline-block';
        enabledCheckbox.disabled = false;
        enabledCheckbox.parentElement.classList.remove('disabled');
        domainInput.disabled = false;
    });
    cancelButton.addEventListener('click', function () {
        editButton.style.display = 'inline-block';
        saveButton.style.display = 'none';
        cancelButton.style.display = 'none';
        enabledCheckbox.disabled = true;
        enabledCheckbox.parentElement.classList.add('disabled');
        domainInput.disabled = true;
    });
}
bindEditEvent();

window.afterPageUpdate = bindEditEvent;