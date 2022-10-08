<?php
/* Actions. */
$lang->kanban->create              = '創建看板';
$lang->kanban->createSpace         = '創建空間';
$lang->kanban->editSpace           = '設置空間';
$lang->kanban->closeSpace          = '關閉空間';
$lang->kanban->deleteSpace         = '刪除空間';
$lang->kanban->sortSpace           = '空間排序';
$lang->kanban->edit                = '看板設置';
$lang->kanban->view                = '查看看板';
$lang->kanban->close               = '關閉看板';
$lang->kanban->delete              = '刪除看板';
$lang->kanban->createRegion        = '新增區域';
$lang->kanban->editRegion          = '編輯區域';
$lang->kanban->sortRegion          = '區域排序';
$lang->kanban->sortGroup           = '泳道組排序';
$lang->kanban->deleteRegion        = '刪除區域';
$lang->kanban->createLane          = '創建泳道';
$lang->kanban->editLane            = '泳道設置';
$lang->kanban->sortLane            = '泳道排序';
$lang->kanban->deleteLane          = '刪除泳道';
$lang->kanban->createColumn        = '創建看板列';
$lang->kanban->editColumn          = '編輯看板列';
$lang->kanban->sortColumn          = '看板列排序';
$lang->kanban->deleteColumn        = '刪除看板列';
$lang->kanban->createCard          = '創建卡片';
$lang->kanban->editCard            = '編輯卡片';
$lang->kanban->viewCard            = '查看卡片';
$lang->kanban->archiveCard         = '歸檔卡片';
$lang->kanban->sortCard            = '卡片排序';
$lang->kanban->copyCard            = '複製卡片';
$lang->kanban->moveCard            = '移動卡片';
$lang->kanban->cardColor           = '卡片顏色';
$lang->kanban->setCardColor        = '設置卡片顏色';
$lang->kanban->deleteCard          = '刪除卡片';
$lang->kanban->assigntoCard        = '指派';
$lang->kanban->setting             = '設置';
$lang->kanban->splitColumn         = '新增子看板列';
$lang->kanban->createColumnOnLeft  = '左側新增看板列';
$lang->kanban->createColumnOnRight = '右側新增看板列';
$lang->kanban->copyColumn          = '複製看板列';
$lang->kanban->archiveColumn       = '歸檔看板列';
$lang->kanban->spaceCommon         = '看板空間';
$lang->kanban->styleCommon         = '樣式';
$lang->kanban->copy                = '複製';
$lang->kanban->custom              = '自定義';
$lang->kanban->archived            = '已歸檔';
$lang->kanban->viewArchivedCard    = '查看已歸檔卡片';
$lang->kanban->viewArchivedColumn  = '查看已歸檔列';
$lang->kanban->archivedColumn      = '已歸檔的看板列';
$lang->kanban->archivedCard        = '已歸檔的卡片';
$lang->kanban->restoreColumn       = '還原看板列';
$lang->kanban->restoreCard         = '還原卡片';
$lang->kanban->restore             = '還原';
$lang->kanban->child               = '子';

/* Fields. */
$lang->kanban->space          = '所屬空間';
$lang->kanban->name           = '看板名稱';
$lang->kanban->archived       = '歸檔功能';
$lang->kanban->owner          = '負責人';
$lang->kanban->team           = '團隊';
$lang->kanban->desc           = '看板描述';
$lang->kanban->acl            = '訪問控制';
$lang->kanban->whitelist      = '白名單';
$lang->kanban->status         = '狀態';
$lang->kanban->createdBy      = '由誰創建';
$lang->kanban->createdDate    = '創建日期';
$lang->kanban->lastEditedBy   = '最後修改';
$lang->kanban->lastEditedDate = '最後修改日期';
$lang->kanban->closed         = '已關閉';
$lang->kanban->closedBy       = '由誰關閉';
$lang->kanban->closedDate     = '關閉日期';
$lang->kanban->empty          = '暫時沒有看板';
$lang->kanban->teamSumCount   = '共%s人';

$lang->kanban->createColumnOnLeft  = '在左側添加看板列';
$lang->kanban->createColumnOnRight = '在右側添加看板列';

$lang->kanban->accessDenied        = '您無權訪問該看板';
$lang->kanban->confirmDeleteSpace  = '您確認要删除該空間嗎？';
$lang->kanban->confirmDeleteKanban = '您確認要删除該看板嗎？';

