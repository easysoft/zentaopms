#!/bin/bash

finishTime=$(date '+%Y-%m-%d %H:%M:%S')

blankLine() {
  echo
}

outputFrame() {
  printf "$1"
}

outputLine() {
  echo "$1"
}

renderGitWebUrl() {
  if [ -z "$GIT_URL" ];then
    return
  fi

  echo $GIT_URL | sed -r -e 's/^.+@/https:\/\//' -e 's/\.git$//'
}

renderTagUrl() {
  GIT_WEB_BASE=$(renderGitWebUrl)
  if [ -n "$GIT_WEB_BASE" ];then
    echo "[${TAG_NAME}](${GIT_WEB_BASE}/src/tag/${TAG_NAME})"
  else
    echo ${TAG_NAME}
  fi
}

renderCommitUrl() {
  GIT_WEB_BASE=$(renderGitWebUrl)
  if [ -n "$GIT_WEB_BASE" ];then
    echo "[${GIT_COMMIT:0:7}](${GIT_WEB_BASE}/commit/${GIT_COMMIT}) ([提交历史](${GIT_WEB_BASE}/commits/tag/${TAG_NAME}))"
  else
    echo ${GIT_COMMIT:0:7}
  fi
}

outputLine "完成时间 **$finishTime**"
blankLine

outputLine "#### Git 信息"

outputLine "* 触发 Tag: $(renderTagUrl)"

outputLine "* CommitId: $(renderCommitUrl)"

outputLine "* 触发人: ${GIT_TAGGER_NAME}"

blankLine


# 触发人: qishiyao
subGroupPath=`echo $GIT_TAG_BUILD_GROUP | sed 's/\./\//'`

outputLine "#### 下载地址"

outputLine "##### 开源版"
outputFrame "[${PMS_VERSION} (中文)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/pmsPack/$subGroupPath/ZenTaoPMS/${PMS_VERSION}/)"
outputFrame "\t\t"
outputLine "[${PMS_VERSION} (English)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/pmsPack/$subGroupPath/ZenTaoALM/${PMS_VERSION}/)"

outputLine "##### 企业版"
outputFrame "[${BIZ_VERSION:3} (中文)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/bizPack/$subGroupPath/ZenTaoPMS/${BIZ_VERSION}/)"
outputFrame "\t\t"
outputLine "[${BIZ_VERSION:3} (English)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/bizPack/$subGroupPath/ZenTaoALM/${BIZ_VERSION}/)"


outputLine "##### 旗舰版"
outputFrame "[${MAX_VERSION:3} (中文)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/maxPack/$subGroupPath/ZenTaoPMS/${MAX_VERSION}/)"
outputFrame "\t\t"
outputLine "[${MAX_VERSION:3} (English)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/maxPack/$subGroupPath/ZenTaoALM/${MAX_VERSION}/)"


if [ -n "$IPD_VERSION" ];then
  outputLine "##### IPD版"
  outputFrame "[${IPD_VERSION:3} (中文)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/ipdPack/$subGroupPath/ZenTaoPMS/${IPD_VERSION}/)"
  outputFrame "\t\t"
  outputLine "[${IPD_VERSION:3} (English)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/ipdPack/$subGroupPath/ZenTaoALM/${IPD_VERSION}/)"
fi

outputLine "##### 历史版本"
outputLine "[查看历史版本](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/#browse/browse:${ARTIFACT_REPOSITORY})"
