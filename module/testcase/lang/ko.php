<?php
/**
 * The testcase module Korean file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: en.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->testcase->id             = '신분증';
$lang->testcase->product        = '제품';
$lang->testcase->module         = '모듈';
$lang->testcase->story          = '이야기';
$lang->testcase->storyVersion   = '스토리 버전';
$lang->testcase->title          = '제목';
$lang->testcase->pri            = '우선';
$lang->testcase->type           = '유형';
$lang->testcase->status         = '지위';
$lang->testcase->steps          = '단계';
$lang->testcase->frequency      = '주파수';
$lang->testcase->order          = '주문';
$lang->testcase->openedBy       = '에 의해 개설';
$lang->testcase->openedDate     = '개설 날짜';
$lang->testcase->lastEditedBy   = '편집하여 최종';
$lang->testcase->lastEditedDate = '마지막으로 편집한 날짜';
$lang->testcase->version        = '버전';
$lang->testcase->result         = '결과';
$lang->testcase->real           = '현실';
$lang->testcase->keywords       = '키워드';
$lang->testcase->files          = '파일';
$lang->testcase->howRun         = '실행하는 방법';
$lang->testcase->scriptedBy     = '에 의해 스크립트';
$lang->testcase->scriptedDate   = '스크립트 날짜';
$lang->testcase->scriptedStatus = '스크립트 상태';
$lang->testcase->scriptedLocation = '스크립트 위치';
$lang->testcase->linkCase         = '관련 사례';
$lang->testcase->stage            = '무대';
$lang->testcase->lastEditedByAB   = '편집하여 최종';
$lang->testcase->lastEditedDateAB = '마지막으로 편집한 날짜';
$lang->testcase->allProduct       = 'All product';
$lang->case = $lang->testcase;  // 用于DAO检查时使用。因为case是系统关键字，所以无法定义该模块为case，只能使用testcase，但表还是使用的case。

$lang->testcase->stepID     = '신분증';
$lang->testcase->stepDesc   = '단계';
$lang->testcase->stepExpect = '기대';

$lang->testcase->common         = '케이스';
$lang->testcase->index          = "색인";
$lang->testcase->create         = "만들기";
$lang->testcase->delete         = "삭제";
$lang->testcase->view           = "정보";
$lang->testcase->edit           = "편집";
$lang->testcase->delete         = "삭제";
$lang->testcase->browse         = "찾아보기";
$lang->testcase->confirmStoryChange = '확인 이야기 변경';

$lang->testcase->deleteStep     = '엑스';
$lang->testcase->insertBefore   = '+ ↑';
$lang->testcase->insertAfter    = '+ ↓';

$lang->testcase->selectProduct  = '선택 제품';
$lang->testcase->byModule       = '모듈로';
$lang->testcase->assignToMe     = '나에게 할당';
$lang->testcase->openedByMe     = '내 옆에 개설';
$lang->testcase->allCases       = '모든 사건';
$lang->testcase->needConfirm    = '스토리가 변경됨';
$lang->testcase->moduleCases    = '모듈로';
$lang->testcase->bySearch       = '검색';

$lang->testcase->lblProductAndModule         = '제품 및 모듈';
$lang->testcase->lblTypeAndPri               = '유형 및 우선 순위';
$lang->testcase->lblSystemBrowserAndHardware = '운영 체제 &amp; 브라우저';
$lang->testcase->lblAssignAndMail            = '할당된 &amp; 흔한';
$lang->testcase->lblStory                    = '이야기';
$lang->testcase->lblLastEdited               = '최종 편집';

$lang->testcase->legendRelated     = '관련 정보';
$lang->testcase->legendBasicInfo   = '기본 정보';
$lang->testcase->legendMailto      = '흔한';
$lang->testcase->legendAttatch     = '파일';
$lang->testcase->legendLinkBugs    = '곤충';
$lang->testcase->legendOpenAndEdit = '열기 및 편집';
$lang->testcase->legendStoryAndTask= '이야기';
$lang->testcase->legendCases       = '관련 사례';
$lang->testcase->legendSteps       = '단계';
$lang->testcase->legendAction      = '행동';
$lang->testcase->legendHistory     = '역사';
$lang->testcase->legendComment     = '논평';
$lang->testcase->legendProduct     = '제품 및 모듈';
$lang->testcase->legendVersion     = '버전';

$lang->testcase->confirmDelete     = '이 사건을 삭제하시겠습니까?';

$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = '기능';
$lang->testcase->typeList['performance'] = '공연';
$lang->testcase->typeList['config']      = '설정';
$lang->testcase->typeList['install']     = '설치';
$lang->testcase->typeList['security']    = '보안';
$lang->testcase->typeList['other']       = '다른';

$lang->testcase->stageList['']            = '';
$lang->testcase->stageList['unittest']    = '단위 테스트';
$lang->testcase->stageList['feature']     = '기능 테스트';
$lang->testcase->stageList['intergrate']  = '통합 테스트';
$lang->testcase->stageList['system']      = '시스템 테스팅';
$lang->testcase->stageList['smoke']       = '흡연 테스트';
$lang->testcase->stageList['bvt']         = 'BVT 테스트';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['normal']      = '일반';
$lang->testcase->statusList['blocked']     = '차단된';
$lang->testcase->statusList['investigate'] = '조사';

$lang->testcase->resultList['n/a']     = 'N /';
$lang->testcase->resultList['pass']    = '패스';
$lang->testcase->resultList['fail']    = '실패';
$lang->testcase->resultList['blocked'] = '차단된';

$lang->testcase->buttonEdit     = '편집';
$lang->testcase->buttonToList   = '뒤로';