$lang->kanban->aclGroup['open']    = '公開';
$lang->kanban->aclGroup['private'] = '私有';

$lang->kanban->aclList['extend']  = '繼承空間訪問權限（能訪問當前空間，即可訪問）';
$lang->kanban->aclList['private'] = '私有（看板團隊成員、白名單、空間負責人可訪問）';

$lang->kanban->enableArchived['0'] = '不啟用';
$lang->kanban->enableArchived['1'] = '啟用';

$lang->kanban->type = array();
$lang->kanban->type['all']   = "綜合看板";
$lang->kanban->type['story'] = "{$lang->SRCommon}看板";
$lang->kanban->type['task']  = "任務看板";
$lang->kanban->type['bug']   = "Bug看板";

$lang->kanban->group = new stdClass();

$lang->kanban->group->all = array();
$lang->kanban->group->story = array();
$lang->kanban->group->story['default']    = "預設方式";
$lang->kanban->group->story['pri']        = "需求優先順序";
$lang->kanban->group->story['category']   = "需求類別";
$lang->kanban->group->story['module']     = "需求模組";
$lang->kanban->group->story['source']     = "需求來源";
$lang->kanban->group->story['assignedTo'] = "指派人員";

$lang->kanban->group->task = array();
$lang->kanban->group->task['default']    = "預設方式";
$lang->kanban->group->task['pri']        = "任務優先順序";
$lang->kanban->group->task['type']       = "任務類型";
$lang->kanban->group->task['module']     = "任務所屬模組";
$lang->kanban->group->task['assignedTo'] = "指派人員";
$lang->kanban->group->task['story']      = "{$lang->SRCommon}";

$lang->kanban->group->bug = array();
$lang->kanban->group->bug['default']    = "預設方式";
$lang->kanban->group->bug['pri']        = "Bug優先順序";
$lang->kanban->group->bug['severity']   = "Bug嚴重程度";
$lang->kanban->group->bug['module']     = "Bug模組";
$lang->kanban->group->bug['type']       = "Bug類型";
$lang->kanban->group->bug['assignedTo'] = "指派人員";

$lang->kanban->WIP                = 'WIP';
$lang->kanban->setWIP             = '在製品設置';
$lang->kanban->WIPStatus          = '在製品狀態';
$lang->kanban->WIPStage           = '在製品階段';
$lang->kanban->WIPType            = '在製品類型';
$lang->kanban->WIPCount           = '在製品數量';
$lang->kanban->noLimit            = '不限制∞';
$lang->kanban->setLane            = '泳道設置';
$lang->kanban->laneName           = '泳道名稱';
$lang->kanban->laneColor          = '泳道顏色';
$lang->kanban->setColumn          = '看板列設置';
$lang->kanban->columnName         = '看板列名稱';
$lang->kanban->columnColor        = '看板列顏色';
$lang->kanban->noColumnUniqueName = '看板列名稱已存在';
$lang->kanban->moveUp             = '泳道上移';
$lang->kanban->moveDown           = '泳道下移';
$lang->kanban->laneMove           = '泳道移動';
$lang->kanban->laneGroup          = '泳道分組';
$lang->kanban->cardsSort          = '卡片排序';
$lang->kanban->more               = '更多';
$lang->kanban->moreAction         = '更多操作';
$lang->kanban->noGroup            = '無';
$lang->kanban->limitExceeded      = '超出在製品限制';
$lang->kanban->fullScreen         = '全屏';
$lang->kanban->setting            = '設置';
$lang->kanban->my                 = '我的看板';
$lang->kanban->other              = '其他';

$lang->kanban->error = new stdclass();
$lang->kanban->error->mustBeInt       = '在製品數量必須是正整數。';
$lang->kanban->error->parentLimitNote = '父列的在製品數量不能小於子列在製品數量之和';
$lang->kanban->error->childLimitNote  = '子列在製品數量之和不能大於父列的在製品數量';

$lang->kanban->defaultColumn = array('未開始', '進行中', '已完成', '已關閉');

$lang->kanban->laneTypeList = array();
$lang->kanban->laneTypeList['story'] = $lang->SRCommon;
$lang->kanban->laneTypeList['bug']   = 'Bug';
$lang->kanban->laneTypeList['task']  = '任務';

