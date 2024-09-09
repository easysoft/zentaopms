window.renderRow = function($row, index, row)
{
    let $field   = $row.find('td[data-name="field"]');
    let selectObject = row.object;
    const tableOptions = $('#fieldSettingsModal').data('tables');

    $field.find('.picker-box').on('inited', function(e, info)
    {
        let $fieldPicker = $(e.target).find('[name^=field]').zui('picker');
        let fields = tableOptions.find((item) => item.value == selectObject)?.fields;

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
}

window.setFieldSettingValue = function($tr, key, value)
{
    let $type = $tr.find('td[data-name="' + key+ '"]');
    $type.find('[name^=' + key + ']').val(value);
}

window.changeFields = function(e)
{
    let $tr = $(e.target).closest('tr');
    /* 在用户切换了所属表之后，需要设置type为object，用于将数据映射关联为所属表的对应字段数据。*/
    setFieldSettingValue($tr, 'type', 'object');
}

window.loadFields = function(e)
{
    let $object = $(e.target);
    let selectObject = $object.val();
    const tableOptions = $('#fieldSettingsModal').data('tables');

    let fields = tableOptions.find((item) => item.value == selectObject)?.fields;
    let fieldOptions = !fields ? [] : Object.keys(fields).map((field) => ({text: fields[field].name, value: field}));

    let $tr = $object.closest('tr');
    let $field = $tr.find('td[data-name="field"]');
    let $fieldPicker = $field.find('[name^=field]').zui('picker');
    $fieldPicker.render({items: fieldOptions});

    /* 在用户切换了所属表之后，需要设置type为object，用于将数据映射关联为所属表的对应字段数据。*/
    setFieldSettingValue($tr, 'type', 'object');
}
