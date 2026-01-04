pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                echo 'Checkout source code berhasil'
            }
        }

        stage('Install Dependencies') {
            steps {
                sh 'php -v'
                sh 'composer install'
            }
        }

        stage('Test') {
            steps {
                echo 'Tahap test (belum ada unit test)'
            }
        }
    }
}
