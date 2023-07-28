window.createChart = function()
{
    const formData = new FormData($('#chartForm')[0]);
    postAndLoadPage($('#chartForm').attr('action'), formData, '#chartContainer,pageJS/.zin-page-js,#configJS');
}

window.triggerChecked = function()
{
    $(".btn-select-all").toggleClass('checked');
    $("#chartForm").find("input[name^=charts]").prop("checked", $('.btn-select-all').hasClass('checked'));
}

window.changeTab = function(e)
{
    $('#chartForm').attr('action', $(e.target).closest('.font-medium').data('param'))
    createChart();
}
