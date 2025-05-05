<?php

namespace App\Enums;

enum OrderTypes: int
{
    case DELIVERY = 1;
    case PICKUP = 2;
    case ON_STORE = 3;
}
