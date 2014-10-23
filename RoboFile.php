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

		$this->taskWatch()
			 ->monitor( [ 'css/variables.less', 'css/laps.less' ], function () {
				 $this->makeLess();
			 } )
			 ->monitor( 'js/source.js', function () {
				 $this->makeJs();
			 } )
			 ->run();
	}

	/**
	 * Compiles plugin's css file from less.
	 */
	public function makeLess() {

		$this->taskExec( 'lessc css/laps.less css/laps.css' )->run();
	}

	/**
	 * Creates plugin's script file.
	 */
	public function makeJs() {

		$this->taskConcat( [
			'vendor/twbs/bootstrap/js/tooltip.js',
			'js/source.js',
		] )
			 ->to( 'js/laps.js' )
			 ->run();
	}
}