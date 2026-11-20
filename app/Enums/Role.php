<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Demo = 'demo';
    case User = 'user';
}
