/**
 * 导入流水线表单前端交互。
 * Handle import form interactions.
 */
$(function()
{
    const $pipeline = $('[name=pipeline]');
    const $name     = $('[name=name]');

    if(!$pipeline.length || !$name.length) return;

    $pipeline.on('change', function()
    {
        const selectedText = $pipeline.find('option:selected').text();
        if(selectedText && (!$name.val() || $name.data('auto')))
        {
            $name.val(selectedText).data('auto', true);
        }
    });

    $name.on('input', function()
    {
        $(this).data('auto', false);
    });
});
