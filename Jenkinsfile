pipeline {
  agent {
    kubernetes {
      inheritFrom 'build-docker code-scan xuanim'
    }
  }

  environment {
    ZENTAO_VERSION = """${sh(
                            returnStdout: true,
                            script: 'cat VERSION'
    ).trim()}"""
    MYSQL_SERVER_HOST   = 'ci-mysql-0'
    MYSQL_ROOT_PASSWORD = 'pass4ci'

    MIDDLE_IMAGE_REPO = 'hub.qc.oop.cc/zentao-ztf'
    MIDDLE_IMAGE_TAG = """${sh(
                            returnStdout: true,
                            script: 'echo $BUILD_ID-${GIT_COMMIT}'
    ).trim()}"""
  }

  stages {
    stage('checkout code') {
      steps {
        echo 'checkout code success'
      }
    }

    stage('build quality') {
      parallel {
        stage('SonarQube') {
          steps {
            container('sonar') {
              withSonarQubeEnv('sonarqube') {
                catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                  sh 'git config --global --add safe.directory $(pwd)'
                  sh 'sonar-scanner -Dsonar.analysis.user=$(git show -s --format=%ae)'
                }
              }
            }
          }
          post {
            success {
              container('xuanimbot') {
                sh 'git config --global --add safe.directory $(pwd)'
                sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%ae)" --title "sonar scanner" --url "https://sonar.qc.oop.cc/dashboard?id=zentaopms&branch=${GIT_BRANCH}" --content "sonar scanner success" --debug --custom'
              }
            }
            failure {
              container('xuanimbot') {
                sh 'git config --global --add safe.directory $(pwd)'
                sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%ae)" --title "sonar scanner" --url "https://sonar.qc.oop.cc/dashboard?id=zentaopms&branch=${GIT_BRANCH}" --content "sonar scanner failure" --debug --custom'
              }
            }
          }
        }

        stage('Unit Test') {
          stages {
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
                    sh 'git config --global --add safe.directory $(pwd)'
                    sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%ae)" --title "build image" --url "${RUN_DISPLAY_URL}" --content "Build unit test image failure" --debug --custom'
                  }
                }
              }
            }

            stage('Run') {
                agent {
                kubernetes {
                  inheritFrom 'xuanim'
                  containerTemplate {
                    name 'zentao'
                    image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                    args 'sleep 99d'
                  }
                }
                }
                options { skipDefaultCheckout() }

                steps {
                  container('zentao') {
                    sh 'apachectl start ; initdb.php ; /apps/zentao/test/ztest batchInit ; /apps/zentao/test/ztest concurrency | tee /apps/zentao/test/${MIDDLE_IMAGE_TAG}.log ; parsehtml.php '
                    publishHTML (target : [allowMissing: false,alwaysLinkToLastBuild: false,keepAll: true,reportDir: 'coverage',reportFiles: 'index.html',reportName: 'UT Coverage Report',reportTitles: 'UT Coverage Report'])
                    sh 'pipeline-unittest.sh /apps/zentao/test/${MIDDLE_IMAGE_TAG}.log'
                  }
                }

              post {
                success {
                  container('xuanimbot') {
                    sh 'git config --global --add safe.directory /home/jenkins/agent/workspace/pangu_pangu_xuanimbot_master'
                    sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%ae)" --title "unittest" --url "${RUN_DISPLAY_URL}" --content "Unit test passed" --debug --custom'
                  }
                }
                failure {
                  container('xuanimbot') {
                    sh 'git config --global --add safe.directory $(pwd)'
                    sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%ae)" --title "unittest" --url "${RUN_DISPLAY_URL}" --content "Unit test failed" --debug --custom'
                  }
                }
              }
            }
          }
        }//End unittest
      }
    }
  }// End Root Stages
} // End pipeline

