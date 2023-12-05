<?php
/**
 * The api module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id: zh-tw.php 5129 2013-07-15 00:16:07Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->api->common   = 'API介面';
$lang->api->getModel = '超級model調用介面';
$lang->api->sql      = 'SQL查詢介面';

$lang->api->index            = '介面庫主頁';
$lang->api->editLib          = '編輯介面庫';
$lang->api->releases         = '版本管理';
$lang->api->deleteRelease    = '刪除版本';
$lang->api->deleteLib        = '刪除介面庫';
$lang->api->createRelease    = '發佈介面';
$lang->api->createLib        = '創建介面庫';
$lang->api->createApi        = '創建介面';
$lang->api->createAB         = '創建';
$lang->api->createDemo       = '導入禪道API';
$lang->api->edit             = '編輯介面';
$lang->api->delete           = '刪除介面';
$lang->api->position         = '位置';
$lang->api->startLine        = "%s,%s行";
$lang->api->desc             = '描述';
$lang->api->debug            = '調試';
$lang->api->submit           = '提交';
$lang->api->url              = '請求地址';
$lang->api->result           = '返回結果';
$lang->api->status           = '狀態';
$lang->api->data             = '內容';
$lang->api->noParam          = 'GET方式調試不需要輸入參數，';
$lang->api->noModule         = '介面庫下沒有目錄，請先維護目錄';
$lang->api->post             = 'POST方式調試請參照頁面表單';
$lang->api->noUniqueName     = '介面庫名已存在。';
$lang->api->noUniqueVersion  = '版本已存在。';
$lang->api->version          = '版本';
$lang->api->createStruct     = '創建資料結構';
$lang->api->editStruct       = '編輯資料結構';
$lang->api->deleteStruct     = '刪除資料結構';
$lang->api->create           = '創建介面';
$lang->api->title            = '介面名稱';
$lang->api->pageTitle        = '介面庫';
$lang->api->module           = '目錄';
$lang->api->apiDoc           = '介面';
$lang->api->manageType       = '維護目錄';
$lang->api->managePublish    = '版本管理';
$lang->api->doing            = '開發中';
$lang->api->done             = '開發完成';
$lang->api->basicInfo        = '基本信息';
$lang->api->apiDesc          = '介面說明';
$lang->api->confirmDelete    = "您確定刪除該介面嗎？";
$lang->api->confirmDeleteLib = "您確定刪除該介面庫嗎？";
$lang->api->filterStruct     = "使用資料結構填充";
$lang->api->defaultVersion   = "當前版本";
$lang->api->zentaoAPI        = "禪道API文檔v1";

/* Common access control lang. */
$lang->api->whiteList          = '白名單';
$lang->api->aclList['open']    = '公開';
$lang->api->aclList['private'] = '私有';
$lang->api->aclList['custom']  = '自定義';
$lang->api->group              = '分組';
$lang->api->user               = '用戶';

$lang->api->noticeAcl = array(
    'open'    => '所有人都可以訪問',
    'custom'  => '白名單的用戶可以訪問',
    'private' => '只有創建者自己可以訪問',
);

/* fields of struct */
$lang->struct = new stdClass();

$lang->struct->add             = '添加';
$lang->struct->field           = '欄位';
$lang->struct->paramsType      = '類型';
$lang->struct->required        = '必填';
$lang->struct->desc            = '描述';
$lang->struct->descPlaceholder = '參數說明';
$lang->struct->action          = '操作';
$lang->struct->addSubField     = '添加子欄位';

$lang->struct->typeOptions = array(
    'formData' => 'FormData',
    'json'     => 'JSON',
    'array'    => 'Array',
    'object'   => 'Object',
);

/* fields of form */
$lang->api->struct             = '資料結構';
$lang->api->structName         = '結構名';
$lang->api->structType         = '類型';
$lang->api->structAttr         = '屬性';
$lang->api->structAddedBy      = '創建人';
$lang->api->structAddedDate    = '創建時間';
$lang->api->name               = '介面庫名稱';
$lang->api->baseUrl            = '請求基路徑';
$lang->api->baseUrlDesc        = '網址或者路徑，比如 test.zentao.net 或者 /v1';
$lang->api->desc               = '描述';
$lang->api->control            = '訪問控制';
$lang->api->noLib              = '暫時沒有介面庫。';
$lang->api->noApi              = '暫時沒有介面。';
$lang->api->noStruct           = '暫時沒有資料結構。';
$lang->api->lib                = '所屬介面庫';
$lang->api->apiList            = '介面列表';
$lang->api->formTitle          = '介面名稱';
$lang->api->path               = '請求路徑';
$lang->api->protocol           = '請求協議';
$lang->api->method             = '請求方式';
$lang->api->requestType        = '請求格式';
$lang->api->status             = '開髮狀態';
$lang->api->owner              = '負責人';
$lang->api->paramsExample      = '請求示例';
$lang->api->header             = '請求頭';
$lang->api->query              = '請求參數';
$lang->api->params             = '請求體';
$lang->api->response           = '請求響應';
$lang->api->responseExample    = '響應示例';
$lang->api->res                = new stdClass();
$lang->api->res->name          = '名稱';
$lang->api->res->desc          = '描述';
$lang->api->res->type          = '類型';
$lang->api->req                = new stdClass();
$lang->api->req->name          = '名稱';
$lang->api->req->desc          = '描述';
$lang->api->req->type          = '類型';
$lang->api->req->required      = '必填';
$lang->api->field              = '欄位';
$lang->api->scope              = '位置';
$lang->api->paramsType         = '類型';
$lang->api->required           = '是否必填';
$lang->api->default            = '預設值';
$lang->api->desc               = '描述';
$lang->api->customType         = '自定義結構';
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

$lang->api->protocalOptions = array();
$lang->api->protocalOptions['HTTP']  = 'HTTP';
$lang->api->protocalOptions['HTTPS'] = 'HTTPS';
$lang->api->protocalOptions['WS']    = 'WS';
$lang->api->protocalOptions['WSS']   = 'WSS';

$lang->api->requestTypeOptions = array();
$lang->api->requestTypeOptions['application/json']                  = 'application/json';
$lang->api->requestTypeOptions['application/x-www-form-urlencoded'] = 'application/x-www-form-urlencoded';
$lang->api->requestTypeOptions['multipart/form-data']               = 'multipart/form-data';

$lang->api->statusOptions      = array(
    'done'   => '開發完成',
    'doing'  => '開發中',
    'hidden' => '不顯示'
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
$lang->apistruct->name = '結構名';

$lang->api_lib_release = new stdClass();
$lang->api_lib_release->version = '版本';

$lang->api->error             = new stdclass();
$lang->api->error->onlySelect = 'SQL查詢介面只允許SELECT查詢';
$lang->api->error->disabled   = '因為安全原因，該功能被禁用。可以到config目錄，修改配置項 %s，打開此功能。';
