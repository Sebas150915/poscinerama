<?php
// inicio.php is included in plantilla.php which already defines the structure.
?>
<style>
    /* ... keeping styles ... */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #ff0066;
        --primary-dark: #cc0052;
        --secondary: #00ffcc;
        --dark: #0a0a0f;
        --dark-light: #1a1a24;
        --dark-lighter: #2a2a38;
        --text: #ffffff;
        --text-dim: #a0a0b0;
        --success: #00ff88;
        --warning: #ffaa00;
        --danger: #ff3366;
    }

    body {
        font-family: 'Space Mono', monospace;
        background: linear-gradient(135deg, var(--dark) 0%, #12121c 100%);
        color: var(--text);
        overflow-x: hidden;
        min-height: 100vh;
    }

    /* LOGIN SCREEN */
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background:
            radial-gradient(circle at 20% 50%, rgba(255, 0, 102, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(0, 255, 204, 0.1) 0%, transparent 50%),
            var(--dark);
    }

    .login-box {
        background: var(--dark-light);
        padding: 60px 50px;
        border: 3px solid var(--primary);
        box-shadow:
            0 0 40px rgba(255, 0, 102, 0.3),
            inset 0 0 40px rgba(255, 0, 102, 0.05);
        max-width: 450px;
        width: 90%;
        position: relative;
        overflow: hidden;
    }

    .login-logo {
        font-family: 'Saira Condensed', sans-serif;
        font-size: 52px;
        font-weight: 900;
        text-align: center;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: -2px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .login-subtitle {
        text-align: center;
        color: var(--text-dim);
        font-size: 11px;
        margin-bottom: 40px;
        text-transform: uppercase;
        letter-spacing: 3px;
    }

    .input-group {
        margin-bottom: 25px;
    }

    .input-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--secondary);
    }

    .input-group input {
        width: 100%;
        padding: 15px;
        background: var(--dark);
        border: 2px solid var(--dark-lighter);
        color: var(--text);
        font-family: 'Space Mono', monospace;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .input-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 20px rgba(255, 0, 102, 0.2);
    }

    .login-btn {
        width: 100%;
        padding: 18px;
        background: var(--primary);
        border: none;
        color: var(--text);
        font-family: 'Saira Condensed', sans-serif;
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .login-btn:hover {
        background: var(--primary-dark);
        box-shadow: 0 0 30px rgba(255, 0, 102, 0.6);
    }

    .login-hint {
        text-align: center;
        color: var(--text-dim);
        font-size: 10px;
        margin-top: 20px;
        padding: 10px;
        background: var(--dark);
        border-left: 3px solid var(--secondary);
    }

    /* MAIN POS INTERFACE */
    .pos-container {
        display: none;
        min-height: 100vh;
    }

    .pos-container.active {
        display: block;
    }

    .pos-header {
        background: var(--dark-light);
        border-bottom: 3px solid var(--primary);
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pos-title {
        font-family: 'Saira Condensed', sans-serif;
        font-size: 32px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -1px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .pos-user {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-info {
        text-align: right;
    }

    .user-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--secondary);
    }

    .user-role {
        font-size: 10px;
        color: var(--text-dim);
        text-transform: uppercase;
    }

    .logout-btn {
        padding: 10px 20px;
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        cursor: pointer;
        font-family: 'Saira Condensed', sans-serif;
        font-weight: 700;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: var(--primary);
        color: var(--text);
    }

    .pos-main {
        display: grid;
        grid-template-columns: 350px 1fr 350px;
        height: calc(100vh - 85px);
        gap: 0;
    }

    @media (max-width: 1200px) {
        .pos-main {
            grid-template-columns: 1fr;
            height: auto;
        }
    }

    .pos-section {
        background: var(--dark-light);
        padding: 25px;
        overflow-y: auto;
        border-right: 2px solid var(--dark);
    }

    .pos-section:last-child {
        border-right: none;
    }

    .section-title {
        font-family: 'Saira Condensed', sans-serif;
        font-size: 20px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 20px;
        color: var(--secondary);
        letter-spacing: 1px;
        border-bottom: 2px solid var(--dark-lighter);
        padding-bottom: 10px;
    }

    /* LEFT PANEL - MOVIES */
    .movie-card {
        background: var(--dark);
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
    }

    .movie-card:hover {
        border-color: var(--primary);
        transform: translateX(5px);
    }

    .movie-card.selected {
        border-color: var(--secondary);
        background: var(--dark-lighter);
    }

    .movie-title {
        font-family: 'Saira Condensed', sans-serif;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .movie-info {
        font-size: 11px;
        color: var(--text-dim);
        margin-bottom: 10px;
    }

    .showtimes {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .showtime-btn {
        padding: 8px 12px;
        background: var(--dark-lighter);
        border: 1px solid var(--dark-lighter);
        color: var(--text);
        font-size: 12px;
        font-family: 'Space Mono', monospace;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .showtime-btn:hover {
        border-color: var(--primary);
        background: var(--primary);
    }

    .showtime-btn.selected {
        background: var(--secondary);
        color: var(--dark);
        border-color: var(--secondary);
        font-weight: 700;
    }

    /* CENTER PANEL - SEAT MAP */
    .center-panel {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px;
        background: var(--dark);
    }

    .screen {
        width: 80%;
        height: 40px;
        background: linear-gradient(180deg, var(--secondary) 0%, transparent 100%);
        margin-bottom: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Saira Condensed', sans-serif;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--dark);
        border-radius: 50% 50% 0 0;
    }

    .seat-map {
        display: grid;
        gap: 8px;
    }

    .seat-row {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .row-label {
        width: 30px;
        text-align: center;
        font-weight: 700;
        color: var(--secondary);
        font-size: 14px;
    }

    .seat {
        width: 40px;
        height: 40px;
        background: var(--dark-lighter);
        border: 2px solid var(--dark-lighter);
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: var(--text-dim);
    }

    .seat:hover:not(.occupied) {
        border-color: var(--primary);
        transform: scale(1.1);
    }

    .seat.occupied {
        background: var(--danger);
        border-color: var(--danger);
        cursor: not-allowed;
        opacity: 0.5;
    }

    .seat.selected {
        background: var(--secondary);
        border-color: var(--secondary);
        color: var(--dark);
        font-weight: 700;
    }

    .seat-legend {
        display: flex;
        gap: 20px;
        margin-top: 30px;
        font-size: 11px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-box {
        width: 20px;
        height: 20px;
        border: 2px solid;
    }

    /* RIGHT PANEL - TARIFF */
    .tariff-item {
        background: var(--dark);
        padding: 15px;
        margin-bottom: 15px;
        border: 2px solid var(--dark-lighter);
        transition: all 0.3s ease;
    }

    .tariff-item.disabled {
        opacity: 0.4;
        pointer-events: none;
    }

    .tariff-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .tariff-name {
        font-family: 'Saira Condensed', sans-serif;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .tariff-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--secondary);
    }

    .tariff-qty {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .qty-btn {
        width: 30px;
        height: 30px;
        background: var(--dark-lighter);
        border: 1px solid var(--primary);
        color: var(--primary);
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .qty-btn:hover:not(:disabled) {
        background: var(--primary);
        color: var(--text);
    }

    .qty-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        border-color: var(--dark-lighter);
    }

    .qty-display {
        width: 50px;
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        color: var(--secondary);
    }

    .summary {
        background: var(--dark-lighter);
        padding: 20px;
        margin-top: 20px;
        border: 2px solid var(--primary);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 13px;
    }

    .summary-row.total {
        font-size: 20px;
        font-weight: 700;
        color: var(--secondary);
        border-top: 2px solid var(--dark);
        padding-top: 10px;
        margin-top: 10px;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-action {
        padding: 15px;
        border: none;
        font-family: 'Saira Condensed', sans-serif;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        letter-spacing: 1px;
    }

    .btn-clear {
        background: transparent;
        border: 2px solid var(--danger);
        color: var(--danger);
    }

    .btn-clear:hover {
        background: var(--danger);
        color: var(--text);
    }

    .btn-pay {
        background: var(--primary);
        color: var(--text);
    }

    .btn-pay:hover:not(:disabled) {
        background: var(--primary-dark);
        box-shadow: 0 0 20px rgba(255, 0, 102, 0.5);
    }

    .btn-pay:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    /* MODAL */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: var(--dark-light);
        padding: 40px;
        max-width: 500px;
        width: 90%;
        border: 3px solid var(--primary);
        position: relative;
    }

    .modal-title {
        font-family: 'Saira Condensed', sans-serif;
        font-size: 28px;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 30px;
        color: var(--secondary);
    }

    .payment-options,
    .document-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 30px;
    }

    .option-btn {
        padding: 25px;
        background: var(--dark);
        border: 2px solid var(--dark-lighter);
        color: var(--text);
        font-family: 'Saira Condensed', sans-serif;
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .option-btn:hover {
        border-color: var(--primary);
        background: var(--dark-lighter);
    }

    .option-btn.selected {
        background: var(--secondary);
        color: var(--dark);
        border-color: var(--secondary);
    }

    .modal-actions {
        display: flex;
        gap: 15px;
    }

    .btn-modal {
        flex: 1;
        padding: 15px;
        border: none;
        font-family: 'Saira Condensed', sans-serif;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background: transparent;
        border: 2px solid var(--text-dim);
        color: var(--text-dim);
    }

    .btn-cancel:hover {
        border-color: var(--text);
        color: var(--text);
    }

    .btn-confirm {
        background: var(--primary);
        color: var(--text);
    }

    .btn-confirm:hover {
        background: var(--primary-dark);
    }

    /* TICKET */
    .ticket {
        background: white;
        color: #000;
        padding: 30px;
        max-width: 400px;
        margin: 0 auto;
        font-family: 'Courier New', monospace;
    }

    .ticket-header {
        text-align: center;
        border-bottom: 2px dashed #000;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .ticket-cinema {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .ticket-row {
        display: flex;
        justify-content: space-between;
        margin: 8px 0;
        font-size: 12px;
    }

    .ticket-row.large {
        font-size: 16px;
        font-weight: 700;
        margin: 15px 0;
    }

    .ticket-footer {
        text-align: center;
        border-top: 2px dashed #000;
        padding-top: 15px;
        margin-top: 15px;
        font-size: 10px;
    }

    .ticket-barcode {
        text-align: center;
        font-size: 20px;
        letter-spacing: 2px;
        margin: 15px 0;
        font-family: 'Courier New', monospace;
    }

    .btn-print {
        width: 100%;
        padding: 15px;
        background: var(--success);
        border: none;
        color: var(--dark);
        font-family: 'Saira Condensed', sans-serif;
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        margin-top: 20px;
    }

    .btn-print:hover {
        background: #00dd77;
    }

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--dark);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary);
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--secondary);
    }

    /* SALES MODAL STYLES */
    .sales-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .sales-table th,
    .sales-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid var(--dark-lighter);
        color: var(--text);
    }

    .sales-table th {
        background: rgba(255, 255, 255, 0.05);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
    }

    .sales-history-btn {
        background: var(--dark-lighter);
        color: var(--text);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-family: inherit;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        margin-right: 10px;
    }

    .sales-history-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .action-btn {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 11px;
        cursor: pointer;
        border: none;
        font-weight: 600;
        margin-right: 5px;
    }

    .btn-reprint {
        background: var(--secondary);
        color: white;
    }

    .btn-cancel {
        background: var(--danger);
        color: white;
    }

    .status-badge {
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-pagado {
        background: #10b981;
        color: white;
    }

    .status-anulado {
        background: #ef4444;
        color: white;
    }

    .modal-body {
        padding: 20px;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: var(--text-dim);
    }

    .form-control {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 12px;
        border-radius: 6px;
        color: var(--text);
        font-family: inherit;
        font-size: 16px;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--secondary);
    }
</style>


<!-- MAIN POS INTERFACE -->.
<div class="pos-container" id="posScreen">
    <div class="pos-header">
        <div class="pos-title">CINEPOS / VENTA</div>
        <div class="pos-user">
            <div class="user-info">
                <div class="user-name" id="currentUser"><?php echo $_SESSION['nombre']; ?></div>
                <div class="user-role" id="localName"><?php echo $_SESSION['local_nombre'] ?? 'Sede Central'; ?></div>
            </div>
            <button class="sales-history-btn" id="viewSalesBtn">🎟️ VENTAS</button>
            <button class="logout-btn" id="logoutBtn">SALIR</button>
        </div>
    </div>

    <div class="pos-main">
        <!-- LEFT PANEL: MOVIES -->
        <div class="pos-section">
            <div class="section-title">📽️ Películas en Cartelera</div>
            <div id="moviesList"></div>
        </div>

        <!-- CENTER PANEL: SEAT MAP -->
        <div class="pos-section center-panel">
            <div class="section-title">Selección de Asientos</div>
            <div id="seatMapContainer" style="display: none;">
                <div class="screen">PANTALLA</div>
                <div class="seat-map" id="seatMap"></div>
                <div class="seat-legend">
                    <div class="legend-item">
                        <div class="legend-box" style="background: var(--dark-lighter); border-color: var(--dark-lighter);"></div>
                        <span>Disponible</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box" style="background: var(--secondary); border-color: var(--secondary);"></div>
                        <span>Seleccionado</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box" style="background: var(--danger); border-color: var(--danger); opacity: 0.5;"></div>
                        <span>Ocupado</span>
                    </div>
                </div>
            </div>
            <div id="noSelectionMessage" style="text-align: center; color: var(--text-dim); padding: 50px 20px;">
                Seleccione una película y horario para ver el mapa de asientos
            </div>
        </div>

        <!-- RIGHT PANEL: TARIFF & SUMMARY -->
        <div class="pos-section">
            <div class="section-title">🎫 Tarifas</div>
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
                    🗑️ Liberar Asientos
                </button>
                <button class="btn-action btn-pay" id="payBtn" disabled>
                    💳 Procesar Pago
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
                💵 Efectivo
            </button>
            <button class="option-btn payment-option" data-payment="card">
                💳 Tarjeta
            </button>
        </div>

        <div class="modal-title" style="font-size: 24px; margin-top: 20px;">Tipo de Documento</div>
        <div class="document-options">
            <button class="option-btn document-option" data-document="boleta">
                📄 Boleta
            </button>
            <button class="option-btn document-option" data-document="factura">
                📋 Factura
            </button>
        </div>

        <div class="modal-title" style="font-size: 24px; margin-top: 20px;">Datos del Cliente</div>
        <div class="customer-info" style="margin-top: 15px;">
            <div class="input-group">
                <label>DNI / RUC</label>
                <input type="text" id="clientDoc" class="form-control" placeholder="Ingrese documento...">
            </div>
            <div class="input-group">
                <label>Nombre / Razón Social</label>
                <input type="text" id="clientName" class="form-control" placeholder="Nombre del cliente...">
            </div>
        </div>

        <div class="modal-actions">
            <button class="btn-modal btn-cancel" id="cancelPayment">Cancelar</button>
            <button class="btn-modal btn-confirm" id="confirmPayment">Confirmar Venta</button>
        </div>
    </div>
</div>

<!-- SALES HISTORY MODAL -->
<div class="modal" id="salesModal">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <div class="modal-title">Historial de Ventas del Día</div>
            <button class="close-modal" id="closeSalesModal">&times;</button>
        </div>
        <div class="modal-body">
            <div id="salesSummary" style="display: flex; gap: 20px; margin-bottom: 20px; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 8px;">
                <div class="summary-item">
                    <div style="font-size: 12px; color: var(--text-dim);">TOTAL VENTAS</div>
                    <div id="salesTotalDay" style="font-size: 24px; font-weight: 700; color: var(--secondary);">S/ 0.00</div>
                </div>
                <div class="summary-item">
                    <div style="font-size: 12px; color: var(--text-dim);">EFECTIVO</div>
                    <div id="salesTotalCash" style="font-size: 18px; font-weight: 600;">S/ 0.00</div>
                </div>
                <div class="summary-item">
                    <div style="font-size: 12px; color: var(--text-dim);">TARJETA</div>
                    <div id="salesTotalCard" style="font-size: 18px; font-weight: 600;">S/ 0.00</div>
                </div>
            </div>
            <div class="table-container">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Película</th>
                            <th>Showtime</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="salesListBody">
                        <!-- Ventas se cargarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- TICKET MODAL -->
<div class="modal" id="ticketModal">
    <div class="modal-content" style="max-width: 500px;">
        <div id="ticketContent"></div>
        <button class="btn-print" id="printBtn">🖨️ Imprimir Ticket</button>
        <button class="btn-modal btn-confirm" id="newSaleBtn" style="width: 100%; margin-top: 15px;">
            Nueva Venta
        </button>
    </div>
</div>

<script>
    // ==================== DATA ====================
    // ==================== STATE ====================
    let movies = [];
    let tariffs = [];
    let selectedMovie = null; // id_cartelera
    let selectedShowtime = null; // id_hora
    let selectedIdFuncion = null; // id_funcion de tbl_funciones
    let selectedTimeStr = null;
    let occupiedSeatsData = []; // Array simple de "FilaNumero"
    let tariffQuantities = {};
    let paymentMethod = null;
    let documentType = null;




    // ==================== INITIALIZE POS ====================
    function initializePOS() {
        console.log("Initializing POS...");
        fetchMovies();
        fetchTariffs();
    }

    function fetchMovies() {
        $.ajax({
            url: 'assets/ajax/pos.php?action=getBillBoard',
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'success') {
                    movies = resp.data;
                    renderMovies();
                } else {
                    console.error("Error fetching movies:", resp.message);
                }
            }
        });
    }

    function fetchTariffs() {
        $.ajax({
            url: 'assets/ajax/pos.php?action=getTariffs',
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'success') {
                    tariffs = resp.data;
                    tariffs.forEach(t => {
                        tariffQuantities[t.id] = 0;
                    });
                    renderTariffs();
                } else {
                    console.error("Error fetching tariffs:", resp.message);
                }
            }
        });
    }

    function fetchOccupiedSeats() {
        if (!selectedMovie || !selectedShowtime) return;
        $.ajax({
            url: `assets/ajax/pos.php?action=getOccupiedSeats&id_cartelera=${selectedMovie}&id_hora=${selectedShowtime}`,
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'success') {
                    occupiedSeatsData = resp.data;
                    renderSeatMap();
                }
            }
        });
    }

    function getCurrentOccupiedSeats() {
        return occupiedSeatsData;
    }

    // ==================== RENDER MOVIES ====================
    function renderMovies() {
        const container = document.getElementById('moviesList');
        if (movies.length === 0) {
            container.innerHTML = '<div style="color:var(--text-dim); text-align:center; padding:20px;">No hay películas disponibles en este local.</div>';
            return;
        }
        container.innerHTML = movies.map(movie => `
                <div class="movie-card" data-id="${movie.id_cartelera}">
                    <div class="movie-title">${movie.nombre}</div>
                    <div class="movie-info">${movie.censura} • ${movie.duracion} • ${movie.nombre_sala}</div>
                    <div class="showtimes">
                        ${movie.horarios.map(h => `
                            <button class="showtime-btn" data-cartelera="${movie.id_cartelera}" data-hora="${h.id}" data-time="${h.hora}" data-funcion="${h.id_funcion}">
                                ${h.hora}
                            </button>
                        `).join('')}
                    </div>
                </div>
            `).join('');

        document.querySelectorAll('.showtime-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const carteleraId = btn.dataset.cartelera;
                const horaId = btn.dataset.hora;
                const timeStr = btn.dataset.time;
                const idFuncion = btn.dataset.funcion;
                selectShowtime(carteleraId, horaId, timeStr, idFuncion);
            });
        });
    }

    function selectShowtime(carteleraId, horaId, timeStr, idFuncion) {
        if (selectedMovie === carteleraId && selectedShowtime === horaId) {
            return;
        }

        selectedMovie = carteleraId;
        selectedShowtime = horaId;
        selectedIdFuncion = idFuncion;
        selectedTimeStr = timeStr; // Necesario para el ticket
        selectedSeats = [];
        Object.keys(tariffQuantities).forEach(k => tariffQuantities[k] = 0);

        // Update UI selection
        document.querySelectorAll('.movie-card').forEach(card => {
            card.classList.remove('selected');
            if (card.dataset.id == carteleraId) card.classList.add('selected');
        });

        document.querySelectorAll('.showtime-btn').forEach(btn => {
            btn.classList.remove('selected');
            if (btn.dataset.cartelera == carteleraId && btn.dataset.hora == horaId) btn.classList.add('selected');
        });

        fetchOccupiedSeats();
        updateTariffState();
        updateSummary();
    }

    // ==================== RENDER SEAT MAP ====================
    function renderSeatMap() {
        document.getElementById('noSelectionMessage').style.display = 'none';
        document.getElementById('seatMapContainer').style.display = 'block';

        const rows = ['A', 'B', 'C', 'D', 'E', 'F'];
        const seatsPerRow = 10;
        const occupiedSeats = getCurrentOccupiedSeats();

        const seatMap = document.getElementById('seatMap');
        seatMap.innerHTML = rows.map(row => `
                <div class="seat-row">
                    <div class="row-label">${row}</div>
                    ${Array.from({ length: seatsPerRow }, (_, i) => {
                        const seatNumber = i + 1;
                        const seatId = `
            $ {
                row
            }
            $ {
                seatNumber
            }
            `;
                        const isOccupied = occupiedSeats.includes(seatId);
                        const isSelected = selectedSeats.includes(seatId);
                        
                        return ` < div class = "seat ${isOccupied ? 'occupied' : ''} ${isSelected ? 'selected' : ''}"
            data - seat = "${seatId}" > $ {
                seatNumber
            } < /div>`;
        }).join('')
    } <
    /div>
    `).join('');

        document.querySelectorAll('.seat:not(.occupied)').forEach(seat => {
            seat.addEventListener('click', () => {
                toggleSeat(seat.dataset.seat);
            });
        });
    }

    function toggleSeat(seatId) {
        const index = selectedSeats.indexOf(seatId);
        if (index > -1) {
            selectedSeats.splice(index, 1);
        } else {
            selectedSeats.push(seatId);
        }
        renderSeatMap();
        updateTariffState();
        updateSummary();
    }

    // ==================== RENDER TARIFFS ====================
    function renderTariffs() {
        const container = document.getElementById('tariffList');
        container.innerHTML = tariffs.map(tariff => ` <
    div class = "tariff-item"
    id = "tariff-${tariff.id}" >
        <
        div class = "tariff-header" >
        <
        div class = "tariff-name" > $ {
            tariff.name
        } < /div> <
    div class = "tariff-price" > S / $ {
        tariff.price.toFixed(2)
    } < /div> < /
    div > <
        div class = "tariff-qty" >
        <
        button class = "qty-btn"
    data - tariff = "${tariff.id}"
    data - action = "decrease" > - < /button> <
    div class = "qty-display"
    id = "qty-${tariff.id}" > 0 < /div> <
    button class = "qty-btn"
    data - tariff = "${tariff.id}"
    data - action = "increase" > + < /button> < /
    div > <
        /div>
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
            const item = document.getElementById(`
    tariff - $ {
        tariff.id
    }
    `);
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
            tariffQuantities[tariffId]++;
            document.getElementById(`
    qty - $ {
        tariffId
    }
    `).textContent = tariffQuantities[tariffId];
            updateSummary();
        } else {
            alert('No puede asignar más tarifas que asientos seleccionados');
        }
    }

    function decreaseTariff(tariffId) {
        if (tariffQuantities[tariffId] > 0) {
            tariffQuantities[tariffId]--;
            document.getElementById(`
    qty - $ {
        tariffId
    }
    `).textContent = tariffQuantities[tariffId];
            updateSummary();
        }
    }

    // ==================== UPDATE SUMMARY ====================
    function updateSummary() {
        const totalSeats = selectedSeats.length;
        const totalTickets = Object.values(tariffQuantities).reduce((a, b) => a + b, 0);

        let total = 0;
        tariffs.forEach(tariff => {
            total += tariff.price * tariffQuantities[tariff.id];
        });

        document.getElementById('seatCount').textContent = totalSeats;
        document.getElementById('ticketCount').textContent = totalTickets;
        document.getElementById('totalAmount').textContent = `
    S / $ {
        total.toFixed(2)
    }
    `;

        const payBtn = document.getElementById('payBtn');
        if (totalSeats > 0 && totalSeats === totalTickets) {
            payBtn.disabled = false;
        } else {
            payBtn.disabled = true;
        }
    }

    // ==================== CLEAR SELECTION ====================
    document.getElementById('clearBtn').addEventListener('click', () => {
        if (selectedSeats.length > 0) {
            if (confirm('¿Desea liberar todos los asientos seleccionados?')) {
                clearSelection();
            }
        }
    });

    function clearSelection() {
        selectedSeats = [];
        tariffQuantities = {
            1: 0,
            2: 0,
            3: 0,
            4: 0
        };
        tariffs.forEach(tariff => {
            document.getElementById(`
    qty - $ {
        tariff.id
    }
    `).textContent = '0';
        });
        if (selectedMovie && selectedShowtime) {
            renderSeatMap();
        }
        updateSummary();
    }

    // ==================== PAYMENT PROCESS ====================
    document.getElementById('payBtn').addEventListener('click', () => {
        paymentMethod = null;
        documentType = null;
        document.getElementById('paymentModal').classList.add('active');

        document.querySelectorAll('.option-btn').forEach(btn => {
            btn.classList.remove('selected');
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
        });
    });

    document.getElementById('cancelPayment').addEventListener('click', () => {
        document.getElementById('paymentModal').classList.remove('active');
    });

    document.getElementById('confirmPayment').addEventListener('click', () => {
        if (!paymentMethod || !documentType) {
            alert('Por favor seleccione método de pago y tipo de documento');
            return;
        }

        if (!selectedIdFuncion || selectedIdFuncion == 0) {
            alert('Error: La función seleccionada no es válida o no existe en el sistema.');
            return;
        }

        // Preparar datos de venta básica
        const saleData = {
            id_funcion: selectedIdFuncion,
            cliente_doc: document.getElementById('clientDoc').value,
            cliente_nombre: document.getElementById('clientName').value,
            total: Object.values(tariffQuantities).reduce((acc, q, i) => {
                const tariff = tariffs.find(t => t.id == Object.keys(tariffQuantities)[i]);
                return acc + (tariff ? tariff.price * q : 0);
            }, 0),
            tipo_comprobante: documentType.toUpperCase(),
            medio_pago: paymentMethod.toUpperCase(),
            items: []
        };

        // Clonamos cantidades para la asignacion de asientos
        const tempQuantities = {
            ...tariffQuantities
        };

        // Re-mapeamos items con las cantidades clonadas
        saleData.items = selectedSeats.map(seat => {
            let assignedTariff = null;
            for (const t of tariffs) {
                if (tempQuantities[t.id] > 0) {
                    assignedTariff = t;
                    tempQuantities[t.id]--;
                    break;
                }
            }
            return {
                seat: seat,
                tarifa_id: assignedTariff ? assignedTariff.id : tariffs[0].id,
                precio: assignedTariff ? assignedTariff.price : tariffs[0].price
            };
        });dd

        // Mostrar loading
        const btnConfirm = document.getElementById('confirmPayment');
        const originalText = btnConfirm.textContent;
        btnConfirm.disabled = true;
        btnConfirm.textContent = 'Procesando...';

        $.ajax({
            url: 'assets/ajax/pos.php?action=processSale',
            type: 'POST',
            data: JSON.stringify(saleData),
            contentType: 'application/json',
            success: function(resp) {
                btnConfirm.disabled = false;
                btnConfirm.textContent = originalText;

                if (resp.status === 'success') {
                    document.getElementById('paymentModal').classList.remove('active');
                    generateTicket(resp.codigo); // Pasar el codigo real
                } else {
                    alert('Error al procesar la venta: ' + resp.message);
                }
            },
            error: function() {
                btnConfirm.disabled = false;
                btnConfirm.textContent = originalText;
                alert('Error de conexión con el servidor');
            }
        });
    });

    // ==================== GENERATE TICKET ====================
    function generateTicket(realCodigo = null, reprintData = null) {
        let movie, totalSeats, total, ticketDetails, ticketNumber, selectedTime, salaName, asientosList;
        const now = new Date();
        const userName = document.getElementById('currentUser').textContent;
        const barcode = '|||  ||  |  ||  |||  |  ||  |||';

        if (reprintData) {
            // Data from getSaleDetails
            movie = {
                nombre: reprintData.venta.pelicula_nombre
            };
            totalSeats = reprintData.boletos.length;
            total = parseFloat(reprintData.venta.total);
            ticketNumber = reprintData.venta.codigo;
            selectedTime = reprintData.venta.hora;
            salaName = reprintData.venta.nombre_sala;
            asientosList = reprintData.boletos.map(b => b.fila + b.columna).sort().join(', ');

            ticketDetails = reprintData.boletos.map(b => ` <
    div class = "ticket-row" >
    <
    span > 1 x $ {
        b.tarifa_nombre
    } < /span> <
    span > S / $ {
        parseFloat(b.price).toFixed(2)
    } < /span> < /
    div >
        `).join('');
        } else {
            // Current selection data
            movie = movies.find(m => m.id_cartelera == selectedMovie);
            totalSeats = selectedSeats.length;
            total = 0;
            ticketDetails = '';

            tariffs.forEach(tariff => {
                if (tariffQuantities[tariff.id] > 0) {
                    const subtotal = tariff.price * tariffQuantities[tariff.id];
                    total += subtotal;
                    ticketDetails += ` <
        div class = "ticket-row" >
        <
        span > $ {
            tariffQuantities[tariff.id]
        }
    x $ {
        tariff.name
    } < /span> <
    span > S / $ {
        parseFloat(subtotal).toFixed(2)
    } < /span> < /
    div >
        `;
                }
            });

            ticketNumber = realCodigo || ('T' + now.getTime().toString().slice(-8));
            selectedTime = selectedTimeStr || '-';
            salaName = movie ? movie.nombre_sala : 'SALA';
            asientosList = selectedSeats.sort().join(', ');
        }

        const ticketHTML = ` <
        div class = "ticket" >
        <
        div class = "ticket-header" >
        <
        div class = "ticket-cinema" > CINEPOS < /div> <
    div style = "font-size: 10px;" > Sistema de Venta de Entradas $ {
        reprintData ? '(REIMPRESIÓN)' : ''
    } < /div> < /
    div >

        <
        div class = "ticket-row large" >
        <
        span > PELÍCULA: < /span> < /
    div > <
        div style = "text-align: center; margin-bottom: 10px; font-weight: 700;" >
        $ {
            movie ? movie.nombre : 'PELÍCULA'
        } <
        /div>

        <
        div class = "ticket-row" >
        <
        span > Fecha: < /span> <
    span > $ {
        now.toLocaleDateString('es-PE')
    } < /span> < /
    div > <
        div class = "ticket-row" >
        <
        span > Hora: < /span> <
    span > $ {
        selectedTime
    } < /span> < /
    div > <
        div class = "ticket-row" >
        <
        span > Sala: < /span> <
    span > $ {
        salaName
    } < /span> < /
    div > <
        div class = "ticket-row large" >
        <
        span > Asientos: < /span> <
    span > $ {
        asientosList
    } < /span> < /
    div >

        <
        div style = "border-top: 1px dashed #000; margin: 15px 0; padding-top: 15px;" >
        $ {
            ticketDetails
        } <
        /div>

        <
        div class = "ticket-row large"
    style = "border-top: 2px solid #000; padding-top: 10px;" >
        <
        span > TOTAL: < /span> <
    span > S / $ {
        total.toFixed(2)
    } < /span> < /
    div >

        <
        div class = "ticket-row"
    style = "margin-top: 15px;" >
        <
        span > Cajero: < /span> <
    span > $ {
        userName
    } < /span> < /
    div >

        <
        div class = "ticket-barcode" > $ {
            barcode
        } < /div>

        <
        div class = "ticket-footer" >
        <
        div > Ticket N°: $ {
            ticketNumber
        } < /div> <
    div > $ {
        now.toLocaleString('es-PE')
    } < /div> <
    div style = "margin-top: 10px;" > ¡Disfruta la función! < /div> < /
    div > <
        /div>
    `;

        document.getElementById('ticketContent').innerHTML = ticketHTML;
        document.getElementById('ticketModal').classList.add('active');

        if (!reprintData) {
            // Local occupation (frontend only until next reload)
            selectedSeats.forEach(seat => {
                if (!occupiedSeatsData.includes(seat)) {
                    occupiedSeatsData.push(seat);
                }
            });
        }
    }

    document.getElementById('printBtn').addEventListener('click', () => {
        window.print();
    });

    document.getElementById('newSaleBtn').addEventListener('click', () => {
        document.getElementById('ticketModal').classList.remove('active');
        resetPOS();
    });

    function resetPOS() {
        selectedMovie = null;
        selectedShowtime = null;
        selectedSeats = [];
        Object.keys(tariffQuantities).forEach(k => tariffQuantities[k] = 0);
        paymentMethod = null;
        documentType = null;

        document.querySelectorAll('.movie-card').forEach(card => {
            card.classList.remove('selected');
        });
        document.querySelectorAll('.showtime-btn').forEach(btn => {
            btn.classList.remove('selected');
        });

        tariffs.forEach(tariff => {
            const qtyEl = document.getElementById(`
    qty - $ {
        tariff.id
    }
    `);
            if (qtyEl) qtyEl.textContent = '0';
        });

        document.getElementById('seatMapContainer').style.display = 'none';
        document.getElementById('noSelectionMessage').style.display = 'block';

        document.getElementById('clientDoc').value = '';
        document.getElementById('clientName').value = '';

        updateSummary();
    }

    // ==================== SALES HISTORY ====================
    document.getElementById('viewSalesBtn').addEventListener('click', () => {
        loadSalesHistory();
        document.getElementById('salesModal').classList.add('active');
    });

    document.getElementById('closeSalesModal').addEventListener('click', () => {
        document.getElementById('salesModal').classList.remove('active');
    });

    function loadSalesHistory() {
        const body = document.getElementById('salesListBody');
        body.innerHTML = '<tr><td colspan="7" style="text-align: center;">Cargando...</td></tr>';

        $.ajax({
            url: 'assets/ajax/pos.php?action=getSalesHistory',
            type: 'GET',
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'success') {
                    if (resp.data.length === 0) {
                        body.innerHTML = '<tr><td colspan="7" style="text-align: center;">No hay ventas registradas hoy</td></tr>';
                        document.getElementById('salesTotalDay').textContent = 'S/ 0.00';
                        document.getElementById('salesTotalCash').textContent = 'S/ 0.00';
                        document.getElementById('salesTotalCard').textContent = 'S/ 0.00';
                        return;
                    }

                    let total = 0,
                        cash = 0,
                        card = 0;
                    body.innerHTML = resp.data.map(sale => {
                        const amount = parseFloat(sale.total);
                        if (sale.estado === 'PAGADO') {
                            total += amount;
                            if (sale.medio_pago === 'EFECTIVO') cash += amount;
                            else card += amount;
                        }
                        return ` <
    tr >
        <
        td > $ {
            sale.codigo
        } < /td> <
    td > $ {
        sale.cliente_nombre || '-'
    } < /td> <
    td > $ {
        sale.pelicula_nombre
    } < /td> <
    td > $ {
        sale.showtime_hora
    } < /td> <
    td > S / $ {
        amount.toFixed(2)
    } < /td> <
    td > $ {
        sale.medio_pago
    } < /td> <
    td > < span class = "status-badge status-${sale.estado.toLowerCase()}" > $ {
            sale.estado
        } < /span></td >
        <
        td >
        <
        button class = "action-btn btn-reprint"
    onclick = "reprintTicket(${sale.id})" > 🖨️ < /button>
    $ {
        sale.estado === 'PAGADO' ? `
                                        <button class="action-btn btn-cancel" onclick="cancelSale(${sale.id})">🚫</button>
                                    ` : ''
    } <
    /td> < /
    tr >
        `;
                    }).join('');

                    document.getElementById('salesTotalDay').textContent = `
    S / $ {
        total.toFixed(2)
    }
    `;
                    document.getElementById('salesTotalCash').textContent = `
    S / $ {
        cash.toFixed(2)
    }
    `;
                    document.getElementById('salesTotalCard').textContent = `
    S / $ {
        card.toFixed(2)
    }
    `;
                } else {
                    body.innerHTML = ` < tr > < td colspan = "7"
    style = "text-align: center; color: var(--danger);" > $ {
        resp.message
    } < /td></tr > `;
                }
            },
            error: function() {
                body.innerHTML = '<tr><td colspan="7" style="text-align: center; color: var(--danger);">Error al conectar con el servidor</td></tr>';
            }
        });
    }

    window.reprintTicket = function(idVenta) {
        $.ajax({
            url: 'assets/ajax/pos.php?action=getSaleDetails&id_venta=' + idVenta,
            type: 'GET',
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'success') {
                    generateTicket(null, resp);
                } else {
                    alert('Error al obtener detalles: ' + resp.message);
                }
            }
        });
    };

    window.cancelSale = function(idVenta) {
        if (!confirm('¿Está seguro de anular esta venta? Esta acción no se puede deshacer.')) return;

        $.ajax({
            url: 'assets/ajax/pos.php?action=cancelSale',
            type: 'POST',
            data: {
                id_venta: idVenta
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'success') {
                    alert(resp.message);
                    loadSalesHistory();
                    // Refrescar mapa de asientos si estamos en esa sala
                    if (selectedMovie) {
                        fetchOccupiedSeats();
                    }
                } else {
                    alert('Error: ' + resp.message);
                }
            }
        });
    };

    // LOGOUT
    document.getElementById('logoutBtn').addEventListener('click', () => {
        if (confirm('¿Desea cerrar sesión?')) {
            window.location = 'cerrar';
        }
    });

    // START
    initializePOS();
</script>