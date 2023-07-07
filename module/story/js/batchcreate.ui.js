window.renderRowData = function($row)
{
    $row.find('select').each(function()
    {
        $select = $(this);
        if($select.prop('multiple')){$select.attr('name', $select.attr('name') + '[]');}
    });
};

/**
 * 点击批量新建表单底部按钮响应事件。
 * The event that click on the submit buttons of the batch creation form.
 *
 * Attach 'clicked' class to mark the clicked button.
 *
 * @param  Event  e
 * @access public
 * @return void
 */
window.onClickActionBtn = function(e)
{
    const parent = e.target.closest('div');
    parent.querySelectorAll('button').forEach(function(btn){
        $(btn).removeClass('clicked');
    });

    $(e.target.closest('button')).addClass('clicked');
};

/**
 * 生成表单提交数据的回调函数。
 * Callback function to change the form data before submit.
 *
 * @param  FormData  formData
 * @access public
 * @return FormData
 */
window.beforeSubmitBatchCreateForm = function(formData)
{
    const actionBtn = document.querySelector('div.toolbar.form-actions button.clicked');

    if(actionBtn && actionBtn.id === 'saveDraft')
    {
        formData.append('status', 'draft');
    }

    return formData;
};
