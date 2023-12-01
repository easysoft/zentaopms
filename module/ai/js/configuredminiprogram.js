let $editingField = null;
let fieldIndex = 0;
/**
 * @type {Map<string, string>}
 */
const words = new Map();

/**
 * Handle the field type change events.
 *
 * @param {Event} event
 * @returns {void}
 */
function handleFieldTypeChange(event)
{
    const fieldType = $(event.target).prop('value');
    if(fieldType === 'text' || fieldType === 'textarea')
    {
        $('input[name="placeholder"]').closest('tr').removeClass('hidden');
        $('.field-options').addClass('hidden');
        revertOptions();
    }
    else
    {
        revertOptions();
        $('input[name="placeholder"]').closest('tr').addClass('hidden');
        $('.field-options').removeClass('hidden');
    }
}

/**
 * Fix options order.
 *
 * @returns {void}
 */
function fixOptionsOrder()
{
    const forEach = function ()
    {
        const index = $(this).children('span.input-group-addon').html().replace(/\d+$/, $(this).index() + 1);
        $(this).children('span.input-group-addon').html(index);
    }
    $('.options-container').children().each(forEach);
}

/**
 * Handle the remove option button Click event.
 *
 * @param {Event} event
 * @returns {void}
 */
function handleRemoveOptionClick(event)
{
    $(event.target).closest('.input-group').remove();
    fixOptionsOrder();
}

/**
 * Handle the add option button Click event.
 *
 * @param {Event} event
 * @returns {void}
 */
function handleAddOptionClick(event)
{
    const template = document.getElementById('option-template');
    const $clone = $(document.importNode(template.content, true));
    $(event.target).closest('.options-container').append($clone);
    fixOptionsOrder();
}

/**
 * Revert options.
 *
 * @returns {void}
 */
function revertOptions()
{
    const template = document.getElementById('option-template');
    const $optionsContainer = $('<div class="options-container"></div>');
    const $option = $(document.importNode(template.content, true));
    $optionsContainer.append($option);
    const $optionStr = $optionsContainer.html();
    $optionsContainer.append($optionStr);
    $optionsContainer.append($optionStr);
    $optionsContainer.find('.input-group').first().find('button').last().attr('disabled', 'disabled');

    $('.field-options')
        .children('td')
        .empty()
        .append($optionsContainer);

    fixOptionsOrder();
}

/**
 * Handle the new field button click event.
 *
 * @returns {void}
 */
function handleAddFieldClick()
{
    const $modal = $('#add-field-modal');
    const $modalBody = $modal.find('.modal-body');
    $modalBody.empty();
    const template = document.getElementById('field-template');
    const clone = document.importNode(template.content, true);
    $modalBody.append(clone);
    $('#add-field-modal .text-danger').remove();
    $modal.modal('show', 'fit');
}

/**
 * Get the editing field.
 *
 * @param {*} $fieldTr
 * @returns {[boolean, *]}
 */
function getEditingField($fieldTr)
{
    const name = $fieldTr.find('.field-name').html();
    const isTextType = !$fieldTr.find('.picker').length;
    if(isTextType)
    {
        const type = $fieldTr.find('input.field-type').length ? 'text' : 'textarea';
        const placeholder = $fieldTr.find('.field-type').prop('placeholder');
        const required = $fieldTr.find('.field-type').closest('td').hasClass('required');
        return [true, { name, type, placeholder, required }];
    }

    const $picker = $fieldTr.find('.picker');
    const type = $picker.hasClass('picker-multi') ? 'checkbox' : 'radio';
    const options = $picker.attr('data-options').split(',');
    const required = $picker.closest('td').hasClass('required');
    return [false, { name, type, options, required }];
}

/**
 * Handle the edit field button click event.
 *
 * @param {Event} event
 * @returns {void}
 */
