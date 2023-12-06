<?php
/**
 * The story module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  story
 * @version  $Id: vi.php 5141 2013-07-15 05:57:15Z quocnho@gmail.com $
 * @link  https://www.zentao.net
 */
global $config;
$lang->story->create            = "Create Story";

$lang->story->requirement       = zget($lang, 'UrCommon', "Requirement");
$lang->story->story             = zget($lang, 'SrCommon', "Story");
$lang->story->createStory       = 'Create ' . $lang->story->story;
$lang->story->createRequirement = 'Create ' . $lang->story->requirement;
$lang->story->affectedStories   = "Affected {$lang->story->story}";

$lang->story->batchCreate       = "Tạo hàng loạt";
$lang->story->change            = "Thay đổi";
$lang->story->changed           = "Đã đổi";
$lang->story->assignTo          = 'Giao cho';
$lang->story->review            = 'Xét duyệt';
$lang->story->submitReview      = "Submit Review";
$lang->story->recall            = 'Revoke';
$lang->story->recallChange      = 'Undo Changes';
$lang->story->recallAction      = 'Undo';
$lang->story->needReview        = 'Duyệt nhu cầu';
$lang->story->batchReview       = 'Duyệt hàng loạt';
$lang->story->edit              = "Sửa câu chuyện";
$lang->story->editDraft         = "Sửa soạn thảo";
$lang->story->batchEdit         = "Sửa hàng loạt";
$lang->story->subdivide         = 'Phân rã';
$lang->story->link              = 'Link';
$lang->story->unlink            = 'Unlink';
$lang->story->track             = 'Track';
$lang->story->trackAB           = 'Track';
$lang->story->processStoryChange= 'Process Story Change';
$lang->story->splitRequirent    = 'Phân rã';
$lang->story->close             = 'Đóng';
$lang->story->batchClose        = 'Đóng hàng loạt';
$lang->story->activate          = 'Kích hoạt';
$lang->story->delete            = "Xóa";
$lang->story->view              = "Chi tiết câu chuyện";
$lang->story->setting           = "Thiết lập";
$lang->story->tasks             = "Nhiệm vụ liên kết";
$lang->story->bugs              = "Bugs liên kết";
$lang->story->cases             = "Tình huống liên kết";
$lang->story->taskCount         = 'Nhiệm vụ';
$lang->story->bugCount          = 'Bugs';
$lang->story->caseCount         = 'Tình huống';
$lang->story->taskCountAB       = 'T';
$lang->story->bugCountAB        = 'B';
$lang->story->caseCountAB       = 'C';
$lang->story->linkStory         = 'Liên kết câu chuyện';
$lang->story->unlinkStory       = 'Hủy liên kết';
$lang->story->export            = "Xuất dữ liệu";
$lang->story->zeroCase          = "Câu chuyện không tình huống";
$lang->story->zeroTask          = "Chỉ liệt kê câu chuyện không nhiệm vụ";
$lang->story->reportChart       = "Báo cáo";
$lang->story->copyTitle         = "Sao chép tiêu đề";
$lang->story->batchChangePlan   = "Thay đổi kế hoạch hàng loạt";
$lang->story->batchChangeBranch = "Thay đổi chi nhánh hàng loạt";
$lang->story->batchChangeStage  = "Thay đổi giai đoạn hàng loạt";
$lang->story->batchAssignTo     = "Bàn giao hàng loạt";
$lang->story->batchChangeModule = "Thay đổi Module hàng loạt";
$lang->story->viewAll           = "Xem tất cả";
$lang->story->toTask            = 'Convert to Task';
$lang->story->batchToTask       = 'Batch Convert to Task';
$lang->story->convertRelations  = 'Convert Relations';
$lang->story->undetermined       = 'undetermined';
$lang->story->order              = 'Order';
$lang->story->saveDraft          = 'Save as draft';
$lang->story->doNotSubmit        = 'Do Not Submit';

