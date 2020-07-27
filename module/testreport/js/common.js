$(function()
{
    var resizeChartTable = function()
    {
      $('.table-wrapper').each(function()
          {
            var $this = $(this);
            $this.css('max-height', $this.closest('.table').find('.chart-wrapper').outerHeight());
          });
    };
    resizeChartTable();
});

/**
 * Change date then show refresh btn.
 * 
 * @access public
 * @return void
 */
function changeDate()
{
    $("#refresh").removeClass("hidden");
}

/**
 * Refresh page.
 * 
 * @access public
 * @return void
 */
function refreshPage()
{
    var begin = $("#begin").val();
    var end   = $("#end").val();

    if(begin.indexOf('-') != -1)
    {
        var beginarray = begin.split("-");
        var begin = '';
        for(i = 0; i < beginarray.length; i++) begin = begin + beginarray[i];
    }
    if(end.indexOf('-') != -1)
    {
        var endarray = end.split("-");
        var end = '';
        for(i = 0 ; i < endarray.length ; i++) end = end + endarray[i];
    }

    if(method == 'create')
    {
        location.href = createLink('testreport', 'create', "project=" + objectID + "&objectType=" + objectType + "&extra=" + extra + "&begin=" + begin + "&end=" + end);
    }
    else if(method == 'edit')
    {
        location.href = createLink('testreport', 'edit', "reportID=" + reportID + "&from=" + from + "&begin=" + begin + "&end=" + end);
    }
}
