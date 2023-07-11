(function () {
    const form = document.querySelector('#mainForm');
    if (form) {
        form.addEventListener('input', function () {
            isPromptDesignDirty = true;
        });
    }
})();