$lang->story->editAction      = "Edit {$lang->SRCommon}";
$lang->story->changeAction    = "Change {$lang->SRCommon}";
$lang->story->assignAction    = "Assign {$lang->SRCommon}";
$lang->story->reviewAction    = "Review {$lang->SRCommon}";
$lang->story->subdivideAction = "Subdivide {$lang->SRCommon}";
$lang->story->closeAction     = "Close {$lang->SRCommon}";
$lang->story->activateAction  = "Activate {$lang->SRCommon}";
$lang->story->deleteAction    = "Delete {$lang->SRCommon}";
$lang->story->exportAction    = "Export {$lang->SRCommon}";
$lang->story->reportAction    = "Report";

$lang->story->skipStory        = '%s is a parent story. It cannot be closed.';
$lang->story->closedStory      = 'Story %s is closed and will not be closed.';
$lang->story->batchToTaskTips  = "This action will create a task with the same name as the selected {$lang->SRCommon} and link {$lang->SRCommon} to the task. The closed {$lang->SRCommon} will not be converted into tasks.";
$lang->story->successToTask    = "Converted to task.";
$lang->story->storyRound       = '%s time estimation';
$lang->story->float            = "『%s』should be positive number, decimals included.";
$lang->story->saveDraftSuccess = 'Save as draft succeeded.';

$lang->story->changeSyncTip       = "The modification of this story will be synchronized to the following twin requirements";
$lang->story->syncTip             = "The twin story are synchronized except for product, branch, module, plan, and stage. After the twin relationship is dissolved, they are no longer synchronized.";
$lang->story->assignSyncTip       = "Both twin stories modify the assignor synchronously";
$lang->story->closeSyncTip        = "Twin stories are closed synchronously";
$lang->story->activateSyncTip     = "Twin stories are activated synchronously";
$lang->story->relievedTwinsTip    = "After product adjustment, the twin relationship of this story will be automatically removed, and the story will no longer be synchronized. Do you want to save?";
$lang->story->batchEditTip        = "{$lang->SRCommon} %sis twin stories, and this operation has been filtered.";

