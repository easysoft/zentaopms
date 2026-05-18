<?php
$lang->artifact->browse         = 'Repo Artifact Repository';
$lang->artifact->create         = 'Create Artifact Repository';
$lang->artifact->edit           = 'Edit Artifact Repository';
$lang->artifact->delete         = 'Delete Artifact Repository';
$lang->artifact->repoBrowser    = 'Repository Content';
$lang->artifact->createDir      = 'Add Directory';
$lang->artifact->uploadArtifact = 'Upload Artifact';
$lang->artifact->addSubDir      = 'Add Sub Directory';
$lang->artifact->addSiblingDir  = 'Add Sibling Directory';
$lang->artifact->editDir        = 'Edit Directory';
$lang->artifact->deleteDir      = 'Delete Directory';
$lang->artifact->editArtifact   = 'Edit Artifact';
$lang->artifact->moveArtifact   = 'Move Artifact';
$lang->artifact->deleteArtifact = 'Delete Artifact';
$lang->artifact->batchDeleteArtifact = 'Batch Delete Artifacts';

$lang->artifact->name          = 'Name';
$lang->artifact->path          = 'Current Path';
$lang->artifact->type          = 'Type';
$lang->artifact->size          = 'Size';
$lang->artifact->version       = 'Version';
$lang->artifact->arch          = 'System/Arch';
$lang->artifact->creator       = 'Creator';
$lang->artifact->createdDate   = 'Created Date';
$lang->artifact->editor        = 'Last Editor';
$lang->artifact->editedDate    = 'Last Edit Date';
$lang->artifact->action        = 'Actions';
$lang->artifact->folder        = 'Folder';
$lang->artifact->file          = 'File';
$lang->artifact->emptyFolder   = 'No files or folders in this directory';
$lang->artifact->expandAll     = 'Expand All';
$lang->artifact->collapseAll   = 'Collapse All';
$lang->artifact->hideTree      = 'Hide Tree';
$lang->artifact->showTree      = 'Show Tree';
$lang->artifact->more          = 'More';
$lang->artifact->settings      = 'Settings';
$lang->artifact->addDirectory  = 'Add Directory';
$lang->artifact->download      = 'Download';
$lang->artifact->rename        = 'Rename';
$lang->artifact->move          = 'Move';
$lang->artifact->switch        = 'Switch';
$lang->artifact->actionMockTip = 'Mock action: %s';
$lang->artifact->dirName       = 'Directory Name';
$lang->artifact->format        = 'Artifact Type';
$lang->artifact->hasVersion    = 'Need to do version control';
$lang->artifact->checkValue    = 'Check Value';
$lang->artifact->okBtn         = 'OK';
$lang->artifact->history       = 'History';
$lang->artifact->artifactRepo  = 'Artifact Repo';
$lang->artifact->parent        = 'Parent';

$lang->artifact->countArtifact = 'Total %s Artifact';

$lang->artifact->placeholder = new stdclass();
$lang->artifact->placeholder->name = 'Enter Artifact Repository Name';

$lang->artifact->notice = new stdclass();
$lang->artifact->notice->deleteConfirm         = 'Are you sure to delete this Artifact Repository?';
$lang->artifact->notice->noArtifact            = 'No Artifact Repository';
$lang->artifact->notice->emptyFolder           = 'Empty Folder';
$lang->artifact->notice->nameNotSupportChinese = 'Name only supports English, numbers, underscores (_), dashes (-), and periods (.).';
$lang->artifact->notice->dirNameFormatError    = 'Directory name only supports Chinese, English, numbers, underscores (_), dashes (-).';
$lang->artifact->notice->confirmDelete         = 'Delete after the file will be in the recycle bin for 30 days. After the timeout, the file will be unable to be restored.';
$lang->artifact->notice->confirmDeleteDir      = 'Delete directory and all sub directories and files. Are you sure to delete it?';
$lang->artifact->notice->rootNotAllowed        = 'Root directory cannot be selected when moving an artifact.';

$lang->artifact->featureBar['browse']['all']   = 'All';
$lang->artifact->featureBar['browse']['space'] = 'Space Artifact Repository';
$lang->artifact->featureBar['browse']['repo']  = 'Repo Artifact Repository';

$lang->artifact->typeList = array();
$lang->artifact->typeList['repo']  = 'Repo';
$lang->artifact->typeList['space'] = 'Space';

$lang->artifact->formatList = array();
$lang->artifact->formatList['generic']   = 'Common File Repository';
$lang->artifact->formatList['container'] = 'Image Repository';
$lang->artifact->formatList['helm']      = 'Helm Repository';
$lang->artifact->formatList['maven']     = 'Maven Repository';
$lang->artifact->formatList['npm']       = 'NPM Repository';
