Absolutely. These two pieces are the **skeleton** and the **security nervous system** of the app.

They do **not** force the agent line-by-line, but they heavily reduce nonsense, leakage, and “creative” architecture. That’s what you want.

---

# Single Master Repo Structure

This is a **recommended Laravel 12 + Inertia + Vue 3 master structure** for UnionOS.
It is opinionated, but not brittle.

## Top-level intent

* **Domains** hold business logic
* **Http** handles delivery
* **Policies** enforce boundaries
* **Services/Actions** keep controllers thin
* **Pages** mirror product phases
* **Shared** contains cross-cutting UI

---

```text
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
```

---

## How to use this with the agent

This structure should be given as:

> “Use this repo structure as the preferred architecture.
> You may adapt only where Laravel 12 scaffolding requires it, but do not collapse domain boundaries, mix privacy logic into UI components, or move authorization concerns out of policies/services.”

That gives flexibility without letting the agent freestyle into nonsense.

---

# Policy Matrix

This is the **who-sees-what** contract.
If the app ever becomes messy, this table is the referee.

## Core principles

* **Deny by default**
* **Private by default**
* **Couple-scoped by default**
* **No raw emotional data shared unless explicitly defined**
* **Dissolved couples cannot create new activity**

---

## Actor definitions

* **Guest** — not logged in
* **Authenticated User** — logged in, may or may not belong to couple
* **Pending Member** — belongs to `pending` couple
* **Active Member** — belongs to `active` couple
* **Dissolved Member** — belonged to dissolved couple
* **Partner** — the other member of the same active couple
* **System Job** — scheduled/queue processes acting within rules
* **Admin** — not part of MVP, so assume nonexistent

---

## Master policy matrix

| Actor              | Resource                                 |            Action | Allowed?                           | Condition                                               |
| ------------------ | ---------------------------------------- | ----------------: | ---------------------------------- | ------------------------------------------------------- |
| Guest              | Auth screens                             |              view | Yes                                | Public auth only                                        |
| Guest              | Invite acceptance page                   |              view | Yes                                | Valid token required                                    |
| Guest              | Couple data                              |              view | No                                 | Never                                                   |
| Guest              | Check-ins                                |               any | No                                 | Never                                                   |
| Guest              | Insights                                 |               any | No                                 | Never                                                   |
| Guest              | Conversations                            |               any | No                                 | Never                                                   |
| Authenticated User | Own user profile/basic account           |       view/update | Yes                                | Self only                                               |
| Authenticated User | Another user record                      |       view/update | No                                 | Never                                                   |
| Authenticated User | Create couple                            |            create | Yes                                | Only if not already in active/pending couple            |
| Authenticated User | Couple                                   |              view | Yes                                | Must be member                                          |
| Authenticated User | Couple                                   |            update | Limited                            | State-safe actions only                                 |
| Authenticated User | Couple invite                            |            create | Yes                                | Must belong to pending couple and invite slot available |
| Authenticated User | Couple invite                            | regenerate/cancel | Yes                                | Must be same couple member                              |
| Authenticated User | Couple invite                            |               use | Yes                                | Valid token and couple not full                         |
| Pending Member     | Weekly check-in                          |       create/view | No                                 | Couple must be active                                   |
| Pending Member     | Monthly check-in                         |       create/view | No                                 | Couple must be active                                   |
| Pending Member     | Conversations                            |               any | No                                 | Couple must be active                                   |
| Pending Member     | Couple pending state page                |              view | Yes                                | Own couple only                                         |
| Active Member      | Own weekly check-in                      |            create | Yes                                | One per open week                                       |
| Active Member      | Own weekly check-in                      |              view | Yes                                | Self only                                               |
| Active Member      | Own weekly check-in                      |            update | Yes                                | Same open week only                                     |
| Active Member      | Own weekly check-in                      |            delete | No                                 | Preserve history                                        |
| Active Member      | Partner weekly check-in raw values       |              view | No                                 | Never                                                   |
| Active Member      | Partner weekly check-in text             |              view | No                                 | Never                                                   |
| Active Member      | Weekly trend aggregate                   |              view | Yes                                | Only if aggregate excludes raw entries                  |
| Active Member      | Own monthly couple check-in submission   |            create | Yes                                | One per open month                                      |
| Active Member      | Own monthly couple check-in raw response |              view | Yes                                | Self only                                               |
| Active Member      | Partner monthly raw response             |              view | No                                 | Never                                                   |
| Active Member      | Shared monthly insight                   |              view | Yes                                | Only after both have submitted                          |
| Active Member      | Shared monthly insight                   |              view | No                                 | If only one partner submitted                           |
| Active Member      | Start conversation session               |            create | Yes                                | Couple active and no conflicting active session         |
| Active Member      | Conversation session                     |              view | Yes                                | Must belong to same couple                              |
| Active Member      | Conversation turn                        |            submit | Yes                                | Must be current turn owner                              |
| Active Member      | Conversation turn                        | edit after submit | No                                 | Never                                                   |
| Active Member      | Conversation session                     |      pause/resume | Yes                                | Must belong to same couple and session not completed    |
| Active Member      | Conversation session                     |          complete | Yes                                | Through valid flow only                                 |
| Active Member      | Conversation transcript export           |            export | No                                 | Not in MVP                                              |
| Active Member      | Personal dashboard                       |              view | Yes                                | Self only                                               |
| Active Member      | Partner personal dashboard               |              view | No                                 | Never                                                   |
| Active Member      | Couple dashboard                         |              view | Yes                                | Same active couple only                                 |
| Active Member      | Couple dashboard raw data drill-down     |              view | No                                 | Never expose raw partner entries                        |
| Active Member      | Notification preferences                 |            update | Yes                                | Self only                                               |
| Active Member      | Dissolve couple                          |           trigger | Yes                                | Either member may trigger                               |
| Dissolved Member   | New weekly check-in                      |            create | No                                 | Couple dissolved                                        |
| Dissolved Member   | New monthly check-in                     |            create | No                                 | Couple dissolved                                        |
| Dissolved Member   | New conversation                         |            create | No                                 | Couple dissolved                                        |
| Dissolved Member   | Historical couple dashboard              |              view | Limited/Optional                   | If enabled, read-only and privacy-safe                  |
| Dissolved Member   | Re-activate dissolved couple             |            update | No                                 | New couple required                                     |
| System Job         | Lock expired check-ins                   |           execute | Yes                                | Schedule-driven                                         |
| System Job         | Finalize monthly insight windows         |           execute | Yes                                | Schedule-driven                                         |
| System Job         | Send reminders                           |           execute | Yes                                | Suppression rules must pass                             |
| System Job         | Read raw reflection text for logging     |                No | Never                              |                                                         |
| System Job         | Access couple data cross-scope           |                No | Never outside intended query scope |                                                         |

