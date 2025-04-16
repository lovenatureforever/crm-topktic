<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'Admin';
    case TEAM_LEADER = 'Team Leader';
    case AGENT = 'Agent';
}
