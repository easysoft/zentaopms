if(!window.watermarkIndex)
{
    window.watermarkIndex = {};
}

window.watermarkIndex.toggleWatermarkTr = () => {
    const enabled = $('[name="enabled"]:checked').val();
    if(enabled == 1)
    {
        $('#watermark-tr').removeClass('hidden');
        $('#watermark-tip-tr').removeClass('hidden');
    }
    else
    {
        $('#watermark-tr').addClass('hidden');
        $('#watermark-tip-tr').addClass('hidden');
    }
};

$(() => {
    window.watermarkIndex.toggleWatermarkTr();
});