<?php
$lang->provider->browse = '浏览服务';
$lang->provider->create = '添加服务';
$lang->provider->edit   = '编辑服务';
$lang->provider->delete = '删除服务';

$lang->provider->browseAction = '服务列表';
$lang->provider->createAction = '新建服务';
$lang->provider->editAction   = '编辑服务';
$lang->provider->deleteAction = '删除服务';

$lang->provider->name        = '服务名称';
$lang->provider->type        = '服务类型';
$lang->provider->url         = '服务器地址';
$lang->provider->token       = '令牌';
$lang->provider->account     = '用户名';
$lang->provider->createdBy   = '创建人';
$lang->provider->createdDate = '创建时间';

$lang->provider->error = new stdclass();
$lang->provider->error->api            = '『服务器地址』无法访问服务器。';
$lang->provider->error->apiWithMessage = '『服务器地址』无法访问服务器：%s';
$lang->provider->error->svnClient      = '无法找到 Subversion 客户端。';

$lang->provider->typeList = array();
$lang->provider->typeList['GitLab']     = 'GitLab';
$lang->provider->typeList['Gitea']      = 'Gitea';
$lang->provider->typeList['Gogs']       = 'Gogs';
$lang->provider->typeList['Subversion'] = 'Subversion';
$lang->provider->typeList['Jenkins']    = 'Jenkins';

$lang->provider->notice = new stdclass();
$lang->provider->notice->confirmDelete = '你确定要删除该服务吗？';
$lang->provider->notice->emptyProvider = '暂无服务。';
$lang->provider->notice->svnPath       = '服务器地址或文件路径';
$lang->provider->notice->hasRepos      = '该服务已经关联了仓库, 请先删除关联的仓库。';
