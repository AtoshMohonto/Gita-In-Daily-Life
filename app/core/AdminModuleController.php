<?php
declare(strict_types=1);

/**
 * Generic list/create/edit/delete engine driven by a per-module field-config
 * array (see public/admin/modules/*\/config.php for examples). Adding a new
 * admin module is "write a config file", not "write three pages" — see §26
 * of the project spec ("reusable admin components").
 *
 * Supported field types: text, slug, textarea, richtext, number, date,
 * checkbox, select, relation (single FK), multiselect (many-to-many pivot).
 */
final class AdminModuleController
{
    private array $config;
    private string $modelClass;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->modelClass = $config['model'];
    }

    public function handle(): void
    {
        $action = Request::query('action', 'list');

        if ($action === 'save' && Request::isPost()) {
            $this->save();
            return;
        }
        if ($action === 'delete' && Request::isPost()) {
            $this->delete();
            return;
        }

        $this->renderHeader();
        if (in_array($action, ['create', 'edit'], true)) {
            $this->renderForm($action);
        } else {
            $this->renderList();
        }
        $this->renderFooter();
    }

    // ---------------------------------------------------------------- list

    private function renderList(): void
    {
        $modelClass = $this->modelClass;
        $page = max(1, (int) Request::query('page', 1));
        $perPage = $this->config['per_page'] ?? 15;
        $q = trim((string) Request::query('q', ''));

        if ($q !== '' && !empty($this->config['search_columns'])) {
            $all = $modelClass::search($this->config['search_columns'], $q, [], $this->config['order_by'] ?? '', 500);
            $total = count($all);
            $rows = array_slice($all, ($page - 1) * $perPage, $perPage);
        } else {
            $total = $modelClass::count();
            $rows = $modelClass::where([], $this->config['order_by'] ?? '', $perPage, ($page - 1) * $perPage);
        }
        $totalPages = max(1, (int) ceil($total / $perPage));

        echo '<div class="d-flex justify-content-between align-items-center mb-4">';
        echo '<h1 class="h3 mb-0">' . e($this->config['title']) . '</h1>';
        echo '<a href="' . e($this->baseUrl()) . '?action=create" class="btn btn-primary btn-sm">+ Add ' . e(rtrim((string) $this->config['title'], 's')) . '</a>';
        echo '</div>';

        $this->renderFlash();

        echo '<form method="get" class="mb-3"><div class="input-group" style="max-width:320px;">';
        echo '<input type="text" name="q" class="form-control" placeholder="Search..." value="' . e($q) . '">';
        echo '<button class="btn btn-outline-secondary" type="submit">Search</button>';
        echo '</div></form>';

        echo '<div class="table-responsive"><table class="table table-hover bg-white align-middle shadow-sm">';
        echo '<thead class="table-light"><tr>';
        foreach ($this->config['list_columns'] as $label) {
            echo '<th>' . e($label) . '</th>';
        }
        echo '<th class="text-end">Actions</th></tr></thead><tbody>';

        if ($rows === []) {
            echo '<tr><td colspan="' . (count($this->config['list_columns']) + 1) . '" class="text-center text-muted py-4">No records found.</td></tr>';
        }

        foreach ($rows as $row) {
            echo '<tr>';
            foreach (array_keys($this->config['list_columns']) as $col) {
                $val = $row[$col] ?? '';
                if ($col === 'status') {
                    echo '<td><span class="badge bg-' . status_badge_class((string) $val) . '">' . e(status_label((string) $val)) . '</span></td>';
                } elseif ($col === 'featured') {
                    echo '<td>' . ((int) $val === 1 ? '<span class="badge bg-info text-dark">Featured</span>' : '') . '</td>';
                } else {
                    echo '<td>' . e(str_excerpt((string) $val, 60)) . '</td>';
                }
            }
            echo '<td class="text-end">';
            echo '<a href="' . e($this->baseUrl()) . '?action=edit&id=' . (int) $row[$modelClass::primaryKey()] . '" class="btn btn-sm btn-outline-secondary">Edit</a> ';
            echo '<form method="post" action="' . e($this->baseUrl()) . '?action=delete" class="d-inline" onsubmit="return confirm(\'Delete this record? This cannot be undone.\');">';
            echo Csrf::field();
            echo '<input type="hidden" name="id" value="' . (int) $row[$modelClass::primaryKey()] . '">';
            echo '<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>';
            echo '</form>';
            echo '</td></tr>';
        }
        echo '</tbody></table></div>';

        if ($totalPages > 1) {
            echo '<nav><ul class="pagination">';
            for ($p = 1; $p <= $totalPages; $p++) {
                $active = $p === $page ? ' active' : '';
                $qs = http_build_query(['page' => $p, 'q' => $q]);
                echo '<li class="page-item' . $active . '"><a class="page-link" href="' . e($this->baseUrl()) . '?' . $qs . '">' . $p . '</a></li>';
            }
            echo '</ul></nav>';
        }
    }

    // ---------------------------------------------------------------- form

    private function renderForm(string $mode): void
    {
        $id = Request::query('id');
        $item = [];
        if ($mode === 'edit' && $id) {
            $modelClass = $this->modelClass;
            $item = $modelClass::find($id) ?? [];
        }
        $recordId = isset($item['id']) ? (int) $item['id'] : null;

        echo '<div class="d-flex justify-content-between align-items-center mb-4">';
        echo '<h1 class="h3 mb-0">' . ($mode === 'edit' ? 'Edit ' : 'Add ') . e(rtrim((string) $this->config['title'], 's')) . '</h1>';
        echo '<a href="' . e($this->baseUrl()) . '" class="btn btn-outline-secondary btn-sm">&larr; Back to list</a>';
        echo '</div>';

        $this->renderFlash();

        echo '<form method="post" action="' . e($this->baseUrl()) . '?action=save" class="card card-body shadow-sm">';
        echo Csrf::field();
        if ($recordId) {
            echo '<input type="hidden" name="id" value="' . $recordId . '">';
        }
        foreach ($this->config['fields'] as $name => $field) {
            $value = $item[$name] ?? ($field['default'] ?? '');
            echo $this->renderField($name, $field, $value, $recordId);
        }
        echo '<button type="submit" class="btn btn-primary">Save</button> ';
        echo '<a href="' . e($this->baseUrl()) . '" class="btn btn-link">Cancel</a>';
        echo '</form>';
    }

    private function renderField(string $name, array $field, $value, ?int $recordId): string
    {
        $label = e($field['label'] ?? ucfirst(str_replace('_', ' ', $name)));
        $required = !empty($field['required']) ? 'required' : '';
        $id = 'f_' . $name;
        $html = '<div class="mb-3">';
        $html .= '<label class="form-label" for="' . $id . '">' . $label . (!empty($field['required']) ? ' <span class="text-danger">*</span>' : '') . '</label>';

        switch ($field['type']) {
            case 'textarea':
                $html .= '<textarea class="form-control" id="' . $id . '" name="' . $name . '" rows="3" ' . $required . '>' . e((string) $value) . '</textarea>';
                break;
            case 'richtext':
                $html .= '<textarea class="form-control richtext-editor" id="' . $id . '" name="' . $name . '" rows="8">' . e((string) $value) . '</textarea>';
                break;
            case 'checkbox':
                $checked = $value ? 'checked' : '';
                $html .= '<div class="form-check"><input type="checkbox" class="form-check-input" id="' . $id . '" name="' . $name . '" value="1" ' . $checked . '><label class="form-check-label" for="' . $id . '">Yes</label></div>';
                break;
            case 'select':
                $html .= '<select class="form-select" id="' . $id . '" name="' . $name . '" ' . $required . '>';
                foreach ($field['options'] as $optValue => $optLabel) {
                    $sel = ((string) $value === (string) $optValue) ? 'selected' : '';
                    $html .= '<option value="' . e((string) $optValue) . '" ' . $sel . '>' . e($optLabel) . '</option>';
                }
                $html .= '</select>';
                break;
            case 'relation':
                $options = $this->relationOptions($field);
                $html .= '<select class="form-select" id="' . $id . '" name="' . $name . '" ' . $required . '><option value="">-- Select --</option>';
                foreach ($options as $opt) {
                    $sel = ((string) $value === (string) $opt['id']) ? 'selected' : '';
                    $html .= '<option value="' . (int) $opt['id'] . '" ' . $sel . '>' . e($opt['label']) . '</option>';
                }
                $html .= '</select>';
                break;
            case 'multiselect':
                $options = $this->multiselectOptions($field);
                $selected = $recordId ? $this->multiselectSelected($field, $recordId) : [];
                $html .= '<select class="form-select" id="' . $id . '" name="' . $name . '[]" multiple size="6">';
                foreach ($options as $opt) {
                    $sel = in_array((int) $opt['id'], $selected, true) ? 'selected' : '';
                    $html .= '<option value="' . (int) $opt['id'] . '" ' . $sel . '>' . e($opt['label']) . '</option>';
                }
                $html .= '</select><div class="form-text">Ctrl/Cmd + click to select multiple.</div>';
                break;
            case 'number':
                $html .= '<input type="number" class="form-control" id="' . $id . '" name="' . $name . '" value="' . e((string) $value) . '" ' . $required . '>';
                break;
            case 'date':
                $html .= '<input type="date" class="form-control" id="' . $id . '" name="' . $name . '" value="' . e((string) $value) . '" ' . $required . '>';
                break;
            default:
                $html .= '<input type="text" class="form-control" id="' . $id . '" name="' . $name . '" value="' . e((string) $value) . '" ' . $required . '>';
        }

        if (!empty($field['help'])) {
            $html .= '<div class="form-text">' . e($field['help']) . '</div>';
        }

        return $html . '</div>';
    }

    // ---------------------------------------------------------------- save

    private function save(): void
    {
        if (!Csrf::verifyRequest()) {
            flash('error', 'Security check failed. Please try again.');
            header('Location: ' . $this->baseUrl());
            exit;
        }

        $id = Request::post('id');
        $modelClass = $this->modelClass;
        $data = [];
        $multiselects = [];

        foreach ($this->config['fields'] as $name => $field) {
            if ($field['type'] === 'multiselect') {
                $multiselects[$name] = $field;
                continue;
            }
            if ($field['type'] === 'checkbox') {
                $data[$name] = Request::post($name) ? 1 : 0;
                continue;
            }

            $value = Request::post($name);
            if (!empty($field['required']) && ($value === null || $value === '')) {
                flash('error', ($field['label'] ?? $name) . ' is required.');
                header('Location: ' . $this->baseUrl() . '?action=' . ($id ? 'edit&id=' . (int) $id : 'create'));
                exit;
            }
            $data[$name] = ($value === '' ? null : $value);
        }

        if (isset($this->config['fields']['slug'])) {
            $source = $this->config['fields']['slug']['source'] ?? 'name';
            $base = ($data['slug'] ?? '') !== '' ? (string) $data['slug'] : (string) ($data[$source] ?? '');
            $data['slug'] = $this->ensureUniqueSlug(slugify($base), $id);
        }

        $now = date('Y-m-d H:i:s');
        try {
            if ($id) {
                $data['updated_at'] = $now;
                $modelClass::update($id, $data);
                $recordId = (int) $id;
                activity_log('update', $this->config['table'], $recordId, 'Updated ' . $this->recordLabel($data, $recordId));
                flash('success', rtrim((string) $this->config['title'], 's') . ' updated.');
            } else {
                $data['created_at'] = $now;
                $data['updated_at'] = $now;
                $recordId = $modelClass::insert($data);
                activity_log('create', $this->config['table'], $recordId, 'Created ' . $this->recordLabel($data, $recordId));
                flash('success', rtrim((string) $this->config['title'], 's') . ' created.');
            }
        } catch (PDOException $e) {
            $message = str_contains($e->getMessage(), 'Duplicate entry')
                ? 'That combination already exists (e.g. this chapter/verse number is already used).'
                : 'Could not save this record. Please check the values and try again.';
            flash('error', $message);
            header('Location: ' . $this->baseUrl() . '?action=' . ($id ? 'edit&id=' . (int) $id : 'create'));
            exit;
        }

        foreach ($multiselects as $name => $field) {
            $selected = array_map('intval', (array) ($_POST[$name] ?? []));
            $this->syncPivot($field, $recordId, $selected);
        }

        header('Location: ' . $this->baseUrl());
        exit;
    }

    private function delete(): void
    {
        if (!Csrf::verifyRequest()) {
            flash('error', 'Security check failed.');
            header('Location: ' . $this->baseUrl());
            exit;
        }

        $id = (int) Request::post('id');
        $modelClass = $this->modelClass;
        $record = $modelClass::find($id);
        if ($record !== null) {
            $modelClass::delete($id);
            activity_log('delete', $this->config['table'], $id, 'Deleted ' . $this->recordLabel($record, $id));
            flash('success', rtrim((string) $this->config['title'], 's') . ' deleted.');
        }

        header('Location: ' . $this->baseUrl());
        exit;
    }

    // ------------------------------------------------------------- helpers

    private function recordLabel(array $data, int $id): string
    {
        return (string) ($data['name'] ?? $data['title'] ?? "#{$id}");
    }

    private function ensureUniqueSlug(string $slug, $excludeId = null): string
    {
        $modelClass = $this->modelClass;
        $original = $slug;
        $i = 2;
        while (true) {
            $existing = $modelClass::findBy('slug', $slug);
            if ($existing === null || (string) $existing[$modelClass::primaryKey()] === (string) $excludeId) {
                return $slug;
            }
            $slug = $original . '-' . $i;
            $i++;
        }
    }

    private function syncPivot(array $field, int $recordId, array $selectedIds): void
    {
        $pdo = Database::connection();
        $pdo->prepare("DELETE FROM {$field['pivot_table']} WHERE {$field['pivot_local_key']} = ?")->execute([$recordId]);
        if ($selectedIds === []) {
            return;
        }
        $stmt = $pdo->prepare("INSERT INTO {$field['pivot_table']} ({$field['pivot_local_key']}, {$field['pivot_foreign_key']}) VALUES (?, ?)");
        foreach ($selectedIds as $sid) {
            $stmt->execute([$recordId, $sid]);
        }
    }

    private function relationOptions(array $field): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT id, {$field['relation_label']} AS label FROM {$field['relation_table']} ORDER BY {$field['relation_order_by']}");
        return $stmt->fetchAll();
    }

    private function multiselectOptions(array $field): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT id, {$field['options_label']} AS label FROM {$field['options_table']} ORDER BY {$field['options_label']}");
        return $stmt->fetchAll();
    }

    private function multiselectSelected(array $field, int $recordId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT {$field['pivot_foreign_key']} FROM {$field['pivot_table']} WHERE {$field['pivot_local_key']} = ?");
        $stmt->execute([$recordId]);
        return array_map('intval', array_column($stmt->fetchAll(), $field['pivot_foreign_key']));
    }

    private function renderFlash(): void
    {
        $success = flash('success');
        $error = flash('error');
        if ($success) {
            echo '<div class="alert alert-success">' . e($success) . '</div>';
        }
        if ($error) {
            echo '<div class="alert alert-danger">' . e($error) . '</div>';
        }
    }

    private function renderHeader(): void
    {
        $pageTitle = $this->config['title'];
        $activeModule = $this->config['table'];
        require ROOT_PATH . '/public/admin/includes/header.php';
    }

    private function renderFooter(): void
    {
        require ROOT_PATH . '/public/admin/includes/footer.php';
    }

    private function baseUrl(): string
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }
}
