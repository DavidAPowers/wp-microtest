<?php
/*
	Plugin Name: WP Microtest
	Plugin URI: http://musicahermetica.com
	Description: Simple unit testing for WP
	Text Domain: wp-microtest
	Author: David A. Powers
	Version: 2.0.0
	Author URI: http://musicahermetica.com
	License: The MIT License (MIT)
*/

/*
	Copyright 2016 David A. Powers (email : cyborgk@gmail.com)

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if accessed directly!
}

class Wp_Microtest {
	public $html, $log, $args;
	protected $tests, $results_arr;
	public function __construct($args=[]) {
		$this->args = $args;
		$this->html = true;
		$this->results_arr = array();
		$this->tests = array();
		$this->setup();
		$this->run();
		$this->cleanup();
		$this->log_results();
	}
	public function add($method_string) {
		array_push($this->tests, $method_string);
	}
	public function log($str) {
		if ($this->html) {$this->log .= "$str<br>";}
		else $this->log .= "$str\n";
	}
	protected function setup() {
		$this->log("MicroTest parent class setup, override in child class.");
		$this->add('dummy_pass');
		$this->add('dummy_fail');
	}
	protected function cleanup() {
		$this->log("MicroTest parent class cleanup, override in child class.");
	}
	private function run() {
		$this->log("Running tests.");
		$result_str = "";
		foreach ($this->tests as $method) {
			array_push($this->results_arr,call_user_func_array(array($this, $method),array()));
		}
	}
	private function dummy_pass() {
		$this->log("Running dummy pass!");
		return true;
	}
	private function dummy_fail() {
		$this->log("Running dummy fail!");
		return false;
	}	
	private function log_results() {
		$count = 0;
		$passed = 0;
		foreach($this->results_arr as $a) {
			$count++;
			if($a===true) {$passed++;}
		}
		$this->log("Passed $passed of $count tests.");
	}
	static public function assertEqual($a,$b) {
		return ($a===$b);
	}
	static public function assertNotEqual($a,$b) {
		return ($a!==$b);
	}
}