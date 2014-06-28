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

	/**
	 * Copies tooltip.js from Bootstrap sources and edits plugin name.
	 */
	public function tooltip() {

		$tooltip = 'js/tooltip.js';

		$this->taskWriteToFile( $tooltip )
			->textFromFile( 'vendor/twbs/bootstrap/js/tooltip.js' )
			->run();

		$replacements = array(
			'+function ($) {'       => '// modified to use lapstooltip as plugin name' . "\n\n" . '+function ($) {',
			'this.init(\'tooltip\'' => 'this.init(\'lapstooltip\'',
			'bs.tooltip'            => 'bs.lapstooltip',
			'$.fn.tooltip'          => '$.fn.lapstooltip',
		);

		foreach ( $replacements as $from => $to ) {
			$this->taskReplaceInFile( $tooltip )->from( $from )->to( $to )->run();
		}
	}
}