<?php
/**
 * The api module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id: zh-cn.php 5129 2013-07-15 00:16:07Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->api->common   = 'API接口';
$lang->api->getModel = '超级model调用接口';
$lang->api->sql      = 'SQL查询接口';
$lang->api->manage   = '接口管理';

$lang->api->index               = '接口空间';
$lang->api->editLib             = '编辑库';
$lang->api->releases            = '版本管理';
$lang->api->deleteRelease       = '删除版本';
$lang->api->deleteLib           = '删除库';
$lang->api->createRelease       = '发布接口';
$lang->api->createLib           = '创建库';
$lang->api->createApi           = '创建接口';
$lang->api->createAB            = '创建';
$lang->api->createDemo          = '导入禅道API';
$lang->api->edit                = '编辑接口';
$lang->api->delete              = '删除接口';
$lang->api->position            = '位置';
$lang->api->startLine           = "%s,%s行";
$lang->api->desc                = '描述';
$lang->api->debug               = '调试';
$lang->api->submit              = '提交';
$lang->api->url                 = '请求地址';
$lang->api->result              = '返回结果';
$lang->api->status              = '状态';
$lang->api->data                = '内容';
$lang->api->noParam             = 'GET方式调试不需要输入参数，';
$lang->api->noModule            = '库下没有目录，请先维护目录';
$lang->api->post                = 'POST方式调试请参照页面表单';
$lang->api->noUniqueName        = '库名已存在。';
$lang->api->noUniqueVersion     = '版本已存在。';
$lang->api->createStruct        = '创建数据结构';
$lang->api->editStruct          = '编辑数据结构';
$lang->api->deleteStruct        = '删除数据结构';
$lang->api->create              = '创建接口';
$lang->api->title               = '接口名称';
$lang->api->pageTitle           = '库';
$lang->api->module              = '目录';
$lang->api->apiDoc              = '接口';
$lang->api->manageType          = '维护目录';
$lang->api->managePublish       = '版本管理';
$lang->api->doing               = '开发中';
$lang->api->done                = '开发完成';
$lang->api->basicInfo           = '基本信息';
$lang->api->apiDesc             = '接口说明';
$lang->api->confirmDelete       = "您确定删除该接口吗？";
$lang->api->confirmDeleteLib    = "您确定删除该库吗？";
$lang->api->confirmDeleteStruct = "您确定删除该数据结构吗？";
$lang->api->filterStruct        = "使用数据结构填充";
$lang->api->defaultVersion      = "当前版本";
$lang->api->zentaoAPI           = "禅道API文档v1";
$lang->api->search              = "搜索";
$lang->api->allLibs             = "全部库";
$lang->api->noLinked            = "未关联{$lang->productCommon}和{$lang->projectCommon}";
$lang->api->addCatalog          = '添加目录';
$lang->api->editCatalog         = '编辑目录';
$lang->api->sortCatalog         = '目录排序';
$lang->api->deleteCatalog       = '删除目录';

/* Common access control lang. */
$lang->api->whiteList          = '白名单';
$lang->api->aclList['open']    = "公开 （有文档视图权限即可访问）";
$lang->api->aclList['default'] = "默认 （有所选%s访问权限用户可以访问）";
$lang->api->aclList['private'] = "私有 （仅创建者和白名单用户可访问）";
$lang->api->group              = '分组';
$lang->api->user               = '用户';

$lang->api->noticeAcl = array(
    'open'    => '所有人都可以访问',
    'custom'  => '白名单的用户可以访问',
    'private' => '只有创建者自己可以访问',
);

/* fields of struct */
$lang->struct = new stdClass();

$lang->struct->add             = '添加';
$lang->struct->field           = '字段';
$lang->struct->paramsType      = '类型';
$lang->struct->required        = '必填';
$lang->struct->desc            = '描述';
$lang->struct->descPlaceholder = '参数说明';
$lang->struct->action          = '操作';
$lang->struct->addSubField     = '添加子字段';
$lang->struct->list            = '数据结构列表';
$lang->struct->type            = 'Body类型';

$lang->struct->typeOptions = array(
    'formData' => 'FormData',
    'json'     => 'JSON',
    'array'    => 'Array',
    'object'   => 'Object',
);

