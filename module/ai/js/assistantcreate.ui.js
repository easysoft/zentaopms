(function (){
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "publish";
    input.value = "true";

    const form = document.getElementById('assistant-form');

    const submitButton = document.getElementById('save-assistant-button');
    const publishButton = document.getElementById('save-publish-assistant-button');
    publishButton.addEventListener('click', function(e){
        form.appendChild(input);
        submitButton.click();
        form.removeChild(input);
    });
})();