---

## Resource-specific rules

## 1. User Policy

### Allow

* `view(User $target)` only if `auth()->id() === $target->id`
* `update(User $target)` only if self
* `delete(User $target)` only if self, with soft-delete workflow

### Deny

* Viewing partner as full user object
* Listing users
* Searching users

The partner is a relationship context, not a browseable profile.

---

## 2. Couple Policy

### Allow

* `view(Couple $couple)` only if current user is an active or pending member
* `dissolve(Couple $couple)` if current user belongs to that couple
* `acceptInvite(CoupleInvite $invite)` if invite valid and capacity not exceeded

### Deny

* Viewing any other couple
* Adding third member
* Reopening dissolved couple

---

## 3. WeeklyCheckIn Policy

### Allow

* `create` only for active-couple users during current open week
* `view` only own entry
* `update` only own entry during same week window

### Deny

* Partner access to raw entries
* Updates after week closes
* Deletion once submitted

---

## 4. MonthlyCoupleCheckIn Policy

### Allow

* `create` only once per user per open month, active couple only
* `viewOwn` only self raw response
* `viewSharedInsight` both members, but only after both submitted

### Deny

* Viewing partner raw monthly answers
* Viewing partial insight before both submissions

---

## 5. ConversationSession Policy

### Allow

* `create` for active couple members only
* `view` only same-couple members
* `submitTurn` only if session active and user owns current turn
* `pause/resume` same-couple members while not completed

### Deny

* Access by pending or dissolved users
* Editing completed session
* Viewing other couples’ sessions
* Exporting transcript/content

---

## 6. Dashboard Policies

### Personal dashboard

Allow self only.

### Couple dashboard

Allow both members of the same active couple.

### Deny

* Partner access to personal trend internals
* Any raw partner text
* Any ranking/comparison layer

---

## 7. NotificationPreference Policy

### Allow

* Self only

### Deny

* Partner changing your preferences
* Cross-user reads unless reduced to safe shared flags, if ever added later

---

# Suggested Laravel policy map

```php
// AuthServiceProvider or equivalent policy registration
protected $policies = [
    \App\Models\User::class => \App\Policies\UserPolicy::class,

    \App\Domains\Couple\Models\Couple::class
        => \App\Domains\Couple\Policies\CouplePolicy::class,

    \App\Domains\Couple\Models\CoupleInvite::class
        => \App\Domains\Couple\Policies\CoupleInvitePolicy::class,

    \App\Domains\CheckIn\Models\WeeklyCheckIn::class
        => \App\Domains\CheckIn\Policies\WeeklyCheckInPolicy::class,

    \App\Domains\CheckIn\Models\MonthlyCoupleCheckIn::class
        => \App\Domains\CheckIn\Policies\MonthlyCoupleCheckInPolicy::class,

    \App\Domains\Conversation\Models\ConversationSession::class
        => \App\Domains\Conversation\Policies\ConversationSessionPolicy::class,

    \App\Domains\Conversation\Models\ConversationTurn::class
        => \App\Domains\Conversation\Policies\ConversationTurnPolicy::class,
];
```

---

# Middleware guard strategy

Policies alone are not enough. Put bouncers at the door too.

## Use middleware for:

* `auth`
* `verified` if you enable email verification
* `EnsureCoupleMember`
* `EnsureActiveCouple`
* `EnsureNoActiveConversationConflict` where needed

## Example route grouping idea

```php
Route::middleware(['auth'])->group(function () {
    Route::prefix('couple')->group(function () {
        // pending + active couple routes
    });

    Route::middleware(['ensure.active.couple'])->group(function () {
        // weekly check-ins
        // monthly check-ins
        // conversations
        // couple dashboard
    });
});
```

---

# Query scoping rule

Every couple-owned resource should be queried through a couple boundary.

Bad:

```php
WeeklyCheckIn::findOrFail($id);
```

Good:

```php
WeeklyCheckIn::query()
    ->where('couple_id', $user->activeCouple()->id)
    ->findOrFail($id);
```

Better:
wrap that pattern in dedicated query classes/services so nobody gets clever later.

---

# Privacy red lines

These are the “absolutely not” items.

* Never expose partner raw journal text
* Never expose partner slider/score values directly
* Never reveal “your partner submitted, why haven’t you?”
* Never compare partners side by side
* Never log reflection text
* Never allow dissolved couples to keep creating data
* Never allow a third member in any state

If the app breaks one of these, it’s not a bug. It’s a trust breach.


