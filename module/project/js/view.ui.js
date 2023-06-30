$(function()
{
    setTimeout(function()
    {
        blocks.forEach(block =>
        {
            if(block.domID)
            {
                block.content = {html: $(`#${block.domID}`).html()};
                $(`#${block.domID}`).remove();
                delete(block.domID);
            }
        });

        const $dashboard = $('#projectDashBoard').data('zui.Dashboard')
        $dashboard.render({blocks: blocks});

        $('#projectDashBoard .dashboard-block').attr('draggable', false);
        $('#projectDashBoard .dashboard-block-header').hide();

        $('.actions-menu').css('width', $('#mainContent').width() / 3 * 2);

        const historyHeight = $('#projectDashBoard .history').height();
        const blockHeight   = $('#projectDashBoard .dashboard-blocks > .dashboard-block-cell').height();
        if(historyHeight < blockHeight * 2) $('.actions-menu').removeClass('fixed');
    }, 10);
});