$lang->story->id             = 'ID';
$lang->story->parent         = 'Mẹ';
$lang->story->product        = $lang->productCommon;
$lang->story->project        = 'Project';
$lang->story->branch         = "Branch/Platform";
$lang->story->module         = 'Module';
$lang->story->moduleAB       = 'Module';
$lang->story->source         = 'Từ';
$lang->story->sourceNote     = 'Ghi chú';
$lang->story->fromBug        = 'Từ Bug';
$lang->story->title          = 'Tiêu đề';
$lang->story->type           = "Story/Requirement";
$lang->story->category       = 'Category';
$lang->story->color          = 'Màu';
$lang->story->toBug          = 'Tới Bug';
$lang->story->spec           = 'Mô tả';
$lang->story->assign         = 'Giao cho';
$lang->story->verify         = 'Chấp thuận';
$lang->story->pri            = 'Ưu tiên';
$lang->story->estimate       = "{$lang->hourCommon} dự tính";
$lang->story->estimateAB     = 'DT.' . $lang->hourCommon == 'giờ' ? '(giờ)' : '(SP)';
$lang->story->hour           = $lang->hourCommon;
$lang->story->consumed       = 'Mất thời gian';
$lang->story->status         = 'Tình trạng';
$lang->story->subStatus      = 'Tình trạng con';
$lang->story->stage          = 'Giai đoạn';
$lang->story->stageAB        = 'Giai đoạn';
$lang->story->stagedBy       = 'Thiết lập bởi';
$lang->story->mailto         = 'Mail tới';
$lang->story->openedBy       = 'Người tạo';
$lang->story->openedDate     = 'Ngày tạo';
$lang->story->assignedTo     = 'Giao cho';
$lang->story->assignedDate   = 'Ngày giao';
$lang->story->lastEditedBy   = 'Người sửa';
$lang->story->lastEditedDate = 'Ngày sửa';
$lang->story->closedBy       = 'Người đóng';
$lang->story->closedDate     = 'Ngày đóng';
$lang->story->closedReason   = 'Lý do';
$lang->story->rejectedReason = 'Lý do từ chối';
$lang->story->changedBy      = 'ChangedBy';
$lang->story->changedDate    = 'ChangedDate';
$lang->story->reviewedBy     = 'Người duyệt';
$lang->story->reviewer       = $lang->story->reviewedBy;
$lang->story->reviewers      = 'Reviewers';
$lang->story->reviewedDate   = 'Ngày duyệt';
$lang->story->activatedDate  = 'Activated Date';
$lang->story->version        = 'Phiên bản';
$lang->story->feedbackBy     = 'From Name';
$lang->story->notifyEmail    = 'From Email';
$lang->story->plan           = 'Kế hoạch liên kết';
$lang->story->planAB         = 'Kế hoạch';
$lang->story->comment        = 'Nhận xét';
$lang->story->children       = "$lang->SRCommon} con";
$lang->story->childrenAB     = "C";
$lang->story->linkStories    = 'Câu chuyện liên kết';
$lang->story->childStories   = 'Câu chuyện được phân rã';
$lang->story->duplicateStory = 'ID câu chuyện được nhân bản';
$lang->story->reviewResult   = 'Duyệt kết quả';
$lang->story->reviewResultAB = 'Kết quả đánh giá';
$lang->story->preVersion     = 'Phiên bản mới nhất';
$lang->story->keywords       = 'Tags';
$lang->story->newStory       = 'Tiếp tục thêm';
$lang->story->colorTag       = 'Màu';
$lang->story->files          = 'Files';
$lang->story->copy           = "Copy";
$lang->story->total          = "Tổng câu chuyện";
$lang->story->draft          = 'Nháp';
$lang->story->unclosed       = 'Chưa đóng';
$lang->story->deleted        = 'Đã xóa';
$lang->story->released       = 'Đã phát hành';
$lang->story->URChanged      = 'Requirement Changed';
$lang->story->design         = 'Designs';
$lang->story->case           = 'Cases';
$lang->story->bug            = 'Bugs';
$lang->story->repoCommit     = 'Commits';
$lang->story->one            = 'One';
$lang->story->field          = 'Synchronized fields';
$lang->story->completeRate   = 'Completion Rate';
$lang->story->reviewed       = 'Reviewed';
$lang->story->toBeReviewed   = 'To Be Reviewed';
$lang->story->linkMR         = 'Related MRs';
$lang->story->linkCommit     = 'Related Commits';
$lang->story->URS            = 'User requirements';

$lang->story->ditto       = 'Như trên';
$lang->story->dittoNotice = 'Câu chuyện này chưa liên kết tới cùng sản phẩm bởi bởi vì nó là cuối cùng!';

$lang->story->needNotReviewList[0] = 'Need Review';
$lang->story->needNotReviewList[1] = 'Need Not Review';

$lang->story->useList[0] = 'Có';
$lang->story->useList[1] = 'Không';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = 'Nháp';
$lang->story->statusList['reviewing'] = 'Đang xem xét';
$lang->story->statusList['active']    = 'Kích hoạt';
$lang->story->statusList['closed']    = 'Đã đóng';
$lang->story->statusList['changing']  = 'Đã thay đổi';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = 'Đang đợi';
$lang->story->stageList['planned']    = 'Kế hoạch';
$lang->story->stageList['projected']  = 'Đã dự án';
$lang->story->stageList['developing'] = 'Đang phát triển';
$lang->story->stageList['developed']  = 'Đang phát triển';
$lang->story->stageList['testing']    = 'Đang test';
$lang->story->stageList['tested']     = 'Đã test';
$lang->story->stageList['verified']   = 'Đã chấp thuận';
$lang->story->stageList['released']   = 'Đã phát hành';
$lang->story->stageList['closed']     = 'Đã đóng';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = 'Đã hoàn thành';
$lang->story->reasonList['subdivided'] = 'Đã phân rã';
$lang->story->reasonList['duplicate']  = 'Đã nhân bản';
$lang->story->reasonList['postponed']  = 'Đã hoãn lại';
$lang->story->reasonList['willnotdo']  = "Sẽ không làm";
$lang->story->reasonList['cancel']     = 'Đã hủy';
$lang->story->reasonList['bydesign']   = 'Như thiết kế';
//$lang->story->reasonList['isbug']    = 'Bug!';

