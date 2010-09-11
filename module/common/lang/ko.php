<?php
/**
 * The common simplified chinese file of ZenTaoMS.
 *
 * This file should be UTF-8 encoded.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id: en.php 1015 2010-08-03 05:50:35Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->arrow        = '»';
$lang->colon        = ': :';
$lang->at           = '에';
$lang->downArrow    = '↓';

$lang->zentaoMS     = 'ZenTaoPMS';
$lang->welcome      = "Welcome to『%s』{$lang->colon} {$lang->zentaoMS}";
$lang->myControl    = "계기판";
$lang->currentPos   = '현재';
$lang->logout       = '로그아웃';
$lang->login        = '로그인';
$lang->aboutZenTao  = '약';
$lang->todayIs      = 'Today is %s，';

$lang->reset        = '재설정';
$lang->edit         = '편집';
$lang->copy         = '복사';
$lang->delete       = '삭제';
$lang->close        = '가까운';
$lang->link         = '링크';
$lang->unlink       = '풀리다';
$lang->import       = '수입';
$lang->exportCSV    = 'CSV 내보내기';
$lang->setFileName  = '제발 입력 파일 이름 :';
$lang->activate     = '활성화';
$lang->save         = '저장';
$lang->confirm      = '확인';
$lang->preview      = '미리보기';
$lang->goback       = '뒤로';
$lang->showHelp     = '에 대한 도움말';
$lang->closeHelp    = '끄기 도움말';
$lang->go           = '어서!';

$lang->actions      = '행위';
$lang->comment      = '논평';
$lang->history      = '역사';
$lang->attatch      = 'Attatch';
$lang->reverse      = '(역방향)';
$lang->addFiles     = '파일 추가';
$lang->files        = '파일';

$lang->selectAll    = '모두 선택';
$lang->notFound     = '죄송합니다, 개체를 찾을 수 없습니다.';
$lang->showAll      = '+ + + + 전체보기';
$lang->hideClosed   = '- 휴관일 숨기기 -';

$lang->feature      = '기능';
$lang->year         = '년';
$lang->workingHour  = '시간';

$lang->idAB         = '신분증';
$lang->priAB        = '피';
$lang->statusAB     = '지위';
$lang->openedByAB   = '열린';
$lang->assignedToAB = '에';
$lang->typeAB       = '유형';

/* 主导航菜单。*/
$lang->menu->index   = '색인 |index|index';
$lang->menu->my      = '대시보드 |my|index';
$lang->menu->product = '제품 소개 |product|index';
$lang->menu->project = '프로젝트 |project|index';
$lang->menu->qa      = '품질 보증 |qa|index';
$lang->menu->doc     = '선생님 |doc|index';
//$lang->menu->forum   = '讨论 视图 |doc|index';
$lang->menu->company = '회사 소개 |company|index';
$lang->menu->admin   = '관리자 |admin|index';

/* 查询条中可以选择的对象列表。*/
$lang->searchObjects['bug']         = '곤충';
$lang->searchObjects['story']       = '이야기';
$lang->searchObjects['task']        = '태스크';
$lang->searchObjects['testcase']    = '케이스';
$lang->searchObjects['project']     = '프로젝트';
$lang->searchObjects['product']     = '제품';
$lang->searchObjects['user']        = '사용자';
$lang->searchObjects['build']       = '빌드';
$lang->searchObjects['release']     = '공개';
$lang->searchObjects['productplan'] = '계획';
$lang->searchObjects['testtask']    = '테스트 작업';
$lang->searchTips                   = '아이디는 여기에';

/* 首页菜单设置。*/
$lang->index->menu->product = '제품 소개 |product|browse';
$lang->index->menu->project = '프로젝트 |project|browse';

