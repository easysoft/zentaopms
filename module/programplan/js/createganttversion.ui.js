window.setGanttData = function()
{
    const ganttData = {data:[], links:[]};
    ganttData.links = gantt.getLinks();
    gantt.eachTask(function(task)
    {
        const data = {};
        for(let key in task)
        {
            if(key.indexOf('$') == 0) continue;

            data[key] = task[key];
            if(key == 'start_date' || key == 'end_date')
            {
                const date = task[key];
                data[key] = date.getDate().toString().padStart(2, '0') + '-' + (date.getMonth() + 1).toString().padStart(2, '0') + '-' + date.getFullYear();
            }
        }
        ganttData.data.push(data);
    });
    $('[name=data]').val(JSON.stringify(ganttData));
};