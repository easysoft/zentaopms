<?php
/**
 * The doc module english file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  doc
 * @version  $Id: vi.php 824 2010-05-02 15:32:06Z wwccss $
 * @link  http://www.zentao.net
 */
/* Fields. */
$lang->doc->common       = 'Tài liệu';
$lang->doc->id           = 'ID';
$lang->doc->product      = $lang->productCommon;
$lang->doc->execution    = $lang->executionCommon;
$lang->doc->lib          = 'Thư viện';
$lang->doc->module       = 'Danh mục';
$lang->doc->object       = 'Object';
$lang->doc->title        = 'Tên';
$lang->doc->digest       = 'Tóm tắt';
$lang->doc->comment      = 'Nhận xét';
$lang->doc->type         = 'Loại';
$lang->doc->content      = 'Text';
$lang->doc->keywords     = 'Tags';
$lang->doc->url          = 'URL';
$lang->doc->files        = 'Files';
$lang->doc->addedBy      = 'Author';
$lang->doc->addedDate    = 'Đã thêm';
$lang->doc->editedBy     = 'UpdatedBy';
$lang->doc->editedDate   = 'UpdatedDate';
$lang->doc->version      = 'Phiên bản';
$lang->doc->basicInfo    = 'Thông tin cơ bản';
$lang->doc->deleted      = 'Đã xóa';
$lang->doc->fileObject   = 'Dependent Item';
$lang->doc->whiteList    = 'Danh sách trắng';
$lang->doc->contentType  = 'Định dạng';
$lang->doc->separator    = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle    = 'File tên';
$lang->doc->filePath     = 'File Path';
$lang->doc->extension    = 'Extension';
$lang->doc->size         = 'Cỡ miếng';
$lang->doc->source       = 'Source';
$lang->doc->download     = 'Tải về';
$lang->doc->acl          = 'Right';
$lang->doc->fileName     = 'Files';
$lang->doc->groups       = 'Nhóm';
$lang->doc->users        = 'Người dùng';
$lang->doc->item         = ' Items';
$lang->doc->num          = 'Documents';
$lang->doc->searchResult = 'Search kết quả';
$lang->doc->mailto       = 'Mailto';
$lang->doc->noModule     = 'No document in this lib, please create it';
$lang->doc->noChapter    = 'No chapters or articles in this book. Please add chapters and articles.';

$lang->doc->moduleDoc     = 'By Module';
$lang->doc->searchDoc     = 'Tìm kiếm';
$lang->doc->fast          = 'Quick Entry';
$lang->doc->allDoc        = 'Tất cả tài liệu';
$lang->doc->allVersion    = 'All Versions';
$lang->doc->openedByMe    = 'Của bạn';
$lang->doc->editedByMe    = 'Edited By Me';
$lang->doc->orderByOpen   = 'Recent Added';
$lang->doc->orderByEdit   = 'Recent Updated';
$lang->doc->orderByVisit  = 'Last Visited';
$lang->doc->todayEdited   = 'Updated Today';
$lang->doc->pastEdited    = 'Tổng Updated';
$lang->doc->myDoc         = 'My tài liệu';
$lang->doc->myCollection  = 'My Favorites';
$lang->doc->tableContents = 'Catalog';

/* Methods list */
$lang->doc->index            = 'Document Home';
$lang->doc->createAB         = 'Create';
$lang->doc->create           = 'Tạo tài liệu';
$lang->doc->edit             = 'Sửa tài liệu';
$lang->doc->delete           = 'Xóa tài liệu';
$lang->doc->createBook       = 'Create Book';
$lang->doc->browse           = 'Document danh sách';
$lang->doc->view             = 'Document Detail';
$lang->doc->diff             = 'Diff';
$lang->doc->cancelDiff       = 'Cancel diff';
$lang->doc->diffAction       = 'Diff tài liệu';
$lang->doc->sort             = 'Đánh giá tài liệu';
$lang->doc->manageType       = 'Quản lý danh mục';
$lang->doc->editType         = 'Sửa';
$lang->doc->deleteType       = 'Xóa';
$lang->doc->addType          = 'Thêm';
$lang->doc->childType        = 'Categories';
$lang->doc->catalogName      = 'Catalog Name';
$lang->doc->collect          = 'Thêm Favorite';
$lang->doc->cancelCollection = 'Remove Favorite';
$lang->doc->deleteFile       = 'Xóa File';
$lang->doc->menuTitle        = 'Menu';

