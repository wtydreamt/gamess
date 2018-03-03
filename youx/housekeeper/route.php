<?php
use think\Route;
Route::rule('','index/index/index');
Route::rule('IndexList','index/index/IndexList');
Route::rule('AddGame','index/index/AddGame');
Route::rule('main','index/index/Main');
Route::rule('setIncDel','index/index/setIncDel');
Route::rule('Edit','index/index/Edit');
Route::rule('GameSave','index/index/GameSave');
Route::rule('GameDel','index/index/del');

Route::rule('login','index/Login/index');
Route::rule('LoginOut','index/Login/LoginOut');

Route::rule('menu','index/Classify/menu');
Route::rule('MenuSave','index/Classify/MenuSave');
Route::rule('Label','index/Classify/Label');
Route::rule('LabelAdd','index/Classify/LabelAdd');
Route::rule('LabelSave','index/Classify/LabelSave');
Route::rule('LabelInfo','index/Classify/LabelInfo');
Route::rule('LabelDel','index/Classify/del');
//Resources 资源管理
Route::rule('Resources','index/Resources/index');   

Route::rule('delFiel','index/Resources/delFiel'); 

Route::rule('uploads','index/Resources/uploads'); 







