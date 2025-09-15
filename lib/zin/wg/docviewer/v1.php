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

class docViewer extends wg
{
    protected static array $defineProps = array
    (
        'doc' => 'array|string|int'
    );

    public static function getPageJS(): ?string
    {
        $docAppJS = file_get_contents(dirname(__DIR__) . DS . 'docapp' . DS . 'js' . DS . 'v1.js');
        $appendJS = file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
        return $docAppJS . js::scope($appendJS);
    }

    public static function getPageCSS(): ?string
    {
        return file_get_contents(dirname(__DIR__) . DS . 'docapp' . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        global $app, $lang, $config;

        /**
         * 定义文档应用接口链接。
         * Define the fetcher links for doc app.
         */
        $docFetcher          = createLink('doc', 'ajaxGetDoc', 'docID={docID}&version={version}');
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
        if(!$this->hasProp('viewModeUrl'))
        {
            $viewModeUrl = createLink($app->rawModule, '{mode}', 'docID={docID}');
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

        return zui::docViewer
        (
            set::_class('shadow rounded ring canvas'),
            set::_style(array('height' => 'calc(100vh - 72px)')),
            set::_id('docApp'),
            set::docViewer(true),
            set::token(session_id()),
            set::awarenessUser(array('id' => $app->user->id, 'account' => $app->user->account, 'name' => $app->user->realname, 'avatar' => $app->user->avatar)),
            set::onModeChange(jsRaw('window.onDocAppModeChange')),
            set::width('100%'),
            set::height('100%'),
            set::userMap(data('users')),
            set::currentUser($app->user->account),
            set::privs(array()),
            set::uploadUrl($uploadUrl),
            set::downloadUrl($downloadUrl),
            set::sessionStr($sessionStr),
            set::fileUrl($fileUrl),
            set::viewModeUrl($viewModeUrl),
            set::langData($langData),
            set::historyPanel($historyPanelProps),
            set::showToolbar(true),
            set::canPreviewOffice($canPreviewOffice),
            set::fileInfoUrl($fileInfoUrl),
            set::getTableOptions(null),
            set::mode('view'),
            set::noSidebar(true),
            set::noSpace('hidden'),
            set::homeName(false),
            set::noDocSwitcher(true),
            set('$options', jsRaw('window.setDocAppOptions')),
            set($this->props)
        );
    }
}