$lang->doc->libName        = 'Thư viện tài liệu';
$lang->doc->libType        = 'Danh mục';
$lang->doc->custom         = 'Tùy biến thư viện tài liệu';
$lang->doc->customAB       = 'Tùy biến Doc Lib';
$lang->doc->createlib      = 'Tạo thư viện tài liệu';
$lang->doc->allLibs        = 'Library danh sách';
$lang->doc->objectLibs     = "{$lang->productCommon}/{$lang->executionCommon} Libraries";
$lang->doc->showFiles      = 'Attachments';
$lang->doc->editlib        = 'Sửa thư viện tài liệu';
$lang->doc->deleteLib      = 'Xóa thư viện tài liệu';
$lang->doc->fixedMenu      = 'Sửa to Menu';
$lang->doc->removeMenu     = 'Remove from Menu';
$lang->doc->search         = 'Tìm kiếm';
$lang->doc->allCollections = 'All Collections';
$lang->doc->keywordsTips   = 'Please use commas to separate multiple keywords.';

/* Query condition list. */
$lang->doc->allProduct    = 'All' . $lang->productCommon;
$lang->doc->allExecutions = 'All' . $lang->executionCommon;

$lang->doc->libTypeList['product']   = $lang->productCommon . ' thư viện';
$lang->doc->libTypeList['execution'] = $lang->executionCommon . ' thư viện';
$lang->doc->libTypeList['custom']    = 'Tùy biến thư viện';

$lang->doc->libIconList['product']   = 'icon-product';
$lang->doc->libIconList['execution'] = 'icon-stack';
$lang->doc->libIconList['custom']    = 'icon-folder-o';

$lang->doc->systemLibs['product']   = $lang->productCommon;
$lang->doc->systemLibs['execution'] = $lang->executionCommon;

$lang->doc->aclList['open']    = 'Công khai';
$lang->doc->aclList['custom']  = 'Tùy biến';
$lang->doc->aclList['private'] = 'Riêng tư';

$lang->doc->typeList['html']     = 'Html';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url']      = 'URL';
$lang->doc->typeList['word']     = 'Word';
$lang->doc->typeList['ppt']      = 'PPT';
$lang->doc->typeList['excel']    = 'Excel';

$lang->doc->types['text'] = 'Text';
$lang->doc->types['url']  = 'URL';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = 'Danh mục';
$lang->doc->browseTypeList['list'] = 'Danh sách';
$lang->doc->browseTypeList['grid'] = 'Card';

$lang->doc->fastMenuList['byediteddate']  = 'Recent Edited';
//$lang->doc->fastMenuList['visiteddate'] = 'Recently Visited';
$lang->doc->fastMenuList['openedbyme']    = 'My tài liệu';
$lang->doc->fastMenuList['collectedbyme'] = 'My Favorites';

$lang->doc->fastMenuIconList['byediteddate']  = 'icon-folder-upload';
//$lang->doc->fastMenuIconList['visiteddate'] = 'icon-folder-move';
$lang->doc->fastMenuIconList['openedbyme']    = 'icon-folder-account';
$lang->doc->fastMenuIconList['collectedbyme'] = 'icon-folder-star';

$lang->doc->customObjectLibs['files']       = 'Hiện Attachment thư viện';
$lang->doc->customObjectLibs['customFiles'] = 'Hiện Custom thư viện';

$lang->doc->orderLib                       = 'Đánh giá Thiết lập';
$lang->doc->customShowLibs                 = 'Display Thiết lập';
$lang->doc->customShowLibsList['zero']     = 'Display Empty thư viện';
$lang->doc->customShowLibsList['children'] = 'Display Child-category tài liệu';
$lang->doc->customShowLibsList['unclosed'] = "Display Active {$lang->executionCommon} Only";

$lang->doc->mail = new stdclass();
$lang->doc->mail->create = new stdclass();
$lang->doc->mail->edit   = new stdclass();
$lang->doc->mail->create->title = "%s created document #%s:%s";
$lang->doc->mail->edit->title   = "%s edited document #%s:%s";

