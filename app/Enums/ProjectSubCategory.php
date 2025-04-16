<?php

namespace App\Enums;


enum ProjectSubCategory: int
{
    const FOOLOWUP_DECLINE_TO_SUBMIT_IC = 1;
    const FOOLOWUP_HIDDEN_CHANGES = 2;
    const FOOLOWUP_INTERSTED_ON_TIME_FIBRE = 3;
    const FOOLOWUP_INTERESTED_APPLY = 4;
    const FOOLOWUP_NO_ANSEWR_HANGUP = 5;
    const FOOLOWUP_NOT_BELIEVE = 6;
    const FOOLOWUP_SLOW_INTERNET = 7;
    const FOOLOWUP_SUSPECT_CON_CALL = 8;

    const FRESH_FRESH = 1;

    const INEFFECTIVE_INVALID_NUMBER = 1;
    const INEFFECTIVE_NO_ANSWER = 2;
    const INEFFECTIVE_WRONG_NUMBER = 3;

    const NEW_100MB  = 1;
    const NEW_300MB = 2;
    const NEW_500MB = 3;
    const NEW_1GB = 4;
    const NEW_2GB = 5;

    const POSTPAID_300MB = 1;
    const POSTPAID_500MB = 2;
    const POSTPAID_1GB = 3;
    const POSTPAID_2GB = 4;

    const NEW_POSTPAID_300MB = 1;
    const NEW_POSTPAID_500MB = 2;
    const NEW_POSTPAID_1GB = 3;
    const NEW_POSTPAID_2GB = 4;

    const PENDING_CALLBACK = 1;
    const PENDING_OUTCONTRACT = 2;
    const PENDING_DOCUMENTS = 3;
    const PENDING_UNDER_CONSIDERATION = 4;

    const REJECT_ACCEPTED_COUNT = 1;
    const REJECT_ALREADY_ASTRO = 2;
    const REJECT_ALREADY_CELECOM = 3;
    const REJECT_ALREADY_MAXIS = 4;
    const REJECT_ALREADY_TIME_FIBRE = 5;
    const REJECT_ALREADY_UNIFIED = 6;
    const REJECT_INTERNET_DOWNTIME = 7;
    const REJECT_FAMILY_DISAGREE = 8;
    const REJECT_HANGUP = 9;
    const REJECT_IN_CONTRACT = 10;
    const REJECT_NO_CLEAR = 11;
    const REJECT_NO_SERVICE_AREA = 12;
    const REJECT_OTHERS = 13;
    const REJECT_EARLY_TERM_FEE = 14;
    const REJECT_EARLY_TERM_FEE_500 = 15;
    const REJECT_WRITTEN_OFFSET = 16;
    const REJECT_WARKIN = 17;
    const REJECT_REALLOCATION = 18;
    const REJECT_REMAIN_UNIFI = 19;
    const REJECT_REMAIN_VOIP = 20;
    const REJECT_TERM_ACCOUNT = 21;

    const SUCCESS_30 = 1;
    const SUCCESS_100 = 2;
    const SUCCESS_300 = 3;
    const SUCCESS_500 = 4;
    const SUCCESS_800 = 5;

    const WINBACK_100 = 1;
    const WINBACK_300 = 2;
    const WINBACK_500 = 3;
    const WINBACK_1G = 4;
    const WINBACK_2G = 5;
}