$lang->story->reviewResultList['']        = '';
$lang->story->reviewResultList['pass']    = 'Đạt';
$lang->story->reviewResultList['revert']  = 'Hoàn lại';
$lang->story->reviewResultList['clarify'] = 'Đã làm rõ';
$lang->story->reviewResultList['reject']  = 'Từ chối';

$lang->story->reviewList[0] = 'Không';
$lang->story->reviewList[1] = 'Có';

$lang->story->sourceList['']           = '';
$lang->story->sourceList['customer']   = 'Khách hàng';
$lang->story->sourceList['user']       = 'Người dùng';
$lang->story->sourceList['po']         = 'Sở hữu '.$lang->productCommon;
$lang->story->sourceList['market']     = 'Marketing';
$lang->story->sourceList['service']    = 'Dịch vụ khách hàng';
$lang->story->sourceList['operation']  = 'Vận hành';
$lang->story->sourceList['support']    = 'Hỗ trợ kỹ thuật';
$lang->story->sourceList['competitor'] = 'Đối thủ';
$lang->story->sourceList['partner']    = 'Đối tác';
$lang->story->sourceList['dev']        = 'Dev đội nhóm';
$lang->story->sourceList['tester']     = 'Đội QA';
$lang->story->sourceList['bug']        = 'Bug';
$lang->story->sourceList['forum']      = 'Forum';
$lang->story->sourceList['other']      = 'Khác';

$lang->story->priList[''] = '';
$lang->story->priList[1]  = '1';
$lang->story->priList[2]  = '2';
$lang->story->priList[3]  = '3';
$lang->story->priList[4]  = '4';

$lang->story->changeList = array();
$lang->story->changeList['no']  = 'Cancel';
$lang->story->changeList['yes'] = 'Confirm';

$lang->story->legendBasicInfo      = 'Thông tin cơ bản';
$lang->story->legendLifeTime       = 'Tổng quan';
$lang->story->legendRelated        = 'Thông tin liên quan';
$lang->story->legendMailto         = 'Mail tới';
$lang->story->legendAttach         = 'Files';
$lang->story->legendProjectAndTask = $lang->executionCommon . ' và nhiệm vụ';
$lang->story->legendBugs           = 'Bugs liên kết';
$lang->story->legendFromBug        = 'Từ Bug';
$lang->story->legendCases          = 'Tình huống liên kết';
$lang->story->legendLinkStories    = 'Câu chuyện liên kết';
$lang->story->legendChildStories   = 'Câu chuyện con';
$lang->story->legendSpec           = 'Mô tả';
$lang->story->legendVerify         = 'Chấp thuận';
$lang->story->legendMisc           = 'Khác';
$lang->story->legendInformation    = 'Story Information';

$lang->story->lblChange   = "Đã đổi";
$lang->story->lblReview   = 'Xét duyệt';
$lang->story->lblActivate = 'Kích hoạt';
$lang->story->lblClose    = 'Đóng';
$lang->story->lblTBC      = 'Nhiệm vụ/Bug/Tình huống';

$lang->story->checkAffection   = 'Ảnh hưởng';
$lang->story->affectedProjects = "{$lang->project->common}s/{$lang->execution->common}s";
$lang->story->affectedBugs     = 'Bugs';
$lang->story->affectedCases    = 'Tình huống';

