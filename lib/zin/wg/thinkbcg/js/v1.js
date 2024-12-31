window.initBcgModel = function()
{
    $.getLib(config.webRoot + 'js/echarts/echarts.common.min.js', {root: false}, function()
    {
        const bcgEcharts         = echarts.init($('.echarts-content>div')[0]);
        const data               = $('.echarts-content').data('blocks');
        const configureDimension = data.runOptions.configureDimension;
    });
}
