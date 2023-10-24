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
                else if(response.locate == 'parent.parent')
                {
                    setTimeout(function(){parent.location.reload();}, 1200);
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
    var width      = boxWidth - beginWidth - addonWidth;
    $('#pri,#pri_chosen .chosen-single').css('width', width > 0 ? width : '160px');
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
    if(tab == 'my')
    {
        window.parent.$.apps.open(link, 'qa');
    }
    else
    {
        window.open(link, '_blank');
    }

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
    var selectedBuild = $('#build').val();

    if(!selectedBuild) selectedBuild = 0;
    var link = createLink('build', 'ajaxGetExecutionBuilds', 'executionID=' + executionID + '&productID=' + $('#product').val() + '&varName=testTaskBuild&build=' + selectedBuild);
    if(executionID == 0) link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&varName=build&build=' + selectedBuild + '&branch=&index=&needCreate=&type=noempty,notrunk,withexecution');
    if(executionID == 0 && projectID == 0) link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + $('#product').val() + '&varName=build&build=&branch=all&index=&type=notrunk,withexecution');
    $('#buildBox').load(link, function()
    {
        $('#build').chosen();
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
    link = createLink('testtask', 'ajaxGetTestReports', 'productID=' + productID);
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
