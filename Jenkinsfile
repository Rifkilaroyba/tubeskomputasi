pipeline {
    agent any

    stages {

        stage('Environment Info') {
            steps {
                echo "Node: ${env.NODE_NAME}"
                echo "Workspace: ${env.WORKSPACE}"
                echo "OS: ${env.OS}"
            }
        }

        stage('Simple Command Test') {
            steps {
                bat 'echo HELLO FROM JENKINS'
            }
        }

        stage('List Files') {
            steps {
                bat 'dir || exit /b 0'
            }
        }

        stage('Finish') {
            steps {
                echo 'Pipeline finished safely'
            }
        }
    }

    post {
        always {
            echo 'DONE'
        }
    }
}
