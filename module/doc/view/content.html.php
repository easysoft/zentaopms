<?php js::set('confirmDelete', $lang->doc->confirmDelete);?>
<?php js::set('latestVersion', $doc->version);?>
<?php $sessionString = session_name() . '=' . session_id();?>
<div style="height:100%" id="h-full">
  <div class="main-col col-8 flex-content">
    <div class="cell" id="content">
      <div class="detail no-padding">
        <div class="detail-title no-padding doc-title">
          <div class="flex-left">
          <div class="title" title="<?php echo $doc->title;?>">
            <?php echo $doc->title;?>
            <?php if($doc->deleted):?>
            <span class='label label-danger'><?php echo $lang->doc->deleted;?></span>
            <?php endif;?>
          </div>
          <?php if($doc->status != 'draft'):?>
          <div class="info">
            <?php $version = $version ? $version : $doc->version;?>
            <div class="version" data-version='<?php echo $version;?>'>
              <div id="diffBtnGroup" class='btn-group exchangeDiffGroup'>
                <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis left-dom' data-toggle='dropdown' style="max-width: 120px;">
                  V<?php echo $version;?>
                  <span class="caret"></span>
                </a>
                <i id="exchangeDiffBtn" class="icon icon-exchange right-dom"></i>
                <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis right-dom' data-toggle='dropdown' style="max-width: 120px;">
                  <span class="caret"></span>
                </a>
                <ul id="docVersionMenu" class='dropdown-menu doc-version-menu' style='width: 260px; overflow-y:auto'>
                  <li class="drop-title flex-between dropdown-header not-clear-menu"><div><?php echo $lang->doc->allVersion?></div></li>
                  <div class="drop-body menu-active-primary menu-hover-primary">
                  <?php for($itemVersion = $doc->version; $itemVersion > 0; $itemVersion--):?>
                    <li class="li-item <?php if($itemVersion == $version) echo 'active';?>"><div class="checkbox-primary"><input type="checkbox" <?php echo "data-id=".$doc->id." data-version=".$itemVersion;?> ></input><label for=""></label></div><a href='javascript:void(0)' data-url='<?php echo $this->createLink('doc', 'view', "docID=$doc->id&version=$itemVersion"); ?>' data-version='<?php echo $itemVersion;?>'>V<?php echo $itemVersion;?></a></li>
                  <?php endfor;?>
                  </div>
                  <li class="drop-bottom"><button data-id="confirm" class="btn btn-primary"><?php echo $lang->confirm?></button><button data-id="cancel" class="btn"><?php echo $lang->doc->cancelDiff?></button></li>
                </ul>
              </div>
            </div>
          </div>
          <?php endif;?>
          </div>
          <div class="actions">
            <?php echo html::a("javascript:fullScreen()", '<span class="icon-fullscreen"></span>', '', "title='{$lang->fullscreen}' class='btn btn-link fullscreen-btn'");?>
            <?php if(common::hasPriv('doc', 'collect')):?>
            <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'star' : 'star-empty';?>
            <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $lang->doc->collect;?>" class='ajaxCollect btn btn-link'><?php echo html::image("static/svg/{$star}.svg", "class='$star'");?></a>
            <?php endif;?>

            <?php if($config->vision == 'rnd' and ($config->edition == 'max' or $config->edition == 'ipd') and $app->tab == 'project'):?>
            <?php
            $canImportToPracticeLib  = (common::hasPriv('doc', 'importToPracticeLib')  and helper::hasFeature('practicelib'));
            $canImportToComponentLib = (common::hasPriv('doc', 'importToComponentLib') and helper::hasFeature('componentlib'));

            if($canImportToPracticeLib or $canImportToComponentLib)
            {
                echo "<div class='btn-group inline'>";
                echo html::a('javascript:;', "<span class='icon icon-diamond'></span>", '', "data-toggle='dropdown' id='more' title='{$lang->import}' class='btn btn-link'");
                echo "<ul class='dropdown-menu pull-right'>";
                if($canImportToPracticeLib) echo '<li>' . html::a('#importToPracticeLib', $lang->doc->importToPracticeLib, '', 'data-toggle="modal"') . '</li>';
                if($canImportToComponentLib) echo '<li>' . html::a('#importToComponentLib', $lang->doc->importToComponentLib, '', 'data-toggle="modal"') . '</li>';
                echo '</ul></div>';
            }
            ?>
            <?php endif;?>

            <?php
            if(common::hasPriv('doc', 'edit'))
            {
                $iframe   = '';
                $onlybody = false;
                if($doc->type != 'text' or isonlybody())
                {
                    $iframe   = 'iframe';
                    $onlybody = 'true';
                }
                echo html::a(inlink('edit', "docID=$doc->id&comment=false&objectType=$objectType&objectID=$object->id&libID=$libID", '', $onlybody), '<span class="icon-edit"></span>', '', "title='{$lang->doc->edit}' class='btn btn-link $iframe' data-app='{$this->app->tab}'");
            }
            if(common::hasPriv('doc', 'delete'))
            {
                $deleteURL = $this->createLink('doc', 'delete', "docID=$doc->id&confirm=yes&from=lib");
                echo html::a("javascript:ajaxDeleteDoc(\"$deleteURL\", \"docList\", confirmDelete)", '<span class="icon-trash"></span>', '', "title='{$lang->doc->delete}' class='btn btn-link'");
            }?>
            <a id="hisTrigger" href="###" class="btn btn-link" title=<?php echo $lang->history?>><span class="icon icon-clock"></span></a>
          </div>
          <?php if(!empty($editors)):?>
          <div id='editorBox'>
            <?php $groupClass = count($editors) == 1 ? 'noDropdown' : '';?>
            <div class="btn-group <?php echo $groupClass;?>">
              <?php
              $space       = common::checkNotCN() ? ' ' : '';
              $firstEditor = current($editors);
              $editorInfo  = zget($users, $firstEditor->account) . ' ' . substr($firstEditor->date, 0, 10) . $space . $lang->doc->update;

              array_shift($editors);
              ?>
              <?php if(!empty($editors)):?>
              <button class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                <span class="text" title='<?php echo $editorInfo;?>'><?php echo $editorInfo;?></span>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" id='editorMenu'>
              <?php
              foreach($editors as $editor)
              {
                  $editorInfo = zget($users, $editor->account) . ' ' . substr($editor->date, 0, 10) . $space . $lang->doc->update;
                  echo "<li title='$editorInfo'>$editorInfo</li>";
              }
              ?>
              </ul>
              <?php else:?>
              <span class="text" title='<?php echo $editorInfo;?>'><?php echo $editorInfo;?></span>
              <?php endif;?>
            </div>
          </div>
          <?php endif;?>
        </div>
        <div id="diffContain">
        <div class="detail-content article-content table-col" <?php if('attachment' == $doc->type) echo 'style="max-height: 60px"';?>>
            <div class='info'>
              <?php $createInfo = $doc->status == 'draft' ? zget($users, $doc->addedBy) . " {$lang->colon} " . substr($doc->addedDate, 0, 10) . (common::checkNotCN() ? ' ' : '') . $lang->doc->createAB : zget($users, $doc->releasedBy) . " {$lang->colon} " . substr($doc->releasedDate, 0, 10) . (common::checkNotCN() ? ' ' : '') . $lang->doc->release;?>
              <span class='user-time text-muted'><i class='icon-contacts'></i> <?php echo $createInfo;?></span>
              <span class='user-time text-muted'><i class='icon-star'></i> <?php echo $doc->collects;?></span>
              <span class='user-time text-muted'><i class='icon-eye'></i> <?php echo $doc->views;?></span>
              <?php if($doc->keywords):?>
              <span class='keywords'>
                <?php foreach($doc->keywords as $keywords):?>
                <?php if($keywords) echo "<span class='label label-outline' title='{$keywords}'>{$keywords}</span>";?>
                <?php endforeach;?>
              </span>
              <?php endif;?>
            </div>
            <?php
            if($doc->type == 'url' and $autoloadPage)
            {
                $url = $doc->content;
                if(!preg_match('/^https?:\/\//', $doc->content)) $url = 'http://' . $url;
                $urlIsHttps = strpos($url, 'https://') === 0;
                $ztIsHttps  = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https'));

                if(($urlIsHttps and $ztIsHttps) or (!$urlIsHttps and !$ztIsHttps))
                {
                    echo "<iframe width='100%' id='urlIframe' src='$url'></iframe>";
                }
                else
                {
                    $parsedUrl = parse_url($url);
                    $urlDomain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

                    $title    = '';
                    $response = common::http($url);
                    preg_match_all('/<title>(.*)<\/title>/Ui', $response, $out);
                    if(isset($out[1][0])) $title = $out[1][0];

                    echo "<div id='urlCard'>";
                    echo "<div class='url-icon'><img src='{$urlDomain}/favicon.ico' width='45' height='45' /></div>";
                    echo "<div class='url-content'>";
                    echo "<div class='url-title'>{$title}</div>";
                    echo "<div class='url-href'>" . html::a($url, $url, '_target') . "</div>";
                    echo "</div></div>";
                }
            }
            elseif($doc->contentType == 'markdown')
            {
                echo "<textarea id='markdownContent'>{$doc->content}</textarea>";
            }
            elseif($doc->type != 'attachment')
            {
                echo $doc->content;
            }
            ?>
            <?php if($doc->type != 'attachment'):?>
            <?php foreach($doc->files as $file):?>
            <?php if(in_array($file->extension, $config->file->imageExtensions)):?>
            <div class='file-image'>
              <a href="<?php echo $file->webPath?>" target="_blank">
                <img onload="setImageSize(this, 0)" src="<?php echo $this->createLink('file', 'read', "fileID={$file->id}");?>" alt="<?php echo $file->title?>" title="<?php echo $file->title;?>">
              </a>
              <span class='right-icon'>
                <?php
                if(common::hasPriv('file', 'download'))
                {
                    $downloadLink  = $this->createLink('file', 'download', 'fileID=' . $file->id);
                    $downloadLink .= strpos($downloadLink, '?') === false ? '?' : '&';
                    $downloadLink .= $sessionString;
                    echo html::a($downloadLink, "<i class='icon icon-import'></i>", '', "class='btn-icon' style='margin-right: 10px;' title=\"{$lang->doc->download}\"");
                }
                ?>
                <?php if(common::hasPriv('doc', 'deleteFile') and $doc->version == $doc->contentVersion) echo html::a('###', "<i class='icon icon-trash'></i>", '', "class='btn-icon' title=\"{$lang->doc->deleteFile}\" onclick='deleteFile($file->id)'");?>
              </span>
            </div>
            <?php unset($doc->files[$file->id]);?>
            <?php endif;?>
            <?php endforeach;?>
            <?php endif;?>
          </div>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'true', 'object' => $doc));?>
      <div id="mainActions" class='main-actions hidden'>
        <?php common::printPreAndNext($preAndNext);?>
      </div>
    </div>
    <div id="outlineMenu" class="outline table-col">
      <div class="outline-content">
      <?php echo isset($outline) ? $outline : '';?>
      </div>
    </div>
    <div class="outline-toggle"><i class="icon icon-menu-arrow-left"></i></div>
    <div id="history" class='panel hidden' style="margin-left: 2px;">
      <?php
      $canBeChanged = common::canBeChanged('doc', $doc);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=doc&objectID=$doc->id");
      ?>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>

