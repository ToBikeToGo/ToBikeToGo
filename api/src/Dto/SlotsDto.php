<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class SlotsDto
{
    #[Assert\Date]
    public string $date;
}