$lang->kanban->storyColumn = array();
$lang->kanban->storyColumn['backlog']    = 'Backlog';
$lang->kanban->storyColumn['ready']      = '準備好';
$lang->kanban->storyColumn['develop']    = '開發';
$lang->kanban->storyColumn['developing'] = '進行中';
$lang->kanban->storyColumn['developed']  = '完成';
$lang->kanban->storyColumn['test']       = '測試';
$lang->kanban->storyColumn['testing']    = '進行中';
$lang->kanban->storyColumn['tested']     = '完成';
$lang->kanban->storyColumn['verified']   = '已驗收';
$lang->kanban->storyColumn['released']   = '已發佈';
$lang->kanban->storyColumn['closed']     = '已關閉';

$lang->kanban->bugColumn = array();
$lang->kanban->bugColumn['unconfirmed'] = '待確認';
$lang->kanban->bugColumn['confirmed']   = '已確認';
$lang->kanban->bugColumn['resolving']   = '解決中';
$lang->kanban->bugColumn['fixing']      = '進行中';
$lang->kanban->bugColumn['fixed']       = '完成';
$lang->kanban->bugColumn['test']        = '測試';
$lang->kanban->bugColumn['testing']     = '測試中';
$lang->kanban->bugColumn['tested']      = '測試完畢';
$lang->kanban->bugColumn['closed']      = '已關閉';

$lang->kanban->taskColumn = array();
$lang->kanban->taskColumn['wait']       = '未開始';
$lang->kanban->taskColumn['develop']    = '開發';
$lang->kanban->taskColumn['developing'] = '研發中';
$lang->kanban->taskColumn['developed']  = '研發完畢';
$lang->kanban->taskColumn['pause']      = '已暫停';
$lang->kanban->taskColumn['canceled']   = '已取消';
$lang->kanban->taskColumn['closed']     = '已關閉';

$lang->kanbanspace = new stdclass();
$lang->kanbanspace->common         = '看板空間';
$lang->kanbanspace->name           = '空間名稱';
$lang->kanbanspace->owner          = '負責人';
$lang->kanbanspace->team           = '團隊';
$lang->kanbanspace->desc           = '空間描述';
$lang->kanbanspace->acl            = '訪問控制';
$lang->kanbanspace->whitelist      = '白名單';
$lang->kanbanspace->status         = '狀態';
$lang->kanbanspace->createdBy      = '由誰創建';
$lang->kanbanspace->createdDate    = '創建日期';
$lang->kanbanspace->lastEditedBy   = '最後修改';
$lang->kanbanspace->lastEditedDate = '最後修改日期';
$lang->kanbanspace->closedBy       = '由誰關閉';
$lang->kanbanspace->closedDate     = '關閉日期';

$lang->kanbanspace->empty = '暫時沒有空間';

$lang->kanbanspace->aclList['open']    = '公開（有看板空間視圖權限即可訪問）';
$lang->kanbanspace->aclList['private'] = '私有（只有看板空間負責人、團隊成員、白名單可訪問）';

$lang->kanbanspace->featureBar['all']    = '所有';
$lang->kanbanspace->featureBar['my']     = '我的空間';
$lang->kanbanspace->featureBar['other']  = '其他空間';
$lang->kanbanspace->featureBar['closed'] = '已關閉';

$lang->kanbancolumn = new stdclass();
$lang->kanbancolumn->name       = $lang->kanban->columnName;
$lang->kanbancolumn->limit      = $lang->kanban->WIPCount;
$lang->kanbancolumn->color      = '看板列顏色';
$lang->kanbancolumn->childName  = '子列名稱';
$lang->kanbancolumn->childColor = '子狀態顏色';
$lang->kanbancolumn->empty      = '暫時沒有看板列';

$lang->kanbancolumn->confirmArchive = '您確認歸檔該列嗎？歸檔列後，該列和列中所有卡片將被隱藏，您可以在區域-已歸檔中查看已歸檔的列。';
$lang->kanbancolumn->confirmDelete  = '您確認刪除該列嗎？刪除列後，該列中所有卡片也會被刪除。';
$lang->kanbancolumn->confirmRestore = '您確定要還原該看板列嗎？還原看板列後，該看板列和看板列中所有的任務將同時還原到之前的位置。';

