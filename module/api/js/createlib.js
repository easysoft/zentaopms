$(function()
{
    $('#apiForm').ajaxForm({
        success: (data) => {
            if (data.result == 'success') {
                if (data.locate) {
                    window.parent.location.href = data.locate
                }
                $.zui.closeModal()
            }
        }
    })

    toggleLibType(libType);
});