function handleEditFieldClick(event)
{
    const $fieldTr = $(event.target).closest('tr');
    $editingField = $fieldTr;
    const [isTextType, field] = getEditingField($fieldTr);

    const $modal = $('#edit-field-modal');
    const $modalBody = $modal.find('.modal-body');
    $modalBody.empty();
    const template = document.getElementById('field-template');
    const $clone = $(document.importNode(template.content, true));
    $modalBody.append($clone);
    $modalBody.find('input[name="field-name"]').prop('value', field.name);
    $modalBody.find('select[name="field-type"]').prop('value', field.type);

    if(isTextType)
    {
        $modalBody.find('input[name="placeholder"]').prop('value', field.placeholder);
        $('input[name="placeholder"]').closest('tr').removeClass('hidden');
        $('.field-options').addClass('hidden');
    }
    else
    {
        const $optionsWrapper = $modalBody.find('.field-options > td')
        $optionsWrapper.empty();
        const $optionsContainer = $(`<div class="options-container"></div>`)
        $optionsWrapper.append($optionsContainer);

        field.options.forEach((option) => {
            const template = document.getElementById('option-template');
            const $clone = $(document.importNode(template.content, true));
            $optionsContainer.append($clone);
            $optionsContainer.children('.input-group').last().find('input[name="option[]"]').prop('value', option);
        });

        fixOptionsOrder();
        $('input[name="placeholder"]').closest('tr').addClass('hidden');
        $('.field-options').removeClass('hidden');
    }

    if(field.required)
    {
        $modalBody.find('input[value="1"]').attr('checked', 'checked');
        $modalBody.find('input[value="0"]').removeAttr('checked');
    }
    else
    {
        $modalBody.find('input[value="0"]').attr('checked', 'checked');
        $modalBody.find('input[value="1"]').removeAttr('checked');
    }

    $modal.find('.input-group').first().find('button').last().attr('disabled', 'disabled');

    $('#edit-field-modal .text-danger').remove();
    $modal.modal('show', 'fit');
}

/**
 * Handle the remove field button click event.
 *
 * @param {Event} event
 * @returns {void}
 */
function handleRemoveFieldClick(event)
{
    const result = window.confirm(deleteTip);
    if(!result) return;

    const $tr = $(event.target).closest('tr');
    const fieldID = $tr.attr('data-id');
    $tr.remove();
    $('.field-content').find(`[data-id="${fieldID}"]`).remove();

    if($('.field-configuration').children().length === 0) $('.field-configuration').parent().addClass('hidden').prev().removeClass('hidden');
    words.delete($tr.find('th').prop('title'));
    const innerHTML = $('#autocomplete-textarea').html();
    $('#autocomplete-textarea').html(innerHTML.replace(/<strong class="text-primary">/g, '').replace(/<\/strong>/g, ''));
    updatePromptPreview();
}

/**
 * Check for duplicate field names.
 *
 * @param {string} name
 * @returns {boolean} result
 */
const checkDuplicatedFieldName = (name) => Array.from(words.keys()).includes(name);

/**
 * Show field name error iferror exists.
 *
 * @param {*} $required
 * @param {HTMLFormElement} formData
 * @returns {boolean} result
 */
function showFieldNameErrorIfExist($required, form)
{
    const $form = $(form);
    const formData = new FormData(form);
    const name = formData.get('field-name');
    $form.find('.text-danger.help-text').remove();
    $form.find('.has-error').removeClass('has-error');

    if(!name.trim())
    {
        $required
            .addClass('has-error')
            .append(`<div class="text-danger help-text">${emptyWarning.replace('%s', name)}</div>`);
        return true;
    }

    if(checkDuplicatedFieldName(name))
    {
        if(!$editingField || $editingField.find('.field-name').html() !== name)
        {
            $required
                .addClass('has-error')
                .append(`<div class="text-danger help-text">${duplicatedWarning.replace('%s', name)}</div>`);
            return true;
        }
    }

    if(['radio', 'checkbox'].includes(formData.get('field-type')) && formData.getAll('option[]').filter(x => !!x).length === 0)
    {
        $form.find('.options-container').children().first().addClass('has-error').after(`<div class="text-danger help-text">${emptyOptionWarning}</div>`);
        return true;
    }


    return false;
}

