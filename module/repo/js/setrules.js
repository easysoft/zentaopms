$(function()
{
    replaceExample();
    $('input').keyup(function(){replaceExample()});
})

function replaceExample()
{
    var html = '';
    var startTask    = $('[id*=start').val().split(';');
    var taskModule   = $('[id*=module][id*=task]').val().split(';');
    var idMark       = $('[id*=id][id*=mark]').val().split(';');
    var idSplit      = $('[id*=id][id*=split]').val().split(';');
    var costs        = $('[id*=task][id*=consumed]').val().split(';');
    var consumedmark = $('[id*=mark][id*=consumed]').val().split(';');
    var lefts        = $('[id*=task][id*=left]').val().split(';');
    var leftMarks    = $('[id*=mark][id*=left]').val().split(';');

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
                                    html += '<br />' + rulesExample['task']['start'].replace('%start%', start)
                                        .replace('%task%', task)
                                        .replace('%id%', id)
                                        .replace('%split%', split)
                                        .replace('%cost%', cost)
                                        .replace('%consumedmark%', consumed)
                                        .replace('%left%', left)
                                        .replace('%leftmark%', leftMark);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    var finishTask = $('[id*=finish').val().split(';');
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
                            html += '<br />' + rulesExample['task']['finish'].replace('%finish%', finish)
                              .replace('%task%', task)
                              .replace('%id%', id)
                              .replace('%split%', split)
                              .replace('%cost%', cost)
                              .replace('%consumedmark%', consumed)
                        }
                    }
                }
            }
        }
    }

    var effortTask = $('[id*=logEfforts').val().split(';');
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
                                    html += '<br />' + rulesExample['task']['effort'].replace('%effort%', effort)
                                        .replace('%task%', task)
                                        .replace('%id%', id)
                                        .replace('%split%', split)
                                        .replace('%cost%', cost)
                                        .replace('%consumedmark%', consumed)
                                        .replace('%left%', left)
                                        .replace('%leftmark%', leftMark);
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
    var builds      = $('[id*=bug][id*=resolvedBuild]').val().split(';');
    var buildMarks  = $('[id*=mark][id*=resolvedBuild]').val().split(';');
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
                    for(m in builds)
                    {
                        build = builds[m];
                        for(n in buildMarks)
                        {
                            buildMark = buildMarks[n];
                            html += '<br />' + rulesExample['bug']['resolve'].replace('%resolve%', resolve)
                                .replace('%bug%', bug)
                                .replace('%id%', id)
                                .replace('%split%', split)
                                .replace('%resolvedBuild%', build)
                                .replace('%buildmark%', buildMark);
                        }
                    }
                }
            }
        }
    }

    $('#example').html(html.substr(6));
}