$lang->story->specTemplate         = "Theo <một số người dùng>, Tôi muốn ra <một số mục tiêu> bởi đó là <một vài lý do>.";
$lang->story->needNotReview        = 'Không có xét duyệt được yêu cầu';
$lang->story->successSaved         = "Câu chuyện đã lưu lại!";
$lang->story->confirmDelete        = "Bạn có muốn xóa câu chuyện này?";
$lang->story->confirmRecall        = "Do you want to recall this story?";
$lang->story->errorEmptyChildStory = '『Câu chuyện đã phân rã』 không thể để trống.';
$lang->story->errorNotSubdivide    = "Nếu tình trạng này là chưa kích hoạt, hoặc giai đoạn này chưa đợi, hoặc một câu chuyện con, nó không thể bị chia nhỏ.";
$lang->story->errorEmptyReviewedBy = "『ReviewedBy』 không thể để trống.";
$lang->story->mustChooseResult     = 'Chọn kết quả';
$lang->story->mustChoosePreVersion = 'Chọn một phiên bản để chuyển thành.';
$lang->story->noStory              = 'Không có câu chuyện nào';
$lang->story->noRequirement        = 'Không có câu chuyện nào';
$lang->story->ignoreChangeStage    = 'The status of %s is Draft or Closed. This operation has been filtered.';
$lang->story->cannotDeleteParent   = "Không thể xóa {$lang->SRCommon} mẹ";
$lang->story->moveChildrenTips     = "Its Child {$lang->SRCommon} will be moved to the selected product when editing the linked product of Parent {$lang->SRCommon}.";
$lang->story->changeTips           = 'The story associated with the requirements to change, click "Cancel" ignore this change, click "Confirm" to change the story.';
$lang->story->estimateMustBeNumber = 'Estimate value must be number.';
$lang->story->estimateMustBePlus   = 'Estimated value cannot be negative';
$lang->story->confirmChangeBranch  = $lang->SRCommon . ' %s is linked to the plan of its linked branch. If the branch is edited, ' . $lang->SRCommon . ' will be removed from the plan of its linked branch. Do you want to continue edit ' . $lang->SRCommon . '?';
$lang->story->confirmChangePlan    = $lang->SRCommon . ' %s is linked to the branch of its plan. If the branch is edited, ' . $lang->SRCommon . ' will be removed from the plan. Do you want to continue edit branch ?';
$lang->story->errorDuplicateStory  = $lang->SRCommon . '%s not exist';
$lang->story->confirmRecallChange  = "After undo the change, the story content will revert to the version before the change. Are you sure you want to undo?";
$lang->story->confirmRecallReview  = "Are you sure you want to withdraw the review?";
$lang->story->noStoryToTask        = "Only the activated {$lang->SRCommon} can be converted into a task!";

$lang->story->form = new stdclass();
$lang->story->form->area     = 'Phạm vi';
$lang->story->form->desc     = 'Nó là câu chuyện gì? Điều gì chấp nhận được?';
$lang->story->form->resource = 'Ai sẽ phân bổ nguồn lực? Bao lâu để thực hiện nó?';
$lang->story->form->file     = 'Nếu bất kỳ tập tin mà liên kết tới một câu chuyện, Vui lòng click tại đây để tải nó lên.';

