<?php

namespace App\Enums;

enum ProjectCategory: string
{

    case FOLLOWUP = 'FOLLOWUP';
    case FRESH = 'Fresh';
    case INACTIVE = 'INACTIVE';
    case NEW_NEW = 'NEW NEW';
    case NEW_NEW_WITH_EXISTING_POSTPAID = 'NEW NEW WITH EXISTING POSTPAID';
    case NEW_NEW_WITH_NEW_POSTPAID = 'NEW NEW WITH NEW POSTPAID';
    case PENDING = 'PENDING';
    case REJECT = 'REJECT';
    case SUCCESS = 'SUCCESS(Business Plan)';
    case WINBACK = 'WINBACK';
}
