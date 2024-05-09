location.hash = '#/chart/preview/' + screen.id;
window.location.replace(window.location.href.toString().replace(window.location.hash, '')+'#/chart/preview/' + screen.id)
window.fetchChartApi = createLink('screen', 'ajaxGetChart');
window.fetchMetricDataApi = createLink('screen', 'ajaxGetMetricData');
window.fetchScreenSchemeApi = createLink('screen', 'ajaxGetScreenScheme', 'screenID=' + screen.id + '&year=' + year + '&month=' + month + '&dept=' + dept + '&account=' + account);
window.fetchFilterOptionsApi = createLink('screen', 'ajaxGetFilterOptions');
