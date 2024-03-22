(function (){
    console.log('assistantcreate.js loaded');
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "publish";
    input.value = "true";

    const form = document.getElementById('assistant-form');

    console.log(form);
    const submitButton = document.getElementById('save-assistant-button');
    const publishButton = document.getElementById('save-publish-assistant-button');
    publishButton.addEventListener('click', function(e){
        console.log('publish button clicked');
        form.appendChild(input);
        form.submit();
    });
})();
