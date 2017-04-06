<?php

namespace MailBundle\Services;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Ivory\CKEditorBundle\Exception\Exception;
use MailBundle\Entity\Files;
use MailBundle\Entity\Letter;

class FileUploader
{
    private $manager;
    private $uploadDir;

    public function __construct(EntityManager $manager, $uploadDir)
    {
        $this->manager = $manager;
        $this->uploadDir = $uploadDir;
    }

    public function uploaderInit($serverFiles, $fileList, Letter $letter)
    {
        $clientFiles = json_decode($fileList);

        $resultFileNames = array_intersect($clientFiles, $serverFiles['name']);

        $resultFilesArray = [];
        foreach ($resultFileNames as $fileName) {
            foreach ($serverFiles['name'] as $key => $name) {
                if ($fileName == $name) {
                    $resultFilesArray[$key]['name'] = $fileName;
                    $resultFilesArray[$key]['type'] = $serverFiles['type'][$key];
                    $resultFilesArray[$key]['tmp_name'] = $serverFiles['tmp_name'][$key];
                    $resultFilesArray[$key]['error'] = $serverFiles['error'][$key];
                    $resultFilesArray[$key]['size'] = $serverFiles['size'][$key];
                }
            }
        }

        //var_dump($resultFilesArray);

        $filesCollection = new ArrayCollection();

        foreach ($resultFilesArray as $file) {
            $newFile = new Files();

            $clientFileName = basename($file['name']);
            $serverFileName = time() . "_" . $clientFileName;

            $newFile->setClientFileName($clientFileName);
            $newFile->setServerFileName($serverFileName);
            $newFile->setLetter($letter);

            try {
                move_uploaded_file($file['tmp_name'], $this->uploadDir . $serverFileName);
            } catch (\Exception $exception) {
                throw new Exception("Something wrong with moving the file");
            }

            $filesCollection->add($newFile);
        }

        //var_dump($filesCollection);
        return $filesCollection;
    }

    public function getNextLetterId()
    {//now unused
        $nextLetterId = $this->manager
            ->createQueryBuilder()
            ->select('MAX(e.id)')
            ->from('MailBundle:Letter', 'e')
            ->getQuery()
            ->getSingleScalarResult();
        return ++$nextLetterId;
    }


}