$lang->doc->confirmDelete        = "Bạn có muốn xóa this document?";
$lang->doc->confirmDeleteLib     = "Bạn có muốn xóa this document library?";
$lang->doc->confirmDeleteChapter = "Do you want to delete this chapter?";
$lang->doc->confirmDeleteBook    = "Do you want to delete this book?";
$lang->doc->errorEditSystemDoc   = "You don't have to change system document library.";
$lang->doc->errorEmptyProduct    = "Không có {$lang->productCommon}. It cannot be created.";
$lang->doc->errorEmptyProject    = "Không có {$lang->executionCommon}. It cannot be created.";
$lang->doc->errorMainSysLib      = "This library không thể xóa.";
$lang->doc->accessDenied         = "Access bị từ chối!";
$lang->doc->versionNotFount      = 'Nó does not exist in bản dựng này.';
$lang->doc->noDoc                = 'Không có documents. ';
$lang->doc->noArticle            = 'No articles.';
$lang->doc->noLib                = 'No libraries. ';
$lang->doc->noBook               = 'The WIKI library has not created a manual, please create a new one :)';
$lang->doc->cannotCreateOffice   = 'Sorry, %s file can only be created in ZenTao Biz Series or above. Contact Philip@easysoft.ltd to know more about ZenTao Biz Series and ZenTao Max Series.';
$lang->doc->notSetOffice         = "<p>To create a %s document, you need to configure <a href='%s' target='_parent'>office convert</a>.<p>";
$lang->doc->noSearchedDoc        = 'Không có documents found.';
$lang->doc->noEditedDoc          = 'Bạn có not edited any documents.';
$lang->doc->noOpenedDoc          = 'Bạn chưa tạo any documents.';
$lang->doc->noCollectedDoc       = 'Bạn có not favorited any documents.';
$lang->doc->errorEmptyLib        = 'No data in document library.';
$lang->doc->confirmUpdateContent = 'You have a document that is not saved from last time. Do you want to continue editing it?';

$lang->doc->noticeAcl['lib']['product']['default']   = 'Users who can access the selected product có thể truy cập nó.';
$lang->doc->noticeAcl['lib']['product']['custom']    = 'Users who can access the selected product or users in the whiltelist có thể truy cập nó.';
$lang->doc->noticeAcl['lib']['project']['default']   = 'Users who can access the selected project có thể truy cập nó.';
$lang->doc->noticeAcl['lib']['project']['custom']    = 'Users who can access the selected project or users in the whiltelist có thể truy cập nó.';
$lang->doc->noticeAcl['lib']['execution']['default'] = "Users who can access the selected {$lang->executionCommon} có thể truy cập nó.";
$lang->doc->noticeAcl['lib']['execution']['custom']  = "Users who can access the selected {$lang->executionCommon} or users in the whiltelist có thể truy cập nó.";
$lang->doc->noticeAcl['lib']['custom']['open']       = 'Tất cả users có thể truy cập nó.';
$lang->doc->noticeAcl['lib']['custom']['custom']     = 'Users in the whitelist có thể truy cập nó.';
$lang->doc->noticeAcl['lib']['custom']['private']    = 'Chỉ the one who created it có thể truy cập nó.';

$lang->doc->noticeAcl['doc']['open']    = 'Users who can access the document library which the document belongs có thể truy cập nó.';
$lang->doc->noticeAcl['doc']['custom']  = 'Users in the whiltelist có thể truy cập nó.';
$lang->doc->noticeAcl['doc']['private'] = 'Chỉ the one who created it có thể truy cập nó.';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = 'URL';

$lang->doc->summary = "Total files on this page: <strong>%s</strong> , total size: <strong>%s</strong>, <strong>%s</strong>.";
$lang->doc->ge      = ':';
$lang->doc->point   = '.';

$lang->doclib            = new stdclass();
$lang->doclib->name      = 'Tên';
$lang->doclib->control   = 'Quyền truy cập';
$lang->doclib->group     = 'Nhóm';
$lang->doclib->user      = 'Người dùng';
$lang->doclib->files     = 'Attachments';
$lang->doclib->all       = 'Tất cả Libraries';
$lang->doclib->select    = 'Chọn';
$lang->doclib->execution = $lang->executionCommon . ' thư viện';
$lang->doclib->product   = $lang->productCommon . ' thư viện';

$lang->doclib->aclListA['default'] = 'Mặc định';
$lang->doclib->aclListA['custom']  = 'Tùy biến';

$lang->doclib->aclListB['open']    = 'Công khai';
$lang->doclib->aclListB['custom']  = 'Tùy biến';
$lang->doclib->aclListB['private'] = 'Riêng tư';

$lang->doclib->create['product']   = 'Tạo ' . $lang->productCommon . ' thư viện';
$lang->doclib->create['execution'] = 'Tạo ' . $lang->executionCommon . ' thư viện';
$lang->doclib->create['custom']    = 'Tạo Custom thư viện';

$lang->doclib->main['product']   = 'Primary thư viện';
$lang->doclib->main['execution'] = 'Primary thư viện';

$lang->doclib->tabList['product']   = $lang->productCommon;
$lang->doclib->tabList['execution'] = $lang->executionCommon;
$lang->doclib->tabList['custom']    = 'Tùy biến';

$lang->doclib->nameList['custom'] = 'Tùy biến tên';
