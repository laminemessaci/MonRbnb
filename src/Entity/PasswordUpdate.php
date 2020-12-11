<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpdate
{

    private $oldPassword;

    /**
     * @Assert\Length (min=8, minMessage="Votre mot de passe doit faire uu moins 8 caractères !")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo (propertyPath="newPassword", message="Vous n'avez pas correctement confirmé votre nouveau mot de passe")
     */
    private $confirmePassword;



    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmePassword(): ?string
    {
        return $this->confirmePassword;
    }

    public function setConfirmePassword(string $confirmePassword): self
    {
        $this->confirmePassword = $confirmePassword;

        return $this;
    }
}
