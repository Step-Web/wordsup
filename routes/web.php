<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DictonaryController;
use App\Http\Controllers\InfopageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\UserPhraseController;
use App\Http\Controllers\UserWordController;
use App\Http\Controllers\VocabularyController;
use App\Http\Controllers\WordGroupController;
use App\Http\Controllers\WordListController;
use cijic\phpMorphy\Morphy;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/tests', [AdminController::class, 'tests']);
//phpRoute::get('/user', [UserController::class, 'profile'])->name('user');
//Route::prefix('user')->group(function () {
   // Route::resource('/user', UserController::class);
//});


Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/infopage', [infopageController::class, 'index'])->name('infopage.index');
    Route::get('/infopage/create', [infopageController::class, 'create'])->name('infopage.create');
    Route::get('/infopage/edit/{id}', [infopageController::class, 'edit'])->name('infopage.edit');
    Route::post('/infopage', [infopageController::class, 'store'])->name('infopage.store');
    Route::put('/infopage', [infopageController::class, 'update'])->name('infopage.update');
    Route::delete('/infopage/{id}', [infopageController::class, 'destroy'])->name('infopage.destroy');
});



Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::resource('/page', PageController::class);
    Route::resource('/section', SectionController::class);
    Route::resource('/user', UserController::class);
    Route::resource('/wordgroup', WordGroupController::class);
    Route::resource('/wordlist', WordListController::class);
    Route::resource('/dictonary', DictonaryController::class);
    Route::resource('/sentence', SentenceController::class);
    Route::get('/processingWord/{lang}', [AdminController::class, 'processingWord'])->name('words.processing');
    Route::get('/processingSentence/{lang}', [AdminController::class, 'processingSentence'])->name('sentence.processing');
    Route::get('/setWordSentences/{lang}', [AdminController::class, 'setWordSentences'])->name('words.setWordSentences');
    Route::get('/createAudioPhrase', [AdminController::class, 'createAudioPhrase'])->name('sentence.createAudioPhrase');

    Route::get('/setflag/{tab}/{id}/{field}/{status}', [AdminController::class, 'setflag'])->name('admin.setflag');
    Route::post('/deleteImage/{tab}/{id}', [AdminController::class, 'deleteImage'])->name('admin.deleteImage');
});

Route::prefix('user')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('userhome');
    Route::get('/{id}/edit', [ProfileController::class, 'edit'])->name('useredit');
    Route::put('update', [ProfileController::class, 'update'])->name('userupdate');
    Route::post('deleteImage/{id}', [ProfileController::class, 'deleteImage'])->name('deleteImage');
    Route::delete('delete/{id}', [ProfileController::class, 'destroy'])->name('deleteUser');
    Route::get('confirmDestroyUser/{id}', [ProfileController::class, 'confirmDestroyUser'])->name('confirmDestroyUser');
    Route::get('/setStatistic', [\App\Http\Controllers\StatisticController::class, 'setStatistic'])->name('setStatistic');
    Route::get('/statistic/{user_id}', [\App\Http\Controllers\StatisticController::class, 'statistic'])->name('statistic');
    Route::get('/{id}', [ProfileController::class, 'show'])->name('usershow');
    Route::get('/clearErorrs/{word}/{type}', [\App\Http\Controllers\ProfileController::class, 'clearErorrs'])->name('clearErorrs');
});

Route::get('/usergroup/{id}', [UserGroupController::class, 'show'])->name('usergroup');
Route::middleware(['auth'])->prefix('words/')->group(function () {
   // Route::get('/group/{id}', [UserGroupController::class, 'show'])->withoutMiddleware(['auth']);
    Route::resource('/group', UserGroupController::class);
    Route::get('/group/{id}/confirmDeleteGroup', [UserGroupController::class, 'confirmDeleteGroup'])->name('group.confirmDeleteGroup');
    Route::get('/group/copy/{id}', [UserGroupController::class, 'copygroup'])->name('group.copygroup');
    Route::get('/userword/setProgressWord/{id}/{progress}', [UserWordController::class, 'setProgressWord'])->name('userword.setProgressWord');
    Route::post('/userword/resetProgress/', [UserWordController::class, 'resetProgress'])->name('userword.resetProgress');
    Route::post('/userword/deleteMultiple/', [UserWordController::class, 'deleteMultiple'])->name('userword.deleteMultiple');
    Route::post('/userword/transferWords/', [UserWordController::class, 'transferWords'])->name('userword.transferWords');
    Route::get('/userword/formTransfer/{act}/{group_id}', [UserWordController::class, 'formTransfer'])->name('userword.formTransfer');
    Route::get('/addword/{word}', [UserWordController::class, 'addword'])->name('addword');
    Route::post('/insertword', [UserWordController::class, 'insertword'])->name('insertword');
    Route::resource('userword', UserWordController::class);
});

