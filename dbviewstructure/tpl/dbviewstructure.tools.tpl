<!-- 
	/**
	* DB Structure Viewer plugin for Cotonti Siena v.1+, PHP 8.4+, MySQL 8.0+
	* Filename: plugins/dbviewstructure/tpl/dbviewstructure.tools.tpl 
	* Purpose: Admin panel for viewing and exporting DB structures
	* Date: 01 July 2026 
	* 
	* Source: https://github.com/webitproff/cot-dbviewstructure
	* Page in Cotonti Marketplace: https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure
	* 
	* @package dbviewstructure
	* @version 3.0.0
	* @author webitproff
	* @copyright Copyright (c) webitproff 2026 https://github.com/webitproff
	* @license BSD
	*/
-->

<!-- BEGIN: MAIN -->

<div class="container-fluid py-4">
    <h2>{PHP.L.dbviewstructure_title}</h2>
	
    <!-- СООБЩЕНИЯ -->
    {FILE "{PHP.cfg.themes_dir}/admin/{PHP.cfg.admintheme}/warnings.tpl"}
	
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {TAB_STRUCTURE_ACTIVE}" href="{URL_STRUCTURE}">{PHP.L.dbviewstructure_tab_structure}</a>
		</li>
        <li class="nav-item">
            <a class="nav-link {TAB_EXPORT_ACTIVE}" href="{URL_EXPORT}">{PHP.L.dbviewstructure_tab_export}</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {TAB_COMBINED_ACTIVE}" href="{URL_COMBINED}">
				{PHP.L.dbviewstructure_tab_combined}
			</a>
		</li>
        <!-- IF {PHP.cfg.plugin.dbviewstructure.log_enabled} -->
        <li class="nav-item">
            <a class="nav-link {TAB_LOGS_ACTIVE}" href="{URL_LOGS}">{PHP.L.dbviewstructure_tab_logs}</a>
		</li>
        <!-- ENDIF -->
	</ul>
	
	<!-- Вкладка "Структура" -->
	<!-- IF {PHP.tab} == 'structure' -->
	
	<!-- Вкладки Bootstrap 5.3 -->
	<ul class="nav nav-tabs mb-3" id="dbviewstructure-structure-tabs" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link {ACTIVE_TAB_ROWS}"
			id="dbviewstructure_view_table_rows-tab"
			data-bs-toggle="tab"
			data-bs-target="#dbviewstructure_view_table_rows"
			type="button" role="tab"
			aria-controls="dbviewstructure_view_table_rows"
			aria-selected="{SELECTED_ROWS}">
				{PHP.L.dbviewstructure_view_table_rows}
			</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link {ACTIVE_TAB_FIELDS}"
			id="dbviewstructure_view_tables_fields-tab"
			data-bs-toggle="tab"
			data-bs-target="#dbviewstructure_view_tables_fields"
			type="button" role="tab"
			aria-controls="dbviewstructure_view_tables_fields"
			aria-selected="{SELECTED_FIELDS}">
				{PHP.L.dbviewstructure_view_tables_fields}
			</button>
		</li>
	</ul>
	
	<div class="tab-content" id="dbviewstructure-structure-tabs-content">
		
		<!-- ВКЛАДКА: ПРОСМОТР СТРОК -->
		<div class="tab-pane fade {ACTIVE_TAB_ROWS}"
		id="dbviewstructure_view_table_rows"
		role="tabpanel"
		aria-labelledby="dbviewstructure_view_table_rows-tab">
			
			<form method="post" id="filter_form">
				<input type="hidden" name="tab" value="structure">
				<div class="row g-3 mb-3">
					<div class="col-md-4">
						<select name="table" class="form-select" required>
							<option value="">{PHP.L.dbviewstructure_select_table}</option>
							<!-- BEGIN: TABLES_LIST -->
							<option value="{TABLE_VALUE}" {TABLE_SELECTED}>{TABLE_VALUE}</option>
							<!-- END: TABLES_LIST -->
						</select>
					</div>
					<div class="col-md-3">
						<input type="number" name="id" class="form-control"
						placeholder="{PHP.L.dbviewstructure_row_id_placeholder}"
						value="{ROW_ID}">
					</div>
					<div class="col-md-3">
						<button type="submit" class="btn btn-primary w-100">
							{PHP.L.dbviewstructure_show}
						</button>
					</div>
				</div>
			</form>
			
			<!-- BEGIN: TABLE_DETAILS -->
			<div class="mt-4">
				<h5>{PHP.L.dbviewstructure_table_fields_header} <code>{SELECTED_TABLE}</code></h5>
				<div class="table-responsive">
					<table class="table table-sm table-bordered">
						<thead>
							<tr>
								<th>{PHP.L.dbviewstructure_field_name}</th>
								<th>{PHP.L.dbviewstructure_field_type}</th>
								<th>{PHP.L.dbviewstructure_field_null}</th>
								<th>{PHP.L.dbviewstructure_field_key}</th>
								<th>{PHP.L.dbviewstructure_field_default}</th>
								<th>{PHP.L.dbviewstructure_field_extra}</th>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: FIELDS_ROW -->
							<tr>
								<td><strong>{FIELD_NAME}</strong></td>
								<td><code>{FIELD_TYPE}</code></td>
								<td>{FIELD_NULL}</td>
								<td>{FIELD_KEY}</td>
								<td>{FIELD_DEFAULT}</td>
								<td>{FIELD_EXTRA}</td>
							</tr>
							<!-- END: FIELDS_ROW -->
						</tbody>
					</table>
				</div>
				
				<!-- IF {HAS_DATA} -->
				<h6 class="mt-4">
					<!-- IF {ROW_ID} -->
					{PHP.L.dbviewstructure_data_row_id} {ROW_ID}
					<!-- ELSE -->
					{PHP.L.dbviewstructure_data_first_ten}
					<!-- ENDIF -->
				</h6>
				<div class="table-responsive">
					<table class="table table-sm table-bordered">
						<thead>
							<tr>
								<!-- BEGIN: DATA_HEADER -->
								<th>{DATA_HEADER}</th>
								<!-- END: DATA_HEADER -->
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: DATA_ROW -->
							<tr>
								<!-- BEGIN: DATA_CELL -->
								<td>{DATA_CELL}</td>
								<!-- END: DATA_CELL -->
							</tr>
							<!-- END: DATA_ROW -->
						</tbody>
					</table>
				</div>
				<!-- ELSE -->
				<p class="text-muted mt-3">{PHP.L.dbviewstructure_no_data}</p>
				<!-- ENDIF -->
			</div>
			<!-- END: TABLE_DETAILS -->
		</div>
		
		<!-- ВКЛАДКА: СПИСОК ТАБЛИЦ -->
		<div class="tab-pane fade {ACTIVE_TAB_FIELDS}"
		id="dbviewstructure_view_tables_fields"
		role="tabpanel"
		aria-labelledby="dbviewstructure_view_tables_fields-tab">
			
			<!-- BEGIN: STRUCTURE -->
			<div class="mb-4">
				<h5>{PHP.L.dbviewstructure_tables_header}</h5>
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>{PHP.L.dbviewstructure_table_name}</th>
								<th>{PHP.L.dbviewstructure_table_fields}</th>
								<th>{PHP.L.dbviewstructure_table_engine}</th>
								<th>{PHP.L.dbviewstructure_table_rows}</th>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: TABLE_ROW -->
							<tr>
								<td><strong>{TABLE_NAME}</strong></td>
								<td><small>{TABLE_FIELDS}</small></td>
								<td>{TABLE_ENGINE}</td>
								<td>{TABLE_ROWS}</td>
							</tr>
							<!-- END: TABLE_ROW -->
							<!-- BEGIN: NO_TABLES -->
							<tr><td colspan="4" class="text-center">{PHP.L.dbviewstructure_no_tables}</td></tr>
							<!-- END: NO_TABLES -->
						</tbody>
					</table>
				</div>
			</div>
			<!-- END: STRUCTURE -->
		</div>
		
	</div>
	<!-- ENDIF -->
	
    <!-- Вкладка "Экспорт" -->
    <!-- IF {PHP.tab} == 'export' -->
    <!-- BEGIN: EXPORT -->
    <form method="post" action="{EXPORT_FORM_URL}">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="format" class="form-select" required>
                            <option value="sql">SQL</option>
                            <option value="csv">CSV</option>
                            <option value="json">JSON</option>
                            <option value="php">PHP Array</option>
						</select>
                        <small class="text-muted d-block mt-1">{PHP.L.dbviewstructure_php_warning_only}</small>
					</div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="data_mode" value="structure" id="data_structure" checked>
                            <label class="form-check-label" for="data_structure">{PHP.L.dbviewstructure_data_structure_only}</label>
						</div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="data_mode" value="ten" id="data_ten">
                            <label class="form-check-label" for="data_ten">{PHP.L.dbviewstructure_include_data}</label>
						</div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="data_mode" value="all" id="data_all">
                            <label class="form-check-label" for="data_all">{PHP.L.dbviewstructure_include_all_data}</label>
						</div>
					</div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success w-100">{PHP.L.dbviewstructure_export_button}</button>
					</div>
				</div>
			</div>
		</div>
		
        <div class="mt-4">
            <h5 class="mb-4"><input type="checkbox" id="select_all"> {PHP.L.dbviewstructure_select_all}</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-3">
                    <thead>
                        <tr>
                            <th style="width:30px"></th>
                            <th>{PHP.L.dbviewstructure_table_name}</th>
                            <th>{PHP.L.dbviewstructure_table_fields}</th>
                            <th>{PHP.L.dbviewstructure_table_engine}</th>
                            <th>{PHP.L.dbviewstructure_table_rows}</th>
						</tr>
					</thead>
                    <tbody>
                        <!-- BEGIN: TABLE_ROW -->
                        <tr>
                            <td><input type="checkbox" name="tables[]" value="{TABLE_NAME}" class="table-checkbox"></td>
                            <td><strong>{TABLE_NAME}</strong></td>
                            <td><small>{TABLE_FIELDS}</small></td>
                            <td>{TABLE_ENGINE}</td>
                            <td>{TABLE_ROWS}</td>
						</tr>
                        <!-- END: TABLE_ROW -->
                        <!-- IF !{TOTAL_TABLES} -->
                        <tr><td colspan="5" class="text-center">{PHP.L.dbviewstructure_no_tables}</td></tr>
                        <!-- ENDIF -->
					</tbody>
				</table>
			</div>
		</div>
	</form>
    <!-- END: EXPORT -->
    <!-- ENDIF -->
	<!-- IF {PHP.tab} == 'combined' -->
	<!-- BEGIN: COMBINED -->
	<div class="mt-4">
		<h4 class="mb-3">{PHP.L.dbviewstructure_combined_title}</h4>
		<p class="text-muted">{PHP.L.dbviewstructure_combined_desc}</p>
		
		<form id="combined-export-form" method="post" action="{COMBINED_FORM_URL}">
			<input type="hidden" name="a" value="export_combined">
			
			<div class="mb-4">
				<label class="form-label fw-bold">{PHP.L.dbviewstructure_base_table}</label>
				<select name="base_table" id="base_table" class="form-select w-auto" required>
					<option value="">— {PHP.L.dbviewstructure_select_table} —</option>
					<!-- BEGIN: BASE_TABLE_OPTION -->
					<option value="{BASE_TABLE_VALUE}">{BASE_TABLE_LABEL}</option>
					<!-- END: BASE_TABLE_OPTION -->
				</select>
			</div>
			
			<div id="columns-container" class="mb-3"></div>
			
			<div class="mb-4">
				<button type="button" id="add-column-btn" class="btn btn-outline-primary">
					+ {PHP.L.dbviewstructure_add_column}
				</button>
				<button type="button" id="clear-columns-btn" class="btn btn-outline-danger ms-2">
					{PHP.L.dbviewstructure_clear_fields}
				</button>
			</div>
			
			<button type="submit" class="btn btn-success px-4">
				{PHP.L.dbviewstructure_export_button}
			</button>
		</form>
	</div>
	
	<script>
		const ALL_TABLES = {ALL_TABLES_JSON};
		const PREFIX = '{PHP.db_x}' || 'cot_';
		const columnsCache = {};
		let columnIndex = 0;
		const STORAGE_KEY = 'dbv_combined_form';
		
		function createColumnRow() {
			const idx = columnIndex++;
			const div = document.createElement('div');
			div.className = 'row g-2 mb-2 column-row';
			div.innerHTML = `
			<div class="col-md-2">
            <input type="text" name="columns[${idx}][csv_header]" class="form-control"
			placeholder="{PHP.L.dbviewstructure_csv_col_name}" required>
			</div>
			<div class="col-md-2">
            <select name="columns[${idx}][table]" class="form-select table-select" required>
			<option value="">— {PHP.L.dbviewstructure_select_table} —</option>
            </select>
			</div>
			<div class="col-md-2">
            <select name="columns[${idx}][field]" class="form-select field-select" disabled required>
			<option value="">— {PHP.L.dbviewstructure_select_field} —</option>
            </select>
			</div>
			<div class="col-md-2">
            <select name="columns[${idx}][aggregate]" class="form-select aggregate-select" disabled>
			<option value="">— {PHP.L.dbviewstructure_aggregate_none} —</option>
			<option value="first_image">{PHP.L.dbviewstructure_aggregate_first_image}</option>
			<option value="rest_images">{PHP.L.dbviewstructure_aggregate_rest_images}</option>
			<option value="all_images">{PHP.L.dbviewstructure_aggregate_all_images}</option>
            </select>
			</div>
			<div class="col-md-2">
            <select name="columns[${idx}][join_mode]" class="form-select join-mode-select" disabled>
			<option value="auto">JOIN: авто</option>
			<option value="manual">JOIN: вручную</option>
            </select>
            <select name="columns[${idx}][join_field]" class="form-select join-field-select mt-1" disabled style="display:none">
			<option value="">— поле связи —</option>
            </select>
			</div>
			<div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm remove-column">×</button>
			</div>`;
			return div;
		}
		
		// ═══════════ СОХРАНЕНИЕ В LOCALSTORAGE ═══════════
		function saveFormToStorage() {
			const base = document.getElementById('base_table').value;
			const cols = [];
			document.querySelectorAll('.column-row').forEach(row => {
				cols.push({
					csv_header: row.querySelector('input').value || '',
					table: row.querySelector('.table-select').value || '',
					field: row.querySelector('.field-select').value || '',
					aggregate: row.querySelector('.aggregate-select').value || '',
					join_mode: row.querySelector('.join-mode-select').value || 'auto',
					join_field: row.querySelector('.join-field-select').value || ''
				});
			});
			localStorage.setItem(STORAGE_KEY, JSON.stringify({ base_table: base, columns: cols }));
		}
		
		// Сохранять при любом изменении
		document.getElementById('combined-export-form').addEventListener('input', saveFormToStorage);
		document.getElementById('combined-export-form').addEventListener('change', saveFormToStorage);
		
		// ═══════════ ЗАГРУЗКА ИЗ LOCALSTORAGE ═══════════
		function loadFormFromStorage() {
			const raw = localStorage.getItem(STORAGE_KEY);
			if (!raw) return null;
			try {
				return JSON.parse(raw);
				} catch (e) {
				return null;
			}
		}
		
		// ═══════════ КНОПКИ ═══════════
		document.getElementById('add-column-btn').addEventListener('click', () => {
			const row = createColumnRow();
			document.getElementById('columns-container').appendChild(row);
			populateTableSelect(row.querySelector('.table-select'));
			saveFormToStorage();
		});
		
		document.getElementById('clear-columns-btn').addEventListener('click', () => {
			localStorage.removeItem(STORAGE_KEY);
			document.getElementById('columns-container').innerHTML = '';
			columnIndex = 0;
			document.getElementById('base_table').value = '';
			for (let i = 0; i < 3; i++) {
				document.getElementById('add-column-btn').click();
			}
		});
		
		function populateTableSelect(select) {
			select.innerHTML = '<option value="">— {PHP.L.dbviewstructure_select_table} —</option>';
			for (const [s, f] of Object.entries(ALL_TABLES)) {
				select.innerHTML += `<option value="${f}">${s}</option>`;
			}
		}
		
		// ═══════════ ЗАГРУЗКА ПОЛЕЙ ПРИ СМЕНЕ ТАБЛИЦЫ ═══════════
		document.getElementById('columns-container').addEventListener('change', async (e) => {
			if (e.target.classList.contains('table-select')) {
				const row = e.target.closest('.column-row');
				const fieldSelect = row.querySelector('.field-select');
				const aggSelect = row.querySelector('.aggregate-select');
				const joinModeSelect = row.querySelector('.join-mode-select');
				const joinFieldSelect = row.querySelector('.join-field-select');
				const tableFull = e.target.value;
				
				if (!tableFull) {
					fieldSelect.innerHTML = '<option value="">— {PHP.L.dbviewstructure_select_field} —</option>';
					fieldSelect.disabled = true;
					aggSelect.disabled = true;
					joinModeSelect.disabled = true;
					joinFieldSelect.disabled = true;
					joinFieldSelect.style.display = 'none';
					return;
				}
				
				if (!columnsCache[tableFull]) {
					const resp = await fetch(`index.php?r=dbviewstructure&a=get_columns&table=${encodeURIComponent(tableFull)}`);
					columnsCache[tableFull] = await resp.json();
				}
				
				const cols = columnsCache[tableFull] || [];
				
				fieldSelect.innerHTML = '<option value="">— {PHP.L.dbviewstructure_select_field} —</option>';
				cols.forEach(c => { fieldSelect.innerHTML += `<option value="${c}">${c}</option>`; });
				fieldSelect.disabled = false;
				
				joinFieldSelect.innerHTML = '<option value="">— поле связи —</option>';
				cols.forEach(c => { joinFieldSelect.innerHTML += `<option value="${c}">${c}</option>`; });
				
				aggSelect.disabled = false;
				joinModeSelect.disabled = false;
				joinModeSelect.value = 'auto';
				joinFieldSelect.disabled = false;
				joinFieldSelect.style.display = 'none';
			}
			
			if (e.target.classList.contains('join-mode-select')) {
				const joinFieldSelect = e.target.closest('.column-row').querySelector('.join-field-select');
				if (e.target.value === 'manual') {
					joinFieldSelect.disabled = false;
					joinFieldSelect.style.display = 'block';
					} else {
					joinFieldSelect.style.display = 'none';
				}
			}
		});
		
		document.getElementById('columns-container').addEventListener('click', e => {
			if (e.target.classList.contains('remove-column')) {
				e.target.closest('.column-row').remove();
				saveFormToStorage();
			}
		});
		
		// ═══════════ ВОССТАНОВЛЕНИЕ ПРИ ЗАГРУЗКЕ ═══════════
		document.addEventListener('DOMContentLoaded', () => {
			const saved = loadFormFromStorage();
			
			const useBase = saved ? saved.base_table : '';
			const useCols = saved ? saved.columns : [];
			
			if (useBase) {
				document.getElementById('base_table').value = useBase;
			}
			
			if (useCols.length) {
				useCols.forEach(col => {
					const row = createColumnRow();
					document.getElementById('columns-container').appendChild(row);
					
					const tableSelect = row.querySelector('.table-select');
					populateTableSelect(tableSelect);
					tableSelect.value = col.table || '';
					
					row.querySelector('input').value = col.csv_header || '';
					row.querySelector('.aggregate-select').value = col.aggregate || '';
					
					const joinModeSelect = row.querySelector('.join-mode-select');
					const joinFieldSelect = row.querySelector('.join-field-select');
					if (joinModeSelect) {
						joinModeSelect.value = col.join_mode || 'auto';
						joinModeSelect.disabled = false;
						if (col.join_mode === 'manual') {
							joinFieldSelect.disabled = false;
							joinFieldSelect.style.display = 'block';
							joinFieldSelect.value = col.join_field || '';
							} else {
							joinFieldSelect.style.display = 'none';
						}
					}
					
					if (col.table) {
						tableSelect.dispatchEvent(new Event('change', { bubbles: true }));
						setTimeout(() => {
							row.querySelector('.field-select').value = col.field || '';
							if (col.join_mode === 'manual' && joinFieldSelect) {
								joinFieldSelect.value = col.join_field || '';
							}
						}, 600);
					}
				});
				} else {
				for (let i = 0; i < 3; i++) {
					document.getElementById('add-column-btn').click();
				}
			}
		});
	</script>
	<!-- END: COMBINED -->
	<!-- ENDIF -->
	
    <!-- Вкладка "Логи" -->
    <!-- IF {PHP.tab} == 'logs' -->
    <!-- BEGIN: LOGS -->
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">{PHP.L.dbviewstructure_logs}</h3>
            <a href="{CLEAR_LOGS_URL}" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены? Все записи логов будут удалены.');">
                {PHP.L.dbviewstructure_clear_logs}
			</a>
		</div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-3">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>{PHP.L.dbviewstructure_log_file}</th>
                        <th>{PHP.L.dbviewstructure_log_download}</th>
                        <th>{PHP.L.dbviewstructure_log_format}</th>
                        <th>{PHP.L.dbviewstructure_log_tables}</th>
                        <th>{PHP.L.dbviewstructure_log_data}</th>
                        <th>{PHP.L.dbviewstructure_log_date}</th>
					</tr>
				</thead>
                <tbody>
                    <!-- BEGIN: LOG_ROW -->
                    <tr class="{LOG_ODDEVEN}">
                        <td>{LOG_ID}</td>
                        <td>
                            <!-- IF {LOG_FILE_EXISTS} -->
							{PHP.cfg.plugin.dbviewstructure.export_path}<code>{LOG_FILENAME}</code>
                            <!-- ELSE -->
							<span class="text-danger">{PHP.L.dbviewstructure_file_missing}</span>
                            <!-- ENDIF -->
						</td>
                        <td>
                            <!-- IF {LOG_FILE_EXISTS} -->
							<a href="{LOG_DOWNLOAD}" target="_blank">{PHP.L.dbviewstructure_download_file}</a>
                            <!-- ELSE -->
							<span class="text-muted">—</span>
                            <!-- ENDIF -->
						</td>
                        <td><span class="badge bg-primary">{LOG_FORMAT}</span></td>
                        <td>{LOG_TABLES}</td>
                        <td>{LOG_WITH_DATA}</td>
                        <td>{LOG_DATE}</td>
					</tr>
                    <!-- END: LOG_ROW -->
                    <!-- BEGIN: NO_LOGS -->
                    <tr><td colspan="7" class="text-center">{PHP.L.dbviewstructure_no_logs}</td></tr>
                    <!-- END: NO_LOGS -->
				</tbody>
			</table>
		</div>
		
        <!-- IF {PAGINATION} -->
        <nav aria-label="Page Pagination" class="mt-3">
            <div class="text-center mb-2">{PHP.L.Total}: {TOTAL_ENTRIES}, {PHP.L.Onpage}: {ENTRIES_ON_CURRENT_PAGE}</div>
            <ul class="pagination justify-content-center">{PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}</ul>
		</nav>
        <!-- ENDIF -->
	</div>
    <!-- END: LOGS -->
    <!-- ENDIF -->
</div>

<script>
	document.getElementById('select_all')?.addEventListener('change', function() {
		document.querySelectorAll('.table-checkbox').forEach(cb => cb.checked = this.checked);
	});
</script>
<!-- END: MAIN -->
