function showAnnualData(data)
{
    $('.text-holder').each(function()
    {
        var $this = $(this);
        var id = $this.data('id');
        var idPath = id.split('.');
        var dataProp = data;
        for(var i = 0; i < idPath.length; ++i)
        {
            dataProp = dataProp[idPath[i]];
        }
        $this.text(dataProp);
    });

    var $projectsTableRows = $('<tbody></tbody>');
    $.each(data.projectsList, function(index)
    {
        var project = data.projectsList[index];
        $projectsTableRows.append(
        [
            '<tr>',
              '<td class="col-name">' + project.name + '</td>',
              '<td class="col-storyCount">' + project.storyCount + '</td>',
              '<td class="col-taskCount">' + project.taskCount + '</td>',
              '<td class="col-bugCount">' + project.bugCount + '</td>',
            '</tr>'
        ].join(''));
    });
    $('#projectsTable').empty().append($projectsTableRows);

    var $projectsSummaryChart = $('#projectsSummaryChart');
    var pieChart = $projectsSummaryChart.data('pieChart');
    while(pieChart.segments.length) pieChart.removeData();
    pieChart.options.tooltipTemplate = "<%=label%>: <%=value%>";
    var pieChartLabels = $projectsSummaryChart.data('labels').split(',');
    var pieChartColors = ['#0068B7', '#1aa1e6', '#81cef2'];
    $.each(['finishProjects', 'activateProjects', 'suspendProjects'], function(index, name)
    {
        pieChart.addData({color: pieChartColors[index], label: pieChartLabels[index], value: data[name]});
    });

    var priColors = ['#CAAC32', '#0075A9', '#22AC38', '#2B4D6D'];
    var $bugsChart = $('#bugsChart');
    var bugsChart = $bugsChart.data('pieChart');
    while(bugsChart.segments.length) bugsChart.removeData();
    var $tasksChart = $('#tasksChart');
    var tasksChart = $tasksChart.data('pieChart');
    while(tasksChart.segments.length) tasksChart.removeData();
    for(var i = 0; i < 5; ++i)
    {
        if(i === 0)
        {
            tasksChart.addData({color: 'transparent', value: data.finishTaskTotalCount / 5, label: 'P0'});
            bugsChart.addData({color: 'transparent', value: data.finishBugTotalCount / 5, label: 'P0'});
        }
        else
        {
            tasksChart.addData({color: priColors[i - 1], value: data['finishTaskCountPri' + i], label: 'P' + i, circleBeginEnd: true});
            bugsChart.addData({color: priColors[i - 1], value: data['finishBugCountPri' + i], label: 'P' + i, circleBeginEnd: true});
        }
    }

    var $hoursChart = $('#hoursChart');
    var hoursChart = $hoursChart.data('hoursChart');
    if(!hoursChart)
    {
        hoursChart = $hoursChart.lineChart(
        {
            labels: data.yearlyLabels,
            datasets: [{
                label: data.yearlyTask,
                color: 'rgba(50,255,50,.5)',
                data: data.yearlyTaskHours
            }, {
                label: data.yearlyBug,
                color: 'rgba(0, 117, 255,.5)',
                data: data.yearlyBugHours
            }]
        }, {
            scaleFontColor: '#A0A0A0',
            datasetStrokeWidth: 1,
            pointDotRadius: 3,
            scaleShowVerticalLines: false,
            scaleGridLineColor: 'rgba(255,255,255,.3)'
        });
    }
}

$(function()
{
    var $main = $('#main');
    var ajustPosition = function()
    {
        $main.css(
        {
            posotion: 'absolute',
            top: Math.floor(($(window).height() - $main.outerHeight()) / 2),
            left: Math.floor(($(window).width() - $main.outerWidth()) / 2),
        });
    };
    ajustPosition();
    $(window).on('resize', ajustPosition);
});
