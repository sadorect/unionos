<?php

namespace App\Support\Privacy;

class PrivacyMatrix
{
    public const RULES = [
        'guest_couple_access' => false,
        'cross_couple_access' => false,
        'partner_raw_entry_access' => false,
        'partner_raw_insight_access' => false,
        'partner_weekly_checkin_numeric_access' => false,
        'partner_weekly_checkin_text_access' => false,
        'partner_weekly_checkin_completion_access' => false,
        'partner_monthly_raw_access' => false,
        'monthly_shared_merged_insight_access' => true,
        'conversation_transcript_export' => false,
        'conversation_live_typing_visibility' => false,
    ];
}
