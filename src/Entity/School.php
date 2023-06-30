<?php

namespace App\Entity;

enum School: string
{
    case Preschool = 'preschool';
    case Elementary = 'elementary';
    case Secondary = 'secondary';
}