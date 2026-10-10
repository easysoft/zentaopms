<?php
$lang->runner->manageRunner = 'Runner管理';
$lang->runner->browse       = 'Runner列表';
$lang->runner->create       = '添加Runner';
$lang->runner->createGuide  = '添加Runner引导页';
$lang->runner->edit         = '编辑Runner';
$lang->runner->enable       = '启用';
$lang->runner->disable      = '停用';
$lang->runner->delete       = '删除Runner';
$lang->runner->changeState  = '启用/停用Runner';

$lang->runner->name        = '名称';
$lang->runner->status      = '状态';
$lang->runner->platOrArch  = '平台/架构';
$lang->runner->plat        = '平台';
$lang->runner->arch        = '架构';
$lang->runner->version     = '版本';
$lang->runner->ip          = 'IP地址';
$lang->runner->package     = '安装包';
$lang->runner->cmd         = '部署命令';
$lang->runner->copyCmd     = '复制命令';
$lang->runner->copySuccess = '复制成功';
$lang->runner->copyFail    = '浏览器不支持复制功能，请手动复制';
$lang->runner->desc        = '描述';
$lang->runner->labels      = '标签';
$lang->runner->runtime     = '运行方式';

$lang->runner->statusList = array();
$lang->runner->statusList['online']  = '在线';
$lang->runner->statusList['offline'] = '离线';
$lang->runner->statusList['suspend'] = '停用';

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
# 注意：请以管理员身份运行 PowerShell，并先切换到目标安装目录再执行以下命令
# 1. 下载runner及依赖工具到指定的路径
powershell -command "Invoke-WebRequest -Uri '%PACKAGE_URL%' -OutFile 'runner-plugin-installer.exe'"

# 2. 赋予执行权限后执行工具安装
Unblock-File .\\runner-plugin-installer.exe; .\\runner-plugin-installer.exe install

# 3. 安装服务
.\\install.bat %GITFOX_URL% %GITFOX_TOKEN% %RUNNER_RUNTIME% %RUNNER_LABELS%
EOF;
$lang->runner->cmdList['linux'] = <<<EOF
# 1. 下载runner及依赖工具到指定的路径
sudo curl --output "runner-plugin-installer" "%PACKAGE_URL%"

# 2. 赋予执行权限后执行工具安装
sudo chmod +x runner-plugin-installer && sudo ./runner-plugin-installer install

# 3. 安装服务
sudo gitfox-runner install --url=%GITFOX_URL% --token=%GITFOX_TOKEN% --runtime=%RUNNER_RUNTIME% %RUNNER_LABELS%

# 4. 启动服务
sudo gitfox-runner start
EOF;
$lang->runner->cmdList['docker'] = <<<'EOF'
docker run -d --name gitfox-runner --restart always
  -v /srv/gitfox-runner/config:/etc/gitfox-runner
  -v /var/run/docker.sock:/var/run/docker.sock
  gitfox/gitfox-runner:latest
EOF;
$lang->runner->cmdList['k8s'] = <<<EOF
# 1. 添加仓库源
helm repo add gitfox https://hub.qucheng.com/chartrepo/stable

# 2. 安装
helm install --namespace <NAMESPACE> --name gitfox-runner -f <CONFIG_VALUES_FILE> gitfox/gitfox-runner
EOF;

$lang->runner->notice = new stdclass();
$lang->runner->notice->confirmDelete    = '您确定要删除该Runner吗？';
$lang->runner->notice->confirmDisable   = '您确定要停用该Runner吗？';
$lang->runner->notice->disableDelete    = '在线状态的Runner不可删除';
$lang->runner->notice->nameLength       = '名称不能超过200个字符。';
$lang->runner->notice->descLength       = '描述不能超过500个字符。';
$lang->runner->notice->newLabelsInvalid = '只允许输入英文、数字、下划线、点、中横线、中文';

$lang->runner->apiError = array();
