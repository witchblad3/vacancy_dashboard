<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\LoginController;
use App\Http\Controllers\MailController;


//регистрация и авторизация
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/authorize', [LoginController::class, 'authorizeHandler'])->name('auth');
Route::post('/reg', [LoginController::class, 'registerHandler'])->name('reg');

Route::group(['middleware'=>['check_auth']], function(){
    //новости
    Route::get('/news', [HomeController::class, 'index'])->name('main_page');
    Route::get('/news/add', [HomeController::class, 'addNews'])->name('add_news')->middleware('check_admin');
    Route::post('/add_news_item', [HomeController::class, 'addNewsItem'])->name('add_news_item');
    Route::post('/get_article/{article_id}', [HomeController::class, 'getArticleJson'])->name('get_article');

    //профиль
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/edit_profile', [ProfileController::class, 'editProfile'])->name('edit_profile');
    Route::post('/set_admin', [ProfileController::class, 'setAdmin'])->name('setadmin');
    Route::post('/disable_admin', [ProfileController::class, 'disableAdmin'])->name('disadmin');

    //вакансии
    Route::get('/vacancies/add', [VacancyController::class, 'addVacancyView'])->name('add_vacancy_view')
        ->middleware('is_jobgiver');
    Route::get('/vacancies', [VacancyController::class, 'showVacancies'])->name('vacancy_show');
    Route::post('/vacancies/add_item', [VacancyController::class, 'addVacancyToTable'])->name('add_vacancy_item')
        ->middleware('is_jobgiver');
    Route::post('/vacancies/delete/{id}', [VacancyController::class, 'deleteVacancy'])->name('delete_vac');
    Route::post('/get_vacancy/{id}', [VacancyController::class, 'getVacById'])->name('get_vac_item');
    Route::post('/make_fav', [VacancyController::class, 'makeFav'])->name('make_fav');
    Route::post('/make_unfav', [VacancyController::class, 'makeUnfav'])->name('make_unfav');
    Route::post('/update_vacancy', [VacancyController::class, 'updateVacancy'])->name('vacancy.update');
    Route::get('/favorites', [VacancyController::class, 'favList'])->name('favs');

    //почта
    Route::get('/mail/my', [MailController::class, 'index'])->name('my_mails');
    Route::get('/sendmail', [MailController::class, 'sendMailView'])->name('send_mail_view');
    Route::post('/send_mail', [MailController::class, 'sendMail'])->name('send_mail');
    Route::get('/mail/my/unread', [MailController::class, 'unreadMailList'])->name('unread_mail');
    Route::get('/mail/my/sent', [MailController::class, 'sentMailList'])->name('sent_mail');
    Route::post('/get_mail', [MailController::class, 'getMail'])->name('get_mail');
    Route::get('/mail/reply', [MailController::class, 'replyView']);
});
//TODO:: сделать ивент на получение нового письма
//Route::post('/fake_users', [LoginController::class, 'FakeUsers']);
