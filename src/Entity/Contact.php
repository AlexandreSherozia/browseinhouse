<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    private $contactingEmail,
            $contactedEmail,
            $advertSlug,
            $advertTitle,
            $contactedPseudo,
            $contactingPseudo;

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

    public function getContactingEmail(): string
    {
        return $this->contactingEmail;
    }

    public function setContactingEmail(string $contactingEmail): self
    {
        $this->contactingEmail = $contactingEmail;
        return $this;
    }

    public function getContactedEmail(): string
    {
        return $this->contactedEmail;
    }

    public function setContactedEmail(string $contactedEmail): self
    {
        $this->contactedEmail = $contactedEmail;
        return $this;
    }

    public function getAdvertSlug(): string
    {
        return $this->advertSlug;
    }

    public function setAdvertSlug(string $advertSlug): self
    {
        $this->advertSlug = $advertSlug;
        return $this;
    }

    public function getMessageTitle(): ?string
    {
        return $this->messageTitle;
    }


    public function setMessageTitle(string $messageTitle): void
    {
        $this->messageTitle = $messageTitle;
    }


    public function getMessageBody(): ?string
    {
        return $this->messageBody;
    }


    public function setMessageBody(string $messageBody): void
    {
        $this->messageBody = $messageBody;
    }

    public function getAdvertTitle(): string
    {
        return $this->advertTitle;
    }

    public function setAdvertTitle(string $advertTitle): self
    {
        $this->advertTitle = $advertTitle;
        return $this;
    }

    public function getContactedPseudo(): string
    {
        return $this->contactedPseudo;
    }

    public function setContactedPseudo(string $contactedPseudo): self
    {
        $this->contactedPseudo = $contactedPseudo;
        return $this;
    }

    public function getContactingPseudo(): string
    {
        return $this->contactingPseudo;
    }

    public function setContactingPseudo(string $contactingPseudo): self
    {
        $this->contactingPseudo = $contactingPseudo;
        return $this;
    }
}