$lang->story->action = new stdclass();
$lang->story->action->reviewed              = array('main' => '$date, ghi nhận bởi <strong>$actor</strong>. Kết quả là <strong>$extra</strong>.', 'extra' => 'reviewResultList');
$lang->story->action->rejectreviewed        = array('main' => '$date, ghi nhận bởi <strong>$actor</strong>. Kết quả là <strong>$extra</strong>. Lý do là <strong>$reason</strong>', 'extra' => 'reviewResultList', 'reason' => 'reasonList');
$lang->story->action->recalled              = array('main' => '$date, recalled by <strong>$actor</strong>.');
$lang->story->action->closed                = array('main' => '$date, được đóng bởi <strong>$actor</strong>. Lý do là <strong>$extra</strong> $appendLink.', 'extra' => 'reasonList');
$lang->story->action->reviewpassed          = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>Pass</strong>.');
$lang->story->action->reviewrejected        = array('main' => '$date, closed by <strong>System</strong>. The reasion is <strong>Rejection</strong>.');
$lang->story->action->reviewclarified       = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>To Be Clarified</strong>. Please re-initiate the review after edit.');
$lang->story->action->reviewreverted        = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>Undo Change</strong>.');
$lang->story->action->linked2plan           = array('main' => '$date, liên kết bởi <strong>$actor</strong> tới kế hoạch <strong>$extra</strong>');
$lang->story->action->unlinkedfromplan      = array('main' => '$date, bị hủy bởi <strong>$actor</strong> từ kế hoạch <strong>$extra</strong>.');
$lang->story->action->linked2execution      = array('main' => '$date, liên kết bởi <strong>$actor</strong> tới ' . $lang->executionCommon . ' <strong>$extra</strong>.');
$lang->story->action->unlinkedfromexecution = array('main' => '$date, bị hủy bởi <strong>$actor</strong> từ ' . $lang->executionCommon . ' <strong>$extra</strong>.');
$lang->story->action->linked2kanban         = array('main' => '$date, linked by <strong>$actor</strong> to Kanban <strong>$extra</strong>.');
$lang->story->action->linked2project        = array('main' => '$date, liên kết bởi <strong>$actor</strong> tớ project <strong>$extra</strong>.');
$lang->story->action->unlinkedfromproject   = array('main' => '$date, bị hủy bởi <strong>$actor</strong> từ project <strong>$extra</strong>.');
$lang->story->action->linked2build          = array('main' => '$date, liên kết bởi <strong>$actor</strong> tới Bản dựng <strong>$extra</strong>');
$lang->story->action->unlinkedfrombuild     = array('main' => '$date, bị hủy bởi <strong>$actor</strong> từ bản dựng <strong>$extra</strong>.');
$lang->story->action->linked2release        = array('main' => '$date, liên kết bởi <strong>$actor</strong> tới Phát hành <strong>$extra</strong>');
$lang->story->action->unlinkedfromrelease   = array('main' => '$date, bị hủy bởi <strong>$actor</strong> từ Phát hành <strong>$extra</strong>.');
$lang->story->action->linked2revision       = array('main' => '$date, linked by <strong>$actor</strong> to Revision <strong>$extra</strong>');
$lang->story->action->unlinkedfromrevision  = array('main' => '$date, unlinked by <strong>$actor</strong> to Revision <strong>$extra</strong>');
$lang->story->action->linkrelatedstory      = array('main' => '$date, liên kết bởi <strong>$actor</strong> tới Câu chuyện <strong>$extra</strong>.');
$lang->story->action->subdividestory        = array('main' => '$date, được phân rã bởi <strong>$actor</strong> tới Câu chuyện <strong>$extra</strong>.');
$lang->story->action->unlinkrelatedstory    = array('main' => '$date, bị hủy bởi <strong>$actor</strong> từ Câu chuyện <strong>$extra</strong>.');
$lang->story->action->unlinkchildstory      = array('main' => '$date, bị hủy bởi <strong>$actor</strong> Câu chuyện được phân rã <strong>$extra</strong>.');
$lang->story->action->recalledchange        = array('main' => '$date, Undo changes by <strong>\$actor</strong>.');
$lang->story->action->synctwins             = array('main' => "\$date, the system judges that this story is adjusted synchronously due to the \$operate of twin story <strong>\$extra</strong>.", 'operate' => 'operateList');

/* Statistical statement. */
$lang->story->report = new stdclass();
$lang->story->report->common = 'Báo cáo';
$lang->story->report->select = 'Chọn loại báo cáo';
$lang->story->report->create = 'Tạo báo cáo';
$lang->story->report->value  = 'Báo cáo';

