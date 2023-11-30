if(!window.aiMiniProgramChat) window.aiMiniProgramChat = {};

function getFormValue()
{
    /**
     * @type {Map<string, string>}
     */
    const fieldValueMap = new Map();
    fieldNames.forEach(name =>
    {
        const $field = $(`[data-name="${name}"]`);
        if(!$field.hasClass('picker-box'))
        {
            fieldValueMap.set(name, $field.prop('value'));
        }
        else
        {
            fieldValueMap.set(name, $field.zui('picker').$.value);
        }
    });
    return fieldValueMap;
}

function generatePrompt()
{
    let promptStr = prompt;
    const map = getFormValue();
    map.forEach((value, key) =>
    {
        promptStr = promptStr.replace(new RegExp(`\\s&lt;${key}&gt;\\s`, 'g'), value);
    });
    return promptStr;
}

window.aiMiniProgramChat.startAIChat = function()
{
    const promptStr = generatePrompt();
    console.log(promptStr);
};
