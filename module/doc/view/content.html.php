<?php js::set('confirmDelete', $lang->doc->confirmDelete);?>
<?php $sessionString = session_name() . '=' . session_id();?>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell" id="content">
      <div class="detail no-padding">
        <div class="detail-title no-padding doc-title">
          <div class="title" title="<?php echo $doc->title;?>">
            <?php echo $doc->title;?>
            <?php if($doc->deleted):?>
            <span class='label label-danger'><?php echo $lang->doc->deleted;?></span>
            <?php endif;?>
          </div>
          <div class="info">
            <?php $version = $version ? $version : $doc->version;?>
            <div class="version" data-version='<?php echo $version;?>'>
              <div class='btn-group'>
                <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;">
                  #<?php echo $version;?>
                  <span class="caret"></span>
                </a>
                <ul class='dropdown-menu doc-version-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
                <?php for($version = $doc->version; $version > 0; $version--): ?>
                  <li><a href='javascript:void(0)' data-url='<?php echo $this->createLink('doc', 'objectLibs', "type=$objectType&objectID=$object->id&libID=$libID&docID=$doc->id&version=$version"); ?>'>#<?php echo $version; ?></a></li>
                <?php endfor; ?>
                </ul>
              </div>
            </div>
            <div class="user"></div>
            <div class="time"></div>
          </div>
          <div class="actions">
            <?php
            echo html::a("javascript:fullScreen()", '<i class="icon-fullscreen"></i>', '', "title='{$lang->fullscreen}' class='btn btn-link fullscreen-btn'");
            if(common::hasPriv('doc', 'edit')) echo html::a(inlink('edit', "docID=$doc->id&comment=false&objectType=$objectType&objectID=$object->id&libID=$libID"), '<i class="icon-edit"></i>', '', "title='{$lang->doc->edit}' class='btn btn-link' data-app='{$this->app->tab}'");
            if(common::hasPriv('doc', 'delete'))
            {
                $deleteURL = $this->createLink('doc', 'delete', "docID=$doc->id&confirm=yes&from=lib");
                echo html::a("javascript:ajaxDeleteDoc(\"$deleteURL\", \"docList\", confirmDelete)", '<i class="icon-trash"></i>', '', "title='{$lang->doc->delete}' class='btn btn-link'");
            }
            ?>
            <?php if(common::hasPriv('doc', 'collect')):?>
            <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $lang->doc->collect;?>" class='ajaxCollect btn btn-link'><i class='icon <?php echo $star;?>'></i></a>
            <?php endif;?>

            <?php if($this->config->edition == 'max' and $this->app->tab == 'project'):?>
            <?php
            $canImportToPracticeLib  = common::hasPriv('doc', 'importToPracticeLib');
            $canImportToComponentLib = common::hasPriv('doc', 'importToComponentLib');

            if($canImportToPracticeLib or $canImportToComponentLib)
            {
                echo "<div class='btn-group' id='more'>";
                echo html::a('javascript:;', "<i class='icon icon-diamond'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
                echo "<ul class='dropdown-menu pull-right'>";
                if($canImportToPracticeLib) echo '<li>' . html::a('#importToPracticeLib', $lang->doc->importToPracticeLib, '', 'data-toggle="modal"') . '</li>';
                if($canImportToComponentLib) echo '<li>' . html::a('#importToComponentLib', $lang->doc->importToComponentLib, '', 'data-toggle="modal"') . '</li>';
                echo '</ul></div>';
            }
            ?>
            <?php endif;?>
          </div>
        </div>
        <div class="table-row">
          <div class="detail-content article-content table-col">
            <?php if($doc->keywords):?>
            <p class='keywords'>
              <?php foreach($doc->keywords as $keywords):?>
              <?php if($keywords) echo "<span class='label label-outline'>$keywords</span>";?>
              <?php endforeach;?>
            </p>
            <?php endif;?>
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
                echo "<textarea id='markdownContent'></textarea>";
            }
            else
            {
                echo $doc->content;
            }
            ?>
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
                <?php if(common::hasPriv('doc', 'deleteFile')) echo html::a('###', "<i class='icon icon-trash'></i>", '', "class='btn-icon' title=\"{$lang->doc->deleteFile}\" onclick='deleteFile($file->id)'");?>
              </span>
            </div>
            <?php unset($doc->files[$file->id]);?>
            <?php endif;?>
            <?php endforeach;?>
          </div>
          <?php if(!empty($outline) and strip_tags($outline)):?>
          <div class="outline table-col">
            <div class="outline-toggle"><i class="icon icon-angle-right"></i></div>
            <div class="outline-content">
              <?php echo $outline;?>
            </div>
          </div>
          <?php endif;?>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'true', 'object' => $doc));?>
      <div id="mainActions" class='main-actions hidden'>
        <?php common::printPreAndNext($preAndNext);?>
      </div>
    </div>
    <div class='cell'>
      <?php
      $canBeChanged = common::canBeChanged('doc', $doc);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=doc&objectID=$doc->id");
      ?>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class="side-col col-4" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-right"></i></div>
    <?php if(!empty($doc->digest)):?>
    <div class="cell" id='sidebarContent'>
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->doc->digest;?></summary>
        <div class="detail-content">
          <?php echo !empty($doc->digest) ? $doc->digest : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </details>
    </div>
    <?php endif;?>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->doc->basicInfo;?></summary>
        <div class="detail-content">
          <table class="table table-data">
            <tbody>
              <?php if($doc->productName):?>
              <tr>
                <th class='c-product'><?php echo $lang->doc->product;?></th>
                <td><?php echo $doc->productName;?></td>
              </tr>
              <?php endif;?>
              <?php if($doc->executionName):?>
              <tr>
                <th class='c-execution'><?php echo $lang->doc->execution;?></th>
                <td><?php echo $doc->executionName;?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th class='c-lib'><?php echo $lang->doc->lib;?></th>
                <td><?php echo $lib->name;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->module;?></th>
                <td><?php echo $doc->moduleName ? $doc->moduleName : '/';?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->addedDate;?></th>
                <td><?php echo $doc->addedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->editedBy;?></th>
                <td><?php echo zget($users, $doc->editedBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->editedDate;?></th>
                <td><?php echo $doc->editedDate;?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </details>
    </div>
  </div>
</div>

<?php if($this->config->edition == 'max'):?>
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
<?php js::set('markdownText', $doc->content);?>
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
