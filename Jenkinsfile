pipeline {
  agent {
    kubernetes {
      inheritFrom "xuanim"
    }
  }

  options {
    skipDefaultCheckout()
  }

  environment {
    ZENTAO_RELEASE_PATH = "${WORKSPACE}/release"

    XUANXUAN_SRC_PATH = "${WORKSPACE}/xuansrc"
    
    SRC_ZDOO_PATH = "${WORKSPACE}/zdoo"
    SRC_ZDOOEXT_PATH = "${WORKSPACE}/zdooext"

    SRC_ZENTAOEXT_PATH = "${WORKSPACE}/zentaoext"
  }

  stages {

    stage("Package") {
      when {
        allOf {
          buildingTag()
        }
        beforeAgent true
      }

      agent {
        kubernetes {
          inheritFrom "zentao-package build-docker xuanim"
        }
      }

      environment {
        MIDDLE_IMAGE_REPO = "hub.qc.oop.cc/zentao-package-ext"
        MIDDLE_IMAGE_TAG = """${sh(
                            returnStdout: true,
                            script: 'date +%y%m%d%H%M-${BUILD_ID}'
        ).trim()}"""
      }

      stages {
        stage("Pull") {
          steps {
            checkout scm
          }
        }

        stage("PullExt") {
          environment {
            XUANVERSION = """${sh(
                      returnStdout: true,
                      script: 'jq -r .pkg.xuanxuan.gitVersion < dependency.json'
            ).trim()}"""
            ZENTAOEXT_VERSION = """${sh(
                      returnStdout: true,
                      script: 'jq -r .pkg.zentaoext.gitVersion < dependency.json'
            ).trim()}"""
            ZDOO_VERSION = """${sh(
                      returnStdout: true,
                      script: 'jq -r .pkg.zdoo.gitVersion < dependency.json'
            ).trim()}"""
            ZDOOEXT_VERSION = """${sh(
                      returnStdout: true,
                      script: 'jq -r .pkg.zdooext.gitVersion < dependency.json'
            ).trim()}"""
          }

          steps {
            dir('xuansrc') {
              checkout scmGit(branches: [[name: "${env.XUANVERSION}"]],
                userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/easycorp/xuanxuan.git']]
              )
            }

            dir('zdoo') {
              checkout scmGit(branches: [[name: "${env.ZDOO_VERSION}"]],
                extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/easycorp/zdoo.git']]
              )
            }

            dir('zdooext') {
              checkout scmGit(branches: [[name: "${env.ZDOOEXT_VERSION}"]],
                extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/easycorp/zdooext.git']]
              )
            }

            dir('zentaoext') {
              checkout scmGit(branches: [[name: "${env.ZENTAOEXT_VERSION}"]],
                extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/demo/zentaoext.git']]
              )
            }
          }
        }

        stage("Build") {
          environment {
            GIT_TAG_BUILD_TYPE = """${sh(
                              returnStdout: true,
                              script: 'misc/parse_tag.sh $TAG_NAME type'
            ).trim()}"""

            GIT_TAG_BUILD_GROUP = """${sh(
                              returnStdout: true,
                              script: 'misc/parse_tag.sh $TAG_NAME group'
            ).trim()}"""
          }

          stages {
            stage("make ciCommon") {
              steps {

                sh "echo $GIT_TAG_BUILD_TYPE/abc"
                sh "echo $GIT_TAG_BUILD_GROUP/def"
                withCredentials([gitUsernamePassword(credentialsId: 'git-zcorp-cc-jenkins-bot-http',gitToolName: 'git-tool')]) {
                  container('package') {
                    sh 'mkdir ${ZENTAO_RELEASE_PATH} && chown 1000:1000 ${ZENTAO_RELEASE_PATH}'
                    sh 'git config --global pull.ff only'
                    sh 'pwd && ls -l && make ciCommon'
                    sh 'ls -l ${ZENTAO_RELEASE_PATH}'
                  }
                }
              }
            }

            stage("encrypt ext code") {
              steps {
                container('package') {
                  sh 'cd $SRC_ZENTAOEXT_PATH && make'
                  sh 'cp ${ZENTAO_BUILD_PATH}/docker/Dockerfile.release.ext ./Dockerfile.release.ext'
                }
                container('docker') {
                  sh 'docker build --pull -t ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG} -f Dockerfile.release.ext ${ZENTAO_RELEASE_PATH}'
                  sh 'docker push ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}'
                }
              }
            }

          }
        }

        stage("Publish") {
          environment {
            PMS_VERSION = """${sh(
                                returnStdout: true,
                                script: 'cat ${SRC_ZENTAOEXT_PATH}/VERSION'
            ).trim()}"""
            BIZ_VERSION = """${sh(
                                returnStdout: true,
                                script: 'cat ${SRC_ZENTAOEXT_PATH}/BIZVERSION'
            ).trim()}"""
            MAX_VERSION = """${sh(
                                returnStdout: true,
                                script: 'cat ${SRC_ZENTAOEXT_PATH}/MAXVERSION'
            ).trim()}"""

            GIT_TAG_BUILD_TYPE = """${sh(
                              returnStdout: true,
                              script: 'misc/parse_tag.sh $TAG_NAME type'
            ).trim()}"""

            GIT_TAG_BUILD_GROUP = """${sh(
                              returnStdout: true,
                              script: 'misc/parse_tag.sh $TAG_NAME group'
            ).trim()}"""

            OUTPUT_PKG_PATH = "${ZENTAO_RELEASE_PATH}/output"

            ARTIFACT_REPOSITORY = """${sh(
                                returnStdout: true,
                                script: 'echo easycorp-snapshot'
                ).trim()}"""
            ARTIFACT_HOST = "nexus.qc.oop.cc"
            ARTIFACT_PROTOCOL = "https"
            ARTIFACT_CRED_ID = "nexus-jenkins"
          }

          stages {
            stage("Merge and Upload") {
              matrix {
                agent {
                  kubernetes {
                    containerTemplate {
                       name "package"
                       image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                       command "sleep"
                       args "99d"
                    }
                    inheritFrom "publish-qiniu"
                  }
                }
                options { skipDefaultCheckout() }

                axes {
                  axis {
                    name "ZLANG"
                    values "cn", "en"
                  }
                  axis {
                    name "PHPVERSION"
                    values "php5.4_5.6", "php7.0", "php7.1",  "php7.2_7.4", "php8.1", "k8s.php7.2_7.4", "k8s.php8.1"
                  }
                }
                excludes {
                  exclude {
                    axis {
                      name 'ZLANG'
                      values 'en'
                    }
                    axis {
                      name "PHPVERSION"
                      values "k8s.php7.2_7.4", "k8s.php8.1"
                    }
                  }
                }
                stages {
                  
                  stage("frame") {
                    environment {
                      ARTIFACT_NAME = """${sh(
                                returnStdout: true,
                                script: 'test ${ZLANG} = cn && echo -n zentaopms || echo -n zentaoalm'
                      ).trim()}"""
                    }

                    stages {
                      stage("package zip") {
                        steps{
                            echo "${env.ZLANG} <=> ${env.PHPVERSION}"
                            container('package') {
                              sh 'mkdir $ZENTAO_RELEASE_PATH'
                              sh '${ZENTAO_BUILD_PATH}/package.sh zip'
                              sh 'mkdir $OUTPUT_PKG_PATH'
                              sh 'env | grep GIT_TAG'
                            }
                        }
                      }

                      stage("upload zip") {
                        steps {
                          nexusArtifactUploader(
                            nexusVersion: 'nexus3',
                            protocol: env.ARTIFACT_PROTOCOL,
                            nexusUrl: env.ARTIFACT_HOST,
                            groupId: 'zentao.base' + '.' + env.GIT_TAG_BUILD_GROUP,
                            version: env.PMS_VERSION,
                            repository: env.ARTIFACT_REPOSITORY,
                            credentialsId: env.ARTIFACT_CRED_ID,
                            artifacts: [
                              [artifactId: env.ARTIFACT_NAME,
                               classifier: env.PHPVERSION,
                               file: env.ZENTAO_RELEASE_PATH + '/base.zip',
                               type: 'zip']
                            ]
                          )
                          nexusArtifactUploader(
                            nexusVersion: 'nexus3',
                            protocol: env.ARTIFACT_PROTOCOL,
                            nexusUrl: env.ARTIFACT_HOST,
                            groupId: 'zentao.biz' + '.' + env.GIT_TAG_BUILD_GROUP,
                            version: env.BIZ_VERSION,
                            repository: env.ARTIFACT_REPOSITORY,
                            credentialsId: env.ARTIFACT_CRED_ID,
                            artifacts: [
                              [artifactId: env.ARTIFACT_NAME,
                               classifier: env.PHPVERSION,
                               file: env.ZENTAO_RELEASE_PATH + '/biz.zip',
                               type: 'zip'],
                              [artifactId: env.ARTIFACT_NAME + '.update',
                               classifier: env.PHPVERSION,
                               file: env.ZENTAO_RELEASE_PATH + '/biz.update.zip',
                               type: 'zip'] 
                            ]
                          )
                          nexusArtifactUploader(
                            nexusVersion: 'nexus3',
                            protocol: env.ARTIFACT_PROTOCOL,
                            nexusUrl: env.ARTIFACT_HOST,
                            groupId: 'zentao.max' + '.' + env.GIT_TAG_BUILD_GROUP,
                            version: env.MAX_VERSION,
                            repository: env.ARTIFACT_REPOSITORY,
                            credentialsId: env.ARTIFACT_CRED_ID,
                            artifacts: [
                              [artifactId: env.ARTIFACT_NAME,
                               classifier: env.PHPVERSION,
                               file: env.ZENTAO_RELEASE_PATH + '/max.zip',
                               type: 'zip'],
                              [artifactId: env.ARTIFACT_NAME + '.update',
                               classifier: env.PHPVERSION,
                               file: env.ZENTAO_RELEASE_PATH + '/max.update.zip',
                               type: 'zip'] 
                            ]
                          )

                          sh 'mkdir ${OUTPUT_PKG_PATH}/${PMS_VERSION} ${OUTPUT_PKG_PATH}/${BIZ_VERSION} ${OUTPUT_PKG_PATH}/${MAX_VERSION}'
                          sh 'cp ${ZENTAO_RELEASE_PATH}/base.zip ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}-${PMS_VERSION}-${PHPVERSION}.zip'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/biz.zip ${OUTPUT_PKG_PATH}/${BIZ_VERSION}/${ARTIFACT_NAME}-${BIZ_VERSION}-${PHPVERSION}.zip'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/biz.update.zip ${OUTPUT_PKG_PATH}/${BIZ_VERSION}/${ARTIFACT_NAME}.update-${BIZ_VERSION}-${PHPVERSION}.zip'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/max.zip ${OUTPUT_PKG_PATH}/${MAX_VERSION}/${ARTIFACT_NAME}-${MAX_VERSION}-${PHPVERSION}.zip'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/max.update.zip ${OUTPUT_PKG_PATH}/${MAX_VERSION}/${ARTIFACT_NAME}.update-${MAX_VERSION}-${PHPVERSION}.zip'
                        }
                      } // End upload zip

                      stage("syspack") {
                        when {
                          buildingTag()
                          // environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                        }
                        steps{
                          sh 'env | grep GIT_TAG'
                            container('package') {
                              sh '${ZENTAO_BUILD_PATH}/package.sh deb'
                              sh '${ZENTAO_BUILD_PATH}/package.sh rpm'
                            }
                        }
                      }

                      stage("upload syspack") {
                        when {
                          environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                        }

                        steps {
                          nexusArtifactUploader(
                            nexusVersion: 'nexus3',
                            protocol: env.ARTIFACT_PROTOCOL,
                            nexusUrl: env.ARTIFACT_HOST,
                            groupId: 'zentao.pms' + '.' + env.GIT_TAG_BUILD_GROUP,
                            version: env.PMS_VERSION,
                            repository: env.ARTIFACT_REPOSITORY,
                            credentialsId: env.ARTIFACT_CRED_ID,
                            artifacts: [
                              [artifactId: env.ARTIFACT_NAME,
                               classifier: env.PHPVERSION + '-1.noarch',
                               file: env.ZENTAO_RELEASE_PATH + '/zentao.rpm',
                               type: 'rpm'],
                              [artifactId: env.ARTIFACT_NAME,
                               classifier: env.PHPVERSION + '.1.all',
                               file: env.ZENTAO_RELEASE_PATH + '/zentao.deb',
                               type: 'deb']
                            ]
                          )

                          sh 'mv ${ZENTAO_RELEASE_PATH}/zentao.rpm ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}-${PMS_VERSION}-${PHPVERSION}-1.noarch.rpm'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/zentao.deb ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}-${PMS_VERSION}-${PHPVERSION}.1.all.deb'
                        }
                      } // End upload syspack cn

                      stage("Upload Qiniu") {
                        when {
                          environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                        }

                        environment {
                          QINIU_BUCKET = "qisytest"
                          OBJECT_KEY_PREFIX = "zentao/"
                          QINIU_ACCESS_KEY = credentials('qiniu-upload-ak')
                          QINIU_SECRET_KEY = credentials('qiniu-upload-sk')
                        }

                        steps {
                          sh 'ls -l ${OUTPUT_PKG_PATH}'
                          container('qiniu') {
                            sh "qshell account $QINIU_ACCESS_KEY $QINIU_SECRET_KEY uploader"
                            sh "qshell qupload2 --bucket ${QINIU_BUCKET} --overwrite --src-dir ${OUTPUT_PKG_PATH} --key-prefix ${OBJECT_KEY_PREFIX}"
                          }
                        }
                      }

                    } // end stages
                  } // end stage frame

                } // End matrix stages
              } // End matrix

            } // End Merge and Upload Max

            stage("Notice") {
              steps {
                container('xuanimbot') {
                  sh 'git config --global --add safe.directory $(pwd)'
                  //sh '/usr/local/bin/xuanimbot  --users "qishiyao" --title "zentao build with tag ${TAG_NAME} success" --url ""${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/#browse/browse:${ARTIFACT_REPOSITORY}:zentao"" --content "zentaopms build success, click buttom below for browse artifacts" --debug --custom'
                  sh '/usr/local/bin/xuanimbot  --users "qishiyao" --groups "fced7fb3-0d48-449f-b408-ecae52a50f89" --title "zentao build with tag ${TAG_NAME} success" --url ""${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/#browse/browse:${ARTIFACT_REPOSITORY}:zentao"" --content "zentaopms build success, click buttom below for browse artifacts" --debug --custom'
                }
              }
            }
          }
        } // end publish
      }
    } // end package

  }

}


