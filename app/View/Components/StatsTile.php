<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatsTile extends Component {

	public $title;
	public $value;
	public $unit;
	public $percent;

	public function __construct($title, $value, $unit = null, $percent = null) {
		$this->title = $title;
		$this->value = $value;
		$this->unit = $unit;
		$this->percent = $percent;
	}

	public function render() {
		return view('components.stats-tile');
	}
}
