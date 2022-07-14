# Metocean

## Project setup

Laravel 6.x, Vue.js 2.x

### Setup

1. Clone the project and enter the project root directory
   ```
   git clone https://github.com/guokeyalin/Metocean.git
   cd Metocean
   ```
2. Setup backend
   cd ./Metocean/backend
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate

   ### Parse and store data default file is metocean.txt, please add new file to Metocean/backend/resources and try

   php artisan system:store-parse-data {path?}

3. Setup frontend

   ### For development

   ```
   environments: Node.js >= V12
   Go to the frontend directory under the project root directory
   ```

   cd ./Metocean/frontend/src/utils

   ### modify the baseURL to our backend API url

   cd ./Metocean/frontend/
   npm install
   npm run serve

   Now start! [http://localhost:8080](http://localhost:8080)
