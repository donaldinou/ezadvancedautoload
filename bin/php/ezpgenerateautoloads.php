#!/usr/bin/env php
<?php
/**
 * Script replacement in order to regenerate autoload with ezadvancedautoload extension.
 * Script base on ezpgenerateautoloads.php script for eZPublish community version 2012.2
 * 
 * @author Adrien Loyant <adrien.loyant@te-laval.fr>
 * 
 * @date 2012-03-01
 * @version 1.0.0
 * @since 1.0.0
 * @copyright GNU Public License v.2
 * 
 */

require ('autoload.php');

$cli = \eZCLI::instance();
$script = \eZScript::instance( array( 'description' => ( "Todo name \n" .
                "Todo desc\n" .
                "\n" .
                "./bin/php/ezcache.php --clear-tag=content" ),
                'use-session' => false,
                'use-modules' => false,
                'use-extensions' => true ) );
$script->startup();
$sys = eZSys::instance();
$script->initialize();

// Start requiring classes. Needed if it's first autoload run
if (!class_exists('extension\\ezadvancedautoload\\pv\\classes\\eZAutoloadGenerator')) {
    require_once('extension/ezadvancedautoload/private/classes/ezautoloadgenerator.php');
}
//

//}

// Setup console parameters
//{
$params = new ezcConsoleInput();

$helpOption = new ezcConsoleOption( 'h', 'help' );
$helpOption->mandatory = false;
$helpOption->shorthelp = 'Show help information';
$params->registerOption( $helpOption );

$targetOption = new ezcConsoleOption( 't', 'target', ezcConsoleInput::TYPE_STRING );
$targetOption->mandatory = false;
$targetOption->shorthelp = 'The directory to where the generated autoload file should be written.';
$params->registerOption( $targetOption );

$verboseOption = new ezcConsoleOption( 'v', 'verbose', ezcConsoleInput::TYPE_NONE );
$verboseOption->mandatory = false;
$verboseOption->shorthelp = 'Whether or not to display more information.';
$params->registerOption( $verboseOption );

$dryrunOption = new ezcConsoleOption( 'n', 'dry-run', ezcConsoleInput::TYPE_NONE );
$dryrunOption->mandatory = false;
$dryrunOption->shorthelp = 'Whether a new file autoload file should be written.';
$params->registerOption( $dryrunOption );

$kernelFilesOption = new ezcConsoleOption( 'k', 'kernel', ezcConsoleInput::TYPE_NONE );
$kernelFilesOption->mandatory = false;
$kernelFilesOption->shorthelp = 'If an autoload array for the kernel files should be generated.';
$params->registerOption( $kernelFilesOption );

$kernelOverrideOption = new ezcConsoleOption( 'o', 'kernel-override', ezcConsoleInput::TYPE_NONE );
$kernelOverrideOption->mandatory = false;
$kernelOverrideOption->shorthelp = 'If an autoload array for the kernel override files should be generated.';
$params->registerOption( $kernelOverrideOption );

$extensionFilesOption = new ezcConsoleOption( 'e', 'extension', ezcConsoleInput::TYPE_NONE );
$extensionFilesOption->mandatory = false;
$extensionFilesOption->shorthelp = 'If an autoload array for the extensions should be generated.';
$params->registerOption( $extensionFilesOption );

$testFilesOption = new ezcConsoleOption( 's', 'tests', ezcConsoleInput::TYPE_NONE );
$testFilesOption->mandatory = false;
$testFilesOption->shorthelp = 'If an autoload array for the tests should be generated.';
$params->registerOption( $testFilesOption );

$excludeDirsOption = new ezcConsoleOption( '', 'exclude', ezcConsoleInput::TYPE_STRING );
$excludeDirsOption->mandatory = false;
$excludeDirsOption->shorthelp = 'Folders to exclude from the class search.';
$params->registerOption( $excludeDirsOption );

$displayProgressOption = new ezcConsoleOption( 'p', 'progress', ezcConsoleInput::TYPE_NONE );
$displayProgressOption->mandatory = false;
$displayProgressOption->shorthelp = 'If progress output should be shown on the command-line.';
$params->registerOption( $displayProgressOption );

// Add an argument for which extension to search
$params->argumentDefinition = new ezcConsoleArguments();

$params->argumentDefinition[0] = new ezcConsoleArgument( 'extension' );
$params->argumentDefinition[0]->mandatory = false;
$params->argumentDefinition[0]->shorthelp = 'Extension to generate autoload files for.';
$params->argumentDefinition[0]->default = getcwd();

// Process console parameters
try 
{
    $params->process();
} 
catch ( ezcConsoleOptionException $e ) {
    echo( $e->getMessage(). PHP_EOL );
    echo( PHP_EOL );
    echo( $params->getHelpText( 'Autoload file generator.' ) . PHP_EOL);
    echo( PHP_EOL );
    exit();
}

if ( $helpOption->value === true )
{
    echo( $params->getHelpText( 'Autoload file generator.' ) . PHP_EOL);
    exit();
}
//}

$excludeDirs = array();
if ( $excludeDirsOption->value !== false ) {
    $excludeDirs = explode( ',', $excludeDirsOption->value );
}

$autoloadOptions = new ezpAutoloadGeneratorOptions();

$autoloadOptions->basePath = $params->argumentDefinition['extension']->value;
$autoloadOptions->searchKernelFiles = $kernelFilesOption->value;
$autoloadOptions->searchKernelOverride = $kernelOverrideOption->value;
$autoloadOptions->searchExtensionFiles = $extensionFilesOption->value;
$autoloadOptions->searchTestFiles = $testFilesOption->value;
$autoloadOptions->writeFiles = !$dryrunOption->value;
$autoloadOptions->displayProgress = $displayProgressOption->value;

if ( !empty( $targetOption->value ) )
{
    $autoloadOptions->outputDir = $targetOption->value;
}
$autoloadOptions->excludeDirs = $excludeDirs;

$autoloadGenerator = new \extension\ezadvancedautoload\pv\classes\eZAutoloadGenerator( $autoloadOptions );

if ( defined( 'EZP_AUTOLOAD_OUTPUT' ) )
{
    $outputClass = EZP_AUTOLOAD_OUTPUT;
    $autoloadCliOutput = new $outputClass();
}
else
{
    $autoloadCliOutput = new \ezpAutoloadCliOutput();
}

$autoloadGenerator->setOutputObject( $autoloadCliOutput );
$autoloadGenerator->setOutputCallback( array( $autoloadCliOutput, 'outputCli') );

try
{
    $autoloadGenerator->buildAutoloadArrays();
    $autoloadGenerator->buildPHPUnitConfigurationFile();

    // If we are showing progress output, let's print the list of warnings at
    // the end.
    if ( $displayProgressOption->value )
    {
        $warningMessages = $autoloadGenerator->getWarnings();
        foreach ( $warningMessages as $msg )
        {
            $autoloadCliOutput->outputCli( $msg, 'warning' );
        }
    }

    if ( $verboseOption->value )
    {
        $autoloadGenerator->printAutoloadArray();
    }
}
catch (Exception $e)
{
    echo( $e->getMessage() . PHP_EOL );
}

$script->shutdown();

?>
