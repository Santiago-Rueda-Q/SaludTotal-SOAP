<?php require __DIR__ . '/partials/header.php'; ?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>
                <i class="fas fa-users"></i>
                Lista de Pacientes
            </h2>
            <p style="color: var(--text-secondary);">Gestione todos los pacientes registrados en el sistema</p>
        </div>
        <a href="index.php?action=crear_paciente" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i>
            <span>Nuevo Paciente</span>
        </a>
    </div>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?php echo htmlspecialchars($_GET['mensaje']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?php echo htmlspecialchars($_GET['error']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (empty($pacientes)): ?>
        <div style="text-align: center; padding: 64px 24px; background: var(--bg-secondary); border-radius: 16px;">
            <div style="font-size: 64px; margin-bottom: 16px; color: var(--text-light);">
                <i class="fas fa-user-slash"></i>
            </div>
            <h3 style="color: var(--text-secondary);">No hay pacientes registrados</h3>
            <p style="color: var(--text-secondary); margin-bottom: 24px;">Comience agregando su primer paciente al sistema</p>
            <a href="index.php?action=crear_paciente" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                <span>Registrar Primer Paciente</span>
            </a>
        </div>
    <?php else: ?>

        <!-- Barra de búsqueda + info -->
        <div class="table-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input
                    type="text"
                    id="search-cedula"
                    placeholder="Buscar por cédula en tiempo real..."
                    autocomplete="off">
            </div>
            <div class="table-info">
                <span id="results-count">
                    Mostrando <?php echo count($pacientes); ?> de <?php echo count($pacientes); ?> pacientes
                </span>
            </div>
        </div>

        <div class="table-container">
            <table class="table" id="patients-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-id-card"></i> Cédula</th>
                        <th><i class="fas fa-user"></i> Nombres</th>
                        <th><i class="fas fa-user-tag"></i> Apellidos</th>
                        <th><i class="fas fa-phone"></i> Teléfono</th>
                        <th><i class="fas fa-calendar-alt"></i> Fecha Nacimiento</th>
                        <th style="text-align: center;"><i class="fas fa-cog"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacientes as $paciente): ?>
                        <tr>
                            <td>
                                <span class="badge badge-primary">
                                    <?php echo htmlspecialchars($paciente['cedula']); ?>
                                </span>
                            </td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($paciente['nombres']); ?></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($paciente['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['fecha_nacimiento']); ?></td>
                            <td style="text-align: center;">
                                <a href="index.php?action=editar_paciente&cedula=<?php echo $paciente['cedula']; ?>" 
                                   class="btn btn-secondary" 
                                   style="padding: 6px 12px; font-size: 14px; margin-right: 8px;">
                                    <i class="fas fa-edit"></i>
                                    <span>Editar</span>
                                </a>
                                <button 
                                   onclick="confirmDeletePatient('<?php echo htmlspecialchars($paciente['cedula']); ?>', '<?php echo htmlspecialchars($paciente['nombres'] . ' ' . $paciente['apellidos']); ?>')"
                                   class="btn btn-danger" 
                                   style="padding: 6px 12px; font-size: 14px;">
                                    <i class="fas fa-trash-alt"></i>
                                    <span>Eliminar</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div id="pagination" class="pagination"></div>

        <div style="margin-top: 16px; text-align: center; color: var(--text-secondary); font-size: 14px;">
            <p>
                <i class="fas fa-info-circle"></i>
                Total de pacientes en el sistema: <strong><?php echo count($pacientes); ?></strong>
            </p>
        </div>
    <?php endif; ?>
</div>

<!-- JS ESPECÍFICO DE ESTA VISTA: BUSCADOR + PAGINACIÓN -->
<script>
(function() {
    const table = document.getElementById('patients-table');
    const searchInput = document.getElementById('search-cedula');
    const paginationContainer = document.getElementById('pagination');
    const resultsCount = document.getElementById('results-count');

    if (!table || !searchInput || !paginationContainer || !resultsCount) {
        return;
    }

    const allRows = Array.from(table.querySelectorAll('tbody tr'));
    const rowsPerPage = 10;
    let filteredRows = [...allRows];
    let currentPage = 1;

    // Guardar la cédula en data-attribute
    allRows.forEach(row => {
        const cedulaBadge = row.querySelector('.badge');
        if (cedulaBadge) {
            row.dataset.cedula = cedulaBadge.textContent.trim().toLowerCase();
        }
    });

    function renderRows() {
        allRows.forEach(row => row.style.display = 'none');

        const total = filteredRows.length;
        const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        filteredRows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';
            }
        });
    }

    function renderPagination() {
        const total = filteredRows.length;
        const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));

        paginationContainer.innerHTML = '';
        if (totalPages <= 1) return;

        const createButton = (label, page, disabled = false, active = false) => {
            const btn = document.createElement('button');
            btn.className = 'page-btn';
            btn.textContent = label;

            if (active) btn.classList.add('active');
            if (disabled) {
                btn.disabled = true;
                btn.classList.add('disabled');
            } else {
                btn.addEventListener('click', () => {
                    currentPage = page;
                    renderRows();
                    renderPagination();
                    table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }
            return btn;
        };

        // « anterior
        paginationContainer.appendChild(
            createButton('«', currentPage - 1, currentPage === 1)
        );

        const totalPagesToShow = 5;
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + totalPagesToShow - 1);

        if (endPage - startPage < totalPagesToShow - 1) {
            startPage = Math.max(1, endPage - totalPagesToShow + 1);
        }

        if (startPage > 1) {
            paginationContainer.appendChild(createButton('1', 1, false, currentPage === 1));
            if (startPage > 2) {
                const dots = document.createElement('span');
                dots.className = 'page-dots';
                dots.textContent = '…';
                paginationContainer.appendChild(dots);
            }
        }

        for (let page = startPage; page <= endPage; page++) {
            paginationContainer.appendChild(
                createButton(String(page), page, false, page === currentPage)
            );
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const dots = document.createElement('span');
                dots.className = 'page-dots';
                dots.textContent = '…';
                paginationContainer.appendChild(dots);
            }
            paginationContainer.appendChild(
                createButton(String(totalPages), totalPages, false, currentPage === totalPages)
            );
        }

        // » siguiente
        paginationContainer.appendChild(
            createButton('»', currentPage + 1, currentPage === totalPages)
        );
    }

    function updateResultsCount() {
        const total = allRows.length;
        const filtered = filteredRows.length;
        resultsCount.textContent = `Mostrando ${filtered} de ${total} pacientes`;
    }

    function applyFilter() {
        const term = searchInput.value.trim().toLowerCase();
        if (!term) {
            filteredRows = [...allRows];
        } else {
            filteredRows = allRows.filter(row =>
                (row.dataset.cedula || '').includes(term)
            );
        }
        currentPage = 1;
        renderRows();
        renderPagination();
        updateResultsCount();
    }

    // Eventos
    searchInput.addEventListener('input', applyFilter);

    // Render inicial
    applyFilter();
})();
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
