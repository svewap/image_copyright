<?php

defined('TYPO3_MODE') || die('Access denied.');

(static function($extName) {

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin (
        $extensionName = 'Fnn.ImageCopyright',
        $pluginName = 'ImageCopyright',
        $controllerActions = [
            'ImageCopyright' => 'index, indexOnPage, first, firstOnPage'
        ],
        $nonCacheableControllerActions = [
            'ImageCopyright' => 'index, indexOnPage, first, firstOnPage'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import \'EXT:' . $extName . '/Configuration/TSconfig/contentElementWizard.typoscript\''
    );

    $templateIcons = [
        'tx-imagecopyright' => 'image_copyright.svg',
    ];

    $templateIconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

    foreach ($templateIcons as $identifier => $path) {
        $templateIconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:' . $extName . '/Resources/Public/Images/Backend/' . $path]
        );
    }

})('image_copyright');