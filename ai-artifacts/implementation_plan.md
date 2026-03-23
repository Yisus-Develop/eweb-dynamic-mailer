# Plan de Implementación: Mars Dynamic Mailer (WordPress Plugin)

## FASE 1: Solución Completa (Envío + Tracking + Limpieza)

**Objetivo:** Plugin de WordPress para envío masivo seguro, sin duplicados y con seguimiento.

### Arquitectura Técnica

#### 1. Mapeo Flexible & Limpieza (CSV)

- Interfaz para seleccionar columnas.
- Filtro Anti-Duplicados (Emails repetidos).

#### 2. Motor de Envío (SMTP/WP Mail)

- **Headers:** Force From `ventas@marschallenge.space`.
- **BCC Dinámico (Monday.com):** Campo para poner el correo "pulse" de Monday. Esto crea un item en el CRM por cada correo enviado, permitiendo seguimiento real allí.

#### 3. Tracking & Dashboard (Mini-CRM)

- **Tracking de Aperturas (Automático):** Píxel invisible 1x1px.
- **Tracking de Respuestas (Manual):**
  - El plugin NO puede leer tu Gmail.
  - **Solución:** Una columna "Estado" en el dashboard.
  - Estados: `Enviado` -> `Abierto` -> `Respondido (Manual)`.
  - Tú marcas "Respondido" cuando ves el correo en tu bandeja.

### History & Persistence

#### [MODIFY] [includes/history.php](file:///C:/Users/jesus/AI-Vault/projects/mc-dynamic-mailer/includes/history.php)

- Handle `mc_mailer_get_history` AJAX functionality.
- [NEW] Handle `mc_mailer_delete_log` (delete by ID).

#### [MODIFY] [admin/ui-page.php](file:///C:/Users/jesus/AI-Vault/projects/mc-dynamic-mailer/admin/ui-page.php)

- **History Table:** Add "Delete" button per row.
- **Variable Chips:** Add a container to display available CSV headers as clickable tags (e.g., `[City]`).
- **Export:** Add "Export to CSV" button for the history table (Client-side JS implementation for simplicity).
- **Visuals:** Update "Progress Area" to show a "Live Card" of the current recipient (Name, Inst, Email) being processed, mimicking the look of `mail.html`.
- **Content:** Populate Tab-EN with proper default text.
- **Preview:** Add a "Predict/Preview" button that takes the first CSV row, parses variables, and shows the result in a modal.
- **Simulation:** Add "Dry Run" button. It iterates through the CSV applying filters and logic, but skips the actual API call. Reports: "Total Rows", "Matched Filters", "Would Send To".

### Phase 2: Automation (No More CSVs)

**Goal:** Eliminate manual uploads. Trigger emails instantly when someone registers.

**Implementation Logic:**

1. **Analyze Hook:** Use `wpcf7_mail_sent` (Contact Form 7) or `elementor_pro/forms/new_record`.
2. **The Connector:**
    - Detect Form ID (e.g., Form 2759).
    - Extract fields: `$_POST['your-email']`, `$_POST['your-name']`.
    - Detect Country (via IP or Form Field).
    - Reuse existing `MC_Mailer::send()` function.
3. **Safety:**
    - Log every automated send in `wp_mc_mailer_logs`.
    - Add a "Sleep/Quiet Mode" switch in Admin to temporarily stop automation without deactivating the plugin.
4. **Content Logic (Smart Templates):**
    - **Issue:** Different profiles need different emails.
    - **Solution:** A "Template Manager" in settings logic.

#### [NEW] [includes/automation.php](file:///C:/Users/jesus/AI-Vault/projects/mc-dynamic-mailer/includes/automation.php)

### Estructura de Archivos (Actualizada)

`wp-plugins/mc-dynamic-mailer/`

- `mc-dynamic-mailer.php`: Core.
- `assets/`: UI styles.
- `includes/`:
  - `db-handler.php`: Gestiona la tabla SQL personalizada para logs.
  - `tracker.php`: Endpoint del píxel.
  - `sender.php`: Envío + Deduplicación.
- `admin/`:
  - `dashboard-page.php`: Tabla de leads con acciones (Ver, Marcar Respondido).

## Verificación

- Enviar correo y abrirlo (Estado cambia a "Abierto").
- Poner correo de Monday en BCC y verificar que cree el item.
