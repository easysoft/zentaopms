$(document).ready(function()
{
    $('#startForm, #closeForm, #activateForm, #blockForm').ajaxForm(
    {
        finish:function(response)
        {
            if(response.locate)
            {
                if(response.locate == 'parent')
                {
                    parent.$.cookie('selfClose', 1);
                    setTimeout(function(){parent.$.closeModal(null, 'this')}, 1200);
                }
                else
                {
                    setTimeout(function(){window.location.href = response.locate;}, 1200);
                }
            }
            return false;
        }
    });
})

/**
 * Adjust priBox width.
 *
 * @access public
 * @return void
 */
function adjustPriBoxWidth()
{
    var boxWidth   = $('#ownerAndPriBox').width();
    var beginWidth = $("input[name='begin']").outerWidth();
    var addonWidth = $('#ownerAndPriBox .input-group-addon').outerWidth();
    $('#pri,#pri_chosen .chosen-single').css('width', boxWidth - beginWidth -addonWidth);
}

/**
 * Create bug from fail case.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function createBug(obj)
{
    var $form  = $(obj).closest('form');
    var params = $form.data('params');
    var stepIdList = '';
    $form.find('.step .step-id :checkbox').each(function()
    {
        if($(this).prop('checked')) stepIdList += $(this).val() + '_';
    });

    var onlybody    = config.onlybody;
    config.onlybody = 'no';

    var link = createLink('bug', 'create', params + ',stepIdList=' + stepIdList);
    if(onlybody = 'yes') link += '#app=qa';
    window.parent.$.apps.open(link);

    config.onlybody = onlybody;
}

/**
 * Load execution related
 *
 * @param  int $executionID
 * @access public
 * @return void
 */
function loadExecutionRelated(executionID)
{
    loadExecutionBuilds(executionID);
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
    selectedBuild = $('#build').val();
    if(!selectedBuild) selectedBuild = 0;
    link = createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + $('#product').val() + '&varName=testTaskBuild&build=' + selectedBuild, '', '', executionID);
    if(executionID == 0) link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + $('#product').val() + '&varName=resolvedBuild&build=' + selectedBuild);
    $('#buildBox').load(link, function()
    {
        $('#resolvedBuild').attr('id', 'build').attr('name', 'build').find('option[value=trunk]').remove();
        $('#build').chosen();
    });
}

/**
 * Load test report.
 *
 * @param  int    buildID
 * @access public
 * @return void
 */
function loadTestReports(buildID)
{
    link = createLink('testtask', 'ajaxGetTestReports', 'buildID=' + buildID);
    $.get(link, function(data)
    {
        if(!data) data = '<select id="testreport" name="testreport" class="form-control"></select>';
        $('#testreport').replaceWith(data);
        $('#testreport_chosen').remove();
        $("#testreport").chosen();
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

/**
 * Convert a date string like 2011-11-11 to date object in js.
 *
 * @param  string $date
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];

    return Date.parse(dateString);
}
