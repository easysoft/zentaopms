/**
 * @type {Map<string, string>}
 */
const words = new Map();

/**
 *  format prompt input string.
 *
 * @param {string} content prompt content.
 * @returns {string} result.
 */
function formatContent(content)
{
    if(words.size === 0) return content;
    Array.from(words.keys()).forEach(word => { content = content.replace(new RegExp(`\\s<${word}>\\s`, 'g'), `&nbsp;<strong class="text-primary">&lt;${word}&gt;</strong>&nbsp;`); });
    return content;
}

/**
 * Update prompt preview.
 *
 * @returns {string} result
 */
function updatePromptPreview()
{
    const innerText = $('#autocomplete-textarea').text();
    let innerHTML = formatContent(innerText);
    $('#autocomplete-textarea').html(innerHTML);
    if(words.size === 0)
    {
        $('.prompt-preview-area .preview-container').html(innerHTML);
        return;
    }

    const regex = /<strong\sclass="text-primary">&lt;([^>]+)&gt;<\/strong>&nbsp;/g;
    const matches = innerHTML.match(regex);
    if(!matches)
    {
        $('.prompt-preview-area .preview-container').html(innerHTML);
        return;
    }

    matches.forEach((match) =>
    {
        const result = match.match(/<strong\sclass="text-primary">&lt;([^>]+)&gt;<\/strong>&nbsp;/)[1];
        const fieldIndex = words.get(result);
        const fieldValue = $(`.field-content [data-id="${fieldIndex}"] .field-type`).val();
        if(!fieldValue) return;
        innerHTML = innerHTML.replace(new RegExp(`&lt;${result}&gt;`, 'g'), fieldValue);
        innerHTML = innerHTML.replace(new RegExp(`<${result}>`, 'g'), fieldValue);
    });

    innerHTML = innerHTML.replace(/&nbsp;/g, ' ')
        .replace(/\s<strong\sclass="text-primary">/g, '<strong class="text-primary">')
        .replace(/<\/strong>\s/g, '</strong>');
    $('.prompt-preview-area .preview-container').html(innerHTML);
}

function updateButtonStatus()
{
    if($('#autocomplete-textarea').text().trim()) $('.button-container button').removeAttr('disabled');
    else $('.button-container button').attr('disabled', 'disabled');
}

$(function()
{
    $('#autocomplete-textarea').textcomplete(
        [{
            match: /(\s+)(\<\S*)$/,
            search(term, callback) {
                callback(Array.from(words.keys()).filter(word => `<${word}>`.indexOf(term) === 0));
            },
            replace(word) {
                word = `<strong class="text-primary">&lt;${word}&gt;</strong>`;
                return `$1${word} `;
            }
        }]
    );

    $('#autocomplete-textarea').on('blur', () =>
    {
        updatePromptPreview();
        updateButtonStatus();
    });
    $('.field-content .field-type').on('change', updatePromptPreview);

    currentField.forEach((field, index) => words.set(field.name, `field-${index}`));
    $('#autocomplete-textarea').html(currentPrompt);
    updatePromptPreview();
    updateButtonStatus();

    $('#generate-result').on('click', function()
    {
        const message = $('.prompt-preview-area .preview-container').text();
        if(!message) return;

        const trigger = new $.zui.ModalTrigger({backdrop: 'static', name: 'generate-loading'});
        trigger.show();

        $.post(
            createLink('aiapp', 'miniProgramChat', `id=${appID}`),
            {
                message,
                test: '1'
            },
        ).done(function(response)
        {
            trigger.close();
            response = JSON.parse(response);
            const {message, result} = response;
            if(result === 'success')
            {
                const {content} = message;
                $('.prompt-result-area .preview-container').html(content);
                return;
            }

            $('.prompt-result-area .preview-container').html(message);
        }).fail(function()
        {
            trigger.close();
            $('.prompt-result-area .preview-container').html('Network error');
        });
    })
});

function saveMiniProgram()
{
    $('input[name="prompt"]').prop('value', $('#autocomplete-textarea').text());
    $('input[name="toPublish"]').prop('value', '0');
}

function publishMiniProgram()
{
    $('input[name="prompt"]').prop('value', $('#autocomplete-textarea').text());
    $('input[name="toPublish"]').prop('value', '1');
}

/**
 * Open publish mini program dialog.
 */
function openPublishDialog()
{
    const $modal = $('#publish-miniprogram');
    $modal.modal('show', 'fit');
}
