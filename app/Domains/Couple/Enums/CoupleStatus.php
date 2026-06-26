<?php

namespace App\Domains\Couple\Enums;

enum CoupleStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Dissolved = 'dissolved';
}
