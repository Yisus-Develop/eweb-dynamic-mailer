# MC Dynamic Mailer - Project Plan & Status

## Project Overview

A WordPress plugin to automate personalized email outreach.

- **Goal:** Replace local `mail.html` with a robust WP plugin.
- **Key Features:** CSV Upload, Dynamic Placeholders, Auto-Language Detection (ES/PT/EN), History Persistence, Open Tracking.

## Current Status (Phase 1.5)

- [x] **Core Sending:** Works with `wp_mail`.
- [x] **Tracking:** Native pixel (`includes/tracker.php`) and DB logging (`wp_mc_mailer_logs`).
- [x] **Multi-language:**
  - Tabs in UI for ES/PT/EN.
  - Auto-detection based on 'Country' column in CSV.
- [x] **Context:** This file serves as the "Memory" for future AIs.

## Active Implementation Plan (Phase 1.5 UX)

1. **Visual Cards:** Show a "Live Card" of the current recipient during sending (User Experience improvement).
2. **Variable Chips:** Display clickable tags (e.g., `[City]`) based on CSV headers.
3. **History Tools:**
    - "Delete" button per row (for testing cleanup).
    - "Export to CSV" button (for reporting).
4. **Content:** Default templates for ES, PT, and EN provided by user.

## Future Roadmap (Phase 2)

- **Frontend Access:** Shortcode `[mc_mailer_dashboard]` for restricted role access.
- **Automation:** Hook into Contact Form 7 (`wpcf7_before_send_mail`) for real-time sending.

## Technical Notes

- **DB Table:** `wp_mc_mailer_logs` (Prefix respects WP config).
- **AJAX:** All actions routed through `admin-ajax.php`.
- **Security:** Nonces should be added in Phase 2.
