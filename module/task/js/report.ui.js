window.createChart = function()
{
    const formData = new FormData($('#chartForm')[0]);
    postAndLoadPage($('#chartForm').attr('action'), formData, '#chartContainer');
}

window.triggerChecked = function()
{
    $(".btn-select-all").toggleClass('checked');
    $("#chartForm").find("input[name^=charts]").prop("checked", $('.btn-select-all').hasClass('checked'));
}
