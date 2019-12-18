<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class StorageLink extends Controller
{
//    TODO : delete it once used
 public function makeStorageLink(){
     Artisan::call('storage:link');
 }
}
