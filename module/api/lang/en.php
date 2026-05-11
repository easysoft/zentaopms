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
$lang->api->view                = 'API Details';
$lang->api->editLib             = 'Edit Library';
$lang->api->releases            = 'Version Management';
$lang->api->deleteRelease       = 'Delete Version';
$lang->api->deleteLib           = 'Delete API Library';
$lang->api->createRelease       = 'Publish API';
$lang->api->createLib           = 'Create API Library';
$lang->api->createApi           = 'Create API Document';
$lang->api->createAB            = 'Create';
$lang->api->createDemo          = 'Import ZenTao API Library';
$lang->api->edit                = 'Edit API';
$lang->api->delete              = 'Delete API';
$lang->api->position            = 'Location';
$lang->api->startLine           = "%s, Line %s";
$lang->api->desc                = 'Description';
$lang->api->debug               = 'Debug';
$lang->api->submit              = 'Submit';
$lang->api->url                 = 'Request URL';
$lang->api->result              = 'Response';
$lang->api->status              = 'Status';
$lang->api->data                = 'Content';
$lang->api->noParam             = 'No input parameters required for GET debugging.';
$lang->api->noModule            = 'No directories found in this library. Please create a directory first.';
$lang->api->post                = 'Please refer to the form for POST debugging.';
$lang->api->noUniqueName        = 'Library name already exists.';
$lang->api->noUniqueVersion     = 'ersion already exists.';
$lang->api->createStruct        = 'Create Data Structure';
$lang->api->editStruct          = 'Edit Data Structure';
$lang->api->deleteStruct        = 'Delete Data Structure';
$lang->api->create              = 'Create API';
$lang->api->title               = 'API Name';
$lang->api->pageTitle           = 'API Library';
$lang->api->module              = 'Directory';
$lang->api->apiDoc              = 'API';
$lang->api->manageType          = 'Manage Directory';
$lang->api->managePublish       = 'Manage Version';
$lang->api->doing               = 'In Development';
$lang->api->done                = 'Completed';
$lang->api->basicInfo           = 'Basic Info';
$lang->api->apiDesc             = 'Description';
$lang->api->confirmDelete       = "Are you sure you want to delete this API?";
$lang->api->confirmDeleteLib    = "Are you sure you want to delete this library?";
$lang->api->confirmDeleteStruct = "Are you sure you want to delete this data structure?";
$lang->api->filterStruct        = "Populate from Data Structure";
$lang->api->defaultVersion      = "Current Version";
$lang->api->latestVersion       = 'Latest Version';
$lang->api->zentaoAPI           = "Zentao API Doc V1";
$lang->api->search              = "Search";
$lang->api->allLibs             = "All Libs";
$lang->api->noLinked            = "Standalone API";
$lang->api->apiCatalog          = 'API Directory';
$lang->api->addCatalog          = 'Add Directory';
$lang->api->editCatalog         = 'Edit Directory';
$lang->api->sortCatalog         = 'Sort Directories';
$lang->api->deleteCatalog       = 'Delete Directory';

/* Common access control lang. */
$lang->api->whiteList          = 'Whitelist';
$lang->api->aclList['open']    = "Public (Accessible to users with document view permissions.)";
$lang->api->aclList['default'] = "Default (Accessible to users with %s access permissions.)";
$lang->api->aclList['private'] = "Private (Accessible only to the creator and whitelist users.)";
$lang->api->group              = 'Group';
$lang->api->user               = 'User';

$lang->api->noticeAcl = array(
    'open'    => 'Accessible to all users',
    'custom'  => 'Accessible to whitelist users',
    'private' => 'Accessible to creator only',
);

/* fields of struct */
$lang->struct = new stdClass();

$lang->struct->add             = 'Add';
$lang->struct->field           = 'Field';
$lang->struct->paramsType      = 'Type';
$lang->struct->required        = 'Required';
$lang->struct->desc            = 'Description';
$lang->struct->descPlaceholder = 'Parameter Description';
$lang->struct->action          = 'Actions';
$lang->struct->addSubField     = 'Add Child Field';
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
$lang->api->structAttr         = 'Property';
$lang->api->structAddedBy      = 'Creator';
$lang->api->structAddedDate    = 'Created on';
$lang->api->name               = 'Library Name';
$lang->api->baseUrl            = 'Base URL';
$lang->api->baseUrlDesc        = 'URL or path, e.g., http://api.zentao.com or /v1.';
$lang->api->desc               = 'Description';
$lang->api->control            = 'Access Control';
$lang->api->noLib              = 'No API libraries yet.';
$lang->api->noApi              = 'No APIs yet.';
$lang->api->noStruct           = 'No data structures yet.';
$lang->api->noRelease          = 'No versions yet.';
$lang->api->lib                = 'API Library';
$lang->api->apiList            = 'API List';
$lang->api->formTitle          = 'API Name';
$lang->api->path               = 'Request Path';
$lang->api->protocol           = 'Protocol';
$lang->api->method             = 'Method';
$lang->api->requestType        = 'Request Format';
$lang->api->status             = 'Development Status';
$lang->api->owner              = 'Manager';
$lang->api->paramsExample      = 'Request Example';
$lang->api->header             = 'Request Header';
$lang->api->query              = 'Request Parameters';
$lang->api->params             = 'Request Body';
$lang->api->response           = 'Response';
$lang->api->responseExample    = 'Response Example';
$lang->api->id                 = 'ID';
$lang->api->addedBy            = 'Creator';
$lang->api->addedDate          = 'Created on';
$lang->api->editedBy           = 'Edited by';
$lang->api->editedDate         = 'Eidted on';
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
$lang->api->scope              = 'Location';
$lang->api->paramsType         = 'Type';
$lang->api->required           = 'Required';
$lang->api->default            = 'Default';
$lang->api->desc               = 'Description';
$lang->api->customType         = 'Custom Structure';
$lang->api->format             = 'Format';
$lang->api->libType            = 'Api Library Type';
$lang->api->product            = $lang->productCommon;
$lang->api->project            = $lang->projectCommon;
$lang->api->apiTotalInfo       = 'Total %d APIs';
$lang->api->showNotEmpty       = 'Only show with APIs';
$lang->api->showClosed         = 'Include Closed';

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
$lang->api->libTypeList['product'] = $lang->productCommon . ' API';
$lang->api->libTypeList['project'] = $lang->projectCommon . ' API';
$lang->api->libTypeList['nolink']  = 'Standalone API';

$lang->api->statusOptions      = array(
    'done'   => 'Completed',
    'doing'  => 'In Development',
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
$lang->apistruct->name = 'Structure Name';

$lang->api_lib_release = new stdClass();
$lang->api_lib_release->version = 'Version';
$lang->api_lib_release->desc    = 'Description';

$lang->api->error = new stdclass();
$lang->api->error->onlySelect = 'Only SELECT queries are allowed in SQL API.';
$lang->api->error->disabled   = 'This feature is disabled for security reasons. To enable it, please modify the configuration item %s in the config directory.';
$lang->api->error->notInput   = 'Debugging is currently not supported due to field parameter type limitations.';

$lang->api->filterTypes[] = array('all', 'All');
$lang->api->filterTypes[] = array('createdByMe', 'Created by me');
$lang->api->filterTypes[] = array('editedByMe', 'Edited by me');

$lang->api->homeFilterTypes['nolink']  = $lang->api->libTypeList['nolink'];
$lang->api->homeFilterTypes['product'] = $lang->api->libTypeList['product'];
$lang->api->homeFilterTypes['project'] = $lang->api->libTypeList['project'];
