/**
 * Load execution related
 *
 * @access public
 * @return void
 */
function loadExecutionRelated()
{
    loadExecutionBuilds($('#execution').val());
}

/**
 * Load execution builds.
 *
 * @param  int $executionID
 * @access public
 * @return void
 */
function loadExecutionBuilds(executionID)
{
    var selectedBuild = $('#build').val();
    if(!selectedBuild) selectedBuild = 0;

    var link = $.createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + $('#product').val() + '&varName=testTaskBuild&build=' + selectedBuild);
    if(executionID == 0) link = $.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&varName=build&build=' + selectedBuild + '&branch=&index=&needCreate=&type=noempty,notrunk,withexecution');

    $.get(link, function(data)
    {
        $('#build').replaceWith(data);
    });
}

/**
 * Load test report.
 *
 * @param  int    productID
 * @access public
 * @return void
 */
function loadTestReports(productID)
{
    link = $.createLink('testtask', 'ajaxGetTestReports', 'productID=' + productID);
    $.get(link, function(data)
    {
        if(!data) data = '<select id="testreport" name="testreport" class="form-control"></select>';
        $('#testreport').replaceWith(data);
    });
}

/**
 * when begin date input change and end date input is null
 * change end date input to begin's after day
 *
 * @access public
 * @return void
 */
function suitEndDate()
{
    beginDate = $('#begin').val();
    if(!beginDate) return;
    endDate = $('#end').val();
    if(endDate) return;

    endDate = $.zui.formatDate(convertStringToDate(beginDate).addDays(1), 'yyyy-MM-dd');
    $('#end').val(endDate);
}
