<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractFileUploadModel
{

    protected $uploadedFile;
    protected $tmpFile;
    protected $uploadDir;

    abstract protected function setStoredFileName($filename);
    abstract protected function getStoredFileName();
    abstract protected function generateFileName();

    public function setUploadedFile(UploadedFile $file = null) {
        $this->uploadedFile = $file;
        $storedFileName = $this->getStoredFileName();
        if (isset($storedFileName)) {
            $this->tmpFile = $this->getStoredFileName();
        }
        $this->setStoredFileName('initial');
    }

    public function getUploadedFile() {
        return $this->uploadedFile;
    }

    public function getAbsolutePath($fileName) {
        return null === $fileName
            ? null
            : $this->getUploadRootDir() . '/' . $fileName;
    }

//    public function getWebPath($fileName) {
//        return null === $fileName
//            ? null
//            : $this->getUploadDir() . '/' . $fileName;
//    }

    public function getUploadRootDir() {
        return __DIR__ . '/../../../' . $this->getUploadDir();
    }

    public function getUploadDir() {
        return $this->uploadDir;
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->getUploadedFile()) {
            $this->setStoredFileName($this->generateFileName() . '.'. $this->getUploadedFile()->guessExtension());
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if(null === $this->getUploadedFile()) {
            return;
        }
        $this->getUploadedFile()->move($this->getUploadRootDir(), $this->getStoredFileName());
        if($this->tmpFile) {
            unlink($this->getUploadRootDir() . '/' . $this->tmpFile);
            $this->tmpFile = null;
        }
        $this->tmpFile = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        $file = $this->getAbsolutePath($this->getStoredFileName());
        if($file) {
            unlink($file);
        }
    }
}