if(!window.aiMiniProgramChat) window.aiMiniProgramChat = {};

let firstGenerate = true;

function getFormValue()
{
    /**
     * @type {Map<string, string>}
     */
    const fieldValueMap = new Map();
    fields.forEach(field =>
    {
        const {name} = field;
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

/**
 * Get required fields.
 *
 * @returns {string[]}
 */
function getRequiredFields()
{
    return fields
        .filter(field => field.required === '1')
        .map(field => field.name);
}

/**
 * Generate prompt string.
 *
 * @param {Map<string, string>} fields
 * @returns {string}
 */
function generatePrompt(fields)
{
    let promptStr = prompt;
    fields.forEach((value, key) =>
    {
        promptStr = promptStr.replace(new RegExp(`\\s&lt;${key}&gt;\\s`, 'g'), value);
    });
    return promptStr;
}

/**
 * Check required fields.
 *
 * @param {string[]} requiredFieldNames
 * @param {Map<string, string>} fields
 * @returns {true|string}
 */
function checkRequiredFields(requiredFieldNames, fields)
{
    for(const name of requiredFieldNames)
    {
        if(!fields.has(name) || !fields.get(name)) return name;
    }
    return true;
}

function clearErrorTip()
{
    $('.form-container').find('.has-error').removeClass('has-error');
    $('.form-container').find('.form-tip').remove();
}

window.aiMiniProgramChat.startAIChat = function()
{
    clearErrorTip();
    const fields = getFormValue();
    const requiredFieldNames = getRequiredFields();
    const result = checkRequiredFields(requiredFieldNames, fields);
    if(typeof result === 'string')
    {
        const $formGroup = $('.form-container').find(`[data-name="${result}"]`).closest('.form-group');
        $formGroup
            .addClass('has-error')
            .append(`<div class="form-tip">${emptyNameWarning.replace('%s', result)}</div>`);
        return;
    }

    const promptStr = generatePrompt(fields);
    console.log(promptStr);
    if(firstGenerate)
    {
        $(this).text(regenerateLang);
        firstGenerate = false;
    }
};

window.aiMiniProgramChat.handleStarBtnClick = function()
{
    const $btn = $(this);
    const url = $btn.attr('data-url');
    $.get(url, function(response)
    {
        if(response.status == '1')
        {
            $btn.children('img').attr('src', 'static/svg/star.svg');
            $btn.attr('data-url', $btn.attr('data-url').replace('false', 'true'));
        }
        else
        {
            $btn.children('img').attr('src', 'static/svg/star-empty.svg');
            $btn.attr('data-url', $btn.attr('data-url').replace('true', 'false'));
        }
    }, 'json');
};

window.aiMiniProgramChat.handleRestBtnClick = function()
{
    try
    {
        $('.form-container .picker-box').each(function()
        {
            $(this).zui('picker').$.clear();
        });
        $('.form-container .form-group > input[data-name]').val('');
        $('.form-container .form-group > textarea[data-name]').val('');
    }
    catch (error) {}
}
