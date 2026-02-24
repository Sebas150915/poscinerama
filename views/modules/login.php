<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinerama - Sistema de Venta</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Saira+Condensed:wght@300;400;600;700;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        /* ====== LOGIN SCREEN - PREMIUM CINEMA THEME ====== */
        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.6;
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(232, 175, 48, 0.15);
            }

            50% {
                box-shadow: 0 0 40px rgba(232, 175, 48, 0.3);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #0c0c1d 0%, #1a0a2e 25%, #16132d 50%, #0d1b2a 75%, #0c0c1d 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        /* Floating cinema particles */
        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 20% 30%, rgba(232, 175, 48, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(220, 50, 80, 0.06) 0%, transparent 40%),
                radial-gradient(circle at 50% 80%, rgba(100, 80, 200, 0.06) 0%, transparent 40%),
                radial-gradient(circle at 70% 60%, rgba(232, 175, 48, 0.04) 0%, transparent 30%);
            pointer-events: none;
        }

        /* Floating film elements */
        .cinema-particle {
            position: absolute;
            pointer-events: none;
            opacity: 0.08;
            color: #e8af30;
            font-size: 24px;
            animation: float 6s ease-in-out infinite;
        }

        .cinema-particle:nth-child(1) {
            top: 10%;
            left: 5%;
            animation-delay: 0s;
            font-size: 28px;
        }

        .cinema-particle:nth-child(2) {
            top: 20%;
            right: 10%;
            animation-delay: 1s;
            font-size: 22px;
        }

        .cinema-particle:nth-child(3) {
            bottom: 15%;
            left: 15%;
            animation-delay: 2s;
            font-size: 32px;
        }

        .cinema-particle:nth-child(4) {
            bottom: 25%;
            right: 5%;
            animation-delay: 3s;
            font-size: 20px;
        }

        .cinema-particle:nth-child(5) {
            top: 50%;
            left: 3%;
            animation-delay: 4s;
            font-size: 26px;
        }

        .cinema-particle:nth-child(6) {
            top: 40%;
            right: 8%;
            animation-delay: 1.5s;
            font-size: 18px;
        }

        .cinema-particle:nth-child(7) {
            bottom: 40%;
            left: 8%;
            animation-delay: 3.5s;
            font-size: 30px;
        }

        .cinema-particle:nth-child(8) {
            top: 70%;
            right: 15%;
            animation-delay: 2.5s;
            font-size: 24px;
        }

        /* Film strip decoration */
        .film-strip {
            position: absolute;
            width: 50px;
            height: 100%;
            opacity: 0.03;
            pointer-events: none;
        }

        .film-strip.left {
            left: 30px;
            top: 0;
        }

        .film-strip.right {
            right: 30px;
            top: 0;
        }

        .film-strip-hole {
            width: 14px;
            height: 10px;
            background: #e8af30;
            margin: 8px auto;
            border-radius: 2px;
        }

        .login-box {
            background: rgba(22, 20, 45, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 50px 45px 40px;
            border-radius: 24px;
            border: 1px solid rgba(232, 175, 48, 0.15);
            box-shadow:
                0 25px 60px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.03),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            max-width: 440px;
            width: 92%;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .login-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #e8af30, #dc3250, #e8af30, transparent);
            border-radius: 24px 24px 0 0;
        }

        /* Logo area */
        .login-logo-wrapper {
            text-align: center;
            margin-bottom: 8px;
        }

        .login-cinema-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            background: linear-gradient(135deg, #e8af30, #dc3250);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            box-shadow: 0 8px 24px rgba(232, 175, 48, 0.3);
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .login-logo {
            font-family: 'Saira Condensed', sans-serif;
            font-size: 48px;
            font-weight: 900;
            text-align: center;
            margin-bottom: 0;
            text-transform: uppercase;
            letter-spacing: 4px;
            background: linear-gradient(135deg, #e8af30 0%, #f0c75e 40%, #e8af30 60%, #d4982a 100%);
            background-size: 200% auto;
            animation: shimmer 3s linear infinite;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .login-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.4);
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 400;
            margin-bottom: 36px;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        /* Divider */
        .login-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(232, 175, 48, 0.3), transparent);
        }

        .login-divider span {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: rgba(232, 175, 48, 0.5);
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }

        /* Input groups */
        .input-group {
            margin-bottom: 20px;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        }

        .input-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .input-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .input-group:nth-child(3) {
            animation-delay: 0.3s;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.5);
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(232, 175, 48, 0.4);
            font-size: 16px;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .input-group input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 400;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        .input-group input:focus {
            outline: none;
            border-color: rgba(232, 175, 48, 0.5);
            background: rgba(232, 175, 48, 0.06);
            box-shadow: 0 0 0 4px rgba(232, 175, 48, 0.08), 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        .input-group input:focus+.input-icon,
        .input-wrapper:focus-within .input-icon {
            color: #e8af30;
        }

        /* Toggle password */
        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            font-size: 15px;
            padding: 4px;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #e8af30;
        }

        /* Login button */
        .login-btn {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, #e8af30, #dc3250);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            margin-top: 12px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.5s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(232, 175, 48, 0.4), 0 4px 12px rgba(220, 50, 80, 0.3);
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .login-btn .btn-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin-slow 0.8s linear infinite;
        }

        .login-btn.loading .btn-spinner {
            display: block;
        }

        .login-btn.loading .btn-text {
            display: none;
        }

        /* Footer info */
        .login-footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .login-footer-text {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.25);
            line-height: 1.6;
        }

        .login-footer-text i {
            color: rgba(232, 175, 48, 0.4);
            margin-right: 4px;
        }

        /* Hint box */
        .login-hint {
            text-align: center;
            color: rgba(255, 255, 255, 0.3);
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            margin-top: 16px;
            padding: 12px 16px;
            background: rgba(232, 175, 48, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(232, 175, 48, 0.1);
            line-height: 1.6;
        }

        .login-hint i {
            color: #e8af30;
            margin-right: 4px;
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
    </style>
</head>

<body>
    <!-- LOGIN SCREEN -->
    <div class="login-container" id="loginScreen">
        <!-- Floating cinema particles -->
        <div class="cinema-particle"><i class="fas fa-film"></i></div>
        <div class="cinema-particle"><i class="fas fa-ticket-alt"></i></div>
        <div class="cinema-particle"><i class="fas fa-star"></i></div>
        <div class="cinema-particle"><i class="fas fa-video"></i></div>
        <div class="cinema-particle"><i class="fas fa-popcorn"></i></div>
        <div class="cinema-particle"><i class="fas fa-clapperboard"></i></div>
        <div class="cinema-particle"><i class="fas fa-masks-theater"></i></div>
        <div class="cinema-particle"><i class="fas fa-film"></i></div>

        <!-- Film strip decorations -->
        <div class="film-strip left" id="filmStripLeft"></div>
        <div class="film-strip right" id="filmStripRight"></div>

        <div class="login-box">
            <div class="login-logo-wrapper">
                <div class="login-cinema-icon">
                    <i class="fas fa-clapperboard"></i>
                </div>
            </div>
            <div class="login-logo">CINERAMA</div>
            <div class="login-subtitle">Punto de Venta</div>

            <div class="login-divider">
                <span>Iniciar Sesión</span>
            </div>

            <div class="input-group">
                <label>RUC Empresa</label>
                <div class="input-wrapper">
                    <input type="text" id="ruc" placeholder="Ingrese el RUC">
                    <i class="fas fa-building input-icon"></i>
                </div>
            </div>
            <div class="input-group">
                <label>Usuario</label>
                <div class="input-wrapper">
                    <input type="text" id="username" placeholder="Nombre de usuario">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>
            <div class="input-group">
                <label>Contraseña</label>
                <div class="input-wrapper">
                    <input type="password" id="password" placeholder="••••••••">
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button id="loginBtn" onclick="iniciarSesion()" class="login-btn">
                <span class="btn-text"><i class="fas fa-right-to-bracket"></i> Ingresar</span>
                <span class="btn-spinner"></span>
            </button>

            <div class="login-hint">
                <i class="fas fa-info-circle"></i> Ingrese con su usuario asignado por Administración
            </div>

            <div class="login-footer">
                <div class="login-footer-text">
                    <i class="fas fa-shield-halved"></i> Acceso seguro · Cinerama POS v2.0
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Generate film strip decorations
        $(document).ready(function() {
            ['filmStripLeft', 'filmStripRight'].forEach(function(id) {
                var strip = document.getElementById(id);
                if (strip) {
                    for (var i = 0; i < 40; i++) {
                        var hole = document.createElement('div');
                        hole.className = 'film-strip-hole';
                        strip.appendChild(hole);
                    }
                }
            });
        });

        // Toggle password visibility
        function togglePassword() {
            var passInput = document.getElementById('password');
            var eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function iniciarSesion() {
            var ruc = $("#ruc").val().trim();
            var username = $("#username").val().trim();
            var password = $("#password").val().trim();
            var $btn = $("#loginBtn");

            if (ruc == "" || username == "" || password == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor ingrese todos los campos',
                    confirmButtonColor: '#e8af30',
                    background: '#16142d',
                    color: '#fff'
                });
                return;
            }

            // Show loading state
            $btn.addClass('loading');

            $.ajax({
                url: "assets/ajax/login.php",
                type: "POST",
                data: {
                    ruc: ruc,
                    username: username,
                    password: password
                },
                success: function(response) {
                    $btn.removeClass('loading');
                    if (response.status == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bienvenido!',
                            text: 'Acceso concedido',
                            showConfirmButton: false,
                            timer: 1500,
                            background: '#16142d',
                            color: '#fff'
                        }).then(() => {
                            window.location.href = "index.php";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de acceso',
                            text: response.message,
                            confirmButtonColor: '#e8af30',
                            background: '#16142d',
                            color: '#fff'
                        });
                    }
                },
                error: function() {
                    $btn.removeClass('loading');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor',
                        confirmButtonColor: '#e8af30',
                        background: '#16142d',
                        color: '#fff'
                    });
                }
            });
        }

        // Permitir login con Enter en cualquier campo
        $(document).on('keypress', '#ruc, #username, #password', function(e) {
            if (e.which === 13) {
                iniciarSesion();
            }
        });
    </script>
</body>

</html>