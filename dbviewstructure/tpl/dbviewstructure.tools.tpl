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
	
    <!-- Вкладка "Логи" -->
    <!-- IF {PHP.tab} == 'logs' -->
    <!-- BEGIN: LOGS -->
    <div class="mt-4">
        <h3 class="mb-4">{PHP.L.dbviewstructure_logs}</h3>
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
                        <td>{PHP.cfg.plugin.dbviewstructure.export_path}<code>{LOG_FILENAME}</code></td>
                        <td><a href="{LOG_DOWNLOAD}" target="_blank">{PHP.L.dbviewstructure_download_file}</a></td>
                        <td><span class="badge bg-primary">{LOG_FORMAT}</span></td>
                        <td>{LOG_TABLES}</td>
                        <td>{LOG_WITH_DATA}</td>
                        <td>{LOG_DATE}</td>
					</tr>
                    <!-- END: LOG_ROW -->
                    <!-- BEGIN: NO_LOGS -->
                    <tr><td colspan="6" class="text-center">{PHP.L.dbviewstructure_no_logs}</td></tr>
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