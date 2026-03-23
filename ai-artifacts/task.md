# Task List: Mars Dynamic Mailer (WordPress Plugin)

- [x] Analyze AI-Vault environment and global rules <!-- id: 0 -->
- [x] Analyze `mail.html` implementation <!-- id: 1 -->
- [x] Answer user question about Gmail integration (sending address) <!-- id: 2 -->
- [x] Implement "Filter by Institution Type" <!-- id: 5 -->
- [x] Implement Dynamic Template Engine (Subject/Body placeholders) <!-- id: 6 -->
- [x] Implement "One-by-One" Workflow (Copy Body + Open Gmail Link) <!-- id: 7 -->
- [x] **Phase 1: Plugin Features (v1.1)**
  - [x] Multi-language Support (Tabs + Country Auto-detect in `admin/ui-page.php`)
  - [x] History Persistence (DB Table `wp_mc_mailer_logs`)
  - [x] Display History in Admin UI
    - [ ] **Phase 1.5: UX Enhancements (Testing & Reporting)**
      - [ ] Add "Delete Single Log" button (for re-testing specific emails) <!-- id: 10 -->
      - [ ] Add "Export History to CSV" button (Evidence for Boss) <!-- id: 11 -->
      - [ ] Display "Available Variable Chips" (e.g. `[City]`, `[Phone]`) dynamically from CSV <!-- id: 12 -->
        - [ ] **Visual Improvement:** Show "Sending Card" (Current Email) during process (like mail.html) <!-- id: 14 -->
        - [ ] **Content:** Add Default English Template <!-- id: 15 -->
        - [ ] **Safety:** Add "Mini Preview" modal to verify placeholders before sending <!-- id: 16 -->
        - [x] **Safety:** Implement "Simulation Mode" (Dry Run) to verify filters <!-- id: 17 -->
- [ ] **Phase 2: Post-Launch Support**ation to match specific CSV headers <!-- id: 13 -->

## Phase 1: WordPress Plugin (Complete Solution)

- [x] Create Plugin Scaffolding (`mc-dynamic-mailer`) <!-- id: 14 -->
- [x] Implement Flexible Column Mapping UI <!-- id: 15 -->
- [x] Implement Mailer Engine (Force From, BCC, Deduplication) <!-- id: 16 -->
- [x] Implement Tracking System (Pixel Endpoint + DB Log) <!-- id: 20 -->
- [x] Implement Batch Processor (AJAX + Progress Bar) <!-- id: 17 -->
- [ ] Verify Bulk Sending & Tracking & Deduplication <!-- id: 19 -->

## Phase 2: Advanced Automation (Future)

- [ ] Analyze Form Plugin Structure (CF7/Elementor) <!-- id: 21 -->
- [ ] Create Form Submission Hook <!-- id: 22 -->
