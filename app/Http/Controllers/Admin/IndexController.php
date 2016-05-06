<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class IndexController extends Controller {

	public function getIndex() {
		return view('admin.index');
	}

	public function getHelp() {
		return view('admin.help');
	}
}