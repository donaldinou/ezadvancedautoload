<?php

$Module = array(
                'name' => 'ezadvancedautoload',
                'variable_params' => false,
                'ui_component_match' => 'module',
                'function' => array()
);

$ViewList = array();
$ViewList['extensions'] = array(
                'script' => 'extensions.php',
                'functions' => array( 'extensions' ),
                'params' => array(),
                'single_post_actions' => array(
                                'ActivateExtensionsButton' => 'ActivateExtensions',
                                'GenerateAutoloadArraysButton' => 'GenerateAutoloadArrays',
                                'GenerateAutoloadOverrideArraysButton' => 'GenerateAutoloadOverrideArrays'
                ),
                'unordered_params' => array(),
                'default_navigation_part' => 'ezsetupnavigationpart',
);

$FunctionList = array();
$FunctionList['extensions'] = array();