/**
 * Handle the save field button click event.
 *
 * @returns {void}
 */
function handleSaveFieldClick()
{
    const form = document.querySelector('#add-field-modal form');
    const formData = new FormData(form);
    const name = formData.get('field-name');
    const $required = $('#add-field-modal input[name="field-name"]').closest('.required');

    if(showFieldNameErrorIfExist($required, form)) return;

    $('#add-field-modal').modal('hide');
    const $fieldView = createFieldView(formData);
    $fieldView.attr('data-id', `field-${++fieldIndex}`);
    $('#sortable-list').append($fieldView);
    $fieldView.find('.picker').addClass('disabled');

    const $button = $('.field-configuration-main > div');
    const $table = $('.field-configuration-main > table');
    if(!$button.hasClass('hidden')) $button.addClass('hidden');
    if($table.hasClass('hidden')) $table.removeClass('hidden');

    const $fieldViewClone = createFieldView(formData);
    $fieldViewClone.attr('data-id', $fieldView.attr('data-id'));
    $fieldViewClone.children().last().remove();
    $fieldViewClone.find('.drag-area').removeClass('darg-area');
    $fieldViewClone.removeClass('srotable-item');
    $fieldViewClone.find('.icon-move').remove();
    $fieldViewClone.find('[readonly]').removeAttr('readonly');
    $fieldViewClone.find('.field-type').on('change', updatePromptPreview);
    $('.field-content').append($fieldViewClone);

    words.set(name, $fieldView.attr('data-id'));
}

/**
 * Handle save edited field button click event.
 *
 * @returns {void}
 */
function handleSaveEditedFieldClick()
{
    const oldName = $editingField.find('.field-name').html();
    const form = document.querySelector('#edit-field-modal form');
    const formData = new FormData(form);
    const name = formData.get('field-name');
    const $required = $('#edit-field-modal input[name="field-name"]').closest('.required');

    if(showFieldNameErrorIfExist($required, form)) return;

    $('#edit-field-modal').modal('hide');
    const $fieldView = createFieldView(formData);
    $fieldView.attr('data-id', $editingField.attr('data-id'));
    $editingField.replaceWith($fieldView);
    $fieldView.find('.picker').addClass('disabled');

    const $fieldViewClone = createFieldView(formData);
    $fieldViewClone.attr('data-id', $fieldView.attr('data-id'));
    $fieldViewClone.children().last().remove();
    $fieldViewClone.children().first().removeClass('darg-area');
    $fieldViewClone.find('.icon-move').remove();
    $fieldViewClone.find('[readonly]').removeAttr('readonly');
    $fieldViewClone.find('.field-type').on('change', updatePromptPreview);
    $(`.field-content [data-id="${$editingField.attr('data-id')}"]`).replaceWith($fieldViewClone);

    words.delete(oldName);
    words.set(name, $fieldView.attr('data-id'));
    if(oldName !== name) updatePromptPreview();
    $editingField = null;
}

/**
 * Create field view.
 *
 * @param {FormData} formData
 * @returns {*}
 */
function createFieldView(formData)
{
    const $tr = $(`<tr class="sortable-item"></tr>`);
    $tr.append(createFieldNameView(formData));
    $tr.append(createFieldTypeView(formData));
    $tr.append(createFieldButtons(formData));
    return $tr;
}

/**
 * Create field name view.
 *
 * @param {FormData} formData
 * @returns {*}
 */
function createFieldNameView(formData)
{
    const name = formData.get('field-name');
    return $(`<th class="drag-area" title="${name}">
        <div>
            <i class="icon icon-move"></i>
            <span class="field-name">${name}</span>
        </div>
    </th>`);
}

/**
 * Create field type view.
 *
 * @param {FormData} formData
 * @returns {*}
 */
