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
    }, 10);
});
