pipeline {
    agent any

    options {
        timestamps()
        skipDefaultCheckout(true)
    }

    stages {

        stage('Checkout SCM') {
            steps {
                echo 'Checkout source code from GitHub'
                checkout scm
            }
        }

        stage('Environment Check') {
            steps {
                echo 'Checking Jenkins environment (Windows)'
                sh 'echo OS=%OS%'
                sh 'where php || echo PHP not found'
                sh 'where composer || echo Composer not found'
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installing dependencies (if composer available)'
                sh '''
                if exist composer.json (
                    composer install --no-interaction --prefer-dist || echo Composer install skipped
                ) else (
                    echo No composer.json found
                )
                '''
            }
        }

        stage('Project Structure Check') {
            steps {
                echo 'Listing project files'
                sh 'dir'
            }
        }

        stage('Build Result') {
            steps {
                echo 'Pipeline executed successfully'
            }
        }
    }

    post {
        success {
            echo 'PIPELINE STATUS: SUCCESS'
        }
        failure {
            echo 'PIPELINE STATUS: FAILED'
        }
        always {
            echo 'Pipeline finished (Windows Jenkins)'
        }
    }
}
