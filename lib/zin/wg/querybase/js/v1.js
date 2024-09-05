window.renderRow = function($row, index, row)
{
    let $field   = $row.find('td[data-name="field"]');
    let selectObject = row.object;

    $field.find('.picker-box').on('inited', function(e, info)
    {
        let $fieldPicker = $(e.target).find('[name^=field]').zui('picker');
        let fields = window.tableOptions.find((item) => item.value == selectObject)?.fields;

        let fieldOptions = !fields ? [] : Object.keys(fields).map((field) => ({text: fields[field].name, value: field}));
        $fieldPicker.render({items: fieldOptions});
    });
}

/**
 * 清空对应字段。
 * Clear field.
 *
 * @param  object event
 * @access public
 * @return void
 */
window.clearField = function(event)
{
    const $currentRow  = $(event.target).closest('tr');
    const fieldName    = $currentRow.find('input[name^=key]').val();
    const $fieldPicker = $currentRow.find('input[name^=field]').zui('picker');

    $fieldPicker.$.setValue('');
    pivotState.fieldSettings[fieldName].field = '';
}
