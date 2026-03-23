<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h1 class="wp-heading-inline">Mars Dynamic Mailer 🚀</h1>
    <hr class="wp-header-end">

    <style>
        /* Tailwind-ish utilities for WP Admin */
        .mdm-card { background: #fff; border: 1px solid #ccd0d4; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.04); max-width: 1000px; }
        .mdm-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .mdm-btn { background: #2271b1; color: #fff; border: none; padding: 10px 20px; cursor: pointer; font-size: 14px; border-radius: 4px; }
        .mdm-btn:hover { background: #135e96; }
        .mdm-btn.disabled { opacity: 0.6; cursor: not-allowed; }
        .mdm-input { width: 100%; padding: 8px; border: 1px solid #8c8f94; border-radius: 4px; margin-top: 5px; }
        .mdm-label { font-weight: 600; display: block; margin-bottom: 5px; color: #1d2327; }
        .mdm-progress { width: 100%; height: 20px; background: #f0f0f1; border-radius: 10px; overflow: hidden; margin-top: 20px; display: none; }
        .mdm-bar { height: 100%; background: #2271b1; width: 0%; transition: width 0.3s; }
        .mdm-log { max-height: 200px; overflow-y: auto; background: #f6f7f7; border: 1px solid #dcdcde; padding: 10px; margin-top: 10px; font-family: monospace; font-size: 12px; }
        .mdm-success { color: #00a32a; }
        .mdm-error { color: #d63638; }
        .mdm-warn { color: #dba617; }
    </style>

    <!-- STEP 1: CSV UPLOAD -->
    <div class="mdm-card">
        <h2>1. Origen de Datos (CSV)</h2>
        <p>Sube tu archivo .csv (Valores separados por comas o punto y coma).</p>
        <input type="file" id="csvInput" accept=".csv" class="mdm-input">
        <textarea id="rawData" rows="5" class="mdm-input" placeholder="O pega los datos aquí..." style="display:none;"></textarea>
    </div>

    <!-- STEP 2: MAPPING -->
    <div id="mappingArea" class="mdm-card" style="display:none;">
        <h2>2. Mapeo de Columnas Flexible</h2>
        <p>Selecciona qué columna corresponde a cada dato.</p>
        <div class="mdm-grid">
            <div>
                <label class="mdm-label">Email *</label>
                <select id="mapEmail" class="mdm-input"></select>
            </div>
            <div>
                <label class="mdm-label">Nombre *</label>
                <select id="mapName" class="mdm-input"></select>
            </div>
            <div>
                <label class="mdm-label">País</label>
                <select id="mapCountry" class="mdm-input"></select>
            </div>
            <div>
                <label class="mdm-label">Institución</label>
                <select id="mapInst" class="mdm-input"></select>
            </div>
            <div>
                <label class="mdm-label">Tipo (Filtro)</label>
                <select id="mapType" class="mdm-input"></select>
            </div>
        </div>
        
        <h3 style="margin-top:20px;">Filtros de Envío</h3>
        <label>
            <input type="checkbox" id="filterDuplicate" checked> 
            <strong>Evitar Duplicados:</strong> No enviar si el email ya existe en el historial.
        </label>
        <br>
        <div style="margin-top:10px;">
            <label class="mdm-label">Filtrar por Tipo (Opcional):</label>
            <input type="text" id="filterTypeVal" placeholder="Ej: Universidad (Dejar vacío para enviar a todos)" class="mdm-input" style="max-width:300px;">
        </div>
    </div>

    <!-- STEP 3: CONFIGURATION -->
    <div id="configArea" class="mdm-card" style="display:none;">
        <h2>3. Configuración del Correo</h2>
        
        <!-- VAR CHIPS -->
        <div class="mdm-note" style="margin-bottom:15px; background:#e3f2fd; border-left:4px solid #2196f3; padding:10px;">
            <p style="margin:0 0 5px 0;"><strong>Variables disponibles del CSV:</strong> (Haz clic para copiar)</p>
            <div id="varChips" style="display:flex; flex-wrap:wrap; gap:5px;"></div>
        </div>

        <div class="mdm-grid">
            <div>
                <label class="mdm-label">Remitente (From)</label>
                <input type="email" id="fromEmail" value="ventas@marschallenge.space" class="mdm-input">
            </div>
            <div>
                <label class="mdm-label">Copia Oculta (BCC - Jefe/CRM)</label>
                <input type="email" id="bccEmail" placeholder="jefe@mars.com" class="mdm-input">
            </div>
        </div>
        
        <label style="display:block; margin:15px 0;">
            <input type="checkbox" id="reqReceipt"> 
            Solicitar Confirmación de Lectura
        </label>

        <!-- TABS -->
        <h3 class="nav-tab-wrapper">
            <a href="#" class="nav-tab nav-tab-active" onclick="switchTab(event, 'ES')">Español (Default)</a>
            <a href="#" class="nav-tab" onclick="switchTab(event, 'PT')">Portugués</a>
            <a href="#" class="nav-tab" onclick="switchTab(event, 'EN')">Inglés</a>
        </h3>

        <!-- TEMPLATE ES -->
        <div id="tab-ES" class="tab-content" style="margin-top:15px;">
            <div style="background:#fff3cd; padding:5px; margin-bottom:5px; font-size:12px;">ℹ️ Se usará para países de habla hispana (Colombia, México, España, etc.)</div>
            <label class="mdm-label">Asunto (ES)</label>
            <input type="text" id="subj-ES" value="[Institucion] — Activación Mars Challenge 2026 como Institución Líder" class="mdm-input">
            <label class="mdm-label" style="margin-top:10px">Mensaje (ES)</label>
            <textarea id="msg-ES" rows="12" class="mdm-input">Estimada/o [Nombre],

Gracias por registrar a [Institucion] en Mars Challenge 2026 – Tierra. Es un gusto contar con su interés en formar parte del movimiento educativo que reúne a jóvenes y docentes de más de 20 países.

Para activar oficialmente la participación de la institución, solo necesitamos que elijan una de estas dos modalidades.

1. Premium
La institución organiza una actividad interna tipo hackathon (duración aproximada: 2 meses). Incluye una actividad interna de 2 días con sus estudiantes, certificación Mission 1 Mentor para todos los docentes y la selección del equipo ganador institucional. El beneficio principal es el acceso garantizado al Grand Jam Madrid – The Originals Internacional*. Además, en la fase nacional, participan en la primera etapa (presentación) y avanzan directamente a la segunda etapa nacional.

2. Fast Track
La institución no organiza actividad interna (duración aproximada: 1 mes). Incluye que la institución elija directamente un equipo, el desarrollo del proyecto por parte de ese equipo y la certificación de hasta 20 docentes como Mission 1 Mentor. El beneficio principal es que, si su proyecto clasifica, accede al Grand Jam Madrid – The Originals Internacional*.

Por qué es importante la Fase Nacional
En esta fase se define qué proyectos viajarán a The Originals Internacional y cuál será el CHAMP Nacional, que representará oficialmente al país en Madrid y competirá por el Premio Internacional NASA.

Inversión institucional
La participación en Mars Challenge implica una inversión institucional pequeña, que depende de la modalidad elegida. Con gusto le enviaremos el detalle correspondiente según la opción que prefieran.

Para continuar, simplemente responda:
“Queremos participar con Premium”
o
“Queremos participar con Fast Track”

Si tienen dudas y desean una explicación más sencilla, podemos agendar una videollamada; muchas veces resulta más simple y rápido aclarar todos los puntos en directo.

Una vez recibida su respuesta, activaremos el proceso de integración y le enviaremos toda la información necesaria.

Quedamos atentos y a su disposición. Gracias nuevamente por su interés en crecer junto al Mars Challenge.

Atentamente,
Adelino Sousa
Founder – Mars Challenge
No Planet B. Just a Better Plan.
You’re made of star-stuff. So hack the future.</textarea>
        </div>

        <!-- TEMPLATE PT -->
        <div id="tab-PT" class="tab-content" style="display:none; margin-top:15px;">
             <div style="background:#e3f2fd; padding:5px; margin-bottom:5px; font-size:12px;">ℹ️ Se usará para Brasil, Portugal, Angola, etc.</div>
            <label class="mdm-label">Asunto (PT)</label>
            <input type="text" id="subj-PT" value="[Institucion] — Ativação Mars Challenge 2026 como Instituição Líder" class="mdm-input">
            <label class="mdm-label" style="margin-top:10px">Mensaje (PT)</label>
            <textarea id="msg-PT" rows="12" class="mdm-input">Prezado(a) [Nombre],

Obrigado por registar a [Institucion] no Mars Challenge 2026 – Terra. É um prazer contar com o vosso interesse em fazer parte do movimento educativo que reúne jovens e docentes de mais de 20 países.

Para ativar oficialmente a participação da instituição, necessitamos apenas que escolham uma das seguintes modalidades.

1. Premium
A instituição organiza uma atividade interna tipo hackathon (duração aproximada: 2 meses). Inclui uma atividade interna de 2 dias com os estudantes, certificação Mission 1 Mentor para todos os docentes e a seleção da equipa vencedora institucional. O principal benefício é o acesso garantido ao Grand Jam Madrid – The Originals Internacional*. Além disso, na fase nacional, participam na primeira etapa (apresentação) e avançam diretamente para a segunda etapa nacional.

2. Fast Track
A instituição não organiza atividade interna (duração aproximada: 1 mês). Inclui a seleção direta de uma equipa pela instituição, o desenvolvimento do projeto por essa equipa e a certificação de até 20 docentes como Mission 1 Mentor. O principal benefício é que, caso o projeto seja classificado na fase nacional, terá acesso ao Grand Jam Madrid – The Originals Internacional*.

Porque é importante a Fase Nacional
Nesta fase define-se quais projetos viajarão para o The Originals Internacional e qual será o CHAMP Nacional, que representará oficialmente o país em Madrid e competirá pelo Prémio Internacional NASA.

Investimento institucional
A participação no Mars Challenge implica um investimento institucional reduzido, que depende da modalidade escolhida. Teremos todo o gosto em enviar o detalhe correspondente após nos indicarem a vossa preferência.

Para continuar, basta responder:
“Queremos participar com Premium”
ou
“Queremos participar com Fast Track”

Se tiverem dúvidas e preferirem uma explicação mais simples, podemos agendar uma videochamada; muitas vezes é a forma mais rápida e prática de esclarecer todos os pontos.

Assim que recebermos a vossa resposta, ativaremos o processo de integração e enviaremos toda a informação necessária.

Ficamos ao dispor. Muito obrigado, mais uma vez, pelo interesse em crescer juntamente com o Mars Challenge.

Com os melhores cumprimentos,
Adelino Sousa
Founder – Mars Challenge
No Planet B. Just a Better Plan.
You’re made of star-stuff. So hack the future.</textarea>
        </div>

        <!-- TEMPLATE EN -->
        <div id="tab-EN" class="tab-content" style="display:none; margin-top:15px;">
             <div style="background:#fce4ec; padding:5px; margin-bottom:5px; font-size:12px;">ℹ️ Se usará para USA, UK y el resto del mundo.</div>
            <label class="mdm-label">Asunto (EN)</label>
            <input type="text" id="subj-EN" value="[Institucion] — Mars Challenge 2026 Activation as Leading Institution" class="mdm-input">
            <label class="mdm-label" style="margin-top:10px">Mensaje (EN)</label>
            <textarea id="msg-EN" rows="12" class="mdm-input">Dear [Nombre],

Thank you for registering [Institucion] in Mars Challenge 2026 – Earth. It is a pleasure to count on your interest in being part of the educational movement that brings together youth and teachers from over 20 countries.

To officially activate the institution's participation, we only need you to choose one of these two modalities.

1. Premium
The institution organizes an internal hackathon-style activity (approximate duration: 2 months). Includes a 2-day internal activity with its students, Mission 1 Mentor certification for all teachers, and the selection of the winning institutional team. The main benefit is guaranteed access to the Grand Jam Madrid – The Originals International*. Additionally, in the national phase, they participate in the first stage (presentation) and advance directly to the second national stage.

2. Fast Track
The institution does not organize an internal activity (approximate duration: 1 month). Includes the direct selection of a team by the institution, the project development by that team, and the certification of up to 20 teachers as Mission 1 Mentor. The main benefit is that if their project qualifies in the national phase, it accesses the Grand Jam Madrid – The Originals Internacional*.

Why the National Phase is Important
In this phase, it is defined which projects will travel to The Originals International and who will be the National CHAMP, effectively representing the country in Madrid and competing for the NASA International Award.

Institutional Investment
Participation in Mars Challenge implies a small institutional investment, which depends on the chosen modality. We will gladly send you the corresponding details according to your preference.

To continue, simply reply:
“We want to participate with Premium”
or
“We want to participate with Fast Track”

If you have doubts and wish for a simpler explanation, we can schedule a video call; often it is simpler and faster to clarify all points live.

Once we receive your response, we will activate the integration process and send you all the necessary information.

We remain attentive and at your disposal. Thank you again for your interest in growing together with the Mars Challenge.

Sincerely,
Adelino Sousa
Founder – Mars Challenge
No Planet B. Just a Better Plan.
You’re made of star-stuff. So hack the future.</textarea>
        </div>

        <div style="margin-top:20px; text-align:right;">
            <button onclick="simulateSending()" class="mdm-btn" style="background:#f0ad4e; margin-right:10px;">🔍 SIMULAR ENVÍO</button>
            <button onclick="previewEmail()" class="mdm-btn" style="background:#0073aa; margin-right:10px;">👁️ VISTA PREVIA</button>
            <button id="btnStart" class="mdm-btn">🚀 INICIAR ENVÍO MASIVO</button>
        </div>
    </div>

    <!-- PREVIEW MODAL -->
    <div id="previewModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99999; align-items:center; justify-content:center;">
        <div style="background:#fff; width:90%; max-width:600px; padding:25px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.5); position:relative;">
            <h3 style="margin-top:0;">👁️ Vista Previa (Primer Registro)</h3>
            <p style="font-size:12px; color:#666;">Así se verá el correo para la primera persona de tu lista:</p>
            
            <div style="background:#f1f1f1; padding:15px; border-radius:5px; border:1px solid #ccc;">
                <p style="margin:5px 0;"><strong>De:</strong> <span id="prevFrom"></span></p>
                <p style="margin:5px 0;"><strong>Para:</strong> <span id="prevTo"></span></p>
                <p style="margin:5px 0;"><strong>Asunto:</strong> <strong id="prevSubj"></strong></p>
                <hr style="border:0; border-top:1px solid #ddd; margin:10px 0;">
                <div id="prevMsg" style="white-space:pre-wrap; font-family:sans-serif; max-height:300px; overflow-y:auto;"></div>
            </div>

            <div style="text-align:right; margin-top:20px;">
                <button onclick="document.getElementById('previewModal').style.display='none'" class="mdm-btn" style="background:#666;">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- STEP 4: PROGRESS (VISUAL CARD) -->
    <div id="progressArea" class="mdm-card" style="display:none;">
        <h2 style="margin-bottom:20px;">Procesando Envíos...</h2>
        
        <!-- CARD UI -->
        <div class="mdm-user-card" style="background:#23282d; color:#fff; padding:20px; border-radius:12px; margin-bottom:20px; display:flex; align-items:center; gap:20px; border:1px solid #444; box-shadow:0 4px 15px rgba(0,0,0,0.3);">
            <div style="width:60px; height:60px; border-radius:50%; background:linear-gradient(45deg, #00b09b, #96c93d); display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:24px; color:#fff; text-shadow:0 1px 2px rgba(0,0,0,0.3);">
                <span id="cardInitials">M</span>
            </div>
            <div style="flex:1;">
                <h3 id="cardName" style="margin:0; color:#fff; font-size:18px;">Preparando...</h3>
                <p id="cardInst" style="margin:5px 0; color:#aaa; font-size:14px;">...</p>
                <div style="display:flex; gap:10px; margin-top:5px; align-items:center;">
                    <span id="cardEmail" style="background:#444; padding:2px 8px; border-radius:4px; font-size:12px;">@</span>
                    <span id="cardLang" style="background:#0073aa; color:#fff; padding:2px 8px; border-radius:4px; font-size:12px; font-weight:bold;">ES</span>
                </div>
            </div>
            <div id="cardStatus" style="font-size:32px;">⏳</div>
        </div>

        <div class="mdm-progress"><div id="progressBar" class="mdm-bar"></div></div>
        <p>Procesado: <span id="progCount">0</span> / <span id="progTotal">0</span></p>
        <div id="processLog" class="mdm-log" style="max-height:150px; opacity:0.8;"></div>
    </div>

    <!-- STEP 5: HISTORY -->
    <div class="mdm-card">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <h2>📜 Historial de Envíos</h2>
            <div style="display:flex; gap:10px;">
                <button onclick="exportHistory()" class="mdm-btn" style="background:#4caf50;">📥 Exportar CSV</button>
                <button onclick="loadHistory()" class="mdm-btn" style="padding:5px 10px; font-size:12px;">🔄 Refrescar</button>
            </div>
        </div>
        
        <p style="margin-top:5px;"><strong>Total Enviados:</strong> <span id="histTotal">0</span> | <strong>Leídos:</strong> <span id="histOpen">0</span></p>
        
        <table class="wp-list-table widefat fixed striped" style="margin-top:10px;">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Enviado</th>
                    <th style="width:60px; text-align:center;">Acción</th>
                </tr>
            </thead>
            <tbody id="histBody">
                <tr><td colspan="5">Cargando...</td></tr>
            </tbody>
        </table>
    </div>

</div>

<script>
    const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
    let rawRows = [];
    let headers = [];

    // Auto-load history
    window.addEventListener('load', loadHistory);

    async function loadHistory() {
        const tbody = document.getElementById('histBody');
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Cargando...</td></tr>';
        
        const res = await fetch(ajaxUrl + '?action=mc_mailer_get_history');
        const json = await res.json();
        
        if (json.success) {
            const logs = json.data.logs;
            const stats = json.data.stats;
            
            document.getElementById('histTotal').innerText = stats.total;
            document.getElementById('histOpen').innerText = stats.opened;
            
            tbody.innerHTML = '';
            if (logs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Sin envíos aún.</td></tr>';
                return;
            }

            // Store logs global for export
            window.lastLogs = logs;

            logs.forEach(row => {
               const status = row.is_opened == 1 
                   ? '<span class="mdm-success" style="font-weight:bold;">✅ Leído</span>' 
                   : '<span class="mdm-warn">Enviado</span>';
               
               // Use email as ID fallback if DB ID missing (though logic is fixed)
               const safeId = row.id || 0; 

               tbody.innerHTML += `
                   <tr>
                       <td>${row.email}</td>
                       <td>${row.recipient_name || '-'}</td>
                       <td>${status}</td>
                       <td>${row.sent_at}</td>
                       <td style="text-align:center;">
                           <button onclick="deleteLog('${row.email}', ${safeId})" class="button-link-delete" style="color:#a00; font-weight:bold; text-decoration:none;">&times;</button>
                       </td>
                   </tr>
               `;
            });
        }
    }


    // Lang Data
    const countriesMap = {
        'ES': ["España", "México", "Colombia", "Argentina", "Chile", "Perú", "Venezuela", "Ecuador", "Guatemala", "Cuba", "Bolivia", "República Dominicana", "Honduras", "El Salvador", "Nicaragua", "Paraguay", "Costa Rica", "Uruguay", "Puerto Rico", "Panamá", 
               "ES", "CO", "MX", "AR", "CL", "PE", "VE", "EC", "GT", "CU", "BO", "DO", "HN", "SV", "NI", "PY", "CR", "UY", "PR", "PA"],
        'PT': ["Portugal", "Brasil", "Angola", "Mozambique", "PT", "BR", "AO", "MZ"],
        'EN': ["United States", "United Kingdom", "Canada", "Australia", "Ireland", "New Zealand", "US", "USA", "UK", "GB", "CA", "AU", "IE", "NZ"]
    };

    function switchTab(e, lang) {
        e.preventDefault();
        document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('nav-tab-active'));
        e.target.classList.add('nav-tab-active');
        document.querySelectorAll('.tab-content').forEach(d => d.style.display = 'none');
        document.getElementById('tab-'+lang).style.display = 'block';
    }

    // --- 1. CSV PARSER ---
    document.getElementById('csvInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(!file) return;
        const reader = new FileReader();
        reader.onload = function(e) { parseCSV(e.target.result); };
        reader.readAsText(file);
    });

    function parseCSV(text) {
        let cleanText = text.replace(/"Persona de contacto\*\s*[\r\n]+"/g, '"Persona de contacto*"');
        cleanText = cleanText.replace(/\r\n/g, '\n').replace(/\r/g, '\n');
        const lines = cleanText.trim().split('\n');
        
        if (lines.length < 2) return alert('CSV inválido o vacío');

        const firstLine = lines[0];
        let delimiter = ',';
        if ((firstLine.match(/\t/g)||[]).length > (firstLine.match(/,/g)||[]).length) delimiter = '\t';
        else if ((firstLine.match(/;/g)||[]).length > (firstLine.match(/,/g)||[]).length) delimiter = ';';

        headers = lines[0].split(delimiter).map(h => h.trim().replace(/^"|"$/g, ''));
        
        rawRows = lines.slice(1).map(line => {
            if(!line.trim()) return null;
             const values = line.split(delimiter); 
             let obj = {};
             headers.forEach((h, i) => obj[h] = values[i] ? values[i].trim().replace(/^"|"$/g, '') : '');
             return obj;
        }).filter(r => r);

        initMapping();
    }

    // --- 2. MAPPING & CHIPS ---
    function initMapping() {
        const selects = ['mapEmail', 'mapName', 'mapCountry', 'mapInst', 'mapType'];
        const chipCont = document.getElementById('varChips');
        chipCont.innerHTML = ''; // Clear chips

        // Populate Selects & Chips
        headers.forEach(h => {
             // Chips
             const chip = document.createElement('span');
             chip.innerText = `[${h}]`;
             chip.style.cssText = 'background:#fff; border:1px solid #bbb; padding:2px 8px; border-radius:10px; font-size:11px; cursor:pointer; color:#333;';
             chip.onclick = () => {
                 navigator.clipboard.writeText(`[${h}]`);
                 chip.style.background = '#4caf50';
                 chip.style.color = '#fff';
                 setTimeout(() => { chip.style.background = '#fff'; chip.style.color = '#333'; }, 500);
             };
             chipCont.appendChild(chip);
        });
        
        selects.forEach(id => {
            const el = document.getElementById(id);
            el.innerHTML = '<option value="">-- Seleccionar --</option>';
            headers.forEach(h => {
                const opt = document.createElement('option');
                opt.value = h;
                opt.innerText = h;
                if(id==='mapEmail' && h.toLowerCase().includes('email')) opt.selected = true;
                if(id==='mapName' && (h.toLowerCase().includes('nombre') || h.toLowerCase().includes('contact'))) opt.selected = true;
                if(id==='mapCountry' && h.toLowerCase().includes('pais')) opt.selected = true;
                if(id==='mapInst' && h.toLowerCase().includes('institu')) opt.selected = true;
                el.appendChild(opt);
            });
        });

        document.getElementById('mappingArea').style.display = 'block';
        document.getElementById('configArea').style.display = 'block';
    }

    // --- 3. PREVIEW & SENDER ---
    
    function previewEmail() {
        if(!rawRows || rawRows.length === 0) return alert("Carga un CSV primero");
        
        const colEmail = document.getElementById('mapEmail').value;
        const colCountry = document.getElementById('mapCountry').value;
        if(!colEmail) return alert("Mapea el campo Email primero");
        
        // Find first matching row
        let row = null;
        
        const filterTypeVal = document.getElementById('filterTypeVal').value.toLowerCase();
        const colType = document.getElementById('mapType').value;

        if (!filterTypeVal || !colType) {
            row = rawRows[0]; // No filter, take first
        } else {
            // Search for first match
            for(let i=0; i<rawRows.length; i++) {
                const r = rawRows[i];
                if (r[colType] && r[colType].toLowerCase().includes(filterTypeVal)) {
                    row = r;
                    break;
                }
            }
        }

        if (!row) return alert("No hay ninguna fila que coincida con tu filtro.");
        
        // Logic duplicated from sender loop (ideal: refactor to shared func, but inline is safer for quick fix)
        let lang = 'ES';
        if (colCountry && row[colCountry]) {
            const c = row[colCountry].toLowerCase();
            if (countriesMap.PT.some(x => c.includes(x.toLowerCase()))) lang = 'PT';
            else if (countriesMap.EN.some(x => c.includes(x.toLowerCase()))) lang = 'EN';
        }

        const name = document.getElementById('mapName').value ? row[document.getElementById('mapName').value] : '';
        const inst = document.getElementById('mapInst').value ? row[document.getElementById('mapInst').value] : '';
        const email = row[colEmail];
        
        let subject = document.getElementById('subj-'+lang).value;
        let message = document.getElementById('msg-'+lang).value;

        // Apply Replacements
        subject = subject.replace(/\[Institucion\]/gi, inst).replace(/\[Nombre\]/gi, name);
        message = message.replace(/\[Institucion\]/gi, inst).replace(/\[Nombre\]/gi, name);
        headers.forEach(h => {
            const val = row[h] || '';
            subject = subject.split(`[${h}]`).join(val);
            message = message.split(`[${h}]`).join(val);
        });

        // Show Modal
        document.getElementById('prevFrom').innerText = document.getElementById('fromEmail').value;
        document.getElementById('prevTo').innerText = `${name} <${email}> [Idioma: ${lang}]`;
        document.getElementById('prevSubj').innerText = subject;
        document.getElementById('prevMsg').innerHTML = message.replace(/\n/g, '<br>'); // Using innerHTML to respect <br> if any, though preview might want text content
        document.getElementById('previewModal').style.display = 'flex';
    }

    function simulateSending() {
        if(!rawRows || rawRows.length === 0) return alert("Carga un CSV primero");
        
        const colEmail = document.getElementById('mapEmail').value;
        if(!colEmail) return alert("Mapea el campo Email primero");

        const filterTypeVal = document.getElementById('filterTypeVal').value.toLowerCase();
        const colType = document.getElementById('mapType').value;
        const total = rawRows.length;
        
        let validCount = 0;
        let filteredCount = 0;
        let skippedCount = 0;

        const processedEmails = new Set();

        // Simulation Loop
        for (let i = 0; i < total; i++) {
            const row = rawRows[i];
            const email = (row[colEmail] || '').trim();

            // 1. Basic Validation
            if (!email || !email.includes('@')) {
                skippedCount++;
                continue;
            }

            // 2. In-Batch Duplicate Check
            if (processedEmails.has(email)) {
               skippedCount++;
               continue; 
            }
            processedEmails.add(email);

            validCount++;

            // 3. Filter Logic
            if (filterTypeVal && colType && row[colType]) {
                if (!row[colType].toLowerCase().includes(filterTypeVal)) {
                     continue; // Filtered out
                }
            }
            
            // If we are here, it matches
            filteredCount++;
        }

        // Report
        let report = `📊 **RESULTADO DE SIMULACIÓN**\n\n`;
        report += `Total Filas CSV: ${total}\n`;
        report += `Emails Válidos: ${validCount}\n`;
        if(filterTypeVal) {
            report += `Filtro Activo: "${filterTypeVal}" en columna "${colType}"\n`;
            report += `✅ **Coincidencias (Se enviarían): ${filteredCount}**\n`;
            report += `🚫 Filtrados/Ignorados: ${validCount - filteredCount}\n`;
        } else {
            report += `✅ **Total a Enviar (Sin filtros): ${filteredCount}**\n`;
        }
        
        alert(report);
    }

    document.getElementById('btnStart').addEventListener('click', async function() {
        const colEmail = document.getElementById('mapEmail').value;
        const colCountry = document.getElementById('mapCountry').value;
        if(!colEmail) return alert("Debes mapear la columna Email");

        // UI Setup
        document.getElementById('progressArea').style.display = 'block';
        const btn = document.getElementById('btnStart');
        btn.innerText = "ENVIANDO...";
        btn.disabled = true;

        const total = rawRows.length;
        document.getElementById('progTotal').innerText = total;
        
        const filterTypeVal = document.getElementById('filterTypeVal').value.toLowerCase();
        const colType = document.getElementById('mapType').value;
        const processedEmails = new Set();

        // Process Loop
        for (let i = 0; i < total; i++) {
            const row = rawRows[i];
            const email = (row[colEmail] || '').trim();

            // --- FILTERING ---
            if (!email || !email.includes('@')) { log(`Fila ${i+1}: Email inválido`, 'warn'); continue; }
            
            // In-Batch Check
            if (processedEmails.has(email)) {
                log(`Fila ${i+1}: ${email} (Duplicado en CSV)`, 'warn');
                continue;
            }
            processedEmails.add(email);

            if (filterTypeVal && colType && row[colType]) {
                if (!row[colType].toLowerCase().includes(filterTypeVal)) continue; 
            }

            // --- DATA PREP ---
            let lang = 'ES';
            if (colCountry && row[colCountry]) {
                const c = row[colCountry].toLowerCase();
                if (countriesMap.PT.some(x => c.includes(x.toLowerCase()))) lang = 'PT';
                else if (countriesMap.EN.some(x => c.includes(x.toLowerCase()))) lang = 'EN';
            }

            const name = document.getElementById('mapName').value ? row[document.getElementById('mapName').value] : '';
            const inst = document.getElementById('mapInst').value ? row[document.getElementById('mapInst').value] : '';
            const country = colCountry ? row[colCountry] : '';

            // --- UPDATE CARD ---
            document.getElementById('cardInitials').innerText = name ? name.charAt(0).toUpperCase() : '?';
            document.getElementById('cardName').innerText = name || email;
            document.getElementById('cardInst').innerText = inst || 'Sin Institución';
            document.getElementById('cardEmail').innerText = email;
            document.getElementById('cardLang').innerText = lang;
            document.getElementById('cardStatus').innerText = '⏳';

            // --- TEMPLATE PARSING ---
            const tplSubj = document.getElementById('subj-'+lang).value;
            const tplMsg = document.getElementById('msg-'+lang).value;
            
            let subject = tplSubj;
            let message = tplMsg;
            
            // Priority Replacements
            subject = subject.replace(/\[Institucion\]/gi, inst).replace(/\[Nombre\]/gi, name);
            message = message.replace(/\[Institucion\]/gi, inst).replace(/\[Nombre\]/gi, name);

            // Generic CSV Replacements
            headers.forEach(h => {
                const val = row[h] || '';
                // Simple textual replacement for any [HeaderName]
                subject = subject.split(`[${h}]`).join(val);
                message = message.split(`[${h}]`).join(val);
            });
            message = message.replace(/\n/g, '<br>');

            // --- SENDING ---
            const formData = new FormData();
            formData.append('action', 'mc_mailer_send');
            formData.append('email', email);
            formData.append('name', name);
            formData.append('country', country);
            formData.append('subject', subject);
            formData.append('message', message);
            formData.append('from_email', document.getElementById('fromEmail').value);
            formData.append('bcc_email', document.getElementById('bccEmail').value);
            formData.append('request_receipt', document.getElementById('reqReceipt').checked);

            try {
                const res = await fetch(ajaxUrl, { method: 'POST', body: formData });
                const json = await res.json();
                
                if (json.success) {
                    log(`✅ [${lang}] ${email}: Enviado`, 'success');
                    document.getElementById('cardStatus').innerText = '✅';
                } else {
                    const msg = json.data ? json.data.message : 'Error desconocido';
                    if (msg.includes('Duplicado')) {
                        log(`ℹ️ ${email}: Omitido (Duplicado)`, 'warn');
                        document.getElementById('cardStatus').innerText = '🔁';
                    }
                    else {
                        log(`❌ ${email}: ${msg}`, 'error');
                        document.getElementById('cardStatus').innerText = '❌';
                    }
                }
            } catch (err) {
                log(`❌ ${email}: Fallo de red`, 'error');
                document.getElementById('cardStatus').innerText = '⚠️';
            }

            // Update Progress
            const pct = Math.round(((i + 1) / total) * 100);
            document.getElementById('progressBar').style.width = pct + '%';
            document.getElementById('progCount').innerText = i + 1;
            
            // Throttle
            await new Promise(r => setTimeout(r, 600)); 
        }

        btn.innerText = "PROCESO TERMINADO";
        alert("Envío masivo finalizado.");
        loadHistory(); // Refresh table
    });


    function log(msg, type) {
        const div = document.createElement('div');
        div.className = `mdm-${type}`;
        div.innerText = msg;
        document.getElementById('processLog').prepend(div);
    }

    // --- HISTORY ---
    async function deleteLog(email, id) {
        if(!confirm(`¿Eliminar registro de ${email}?`)) return;
        
        const formData = new FormData();
        formData.append('action', 'mc_mailer_delete_log');
        formData.append('id', id);
        
        await fetch(ajaxUrl, { method: 'POST', body: formData });
        loadHistory();
    }

    function exportHistory() {
        if(!window.lastLogs || window.lastLogs.length === 0) return alert("Nada que exportar");
        
        let csv = "Email,Nombre,Pais,Enviado,Estado\n";
        window.lastLogs.forEach(r => {
            const st = r.is_opened == 1 ? "Leido" : "Enviado";
            csv += `"${r.email}","${r.recipient_name}","${r.country}","${r.sent_at}","${st}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `reporte_envios_${new Date().toISOString().slice(0,10)}.csv`;
        a.click();
    }
</script>
