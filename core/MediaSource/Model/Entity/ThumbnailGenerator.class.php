<?php

/**
 * Class ThumbnailGenerator
 *
 * @copyright   Comvation AG
 * @author      Robin Glauser <robin.glauser@comvation.com>
 * @package     contrexx
 * @subpackage  coremodule_mediabrowser
 */

namespace Cx\Core\MediaSource\Model\Entity;

use Cx\Core\Core\Controller\Cx;
use Cx\Model\Base\EntityBase;

/**
 * Class ThumbnailGenerator
 *
 * @copyright   Comvation AG
 * @author      Robin Glauser <robin.glauser@comvation.com>
 */
class ThumbnailGenerator extends EntityBase
{
    /**
     * List with thumbnails
     * @var array
     */
    protected $thumbnails;

    /**
     * @var Cx
     */
    protected $cx;

    /**
     * @var MediaSourceManager
     */
    protected $mediaSourceManager;

    /**
     * ThumbnailGenerator constructor.
     *
     * @param $cx Cx
     * @param $mediaSourceManager MediaSourceManager
     */
    public function __construct($cx,$mediaSourceManager) {
        $this->cx = $cx;
        $this->mediaSourceManager = $mediaSourceManager;
    }

    /**
     * Create all the thumbnails for a picture.
     *
     * @param string        $path          Path to the file. This can be a virtual path or a absolute path.
     * @param string        $fileNamePlain Plain file name without extension
     * @param string        $fileExtension Extension of the file
     * @param \ImageManager $imageManager
     *
     * <code>
     * <?php
     * \Cx\Core_Modules\MediaBrowser\Model\FileSystem::createThumbnail(
     *      'files/',
     *      'Django,
     *      'jpg',
     *      new ImageManager() // Please recycle the instance and don't create a new anonymous instance for each call.
     *                         // This is just a simple example.
     * );
     * ?>
     * </code>
     *
     * @param bool          $generateThumbnailByRatio
     * @param bool          $force
     *
     * @return array With an array of the generated thumbnails
     */
    public function createThumbnail(
        $path, $fileNamePlain, $fileExtension, \ImageManager $imageManager,
        $generateThumbnailByRatio = false, $force = false
    ) {
        $thumbnails = array();
        foreach (
            $this->getThumbnails() as $thumbnail
        ) {
            if ($force) {
                \Cx\Lib\FileSystem\FileSystem::delete_file(
                    MediaSourceManager::getAbsolutePath($path) . '/'
                    . $fileNamePlain . $thumbnail['value'] . '.'
                    . $fileExtension
                );
            } elseif (\Cx\Lib\FileSystem\FileSystem::exists(
                MediaSourceManager::getAbsolutePath($path) . '/'
                . $fileNamePlain . $thumbnail['value'] . '.' . $fileExtension
            )
            ) {
                $thumbnails[] = $fileNamePlain . $thumbnail['value'] . '.'
                    . $fileExtension;
                continue;
            }
            if ($imageManager->_createThumb(
                MediaSourceManager::getAbsolutePath($path) . '/',
                '',
                $fileNamePlain . '.' . $fileExtension,
                $thumbnail['size'],
                $thumbnail['quality'],
                $fileNamePlain . $thumbnail['value'] . '.' . $fileExtension,
                $generateThumbnailByRatio
            )
            ) {
                $thumbnails[] = $fileNamePlain . $thumbnail['value'] . '.'
                    . $fileExtension;
                continue;
            }
        }
        return $thumbnails;
    }

    /**
     * @param            $filePath
     * @param bool|false $force
     *
     * @return array
     */
    public function createThumbnailFromPath($filePath, $force = false) {
        if (!file_exists($filePath)) {
            $filePath = $this->cx->getWebsitePath() . $filePath;
        }
        $fileInfo = pathinfo($filePath);
        return $this->createThumbnail(
            $fileInfo['dirname'],
            preg_replace('/\.thumb_[a-z]+/i', '', $fileInfo['filename']),
            $fileInfo['extension'], new \ImageManager(), false, $force
        );
    }

    /**
     * Loads thumbnails from database
     */
    protected function loadThumbnails() {
        $pdo              = \Cx\Core\Core\Controller\Cx::instanciate()->getDb()
            ->getPdoConnection();
        $sth              = $pdo->query(
            'SELECT id, name, size, 100 as quality,
             CONCAT(".thumb_",name) as value FROM  `'
            . DBPREFIX
            . 'settings_thumbnail`'
        );
        $this->thumbnails = $sth->fetchAll();
    }

    /**
     * Get Thumbnails from database
     * @return array
     */
    public function getThumbnails() {
        if (!$this->thumbnails) {
            $this->loadThumbnails();
        }
        return $this->thumbnails;
    }


} 
