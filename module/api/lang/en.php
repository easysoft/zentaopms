<?php
/**
 * The api module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id: English.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->api->common   = 'API';
$lang->api->getModel = 'Super Model API';
$lang->api->sql      = 'SQL Query API';
$lang->api->manage   = 'API management';

$lang->api->index               = 'API Space';
$lang->api->editLib             = 'Edit';
$lang->api->releases            = 'Release';
$lang->api->deleteRelease       = 'Delete Release';
$lang->api->deleteLib           = 'Delete API Doc';
$lang->api->createRelease       = 'Publish';
$lang->api->createLib           = 'Create API Library';
$lang->api->createApi           = 'Create API Document';
$lang->api->createAB            = 'Create';
$lang->api->createDemo          = 'Import ZenTao Library';
$lang->api->edit                = 'Edit';
$lang->api->delete              = 'Delete';
$lang->api->position            = 'Position';
$lang->api->startLine           = "%s,%s";
$lang->api->desc                = 'Description';
$lang->api->debug               = 'Debug';
$lang->api->submit              = 'Submit';
$lang->api->url                 = 'URL';
$lang->api->result              = 'Result';
$lang->api->status              = 'Status';
$lang->api->data                = 'Content';
$lang->api->noParam             = 'Get debugging does not require input parameters，';
$lang->api->noModule            = 'No directory in the API library. Please add the directory first';
$lang->api->post                = 'Please refer to the page form for post debugging';
$lang->api->noUniqueName        = 'The API library name exists.';
$lang->api->noUniqueVersion     = 'The version exists.';
$lang->api->createStruct        = 'Create Data Structure';
$lang->api->editStruct          = 'Edit Data Structure';
$lang->api->deleteStruct        = 'Delete Data Structure';
$lang->api->create              = 'Create API';
$lang->api->title               = 'Name';
$lang->api->pageTitle           = 'API Library';
$lang->api->module              = 'Directory';
$lang->api->apiDoc              = 'API';
$lang->api->manageType          = 'Manage Directory';
$lang->api->managePublish       = 'Manage Version';
$lang->api->doing               = 'Doing';
$lang->api->done                = 'Done';
$lang->api->basicInfo           = 'Basic Information';
$lang->api->apiDesc             = 'Description';
$lang->api->confirmDelete       = "Do you want to delete this API?";
$lang->api->confirmDeleteLib    = "Do you want to delete this interface library?";
$lang->api->confirmDeleteStruct = "Do you want to delete this data struct?";
$lang->api->filterStruct        = "use struct";
$lang->api->defaultVersion      = "Current Version";
$lang->api->zentaoAPI           = "Zentao API v1";
$lang->api->search              = "Search";
$lang->api->allLibs             = "AllLibs";
$lang->api->noLinked            = "No Linked {$lang->productCommon} and {$lang->projectCommon}";
$lang->api->addCatalog          = 'Add Catalog';
$lang->api->editCatalog         = 'Edit Catalog';
$lang->api->sortCatalog         = 'Catalog Sorting';
$lang->api->deleteCatalog       = 'Delete Catalog';

/* Common access control lang. */
$lang->api->whiteList          = 'Whitelist';
$lang->api->aclList['open']    = "Public (Users who can access doccan access it)";
$lang->api->aclList['default'] = "Default (Users who can access the selected %s can access it)";
$lang->api->aclList['private'] = "Private (Only the one who created it or users in the whiltelist can access it)";
$lang->api->group              = 'Group';
$lang->api->user               = 'User';

$lang->api->noticeAcl = array(
    'open'    => 'Users who can access the API library can access it.',
    'custom'  => 'Users on the whiltelist can access it.',
    'private' => 'Only the one who creates it can access it.',
);

/* fields of struct */
$lang->struct = new stdClass();

$lang->struct->add             = 'Add';
$lang->struct->field           = 'Field';
$lang->struct->paramsType      = 'Type';
$lang->struct->required        = 'Require';
$lang->struct->desc            = 'Description';
$lang->struct->descPlaceholder = 'Parameter Description';
$lang->struct->action          = 'Action';
$lang->struct->addSubField     = 'Add Subfield';
$lang->struct->list            = 'Data Structure List';
$lang->struct->type            = 'Body Type';

