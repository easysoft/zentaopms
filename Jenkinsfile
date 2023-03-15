pipeline {
  agent {
    kubernetes {
      inheritFrom "build-docker code-scan xuanim mysql-server"
    }
  }


  environment {
    ZENTAO_VERSION = """${sh(
                            returnStdout: true,
                            script: 'cat VERSION'
    ).trim()}"""
    MYSQL_SERVER_HOST = """${sh(
                            returnStdout: true,
                            script: 'hostname -I'
    ).trim()}"""
    MYSQL_ROOT_PASSWORD = 'pass4ci'

    MIDDLE_IMAGE_REPO = "hub.qc.oop.cc/zentao-ztf"
    MIDDLE_IMAGE_TAG = """${sh(
                            returnStdout: true,
                            script: 'echo $BUILD_ID'
    ).trim()}"""
  }


  stages {
     stage("拉取代码") {
       steps {
           echo "checkout code success"
       }
     }
     stage('sonar扫描') {
       parallel {
         stage('SonarQube') {
           steps {
             container('sonar') {
                 withSonarQubeEnv('sonarqube') {
                     catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                         sh 'git config --global --add safe.directory $(pwd)'
                         sh 'sonar-scanner -Dsonar.inclusions=$(git diff --name-only HEAD~1|tr "\\n" ",") -Dsonar.analysis.user=$(git show -s --format=%an)'
                    }
               }
             }
           }
           post {
             success {
                 container('xuanimbot') {
                     sh 'git config --global --add safe.directory /home/jenkins/agent/workspace/pangu_pangu_xuanimbot_master'
                     sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%cn)" --title "sonar scanner" --url "${RUN_DISPLAY_URL}" --content "sonar静态扫描通过" --debug --custom'
                 }
             }
             failure {
                 container('xuanimbot') {
                     sh 'git config --global --add safe.directory /home/jenkins/agent/workspace/pangu_pangu_xuanimbot_master'
                     sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%cn)" --title "sonar scanner" --url "${RUN_DISPLAY_URL}" --content "sonar静态扫描失败" --debug --custom'
                 }
            }
          }
        }
         stage('Build Image') {
           steps {
             container('docker') {
                 sh 'docker build --pull . -f Dockerfile.test --build-arg VERSION=${ZENTAO_VERSION} --build-arg MIRROR=true --build-arg MYSQL_HOST=${MYSQL_SERVER_HOST} --build-arg MYSQL_PASSWORD=${MYSQL_ROOT_PASSWORD} -t ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}'
                 sh 'docker push ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}'
             }
           }
           post {
             success {
                 echo 'build image success'
             }
             failure {
                 container('xuanimbot') {
                     sh 'git config --global --add safe.directory /home/jenkins/agent/workspace/pangu_pangu_xuanimbot_master'
                     sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%cn)" --title "build image" --url "${RUN_DISPLAY_URL}" --content "构建禅道单元测试镜像失败" --debug --custom'
                 }
            }
        }
      }
    }

     stage('Unit Test'){
      stages{
        stage('Unittest Init') {
              agent {
                  kubernetes {
                      inheritFrom "xuanim"
                          containerTemplate {
                              name "zentao"
                              image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                              command "sleep"
                              args "99d"
                          }
                  }
              }
              options { skipDefaultCheckout() }

              steps {
                  container('zentao') {
                      sh 'initdb.php ; /apps/zentao/test/ztest init'
                  }
              }
              post {
                success {
                    sh 'echo "stage unit init success"'
                }
                failure {
                 container('xuanimbot') {
                     sh 'git config --global --add safe.directory /home/jenkins/agent/workspace/pangu_pangu_xuanimbot_master'
                     sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%cn)" --title "unittest init" --url "${RUN_DISPLAY_URL}" --content "初始化单元测试数据库失败" --debug --custom'
                 }
                }
              }
          }

        stage('Run Test') {
          parallel {
            stage('UnitTest P1') {
              agent {
                kubernetes {
                  inheritFrom "xuanim"
                  containerTemplate {
                    name "zentao1"
                    image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                    command "sleep"
                    args "99d"
                  }
                }
              }
              options { skipDefaultCheckout() }

              steps {
                container('zentao1') {
                    sh 'initdb.php config'
                    sh '/apps/zentao/test/ztest extract ; /apps/zentao/test/ztest P1 | tee /apps/zentao/test/p1.log'
                    sh 'pipeline-unittest.sh /apps/zentao/test/p1.log'
                }
              }
            }
            stage('UnitTest P2') {
              agent {
                kubernetes {
                  inheritFrom "xuanim"
                  containerTemplate {
                    name "zentao2"
                    image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                    command "sleep"
                    args "99d"
                  }
                }
              }
              options { skipDefaultCheckout() }

              steps {
                container('zentao2') {
                    sh 'initdb.php config'
                    sh '/apps/zentao/test/ztest extract ; /apps/zentao/test/ztest P2 | tee /apps/zentao/test/p2.log'
                    sh 'pipeline-unittest.sh /apps/zentao/test/p2.log'
                }
              }

            }
            stage('UnitTest P3') {
              agent {
                kubernetes {
                  inheritFrom "xuanim"
                  containerTemplate {
                    name "zentao3"
                    image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                    command "sleep"
                    args "99d"
                  }
                }
              }
              options { skipDefaultCheckout() }

              steps {
                container('zentao3') {
                    sh 'initdb.php config'
                    sh '/apps/zentao/test/ztest extract ; /apps/zentao/test/ztest P3 | tee /apps/zentao/test/p3.log'
                    sh 'pipeline-unittest.sh /apps/zentao/test/p3.log'
                }
              }
            }
            stage('UnitTest P4') {
              agent {
                kubernetes {
                  inheritFrom "xuanim"
                  containerTemplate {
                    name "zentao4"
                    image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                    command "sleep"
                    args "99d"
                  }
                }
              }
              options { skipDefaultCheckout() }

              steps {
                container('zentao4') {
                    sh 'initdb.php config'
                    sh '/apps/zentao/test/ztest extract ; /apps/zentao/test/ztest P4 | tee /apps/zentao/test/p4.log '
                    sh 'pipeline-unittest.sh /apps/zentao/test/p4.log'
                }
              }

            }
            stage('UnitTest P5') {
              agent {
                kubernetes {
                  inheritFrom "xuanim"
                  containerTemplate {
                    name "zentao5"
                    image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                    command "sleep"
                    args "99d"
                  }
                }
              }
              options { skipDefaultCheckout() }

              steps {
                container('zentao5') {
                    sh 'initdb.php config'
                    sh '/apps/zentao/test/ztest extract ; /apps/zentao/test/ztest P5 | tee /apps/zentao/test/p5.log'
                    sh 'pipeline-unittest.sh /apps/zentao/test/p5.log'
                }
              }
            }
            stage('UnitTest P6') {
              agent {
                kubernetes {
                  inheritFrom "xuanim"
                  containerTemplate {
                    name "zentao6"
                    image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                    command "sleep"
                    args "99d"
                  }
                }
              }
              options { skipDefaultCheckout() }

              steps {
                container('zentao6') {
                    sh 'initdb.php config'
                    sh '/apps/zentao/test/ztest extract ; /apps/zentao/test/ztest P6 | tee /apps/zentao/test/p6.log'
                    sh 'pipeline-unittest.sh /apps/zentao/test/p6.log'
                }
              }

            }
            stage('UnitTest P7') {
              agent {
                kubernetes {
                  inheritFrom "xuanim"
                  containerTemplate {
                    name "zentao7"
                    image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                    command "sleep"
                    args "99d"
                  }
                }
              }
              options { skipDefaultCheckout() }

              steps {
                container('zentao7') {
                    sh 'initdb.php config'
                    sh '/apps/zentao/test/ztest extract ; /apps/zentao/test/ztest P7 | tee /apps/zentao/test/p7.log'
                    sh 'pipeline-unittest.sh /apps/zentao/test/p7.log'
                }
              }
            }
          } // End Parallel
       }
      }
      post{
          success{
              container('xuanimbot') {
                  sh 'git config --global --add safe.directory /home/jenkins/agent/workspace/pangu_pangu_xuanimbot_master'
                  sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%cn)" --title "unittest" --url "${RUN_DISPLAY_URL}" --content "单元测试通过" --debug --custom'
              }
          }
          failure{
              container('xuanimbot') {
                  sh 'git config --global --add safe.directory /home/jenkins/agent/workspace/pangu_pangu_xuanimbot_master'
                  sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%cn)" --title "unittest" --url "${RUN_DISPLAY_URL}" --content "单元测试通过" --debug --custom'
              }
          }
      }
    }//End unittest

  } // End Root Stages
} // End pipeline
