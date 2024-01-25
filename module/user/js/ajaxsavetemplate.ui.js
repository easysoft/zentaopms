$(function()
{
    /**
     * 把编辑器内容添加到表单中 post 到后台保存为模板。
     * Add the editor content to the form and post it to the background to save it as a template.
     */
    $(document).off('click', '#saveTemplate button[type=submit]').on('click', '#saveTemplate button[type=submit]', function()
    {
        const $form  = $(this).closest('form');
        const editor = $form.find('input[name="editor"]').val();
        $('zen-editor[name="' + editor + '"]')[0].getHTML().then((content) => {
            $form.find('input[name="content"]').val(content);
        })
    });
})
