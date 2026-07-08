<?php
$lang->artifact->browse              = '制品库列表';
$lang->artifact->create              = '创建制品库';
$lang->artifact->edit                = '编辑制品库';
$lang->artifact->delete              = '删除制品库';
$lang->artifact->repoBrowser         = '制品库内容';
$lang->artifact->createDir           = '添加目录';
$lang->artifact->uploadArtifact      = '上传制品';
$lang->artifact->addSubDir           = '添加子目录';
$lang->artifact->addSiblingDir       = '添加同级目录';
$lang->artifact->editDir             = '编辑目录';
$lang->artifact->deleteDir           = '删除目录';
$lang->artifact->editArtifact        = '修改制品信息';
$lang->artifact->moveArtifact        = '移动制品';
$lang->artifact->deleteArtifact      = '删除制品';
$lang->artifact->batchDeleteArtifact = '批量删除制品';
$lang->artifact->copyCMD             = '复制命令';
$lang->artifact->copied              = '复制成功';

$lang->artifact->name          = '名称';
$lang->artifact->path          = '所属目录';
$lang->artifact->type          = '类型';
$lang->artifact->size          = '大小';
$lang->artifact->version       = '版本';
$lang->artifact->arch          = '系统/架构';
$lang->artifact->creator       = '创建者';
$lang->artifact->createdDate   = '创建时间';
$lang->artifact->editor        = '最后更新';
$lang->artifact->editedDate    = '最后更新时间';
$lang->artifact->action        = '操作';
$lang->artifact->folder        = '文件夹';
$lang->artifact->file          = '文件';
$lang->artifact->emptyFolder   = '当前目录下暂无文件或文件夹';
$lang->artifact->expandAll     = '全部展开';
$lang->artifact->collapseAll   = '全部收起';
$lang->artifact->hideTree      = '隐藏目录树';
$lang->artifact->showTree      = '显示目录树';
$lang->artifact->more          = '更多';
$lang->artifact->settings      = '设置';
$lang->artifact->addDirectory  = '添加目录';
$lang->artifact->download      = '下载';
$lang->artifact->rename        = '重命名';
$lang->artifact->move          = '移动';
$lang->artifact->switch        = '切换当前层级';
$lang->artifact->actionMockTip = '当前为模拟操作：%s';
$lang->artifact->dirName       = '目录名称';
$lang->artifact->format        = '制品库类型';
$lang->artifact->hasVersion    = '需要进行版本控制';
$lang->artifact->checkValue    = '校验值';
$lang->artifact->okBtn         = '确定';
$lang->artifact->history       = '历史记录';
$lang->artifact->artifactRepo  = '制品库';
$lang->artifact->parent        = '所属上级';
$lang->artifact->repo          = '所属代码库';
$lang->artifact->package       = '包名';
$lang->artifact->asset         = '制品';

$lang->artifact->countArtifact = '共%s个制品';

$lang->artifact->actionComment = new stdclass();
$lang->artifact->actionComment->moved     = '从制品库 <strong>%s</strong> 的目录 <strong>%s</strong> 移动到制品库 <strong>%s</strong> 的目录 <strong>%s<strong>。';
$lang->artifact->actionComment->editedDir = '从制品库 <strong>%s</strong> 的 <strong>%s</strong> 修改为制品库 <strong>%s</strong> 的 <strong>%s</strong>。';
$lang->artifact->actionComment->edited    = '从 <strong>%s</strong> 重命名为 <strong>%s</strong>。';

$lang->artifact->placeholder = new stdclass();
$lang->artifact->placeholder->name = '请输入制品库名称';

$lang->artifact->notice = new stdclass();
$lang->artifact->notice->deleteConfirm         = '您确定要删除该制品库吗？';
$lang->artifact->notice->noArtifact            = '暂无制品库';
$lang->artifact->notice->emptyAsset            = '暂无制品';
$lang->artifact->notice->nameNotSupportChinese = '名称仅支持英文，数字，下划线（_），中横线（-），英文句号（.）';
$lang->artifact->notice->dirNameFormatError    = '名称仅支持中文，英文，数字，下划线（_），中横线（-）';
$lang->artifact->notice->assetNameFormatError  = '名称不能包含\/:*?"<>|';
$lang->artifact->notice->confirmDelete         = '删除后文件将会在回收站保留30天，超时后将无法恢复。';
$lang->artifact->notice->confirmDeleteDir      = '删除目录后，同步删除目录下的子目录和文件，确认要删除吗?';
$lang->artifact->notice->rootNotAllowed        = '移动制品时不能选择根目录。';
$lang->artifact->notice->dirNameTooLong        = '目录名称不能超过15个字符。';

$lang->artifact->featureBar['browse']['all']   = '全部';
$lang->artifact->featureBar['browse']['space'] = '空间制品库';
$lang->artifact->featureBar['browse']['repo']  = '代码库制品库';

$lang->artifact->typeList = array();
$lang->artifact->typeList['repo']  = '代码库';
$lang->artifact->typeList['space'] = '空间';

$lang->artifact->formatList = array();
$lang->artifact->formatList['file']      = '通用文件仓库';
$lang->artifact->formatList['container'] = '镜像仓库';
//$lang->artifact->formatList['helm']      = 'Helm仓库';
//$lang->artifact->formatList['maven']     = 'Maven仓库';
//$lang->artifact->formatList['npm']       = 'NPM仓库';
