<?php
$lang->runner->manageRunner = 'Runner Management';
$lang->runner->browse       = 'Runner List';
$lang->runner->create       = 'Add Runner';
$lang->runner->createGuide  = 'Add Runner Guide';
$lang->runner->edit         = 'Edit Runner';
$lang->runner->enable       = 'Enable';
$lang->runner->disable      = 'Suspend';
$lang->runner->delete       = 'Delete Runner';
$lang->runner->changeState  = 'Enable/Suspend Runner';

$lang->runner->name        = 'Name';
$lang->runner->status      = 'Status';
$lang->runner->platOrArch  = 'Platform/Arch';
$lang->runner->plat        = 'Platform';
$lang->runner->arch        = 'Arch';
$lang->runner->version     = 'Version';
$lang->runner->ip          = 'IP Address';
$lang->runner->package     = 'Package';
$lang->runner->cmd         = 'Command';
$lang->runner->copyCmd     = 'Copy command';
$lang->runner->copySuccess = 'Copied';
$lang->runner->copyFail    = 'Copying not supported by your browser. Please copy manually.';
$lang->runner->desc        = 'Description';
$lang->runner->labels      = 'Labels';
$lang->runner->runtime     = 'Runtime';

$lang->runner->statusList = array();
$lang->runner->statusList['online']  = 'Online';
$lang->runner->statusList['offline'] = 'Offline';
$lang->runner->statusList['suspend'] = 'Suspend';

$lang->runner->osList = array();
$lang->runner->osList['linux']   = 'Linux';
$lang->runner->osList['windows'] = 'Windows';

$lang->runner->typeList = array();
$lang->runner->typeList['docker'] = 'Docker';
$lang->runner->typeList['k8s']    = 'Kubernetes';

$lang->runner->archList = array();
$lang->runner->archList['amd64'] = 'amd64';
$lang->runner->archList['arm64'] = 'arm64';

$lang->runner->cmdList = array();
$lang->runner->cmdList['windows'] = <<<EOF
# 1. Download runner and dependency tools to specified path
powershell -command "Invoke-WebRequest -Uri '%PACKAGE_URL%' -OutFile 'runner-plugin-installer.exe'"

# 2. Grant execute permission and run the installer
Unblock-File .\\runner-plugin-installer.exe; .\\runner-plugin-installer.exe install

# 3. Install service
.\install.bat %GITFOX_URL% %GITFOX_TOKEN% %RUNNER_RUNTIME% %RUNNER_LABELS%
EOF;
$lang->runner->cmdList['linux'] = <<<EOF
# 1. Download runner and dependency tools to specified path
sudo curl --output "runner-plugin-installer" "%PACKAGE_URL%"

# 2. Grant execute permission and run the installer
sudo chmod +x runner-plugin-installer && ./runner-plugin-installer install

# 3. Install service
sudo gitfox-runner install --url=%GITFOX_URL% --token=%GITFOX_TOKEN% --runtime=%RUNNER_RUNTIME% %RUNNER_LABELS%

# 4. Start service
sudo gitfox-runner start
EOF;
$lang->runner->cmdList['docker'] = <<<EOF
docker run -d --name gitfox-runner --restart always \
    -v /srv/gitfox-runner/config:/etc/gitfox-runner \
    -v /var/run/docker.sock:/var/run/docker.sock \
    gitfox/gitfox-runner:latest
EOF;
$lang->runner->cmdList['k8s'] = <<<EOF
# 1. Add repository source
helm repo add gitfox https://hub.qucheng.com/chartrepo/stable

# 2. Install
helm install --namespace <NAMESPACE> --name gitfox-runner -f <CONFIG_VALUES_FILE> gitfox/gitfox-runner
EOF;

$lang->runner->notice = new stdclass();
$lang->runner->notice->confirmDelete    = 'Are you sure to delete this Runner?';
$lang->runner->notice->confirmDisable   = 'Are you sure to suspend this Runner?';
$lang->runner->notice->disableDelete    = 'Online status runner cannot be deleted';
$lang->runner->notice->nameLength       = 'Name cannot exceed 200 characters';
$lang->runner->notice->descLength       = 'Description cannot exceed 500 characters';
$lang->runner->notice->newLabelsInvalid = 'Only letters, digits, underscores, dots, hyphens and Chinese characters are allowed.';

$lang->runner->apiError = array();
