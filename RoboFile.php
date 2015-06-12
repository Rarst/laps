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
	 * @return string
	 */
	private function versionGet() {

		$content = file_get_contents( __DIR__ . '/laps.php' );

		if ( false !== preg_match( '|^Version: (?P<version>.+)$|m', $content, $matches ) ) {
			return trim( $matches['version'] );
		}

		return '';
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

	/**
	 * Creates release zip
	 *
	 * @param string $version
	 */
	public function makeRelease( $version = '' ) {

		if ( empty( $version ) ) {
			$version = $this->versionGet();
		}

		$this->taskFileSystemStack()
		     ->mkdir( 'release' )
		     ->run();

		$this->taskCleanDir( 'release' )->run();

		$this->taskExec( 'composer' )
		     ->dir( __DIR__ . '/release' )
		     ->arg( 'create-project rarst/laps laps ' . $version )
		     ->arg( '--prefer-dist --no-dev' )
		     ->run();

		$this->taskExec( 'composer' )
		     ->dir( __DIR__ . '/release/laps' )
		     ->arg( 'dump-autoload --optimize' )
		     ->run();

		$zipFile    = "release/laps-{$version}.zip";
		$zipArchive = new ZipArchive();

		if ( ! $zipArchive->open( $zipFile, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE ) ) {
			die( "Failed to create archive\n" );
		}

		$finder = new Symfony\Component\Finder\Finder();
		$finder->files()->in( 'release/laps' )->ignoreDotFiles( false );

		/** @var \Symfony\Component\Finder\SplFileInfo $file */
		foreach ( $finder as $file ) {
			$zipArchive->addFile( $file->getRealPath(), 'laps/' . $file->getRelativePathname() );
		}

		if ( ! $zipArchive->status === ZIPARCHIVE::ER_OK ) {
			echo "Failed to write files to zip\n";
		}

		$zipArchive->close();

		$this->taskDeleteDir( 'release/laps' )->run();
	}
}
