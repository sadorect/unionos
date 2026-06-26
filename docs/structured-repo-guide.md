unionos/
├── app/
│   ├── Actions/
│   │   ├── Couple/
│   │   │   ├── CreateCouple.php
│   │   │   ├── AcceptCoupleInvite.php
│   │   │   ├── DissolveCouple.php
│   │   │   └── RegenerateCoupleInvite.php
│   │   ├── CheckIn/
│   │   │   ├── SubmitWeeklyCheckIn.php
│   │   │   ├── UpdateWeeklyCheckIn.php
│   │   │   ├── SubmitMonthlyCoupleCheckIn.php
│   │   │   └── LockExpiredCheckInPeriods.php
│   │   ├── Conversation/
│   │   │   ├── StartConversationSession.php
│   │   │   ├── SubmitConversationTurn.php
│   │   │   ├── PauseConversationSession.php
│   │   │   ├── ResumeConversationSession.php
│   │   │   └── CompleteConversationSession.php
│   │   └── Dashboard/
│   │       ├── BuildPersonalDashboard.php
│   │       └── BuildCoupleDashboard.php
│   │
│   ├── Data/
│   │   ├── Insight/
│   │   │   ├── AlignmentResultData.php
│   │   │   ├── MonthlyInsightData.php
│   │   │   └── TrendPointData.php
│   │   ├── Conversation/
│   │   │   ├── ConversationPackData.php
│   │   │   └── ConversationPromptData.php
│   │   └── Dashboard/
│   │       ├── CoupleDashboardData.php
│   │       └── PersonalDashboardData.php
│   │
│   ├── Domains/
│   │   ├── Couple/
│   │   │   ├── Models/
│   │   │   │   ├── Couple.php
│   │   │   │   ├── CoupleInvite.php
│   │   │   │   └── CoupleMembership.php
│   │   │   ├── Enums/
│   │   │   │   └── CoupleStatus.php
│   │   │   ├── Policies/
│   │   │   │   ├── CouplePolicy.php
│   │   │   │   └── CoupleInvitePolicy.php
│   │   │   ├── Services/
│   │   │   │   ├── CoupleAccessService.php
│   │   │   │   └── CoupleStateService.php
│   │   │   └── Queries/
│   │   │       └── ActiveCoupleForUser.php
│   │   │
│   │   ├── CheckIn/
│   │   │   ├── Models/
│   │   │   │   ├── WeeklyCheckIn.php
│   │   │   │   └── MonthlyCoupleCheckIn.php
│   │   │   ├── Enums/
│   │   │   │   └── CheckInPeriodType.php
│   │   │   ├── Policies/
│   │   │   │   ├── WeeklyCheckInPolicy.php
│   │   │   │   └── MonthlyCoupleCheckInPolicy.php
│   │   │   ├── Services/
│   │   │   │   ├── WeeklyCheckInService.php
│   │   │   │   ├── MonthlyInsightMergeService.php
│   │   │   │   └── CheckInWindowService.php
│   │   │   └── Queries/
│   │   │       ├── WeeklyCheckInHistoryForUser.php
│   │   │       └── MonthlyInsightForCouple.php
│   │   │
│   │   ├── Conversation/
│   │   │   ├── Models/
│   │   │   │   ├── ConversationPack.php
│   │   │   │   ├── ConversationPrompt.php
│   │   │   │   ├── ConversationSession.php
│   │   │   │   └── ConversationTurn.php
│   │   │   ├── Enums/
│   │   │   │   ├── ConversationPackType.php
│   │   │   │   └── ConversationSessionStatus.php
│   │   │   ├── Policies/
│   │   │   │   ├── ConversationSessionPolicy.php
│   │   │   │   └── ConversationTurnPolicy.php
│   │   │   ├── Services/
│   │   │   │   ├── ConversationFlowService.php
│   │   │   │   ├── ConversationAccessService.php
│   │   │   │   └── PromptPackResolver.php
│   │   │   └── Queries/
│   │   │       └── ConversationHistoryForCouple.php
│   │   │
│   │   ├── Dashboard/
│   │   │   ├── Policies/
│   │   │   │   ├── PersonalDashboardPolicy.php
│   │   │   │   └── CoupleDashboardPolicy.php
│   │   │   ├── Services/
│   │   │   │   ├── PersonalTrendService.php
│   │   │   │   ├── CoupleTrendService.php
│   │   │   │   └── DashboardInsightLabelService.php
│   │   │   └── Queries/
│   │   │       ├── PersonalDashboardQuery.php
│   │   │       └── CoupleDashboardQuery.php
│   │   │
│   │   └── Notification/
│   │       ├── Services/
│   │       │   ├── NotificationSuppressionService.php
│   │       │   └── NotificationTimingService.php
│   │       └── Policies/
│   │           └── NotificationPreferencePolicy.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── Couple/
│   │   │   │   ├── CoupleController.php
│   │   │   │   ├── CoupleInviteController.php
│   │   │   │   └── CoupleDissolutionController.php
│   │   │   ├── CheckIn/
│   │   │   │   ├── WeeklyCheckInController.php
│   │   │   │   └── MonthlyCoupleCheckInController.php
│   │   │   ├── Conversation/
│   │   │   │   ├── ConversationSessionController.php
│   │   │   │   └── ConversationTurnController.php
│   │   │   ├── Dashboard/
│   │   │   │   ├── PersonalDashboardController.php
│   │   │   │   └── CoupleDashboardController.php
│   │   │   └── Settings/
│   │   │       └── NotificationPreferenceController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── EnsureActiveCouple.php
│   │   │   ├── EnsureCoupleMember.php
│   │   │   ├── EnsureNoActiveConversationConflict.php
│   │   │   └── ShareInertiaCommonData.php
│   │   │
│   │   ├── Requests/
│   │   │   ├── Couple/
│   │   │   ├── CheckIn/
│   │   │   ├── Conversation/
│   │   │   └── Settings/
│   │   │
│   │   └── Resources/
│   │       ├── Couple/
│   │       ├── CheckIn/
│   │       ├── Conversation/
│   │       └── Dashboard/
│   │
│   ├── Jobs/
│   │   ├── LockExpiredWeeklyCheckInsJob.php
│   │   ├── FinalizeMonthlyInsightWindowsJob.php
│   │   ├── SendWeeklyCheckInReminderJob.php
│   │   └── SendMonthlyCheckInReminderJob.php
│   │
│   ├── Listeners/
│   │   ├── LogPolicyViolation.php
│   │   ├── RecordFeatureUsage.php
│   │   └── DissolveCoupleOnUserDeletion.php
│   │
│   ├── Models/
│   │   └── User.php
│   │
│   ├── Notifications/
│   │   ├── WeeklyCheckInReminder.php
│   │   ├── MonthlyCheckInOpened.php
│   │   └── GuidedConversationSuggested.php
│   │
│   ├── Observers/
│   │   ├── UserObserver.php
│   │   └── CoupleObserver.php
│   │
│   ├── Policies/
│   │   └── UserPolicy.php
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   └── EventServiceProvider.php
│   │
│   └── Support/
│       ├── Privacy/
│       │   ├── PrivacyMatrix.php
│       │   ├── SafeLogger.php
│       │   └── CoupleScopedQuery.php
│       ├── Dates/
│       │   ├── WeekBoundary.php
│       │   └── MonthBoundary.php
│       └── Content/
│           └── ToneGuard.php
│
├── bootstrap/
├── config/
│   ├── unionos.php
│   └── prompts.php
│
├── database/
│   ├── factories/
│   ├── migrations/
│   ├── seeders/
│   │   ├── ConversationPackSeeder.php
│   │   ├── ConversationPromptSeeder.php
│   │   └── DatabaseSeeder.php
│   └── states/
│
├── resources/
│   ├── js/
│   │   ├── app.js
│   │   ├── Layouts/
│   │   │   ├── AppLayout.vue
│   │   │   ├── AuthLayout.vue
│   │   │   └── CoupleLayout.vue
│   │   ├── Pages/
│   │   │   ├── Auth/
│   │   │   │   ├── Login.vue
│   │   │   │   ├── Register.vue
│   │   │   │   └── ForgotPassword.vue
│   │   │   ├── Couple/
│   │   │   │   ├── Create.vue
│   │   │   │   ├── Pending.vue
│   │   │   │   ├── Invite.vue
│   │   │   │   ├── AcceptInvite.vue
│   │   │   │   └── Dissolved.vue
│   │   │   ├── CheckIn/
│   │   │   │   ├── WeeklyForm.vue
│   │   │   │   ├── WeeklyHistory.vue
│   │   │   │   ├── MonthlyForm.vue
│   │   │   │   ├── MonthlyWaiting.vue
│   │   │   │   └── MonthlyInsight.vue
│   │   │   ├── Conversation/
│   │   │   │   ├── Index.vue
│   │   │   │   ├── GroundRules.vue
│   │   │   │   ├── Turn.vue
│   │   │   │   ├── Pause.vue
│   │   │   │   ├── AppreciationClose.vue
│   │   │   │   └── Complete.vue
│   │   │   ├── Dashboard/
│   │   │   │   ├── Personal.vue
│   │   │   │   └── Couple.vue
│   │   │   └── Settings/
│   │   │       └── Notifications.vue
│   │   ├── Components/
│   │   │   ├── Couple/
│   │   │   ├── CheckIn/
│   │   │   ├── Conversation/
│   │   │   ├── Dashboard/
│   │   │   └── Shared/
│   │   ├── Composables/
│   │   │   ├── useCouple.js
│   │   │   ├── useCheckInWindow.js
│   │   │   └── useConversationSession.js
│   │   └── Utils/
│   │       ├── formatters.js
│   │       └── guards.js
│   ├── css/
│   │   └── app.css
│   └── views/
│       └── app.blade.php
│
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── console.php
│
├── storage/
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   ├── Couple/
│   │   ├── CheckIn/
│   │   ├── Conversation/
│   │   ├── Dashboard/
│   │   └── Privacy/
│   ├── Unit/
│   │   ├── Domains/
│   │   └── Support/
│   └── Pest.php
│
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md



Use the attached master repo structure as the preferred architectural layout.
You may adapt for Laravel 12 scaffolding only where necessary, but do not merge domains, do not move authorization logic into controllers, and do not expose private emotional data outside policy-approved views.

Use the attached policy matrix as the source of truth for all authorization and visibility decisions.
If a feature implementation conflicts with the matrix, the matrix wins.
Default to deny-by-default behavior for any undefined access path.
All code must be production-ready, secure, and maintainable, with comprehensive test coverage for edge cases.