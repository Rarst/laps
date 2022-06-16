<?php
declare( strict_types=1 );

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
		     ->monitor( [ 'public/css/variables.less', 'public/css/laps.less' ], function () {
			     $this->makeCss();
		     } )
		     ->monitor( 'public/js/source.js', function () {
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

		$this->taskExec( 'lessc public/css/laps.less public/css/laps.css --source-map=public/css/laps.css.map' )->run();

		$this->taskMinify( 'public/css/laps.css' )
		     ->run();
	}

	/**
	 * Creates plugin's script file.
	 */
	public function makeJs() {

		$this->taskConcat( [
			'vendor/twbs/bootstrap/js/tooltip.js',
			'public/js/source.js',
		] )
		     ->to( 'public/js/laps.js' )
		     ->run();

		$this->taskMinify( 'public/js/laps.js' )
		     ->run();
	}

	/**
	 * Compiles plugin's mustache template
	 */
	public function makeMustache() {
		$dir = __DIR__ . '/src/mustache/cache';
		$this->_cleanDir( $dir );
		$mustache = new \Mustache_Engine(
			[
				'loader' => new \Mustache_Loader_FilesystemLoader( __DIR__ . '/src/mustache' ),
				'cache'  => $dir,
			]
		);
		$mustache->loadTemplate( 'laps' );
	}

	/**
	 * Creates release zip
	 *
	 * @param string $php PHP version to target.
	 */
	public function makeArchive( $php = '7.2.5' ): void {

		$composer = json_decode( file_get_contents( __DIR__ . '/composer.json' ) );
		$package  = $composer->name;

		[ $vendor, $name ] = explode( '/', $package );

		if ( empty( $vendor ) || empty( $name ) ) {
			return;
		}

		$this->_mkdir( 'release' );

		$version = $this->latestTag();

		$this->taskExec( "composer create-project {$package} {$name} {$version}" )
		     ->dir( __DIR__ . '/release' )
		     ->arg( '--prefer-dist' )
		     ->arg( '--no-install' )
		     ->run();

		if ( $php ) {
			$this->taskExec( "composer config platform.php {$php}" )
			     ->dir( __DIR__ . "/release/{$name}" )
			     ->run();
		}

		$this->taskExec( 'composer remove composer/installers --no-update' )
		     ->dir( __DIR__ . "/release/{$name}" )
		     ->run();

		$this->taskExec( 'composer update --no-dev --optimize-autoloader' )
		     ->dir( __DIR__ . "/release/{$name}" )
		     ->run();

		$zipFile = "release/{$name}.zip";

		$this->_remove( $zipFile );

		$this->taskPack( $zipFile )
		     ->addDir( $name, "release/{$name}" )
		     ->run();

		$this->_deleteDir( "release/{$name}" );
	}

	/**
	 * Updates version in header and makes git version tag.
	 *
	 * @param string $version Version string.
	 */
	public function tag( $version ) {

		$this->makeVersion( $version );

		$this->taskGitStack()
		     ->stopOnFail()
		     ->add( 'CHANGELOG.md' )
		     ->add( 'laps.php' )
		     ->commit( "Released version $version" )
		     ->tag( $version )
		     ->run();
	}

	/**
	 * Sets the plugin version in header.
	 *
	 * @param string $version Version string.
	 */
	public function makeVersion( $version ) {

		$this->taskReplaceInFile( 'CHANGELOG.md' )
		     ->from( '## Unreleased' )
		     ->to( '## Unreleased' . PHP_EOL . PHP_EOL . "## $version - " . date_format( date_create(), 'Y-m-d' ) )
		     ->run();

		$this->taskReplaceInFile( 'laps.php' )
		     ->regex( '|^Version:.*$|m' )
		     ->to( 'Version: ' . $version )
		     ->run();
	}

	public function upload() {
		$version = $this->latestTag();
		$zipFile = __DIR__ . '/release/laps.zip';

		$this->makeArchive();
		$this->_exec("gh release create $version $zipFile");
		$this->_remove( $zipFile );
	}

	private function latestTag(): string {
		$commit = escapeshellarg( exec( 'git rev-list --tags --max-count=1' ) );

		return escapeshellarg( exec( "git describe --tags {$commit}" ) );
	}
}
