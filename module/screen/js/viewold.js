location.hash = '#/chart/preview/' + screen.id;
window.location.replace(window.location.href.toString().replace(window.location.hash, '')+'#/chart/preview/' + screen.id)
window.fetchChartApi = createLink('screen', 'ajaxGetChart', 'year=' + year + '&month=' + month + '&dept=' + dept + '&account=' + account);
window.fetchMetricDataApi = createLink('screen', 'ajaxGetMetricData');
window.fetchScreenSchemeApi = createLink('screen', 'ajaxGetScreenScheme', 'screenID=' + screen.id + '&year=' + year + '&month=' + month + '&dept=' + dept + '&account=' + account);
window.fetchFilterOptionsApi = createLink('screen', 'ajaxGetFilterOptions');
window.drillModalApi = parent.window.drillModalApi;

$('link[href^="/theme/zui/css"]').remove();
$('link[href^="/theme/default/style.css"]').remove();
