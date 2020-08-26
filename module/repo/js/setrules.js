$(function()
{
    replaceExample();
    $('input').keyup(function(){replaceExample()});
})

function replaceExample()
{
    var html = '';
    var startTask    = $('[id*=start]').val().split(';');
    var taskModule   = $('[id*=module][id*=task]').val().split(';');
    var idMark       = $('[id*=id][id*=mark]').val().split(';');
    var idSplit      = $('[id*=id][id*=split]').val().split(';');
    var costs        = $('[id*=task][id*=consumed]').val().split(';');
    var consumedmark = $('[id*=mark][id*=consumed]').val().split(';');
    var lefts        = $('[id*=task][id*=left]').val().split(';');
    var leftMarks    = $('[id*=mark][id*=left]').val().split(';');
    var cunits       = $('[id*=unit][id*=consumed]').val().split(';');
    var lunits       = $('[id*=unit][id*=left]').val().split(';');

    for(i in startTask)
    {
        start = startTask[i];
        for(j in taskModule)
        {
            task = taskModule[j];
            for(k in idMark)
            {
                id = idMark[k];
                for(l in idSplit)
                {
                    split = idSplit[l];
                    for(m in costs)
                    {
                        cost = costs[m];
                        for(n in consumedmark)
                        {
                            consumed = consumedmark[n];
                            for(o in lefts)
                            {
                                left = lefts[o];
                                for(p in leftMarks)
                                {
                                    leftMark = leftMarks[p];
                                    for(q in cunits)
                                    {
                                        cunit = cunits[q];
                                        for(r in lunits)
                                        {
                                            lunit = lunits[r];
                                            html += '<br />' + rulesExample['task']['start'].replace('%start%', start)
                                              .replace('%task%', task)
                                              .replace('%id%', id)
                                              .replace('%split%', split)
                                              .replace('%cost%', cost)
                                              .replace('%consumedmark%', consumed)
                                              .replace('%left%', left)
                                              .replace('%leftmark%', leftMark)
                                              .replace('%cunit%', cunit)
                                              .replace('%lunit%', lunit);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    var finishTask = $('[id*=finish]').val().split(';');
    for(i in finishTask)
    {
        finish = finishTask[i];
        for(j in taskModule)
        {
            task = taskModule[j];
            for(k in idMark)
            {
                id = idMark[k];
                for(l in idSplit)
                {
                    split = idSplit[l];
                    for(m in costs)
                    {
                        cost = costs[m];
                        for(n in consumedmark)
                        {
                            consumed = consumedmark[n];
                            for(o in cunits)
                            {
                                cunit = cunits[o];
                                html += '<br />' + rulesExample['task']['finish'].replace('%finish%', finish)
                                  .replace('%task%', task)
                                  .replace('%id%', id)
                                  .replace('%split%', split)
                                  .replace('%cost%', cost)
                                  .replace('%consumedmark%', consumed)
                                  .replace('%cunit%', cunit);
                            }
                        }
                    }
                }
            }
        }
    }

    var effortTask = $('[id*=logEfforts]').val().split(';');
    for(i in effortTask)
    {
        effort = effortTask[i];
        for(j in taskModule)
        {
            task = taskModule[j];
            for(k in idMark)
            {
                id = idMark[k];
                for(l in idSplit)
                {
                    split = idSplit[l];
                    for(m in costs)
                    {
                        cost = costs[m];
                        for(n in consumedmark)
                        {
                            consumed = consumedmark[n];
                            for(o in lefts)
                            {
                                left = lefts[o];
                                for(p in leftMarks)
                                {
                                    leftMark = leftMarks[p];
                                    for(q in cunits)
                                    {
                                        cunit = cunits[q];
                                        for(r in lunits)
                                        {
                                            lunit = lunits[r];
                                            html += '<br />' + rulesExample['task']['effort'].replace('%effort%', effort)
                                                .replace('%task%', task)
                                                .replace('%id%', id)
                                                .replace('%split%', split)
                                                .replace('%cost%', cost)
                                                .replace('%consumedmark%', consumed)
                                                .replace('%left%', left)
                                                .replace('%leftmark%', leftMark)
                                                .replace('%cunit%', cunit)
                                                .replace('%lunit%', lunit);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    var resolveBug  = $('[id*=bug][id*="resolve\]"]').val().split(';');
    var bugModule   = $('[id*=module][id*=bug]').val().split(';');
    for(i in resolveBug)
    {
        resolve = resolveBug[i];
        for(j in bugModule)
        {
            bug = bugModule[j];
            for(k in idMark)
            {
                id = idMark[k];
                for(l in idSplit)
                {
                    split = idSplit[l];
                    html += '<br />' + rulesExample['bug']['resolve'].replace('%resolve%', resolve)
                        .replace('%bug%', bug)
                        .replace('%id%', id)
                        .replace('%split%', split);
                }
            }
        }
    }

    $('#example').html(html.substr(6));
}
