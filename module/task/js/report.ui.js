window.createChart = function()
{
    const formData = new FormData($('#chartForm form')[0]);
    postAndLoadPage($('#chartForm form').attr('action'), formData, '#chartContainer,pageJS/.zin-page-js,#configJS');
}

window.triggerChecked = function()
{
    $(".btn-select-all").toggleClass('checked');
    $("#chartForm").find("input[name^=charts]").prop("checked", $('.btn-select-all').hasClass('checked'));
}

window.changeTab = function(e)
{
    $('#chartForm form').attr('action', $(e.target).closest('.font-medium').data('param'))
    createChart();
}
