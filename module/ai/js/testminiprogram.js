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
function formatContent(content) {
    if (words.size === 0) return content;
    Array.from(words.keys()).forEach(word => { content = content.replace(new RegExp(`\\s<${word}>\\s`, 'g'), `&nbsp;<strong class="text-primary">&lt;${word}&gt;</strong>&nbsp;`); });
    return content;
}

/**
 * Update prompt preview.
 *
 * @returns {string} result
 */
function updatePromptPreview() {
    const innerText = $('#autocomplete-textarea').text();
    let innerHTML = formatContent(innerText);
    $('#autocomplete-textarea').html(innerHTML);
    if (!innerHTML || words.size === 0) {
        $('.prompt-preview-area .preview-container').html(innerHTML);
        return;
    }

    const regex = /<strong\sclass="text-primary">&lt;([^>]+)&gt;<\/strong>&nbsp;/g;
    const matches = innerHTML.match(regex);
    if (!matches) return;

    matches.forEach((match) => {
        const result = match.match(/<strong\sclass="text-primary">&lt;([^>]+)&gt;<\/strong>&nbsp;/)[1];
        const fieldIndex = words.get(result);
        const fieldValue = $(`.field-content [data-id="${fieldIndex}"] .field-type`).prop('value');
        if (!fieldValue) return;
        innerHTML = innerHTML.replace(new RegExp(`&lt;${result}&gt;`, 'g'), fieldValue);
    });

    innerHTML = innerHTML.replace(/&nbsp;/g, ' ')
        .replace(/\s<strong\sclass="text-primary">/g, '<strong class="text-primary">')
        .replace(/<\/strong>\s/g, '</strong>');
    $('.prompt-preview-area .preview-container').html(innerHTML);
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

    $('#autocomplete-textarea').on('blur', updatePromptPreview);
    $('.field-content .field-type').on('change', updatePromptPreview);

    currentField.forEach((field, index) => words.set(field.name, `field-${index}`));
    $('#autocomplete-textarea').html(currentPrompt);
    updatePromptPreview();
});
