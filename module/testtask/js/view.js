$(function()
{
    $('.loadAjax').each(function()
    {
        var $this = $(this);
        $this.load($this.data('url'), function()
        {
            $('#caseList').tablesorter(
            {
                saveSort: true,
                widgets: ['zebra', 'saveSort'], 
                widgetZebra: {css: ['odd', 'even'] }
            }).find('a.iframe').modalTrigger();
        });
    });
});