$lang->struct->typeOptions = array(
    'formData' => 'FormData',
    'json'     => 'JSON',
    'array'    => 'Array',
    'object'   => 'Object',
);

/* fields of form */
$lang->api->struct             = 'Data Structure';
$lang->api->structName         = 'Name';
$lang->api->structType         = 'Type';
$lang->api->structAttr         = 'Attribute';
$lang->api->structAddedBy      = 'CreatedBy';
$lang->api->structAddedDate    = 'Created';
$lang->api->name               = 'API Library Name';
$lang->api->baseUrl            = 'Base URL';
$lang->api->baseUrlDesc        = 'Site or path, e.g., http://api.zentao.com or /v1.';
$lang->api->desc               = 'Description';
$lang->api->control            = 'Access Control';
$lang->api->noLib              = 'No API library yet.';
$lang->api->noApi              = 'No API yet.';
$lang->api->noStruct           = 'No API yet.';
$lang->api->noRelease          = 'No version yet.';
$lang->api->lib                = 'API Library';
$lang->api->apiList            = 'API List';
$lang->api->formTitle          = 'API Name';
$lang->api->path               = 'Request Path';
$lang->api->protocol           = 'Protocol';
$lang->api->method             = 'Method';
$lang->api->requestType        = 'Type';
$lang->api->status             = 'Status';
$lang->api->owner              = 'Owner';
$lang->api->paramsExample      = 'Request Example';
$lang->api->header             = 'Request Header';
$lang->api->query              = 'Parameter';
$lang->api->params             = 'Request Body';
$lang->api->response           = 'Response';
$lang->api->responseExample    = 'Response Example';
$lang->api->id                 = 'ID';
$lang->api->addedBy            = 'AddedBy';
$lang->api->addedDate          = 'AddedDate';
$lang->api->editedBy           = 'EditedBy';
$lang->api->editedDate         = 'EditedDate';
$lang->api->version            = 'Version';
$lang->api->res                = new stdClass();
$lang->api->res->name          = 'Name';
$lang->api->res->desc          = 'Description';
$lang->api->res->type          = 'Type';
$lang->api->req                = new stdClass();
$lang->api->req->name          = 'Name';
$lang->api->req->desc          = 'Description';
$lang->api->req->type          = 'Type';
$lang->api->req->required      = 'Required';
$lang->api->field              = 'Field';
$lang->api->scope              = 'Position';
$lang->api->paramsType         = 'Type';
$lang->api->required           = 'Required';
$lang->api->default            = 'Default';
$lang->api->desc               = 'Description';
$lang->api->customType         = 'Custom Structure';
$lang->api->format             = 'Format';
$lang->api->libType            = 'Api Library Type';
$lang->api->product            = $lang->productCommon;
$lang->api->project            = $lang->projectCommon;

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
$lang->api->libTypeList['product'] = $lang->productCommon . ' Api Library';
$lang->api->libTypeList['project'] = $lang->projectCommon . ' Api Library';
$lang->api->libTypeList['nolink']  = 'Independent Api Library';

$lang->api->statusOptions      = array(
    'done'   => 'Done',
    'doing'  => 'Doing',
    'hidden' => 'Hidden'
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

$lang->api->boolList = array(false => 'No', true => 'Yes', '' => 'No');

/* Api params */
$lang->api->paramsTypeCustomOptions = array('file' => 'file', 'ref' => 'ref');

$lang->api->structParamsOptons   = array_merge($lang->api->paramsTypeOptions, array('file' => 'file', 'ref' => 'ref'));
$lang->api->allParamsTypeOptions = array_merge($lang->api->paramsTypeOptions, $lang->api->paramsTypeCustomOptions);
$lang->api->requiredOptions      = array(0 => 'No', 1 => 'Yes');

$lang->apistruct = new stdClass();
$lang->apistruct->name = 'Name';

$lang->api_lib_release = new stdClass();
$lang->api_lib_release->version = 'Version';

$lang->api->error = new stdclass();
$lang->api->error->onlySelect = 'SQL API only allows SELECT query.';
$lang->api->error->disabled   = 'For security reasons, this feature is disabled. Go to the config directory and modify the configuration item %s to enable it.';
$lang->api->error->notInput   = 'Debugging is not supported temporarily due to field parameter type restrictions';
