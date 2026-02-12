<?php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinePOS - Punto de Venta</title>
    <link href="https://fonts.googleapis.com/css2?family=Saira+Condensed:wght@300;400;600;700;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        .pos-user { display: flex; align-items: center; gap: 15px; }
        .user-info { text-align: right; }
        .user-name { font-size: 14px; font-weight: 700; color: var(--secondary); }
        .user-role { font-size: 10px; color: var(--text-dim); text-transform: uppercase; }
        .logout-btn {
            padding: 10px 20px; background: transparent; border: 2px solid var(--primary);
            color: var(--primary); cursor: pointer; font-family: 'Saira Condensed', sans-serif;
            font-weight: 700; text-transform: uppercase; transition: all 0.3s ease;
        }
        .logout-btn:hover { background: var(--primary); color: var(--text); }
        .pos-main {
            display: grid; grid-template-columns: 350px 1fr 350px;
            height: calc(100vh - 85px); gap: 0;
        }
        @media (max-width: 1200px) {
            .pos-main { grid-template-columns: 1fr; height: auto; }
        }
        .pos-section {
            background: var(--dark-light); padding: 25px; overflow-y: auto; border-right: 2px solid var(--dark);
        }
        .pos-section:last-child { border-right: none; }
        .section-title {
            font-family: 'Saira Condensed', sans-serif; font-size: 20px; font-weight: 700; text-transform: uppercase;
            margin-bottom: 20px; color: var(--secondary); letter-spacing: 1px; border-bottom: 2px solid var(--dark-lighter); padding-bottom: 10px;
        }
        .movie-card { background: var(--dark); padding: 15px; margin-bottom: 15px; cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent; position: relative; }
        .movie-card:hover { border-color: var(--primary); transform: translateX(5px); }
        .movie-card.selected { border-color: var(--secondary); background: var(--dark-lighter); }
        .movie-title { font-family: 'Saira Condensed', sans-serif; font-size: 18px; font-weight: 700; margin-bottom: 5px; text-transform: uppercase; }
        .movie-info { font-size: 11px; color: var(--text-dim); margin-bottom: 10px; }
        .showtimes { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .showtime-btn {
            padding: 8px 12px; background: var(--dark-lighter); border: 1px solid var(--dark-lighter); color: var(--text);
            font-size: 12px; font-family: 'Space Mono', monospace; cursor: pointer; transition: all 0.2s ease;
        }
        .showtime-btn:hover { border-color: var(--primary); background: var(--primary); }
        .showtime-btn.selected { background: var(--secondary); color: var(--dark); border-color: var(--secondary); font-weight: 700; }
        .center-panel { display: flex; flex-direction: column; align-items: center; padding: 30px; background: var(--dark); }
        .screen {
            width: 80%; height: 40px; background: linear-gradient(180deg, var(--secondary) 0%, transparent 100%);
            margin-bottom: 40px; display: flex; align-items: center; justify-content: center; font-family: 'Saira Condensed', sans-serif;
            font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--dark); border-radius: 50% 50% 0 0;
        }
        .seat-map { display: grid; gap: 8px; }
        .seat-row { display: flex; gap: 8px; align-items: center; }
        .row-label { width: 30px; text-align: center; font-weight: 700; color: var(--secondary); font-size: 14px; }
        .seat {
            width: 40px; height: 40px; background: var(--dark-lighter); border: 2px solid var(--dark-lighter);
            cursor: pointer; transition: all 0.2s ease; position: relative; display: flex; align-items: center; justify-content: center; font-size: 10px; color: var(--text-dim);
        }
        .seat:hover:not(.occupied) { border-color: var(--primary); transform: scale(1.1); }
        .seat.occupied { background: var(--danger); border-color: var(--danger); cursor: not-allowed; opacity: 0.5; }
        .seat.selected { background: var(--secondary); border-color: var(--secondary); color: var(--dark); font-weight: 700; }
        .seat-legend { display: flex; gap: 20px; margin-top: 30px; font-size: 11px; }
        .legend-item { display: flex; align-items: center; gap: 8px; }
        .legend-box { width: 20px; height: 20px; border: 2px solid; }
        .tariff-item { background: var(--dark); padding: 15px; margin-bottom: 15px; border: 2px solid var(--dark-lighter); transition: all 0.3s ease; }
        .tariff-item.disabled { opacity: 0.4; pointer-events: none; }
        .tariff-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .tariff-name { font-family: 'Saira Condensed', sans-serif; font-size: 16px; font-weight: 700; text-transform: uppercase; }
        .tariff-price { font-size: 18px; font-weight: 700; color: var(--secondary); }
        .tariff-qty { display: flex; align-items: center; gap: 10px; }
        .qty-btn { width: 30px; height: 30px; background: var(--dark-lighter); border: 1px solid var(--primary); color: var(--primary); font-size: 18px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; }
        .qty-btn:hover:not(:disabled) { background: var(--primary); color: var(--text); }
        .qty-btn:disabled { opacity: 0.3; cursor: not-allowed; border-color: var(--dark-lighter); }
        .qty-display { width: 50px; text-align: center; font-size: 18px; font-weight: 700; color: var(--secondary); }
        .summary { background: var(--dark-lighter); padding: 20px; margin-top: 20px; border: 2px solid var(--primary); }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; }
        .summary-row.total { font-size: 20px; font-weight: 700; color: var(--secondary); border-top: 2px solid var(--dark); padding-top: 10px; margin-top: 10px; }
        .action-buttons { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
        .btn-action { padding: 15px; border: none; font-family: 'Saira Condensed', sans-serif; font-size: 16px; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: all 0.3s ease; letter-spacing: 1px; }
        .btn-clear { background: transparent; border: 2px solid var(--danger); color: var(--danger); }
        .btn-clear:hover { background: var(--danger); color: var(--text); }
        .btn-pay { background: var(--primary); color: var(--text); }
        .btn-pay:hover:not(:disabled) { background: var(--primary-dark); box-shadow: 0 0 20px rgba(255, 0, 102, 0.5); }
        .btn-pay:disabled { opacity: 0.3; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="pos-header">
        <div class="pos-title">CINEPOS / VENTA</div>
        <div class="pos-user">
            <div class="user-info">
                <div class="user-name"><?php echo $_SESSION['nombre'] ?? 'Usuario'; ?></div>
                <div class="user-role"><?php echo $_SESSION['local_nombre'] ?? 'Sede'; ?></div>
            </div>
            <button class="logout-btn" onclick="window.location='cerrar'">SALIR</button>
        </div>
    </div>
    <div class="pos-main">
        <div class="pos-section">
            <div class="section-title">📽️ Películas en Cartelera</div>
            <div id="moviesList"></div>
        </div>
        <div class="pos-section center-panel">
            <div class="section-title">Selección de Asientos</div>
            <div id="seatMapContainer" style="display:none;">
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
            <div id="noSelectionMessage" style="text-align:center; color:var(--text-dim); padding:50px 20px;">
                Seleccione una película y horario para ver el mapa de asientos
            </div>
        </div>
        <div class="pos-section">
            <div class="section-title">🎫 Tarifas</div>
            <div id="tariffList"></div>
            <div class="summary">
                <div class="summary-row"><span>Asientos:</span><span id="seatCount">0</span></div>
                <div class="summary-row"><span>Entradas:</span><span id="ticketCount">0</span></div>
                <div class="summary-row total"><span>TOTAL:</span><span id="totalAmount">S/ 0.00</span></div>
            </div>
            <div class="action-buttons">
                <button class="btn-action btn-clear" id="clearBtn">🗑️ Liberar Asientos</button>
                <button class="btn-action btn-pay" id="payBtn" disabled>💳 Procesar Pago</button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let movies = [];
        let tariffs = [];
        let selectedMovie = null;
        let selectedShowtime = null;
        let selectedFuncion = null;
        let selectedSeats = [];
        let occupiedSeats = [];
        let tariffQuantities = {};
        let currentSalaId = null;
        let seatLayout = {}; // mapa: fila => [numeros]

        function initializePOS() {
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
                        Swal.fire({icon:'error',title:'Error',text:resp.message});
                    }
                },
                error: function() {
                    Swal.fire({icon:'error',title:'Error',text:'No se pudo cargar la cartelera'});
                }
            });
        }
        function fetchTariffs() {
            $.ajax({
                url: 'assets/ajax/pos.php?action=getTariffs' + (currentSalaId ? ('&id_sala=' + currentSalaId) : ''),
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        tariffs = resp.data;
                        tariffs.forEach(t => tariffQuantities[t.id] = 0);
                        renderTariffs();
                    }
                }
            });
        }
        function fetchOccupiedSeats() {
            $.ajax({
                url: 'assets/ajax/pos.php?action=getOccupiedSeats&id_cartelera=' + selectedMovie + '&id_hora=' + selectedShowtime,
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        occupiedSeats = resp.data || [];
                        renderSeatMap();
                    }
                },
                error: function() {
                    renderSeatMap();
                }
            });
        }
        function renderMovies() {
            const container = document.getElementById('moviesList');
            if (movies.length === 0) {
                container.innerHTML = '<div style="color:var(--text-dim); text-align:center; padding:20px;">No hay películas disponibles.</div>';
                return;
            }
            container.innerHTML = movies.map(m => `
                <div class="movie-card" data-id="${m.id_cartelera}">
                    <div class="movie-title">${m.nombre}</div>
                    <div class="movie-info">${m.censura} • ${m.duracion} • ${m.nombre_sala}</div>
                    <div class="showtimes">
                        ${m.horarios.map(h => `
                            <button class="showtime-btn" data-cartelera="${m.id_cartelera}" data-hora="${h.id}" data-funcion="${h.id_funcion}" data-time="${h.hora}">
                                ${h.hora}
                            </button>
                        `).join('')}
                    </div>
                </div>
            `).join('');
            document.querySelectorAll('.showtime-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedMovie = btn.dataset.cartelera;
                    selectedShowtime = btn.dataset.hora;
                    selectedFuncion = btn.dataset.funcion;
                    selectedSeats = [];
                    Object.keys(tariffQuantities).forEach(k => tariffQuantities[k] = 0);
                    document.querySelectorAll('.movie-card').forEach(c => c.classList.remove('selected'));
                    btn.closest('.movie-card').classList.add('selected');
                    document.querySelectorAll('.showtime-btn').forEach(b => b.classList.remove('selected'));
                    btn.classList.add('selected');
                    occupiedSeats = [];
                    seatLayout = {};
                    fetchSeatLayout();
                    updateTariffState();
                    updateSummary();
                });
            });
        }
        function fetchSeatLayout() {
            // Mostrar estado de carga
            document.getElementById('noSelectionMessage').style.display = 'none';
            document.getElementById('seatMapContainer').style.display = 'block';
            document.getElementById('seatMap').innerHTML = '<div style="color:var(--text-dim); padding:20px;">Cargando distribución de sala...</div>';
            $.ajax({
                url: 'assets/ajax/pos.php?action=getSeatLayout&id_funcion=' + (selectedFuncion || 0) + '&id_cartelera=' + selectedMovie,
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        seatLayout = resp.data.layout || {};
                        currentSalaId = resp.data.id_sala || null;
                        renderSeatMap();
                        fetchOccupiedSeats();
                        fetchTariffs(); // refrescar tarifas según sala/local
                    } else {
                        document.getElementById('seatMap').innerHTML = '<div style="color:#ff3366; padding:20px;">No se pudo cargar la sala: ' + resp.message + '</div>';
                    }
                },
                error: function() {
                    document.getElementById('seatMap').innerHTML = '<div style="color:#ff3366; padding:20px;">Error cargando la sala</div>';
                }
            });
        }
        function renderSeatMap() {
            document.getElementById('noSelectionMessage').style.display = 'none';
            document.getElementById('seatMapContainer').style.display = 'block';
            const rows = Object.keys(seatLayout).length ? Object.keys(seatLayout) : ['A','B','C','D','E','F'];
            const seatMap = document.getElementById('seatMap');
            seatMap.innerHTML = rows.map(row => {
                const nums = seatLayout[row] || Array.from({length: 10}, (_,i)=> i+1);
                return `
                <div class="seat-row">
                    <div class="row-label">${row}</div>
                    ${nums.map(n => {
                        const id = row + n;
                        const occ = occupiedSeats.includes(id);
                        const sel = selectedSeats.includes(id);
                        return `<div class="seat ${occ ? 'occupied' : ''} ${sel ? 'selected' : ''}" data-seat="${id}">${n}</div>`;
                    }).join('')}
                </div>
            `;
            }).join('');
            document.querySelectorAll('.seat').forEach(s => {
                s.addEventListener('click', () => {
                    if (s.classList.contains('occupied')) return;
                    const id = s.dataset.seat;
                    const idx = selectedSeats.indexOf(id);
                    if (idx >= 0) {
                        selectedSeats.splice(idx, 1);
                        s.classList.remove('selected');
                    } else {
                        selectedSeats.push(id);
                        s.classList.add('selected');
                    }
                    updateSummary();
                    updatePayButton();
                });
            });
        }
        function renderTariffs() {
            const container = document.getElementById('tariffList');
            if (tariffs.length === 0) {
                container.innerHTML = '<div style="color:var(--text-dim);">No hay tarifas disponibles.</div>';
                return;
            }
            container.innerHTML = tariffs.map(t => `
                <div class="tariff-item ${!selectedMovie ? 'disabled' : ''}">
                    <div class="tariff-header">
                        <div class="tariff-name">${t.name}</div>
                        <div class="tariff-price">S/ ${Number(t.price).toFixed(2)}</div>
                    </div>
                    <div class="tariff-qty">
                        <button class="qty-btn" data-action="dec" data-id="${t.id}" ${!selectedMovie ? 'disabled' : ''}>-</button>
                        <div class="qty-display" id="qty-${t.id}">${tariffQuantities[t.id] || 0}</div>
                        <button class="qty-btn" data-action="inc" data-id="${t.id}" ${!selectedMovie ? 'disabled' : ''}>+</button>
                    </div>
                </div>
            `).join('');
            document.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const action = btn.dataset.action;
                    if (action === 'inc') tariffQuantities[id] = (tariffQuantities[id] || 0) + 1;
                    else tariffQuantities[id] = Math.max(0, (tariffQuantities[id] || 0) - 1);
                    document.getElementById('qty-'+id).textContent = tariffQuantities[id];
                    updateSummary();
                    updatePayButton();
                });
            });
            updateTariffState();
        }
        function updateTariffState() {
            document.querySelectorAll('.tariff-item').forEach(el => {
                if (!selectedMovie || !selectedShowtime) el.classList.add('disabled');
                else el.classList.remove('disabled');
            });
            document.querySelectorAll('.qty-btn').forEach(btn => {
                btn.disabled = (!selectedMovie || !selectedShowtime);
            });
        }
        function updateSummary() {
            document.getElementById('seatCount').textContent = selectedSeats.length;
            const ticketCount = Object.values(tariffQuantities).reduce((a,b)=>a+(b||0),0);
            document.getElementById('ticketCount').textContent = ticketCount;
            const total = tariffs.reduce((sum,t)=> sum + (Number(t.price) * (tariffQuantities[t.id] || 0)), 0);
            document.getElementById('totalAmount').textContent = 'S/ ' + total.toFixed(2);
        }
        function updatePayButton() {
            const ticketCount = Object.values(tariffQuantities).reduce((a,b)=>a+(b||0),0);
            const canPay = selectedMovie && selectedShowtime && selectedSeats.length > 0 && ticketCount === selectedSeats.length;
            document.getElementById('payBtn').disabled = !canPay;
        }
        document.getElementById('clearBtn').addEventListener('click', () => {
            selectedSeats = [];
            document.querySelectorAll('.seat.selected').forEach(s => s.classList.remove('selected'));
            Object.keys(tariffQuantities).forEach(k => tariffQuantities[k] = 0);
            renderTariffs();
            updateSummary();
            updatePayButton();
        });
        initializePOS();
    </script>
</body>
</html>
