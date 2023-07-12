(function()
{
    let currentPrompt = null;
    const actions = document.querySelectorAll('.c-actions');
    for(let action of actions)
    {
        action.addEventListener('click', function()
        {
            currentPrompt = action.getAttribute('data-prompt-id');
        });
    }

    const goDesignButton = document.getElementById('goDesignButton');
    if(goDesignButton)
    {
        goDesignButton.addEventListener('click', function()
        {
            if(currentPrompt)
            {
                location.href = createLink('ai', 'promptassignrole', `prompt=${currentPrompt}`);
            }
        });
    }

    const draftPromptButton = document.getElementById('draftPromptButton');
    if(draftPromptButton)
    {
        draftPromptButton.addEventListener('click', function()
        {
            if(currentPrompt)
            {
                togglePromptStatus(currentPrompt);
            }
        });
    }
})();

function togglePromptStatus(id)
{
    const form = document.createElement("form");
    form.method = 'post';
    form.action = createLink('ai', 'prompts');

    const promptId = document.createElement('input');
    promptId.type = 'hidden';
    promptId.name = 'promptId';
    promptId.value = id;
    form.appendChild(promptId);

    const publish = document.createElement('input');
    publish.type = 'hidden';
    publish.name = 'togglePromptStatus';
    publish.value = '1';
    form.appendChild(publish);

    document.body.appendChild(form);
    form.submit();
}
