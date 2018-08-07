<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{

    private $contactingEmail;

    private $contactedEmail;

    private $advertSlug;

    private $advertTitle;

    private $contactedPseudo;

    private $contactingPseudo;


    /**
     * @Assert\NotBlank(message="You have to enter a title")
     * @Assert\Length(
     *      min = 5,
     *      max = 60,
     *      minMessage = "Your title must contain at least {{ limit }} characters",
     *      maxMessage = "Your title can't contain more than {{ limit }} characters")
     */
    private $messageTitle;

    /**
     * @Assert\NotBlank(message="You have to enter a message")
     * @Assert\Length(
     *      min = 10,
     *      max = 500,
     *      minMessage = "Your message must contain at least {{ limit }} characters",
     *      maxMessage = "Your message can't contain more than {{ limit }} characters")
     */
    private $messageBody;

    /**
     * @return mixed
     */
    public function getContactingEmail()
    {
        return $this->contactingEmail;
    }

    /**
     * @param mixed $contactingEmail
     */
    public function setContactingEmail($contactingEmail): void
    {
        $this->contactingEmail = $contactingEmail;
    }

    /**
     * @return mixed
     */
    public function getContactedEmail()
    {
        return $this->contactedEmail;
    }

    /**
     * @param mixed $contactedEmail
     */
    public function setContactedEmail($contactedEmail): void
    {
        $this->contactedEmail = $contactedEmail;
    }

    /**
     * @return mixed
     */
    public function getAdvertSlug()
    {
        return $this->advertSlug;
    }

    /**
     * @param mixed $advertSlug
     */
    public function setAdvertSlug($advertSlug): void
    {
        $this->advertSlug = $advertSlug;
    }

    /**
     * @return mixed
     */
    public function getMessageTitle()
    {
        return $this->messageTitle;
    }

    /**
     * @param mixed $messageTitle
     */
    public function setMessageTitle($messageTitle): void
    {
        $this->messageTitle = $messageTitle;
    }

    /**
     * @return mixed
     */
    public function getMessageBody()
    {
        return $this->messageBody;
    }

    /**
     * @param mixed $messageBody
     */
    public function setMessageBody($messageBody): void
    {
        $this->messageBody = $messageBody;
    }

    /**
     * @return mixed
     */
    public function getAdvertTitle()
    {
        return $this->advertTitle;
    }

    /**
     * @param mixed $advertTitle
     */
    public function setAdvertTitle($advertTitle): void
    {
        $this->advertTitle = $advertTitle;
    }

    /**
     * @return mixed
     */
    public function getContactedPseudo()
    {
        return $this->contactedpseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setContactedPseudo($contactedpseudo): void
    {
        $this->contactedpseudo = $contactedpseudo;
    }

    /**
     * @return mixed
     */
    public function getContactingPseudo()
    {
        return $this->contactingPseudo;
    }

    /**
     * @param mixed $contactingPseudo
     */
    public function setContactingPseudo($contactingPseudo): void
    {
        $this->contactingPseudo = $contactingPseudo;
    }



}