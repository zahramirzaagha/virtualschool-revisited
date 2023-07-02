<?php

namespace App\Entity;

enum Role: string
{
    case Teacher = 'ROLE_TEACHER';
    case Parent = 'ROLE_PARENT';
    case Student = 'ROLE_STUDENT';
    case RaterCommenter = 'ROLE_RATERCOMMENTER';
}