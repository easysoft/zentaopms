<?php
/**
 * The webapp module en file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->webapp->common = 'App';
$lang->webapp->index  = 'Added App';
$lang->webapp->obtain = 'Obtain App';

$lang->webapp->install    = 'Install';
$lang->webapp->uninstall  = 'Remove';
$lang->webapp->useapp     = 'Run';
$lang->webapp->view       = 'View';
$lang->webapp->preview    = 'Preview';
$lang->webapp->installed  = 'Installed';
$lang->webapp->edit       = 'Edit App';
$lang->webapp->create     = 'Create App';
$lang->webapp->manageTree = 'Category';

$lang->webapp->id          = 'ID';
$lang->webapp->name        = 'Name';
$lang->webapp->url         = 'URL';
$lang->webapp->icon        = 'Icon';
$lang->webapp->module      = 'Category';
$lang->webapp->author      = 'Author';
$lang->webapp->abstract    = 'Abstract';
$lang->webapp->desc        = 'Desc';
$lang->webapp->target      = 'How';
$lang->webapp->size        = 'Size';
$lang->webapp->height      = 'Height';
$lang->webapp->addedTime   = 'Add Time';
$lang->webapp->updatedTime = 'Update Time';
$lang->webapp->downloads   = 'Installs';
$lang->webapp->grade       = 'Grade';
$lang->webapp->addType     = 'Add Type';
$lang->webapp->addedBy     = 'Added By';
$lang->webapp->addedDate   = 'Added date';
$lang->webapp->views       = 'Views';
$lang->webapp->packup      = 'Fold';
$lang->webapp->custom      = 'Custom';

$lang->webapp->byDownloads   = 'Most Downloads';
$lang->webapp->byAddedTime   = 'New Add';
$lang->webapp->byUpdatedTime = 'New Update';
$lang->webapp->bySearch      = 'Search';
$lang->webapp->byCategory    = 'Category';

$lang->webapp->selectModule = 'Select Category:';
$lang->webapp->allModule    = 'All';
$lang->webapp->noModule     = 'Uncategorized';

$lang->webapp->targetList['']       = '';
$lang->webapp->targetList['popup']  = 'Popup';
$lang->webapp->targetList['iframe']  = 'Iframe';
$lang->webapp->targetList['blank']  = 'New Window';

$lang->webapp->sizeList['']         = "";
$lang->webapp->sizeList['1024x600'] = "1024 x 600";
$lang->webapp->sizeList['900x600']  = "900 x 600";
$lang->webapp->sizeList['700x600']  = "700 x 600";
$lang->webapp->sizeList['600x500']  = "600 x 500";
$lang->webapp->sizeList['custom']   = "Custom size";

$lang->webapp->addTypeList['system'] = 'System App';
$lang->webapp->addTypeList['custom'] = 'Custom App';

$lang->webapp->errorOccurs        = 'Error:';
$lang->webapp->errorGetModules    = "Get extensions' categories data from the www.zentao.net failed. ";
$lang->webapp->errorGetExtensions = 'Get extensions from www.zentao.net failed. You can visit  “<a href="' . inlink('create') . '">Create webapp</a>” to create it.';
$lang->webapp->successInstall     = 'Success Install App.';
$lang->webapp->confirmDelete      = 'Are you sure delete this app?';
$lang->webapp->noticeAbstract     = 'Sentence describes the application, not more than 30 words';
$lang->webapp->noticeIcon         = 'The size of icon is 72x72.';
