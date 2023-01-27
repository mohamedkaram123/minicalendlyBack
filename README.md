## Installation
 1. make clone from repo
 2. run server `php artisan serve`
 3. make migrate after setup database and put data in env `php artisan migrate`
 4. make seeds content data `php artisan db:seed`
 5. make integration with jwt [refrence link](https://blog.logrocket.com/implementing-jwt-authentication-laravel-9/) help you
 6. make integration with google calender app [refrence link](https://www.youtube.com/watch?v=pOdgE22D-1U)
    - open google cloud `https://console.cloud.google.com/home/dashboard`
    - make new project
    - enable calender app
    - open your project
    - open api & service
    - open  OAuth consent screen  -> external --> add data required
    - after created open credentials
    - and create credentials --> api key 
    - then create `Oauth client id` and put origin in -->`Authorized JavaScript origins` and `Authorized redirect URIs` --> download file      `oauth_client_id.json` put it in path `storage\app\google-calendar` 
 7. make integration with zoom
 8. file `.env.example` has all keys reqiured in bottom
 
