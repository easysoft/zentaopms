self["MonacoEnvironment"] = (function(paths) {
    return {
        globalAPI: false,
        getWorkerUrl : function (moduleId, label) {
            var result =  paths[label];
            if (/^((http:)|(https:)|(file:)|(\/\/))/.test(result)) {
                var currentUrl = String(window.location);
                var currentOrigin = currentUrl.substr(0, currentUrl.length - window.location.hash.length - window.location.search.length - window.location.pathname.length);
                if (result.substring(0, currentOrigin.length) !== currentOrigin) {
                    var js = '/*' + label + '*/importScripts("' + result + '");';
                    var blob = new Blob([js], { type: 'application/javascript' });
                    return URL.createObjectURL(blob);
                }
            }
            return result;
        }
    };
})({
    "editorWorkerService": "/monacoeditorwork/editor.worker.bundle.js",
    "typescript": "/monacoeditorwork/ts.worker.bundle.js",
    "json": "/monacoeditorwork/json.worker.bundle.js",
    "html": "/monacoeditorwork/html.worker.bundle.js",
    "javascript": "/monacoeditorwork/ts.worker.bundle.js",
    "handlebars": "/monacoeditorwork/html.worker.bundle.js",
    "razor": "/monacoeditorwork/html.worker.bundle.js"
});

location.hash = '#/chart/preview/' + screen.id;
window.location.replace(window.location.href.toString().replace(window.location.hash, '')+'#/chart/preview/' + screen.id)
window.fetchChartApi = createLink('screen', 'ajaxGetChart');
window.fetchMetricDataApi = createLink('screen', 'ajaxGetMetricData');
window.fetchScreenSchemeApi = createLink('screen', 'ajaxGetScreenScheme', 'screenID=' + screen.id + '&year=' + year + '&month=' + month + '&dept=' + dept + '&account=' + account);