function createFieldTypeView(formData)
{
    const type = formData.get('field-type');
    const required = formData.get('required') === '1';
    const $td = $(`<td data-type="${type}"></td>`);
    if(required) $td.addClass('required');
    if(type === 'text')
    {
        const placeholder = formData.get('placeholder') || pleaseInput;
        $td.append(`<input type="text" class="form-control field-type" placeholder="${placeholder}" readonly />`);
        return $td;
    }

    if(type === 'textarea')
    {
        const placeholder = formData.get('placeholder') || pleaseInput;
        $td.append(`<textarea class="form-control field-type" placeholder="${placeholder}" readonly />`);
        return $td;
    }

    const options = Array.from(formData.getAll('option[]')).filter(x => !!x);
    const $picker = $(`<div class="picker"><input type="text" class="field-type" /></div>`);
    if(type === 'checkbox') $picker.attr('data-multi', 'true');
    $picker.picker({ list: options.map(option => ({ text: option, value: option })) });
    $picker.attr('data-options', options.join(','));
    $td.append($picker);
    return $td;
}

/**
 * Create field buttons view.
 *
 * @returns {*}
 */
function createFieldButtons()
{
    return $(`<td style="width: 100px;">
        <button type="button" class="btn btn-link btn-sm btn-icon" onclick="handleAddFieldClick()">
            <i class="icon icon-plus"></i>
        </button>
        <button type="button" class="btn btn-link btn-sm btn-icon" onclick="handleEditFieldClick(event)">
            <i class="icon icon-edit"></i>
        </button>
        <button type="button" class="btn btn-link btn-sm btn-icon" onclick="handleRemoveFieldClick(event)">
            <i class="icon icon-trash"></i>
        </button>
    </td>`);
}

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
        const fieldValue = $(`.field-content [data-id="${fieldIndex}"] .field-type`).prop('value');
        if(!fieldValue) return;
        innerHTML = innerHTML.replace(new RegExp(`&lt;${result}&gt;`, 'g'), fieldValue);
    });

    innerHTML = innerHTML.replace(/&nbsp;/g, ' ')
        .replace(/\s<strong\sclass="text-primary">/g, '<strong class="text-primary">')
        .replace(/<\/strong>\s/g, '</strong>');
    $('.prompt-preview-area .preview-container').html(innerHTML);
}

function getRequiredFields()
{
    const fields = [];
    $('.field-content').children().each(function ()
    {
        const field = {};
        field.name = $(this).find('.field-name').html();
        field.type = $(this).find('td').attr('data-type');
        field.required = $(this).find('td').hasClass('required') ? '1' : '0'
        field.appID = appID;
        if(['text', 'textarea'].includes(field.type)) field.placeholder = $(this).find('.field-type').prop('placeholder');
        else field.options = $(this).find('.picker').attr('data-options').split(',');
        fields.push(field);
    });
    const prompt = $('#autocomplete-textarea').text();
    return [fields, prompt];
}

/**
 * Save mini program fields.
 *
 * @param {'0'|'1'} toPublish
 * @returns {void}
 */
function saveMiniProgram(toPublish)
{
    if(!$('#autocomplete-textarea').text().trim())
    {
        alert(promptPlaceholder);
        return;
    }
    if(toPublish === '1')
    {
        const $modal = $('#publish-confirm-modal');
        $modal.modal('show', 'fit');
        return;
    }
    postMiniProgramData('0', () => location.reload());
}

$('#publish-confirm-modal .btn-primary').on('click', function ()
{
    postMiniProgramData('1', () => { window.location.href = createLink('ai', 'miniPrograms'); });
});

function postMiniProgramData(toPublish, callback)
{
    const [fields, prompt] = getRequiredFields();
    $.post(createLink('ai', 'configuredMiniProgram', `appID=${appID}`), { fields, prompt, toPublish }).done(callback);
}

function backToList()
{
    const [, prompt] = getRequiredFields();
    console.log(prompt);
    if(prompt && prompt.trim())
    {
        $modal = $('#back-to-list-modal');
        $modal.modal('show', 'fit');
        return;
    }
    window.location.href = createLink('ai', 'miniPrograms');
}

