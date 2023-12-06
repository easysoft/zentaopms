function search()
{
    loadPage({method:'post', data: {keyword: $('#keyword').val()}, target: '#table-sonarqube-browseissue>*'});
}

window.saveIssueTitle = function(productID, sonarqubeID, issueKey, issueTitle)
{
    $.cookie.set('sonarqubeIssue', issueTitle, {expires:config.cookieLife, path:config.webRoot});
    openPage($.createLink('bug', 'create', `productID=${productID}&branch=&extra=from=sonarqube%2CsonarqubeID=${sonarqubeID}%2CissueKey=${issueKey}`), 'qa');
}