/* 我的地盘菜单设置。*/
$lang->my->menu->account  = '%s' . $lang->arrow;
$lang->my->menu->todo     = array('link' => '도도|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task     = '태스크|my|task|';
$lang->my->menu->bug      = '곤충|my|bug|';
$lang->my->menu->story    = '이야기|my|story|';
$lang->my->menu->project  = '프로젝트|my|project|';
$lang->my->menu->profile  = array('link' => '프로필|my|profile|', 'alias' => 'editprofile');
$lang->todo->menu         = $lang->my->menu;

/* 产品视图设置。*/
$lang->product->menu->list   = '%s';
$lang->product->menu->story  = array('link' => '이야기|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->plan   = array('link' => '계획|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release= array('link' => '공개|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap= '로드맵|product|roadmap|productID=%s';
$lang->product->menu->doc    = array('link' => '의사|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->view   = '정보|product|view|productID=%s';
$lang->product->menu->edit   = '편집|product|edit|productID=%s';
$lang->product->menu->module = '모듈|tree|browse|productID=%s&view=story';
$lang->product->menu->delete = array('link' => '삭제|product|delete|productID=%s', 'target' => 'hiddenwin');
$lang->product->menu->create = array('link' => '신제품|product|create', 'float' => 'right');
$lang->story->menu           = $lang->product->menu;
$lang->productplan->menu     = $lang->product->menu;
$lang->release->menu         = $lang->product->menu;

/* 项目视图菜单设置。*/
$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => '태스크|project|task|projectID=%s', 'subModule' => 'task', 'alias' => 'grouptask,importtask');
$lang->project->menu->story     = array('link' => '이야기|project|story|projectID=%s');
$lang->project->menu->bug       = '곤충|project|bug|projectID=%s';
$lang->project->menu->build     = array('link' => '빌드|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->burn      = '화상|project|burn|projectID=%s';
$lang->project->menu->team      = array('link' => '팀|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => '의사|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = '링크 제품|project|manageproducts|projectID=%s';
$lang->project->menu->linkstory = array('link' => '링크 기사|project|linkstory|projectID=%s');
$lang->project->menu->view      = '정보|project|view|projectID=%s';
$lang->project->menu->edit      = '편집|project|edit|projectID=%s';
$lang->project->menu->delete    = array('link' => '삭제|project|delete|projectID=%s', 'target' => 'hiddenwin');

$lang->project->menu->create = array('link' => '새 프로젝트|project|create', 'float' => 'right');
$lang->task->menu            = $lang->project->menu;
$lang->build->menu           = $lang->project->menu;

/* QA视图菜单设置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => '곤충|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,activate,report', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '케이스|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => '태스크|testtask|browse|productID=%s');

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => '곤충|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => '케이스|testcase|browse|productID=%s', 'alias' => 'view,create,edit', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => '태스크|testtask|browse|productID=%s');

$lang->testtask->menu->product  = '%s';
$lang->testtask->menu->bug      = array('link' => '곤충|bug|browse|productID=%s');
$lang->testtask->menu->testcase = array('link' => '케이스|testcase|browse|productID=%s');
$lang->testtask->menu->testtask = array('link' => '태스크|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases');

/* 文档视图菜单设置。*/
$lang->doc->menu->list    = '%s';
$lang->doc->menu->browse  = array('link' => '의사|doc|browse|libID=%s');
$lang->doc->menu->edit    = '수정 도서관|doc|editLib|libID=%s';
$lang->doc->menu->module  = '모듈|tree|browse|libID=%s&viewType=doc';
$lang->doc->menu->delete  = array('link' => '삭제 자료실|doc|deleteLib|libID=%s', 'target' => 'hiddenwin');
$lang->doc->menu->create  = array('link' => '새로운 도서관|doc|createLib', 'float' => 'right');

/* 组织结构视图菜单设置。*/
$lang->company->menu->name        = '%s' . $lang->arrow;
$lang->company->menu->browseUser  = array('link' => '사용자|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => '학부|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '그룹|group|browse', 'subModule' => 'group');
$lang->company->menu->edit        = array('link' => '회사|company|edit');
$lang->company->menu->addGroup    = array('link' => '그룹 추가|group|create', 'float' => 'right');
$lang->company->menu->addUser     = array('link' => '사용자 추가|user|create|dept=%s&from=company', 'subModule' => 'user', 'float' => 'right');
$lang->dept->menu            = $lang->company->menu;
$lang->group->menu           = $lang->company->menu;

/* 用户信息菜单设置。*/
$lang->user->menu->account  = '%s' . $lang->arrow;
$lang->user->menu->todo     = array('link' => '도도|user|todo|account=%s', 'subModule' => 'todo');
$lang->user->menu->task     = '태스크|user|task|account=%s';
$lang->user->menu->bug      = '버그 列表|user|bug|account=%s';
$lang->user->menu->project  = '프로젝트|user|project|account=%s';
$lang->user->menu->profile  = array('link' => '프로필|user|profile|account=%s', 'alias' => 'edit');
$lang->user->menu->browse   = array('link' => '用户管理|company|browse|', 'float' => 'right');

/* 后台管理菜单设置。*/
$lang->admin->menu->trashes = array('link' => '휴지통|action|trash', 'subModule' => 'action');
$lang->admin->menu->convert = array('link' => '수입|convert|index', 'subModule' => 'convert');
$lang->convert->menu        = $lang->admin->menu;
$lang->upgrade->menu        = $lang->admin->menu;
$lang->action->menu         = $lang->admin->menu;

/*菜单设置：分组设置。*/
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task        = 'project';
$lang->menugroup->build       = 'project';
$lang->menugroup->convert     = 'admin';
$lang->menugroup->upgrade     = 'admin';
$lang->menugroup->user        = 'company';
$lang->menugroup->group       = 'company';
$lang->menugroup->bug         = 'qa';
$lang->menugroup->testcase    = 'qa';
$lang->menugroup->testtask    = 'qa';
$lang->menugroup->people      = 'company';
$lang->menugroup->dept        = 'company';
$lang->menugroup->todo        = 'my';
$lang->menugroup->action      = 'admin';

/* 错误提示信息。*/
$lang->error->companyNotFound = "The domain %s does not exist.";
$lang->error->length          = array("『%s』length should be『%s』", "『%s』length should between『%s』and 『%s』.");
$lang->error->reg             = "『%s』should like『%s』";
$lang->error->unique          = "『%s』has『%s』already.";
$lang->error->notempty        = "『%s』can not be empty.";
$lang->error->equal           = "『%s』must be『%s』。";
$lang->error->int             = array("『%s』should be interger", "『%s』should between『%s-%s』.");
$lang->error->float           = "『%s』should be a interger or float.";
$lang->error->email           = "『%s』should be email.";
$lang->error->date            = "『%s』should be date";
$lang->error->account         = "『%s』should be a valid account.";
$lang->error->passwordsame    = "두 암호가 동일해야합니다";
$lang->error->passwordrule    = "비밀 번호한다 이상의 6 글자.";

/* 分页信息。*/
$lang->pager->noRecord  = "아니 아직 기록.";
$lang->pager->digest    = "<strong>%s</strong> records, <strong>%s</strong> per page, <strong>%s/%s</strong> ";
$lang->pager->first     = "처음으로";
$lang->pager->pre       = "이전";
$lang->pager->next      = "다음";
$lang->pager->last      = "마지막";
$lang->pager->locate    = "어서!";

$lang->zentaoSite     = "공식 사이트";
$lang->sponser        = "<a href='http://www.pujia.com' target='_blank'>PuJia Sponsed</a>";
$lang->zentaoKeywords = "오픈 소스 프로젝트 관리 시스템";
$lang->zentaoDESC     = "ZenTaoPMS가 열려있는 원천 프로젝트 관리 시스템입니다."; 

/* 时间格式设置。*/
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'F j, H:i');
define('DT_DATE1',     'Y-m-d');
define('DT_DATE2',     'Ymd');
define('DT_DATE3',     'F j, Y ');
define('DT_TIME1',     'H:i:s');
define('DT_TIME2',     'H:i');

/* 表情。*/
$lang->smilies->smile       = '미소';
$lang->smilies->sad         = '슬픈';
$lang->smilies->wink        = '눈짓';
$lang->smilies->tongue      = '혀';
$lang->smilies->shocked     = '충격';
$lang->smilies->eyesdown    = '실망한';
$lang->smilies->angry       = '성난';
$lang->smilies->cool        = '시원한';
$lang->smilies->indifferent = '무관 심한';
$lang->smilies->sick        = '아픈';
$lang->smilies->blush       = '얼굴을 붉히다';
$lang->smilies->angel       = '천사';
$lang->smilies->confused    = '혼란스러운';
$lang->smilies->cry         = '울어';
$lang->smilies->footinmouth = '비밀';
$lang->smilies->biggrin     = '웃다';
$lang->smilies->nerd        = '얼간이';
$lang->smilies->tired       = '피곤한';
$lang->smilies->rose        = '장미';
$lang->smilies->kiss        = '키스';
$lang->smilies->heart       = '사랑';
$lang->smilies->hug         = '포옹';
$lang->smilies->dog         = '개';
$lang->smilies->deadrose    = '죽은 로즈';
$lang->smilies->clock       = '시계';
$lang->smilies->brokenheart = '브로큰 하트';
$lang->smilies->coffee      = '커피';
$lang->smilies->computer    = '컴퓨터';
$lang->smilies->devil       = '악마';
$lang->smilies->thumbsup    = '엄지 손가락 위로';
$lang->smilies->thumbsdown  = 'Hhumb 다운';
$lang->smilies->mail        = '이메일';
