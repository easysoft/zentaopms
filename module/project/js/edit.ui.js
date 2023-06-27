/* 切换项目管理模型的逻辑. */
$(document).on('click', '.dropdown-menu .menu-item', function()
{
    let text  = $(this).find('.model-drop').attr('data-value');
    let model = $(this).find('.model-drop').attr('data-key');

    const btnClass = labelClass[model];

    $('#project-model .text').text(text);
    $('#project-model').removeClass('secondary-outline special-outline warning-outline');
    $('#project-model').addClass(btnClass);
    $('#model').val(model);
})
