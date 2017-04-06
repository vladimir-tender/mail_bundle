<?php

namespace MailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Files
 *
 * @ORM\Table(name="files")
 * @ORM\Entity(repositoryClass="MailBundle\Repository\FilesRepository")
 */
class Files
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="serverFileName", type="string", length=255, unique=true)
     */
    private $serverFileName;

    /**
     * @var string
     *
     * @ORM\Column(name="clientFileName", type="string", length=255)
     */
    private $clientFileName;

    /**
     * @var string
     *
     * @ORM\Column(name="fileExtention", type="string", length=255, nullable=true)
     */
    private $fileExtention;

    /**
     * @var Letter
     *
     * @ORM\ManyToOne(targetEntity="MailBundle\Entity\Letter", inversedBy="files")
     * @ORM\JoinColumn(name="id_letter", referencedColumnName="id")
     */
    private $letter;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getServerFileName()
    {
        return $this->serverFileName;
    }

    /**
     * @param string $serverFileName
     */
    public function setServerFileName($serverFileName)
    {
        $this->serverFileName = $serverFileName;
    }

    /**
     * @return string
     */
    public function getClientFileName()
    {
        return $this->clientFileName;
    }

    /**
     * @param string $clientFileName
     */
    public function setClientFileName($clientFileName)
    {
        $this->clientFileName = $clientFileName;
    }

    /**
     * @return string
     */
    public function getFileExtention()
    {
        return $this->fileExtention;
    }

    /**
     * @param string $fileExtention
     */
    public function setFileExtention($fileExtention)
    {
        $this->fileExtention = $fileExtention;
    }

    /**
     * @return Letter
     */
    public function getLetter()
    {
        return $this->letter;
    }

    /**
     * @param Letter $letter
     */
    public function setLetter($letter)
    {
        $this->letter = $letter;
    }


}

