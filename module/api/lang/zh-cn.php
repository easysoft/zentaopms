<?php
/**
 * The api module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id: zh-cn.php 5129 2013-07-15 00:16:07Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->api           = new stdclass();
$lang->api->common   = 'API接口';
$lang->api->getModel = '超级model调用接口';
$lang->api->sql      = 'SQL查询接口';

$lang->api->edit             = '编辑';
$lang->api->delete           = '删除';
$lang->api->position         = '位置';
$lang->api->startLine        = "%s,%s行";
$lang->api->desc             = '描述';
$lang->api->debug            = '调试';
$lang->api->submit           = '提交';
$lang->api->url              = '请求地址';
$lang->api->result           = '返回结果';
$lang->api->status           = '状态';
$lang->api->data             = '内容';
$lang->api->noParam          = 'GET方式调试不需要输入参数，';
$lang->api->noModule         = '接口库下没有目录，请先维护目录';
$lang->api->post             = 'POST方式调试请参照页面表单';
$lang->api->noUniqueName     = '接口库名已存在。';
$lang->api->createLib        = '创建接口库';
$lang->api->createStruct     = '创建数据结构';
$lang->api->editStruct       = '修改数据结构';
$lang->api->editLib          = '编辑接口库';
$lang->api->deleteLib        = '删除接口库';
$lang->api->create           = '创建文档';
$lang->api->createApi        = '创建接口';
$lang->api->title            = '接口库';
$lang->api->module           = '模块';
$lang->api->apiDoc           = '接口';
$lang->api->manageType       = '维护模块';
$lang->api->doing            = '开发中';
$lang->api->done             = '开发完成';
$lang->api->basicInfo        = '基本信息';
$lang->api->principal        = '负责人';
$lang->api->apiDesc          = '接口说明';
$lang->api->confirmDelete    = "您确定删除该接口吗？";
$lang->api->confirmDeleteLib = "您确定删除该接口库吗？";
$lang->api->filterStruct     = "使用数据结构填充";

/* fields of struct */
$lang->struct = new stdClass();

$lang->struct->field      = '字段';
$lang->struct->paramsType = '类型';
$lang->struct->desc       = '描述';

/* fields of form */
$lang->api->struct             = '数据结构';
$lang->api->structName         = '结构名';
$lang->api->structAttr         = '属性';
$lang->api->name               = '接口库名称';
$lang->api->baseUrl            = '请求基路径';
$lang->api->desc               = '描述';
$lang->api->control            = '访问控制';
$lang->api->noLib              = '暂时没有接口库。';
$lang->api->noApi              = '暂时没有接口。';
$lang->api->lib                = '所属接口库';
$lang->api->module             = '所属模块';
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
$lang->api->responseExample    = '响应实例';
$lang->api->res                = new stdClass();
$lang->api->res->name          = '名称';
$lang->api->res->desc          = '描述';
$lang->api->res->type          = '类型';
$lang->api->field              = '字段';
$lang->api->scope              = '位置';
$lang->api->paramsType         = '类型';
$lang->api->required           = '是否必填';
$lang->api->default            = '默认值';
$lang->api->desc               = '描述';
$lang->api->customType         = '自定义结构';
$lang->api->format             = '格式化';
$lang->api->methodOptions      = array(
    'GET'     => 'GET',
    'POST'    => 'POST',
    'PUT'     => 'PUT',
    'DELETE'  => 'DELETE',
    'PATCH'   => 'PATCH',
    'OPTIONS' => 'OPTIONS',
    'HEAD'    => 'HEAD'
);
$lang->api->protocalOptions    = array(
    'HTTP'  => 'HTTP',
    'HTTPS' => 'HTTPS',
);
$lang->api->requestTypeOptions = array(
    'application/json'                  => 'application/json',
    'application/x-www-form-urlencoded' => 'application/x-www-form-urlencoded',
    'multipart/form-data'               => 'multipart/form-data'
);
$lang->api->statusOptions      = array(
    'doing'  => '开发中',
    'done'   => '开发完成',
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
/* Api params */
$lang->api->paramsTypeCustomOptions = array(
    'file' => 'file',
    'ref'  => 'ref',
);
$lang->api->structParamsOptons      = array_merge($lang->api->paramsTypeOptions, array('file' => 'file', 'ref' => 'ref'));
$lang->api->allParamsTypeOptions    = array_merge($lang->api->paramsTypeOptions, $lang->api->paramsTypeCustomOptions);
$lang->api->requiredOptions         = [
    0 => '否',
    1 => '是',
];

$lang->doclib       = new stdclass();
$lang->doclib->name = '接口库名称';

$lang->api->error             = new stdclass();
$lang->api->error->onlySelect = 'SQL查询接口只允许SELECT查询';
$lang->api->error->disabled   = '因为安全原因，该功能被禁用。可以到config目录，修改配置项 %s，打开此功能。';
