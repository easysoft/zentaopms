<?php
$lang->artifact->browse         = '代码库制品库';
$lang->artifact->create         = '创建制品库';
$lang->artifact->edit           = '编辑制品库';
$lang->artifact->delete         = '删除制品库';
$lang->artifact->repoBrowser    = '制品库内容';
$lang->artifact->createDir      = '添加目录';
$lang->artifact->uploadArtifact = '上传制品';
$lang->artifact->addSubDir      = '添加子目录';
$lang->artifact->addSiblingDir  = '添加同级目录';
$lang->artifact->editDir        = '编辑目录';
$lang->artifact->deleteDir      = '删除目录';
$lang->artifact->editArtifact   = '修改制品信息';
$lang->artifact->deleteArtifact = '删除制品';

$lang->artifact->name          = '名称';
$lang->artifact->path          = '当前路径';
$lang->artifact->type          = '类型';
$lang->artifact->size          = '大小';
$lang->artifact->creator       = '创建者';
$lang->artifact->createdDate   = '创建时间';
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
$lang->artifact->history       = '记录';

$lang->artifact->countArtifact = '共%s个制品';

$lang->artifact->placeholder = new stdclass();
$lang->artifact->placeholder->name = '请输入制品库名称';

$lang->artifact->notice = new stdclass();
$lang->artifact->notice->deleteConfirm         = '您确定要删除该制品库吗？';
$lang->artifact->notice->noArtifact            = '暂无制品库';
$lang->artifact->notice->emptyFolder           = '空目录';
$lang->artifact->notice->nameNotSupportChinese = '名称仅支持英文，数字，下划线（_），中横线（-），英文句号（.）';
$lang->artifact->notice->dirNameFormatError    = '目录名称仅支持中文，英文，数字，下划线（_），中横线（-）';
$lang->artifact->notice->confirmDelete         = '删除后文件将会在回收站保留30天，超时后将无法恢复。';
$lang->artifact->notice->confirmDeleteDir      = '删除目录后，同步删除目录下的子目录和文件，确认要删除吗?';

$lang->artifact->featureBar['browse']['all']   = '全部';
$lang->artifact->featureBar['browse']['space'] = '空间制品库';
$lang->artifact->featureBar['browse']['repo']  = '代码库制品库';

$lang->artifact->typeList = array();
$lang->artifact->typeList['repo']  = '代码库';
$lang->artifact->typeList['space'] = '空间';

$lang->artifact->formatList = array();
$lang->artifact->formatList['file']      = '通用文件仓库';
$lang->artifact->formatList['container'] = '镜像仓库';
$lang->artifact->formatList['helm']      = 'Helm仓库';
$lang->artifact->formatList['maven']     = 'Maven仓库';
$lang->artifact->formatList['npm']       = 'NPM仓库';
