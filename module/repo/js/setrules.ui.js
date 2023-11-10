$(function()
{
    replaceExample();
    $(document).on('keyup', 'input', function(){replaceExample()});
});

function replaceExample()
{
    let html = '';
    let startTask    = $('[id*=start]').val().split(';');
    let taskModule   = $('[id*=module][id*=task]').val().split(';');
    let idMark       = $('[id*=id][id*=mark]').val().split(';');
    let idSplit      = $('[id*=id][id*=split]').val().split(';');
    let costs        = $('[id*=task][id*=consumed]').val().split(';');
    let consumedmark = $('[id*=mark][id*=consumed]').val().split(';');
    let lefts        = $('[id*=task][id*=left]').val().split(';');
    let leftMarks    = $('[id*=mark][id*=left]').val().split(';');
    let cunits       = $('[id*=unit][id*=consumed]').val().split(';');
    let lunits       = $('[id*=unit][id*=left]').val().split(';');

    for(let i in startTask)
    {
        start = startTask[i];
        for(let j in taskModule)
        {
            task = taskModule[j];
            for(let k in idMark)
            {
                id = idMark[k];
                for(let l in idSplit)
                {
                    split = idSplit[l];
                    for(let m in costs)
                    {
                        cost = costs[m];
                        for(let n in consumedmark)
                        {
                            consumed = consumedmark[n];
                            for(let o in lefts)
                            {
                                left = lefts[o];
                                for(let p in leftMarks)
                                {
                                    leftMark = leftMarks[p];
                                    for(let q in cunits)
                                    {
                                        cunit = cunits[q];
                                        for(let r in lunits)
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

    let finishTask = $('[id*=finish]').val().split(';');
    for(let i in finishTask)
    {
        finish = finishTask[i];
        for(let j in taskModule)
        {
            task = taskModule[j];
            for(let k in idMark)
            {
                id = idMark[k];
                for(let l in idSplit)
                {
                    split = idSplit[l];
                    for(let m in costs)
                    {
                        cost = costs[m];
                        for(let n in consumedmark)
                        {
                            consumed = consumedmark[n];
                            for(let o in cunits)
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

    let effortTask = $('[id*=logEfforts]').val().split(';');
    for(let i in effortTask)
    {
        effort = effortTask[i];
        for(let j in taskModule)
        {
            task = taskModule[j];
            for(let k in idMark)
            {
                id = idMark[k];
                for(let l in idSplit)
                {
                    split = idSplit[l];
                    for(let m in costs)
                    {
                        cost = costs[m];
                        for(let n in consumedmark)
                        {
                            consumed = consumedmark[n];
                            for(let o in lefts)
                            {
                                left = lefts[o];
                                for(let p in leftMarks)
                                {
                                    leftMark = leftMarks[p];
                                    for(let q in cunits)
                                    {
                                        cunit = cunits[q];
                                        for(let r in lunits)
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

    let resolveBug  = $('[id*=bug][id*="resolve"]').val().split(';');
    let bugModule   = $('[id*=module][id*=bug]').val().split(';');
    for(let i in resolveBug)
    {
        resolve = resolveBug[i];
        for(let j in bugModule)
        {
            bug = bugModule[j];
            for(let k in idMark)
            {
                id = idMark[k];
                for(let l in idSplit)
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
