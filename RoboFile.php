<?php

require __DIR__ . '/vendor/autoload.php';

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
				 $this->makeCss();
			 } )
			 ->monitor( 'js/source.js', function () {
				 $this->makeJs();
			 } )
			 ->run();
	}

	/**
	 * Compiles all assets.
	 */
	public function makeAll() {

		$this->makeCss();
		$this->makeJs();
		$this->makeMustache();
	}

	/**
	 * Compiles plugin's css file from less.
	 */
	public function makeCss() {

		$this->taskExec( 'lessc css/laps.less css/laps.css --source-map=css/laps.css.map' )->run();

		$this->taskMinify( 'css/laps.css' )
			 ->run();
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

		$this->taskMinify( 'js/laps.js' )
			 ->run();
	}

	/**
	 * Compiles plugin's mustache template
	 */
	public function makeMustache() {
		$dir = __DIR__ . '/views/cache';
		$this->_cleanDir( $dir );

		$mustache = new \Mustache_Engine(
			array(
				'loader' => new \Mustache_Loader_FilesystemLoader( __DIR__ . '/views' ),
				'cache'  => $dir,
			)
		);
		$mustache->loadTemplate( 'laps' );
	}

	/**
	 * Updates version in header and makes git version tag.
	 *
	 * @param string $version Version string.
	 */
	public function tag( $version ) {

		$this->versionSet( $version );

		$this->taskGitStack()
		     ->stopOnFail()
		     ->add( 'laps.php' )
		     ->commit( "Updated header version to {$version}" )
		     ->tag( $version )
		     ->run();
	}

	/**
	 * Sets the plugin version in header.
	 *
	 * @param string $version Version string.
	 */
	public function versionSet( $version ) {

		$this->taskReplaceInFile( 'laps.php' )
		     ->regex( '|^Version:.*$|m' )
		     ->to( 'Version: ' . $version )
		     ->run();
	}
}
