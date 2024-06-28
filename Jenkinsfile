properties([
  pipelineTriggers([pollSCM('* * * * *')])
])

def FAILED_STAGE

pipeline {
  agent any

  // Environment variables
  environment {
    def GIT_HASH = sh(returnStdout: true, script: 'git log -1 --pretty=format:"%h"').trim()
    DOCKERHUB_CREDENTIALS = credentials('dockerhub-apergu')
    SSH = credentials('ssh-apergu')
  }

  stages {
    stage("PREPARE") {
      steps {
        script {
          FAILED_STAGE = env.STAGE_NAME
          echo "PREPARE"
        }

        // Install Script
        sh label: 'Preparation Script', script:
        """
        composer update --ignore-platform-reqs
        """
      }
    }

    stage("BUILD") {
      steps {
        script {
          FAILED_STAGE = env.STAGE_NAME
          echo "BUILD"

          // Run Docker Build with sudo
          def command = """
          echo '${SSH}' | sudo -S su
          echo '${SSH}' | sudo -S docker build -t apergudev/sompo-zd:latest .
          """

          // Execute the command
          sh label: 'Run Docker Build', script: command
        }
      }
    }

    stage("RELEASE") {
      steps {
        script {
          FAILED_STAGE = env.STAGE_NAME
          echo "RELEASE"
        }

        sh label: 'STEP RELEASE', script:
        """
        echo $DOCKERHUB_CREDENTIALS_PSW | docker login -u $DOCKERHUB_CREDENTIALS_USR --password-stdin
        docker push apergudev/sompo-zd:latest
        """
      }
    }
  }
}
