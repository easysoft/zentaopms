window.copy = function(event)
{
    const textToCopy = $(event.target).closest('.label').text();
    navigator.clipboard.writeText(textToCopy).then(() =>
    {
        zui.Messager.show({
            message: '复制成功',
            type: 'success',
        });
    }).catch(error => {
        zui.Messager.show({
            message: '复制失败',
            type: 'danger',
        });
    });
}
