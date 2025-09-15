<?php
declare(strict_types=1);
/**
 * The docApp widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class docApp extends wg
{
    protected static array $defineProps = array
    (
        'width'                 => '?int|string="100%"',   // 宽度。
        'height'                => '?int|string="100%"',   // 高度。
        'fetcher'               => '?string|array',        // 数据获取 URL 或回调函数。
        'fetchOnChangeSpace'    => '?boolean',             // 是否在切换空间时重新获取数据。
        'docFetcher'            => '?string|array',        // 文档数据获取 URL 或回调函数。
        'libSummariesFetcher'   => '?string|array|object', // 落地页库概要数据获取 URL 或回调函数。
        'noSpace'               => '?boolean',             // 是否不显示空间。
        'noModule'              => '?boolean',             // 是否不显示模块。
        'data'                  => '?array',               // 数据。
        'spaceType'             => '?string',              // 空间类型。
        'spaceID'               => '?number',              // 空间 ID。
        'libID'                 => '?number',              // 库 ID。
        'moduleID'              => '?number',              // 模块 ID。
        'docID'                 => '?number',              // 文档 ID。
        'docVersion'            => '?number',              // 文档版本。
        'mode'                  => '?string',              // 应用默认界面模式，包括 home、list、edit、create、files。
        'spaceIcon'             => '?string',              // 空间图标。
        'libIcon'               => '?string',              // 库图标。
        'libColors'             => '?string[]',            // 库颜色列表。
        'libFilesIcon'          => '?string',              // 库文件图标。
        'moduleIcon'            => '?string',              // 模块图标。
        'docIcon'               => '?string',              // 文档图标。
        'fileIcon'              => '?array',               // 文件图标。
        'fileUrl'               => '?string',              // 文件下载链接。
        'libTypes'              => '?array',               // 库类型名称定义。
        'preserve'              => '?string',              // 是否保留 UI 设置到本地，例如记住侧边栏宽度和状态。
        'userMap'               => '?array',               // 用户映射定义。
        'currentUser'           => '?string',              // 当前用户。
        'historyFetcher'        => '?string|array|object', // 历史记录数据获取 URL 或回调函数。
        'historyPanel'          => '?string|array|object', // 历史记录面板。
        'uploadUrl'             => '?string',              // 上传文件 URL。
        'downloadUrl'           => '?string',              // 下载文件 URL。
        'privs'                 => '?array',               // 权限定义。
        'homeName'              => '?string|bool',         // 首页名称，设置为 false 不显示首页名称。
        'filesFetcher'          => '?string|array',        // 文件数据获取 URL 或回调函数。
        'search'                => '?string',              // 搜索关键字。
        'filterType'            => '?string',              // 过滤类型。
        'pager'                 => '?PagerInfo',           // 分页信息。
        'params'                => '?string',              // 额外参数信息。
        'orderBy'               => '?string',              // 排序字段。
        'langData'              => '?array',               // 语言数据。
        'showLibFiles'          => '?boolean|array|string',// 是否显示库文件。
        'getSortableOptions'    => '?string',              // 获取排序选项。
        'filterItems'           => '?string',              // 过滤项。
        'isMatchFilter'         => '?string',              // 是否匹配过滤。
        'getTableOptions'       => '?string',              // 获取表格选项。
        'getDefaultPager'       => '?string',              // 获取默认分页。
        'getPagerOptions'       => '?string',              // 获取分页选项。
        'getSearchBoxOptions'   => '?string',              // 获取搜索框选项。
        'getActions'            => '?string',              // 获取操作。
        'getFilterTypes'        => '?string',              // 获取过滤类型。
        'onCreateDoc'           => '?string',              // 创建文档事件。
        'onSaveDoc'             => '?string',              // 保存文档事件。
        'canMoveDoc'            => '?string',              // 是否可以移动文档。
        'onSwitchView'          => '?string',              // 切换视图事件。
        'getDocViewSidebarTabs' => '?string',              // 获取文档视图侧边栏选项。
        'formatDataItem'        => '?string',              // 格式化数据条目。
        'viewModeUrl'           => '?string',              // 应用视图 URL 格式。
        'hasZentaoSlashMenu'    => '?boolean'              // 是否显示禅道数据。
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    /**
     * 获取禅道数据菜单。
     * Get zentao list menu.
     *
     * @access public
     * @return array
     */
    protected function getZentaoListMenu(): array
    {
        global $lang, $config;
        $menus = array();
        $zentaoList = $config->doc->zentaoList;
        $privs      = $this->prop('privs');

        foreach($zentaoList as $value)
        {
            $priv = isset($value['priv']) ? $value['priv'] : null;
            if($priv && isset($privs[$priv]) && !$privs[$priv]) continue;

            if(isset($value['subMenu']))
            {
                $subMenus = array();
                foreach($value['subMenu'] as $subMenu)
                {
                    $priv = isset($subMenu['priv']) ? $subMenu['priv'] : null;
                    if($priv && isset($privs[$priv]) && !$privs[$priv]) continue;
                    if($subMenu['key'] == 'ER' && !$config->enableER)   continue;
                    if($subMenu['key'] == 'UR' && !$config->URAndSR)    continue;

                    $subMenus[] = $subMenu;
                }
                if(empty($subMenus)) continue;
                $value['subMenu'] = $subMenus;
            }
            $menus[] = $value;
        }

        return $menus;
    }

    /**
     * 转换禅道数据菜单格式
     * Convert zentao list menu format.
     *
     * @access public
     * @return array
     */
    protected function convertZentaoListMenu(array $list): array
    {
        $menus = array();

        foreach($list as $item)
        {
            $menu = array();

            if(isset($item['key']))       $menu['key']       = $item['key'];
            if(isset($item['name']))      $menu['text']      = $item['name'];
            if(isset($item['icon']))      $menu['icon']      = $item['icon'];
            if(isset($item['module']))    $menu['module']    = $item['module'];
            if(isset($item['method']))    $menu['method']    = $item['method'];
            if(isset($item['params']))    $menu['params']    = $item['params'];
            if(isset($item['priv']))      $menu['priv']      = $item['priv'];
            if(isset($item['isModal']))   $menu['isModal']   = $item['isModal'];
            if(isset($item['modalSize'])) $menu['modalSize'] = $item['modalSize'];

            if(isset($item['subMenu']))
            {
                $menu['items'] = $this->convertZentaoListMenu($item['subMenu']);
            }

            $menus[] = $menu;
        }

        return $menus;
    }

    protected function build()
    {
        global $app, $lang, $config;

        jsVar('hocuspocusConnect', $lang->doc->hocuspocusConnect);
        jsVar('hocuspocusDisconnect', $lang->doc->hocuspocusDisconnect);
        jsVar('enableHocuspocus', $config->docHocuspocus->enable);
        jsVar('needReadable', $lang->doc->needReadable);
        jsVar('vision', $config->vision);
        jsVar('isInModal', isInModal());
        jsVar('userGroups', $app->user->groups);

        /**
         * 定义文档应用接口链接。
         * Define the fetcher links for doc app.
         */
        $fetcher             = createLink('doc', 'ajaxGetSpaceData', 'type={spaceType}&spaceID={spaceID}&picks={picks}');
        $docFetcher          = createLink('doc', 'ajaxGetDoc', 'docID={docID}&version={version}');
        $filesFetcher        = createLink('doc', 'ajaxGetFiles', 'type={objectType}&objectID={objectID}');
        $libSummariesFetcher = createLink('doc', 'ajaxGetLibSummaries', 'spaceType={spaceType}&spaceList={spaceList}');
        $uploadUrl           = createLink('file', 'ajaxUpload', 'uid={uid}&objectType={objectType}&objectID={objectID}&extra={extra}&field={field}&api={api}&gid={gid}');
        $downloadUrl         = createLink('file', 'ajaxQuery', 'fileID={gid}&objectType={objectType}&objectID={objectID}&title={title}&extra={extra}');
        $fileInfoUrl         = createLink('file', 'ajaxQuery', 'fileID={gid}&objectType={objectType}&objectID={objectID}&title={title}&extra={extra}', 'json');

        /**
         * 定义文档界面上的文件下载链接。
         * Define the file download link for doc app.
         */
        $sessionStr = session_name() . '=' . session_id();
        $fileUrl    = $this->prop('fileUrl');
        if(empty($fileUrl))
        {
            $fileUrl    = createLink('file', 'download', 'fileID={id}');
            $fileUrl   .= strpos($fileUrl, '?') === false ? '?' : '&';
            $fileUrl   .= $sessionStr;
        }

        /**
         * Setting language data for frontend. Use getLang('xxx') in js/app.ui.js.
         * 设置前端语言数据。 在 js/app.ui.js 中使用 getLang('xxx') 来访问语言数据。
         */
        $app->loadLang('file');
        $app->loadLang('api');
        $langData = $lang->doc->docLang;
        $langData->filePreview       = $lang->file->preview;
        $langData->fileDownload      = $lang->file->download;
        $langData->fileDelete        = $lang->file->delete;
        $langData->fileRename        = $lang->file->edit;
        $langData->fileConfirmDelete = $lang->file->confirmDelete;
        $langData->deleted           = $lang->file->deleted;
        $langData->createApi         = $lang->api->createApi;
        $langData->apifilterTypes    = $lang->api->filterTypes;
        $langData->module            = $lang->api->module;
        $langData->struct            = $lang->api->struct;
        $langData->releases          = $lang->api->releases;
        $langData->noApi             = $lang->api->noApi;
        $langData->version           = $lang->api->version;
        $langData->defaultVersion    = $lang->api->defaultVersion;
        $langData->createStruct      = $lang->api->createStruct;
        $langData->createRelease     = $lang->api->createRelease;
        $langData->libTypeList       = $lang->api->libTypeList;
        $langData->latestVersion     = $lang->api->latestVersion;
        $langData->template          = $lang->doc->template;
        $langData->labelAllVersions  = $lang->doc->allVersion;
        $langData->labelDiff         = $lang->doc->diff;
        $langData->labelConfirm      = $lang->doc->confirm;
        $langData->labelCancelDiff   = $lang->doc->cancelDiff;

        /**
         * 通过语言项定义文档表格列显示名称。
         * Define the table columns for doc app.
         */
        $langData->tableCols = array();
        $langData->tableCols['id']         = $lang->doc->id;
        $langData->tableCols['title']      = $lang->doc->title;
        $langData->tableCols['collects']   = $lang->doc->collect;
        $langData->tableCols['views']      = $lang->doc->views;
        $langData->tableCols['addedBy']    = $lang->doc->addedBy;
        $langData->tableCols['addedDate']  = $lang->doc->addedDate;
        $langData->tableCols['editedBy']   = $lang->doc->editedBy;
        $langData->tableCols['editedDate'] = $lang->doc->editedDate;
        $langData->tableCols['actions']    = $lang->actions;

        /**
         * 通过语言项定义附件表格列显示名称。
         * Define the files table columns for doc app.
         */
        $langData->fileTableCols = array();
        $langData->fileTableCols['id']         = $lang->idAB;
        $langData->fileTableCols['title']      = $lang->doc->fileTitle;
        $langData->fileTableCols['objectName'] = $lang->doc->source;
        $langData->fileTableCols['extension']  = $lang->doc->extension;
        $langData->fileTableCols['size']       = $lang->doc->size;
        $langData->fileTableCols['addedBy']    = $lang->doc->addedBy;
        $langData->fileTableCols['addedDate']  = $lang->doc->addedDate;
        $langData->fileTableCols['actions']    = $lang->actions;

        /**
         * 合并语言数据。
         * Merge the language data.
         */
        if($this->hasProp('langData')) $langData = array_merge((array)$langData, (array)$this->prop('langData'));

        /**
         * 界面模式 URL 格式化模版。
         * URL format for view mode change.
         */
        $viewModeUrl = $this->prop('viewModeUrl');
        $spaceType   = $this->hasProp('spaceType') ? $this->prop('spaceType') : data('spaceType');

        $rawModule = $app->rawModule;
        $rawMethod = $app->rawMethod;
        if(!$this->hasProp('viewModeUrl'))
        {
            if ($rawModule == 'doc' && $rawMethod == 'app')
            {
                $viewModeUrl = createLink('doc', 'app', 'type={spaceType}&spaceID={spaceID}&libID={libID}&moduleID={moduleID}&docID={docID}&mode={mode}&orderBy={orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}&filterType={filterType}&search={search}&noSpace={noSpace}');
            }
            else
            {
                if($rawModule == 'doc' && $rawMethod == 'view')
                {
                    $spaceMethod = array('mine' => 'mySpace', 'custom' => 'teamSpace', 'project' => 'projectSpace', 'product' => 'productSpace');
                    if(isset($spaceMethod[$spaceType])) $rawMethod = $spaceMethod[$spaceType];
                }
                $viewModeUrl = createLink($rawModule, $rawMethod, 'objectID={spaceID}&libID={libID}&moduleID={moduleID}&browseType={filterType}&orderBy={orderBy}&param=0&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}&mode={mode}&docID={docID}&search={search}');
            }
        }

        $hasZentaoSlashMenu = $this->prop('hasZentaoSlashMenu');
        if($hasZentaoSlashMenu === null ) $hasZentaoSlashMenu = true;

        $app->control->loadModel('file');

        $canDownload   = common::hasPriv('file', 'download');
        $fileListProps = array();
        if($canDownload)
        {
            $previewLink = helper::createLink('file', 'download', "fileID={id}&mouse=left");
            jsVar('previewLang', $lang->file->preview);
            jsVar('downloadLang', $lang->file->download);
            jsVar('previewLink', $previewLink);
            jsVar('downloadLink', $fileUrl);
            jsVar('libreOfficeTurnon', isset($config->file->libreOfficeTurnon) && $config->file->libreOfficeTurnon == 1);

            $fileListProps['fileUrl']          = $fileUrl;
            $fileListProps['target']           = '_blank';
            $fileListProps['hoverItemActions'] = true;
            $fileListProps['itemProps']        = array('target' => '_blank');
            $fileListProps['fileActions']      = jsCallback('file')->do('return getFileActions(file)');
        }
        else
        {
            $fileUrl = '';
        }

        $canPreviewOffice  = $canDownload && isset($config->file->libreOfficeTurnon) and $config->file->libreOfficeTurnon == 1;
        $historyPanelProps = $this->prop('historyPanel');
        if(empty($historyPanelProps)) $historyPanelProps = array();
        if(is_array($historyPanelProps)) $historyPanelProps['fileListProps'] = $fileListProps;

        /* 对比不可用场景：文档模板、API 文档、开源版 */
        $diffEnabled = ($config->edition != 'open')
            && !($rawModule == 'doc' && $rawMethod == 'view')
            && $rawModule != 'api';

        /* 禅道数据菜单不可用界面：运营（lite）、需求与市场（or） */
        if ($config->vision == 'lite' || $config->vision == 'or') $hasZentaoSlashMenu = false;

        $zentaoListMenu = $hasZentaoSlashMenu ? $this->getZentaoListMenu() : array();

        $moreMenus = $zentaoListMenu
            ? array_merge([array(
                'text' => $lang->doc->zentaoData,
                'type' => 'heading',
            )], $this->convertZentaoListMenu($zentaoListMenu))
            : array();

        return zui::docApp
        (
            set::_class('shadow rounded ring canvas'),
            set::_style(array('height' => 'calc(100vh - 72px)')),
            set::_id('docApp'),
            set::token(session_id()),
            set::awarenessUser(array('id' => $app->user->id, 'account' => $app->user->account, 'name' => $app->user->realname, 'avatar' => $app->user->avatar)),
            set::useHocuspocus(jsRaw('window.useHocuspocus')),
            set::hocuspocusUrls(array($config->docHocuspocus->http, $config->docHocuspocus->websocket)),
            set::onHocuspocusConnect(jsRaw('window.onHocuspocusConnect')),
            set::onHocuspocusDisconnect(jsRaw('window.onHocuspocusDisconnect')),
            set::onAuthenticationFailed(jsRaw('window.onAuthenticationFailed')),
            set::onModeChange(jsRaw('window.onDocAppModeChange')),
            set::spaceType($spaceType),
            set::spaceID(data('spaceID')),
            set::libID(data('libID')),
            set::moduleID(data('moduleID')),
            set::docID(data('docID')),
            set::docVersion(data('docVersion')),
            set::diffEnabled($diffEnabled),
            set::mode('list'),
            set::filterType(data('filterType')),
            set::search(data('search')),
            set::orderBy(data('orderBy')),
            set::pager(array('recTotal' => 0, 'recPerPage' => 20, 'page' => 1)),
            set::fetcher($fetcher),
            set::docFetcher($docFetcher),
            set::filesFetcher($filesFetcher),
            set::libSummariesFetcher($libSummariesFetcher),
            set::width('100%'),
            set::height('100%'),
            set::userMap(data('users')),
            set::currentUser($app->user->account),
            set::privs(array()),
            set::uploadUrl($uploadUrl),
            set::downloadUrl($downloadUrl),
            set::sessionStr($sessionStr),
            set('$options', jsRaw('window.setDocAppOptions')),
            set($this->props),
            set::fileUrl($fileUrl),
            set::viewModeUrl($viewModeUrl),
            set::langData($langData),
            set::historyPanel($historyPanelProps),
            set::showToolbar(true),
            set::moreMenu($moreMenus),
            set::moreMenuAction(jsRaw('window.moreMenuAction')),
            set::canPreviewOffice($canPreviewOffice),
            set::fileInfoUrl($fileInfoUrl),
            $hasZentaoSlashMenu ? jsCall('setZentaoSlashMenu', $zentaoListMenu, $lang->doc->zentaoData, $config->doc->zentaoListMenuPosition) : null
        );
    }
}