$lang->story->report->charts['storiesPerProduct']      = '' . $lang->productCommon . ' câu chuyện';
$lang->story->report->charts['storiesPerModule']       = 'Module câu chuyện';
$lang->story->report->charts['storiesPerSource']       = 'Nguồn câu chuyện';
$lang->story->report->charts['storiesPerPlan']         = 'kế hoạch';
$lang->story->report->charts['storiesPerStatus']       = 'tình trạng';
$lang->story->report->charts['storiesPerStage']        = 'Giai đoạn';
$lang->story->report->charts['storiesPerPri']          = 'ưu tiên';
$lang->story->report->charts['storiesPerEstimate']     = 'Dự tính';
$lang->story->report->charts['storiesPerOpenedBy']     = 'tạo bởi';
$lang->story->report->charts['storiesPerAssignedTo']   = 'giao cho';
$lang->story->report->charts['storiesPerClosedReason'] = 'Lý do đóng';
$lang->story->report->charts['storiesPerChange']       = 'Câu chuyện đã thay đổi';

$lang->story->report->options         = new stdclass();
$lang->story->report->options->graph  = new stdclass();
$lang->story->report->options->type   = 'pie';
$lang->story->report->options->width  = 500;
$lang->story->report->options->height = 140;

$lang->story->report->storiesPerProduct      = new stdclass();
$lang->story->report->storiesPerModule       = new stdclass();
$lang->story->report->storiesPerSource       = new stdclass();
$lang->story->report->storiesPerPlan         = new stdclass();
$lang->story->report->storiesPerStatus       = new stdclass();
$lang->story->report->storiesPerStage        = new stdclass();
$lang->story->report->storiesPerPri          = new stdclass();
$lang->story->report->storiesPerOpenedBy     = new stdclass();
$lang->story->report->storiesPerAssignedTo   = new stdclass();
$lang->story->report->storiesPerClosedReason = new stdclass();
$lang->story->report->storiesPerEstimate     = new stdclass();
$lang->story->report->storiesPerChange       = new stdclass();

$lang->story->report->storiesPerProduct->item      = $lang->productCommon;
$lang->story->report->storiesPerModule->item       = 'Module';
$lang->story->report->storiesPerSource->item       = 'Nguồn';
$lang->story->report->storiesPerPlan->item         = 'Kế hoạch';
$lang->story->report->storiesPerStatus->item       = 'Tình trạng';
$lang->story->report->storiesPerStage->item        = 'Giai đoạn';
$lang->story->report->storiesPerPri->item          = 'Ưu tiên';
$lang->story->report->storiesPerOpenedBy->item     = 'Mở bởi';
$lang->story->report->storiesPerAssignedTo->item   = 'Giao cho';
$lang->story->report->storiesPerClosedReason->item = 'Lý do';
$lang->story->report->storiesPerEstimate->item     = 'Dự tính';
$lang->story->report->storiesPerChange->item       = 'Đã thay đổi câu chuyện';

$lang->story->report->storiesPerProduct->graph      = new stdclass();
$lang->story->report->storiesPerModule->graph       = new stdclass();
$lang->story->report->storiesPerSource->graph       = new stdclass();
$lang->story->report->storiesPerPlan->graph         = new stdclass();
$lang->story->report->storiesPerStatus->graph       = new stdclass();
$lang->story->report->storiesPerStage->graph        = new stdclass();
$lang->story->report->storiesPerPri->graph          = new stdclass();
$lang->story->report->storiesPerOpenedBy->graph     = new stdclass();
$lang->story->report->storiesPerAssignedTo->graph   = new stdclass();
$lang->story->report->storiesPerClosedReason->graph = new stdclass();
$lang->story->report->storiesPerEstimate->graph     = new stdclass();
$lang->story->report->storiesPerChange->graph       = new stdclass();

