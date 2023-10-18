let $editingField = null;

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
    const forEach = function()
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
    const $newField = $(event.target).closest('.input-group').clone();
    $newField.find('input').prop('value', '');
    $(event.target).closest('.options-container').append($newField);
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
    const isTextType = $fieldTr.find('.field-type').length;
    if(isTextType)
    {
        const type = $fieldTr.find('input.field-type').length ? 'text' : 'textarea';
        const placeholder = $fieldTr.find('input[type="text"]').prop('placeholder');
        const required = $fieldTr.find('input[type="text"]').closest('td').hasClass('required');
        return [true, {name, type, placeholder, required}];
    }

    const $picker = $fieldTr.find('.picker');
    const type = $picker.hasClass('picker-multi') ? 'checkbox' : 'radio';
    const options = $picker.attr('data-options').split(',');
    const required = $picker.closest('td').hasClass('required');
    return [false, {name, type, options, required}];
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

        field.options.forEach((option) =>
        {
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
    if(result) $(event.target).closest('tr').remove();
    const $button = $('.field-configuration-main > div');
    const $table = $('.field-configuration-main > table');
    if($button.hasClass('hidden')) $button.removeClass('hidden');
    if(!$table.hasClass('hidden')) $table.addClass('hidden');
}

/**
 * Handle the save field button click event.
 *
 * @returns {void}
 */
function handleSaveFieldClick()
{
    const formData = new FormData(document.querySelector('#add-field-modal form'));
    const name = formData.get('field-name');
    if(!name)
    {
        const template = document.getElementById('text-danger-template');
        const $clone = $(document.importNode(template.content, true));
        $('#add-field-modal input[name="field-name"]')
            .closest('.required')
            .append($clone);
        return;
    }

    $('#add-field-modal').modal('hide');
    const $fieldView = createFieldView(formData);
    $('#sortable-list').append($fieldView);
    const $button = $('.field-configuration-main > div');
    const $table = $('.field-configuration-main > table');
    if(!$button.hasClass('hidden')) $button.addClass('hidden');
    if($table.hasClass('hidden')) $table.removeClass('hidden');
}

/**
 * Handle save edited field button click event.
 *
 * @returns {void}
 */
function handleSaveEditedFieldClick()
{
    const formData = new FormData(document.querySelector('#edit-field-modal form'));
    const name = formData.get('field-name');
    if(!name)
    {
        const template = document.getElementById('text-danger-template');
        const $clone = $(document.importNode(template.content, true));
        $('#edit-field-modal input[name="field-name"]')
            .closest('.required')
            .append($clone);
        return;
    }

    $('#edit-field-modal').modal('hide');
    const $fieldView = createFieldView(formData);
    $editingField.replaceWith($fieldView);
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
    return $(`<th class="drag-area">
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
    const $td = $('<td></td>');
    if(required) $td.addClass('required');
    if(type === 'text')
    {
        const placeholder = formData.get('placeholder') || pleaseInput;
        $td.append(`<input type="text" class="form-control field-type" placeholder="${placeholder}" />`);
        return $td;
    }

    if(type === 'textarea')
    {
        const placeholder = formData.get('placeholder') || pleaseInput;
        $td.append(`<textarea class="form-control field-type" placeholder="${placeholder}" />`);
        return $td;
    }

    const options = formData.getAll('option[]');
    const $picker = $(`<div class="picker"><input type="text"></div>`);
    if(type === 'checkbox') $picker.attr('data-multi', 'true');
    $picker.picker({list: options.map(option => ({text: option, value: option}))});
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
    return $(`<td>
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

$('#sortable-list').sortable({selector: '.sortable-item', trigger: '.drag-area'});