Route::middleware(['auth'])->prefix('phrases')->group(function () {
   Route::resource('/group', UserGroupController::class);
    Route::get('/userphrase/setProgressWord/{id}/{progress}', [UserPhraseController::class, 'setProgressWord'])->name('userphrase.setProgressWord');
    Route::post('/userphrase/resetProgress/', [UserPhraseController::class, 'resetProgress'])->name('userphrase.resetProgress');
    Route::post('/userphrase/deleteMultiple/', [UserPhraseController::class, 'deleteMultiple'])->name('userphrase.deleteMultiple');
    Route::get('/addphrase/{id}', [UserPhraseController::class,'addphrase'])->name('addphrase');
    Route::post('/addPhraseByID/{id}', [UserPhraseController::class,'addPhraseByID'])->name('addPhraseByID');
    Route::post('/userphrase/transferWords/', [UserPhraseController::class, 'transferWords'])->name('userphrase.transferWords');
    Route::get('/userphrase/formTransfer/{act}/{group_id}', [UserPhraseController::class, 'formTransfer'])->name('userphrase.formTransfer');
    Route::get('/searchPhrases', [SentenceController::class,'searchPhrases'])->name('searchPhrases');
    Route::resource('/userphrase', UserPhraseController::class);
});


//Route::get('/morphy/{word}', [Morphy::class, 'info'])->name('morphy');
Route::get('/morphy/{word}', [UserWordController::class, 'findTranslate']);
Route::get('/morphy/moreform/{word}', [Morphy::class, 'findMoreForm']);


Route::prefix('learnword')->group(function () {
    Route::get('/settingModal', [\App\Http\Controllers\LearnWordController::class, 'settingModal'])->name('words.settingModal');
    Route::get('/levels', [\App\Http\Controllers\LearnWordController::class, 'levels'])->name('words.levels');
    Route::get('/random', [\App\Http\Controllers\LearnWordController::class, 'random'])->name('words.random');
    Route::post('/getWords/{type}', [\App\Http\Controllers\LearnWordController::class, 'getWords'])->name('getWords');
    Route::post('/getUserWords/{type}', [\App\Http\Controllers\LearnWordController::class, 'getUserWords'])->name('getUserWords');
    Route::post('/getCheckedWords/{type}', [\App\Http\Controllers\LearnWordController::class, 'getCheckedWords'])->name('learnword.getCheckedWords');
    Route::post('/saveUserStatistics', [\App\Http\Controllers\LearnWordController::class, 'saveUserStatistics'])->name('learnword.saveUserStatistics');
});

Route::prefix('learnphrase')->group(function () {
    Route::get('/settingModal', [\App\Http\Controllers\LearnPhraseController::class, 'settingModal'])->name('phrase.settingModal');
    Route::post('/getPhrases/{type}', [\App\Http\Controllers\LearnPhraseController::class, 'getPhrases'])->name('getPhrases');
    Route::post('/getCheckedWords/{type}', [\App\Http\Controllers\LearnPhraseController::class, 'getCheckedWords'])->name('getCheckedWords');
    Route::post('/saveUserStatistics', [\App\Http\Controllers\LearnPhraseController::class, 'saveUserStatistics'])->name('learnphrase.saveUserStatistics');
});


Route::prefix('test')->group(function () {
    Route::resource('/vocabulary', VocabularyController::class);

});
Route::prefix('sentence')->group(function () {
    Route::get('/', [SentenceController::class,'all'])->name('sentence.all');;
    Route::get('/word/{w}', [SentenceController::class,'search'])->name('sentence.search');

});


Route::get('/topuser/{period}', [\App\Http\Controllers\StatisticController::class, 'topuser'])->name('topuser');
Route::get('/dictonary/search', [DictonaryController::class, 'search'])->name('dictonary.search');
Route::get('/dictonary/translate', [DictonaryController::class, 'translate'])->name('dictonary.translate');
Route::get('/dictonary/addword/{word}', [DictonaryController::class, 'addword'])->name('dictonary.addword');
Route::get('/{type}/errors', [ProfileController::class, 'errors'])->name('userErrors');

Route::get('/', [infopageController::class, 'home'])->name('home');
Route::get('/wordlist/{url}', [WordListController::class, 'show'])->name('wordlist');
Route::get('/wordlist/', [WordListController::class, 'all'])->name('wordlists');
Route::get('/{section}/{page}.html', [PageController::class, 'show'])->name('page.show');
Route::get('/{url}.html', [infopageController::class, 'show'])->name('infopage');
Route::get('/{url}/', [SectionController::class, 'show'])->name('section.show');