/* fields of form */
$lang->api->struct             = '数据结构';
$lang->api->structName         = '结构名';
$lang->api->structType         = '类型';
$lang->api->structAttr         = '属性';
$lang->api->structAddedBy      = '创建人';
$lang->api->structAddedDate    = '创建时间';
$lang->api->name               = '库名称';
$lang->api->baseUrl            = '请求基础路径';
$lang->api->baseUrlDesc        = '网址或者路径，比如 http://test.zentao.net 或者 /v1';
$lang->api->desc               = '描述';
$lang->api->control            = '访问控制';
$lang->api->noLib              = '暂时没有库。';
$lang->api->noApi              = '暂时没有接口。';
$lang->api->noStruct           = '暂时没有数据结构。';
$lang->api->noRelease          = '暂时没有版本。';
$lang->api->lib                = '所属库';
$lang->api->apiList            = '接口列表';
$lang->api->formTitle          = '接口名称';
$lang->api->path               = '请求路径';
$lang->api->protocol           = '请求协议';
$lang->api->method             = '请求方式';
$lang->api->requestType        = '请求格式';
$lang->api->status             = '开发状态';
$lang->api->owner              = '负责人';
$lang->api->paramsExample      = '请求示例';
$lang->api->header             = '请求头';
$lang->api->query              = '请求参数';
$lang->api->params             = '请求体';
$lang->api->response           = '请求响应';
$lang->api->responseExample    = '响应示例';
$lang->api->id                 = 'ID';
$lang->api->addedBy            = '创建者';
$lang->api->addedDate          = '创建时间';
$lang->api->editedBy           = '修改者';
$lang->api->editedDate         = '修改时间';
$lang->api->version            = '版本号';
$lang->api->res                = new stdClass();
$lang->api->res->name          = '名称';
$lang->api->res->desc          = '描述';
$lang->api->res->type          = '类型';
$lang->api->req                = new stdClass();
$lang->api->req->name          = '名称';
$lang->api->req->desc          = '描述';
$lang->api->req->type          = '类型';
$lang->api->req->required      = '必填';
$lang->api->field              = '字段';
$lang->api->scope              = '位置';
$lang->api->paramsType         = '类型';
$lang->api->required           = '是否必填';
$lang->api->default            = '默认值';
$lang->api->desc               = '描述';
$lang->api->customType         = '自定义结构';
$lang->api->format             = '格式化';
$lang->api->libType            = '接口库类型';
$lang->api->product            = '所属' . $lang->productCommon;
$lang->api->project            = '所属' . $lang->projectCommon;

$lang->api->methodOptions      = array(
    'GET'     => 'GET',
    'POST'    => 'POST',
    'PUT'     => 'PUT',
    'DELETE'  => 'DELETE',
    'PATCH'   => 'PATCH',
    'OPTIONS' => 'OPTIONS',
    'HEAD'    => 'HEAD'
);

$lang->api->protocalOptions = array();
$lang->api->protocalOptions['HTTP']  = 'HTTP';
$lang->api->protocalOptions['HTTPS'] = 'HTTPS';
$lang->api->protocalOptions['WS']    = 'WS';
$lang->api->protocalOptions['WSS']   = 'WSS';

$lang->api->requestTypeOptions = array();
$lang->api->requestTypeOptions['application/json']                  = 'application/json';
$lang->api->requestTypeOptions['application/x-www-form-urlencoded'] = 'application/x-www-form-urlencoded';
$lang->api->requestTypeOptions['multipart/form-data']               = 'multipart/form-data';

$lang->api->libTypeList = array();
$lang->api->libTypeList['product'] = $lang->productCommon . '接口库';
$lang->api->libTypeList['project'] = $lang->projectCommon . '接口库';
$lang->api->libTypeList['nolink']  = '独立接口库';

$lang->api->statusOptions      = array(
    'done'   => '开发完成',
    'doing'  => '开发中',
    'hidden' => '不显示'
);
$lang->api->paramsScopeOptions = array(
    'formData' => 'formData',
    'path'     => 'path',
    'query'    => 'query',
    'body'     => 'body',
    'header'   => 'header',
    'cookie'   => 'cookie',
);
/* Api global common params */
$lang->api->paramsTypeOptions = array(
    'object'   => 'object',
    'array'    => 'array',
    'string'   => 'string',
    'date'     => 'date',
    'datetime' => 'datetime',
    'boolean'  => 'boolean',
    'int'      => 'int',
    'long'     => 'long',
    'float'    => 'float',
    'double'   => 'double',
    'decimal'  => 'decimal'
);

$lang->api->boolList = array(false => '否', true => '是', '' => '否');

/* Api params */
$lang->api->paramsTypeCustomOptions = array('file' => 'file', 'ref' => 'ref');

$lang->api->structParamsOptons   = array_merge($lang->api->paramsTypeOptions, array('file' => 'file', 'ref' => 'ref'));
$lang->api->allParamsTypeOptions = array_merge($lang->api->paramsTypeOptions, $lang->api->paramsTypeCustomOptions);
$lang->api->requiredOptions      = array(0 => '否', 1 => '是');

$lang->apistruct = new stdClass();
$lang->apistruct->name = '结构名';

$lang->api_lib_release = new stdClass();
$lang->api_lib_release->version = '版本号';

$lang->api->error             = new stdclass();
$lang->api->error->onlySelect = 'SQL查询接口只允许SELECT查询';
$lang->api->error->disabled   = '因为安全原因，该功能被禁用。可以到config目录，修改配置项 %s，打开此功能。';
$lang->api->error->notInput   = '因字段参数类型限制，暂不支持调试';
