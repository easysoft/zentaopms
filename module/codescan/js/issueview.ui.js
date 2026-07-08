window.copyFile = function(dom)
{
    const fileInput = $('[name=file]');
    fileInput.removeClass('hidden');
    fileInput[0].select();

    document.execCommand('copy');
    fileInput.addClass('hidden');

    zui.Messager.show({
        type:    'success',
        content: codescanLang.notice.copiedSuccess,
        time:    1000
    });
};

$(function()
{
    if(config.currentModule == 'codescan' && config.currentMethod == 'issueview')
    {
        setTimeout(() => {
            $('.solution-block .secondary-pale').scrollIntoView({behavior: 'smooth'});
        }, 100);
    }
});
