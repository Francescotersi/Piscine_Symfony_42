<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class NotesModel
{
    #[Assert\NotBlank(message: "The message cannot be blank.")]
    public ?string $message = null;
    public bool $includeTimestamp = false;
}