$lang->kanbanlane = new stdclass();
$lang->kanbanlane->name      = $lang->kanban->laneName;
$lang->kanbanlane->common    = '泳道';
$lang->kanbanlane->default   = '預設泳道';
$lang->kanbanlane->column    = '泳道看板列';
$lang->kanbanlane->otherlane = '選擇共享看板列的泳道';
$lang->kanbanlane->color     = '泳道顏色';

$lang->kanbanlane->confirmDelete = '您確認刪除該泳道嗎？刪除泳道後，該泳道中所有數據（列、卡片）也會被刪除。';

$lang->kanbanlane->modeList['sameAsOther'] = '與其他泳道使用相同看板列';
$lang->kanbanlane->modeList['independent'] = '採用獨立的看板列';

$lang->kanbanregion = new stdclass();
$lang->kanbanregion->name    = '區域名稱';
$lang->kanbanregion->default = '預設區域';
$lang->kanbanregion->style   = '區域樣式';

$lang->kanbanregion->confirmDelete = '您確認刪除該區域嗎？刪除該區域後，該區域中所有數據將會被刪除。';

$lang->kanbancard = new stdclass();
$lang->kanbancard->create  = '創建卡片';
$lang->kanbancard->edit    = '編輯卡片';
$lang->kanbancard->view    = '查看卡片';
$lang->kanbancard->archive = '歸檔';
$lang->kanbancard->assign  = '指派';
$lang->kanbancard->copy    = '複製';
$lang->kanbancard->delete  = '刪除';

$lang->kanbancard->name            = '卡片名稱';
$lang->kanbancard->legendBasicInfo = '基本信息';
$lang->kanbancard->legendLifeTime  = '卡片的一生';
$lang->kanbancard->space           = '所屬空間';
$lang->kanbancard->region          = '所屬區域';
$lang->kanbancard->kanban          = '所屬看板';
$lang->kanbancard->lane            = '所屬泳道';
$lang->kanbancard->column          = '所屬看板列';
$lang->kanbancard->assignedTo      = '指派給';
$lang->kanbancard->beginAndEnd     = '起止日期';
$lang->kanbancard->begin           = '預計開始';
$lang->kanbancard->end             = '截止日期';
$lang->kanbancard->pri             = '優先順序';
$lang->kanbancard->desc            = '描述';
$lang->kanbancard->estimate        = '預計';
$lang->kanbancard->createdBy       = '由誰創建';
$lang->kanbancard->createdDate     = '由誰創建';
$lang->kanbancard->lastEditedBy    = '由誰編輯';
$lang->kanbancard->lastEditedDate  = '最後編輯時間';
$lang->kanbancard->archivedBy      = '由誰歸檔';
$lang->kanbancard->archivedDate    = '歸檔時間';
$lang->kanbancard->lblHour         = 'h';
$lang->kanbancard->noAssigned      = '未指派';
$lang->kanbancard->deadlineAB      = '截止';
$lang->kanbancard->beginAB         = '開始';
$lang->kanbancard->to              = '~';
$lang->kanbancard->archived        = '已歸檔';
$lang->kanbancard->empty           = '暫時沒有卡片';

$lang->kanbancard->confirmArchive    = '您確認歸檔該卡片嗎？歸檔卡片後，該卡片將從列中隱藏，您可以在區域-已歸檔中查看。';
$lang->kanbancard->confirmDelete     = '您確認刪除該卡片嗎？刪除卡片後，該卡片將從看板中刪除，您只能通過系統資源回收筒查看。';
$lang->kanbancard->confirmRestore    = '您確定要還原該卡片嗎？還原卡片後，該卡片將還原到“%s”看板列中。';
$lang->kanbancard->confirmRestoreTip = '該卡片所屬的看板列已被歸檔或刪除，請先還原“%s”看板列。';

$lang->kanbancard->priList[1] = 1;
$lang->kanbancard->priList[2] = 2;
$lang->kanbancard->priList[3] = 3;
$lang->kanbancard->priList[4] = 4;

$lang->kanbancard->colorList['#fff']    = '預設';
$lang->kanbancard->colorList['#b10b0b'] = '阻塞';
$lang->kanbancard->colorList['#cfa227'] = '警告';
$lang->kanbancard->colorList['#2a5f29'] = '加急';

$lang->kanbancard->error = new stdClass();
$lang->kanbancard->error->recordMinus = '預計不能為負數!';
$lang->kanbancard->error->endSmall    = '"截止日期"不能小於"預計開始"!';
