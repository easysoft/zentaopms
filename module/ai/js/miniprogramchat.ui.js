if(!window.aiMiniProgramChat) window.aiMiniProgramChat = {};

let firstGenerate = true;
const messageList = [];

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

function sendMessagesToAI(message)
{
    const curMsgObj = {
        role: 'user',
        content: message,
        time: (new Date()).toLocaleString()
    };

    const formData = new FormData();
    formData.set('message', message);
    if(messageList.length) formData.set('messages', messageList);

    const $messageList = $('.chat-history .message-list');
    $.post(
        postLink,
        formData,
        function(response) {
            console.log(JSON.parse(response));
            response = JSON.parse(response);
            const {content, time} = response.message;
            const $message = createMessage('ai', content, time);
            $messageList.append($message);
            $messageList[0].scrollTo(0, $messageList[0].scrollHeight);

            messageList.append(curMsgObj);
            messageList.append(response.message);
        }
    );

    const $message = createMessage('user', message);
    $messageList.append($message);
    $messageList[0].scrollTo(0, $messageList[0].scrollHeight);
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
    if(firstGenerate)
    {
        $(this).text(regenerateLang);
        firstGenerate = false;
    }
    sendMessagesToAI(promptStr);
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

/**
 *
 * @param {KeyboardEvent} event
 */
window.aiMiniProgramChat.handleInputEnter = (event) =>
{
    console.log(event.code);
    if (event.code === 'Enter') {
        event.preventDefault();
        window.aiMiniProgramChat.clearInputAndChat();
    }
};

window.aiMiniProgramChat.clearInputAndChat = () =>
{
    const $inputBox = $('.chat-input-box');
    const message = $inputBox.val()
    $inputBox.val('');
    sendMessagesToAI(message);
}

/**
 *
 * @param {'ai'|'user'} type
 * @returns {jQuery}
 */
function createAvatar(type)
{
    const $avatar = type === 'user'
        ? $('#userMenu-toggle > div.avatar').clone()
        : $('#program-avatar').clone().removeAttr('id');
    $avatar.addClass('message-avatar');
    return $avatar;
}

/**
 *
 * @param {string} time
 * @returns {jQuery}
 */
function createMessageTime(time)
{
    return $(`<div class="message-time">${time}</div>`);
}

/**
 *
 * @param {'user'|'ai'} type
 * @param {string} content
 * @returns {jQuery}
 */
function createMessageContent(type, content)
{
    return $(`<div class="message-content ${type}-message-content">${content}</div>`);
}

/**
 *
 * @param {'user'|'ai'} type
 * @param {string} content
 * @param {string} time
 * @returns {jQuery}
 */
function createMessageBody(type, content, time)
{
    const $time = createMessageTime(time);
    const $content = createMessageContent(type, content);
    return $(`<div class="message-body"></div>`)
        .append($time)
        .append($content);
}

/**
 *
 * @param {'user'|'ai'} type
 * @param {string} content
 * @param {string} time
 * @returns {jQuery}
 */
function createMessage(type, content, time = (new Date).toLocaleString())
{
    const $avatar = createAvatar(type);
    const $body = createMessageBody(type, content, time);
    return $(`<div class="message ${type}-message"></div>`)
        .append($avatar)
        .append($body);
}
