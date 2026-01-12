if(!window.aiBrowseMiniProgram) window.aiBrowseMiniProgram = {};

let firstGenerate = true;
let aiChatView    = null;
const fieldArr = Array.from(Object.values(fields));

/**
 * Format datetime.
 *
 * @param {Date?} date
 * @returns {string}
 */
function formatDateTime(date = new Date())
{
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();
    const hours = date.getHours();
    const minutes = date.getMinutes();

    const formattedDate = `${year}/${month}/${day}`;
    const formattedTime = `${hours}:${minutes < 10 ? '0' + minutes : minutes}`;

    return `${formattedDate} ${formattedTime}`;
}

/**
 * Get form value map.
 */
function getFormValue()
{
    const fieldValueMap = new Map();
    fieldArr.forEach(field =>
    {
        const {id, name} = field;
        let $field = $(`[name="field-${id}"]`);
        if(!$field.length) $field = $(`.form-group[data-name="${name}"]`).find('input,textarea,select');
        const picker = zui.FormHelper.globalControls.picker($field, $('#miniProgramForm'));
        fieldValueMap.set(name, picker ? picker.getVal() : $field.prop('value'));
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
    return fieldArr
        .filter(field => field.required === '1')
        .map(field => field.name);
}

/**
 * Generate prompt string.
 *
 * @param {Map<string, string>} fieldArr
 * @returns {string}
 */
function generatePrompt(fieldArr)
{
    let promptStr = prompt;
    fieldArr.forEach((value, key) =>
    {
        promptStr = promptStr.replace(new RegExp(`\\s&lt;${key}&gt;\\s`, 'g'), value);
        promptStr = promptStr.replace(new RegExp(`\\s<${key}>\\s`, 'g'), value);
    });
    return promptStr;
}

/**
 * Check required fields.
 *
 * @param {string[]} requiredFieldNames
 * @param {Map<string, string>} fieldArr
 * @returns {true|string}
 */
function checkRequiredFields(requiredFieldNames, fieldArr)
{
    for(const name of requiredFieldNames)
    {
        if(!fieldArr.has(name) || !fieldArr.get(name)) return name;
    }
    return true;
}

/**
 * Clear error tip.
 */
function clearErrorTip()
{
    $('.form-container').find('.has-error').removeClass('has-error');
    $('.form-container').find('.form-tip').remove();
}

/**
 * Send message to ai.
 *
 * @param {string} message
 * @param {boolean} renew
 */
function sendMessagesToAI(message, renew)
{
    const postOptions = {content: message, chat: {userPrompt: message, type: 'miniprogram', contexts: [{code: 'zentao-knowledgeLibs', data: {memory: {collections: (knowledgeLibs || '').split(',').filter(Boolean).map(x => `zentao:${x}`)}}}]}};
    aiChatView.$.postMessage(postOptions);
}

window.aiBrowseMiniProgram.initAIChatView = function(options)
{
    const $aiChatView = $('#aiChatView');
    aiChatView = parent.zui.create('AIChatView', $aiChatView, $.extend({$notDestroyOnDetach: true}, options, {isEmbed: true}));
    if(!parent.isZaiOK) $('.generate-btn').addClass('disabled');
};

window.aiBrowseMiniProgram.startAIChat = function()
{
    clearErrorTip();
    const fieldArr = getFormValue();
    const requiredFieldNames = getRequiredFields();
    const result = checkRequiredFields(requiredFieldNames, fieldArr);
    if(typeof result === 'string')
    {
        const $container = $('.form-container').find(`[data-name="${result}"]`);
        const fid = $container.attr('data-fid');
        const $formGroup = $container.closest('.form-group');
        $formGroup
            .attr('name', `field-${fid}`)
            .addClass('has-error')
            .append(`<div class="form-tip" id="field-${fid}Tip">${emptyNameWarning.replace('%s', result)}</div>`);
        return;
    }

    const promptStr = generatePrompt(fieldArr);
    if(firstGenerate)
    {
        $(this).text(regenerateLang);
        firstGenerate = false;
    }
    sendMessagesToAI(promptStr, true);
};

window.aiBrowseMiniProgram.handleStarBtnClick = function()
{
    const $btn = $(this);
    const url = $btn.attr('data-url');
    $.get(url, function (response)
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

window.aiBrowseMiniProgram.handleRestBtnClick = function()
{
    try
    {
        $('.form-container .picker-box').each(function()
        {
            $(this).zui('picker').$.clear();
        });
        $('.form-container .form-group .form-field').val('');
    }
    catch (error) {}
};

window.aiBrowseMiniProgram.backToSquare = function()
{
    window.location.href = $.createLink('aiapp', 'square');
};

window.onPageUnmount = function()
{
    if(aiChatView) aiChatView.destroy();
};

$(function()
{
    $('#reload-current').on('click', () =>
    {
        loadCurrentPage('.mini-program');
    });
});