function backWithoutSave()
{
    $modal = $('#back-to-list-modal');
    $modal.modal('hide');
    window.location.href = createLink('ai', 'miniPrograms');
}

function backWithSave()
{
    $modal = $('#back-to-list-modal');
    $modal.modal('hide');
    saveMiniProgram('0');
    window.location.href = createLink('ai', 'miniPrograms');
}

function updateButtonStatus()
{
    if($('#autocomplete-textarea').text().trim())
    {
        $('.footer > a.btn-primary').removeAttr('disabled');
        $('.footer > a.btn-secondary').removeAttr('disabled');
    }
    else
    {
        $('.footer > a.btn-primary').attr('disabled', 'disabled');
        $('.footer > a.btn-secondary').attr('disabled', 'disabled');
    }
}

$(function ()
{
    $('#sortable-list').sortable(
        {
            selector: '.sortable-item',
            trigger: '.drag-area',
            finish: function (e)
            {
                const $draggedElm = e.element;
                const $fieldContent = $('.field-content');
                const selector = `[data-id="${$draggedElm.attr('data-id')}"]`;
                const prevSelector = `[data-id="${$draggedElm.prev().attr('data-id')}"]`;
                const index = $draggedElm.index();
                const $draggedElmContent = $fieldContent.find(selector);
                if(index === 0) $fieldContent.prepend($draggedElmContent);
                else $draggedElmContent.insertAfter($fieldContent.find(prevSelector));
            },
        });
    $('#autocomplete-textarea').textcomplete(
        [{
            match: /(\s+)(\<\S*)$/,
            search(term, callback)
            {
                callback(Array.from(words.keys()).filter(word => `<${word}>`.indexOf(term) === 0));
            },
            replace(word)
            {
                word = `<strong class="text-primary">&lt;${word}&gt;</strong>`;
                return `$1${word} `;
            }
        }]);

    $('#autocomplete-textarea').on('blur', () =>
    {
        updatePromptPreview();
        updateButtonStatus();
    });

    if(!currentPrompt)
    {
        currentFields = defaultFields;
        console.log(defaultFields);
        currentPrompt = currentFields.pop();
        currentFields = currentFields.map(name =>
        ({
            name,
            required: '0',
            type: 'text',
            placeholder: '',
        }));
    }

    currentFields.forEach(field =>
    {
        const formData = new FormData();
        formData.set('field-name', field.name);
        formData.set('field-required', field.required);
        formData.set('field-type', field.type);
        formData.set('placeholder', field.placeholder);

        if(typeof field.options === 'string' && field.options.length > 0)
        {
            field.options = field.options.split(',');
            field.options.forEach(option => formData.append('option[]', option));
        }

        const $fieldView = createFieldView(formData);
        $fieldView.attr('data-id', `field-${++fieldIndex}`);
        $('#sortable-list').append($fieldView);
        $fieldView.find('.picker').addClass('disabled');

        const $button = $('.field-configuration-main > div');
        const $table = $('.field-configuration-main > table');
        if(!$button.hasClass('hidden')) $button.addClass('hidden');
        if($table.hasClass('hidden')) $table.removeClass('hidden');

        const $fieldViewClone = createFieldView(formData);
        $fieldViewClone.attr('data-id', $fieldView.attr('data-id'));
        $fieldViewClone.children().last().remove();
        $fieldViewClone.find('.drag-area').removeClass('darg-area');
        $fieldViewClone.removeClass('srotable-item');
        $fieldViewClone.find('.icon-move').remove();
        $fieldViewClone.find('[readonly]').removeAttr('readonly');
        $fieldViewClone.find('.field-type').on('change', updatePromptPreview);
        $('.field-content').append($fieldViewClone);

        words.set(field.name, $fieldView.attr('data-id'));
    });

    $('#autocomplete-textarea').html(currentPrompt);
    updatePromptPreview();
    updateButtonStatus();
});