$lang->story->report->storiesPerProduct->graph->xAxisName      = $lang->productCommon;
$lang->story->report->storiesPerModule->graph->xAxisName       = 'Module';
$lang->story->report->storiesPerSource->graph->xAxisName       = 'Nguồn';
$lang->story->report->storiesPerPlan->graph->xAxisName         = 'Kế hoạch';
$lang->story->report->storiesPerStatus->graph->xAxisName       = 'Tình trạng';
$lang->story->report->storiesPerStage->graph->xAxisName        = 'Giai đoạn';
$lang->story->report->storiesPerPri->graph->xAxisName          = 'Ưu tiên';
$lang->story->report->storiesPerOpenedBy->graph->xAxisName     = 'Người tạo';
$lang->story->report->storiesPerAssignedTo->graph->xAxisName   = 'Giao cho';
$lang->story->report->storiesPerClosedReason->graph->xAxisName = 'Lý do đóng';
$lang->story->report->storiesPerEstimate->graph->xAxisName     = 'Dự tính ';
$lang->story->report->storiesPerChange->graph->xAxisName       = 'Số lần thay đổi';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = 'Chọn xét duyệt bởi';

$lang->story->notice = new stdClass();
$lang->story->notice->closed           = 'Câu chuyện mà bạn chọn đã đóng!';
$lang->story->notice->reviewerNotEmpty = 'This requirement needs to be reviewed, and the reviewedby is required.';

$lang->story->convertToTask = new stdClass();
$lang->story->convertToTask->fieldList = array();
$lang->story->convertToTask->fieldList['module']     = 'Module';
$lang->story->convertToTask->fieldList['spec']       = "Description";
$lang->story->convertToTask->fieldList['pri']        = 'Priority';
$lang->story->convertToTask->fieldList['mailto']     = 'Mailto';
$lang->story->convertToTask->fieldList['assignedTo'] = 'AssignTo';

$lang->story->categoryList['feature']     = 'Feature';
$lang->story->categoryList['interface']   = 'Interface';
$lang->story->categoryList['performance'] = 'Performance';
$lang->story->categoryList['safe']        = 'Safe';
$lang->story->categoryList['experience']  = 'Experience';
$lang->story->categoryList['improve']     = 'Improve';
$lang->story->categoryList['other']       = 'Other';

$lang->story->changeTip = 'Only active can be changed.';

$lang->story->reviewTip = array();
$lang->story->reviewTip['active']      = 'The Story is already active,no review requirements.';
$lang->story->reviewTip['notReviewer'] = 'You are not the reviewer of this Story and cannot perform review operations.';
$lang->story->reviewTip['reviewed']    = 'Reviewed';

$lang->story->recallTip = array();
$lang->story->recallTip['actived'] = 'The Story has not initiated a review process and no undo action is required.';

$lang->story->subDivideTip = array();
$lang->story->subDivideTip['subStory']  = 'The Sub-stories cannot be subdivided.';
$lang->story->subDivideTip['notWait']   = 'The Story has been %s and cannot be subdivided.';
$lang->story->subDivideTip['notActive'] = 'The Story is not active and cannot be subdivided.';

$lang->story->featureBar['browse']['all']       = $lang->all;
$lang->story->featureBar['browse']['unclosed']  = $lang->story->unclosed;
$lang->story->featureBar['browse']['draft']     = $lang->story->statusList['draft'];
$lang->story->featureBar['browse']['reviewing'] = $lang->story->statusList['reviewing'];

$lang->story->operateList = array();
$lang->story->operateList['assigned']       = 'assigned';
$lang->story->operateList['closed']         = 'closed';
$lang->story->operateList['activated']      = 'activated';
$lang->story->operateList['changed']        = 'changed';
$lang->story->operateList['reviewed']       = 'reviewed';
$lang->story->operateList['edited']         = 'edited';
$lang->story->operateList['submitreview']   = 'submit review';
$lang->story->operateList['recalledchange'] = 'recalled change';
$lang->story->operateList['recalled']       = 'recalled review';
