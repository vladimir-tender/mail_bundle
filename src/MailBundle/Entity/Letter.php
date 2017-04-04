<?php

namespace MailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Letter
 *
 * @ORM\Table(name="letter")
 * @ORM\Entity(repositoryClass="MailBundle\Repository\LetterRepository")
 */
class Letter
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
     * @ORM\Column(name="subject", type="string", length=255, nullable=true )
     */
    private $subject = 'No subject';

    /**
     * @var string
     *
     * @ORM\Column(name="receiver", type="string", length=2000)
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $receiver;


    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=65535, nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="senttime", type="datetime")
     */
    private $senttime;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true )
     */
    private $status = 0;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MailBundle\Entity\Files", mappedBy="letter")
     */
    private $files;

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
     * Set subject
     *
     * @param string $subject
     *
     * @return Letter
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set receiver
     *
     * @param string $receiver
     *
     * @return Letter
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set senttime
     *
     * @param \DateTime $senttime
     *
     * @return Letter
     */
    public function setSenttime($senttime)
    {
        $this->senttime = $senttime;

        return $this;
    }

    /**
     * Get senttime
     *
     * @return \DateTime
     */
    public function getSenttime()
    {
        return $this->senttime;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Letter
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param ArrayCollection $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }


}

