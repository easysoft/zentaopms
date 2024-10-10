$(function()
{
    window.customCount  = $('#paramDiv .input-group').length;
    window.triggerCount = $('#triggerForm .trigger-box').length;
});
window.addItem = function(event)
{
    const obj        = $(event.target);
    const inputGroup = obj.closest('.input-group').clone();
    const newName    = window.customCount + 'custom';
    window.customCount ++;

    $(inputGroup).find('input.custom').attr('id', newName);
    $(inputGroup).find('input.paramName').val('');
    $(inputGroup).find('input[id="' + newName + '"]').next().attr('for', newName);
    obj.closest('.form-group').append($(inputGroup));
}
