## Plan: Comprehensive Test Strategy for DORA

Establish a layered automated testing setup for unit, integration, UAT/end-to-end, cross-platform browser/device coverage, and baseline performance checks in Laravel 11, starting with fast deterministic unit/integration tests (PHPUnit), then browser/UAT (Laravel Dusk), then API/load checks (k6), with CI orchestration and clear scope boundaries.

**Steps**
1. Phase 1 - Testing Foundation (blocks later phases)
2. Add and commit base test configuration files: `phpunit.xml`, `tests/TestCase.php`, `tests/Unit/.gitkeep`, `tests/Feature/.gitkeep`, and update `composer.json` scripts for `test`, `test:unit`, `test:feature`, `test:coverage`.
3. Configure `.env.testing` with safe defaults (array/session cache sync drivers, sqlite test DB if chosen) and document local prerequisites in `README.md` test section.
4. Add database test scaffolding conventions using Laravel traits (`RefreshDatabase` for DB-coupled tests, pure unit tests without framework boot where practical).
5. Phase 2 - Unit Test Coverage (parallel with Phase 3 once foundation exists)
6. Create unit tests for service classes first: `CloudinaryService`, `WeatherService`, and `EmailService` success/failure behavior with facade fakes/mocks (`Http::fake`, `Cache` assertions, `Mail::fake`, exception path assertions).
7. Add model unit tests for key business rules/accessors/scopes only (avoid redundant Eloquent CRUD tests already covered by framework).
8. Phase 3 - Integration/Feature Tests (parallel with Phase 2)
9. Add feature tests for critical flows spanning routes/controllers/models: auth access controls by role (`traveler`, `agency`, `admin`), inquiry submission, agency package management, and favorites/memories write paths.
10. Validate side effects in integration tests: queued emails, DB state transitions, and middleware authorization behavior.
11. Phase 4 - UAT and Cross-Platform
12. Introduce Laravel Dusk for browser-level UAT scenarios (happy paths + one failure path each): traveler booking inquiry, agency dashboard actions, admin approval flow.
13. Define browser matrix and execution strategy: local smoke in Chrome + CI scheduled/full matrix for Chromium/Firefox/WebKit via Playwright alternative (decision required).
14. Add responsive checks for key breakpoints and JS-dependent interactions in UAT scripts.
15. Phase 5 - Performance Baseline
16. Add lightweight load/performance scripts for top endpoints (home page, destination detail, inquiry submit) using k6 or Artillery, with baseline thresholds for p95 latency/error rate.
17. Add DB query count/time regression checks for selected Laravel feature tests where known hotspots exist.
18. Phase 6 - CI/CD and Quality Gates (depends on phases 1-5)
19. Configure CI workflow (GitHub Actions or existing runner) to run lint + unit/integration on PR, UAT/performance on schedule or release branch; cache Composer/npm artifacts.
20. Publish artifacts: test reports, coverage summary, and failed-browser screenshots/logs.
21. Enforce merge gate: unit/integration required, UAT/performance non-blocking initially then promote to blocking after stability period.

**Relevant files**
- `composer.json` - add test scripts and optional coverage command.
- `README.md` - add testing section with commands and environment setup.
- `app/Services/CloudinaryService.php` - reference for upload/delete success and exception branch unit tests.
- `app/Services/WeatherService.php` - reference for HTTP/cache/transform logic test cases.
- `app/Services/EmailService.php` - reference for Mail send success/failure and message metadata assertions.
- `routes/web.php` - source for integration/UAT flow coverage map.
- `app/Http/Controllers/**` - integration test targets for role-gated workflows.
- `tests/Unit/**` - new unit tests.
- `tests/Feature/**` - new integration tests.
- `tests/Browser/**` - new UAT browser tests if Dusk is selected.
- `.env.testing` - isolated test environment configuration.
- `.github/workflows/**` (or project CI folder) - pipeline orchestration.
- `performance/**` - load/performance scripts and threshold configs.

**Verification**
1. Run `composer test` and confirm all unit + feature tests pass in clean environment.
2. Run targeted suites: `composer test:unit` and `composer test:feature` for deterministic results.
3. Run UAT suite locally in headless mode and verify screenshots/logs are generated on failures.
4. Run performance script with fixed virtual users and duration; verify thresholds pass and baseline report saved.
5. Execute CI pipeline on a PR branch and verify required gates, artifacts, and timing are acceptable.

**Decisions**
- Included: full strategy and implementation sequence for unit, integration, UAT, cross-platform, and performance coverage.
- Excluded for first rollout: mutation testing and chaos testing (can be phase-2 maturity upgrades).
- Recommended first technical focus: stabilize PHPUnit unit/integration foundation before adding browser/performance layers.
- Pending decision: Dusk-only (simpler Laravel-native) vs Playwright for stronger cross-browser matrix.
- Pending decision: sqlite in-memory vs dedicated MySQL test database for higher production fidelity.

**Further Considerations**
1. Browser UAT engine recommendation: Option A Dusk first (fast adoption), Option B Playwright first (strong cross-browser), Option C hybrid (Dusk smoke + Playwright matrix).
2. Test database strategy recommendation: Option A sqlite in-memory (speed), Option B MySQL test DB (schema fidelity), Option C dual-mode (sqlite local, MySQL CI).
3. Performance tooling recommendation: Option A k6 (scriptable and CI-friendly), Option B Artillery (JS-centric), Option C both (k6 API + Lighthouse UI budget checks later).