<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 15/02/2017
 * Time: 12:43
 */
namespace AppBundle\EventListener;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use AppBundle\Entity\Family;
use AppBundle\Entity\PaymentMethod;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\FileUploader;

class BrochureUploadListener
{
    private $uploader;
    private $img_path;

    /**
     * BrochureUploadListener constructor.
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader, $img_path)
    {
        $this->uploader = $uploader;
        $this->img_path = $img_path;

    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $this->uploadFile($entity);
        $em->flush();

    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {

    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Article) {
            try {
                $oldPicture = $args->getOldValue('urlPicture');
                $newPicture = $args->getNewValue('urlPicture');


                if ($newPicture == null) {
                    $entity->setUrlPicture($oldPicture);

                } else {
                    /*if(!strpos($oldPicture,'images/') && $oldPicture!=null && file_exists ($this->img_path.'/p/'.$oldPicture))
                        unlink($this->img_path.'/p/'.$oldPicture);*/
                    $this->uploadFile($entity);
                }
            } catch (\Exception $ex) {

            }

        }
        if ($entity instanceof Category) {
            try {
            $oldPicture = $args->getOldValue('bgPicture');
            $newPicture = $args->getNewValue('bgPicture');


            if ($newPicture == null) {
                $entity->setBgPicture($oldPicture);

            } else {
                if ($oldPicture != null && file_exists($this->img_path . '/c/' . $oldPicture))
                    unlink($this->img_path . '/c/' . $oldPicture);
                $this->uploadFile($entity);
            }
            } catch (\Exception $ex) {

            }
        }
        if ($entity instanceof Family) {
            $oldPicture = $args->getOldValue('bgPicture');
            $newPicture = $args->getNewValue('bgPicture');


            if ($newPicture == null) {
                $entity->setBgPicture($oldPicture);

            } else {
                if ($oldPicture != null && file_exists($this->img_path . '/f/' . $oldPicture))
                    unlink($this->img_path . '/f/' . $oldPicture);
                $this->uploadFile($entity);
            }
        }
        if ($entity instanceof PaymentMethod) {
            try {
                $oldPicture = $args->getOldValue('logo');
                $newPicture = $args->getNewValue('logo');


                if ($newPicture == null) {
                    $entity->setLogo($oldPicture);

                } else {
                    if ($oldPicture != null && file_exists($this->img_path . '/logo/' . $oldPicture))
                        unlink($this->img_path . '/logo/' . $oldPicture);
                    $this->uploadFile($entity);
                }
            } catch (\Exception $ex) {

            }
        }

    }


    /**
     * @param $entity
     */
    private function uploadFile($entity)
    {
        // upload only works for Article entities
        if ($entity instanceof Article) {
            $file = $entity->getUrlPicture();

            // only upload new files
            if (!$file instanceof UploadedFile) {
                return;
            }

            $fileName = $this->uploader->upload($file, 'p', $entity->getId() . '_article');

            $entity->setUrlPicture($fileName);
        }
        if ($entity instanceof Category) {
            $file = $entity->getBgPicture();

            // only upload new files
            if (!$file instanceof UploadedFile) {
                return;
            }

            $fileName = $this->uploader->upload($file, 'c', $entity->getId() . '_category');

            $entity->setBgPicture($fileName);
        }

        if ($entity instanceof Family) {
            $file = $entity->getBgPicture();

            // only upload new files
            if (!$file instanceof UploadedFile) {
                return;
            }

            $fileName = $this->uploader->upload($file, 'f', $entity->getId() . '_family');

            $entity->setBgPicture($fileName);
        }

        if ($entity instanceof PaymentMethod) {
            $file = $entity->getLogo();

            // only upload new files
            if (!$file instanceof UploadedFile) {
                return;
            }

            $fileName = $this->uploader->upload($file, 'logo', $entity->getId() . '_payment');

            $entity->setLogo($fileName);
        }


    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $fileName = $entity->getUrlPicture();

        $entity->setUrlPicture(new File($this->targetPath . '/' . $fileName));
    }

}