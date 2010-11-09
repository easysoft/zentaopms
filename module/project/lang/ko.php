<?php
/**
 * The project module Korean file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: en.php 1014 2010-08-03 05:48:50Z wwccss $
 * @link        http://www.zentao.net
 */
/* 字段列表.*/
$lang->project->common       = '프로젝트';
$lang->project->id           = '신분증';
$lang->project->company      = '회사';
$lang->project->iscat        = '분류가요';
$lang->project->type         = '유형';
$lang->project->parent       = '부모의';
$lang->project->name         = '이름';
$lang->project->code         = '암호';
$lang->project->begin        = '시작';
$lang->project->end          = '끝';
$lang->project->status       = '지위';
$lang->project->statge       = '무대';
$lang->project->pri          = '우선';
$lang->project->desc         = '내림차순';
$lang->project->goal         = '골';
$lang->project->openedBy     = '에 의해 개설';
$lang->project->openedDate   = '개설 날짜';
$lang->project->closedBy     = '하여 종료';
$lang->project->closedDate   = '휴관일 날짜';
$lang->project->canceledBy   = '에 의해 취소';
$lang->project->canceledDate = '취소 날짜';
$lang->project->PO           = '제품 소유자';
$lang->project->PM           = '프로젝트 매니저';
$lang->project->QM           = '품질 보증 매니저';
$lang->project->acl          = '액세스 제한';
$lang->project->teamname     = '팀 이름';
$lang->project->products     = '제품 소개';
$lang->project->childProjects= '어린이 프로젝트';
$lang->project->whitelist    = '허용된 사이트 목록';

$lang->team->account     = '계정';
$lang->team->role        = '역할';
$lang->team->joinDate    = '가입 날짜';
$lang->team->workingHour = '작업 / 일';

/* 字段取值列表.*/
$lang->project->statusList['']      = '';
$lang->project->statusList['wait']  = '기다리는 것 같은데';
$lang->project->statusList['doing'] = '뭐';
$lang->project->statusList['done']  = '완료';

$lang->project->aclList['open']    = '기본값은 (프로젝트 모듈의 특권을 갖는)이 프로젝트를 방문할 수 있습니다';
$lang->project->aclList['private'] = '(단 개인 팀원들이 방문할 수 있습니다)';
$lang->project->aclList['custom']  = '허용된 사이트 목록 (팀 구성원, 누가 허용된 사이트 목록 grups 방문할 수)에 속해';

/* 方法列表.*/
$lang->project->index          = "색인";
$lang->project->task           = '태스크';
$lang->project->groupTask      = '그룹보기 작업';
$lang->project->story          = '이야기';
$lang->project->bug            = '곤충';
$lang->project->build          = '빌드';
$lang->project->burn           = 'Burndown 차트';
$lang->project->computeBurn    = '업데이트 burndown';
$lang->project->burnData       = 'Burndown 데이터';
$lang->project->team           = '팀';
$lang->project->doc            = '의사';
$lang->project->manageProducts = '링크 제품';
$lang->project->linkStory      = '링크 기사';
$lang->project->view           = "정보";
$lang->project->create         = "추가하기";
$lang->project->delete         = "삭제";
$lang->project->browse         = "찾아보기";
$lang->project->edit           = "편집";
$lang->project->manageMembers  = '관리 팀원';
$lang->project->unlinkMember   = '삭제 회원';
$lang->project->unlinkStory    = '삭제 이야기';
$lang->project->importTask     = '가져오기 작업을 취소할';
$lang->project->ajaxGetProducts= "API가 : 프로젝트의 제품을 얻을";

/* 分组浏览.*/
$lang->project->listTask            = '목록';
$lang->project->groupTaskByStory    = '이야기로';
$lang->project->groupTaskByStatus   = '상태';
$lang->project->groupTaskByPri      = '우선으로';
$lang->project->groupTaskByOwner    = '소유자';
$lang->project->groupTaskByEstimate = '견적 검색';
$lang->project->groupTaskByConsumed = '소비';
$lang->project->groupTaskByLeft     = '왼쪽으로';
$lang->project->groupTaskByType     = '유형';
$lang->project->groupTaskByDeadline = '인한 마감';
$lang->project->listTaskNeedConfrim = '스토리가 변경됨';

/* 页面提示.*/
$lang->project->selectProject  = "선택 프로젝트";
$lang->project->beginAndEnd    = '시작과 끝';
$lang->project->lblStats       = '통계';
$lang->project->stats          = 'Total estimate is『%s』hours,<br />confused『%s』hours<br />left『%s』hours';
$lang->project->oneLineStats   = "Project『%s』, code is『%s』, products is『%s』,begin from『%s』to 『%s』,total estimate『%s』hours,consumed『%s』hours,left『%s』hours.";
$lang->project->storySummary   = "Total 『%s』stories, estimate『%s』hours.";
$lang->project->wbs            = "WBS";
$lang->project->largeBurnChart = '대규모보기';

/* 交互提示.*/
$lang->project->confirmDelete         = 'Are you sure to delete project [%s]?';
$lang->project->confirmUnlinkMember   = '이 프로젝트에서 해당 사용자를 제거시겠습니까?';
$lang->project->confirmUnlinkStory    = '이 프로젝트에서 이야기를 제거시겠습니까?';
$lang->project->errorNoLinkedProducts = '아무 연결된 제품, 링크 페이지로 이동합니다.';
$lang->project->accessDenied          = '교통이 프로젝트를 부인했다.';

/* 统计.*/
$lang->project->charts->burn->graph->caption      = "Burndown 차트";
$lang->project->charts->burn->graph->xAxisName    = "날짜";
$lang->project->charts->burn->graph->yAxisName    = "1 시간";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
