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

outputLine "构建日期 **$finishTime**"
blankLine

outputLine "#### Git 信息"

outputLine "* 触发 Tag: $TAG_NAME"

outputLine "* CommitId: ${GIT_COMMIT:0:7}"

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
outputLine "[${BIZ_VERSION:3} 升级包 (中文)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/bizPack/$subGroupPath/ZenTaoPMS.update/${BIZ_VERSION}/)"

outputFrame "[${BIZ_VERSION:3} (English)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/bizPack/$subGroupPath/ZenTaoALM/${BIZ_VERSION}/)"
outputFrame "\t"
outputLine "[${BIZ_VERSION:3} 升级包 (English)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/bizPack/$subGroupPath/ZenTaoALM.update/${BIZ_VERSION}/)"

outputLine "##### 旗舰版"

outputFrame "[${MAX_VERSION:3} (中文)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/maxPack/$subGroupPath/ZenTaoPMS/${MAX_VERSION}/)"
outputFrame "\t\t"
outputLine "[${MAX_VERSION:3} 升级包 (中文)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/maxPack/$subGroupPath/ZenTaoPMS.update/${MAX_VERSION}/)"

outputFrame "[${MAX_VERSION:3} (English)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/maxPack/$subGroupPath/ZenTaoALM/${MAX_VERSION}/)"
outputFrame "\t"
outputLine "[${MAX_VERSION:3} 升级包 (English)](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/service/rest/repository/browse/${ARTIFACT_REPOSITORY}/zentao/maxPack/$subGroupPath/ZenTaoALM.update/${MAX_VERSION}/)"

outputLine "##### 历史版本"
outputLine "[查看历史版本](${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/#browse/browse:easycorp-snapshot)"
