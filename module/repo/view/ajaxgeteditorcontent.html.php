<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Yanyi Cao
 * @package     repo
 * @version     $Id: create.html.php $
 */
?>
<?php
include '../../common/view/header.lite.html.php';
js::set('jsRoot', $jsRoot);
js::set('clientLang', $app->clientLang);
js::set('fileExt', $this->config->repo->fileExt);
js::set('codeContent', trim($content));
js::set('file', $pathInfo);
js::set('blames', $blames);
js::set('blameTmpl', $lang->repo->blamTmpl);
js::set('repoID', $repoID);
js::import($jsRoot  . '/zui/tabs/tabs.min.js');
js::import($jsRoot  . 'monaco-editor/min/vs/loader.js');
$canLinkStory    = common::hasPriv('repo', 'linkStory');
$canLinkBug      = common::hasPriv('repo', 'linkBug');
$canLinkTask     = common::hasPriv('repo', 'linkTask');
$canUnlinkObject = common::hasPriv('repo', 'unlinkObject');
?>
<div id="monacoEditor">
  <div id="codeContainer"></div>
  <div id="log">
    <div class="tip"></div>
    <div class="history"></div>
  </div>
  <div id="related">
    <div class="main-col main">
      <div class="content panel">
        <div class='btn-toolbar'>
          <div class="btn btn-left pull-left"><i class="icon icon-chevron-left"></i></div>
          <?php if($canLinkStory or $canLinkBug or $canLinkTask or $canUnlinkObject):?>
          <div class="dropdown pull-right">
            <button class="btn" type="button" data-toggle="context-dropdown"><i class="icon icon-ellipsis-v icon-rotate-90"></i></button>
            <ul class="dropdown-menu">
              <?php
              if($canLinkStory) echo '<li id="linkStory">' . html::a('javascript:;', '<i class="icon icon-lightbulb"></i> ' . $lang->repo->linkStory) . '</li>';
              if($canLinkBug) echo '<li id="linkBug">' . html::a('javascript:;', '<i class="icon icon-bug"></i> ' . $lang->repo->linkBug) . '</li>';
              if($canLinkTask) echo '<li id="linkTask">' . html::a('javascript:;', '<i class="icon icon-todo"></i> ' . $lang->repo->linkTask) . '</li>';
              ?>
            </ul>
          </div>
          <?php endif;?>
          <div class="btn btn-right pull-right"><i class="icon icon-chevron-right"></i></div>
          <div class='panel-title'>
            <div class="tabs w-10" id="relationTabs"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
<script>
var codeHeight = $(window).innerHeight() - $('#mainHeader').height() - $('#appsBar').height() - $('#fileTabs .tabs-navbar').height();
if(codeHeight > 0) $.cookie('codeContainerHeight', codeHeight);
$('#codeContainer').css('height', $.cookie('codeContainerHeight'));

/**
 * Get relation by commit.
 *
 * @param  string $commit
 * @access public
 * @return void
 */
function getRelation(commit)
{
    $('#codeContainer').css('height', codeHeight / 5 * 3);
    var relatedHeight = codeHeight / 5 * 2 - $('#log').height() - 10;
    $('#related').css('height', relatedHeight);
    $tabs = $('#relationTabs').data('zui.tabs');
    if($tabs) $tabs.closeAll();

    $.post(createLink('repo', 'ajaxGetCommitRelation', 'commit=' + commit), function(data)
    {
        var titleList = JSON.parse(data).titleList;
        var tabs = [];
        $.each(titleList, function(i, titleObj)
        {
            var tab = setTab(titleObj);
            if($tabs)
            {
                $tabs.open(tab);
            }
            else
            {
                tabs.push(tab);
            }
        });

        if(titleList.length > 0)
        {
            if($tabs)
            {
                $tabs.open(setTab(titleList[0]));
            }
            else
            {
                $('#relationTabs').tabs({tabs: tabs});
            }
        }

        arrowTabs('relationTabs', 1);
    });

    var linkStory = createLink('repo', 'linkStory', 'repoID=' + repoID + '&commit=' + commit, '', true);
    var linkBug   = createLink('repo', 'linkBug',   'repoID=' + repoID + '&commit=' + commit, '', true);
    var linkTask  = createLink('repo', 'linkTask',  'repoID=' + repoID + '&commit=' + commit, '', true);
    $('#linkStory a').attr('data-link', linkStory);
    $('#linkBug a').attr('data-link', linkBug);
    $('#linkTask a').attr('data-link', linkTask);
    $('#related').show();
}

/**
 * Set tab data.
 *
 * @param  object $titleObj
 * @access public
 * @return object
 */
function setTab(titleObj)
{
    return {
        id:    titleObj.type + '-' + titleObj.id,
        title: ' ' + titleObj.title,
        icon:  titleObj.type == 'story' ? 'icon-lightbulb' : (titleObj.type == 'task' ? 'icon-check-sign' : 'icon-bug'),
        icon:  titleObj.type == 'story' ? 'icon-lightbulb text-primary' : (titleObj.type == 'task' ? 'icon-check-sign text-info' : 'icon-bug text-red'),
        type:  'iframe',
        url:   createLink('repo', 'ajaxGetRelationInfo', 'objectID=' + titleObj.id + '&objectType=' + titleObj.type)
    };
}

$(function()
{
    $('.btn-left').click(function()  {arrowTabs('relationTabs', 1);});
    $('.btn-right').click(function() {arrowTabs('relationTabs', -2);});

    require.config({
        paths: {vs: jsRoot + 'monaco-editor/min/vs'},
        'vs/nls': {
            availableLanguages: {
                '*': clientLang
            }
        }
    });

    require(['vs/editor/editor.main'], function ()
    {
        var lang = 'php';
        $.each(fileExt, function(langName, ext)
        {
            if(ext.indexOf('.' + file.extension) !== -1) lang = langName;
        });

        var editor = monaco.editor.create(document.getElementById('codeContainer'),
        {
            autoIndent: true,
            value: codeContent,
            language: lang,
            contextmenu: true,
            EditorMinimapOptions: {
                enabled: false
            },
            readOnly: true,
            automaticLayout: true
        });

        editor.onMouseDown(function(obj)
        {
            var line = obj.target.position.lineNumber;

            var blame  = blames[line];
            var p_line = parseInt(line);
            while(!blame.revision)
            {
                p_line--;
                blame = blames[p_line];
            }
            if($('#log').data('line') == p_line) return;

            var time    = blame.time != 'unknown' ? blame.time : '';
            var user    = blame.committer != 'unknown' ? blame.committer : '';
            var version = blame.revision.toString().substring(0, 10);
            var content = blameTmpl.replace('%time', time).replace('%name', user).replace('%version', version).replace('%comment', blame.message);
            $('.history').text(content);
            $('#log').data('line', p_line);
            $('#log').css('display', 'flex');
            getRelation(blame.revision);
        })
    });

    $('#linkStory a, #linkBug a, #linkTask a').on('click', function()
    {
        var link = $(this).data('link');
        parent.loadLinkPage(link);
    })
});
</script>
