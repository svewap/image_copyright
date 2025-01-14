<?php

namespace Walther\ImageCopyright\Controller;

use Walther\ImageCopyright\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class ImageCopyrightController
 *
 * @package Walther\ImageCopyright\Controller
 */
class ImageCopyrightController extends ActionController
{
    /**
     * @var array
     */
    protected array $cObjectData = [];

    /**
     * @var array
     */
    protected array $tableFieldConfiguration = [];

    /**
     * @var bool
     */
    protected bool $showEmpty = true;

    /**
     * @var array
     */
    protected array $extensions = [];

    /**
     * @var bool
     */
    protected bool $includeFileCollections = false;

    /**
     * @var array
     */
    protected array $tableFieldConfigurationForCollections = [];

    /**
     * @var \Walther\ImageCopyright\Resource\FileRepository
     */
    protected FileRepository $fileRepository;

    /**
     * injectFileRepository
     *
     * @param \Walther\ImageCopyright\Resource\FileRepository $fileRepository
     */
    public function injectFileRepository(FileRepository $fileRepository) : void
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * initializeAction
     *
     * @return void
     */
    public function initializeAction() : void
    {
        $this->cObjectData = $this->configurationManager->getContentObject()->data;

        // get table field configuration
        $tempTableFieldConfiguration = $this->settings['tableFieldConfiguration'];

        // check if extension is loaded
        foreach ($tempTableFieldConfiguration as $config) {
            if (!empty($config['extension']) && !empty($config['tableName']) && ExtensionManagementUtility::isLoaded($config['extension'])) {
                $this->tableFieldConfiguration [] = $config;
            }
        }

        $this->extensions = GeneralUtility::trimExplode(',', $this->settings['extensions'], true);
        $this->showEmpty = (bool)$this->settings['showEmpty'];
        $this->includeFileCollections = (bool)$this->settings['includeFileCollections'];

        if ($this->includeFileCollections === true) {
            // get table field configuration for file collections
            $tempTableFieldConfigurationForCollections = $this->settings['tableFieldConfigurationForCollections'];
            // check if extension is loaded
            foreach ($tempTableFieldConfigurationForCollections as $config) {
                if (!empty($config['extension']) && !empty($config['tableName']) && !empty($config['fieldName']) && ExtensionManagementUtility::isLoaded($config['extension'])) {
                    $this->tableFieldConfigurationForCollections [] = $config;
                }
            }
        }
    }

    /**
     * indexAction
     *
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function indexAction() : void
    {
        $this->view->assignMultiple([
            'images' => $this->fileRepository->findAllByRelation($this->tableFieldConfiguration, $this->tableFieldConfigurationForCollections, $this->extensions, $this->showEmpty, null)
        ]);
    }

    /**
     * indexOnPageAction
     *
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function indexOnPageAction() : void
    {
        $this->view->assignMultiple([
            'images' => $this->fileRepository->findAllByRelation($this->tableFieldConfiguration, $this->tableFieldConfigurationForCollections, $this->extensions, $this->showEmpty, $this->cObjectData['pid'])
        ]);
    }

    /**
     * @param bool $showEmpty
     *
     * @return ImageCopyrightController
     */
    public function setShowEmpty(bool $showEmpty) : ImageCopyrightController
    {
        $this->showEmpty = $showEmpty;
        return $this;
    }
}
