<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinePOS - Cinerama</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Saira+Condensed:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #f0f0f5;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --bg-card-hover: #f8f7ff;
            --bg-input: #f4f3f8;
            --border: #e2e0ef;
            --border-active: rgba(180, 130, 20, 0.4);
            --gold: #c8940a;
            --gold-dim: rgba(200, 148, 10, 0.4);
            --gold-light: #daa520;
            --gold-bg: rgba(200, 148, 10, 0.08);
            --rose: #d6264a;
            --rose-dim: rgba(214, 38, 74, 0.12);
            --emerald: #16a34a;
            --sky: #2563eb;
            --text: #1a1a2e;
            --text-secondary: #5c5a72;
            --text-muted: #9896af;
            --danger: #dc2626;
            --danger-dim: rgba(220, 38, 38, 0.08);
            --danger-bg: rgba(220, 38, 38, 0.06);
            --radius: 14px;
            --radius-sm: 10px;
            --radius-xs: 8px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text);
            overflow-x: hidden;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ====== HIDE LOGIN (not used in this page) ====== */
        .login-container {
            display: none;
        }

        /* ====== POS LAYOUT ====== */
        .pos-container {
            display: none;
            min-height: 100vh;
            background: var(--bg-primary);
        }

        .pos-container.active {
            display: flex;
            flex-direction: column;
        }

        /* ====== HEADER ====== */
        .pos-header {
            background: #ffffff;
            border-bottom: 2px solid var(--gold);
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .pos-title {
            font-family: 'Saira Condensed', sans-serif;
            font-size: 28px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--gold);
        }

        .pos-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .user-role {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .logout-btn {
            padding: 8px 18px;
            background: transparent;
            border: 1px solid var(--rose-dim);
            border-radius: var(--radius-xs);
            color: var(--rose);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--transition);
        }

        .logout-btn:hover {
            background: var(--rose);
            color: #fff;
            border-color: var(--rose);
            box-shadow: 0 4px 16px rgba(214, 38, 74, 0.2);
        }

        /* ====== 3 COLUMN GRID ====== */
        .pos-main {
            display: grid;
            grid-template-columns: 320px 1fr 340px;
            height: calc(100vh - 60px);
            gap: 1px;
            background: var(--border);
        }

        @media (max-width: 1200px) {
            .pos-main {
                grid-template-columns: 1fr;
                height: auto;
            }
        }

        .pos-section {
            background: var(--bg-secondary);
            padding: 24px;
            overflow-y: auto;
        }

        .section-title {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            margin-bottom: 20px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .section-title i {
            color: var(--gold);
            font-size: 14px;
        }

        /* ====== LEFT PANEL - MOVIES ====== */
        .movie-card {
            background: var(--bg-card);
            padding: 16px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            position: relative;
            box-shadow: var(--shadow-sm);
        }

        .movie-card:hover {
            border-color: var(--border-active);
            background: var(--bg-card-hover);
            transform: translateX(3px);
            box-shadow: var(--shadow-md);
        }

        .movie-card.selected {
            border-color: var(--gold);
            background: var(--gold-bg);
            box-shadow: 0 0 0 1px var(--gold-dim), var(--shadow-md);
        }

        .movie-card.selected::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gold);
            border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        }

        .movie-title {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
            color: var(--text);
        }

        .movie-info {
            font-size: 11px;
            color: var(--text-secondary);
            margin-bottom: 12px;
            font-weight: 400;
        }

        .showtimes {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .showtime-btn {
            padding: 12px 18px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            min-width: 80px;
            text-align: center;
        }

        .showtime-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: var(--gold-bg);
        }

        .showtime-btn.selected {
            background: var(--gold);
            color: #fff;
            border-color: var(--gold);
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(200, 148, 10, 0.3);
        }

        /* ====== CENTER PANEL - SEAT MAP ====== */
        .center-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px 20px;
            background: var(--bg-primary);
            position: relative;
        }

        .screen {
            width: 70%;
            max-width: 500px;
            height: 6px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin-bottom: 8px;
            border-radius: 0 0 50% 50%;
            position: relative;
        }

        .screen::after {
            content: 'PANTALLA';
            position: absolute;
            bottom: -22px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 4px;
            color: var(--text-muted);
            text-transform: uppercase;
            white-space: nowrap;
        }

        .seat-map {
            display: grid;
            gap: 5px;
            margin-top: 36px;
            padding: 10px;
        }

        .seat-row {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .row-label {
            width: 28px;
            text-align: center;
            font-weight: 700;
            color: var(--text-muted);
            font-size: 11px;
            font-family: 'Inter', sans-serif;
        }

        .seat {
            width: 42px;
            height: 42px;
            background-image: url('assets/images/butacas/butacaVacia.gif');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 950;
            color: #000;
            /* Black for green seats */
            text-shadow: 0 0 2px #fff, 0 0 4px #fff;
            /* Explicit white glow */
            border: none;
        }

        .seat.occupied {
            background-image: url('assets/images/butacas/butacaOcupada.gif');
            cursor: not-allowed;
            color: #fff;
            /* White for blue seats */
            text-shadow: 0 0 3px #000;
        }

        .seat.selected {
            background-image: url('assets/images/butacas/miButaca.gif');
            color: #ca1515;
            /* Red as requested */
            font-weight: 950;
            text-shadow: 0 0 3px #fff;
            filter: drop-shadow(0 2px 8px rgba(200, 148, 10, 0.4));
        }

        .seat:hover:not(.occupied) {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .seat-legend {
            display: flex;
            gap: 24px;
            margin-top: 32px;
            font-size: 13px;
            /* Slightly larger legend text */
            color: var(--text);
            font-weight: 700;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        /* ====== RIGHT PANEL - TARIFF & SUMMARY ====== */
        .tariff-item {
            background: var(--bg-card);
            padding: 16px;
            margin-bottom: 10px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .tariff-item.disabled {
            opacity: 0.35;
            pointer-events: none;
        }

        .tariff-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .tariff-name {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .tariff-price {
            font-size: 15px;
            font-weight: 800;
            color: var(--gold);
        }

        .tariff-qty {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover:not(:disabled) {
            background: var(--gold);
            color: #fff;
            border-color: var(--gold);
        }

        .qty-btn:disabled {
            opacity: 0.25;
            cursor: not-allowed;
        }

        .qty-display {
            width: 40px;
            text-align: center;
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
        }

        /* Summary */
        .summary {
            background: #faf9ff;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .summary-row.total {
            font-size: 22px;
            font-weight: 800;
            color: var(--gold);
            border-top: 2px solid var(--border);
            padding-top: 14px;
            margin-top: 14px;
            margin-bottom: 0;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-action {
            padding: 14px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: var(--transition);
            border-radius: var(--radius-xs);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-clear {
            background: transparent;
            border: 1px solid rgba(220, 38, 38, 0.25);
            color: var(--danger);
        }

        .btn-clear:hover {
            background: var(--danger-bg);
            border-color: var(--danger);
        }

        .btn-pay {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #fff;
            box-shadow: 0 4px 14px rgba(200, 148, 10, 0.25);
        }

        .btn-pay:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(200, 148, 10, 0.35);
        }

        .btn-pay:disabled {
            opacity: 0.3;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* ====== MODALS ====== */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: #ffffff;
            padding: 36px;
            max-width: 500px;
            width: 92%;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            position: relative;
            box-shadow: var(--shadow-lg);
            animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-title {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 24px;
            color: var(--text);
        }

        .payment-options,
        .document-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }

        .option-btn {
            padding: 20px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .option-btn:hover {
            border-color: var(--gold);
            background: var(--gold-bg);
            color: var(--text);
        }

        .option-btn.selected {
            background: var(--gold-bg);
            color: var(--gold);
            border-color: var(--gold);
            box-shadow: 0 0 0 1px var(--gold-dim);
            font-weight: 700;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
        }

        .btn-modal {
            flex: 1;
            padding: 14px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: var(--transition);
            border-radius: var(--radius-xs);
        }

        .btn-cancel {
            background: var(--bg-input);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-cancel:hover {
            border-color: var(--text-muted);
            color: var(--text);
        }

        .btn-confirm {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #fff;
        }

        .btn-confirm:hover {
            box-shadow: 0 4px 16px rgba(200, 148, 10, 0.3);
        }

        /* ====== TICKET ====== */
        .ticket {
            background: #fff;
            color: #1a1a2e;
            padding: 28px;
            max-width: 380px;
            margin: 0 auto;
            font-family: 'Inter', sans-serif;
            border-radius: var(--radius-sm);
        }

        .ticket-header {
            text-align: center;
            border-bottom: 2px dashed #d1d5db;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }

        .ticket-cinema {
            font-family: 'Saira Condensed', sans-serif;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #1a1a2e;
        }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            margin: 6px 0;
            font-size: 12px;
            color: #4b5563;
        }

        .ticket-row.large {
            font-size: 14px;
            font-weight: 700;
            margin: 12px 0;
            color: #1a1a2e;
        }

        .ticket-footer {
            text-align: center;
            border-top: 2px dashed #d1d5db;
            padding-top: 14px;
            margin-top: 14px;
            font-size: 10px;
            color: #9ca3af;
        }

        .ticket-barcode {
            text-align: center;
            font-size: 18px;
            letter-spacing: 2px;
            margin: 14px 0;
            font-family: 'Courier New', monospace;
            color: #1a1a2e;
        }

        .btn-print {
            width: 100%;
            padding: 14px;
            background: var(--emerald);
            border: none;
            border-radius: var(--radius-xs);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            margin-top: 16px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-print:hover {
            box-shadow: 0 4px 16px rgba(22, 163, 74, 0.3);
        }

        /* ====== CONTRIB MODAL INLINE STYLES OVERRIDE ====== */
        #contribModal label {
            font-family: 'Inter', sans-serif !important;
            color: var(--text-muted) !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            letter-spacing: 1.5px !important;
            margin-bottom: 6px !important;
            display: block !important;
        }

        #contribModal input,
        #contribModal select {
            font-family: 'Inter', sans-serif !important;
            border-radius: var(--radius-xs) !important;
            background: var(--bg-input) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
            padding: 10px 14px !important;
            font-size: 13px !important;
            transition: var(--transition) !important;
        }

        #contribModal input:focus,
        #contribModal select:focus {
            outline: none !important;
            border-color: var(--gold) !important;
            box-shadow: 0 0 0 3px rgba(200, 148, 10, 0.1) !important;
        }

        /* ====== SCROLLBAR ====== */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.12);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(200, 148, 10, 0.4);
        }

        /* ====== NO SELECTION MESSAGE ====== */
        #noSelectionMessage {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            min-height: 300px;
        }

        #noSelectionMessage i {
            font-size: 48px;
            color: var(--text-muted);
            opacity: 0.4;
        }
    </style>
</head>

<body>


    <!-- MAIN POS INTERFACE -->
    <div class="pos-container active" id="posScreen">
        <div class="pos-header">
            <div class="pos-title">CINERAMA</div>
            <div class="pos-user">
                <div class="user-info">
                    <div class="user-name" id="currentUser">Cajero</div>
                    <div class="user-role">Punto de Venta #01</div>
                </div>
                <button class="logout-btn" id="logoutBtn"><i class="fas fa-right-from-bracket"></i> Salir</button>
            </div>
        </div>

        <div class="pos-main">
            <!-- LEFT PANEL: MOVIES -->
            <div class="pos-section">
                <div class="section-title"><i class="fas fa-film"></i> Cartelera</div>
                <div id="moviesList"></div>
            </div>

            <!-- CENTER PANEL: SEAT MAP -->
            <div class="pos-section center-panel">
                <div class="section-title"><i class="fas fa-couch"></i> Selección de Asientos</div>
                <div id="seatMapContainer" style="display: none;">
                    <div class="screen"></div>
                    <div class="seat-map" id="seatMap"></div>
                    <div class="seat-legend">
                        <div class="legend-item">
                            <img src="assets/images/butacas/butacaVacia.gif" class="legend-icon" alt="Disponible">
                            <span>Disponible</span>
                        </div>
                        <div class="legend-item">
                            <img src="assets/images/butacas/miButaca.gif" class="legend-icon" alt="Seleccionado">
                            <span>Seleccionado</span>
                        </div>
                        <div class="legend-item">
                            <img src="assets/images/butacas/butacaOcupada.gif" class="legend-icon" alt="Ocupado">
                            <span>Ocupado</span>
                        </div>
                    </div>
                </div>
                <div id="noSelectionMessage">
                    <i class="fas fa-ticket"></i>
                    <span style="color: var(--text-muted); font-size: 13px; font-weight: 500;">Seleccione una película y horario</span>
                </div>
            </div>

            <!-- RIGHT PANEL: TARIFF & SUMMARY -->
            <div class="pos-section">
                <div class="section-title"><i class="fas fa-file-invoice"></i> Comprobante y Cliente</div>

                <div style="margin-bottom: 15px;">
                    <label style="color: var(--text-muted); font-size:10px; text-transform:uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 5px;">Tipo de Comprobante</label>
                    <select id="sideDocumentType" class="option-btn" style="width: 100%; text-align: left; padding: 10px 15px; height: auto; border-radius: var(--radius-xs);">
                        <option value="boleta">BOLETA</option>
                        <option value="factura">FACTURA</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: var(--text-muted); font-size:10px; text-transform:uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 5px;">RUC / DNI del Cliente</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="sideClientDoc" placeholder="Buscar documento..." style="flex: 1; padding: 10px; background: var(--bg-input); border: 1px solid var(--border); border-radius: var(--radius-xs); font-family: 'Inter', sans-serif; font-size: 13px;">
                        <button class="option-btn" id="sideSearchBtn" style="padding: 10px 15px; border-radius: var(--radius-xs);"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <div id="sideClientInfo" style="display: none; padding: 10px; background: var(--gold-bg); border: 1px solid var(--gold-dim); border-radius: var(--radius-xs); margin-top: 10px;">
                    <div id="sideClientName" style="font-size: 11px; font-weight: 700; color: var(--gold); word-break: break-all;"></div>
                    <div id="sideClientAddress" style="font-size: 10px; color: var(--text-secondary); margin-top: 4px;"></div>
                </div>

                <div class="section-title" style="margin-top: 25px;"><i class="fas fa-tags"></i> Tarifas</div>
                <div id="tariffList"></div>

                <div class="summary">
                    <div class="summary-row">
                        <span>Asientos:</span>
                        <span id="seatCount">0</span>
                    </div>
                    <div class="summary-row">
                        <span>Entradas:</span>
                        <span id="ticketCount">0</span>
                    </div>
                    <div class="summary-row total">
                        <span>TOTAL:</span>
                        <span id="totalAmount">S/ 0.00</span>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn-action btn-clear" id="clearBtn">
                        <i class="fas fa-rotate-left"></i> Liberar Asientos
                    </button>
                    <button class="btn-action btn-pay" id="payBtn" disabled>
                        <i class="fas fa-credit-card"></i> Procesar Pago
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- PAYMENT MODAL -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-title">Método de Pago</div>
            <div class="payment-options">
                <button class="option-btn payment-option" data-payment="cash">
                    <i class="fas fa-money-bill-wave"></i> Efectivo
                </button>
                <button class="option-btn payment-option" data-payment="card">
                    <i class="fas fa-credit-card"></i> Tarjeta
                </button>
            </div>

            <div class="modal-title" style="font-size: 24px; margin-top: 20px;">Tipo de Documento</div>
            <div class="document-options">
                <button class="option-btn document-option" data-document="boleta">
                    <i class="fas fa-receipt"></i> Boleta
                </button>
                <button class="option-btn document-option" data-document="factura">
                    <i class="fas fa-file-invoice"></i> Factura
                </button>
            </div>

            <div class="modal-actions">
                <button class="btn-modal btn-cancel" id="cancelPayment">Cancelar</button>
                <button class="btn-modal btn-confirm" id="confirmPayment">Confirmar Venta</button>
            </div>
        </div>
    </div>

    <div class="modal" id="contribModal">
        <div class="modal-content">
            <div class="modal-title">Datos del Contribuyente</div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div>
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">Tipo Documento</label>
                    <select id="contribTipo" style="width:100%; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);">
                        <option value="RUC">RUC</option>
                        <option value="DNI">DNI</option>
                        <option value="OTROS">OTROS</option>
                    </select>
                </div>
                <div>
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">Número</label>
                    <div style="display:flex; gap:8px;">
                        <input id="contribNum" style="flex:1; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);" />
                        <button class="option-btn" id="contribSearchBtn" style="padding:10px 15px;"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div>
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">API Key (RUC)</label>
                    <input id="contribApiKey" placeholder="5000" style="width:100%; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);" />
                </div>
                <div></div>
                <div style="grid-column: 1 / span 2;">
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">Nombre / Razón Social</label>
                    <input id="contribNombre" style="width:100%; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);" />
                </div>
                <div style="grid-column: 1 / span 2;">
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">Dirección</label>
                    <input id="contribDireccion" style="width:100%; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);" />
                </div>
                <div>
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">Distrito</label>
                    <input id="contribDistrito" style="width:100%; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);" />
                </div>
                <div>
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">Provincia</label>
                    <input id="contribProvincia" style="width:100%; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);" />
                </div>
                <div>
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">Departamento</label>
                    <input id="contribDepartamento" style="width:100%; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);" />
                </div>
                <div>
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">Correo</label>
                    <input id="contribCorreo" style="width:100%; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);" />
                </div>
                <div>
                    <label style="color: var(--secondary); font-size:11px; text-transform:uppercase;">Teléfono</label>
                    <input id="contribTelefono" style="width:100%; padding:10px; background:var(--dark); border:2px solid var(--dark-lighter); color:var(--text);" />
                </div>
            </div>
            <div class="modal-actions" style="margin-top:15px;">
                <button class="btn-modal btn-cancel" id="contribCancel">Cerrar</button>
                <button class="btn-modal btn-confirm" id="contribUse">Usar Datos</button>
            </div>
        </div>
    </div>

    <!-- TICKET MODAL -->
    <div class="modal" id="ticketModal">
        <div class="modal-content" style="max-width: 500px;">
            <div id="ticketContent"></div>
            <button class="btn-print" id="printBtn"><i class="fas fa-print"></i> Imprimir Ticket</button>
            <button class="btn-modal btn-confirm" id="newSaleBtn" style="width: 100%; margin-top: 15px;">
                Nueva Venta
            </button>
        </div>
    </div>

    <script>
        let moviesData = [];
        let tariffs = [];
        let selectedCartelera = null;
        let selectedHora = null;
        let selectedFuncion = null;
        let selectedSala = null;
        let selectedSeats = [];
        let occupiedSeats = [];
        let seatLayout = {};
        let tariffQuantities = {};
        let paymentMethod = null;
        let documentType = 'boleta';
        let currentUser = '<?php echo $_SESSION["id"]; ?>';
        let currentEmpresa = '<?php echo $_SESSION["id_local"]; ?>';
        let contribData = null;
        let currentSerie = '';
        let currentNumber = '';

        // Side panel elements
        const sideDocType = document.getElementById('sideDocumentType');
        const sideDocInput = document.getElementById('sideClientDoc');
        const sideSearchBtn = document.getElementById('sideSearchBtn');
        const sideClientInfo = document.getElementById('sideClientInfo');
        const sideClientName = document.getElementById('sideClientName');
        const sideClientAddress = document.getElementById('sideClientAddress');

        function initializePOS() {
            fetchMovies();
            fetchTariffs();
            fetchSeries();
            setupEventListeners();
        }

        function fetchSeries() {
            const cod = (documentType === 'boleta') ? '03' : '01';
            $.ajax({
                url: 'assets/ajax/pos.php?action=searchSerie',
                method: 'POST',
                data: {
                    action: 'searchSerie',
                    cliente: cod,
                    usuario: currentUser,
                    empresa: currentEmpresa,
                    cod: cod
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp) {
                        currentSerie = resp.serie;
                        currentNumber = resp.numero;
                        console.log('Serie cargada:', currentSerie, currentNumber);
                    }
                }
            });
        }

        function setupEventListeners() {
            sideDocType.addEventListener('change', () => {
                documentType = sideDocType.value;
                fetchSeries();
                // Sync with payment modal buttons if they exist
                document.querySelectorAll('.document-option').forEach(btn => {
                    if (btn.dataset.document === documentType) {
                        btn.classList.add('selected');
                    } else {
                        btn.classList.remove('selected');
                    }
                });
            });

            sideSearchBtn.addEventListener('click', () => {
                const doc = sideDocInput.value.trim();
                if (doc === '') return;
                searchClient(doc);
            });

            sideDocInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const doc = sideDocInput.value.trim();
                    if (doc === '') return;
                    searchClient(doc);
                }
            });
        }

        function searchClient(num) {
            const tipo = (num.length === 8) ? 'DNI' : 'RUC';
            const apiKey = '5000';

            sideSearchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            sideSearchBtn.disabled = true;

            $.ajax({
                url: 'assets/ajax/pos.php?action=searchContributor',
                method: 'GET',
                data: {
                    tipo: tipo,
                    num_doc: num,
                    api_key: apiKey
                },
                dataType: 'json',
                success: function(resp) {
                    sideSearchBtn.innerHTML = '<i class="fas fa-search"></i>';
                    sideSearchBtn.disabled = false;

                    if (resp.status === 'success') {
                        const d = resp.data;
                        contribData = {
                            tipo_doc: d.tipo_doc || (tipo === 'RUC' ? 6 : (tipo === 'DNI' ? 1 : 0)),
                            num_doc: d.num_doc || num,
                            nombre: d.nombre || '',
                            direccion: d.direccion || '',
                            distrito: d.distrito || '',
                            provincia: d.provincia || '',
                            departamento: d.departamento || '',
                            correo: d.correo || '',
                            telefono: d.telefono || ''
                        };

                        // Update UI
                        sideClientName.textContent = contribData.nombre;
                        sideClientAddress.textContent = contribData.direccion || 'Sin dirección registrada';
                        sideClientInfo.style.display = 'block';

                        // Sync with modal
                        document.getElementById('contribNum').value = contribData.num_doc;
                        document.getElementById('contribNombre').value = contribData.nombre;
                        document.getElementById('contribDireccion').value = contribData.direccion;
                    } else {
                        alert(resp.message || 'No se encontró contribuyente');
                        sideClientInfo.style.display = 'none';
                    }
                },
                error: function() {
                    sideSearchBtn.innerHTML = '<i class="fas fa-search"></i>';
                    sideSearchBtn.disabled = false;
                    alert('Error en la búsqueda del cliente');
                }
            });
        }

        function fetchMovies() {
            $.ajax({
                url: 'assets/ajax/pos.php?action=getBillBoard',
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        moviesData = resp.data || [];
                        renderMovies();
                    }
                }
            });
        }

        function fetchTariffs(idSala) {
            const url = idSala ? `assets/ajax/pos.php?action=getTariffs&id_sala=${idSala}` : 'assets/ajax/pos.php?action=getTariffs';
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        tariffs = resp.data || [];
                        tariffQuantities = {};
                        tariffs.forEach(t => {
                            tariffQuantities[t.id] = 0;
                        });
                        renderTariffs();
                        updateTariffState();
                        updateSummary();
                    }
                }
            });
        }

        function renderMovies() {
            const container = document.getElementById('moviesList');
            container.innerHTML = moviesData.map(m => `
                <div class="movie-card" data-id="${m.id_cartelera}">
                    <div class="movie-title">${m.nombre}</div>
                    <div class="movie-info">${m.duracion} • ${m.censura}</div>
                    <div class="showtimes">
                        ${Array.isArray(m.horarios) ? m.horarios.map(h => `
                            <button class="showtime-btn" 
                                data-cartelera="${m.id_cartelera}" 
                                data-hora="${h.id}" 
                                data-funcion="${h.id_funcion || 0}" 
                                data-sala="${m.id_sala}">
                                ${h.hora}
                            </button>
                        `).join('') : ''}
                    </div>
                </div>
            `).join('');

            document.querySelectorAll('.showtime-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const carteleraId = parseInt(btn.dataset.cartelera);
                    const horaId = parseInt(btn.dataset.hora);
                    const funcionId = parseInt(btn.dataset.funcion);
                    const salaId = parseInt(btn.dataset.sala);
                    selectShowtime(carteleraId, horaId, funcionId, salaId);
                });
            });
        }

        function selectShowtime(carteleraId, horaId, funcionId, salaId) {
            if ((selectedCartelera !== carteleraId || selectedHora !== horaId) && selectedSeats.length > 0) {
                if (!confirm('Tiene asientos seleccionados. ¿Desea liberarlos y cambiar de función?')) {
                    return;
                }
                clearSelection();
            }

            selectedCartelera = carteleraId;
            selectedHora = horaId;
            selectedFuncion = funcionId;
            selectedSala = salaId;

            document.querySelectorAll('.movie-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.querySelector(`.movie-card[data-id="${carteleraId}"]`).classList.add('selected');

            document.querySelectorAll('.showtime-btn').forEach(btn => {
                btn.classList.remove('selected');
            });
            document.querySelector(`.showtime-btn[data-cartelera="${carteleraId}"][data-hora="${horaId}"]`).classList.add('selected');

            fetchSeatLayout();
            fetchOccupiedSeats();
            fetchTariffs(selectedSala);
        }

        function fetchSeatLayout() {
            $.ajax({
                url: `assets/ajax/pos.php?action=getSeatLayout&id_funcion=${selectedFuncion}&id_cartelera=${selectedCartelera}`,
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        seatLayout = (resp.data && resp.data.layout) ? resp.data.layout : {};
                        renderSeatMap();
                    }
                }
            });
        }

        function fetchOccupiedSeats() {
            $.ajax({
                url: `assets/ajax/pos.php?action=getOccupiedSeats&id_cartelera=${selectedCartelera}&id_hora=${selectedHora}`,
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        occupiedSeats = resp.data || [];
                        renderSeatMap();
                    }
                }
            });
        }

        function renderSeatMap() {
            if (!selectedCartelera || !selectedHora) return;
            document.getElementById('noSelectionMessage').style.display = 'none';
            document.getElementById('seatMapContainer').style.display = 'block';
            const seatMap = document.getElementById('seatMap');
            const filas = Object.keys(seatLayout).sort();
            seatMap.innerHTML = filas.map(fila => `
                <div class="seat-row">
                    <div class="row-label">${fila}</div>
                    ${seatLayout[fila].map(num => {
                        const seatId = `${fila}${num}`;
                        const isOccupied = occupiedSeats.includes(seatId);
                        const isSelected = selectedSeats.includes(seatId);
                        return `
                            <div class="seat ${isOccupied ? 'occupied' : ''} ${isSelected ? 'selected' : ''}" data-seat="${seatId}">
                                ${num}
                            </div>
                        `;
                    }).join('')}
                </div>
            `).join('');
            document.querySelectorAll('.seat:not(.occupied)').forEach(seat => {
                seat.addEventListener('click', () => {
                    toggleSeat(seat.dataset.seat);
                });
            });
        }

        function toggleSeat(seatId) {
            const i = selectedSeats.indexOf(seatId);
            if (i > -1) {
                selectedSeats.splice(i, 1);
            } else {
                selectedSeats.push(seatId);
            }
            renderSeatMap();
            updateTariffState();
            updateSummary();
        }

        function renderTariffs() {
            const container = document.getElementById('tariffList');
            container.innerHTML = tariffs.map(tariff => `
                <div class="tariff-item" id="tariff-${tariff.id}">
                    <div class="tariff-header">
                        <div class="tariff-name">${tariff.name}</div>
                        <div class="tariff-price">S/ ${Number(tariff.price).toFixed(2)}</div>
                    </div>
                    <div class="tariff-qty">
                        <button class="qty-btn" data-tariff="${tariff.id}" data-action="decrease">-</button>
                        <div class="qty-display" id="qty-${tariff.id}">${tariffQuantities[tariff.id] || 0}</div>
                        <button class="qty-btn" data-tariff="${tariff.id}" data-action="increase">+</button>
                    </div>
                </div>
            `).join('');
            document.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tariffId = parseInt(btn.dataset.tariff);
                    const action = btn.dataset.action;
                    if (action === 'increase') {
                        increaseTariff(tariffId);
                    } else {
                        decreaseTariff(tariffId);
                    }
                });
            });
        }

        function updateTariffState() {
            const totalSeats = selectedSeats.length;
            tariffs.forEach(tariff => {
                const item = document.getElementById(`tariff-${tariff.id}`);
                if (!item) return;
                if (totalSeats === 0) {
                    item.classList.add('disabled');
                } else {
                    item.classList.remove('disabled');
                }
            });
        }

        function increaseTariff(tariffId) {
            const totalSeats = selectedSeats.length;
            const totalTickets = Object.values(tariffQuantities).reduce((a, b) => a + b, 0);
            if (totalTickets < totalSeats) {
                tariffQuantities[tariffId] = (tariffQuantities[tariffId] || 0) + 1;
                document.getElementById(`qty-${tariffId}`).textContent = tariffQuantities[tariffId];
                updateSummary();
            } else {
                alert('No puede asignar más tarifas que asientos seleccionados');
            }
        }

        function decreaseTariff(tariffId) {
            const curr = tariffQuantities[tariffId] || 0;
            if (curr > 0) {
                tariffQuantities[tariffId] = curr - 1;
                document.getElementById(`qty-${tariffId}`).textContent = tariffQuantities[tariffId];
                updateSummary();
            }
        }

        function updateSummary() {
            const totalSeats = selectedSeats.length;
            const totalTickets = Object.values(tariffQuantities).reduce((a, b) => a + b, 0);
            let total = 0;
            tariffs.forEach(tariff => {
                total += Number(tariff.price) * (tariffQuantities[tariff.id] || 0);
            });
            document.getElementById('seatCount').textContent = totalSeats;
            document.getElementById('ticketCount').textContent = totalTickets;
            document.getElementById('totalAmount').textContent = `S/ ${total.toFixed(2)}`;
            const payBtn = document.getElementById('payBtn');
            payBtn.disabled = !(totalSeats > 0 && totalSeats === totalTickets);
        }

        document.getElementById('clearBtn').addEventListener('click', () => {
            if (selectedSeats.length > 0) {
                if (confirm('¿Desea liberar todos los asientos seleccionados?')) {
                    clearSelection();
                }
            }
        });

        function clearSelection() {
            selectedSeats = [];
            Object.keys(tariffQuantities).forEach(id => {
                tariffQuantities[id] = 0;
            });
            tariffs.forEach(tariff => {
                const el = document.getElementById(`qty-${tariff.id}`);
                if (el) el.textContent = '0';
            });
            if (selectedCartelera && selectedHora) {
                renderSeatMap();
            }
            updateSummary();
        }

        document.getElementById('payBtn').addEventListener('click', () => {
            paymentMethod = null;
            // documentType already set via side panel, but allow re-selection if needed
            document.getElementById('paymentModal').classList.add('active');
            document.querySelectorAll('.option-btn.payment-option').forEach(btn => {
                btn.classList.remove('selected');
            });

            // Highlight current document type in modal
            document.querySelectorAll('.document-option').forEach(btn => {
                if (btn.dataset.document === documentType) {
                    btn.classList.add('selected');
                } else {
                    btn.classList.remove('selected');
                }
            });
        });

        document.querySelectorAll('.payment-option').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.payment-option').forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                paymentMethod = btn.dataset.payment;
            });
        });

        document.querySelectorAll('.document-option').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.document-option').forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                documentType = btn.dataset.document;
                sideDocType.value = documentType; // Sync side panel
                fetchSeries(); // Fetch series for new type
                if (documentType === 'factura') {
                    if (!contribData || !contribData.num_doc) {
                        document.getElementById('contribModal').classList.add('active');
                    }
                }
            });
        });

        document.getElementById('cancelPayment').addEventListener('click', () => {
            document.getElementById('paymentModal').classList.remove('active');
        });

        document.getElementById('confirmPayment').addEventListener('click', () => {
            const finalDocType = documentType;
            if (!paymentMethod || !finalDocType) {
                alert('Por favor seleccione método de pago y tipo de documento');
                return;
            }
            if (finalDocType === 'factura') {
                if (!contribData || !contribData.num_doc || !contribData.nombre) {
                    alert('Debe ingresar los datos del contribuyente (RUC) para facturar');
                    document.getElementById('contribModal').classList.add('active');
                    return;
                }
            }
            const itemsTarifa = [];
            tariffs.forEach(t => {
                for (let i = 0; i < (tariffQuantities[t.id] || 0); i++) itemsTarifa.push({
                    id: t.id,
                    price: Number(t.price)
                });
            });
            const items = selectedSeats.map((seat, idx) => {
                const t = itemsTarifa[idx];
                return {
                    seat: seat,
                    tarifa_id: t ? t.id : null,
                    precio: t ? t.price : 0
                };
            }).filter(it => it.tarifa_id !== null);
            let total = 0;
            items.forEach(it => {
                total += it.precio;
            });
            $.ajax({
                url: 'assets/ajax/pos.php?action=processSale',
                method: 'POST',
                data: JSON.stringify({
                    id_funcion: selectedFuncion,
                    id_cartelera: selectedCartelera,
                    id_hora: selectedHora,
                    total: total,
                    tipo_comprobante: finalDocType === 'boleta' ? 'BOLETA' : 'FACTURA',
                    medio_pago: paymentMethod === 'cash' ? 'EFECTIVO' : 'TARJETA',
                    items: items,
                    cliente_doc: (contribData && contribData.num_doc) ? contribData.num_doc : '',
                    cliente_nombre: (contribData && contribData.nombre) ? contribData.nombre : '',
                    serie: currentSerie,
                    num_doc: currentNumber
                }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        document.getElementById('paymentModal').classList.remove('active');
                        generateTicket(total);
                        fetchOccupiedSeats();
                    } else {
                        alert(resp.message || 'Error al procesar venta');
                    }
                }
            });
        });

        document.getElementById('contribSearchBtn').addEventListener('click', () => {
            const tipo = document.getElementById('contribTipo').value;
            const num = document.getElementById('contribNum').value.trim();
            const apiKey = document.getElementById('contribApiKey').value.trim() || '5000';
            if (num === '') {
                alert('Ingrese número de documento');
                return;
            }
            const params = {
                tipo: tipo,
                num_doc: num
            };
            if (tipo === 'RUC') params.api_key = apiKey;
            $.ajax({
                url: 'assets/ajax/pos.php?action=searchContributor',
                method: 'GET',
                data: params,
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        const d = resp.data;
                        contribData = {
                            tipo_doc: d.tipo_doc || (tipo === 'RUC' ? 6 : (tipo === 'DNI' ? 1 : 0)),
                            num_doc: d.num_doc || num,
                            nombre: d.nombre || '',
                            direccion: d.direccion || '',
                            distrito: d.distrito || '',
                            provincia: d.provincia || '',
                            departamento: d.departamento || '',
                            correo: d.correo || '',
                            telefono: d.telefono || ''
                        };
                        document.getElementById('contribNombre').value = contribData.nombre;
                        document.getElementById('contribDireccion').value = contribData.direccion;
                        document.getElementById('contribDistrito').value = contribData.distrito;
                        document.getElementById('contribProvincia').value = contribData.provincia;
                        document.getElementById('contribDepartamento').value = contribData.departamento;
                        document.getElementById('contribCorreo').value = contribData.correo;
                        document.getElementById('contribTelefono').value = contribData.telefono;
                    } else {
                        alert(resp.message || 'No se encontró contribuyente');
                    }
                }
            });
        });

        document.getElementById('contribUse').addEventListener('click', () => {
            if (!contribData || !contribData.num_doc || !contribData.nombre) {
                alert('Complete datos del contribuyente');
                return;
            }
            document.getElementById('contribModal').classList.remove('active');
        });
        document.getElementById('contribCancel').addEventListener('click', () => {
            document.getElementById('contribModal').classList.remove('active');
        });

        function generateTicket(totalOverride) {
            const m = moviesData.find(x => x.id_cartelera === selectedCartelera);
            const totalSeats = selectedSeats.length;
            let total = typeof totalOverride === 'number' ? totalOverride : 0;
            if (typeof totalOverride !== 'number') {
                tariffs.forEach(t => {
                    total += Number(t.price) * (tariffQuantities[t.id] || 0);
                });
            }
            let ticketDetails = '';
            tariffs.forEach(t => {
                const q = tariffQuantities[t.id] || 0;
                if (q > 0) {
                    const subtotal = Number(t.price) * q;
                    ticketDetails += `
                        <div class="ticket-row">
                            <span>${q}x ${t.name}</span>
                            <span>S/ ${subtotal.toFixed(2)}</span>
                        </div>
                    `;
                }
            });
            const now = new Date();
            const ticketNumber = 'T' + now.getTime().toString().slice(-8);
            const barcode = '|||  ||  |  ||  |||  |  ||  |||';
            const ticketHTML = `
                <div class="ticket">
                    <div class="ticket-header">
                        <div class="ticket-cinema">CINERAMA</div>
                        <div style="font-size: 10px;">Sistema de Venta de Entradas</div>
                    </div>
                    <div class="ticket-row large">
                        <span>PELÍCULA:</span>
                    </div>
                    <div style="text-align: center; margin-bottom: 10px; font-weight: 700;">
                        ${m ? m.nombre : ''}
                    </div>
                    <div class="ticket-row">
                        <span>Fecha:</span>
                        <span>${now.toLocaleDateString('es-PE')}</span>
                    </div>
                    <div class="ticket-row">
                        <span>Hora:</span>
                        <span>${document.querySelector('.showtime-btn.selected') ? document.querySelector('.showtime-btn.selected').textContent.trim() : ''}</span>
                    </div>
                    <div class="ticket-row">
                        <span>Sala:</span>
                        <span>${m ? m.nombre_sala : ''}</span>
                    </div>
                    <div class="ticket-row large">
                        <span>Asientos:</span>
                        <span>${selectedSeats.sort().join(', ')}</span>
                    </div>
                    <div style="border-top: 1px dashed #000; margin: 15px 0; padding-top: 15px;">
                        ${ticketDetails}
                    </div>
                    <div class="ticket-row large" style="border-top: 2px solid #000; padding-top: 10px;">
                        <span>TOTAL:</span>
                        <span>S/ ${total.toFixed(2)}</span>
                    </div>
                    <div class="ticket-row" style="margin-top: 15px;">
                        <span>Pago:</span>
                        <span>${paymentMethod === 'cash' ? 'EFECTIVO' : 'TARJETA'}</span>
                    </div>
                    <div class="ticket-row">
                        <span>Documento:</span>
                        <span>${documentType === 'boleta' ? 'BOLETA' : 'FACTURA'}</span>
                    </div>
                    <div class="ticket-row">
                        <span>Cliente:</span>
                        <span>${contribData ? (contribData.num_doc + ' - ' + contribData.nombre) : 'VARIOS'}</span>
                    </div>
                    <div class="ticket-barcode">${barcode}</div>
                    <div class="ticket-footer">
                        <div>Ticket N°: ${ticketNumber}</div>
                        <div>Cajero: ${currentUser}</div>
                        <div>${now.toLocaleString('es-PE')}</div>
                        <div style="margin-top: 10px;">¡Disfruta la función!</div>
                    </div>
                </div>
            `;
            document.getElementById('ticketContent').innerHTML = ticketHTML;
            document.getElementById('ticketModal').classList.add('active');
        }

        document.getElementById('printBtn').addEventListener('click', () => {
            window.print();
        });

        document.getElementById('newSaleBtn').addEventListener('click', () => {
            document.getElementById('ticketModal').classList.remove('active');
            resetPOS();
        });

        function resetPOS() {
            selectedCartelera = null;
            selectedHora = null;
            selectedFuncion = null;
            selectedSala = null;
            selectedSeats = [];
            Object.keys(tariffQuantities).forEach(id => {
                tariffQuantities[id] = 0;
            });
            tariffs.forEach(tariff => {
                const el = document.getElementById(`qty-${tariff.id}`);
                if (el) el.textContent = '0';
            });
            document.querySelectorAll('.movie-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.querySelectorAll('.showtime-btn').forEach(btn => {
                btn.classList.remove('selected');
            });
            document.getElementById('seatMapContainer').style.display = 'none';
            document.getElementById('noSelectionMessage').style.display = 'block';

            // Reset side panel
            sideDocType.value = 'boleta';
            documentType = 'boleta';
            sideDocInput.value = '';
            sideClientInfo.style.display = 'none';
            contribData = null;

            updateSummary();
        }

        window.onload = initializePOS;
    </script>
</body>

</html>