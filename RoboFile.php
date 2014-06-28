<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks {

	/**
	 * Watches for changes.
	 */
	public function watch() {

		$less_callback = function () {
			$this->less();
		};

		$this->taskWatch()
			->monitor( 'css/variables.less', $less_callback )
			->monitor( 'css/laps.less', $less_callback )
			->run();
	}

	/**
	 * Compiles stylesheet from less into css.
	 */
	public function less() {

		$this->taskExec( 'lessc css/laps.less css/laps.css' )->run();
	}
}