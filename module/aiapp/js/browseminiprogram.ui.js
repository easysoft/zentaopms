if(!window.aiBrowseMiniProgram) window.aiBrowseMiniProgram = {};

let firstGenerate = true;
let messageList = [];
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
 *
 * @returns {Map<string, string>}
 */
function getFormValue()
{
    /**
     * @type {Map<string, string>}
     */
    const fieldValueMap = new Map();
    fieldArr.forEach(field =>
    {
        const {id, name} = field;
        let $field = $(`[data-name="${name}"]`);
        if(!$field.length) $field = $(`[name="field-${id}"]`);
        if(!$field.hasClass('picker-box')) fieldValueMap.set(name, $field.prop('value'));
        else                               fieldValueMap.set(name, $field.zui('picker').$.value);
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
 */
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

    const $inputBox = $('.chat-input-box');
    const $sendBtn = $('.send-btn');
    const $messageList = $('.chat-history .message-list');
    const $generateBtn = $('.generate-btn');
    $inputBox.attr('readonly', 'readonly');
    $sendBtn.attr('disabled', 'disabled');
    $generateBtn.attr('disabled', 'disabled');
    $.post(
        postLink,
        formData,
        function (response)
        {
            $inputBox.removeAttr('readonly');
            $sendBtn.removeAttr('disabled');
            $generateBtn.removeAttr('disabled');
            response = JSON.parse(response);
            const {message, result, reason} = response;
            if(result === 'success')
            {
                const {content, time} = message;
                const $message = createMessage('ai', content, time);
                $messageList.find('.tmp-message').remove();
                $messageList.append($message);
                $messageList[0].scrollTo(0, $messageList[0].scrollHeight);

                messageList.append(curMsgObj);
                messageList.append(message);
                return;
            }

            if(reason === 'no model')
            {
                $('.chat').addClass('hidden');
                $('.chat-nomodel').removeClass('hidden');
                $('form .footer .btn.primary').attr('disabled', 'disabled');
                return;
            }
            if(reason === 'unpublished')
            {
                $('#open-dialog').trigger('click')
                return;
            }
            if(reason === 'no response')
            {
                const $message = createMessage('ai', message);
                $messageList.find('.tmp-message').remove();
                $messageList.append($message);
                $messageList[0].scrollTo(0, $messageList[0].scrollHeight);
                return;
            }
        }
    );

    const $message = createMessage('user', message);
    $messageList.append($message);
    $messageList.append(createTmpMessage());
    $messageList[0].scrollTo(0, $messageList[0].scrollHeight);
}

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
    else
    {
        const $messageList = $('.chat-history .message-list');
        $messageList.append(createLocalNotification(`${formatDateTime()} ${clearContextLang}`));
        messageList = [];
    }
    $('.chat').addClass('hidden');
    $('.chat-history').removeClass('hidden');
    $('.input-container').removeClass('hidden');
    sendMessagesToAI(promptStr);
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

let composing = false;

/**
 * Handle input box enter.
 *
 * @param {KeyboardEvent} event
 */
window.aiBrowseMiniProgram.handleInputEnter = function(event)
{
    if(event.code === 'Enter' && !composing)
    {
        event.preventDefault();
        window.aiBrowseMiniProgram.clearInputAndChat();
    }
};

window.aiBrowseMiniProgram.clearInputAndChat = function()
{
    const $inputBox = $('.chat-input-box');
    const message = $inputBox.val()
    $inputBox.val('');
    if(!message) return;
    sendMessagesToAI(message);
};

window.aiBrowseMiniProgram.handleInputCompositionStart = function()
{
    composing = true;
};

window.aiBrowseMiniProgram.handleInputCompositionEnd = function()
{
    composing = false;
};

/**
 * Rest input box height.
 *
 * @param {Event} event
 */
window.aiBrowseMiniProgram.resetInputHeight = function(event)
{
    /**
     * @type {HTMLTextAreaElement}
     */
    const inputBox = event.target;
    inputBox.style.height = 'auto';
    inputBox.style.height = `${(inputBox.scrollHeight + 2)}px`
};

window.aiBrowseMiniProgram.backToSquare = function()
{
    window.location.href = $.createLink('aiapp', 'square');
};

/**
 * Create message avatar.
 *
 * @param {'ai'|'user'} role
 * @returns {jQuery}
 */
function createAvatar(role)
{
    const $avatar = role === 'user'
        ? $('#userMenu-toggle > div.avatar').clone()
        : $('#program-avatar').clone().removeAttr('id');
    if(role === 'ai')
    {
        $avatar.find('svg')
            .attr('height', '14px')
            .attr('width', '14px')
    }
    $avatar.addClass('message-avatar');
    return $avatar;
}

/**
 * Create message time.
 *
 * @param {string} time
 * @returns {jQuery}
 */
function createMessageTime(time)
{
    return $(`<div class="message-time">${time}</div>`);
}

/**
 * Create message content.
 *
 * @param {'user'|'ai'} role
 * @param {string} content
 * @returns {jQuery}
 */
function createMessageContent(role, content)
{
    content = content.replace(/\n{2,}/g, '\n');
    return $(`<pre class="message-content ${role}-message-content">${content}</pre>`);
}

/**
 * Create message body.
 *
 * @param {'user'|'ai'} role
 * @param {string} content
 * @param {string} time
 * @returns {jQuery}
 */
function createMessageBody(role, content, time)
{
    const $time = createMessageTime(time);
    const $content = createMessageContent(role, content);
    return $(`<div class="message-body"></div>`)
        .append($time)
        .append($content);
}

function createTmpMessage()
{
    const $avatar = createAvatar('ai');
    const $body = $(`<div class="message-body flex-1">
        <div class="animate-pulse">
            <div class="h-3 w-24 loading-bar mb-1.5 rounded"></div>
        </div>
        <div class="message-content ai-message-content w-full">
            <div class="animate-pulse w-full">
                <div class="h-3 w-full loading-bar mb-2 rounded"></div>
                <div class="h-3 w-full loading-bar mb-2 rounded"></div>
                <div class="h-3 w-1/2 loading-bar rounded"></div>
            </div>
        </div>
    </div>`);
    return $(`<div class="message ai-message tmp-message"></div>`)
        .append($avatar)
        .append($body);
}

/**
 * Create message.
 *
 * @param {'user'|'ai'} role
 * @param {string} content
 * @param {string} time
 * @returns {jQuery}
 */
function createMessage(role, content, time = formatDateTime())
{
    const $avatar = createAvatar(role);
    const $body = createMessageBody(role, content, time);
    return $(`<div class="message ${role}-message"></div>`)
        .append($avatar)
        .append($body);
}

/**
 * Create local notification.
 *
 * @param {string} content
 * @returns {jQuery}
 */
function createLocalNotification(content)
{
    return $(`<div class="local-notification"><span>${content}</span></div>`);
}

/**
 * Create server notification.
 *
 * @param {string} content
 * @returns {jQuery}
 */
function createServerNotification(content)
{
    return $(`<div class="server-notification">${content}</div>`);
}

/**
 * Append current path.
 */
function appendCurrentPath()
{
    $('#heading .toolbar')
        .append(`<i class="icon icon-angle-right"></i>`)
        .append(`<span class="ml-2">${pathname}</span>`);
}

$(function()
{
    appendCurrentPath();
    $('#to-language-model').prop('href', $.createLink('ai', 'models'));
    $('#reload-current').on('click', () =>
    {
        loadCurrentPage('.mini-program');
    });

    if(messages && messages.length)
    {
        const $messageList = $('.chat-history .message-list');
        const messagesReverse = messages.reverse();
        for(let i = 0; i < messagesReverse.length; i++)
        {
            const message = messagesReverse[i];
            const {type, content, createdDate: time} = message;
            let role;
            if(type === 'req') role = 'user';
            else if(type === 'res') role = 'ai';
            const $message = (role === 'user' || role === 'ai')
                ? createMessage(role, content, time)
                : createServerNotification(content);
            $messageList.append($message);
        }

        $messageList.append(createLocalNotification(`${formatDateTime()} ${newChatTip}`));
        $messageList[0].scrollTo(0, $messageList[0].scrollHeight);
    }

    if(isAppDisabled)
    {
        $('#open-dialog').trigger('click')
    }

    const $inputBox = $('.chat-input-box');
    if(!$inputBox.attr('data-bind'))
    {
        $inputBox
            .on('input', window.aiBrowseMiniProgram.resetInputHeight)
            .on('keydown', window.aiBrowseMiniProgram.handleInputEnter)
            .on('compositionstart', window.aiBrowseMiniProgram.handleInputCompositionStart)
            .on('compositionend', window.aiBrowseMiniProgram.handleInputCompositionEnd);

        $inputBox.attr('data-bind', 'true');
    }

    if(!hasModels)
    {
        $('.chat').addClass('hidden');
        $('.chat-nomodel').removeClass('hidden');
        $('form .footer .btn.primary').attr('disabled', 'disabled');
        $('.generate-btn').attr('disabled', 'disabled');
    }
});