<?php if($config->edition == 'max' or $config->edition == 'ipd'):?>
<div class="modal fade" id="importToPracticeLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->doc->importToPracticeLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('doc', 'importToPracticeLib', "doc=$doc->id");?>'>
          <table class='table table-form'>
            <tr>
              <th class='w-120px'><?php echo $lang->doc->practiceLib;?></th>
              <td>
                <?php echo html::select('lib', $practiceLibs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approvePractice') and !common::hasPriv('assetlib', 'batchApprovePractice')):?>
            <tr>
              <th><?php echo $lang->doc->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $practiceApprovers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="importToComponentLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->doc->importToComponentLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('doc', 'importToComponentLib', "doc=$doc->id");?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->doc->componentLib;?></th>
              <td>
                <?php echo html::select('lib', $componentLibs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveComponent') and !common::hasPriv('assetlib', 'batchApproveComponent')):?>
            <tr>
              <th><?php echo $lang->doc->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $componentApprovers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php if($doc->contentType == 'markdown'):?>
<?php css::import($jsRoot . "markdown/simplemde.min.css");?>
<?php js::import($jsRoot . 'markdown/simplemde.min.js'); ?>
<?php
    $markdownText = preg_replace("/(\r\n)+|\r+|\n+/", "\n", $doc->content);
    js::set('markdownText', htmlspecialchars($markdownText));
?>
<script>
$(function()
{
    var simplemde = new SimpleMDE({element: $("#markdownContent")[0],toolbar:false, status: false});
    simplemde.value(String(markdownText));
    simplemde.togglePreview();

    $('#content .CodeMirror .editor-preview a').attr('target', '_blank');
})
</script>
<?php endif;?>
