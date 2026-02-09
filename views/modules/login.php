<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinerama - Sistema de Venta</title>
    <link href="https://fonts.googleapis.com/css2?family=Saira+Condensed:wght@300;400;600;700;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
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

        .payment-options, .document-options {
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
    </style>
</head>
<body>
    <!-- LOGIN SCREEN -->
    <div class="login-container" id="loginScreen">
        <div class="login-box">
            <div class="login-logo">CINERAMA</div>
            <div class="login-subtitle">Sistema de Venta de Entradas</div>
            <div class="input-group">
                <label>Usuario</label>
                <input type="text" id="username" value="cajero">
            </div>
            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" id="password" value="1234">
            </div>
            <button id="loginBtn" class="login-btn">INGRESAR AL SISTEMA</button>
            <div class="login-hint">
                💡 Usuario: Segun Caja | La indicada por Administracion<br>
                   ingresar con su usuario asignado
            </div>
        </div>
    </div>

    <script>      

        // ==================== LOGIN ====================
        // permitir login con Enter
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginBtn').click();
            }
        });

        //iniciar sesion usando ajax y js la pagina para el ajax es assets/ajas/login.php ahi se procesa la informacion
        //y si es correcta se redirige a la pagina de inicio
        //si es incorrecta se muestra un mensaje de error
        document.getElementById('loginBtn').addEventListener('click', function() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if (username.trim() && password.trim()) {
                currentUser = username;
                document.getElementById('currentUser').textContent = username;
                document.getElementById('loginScreen').style.display = 'none';
                document.getElementById('posScreen').classList.add('active');
                //hacer la peticion ajax
                $.ajax({
                    url: 'assets/ajax/login.php',
                    type: 'POST',
                    data: {
                        username: username,
                        password: password
                    },
                    success: function(response) {
                        if (response == 'success') {
                            //redirigir a la pagina de inicio
                            window.location.href = '/inicio';
                        } else {
                            alert('Usuario o contraseña incorrectos');
                        }
                    }
                });
            } else {
                alert('Por favor ingrese usuario y contraseña');
            }
        });

       
        

        document.getElementById('logoutBtn').addEventListener('click', () => {
            if (confirm('¿Está seguro que desea cerrar sesión?')) {
                location.reload();
            }
        });
    
    </script>
</body>
</html>