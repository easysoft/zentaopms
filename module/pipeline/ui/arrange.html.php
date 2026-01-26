<?php
namespace zin;

$backUrl = createLink('job', "browse", "repoID={$repo->id}");

panel
(
    zui::FlowApp
    (
        set::height('calc(100vh - 96px)'),
        set::goBack(jsRaw("() => {goBack('pipeline-browse', '{$backUrl}')}")),
        set::id($jobID),
        set::name($jobName),
        set::labels($lang->job->flowApp->labels),
    )
);
