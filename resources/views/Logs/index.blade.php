<x-app-layout>
    @php
        $logCollection = $logs ?? ($activities ?? ($activityLogs ?? collect()));
        $totalLogsCount = method_exists($logCollection, 'total') ? $logCollection->total() : $logCollection->count();

        $createCount = 0;
        $updateCount = 0;
        $deleteCount = 0;

        foreach ($logCollection as $entry) {
            $rawAct = strtolower(trim($entry->action ?? ($entry->type ?? ($entry->activity ?? ''))));
            if (str_contains($rawAct, 'tambah') || str_contains($rawAct, 'buat') || str_contains($rawAct, 'create') || str_contains($rawAct, 'store')) {
                $createCount++;
            } elseif (str_contains($rawAct, 'edit') || str_contains($rawAct, 'update') || str_contains($rawAct, 'ubah') || str_contains($rawAct, 'perbarui')) {
                $updateCount++;
            } elseif (str_contains($rawAct, 'hapus') || str_contains($rawAct, 'delete') || str_contains($rawAct, 'destroy')) {
                $deleteCount++;
            }
        }

        $statAll = $totalAllLogs ?? ($stats['all'] ?? $totalLogsCount);
        $statCreate = $totalCreateLogs ?? ($stats['create'] ?? $createCount);
        $statUpdate = $totalUpdateLogs ?? ($stats['update'] ?? $updateCount);
        $statDelete = $totalDeleteLogs ?? ($stats['delete'] ?? $deleteCount);

        $selectedAction = request('action', request('type', ''));
        $selectedSearch = request('search', request('q', ''));
        $selectedFrom = request('from_date', '');
        $selectedTo = request('to_date', '');

        $buildLogPayload = function ($log, string $bucket): array {
            $rawSource = (string) (
                $log->description
                ?? ($log->detail
                ?? ($log->message
                ?? ($log->action ?? '-')))
            );

            $decoded = html_entity_decode($rawSource, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $decoded = trim((string) preg_replace('/\s+/', ' ', $decoded));

            $splitText = str_ireplace(
                ['\\vert{}', '[DETAIL]', '[PERUBAHAN]'],
                ["\n", "\n", "\n"],
                $decoded
            );
            $splitText = preg_replace('/\s*\|\s*/', "\n", (string) $splitText);

            $rawParts = array_values(array_filter(
                array_map('trim', explode("\n", (string) $splitText)),
                fn($p) => $p !== ''
            ));

            $labelNames = [
                'Kode Barang',
                'Kode',
                'Code',
                'Nama Barang',
                'Nama',
                'Kategori',
                'Jumlah',
                'Jml',
                'Stok',
                'Qty',
                'Kondisi',
                'Lokasi',
                'Ruangan',
                'Tempat',
                'Keterangan',
                'Catatan',
                'Deskripsi',
                'Keterangan Tambahan',
            ];
            $labelRegex = implode('|', array_map(fn($l) => preg_quote($l, '/'), $labelNames));

            $lines = [];
            foreach ($rawParts as $part) {
                $chunks = preg_split('/\s+(?=(?:' . $labelRegex . ')\s*:)/iu', (string) $part);
                foreach ($chunks as $chunk) {
                    $chunk = trim((string) $chunk);
                    if ($chunk !== '') {
                        $lines[] = $chunk;
                    }
                }
            }

            $firstLine = $lines[0] ?? $decoded;
            $props = is_array($log->properties ?? null) ? $log->properties : [];

            $code = (string) (
                $log->item_code
                ?? ($log->code
                ?? ($log->item->code
                ?? ($log->item->item_code
                ?? ($props['code'] ?? ($props['item_code'] ?? '')))))
            );

            $name = (string) (
                $log->item_name
                ?? ($log->item->name
                ?? ($props['name'] ?? ($props['item_name'] ?? '')))
            );

            if (preg_match('/^(?:Menambahkan\s*barang(?:\s*baru)?|Memperbarui\s*data\s*barang|Menghapus\s*barang)\s*:\s*(.+)$/iu', $firstLine, $mHead)) {
                $body = trim($mHead[1]);
                if (preg_match('/^(.+?)\s*\(\s*(?:Kode(?:\s*Barang)?|Code)\s*:\s*([^)]+)\)\s*$/iu', $body, $mParen)) {
                    if ($name === '') $name = trim($mParen[1]);
                    if ($code === '') $code = trim($mParen[2]);
                } elseif (preg_match('/^(.+?)\s*\(\s*(?:Kode(?:\s*Barang)?|Code)\s*:\s*([^)]+)$/iu', $body, $mOpenParen)) {
                    if ($name === '') $name = trim($mOpenParen[1]);
                    if ($code === '') $code = trim($mOpenParen[2]);
                } else {
                    $stripped = preg_replace('/\s*\([^)]*$/', '', $body);
                    if ($name === '') $name = trim((string) $stripped);
                }
            }

            $summary = $firstLine;
            if ($bucket === 'delete') {
                if ($name !== '') {
                    $summary = 'Menghapus barang: ' . $name;
                } elseif (preg_match('/^Menghapus\s*barang\s*:\s*([^(\n]+)/iu', $firstLine, $mDel)) {
                    $summary = 'Menghapus barang: ' . trim($mDel[1]);
                }
            } elseif ($bucket === 'update') {
                if (preg_match('/^(Memperbarui\s*data\s*barang\s*:\s*[^(\n]+)/iu', $firstLine, $mUpd)) {
                    $summary = trim($mUpd[1]);
                } elseif ($name !== '' && !str_contains(strtolower($firstLine), strtolower($name))) {
                    $summary = 'Memperbarui data barang: ' . $name;
                }
            } elseif ($bucket === 'create') {
                if (preg_match('/^(Menambahkan\s*barang(?:\s*baru)?\s*:\s*[^(\n]+)/iu', $firstLine, $mCre)) {
                    $summary = trim($mCre[1]);
                }
            }

            $category = (string) ($log->category_name ?? ($log->category ?? ($log->item->category->name ?? ($props['category'] ?? ''))));
            $quantity = (string) ($log->quantity ?? ($log->stock ?? ($log->item->quantity ?? ($log->item->stock ?? ($props['quantity'] ?? ($props['stock'] ?? ''))))));
            $condition = (string) ($log->condition ?? ($log->item->condition ?? ($props['condition'] ?? '')));
            $location = (string) ($log->location ?? ($log->room ?? ($log->item->location ?? ($props['location'] ?? ($props['room'] ?? '')))));
            $note = (string) ($log->note ?? ($log->notes ?? ($log->description_note ?? ($props['note'] ?? ($props['notes'] ?? '')))));

            $changes = [];
            $extraFields = [];

            if (preg_match('/^([^:]+):\s*(.+?)\s*(?:->|→)\s*(.+)$/u', $firstLine, $mFirstKvDiff)) {
                $changes[] = [
                    'field' => trim($mFirstKvDiff[1]),
                    'before' => trim($mFirstKvDiff[2]),
                    'after' => trim($mFirstKvDiff[3]),
                ];
            } elseif (preg_match('/^([A-Za-z0-9._-]+(?:\s+[A-Za-z0-9._-]+)*)\s*(?:->|→)\s*([A-Za-z0-9._-]+(?:\s+[A-Za-z0-9._-]+)*)$/u', $firstLine, $mFirstBareDiff)) {
                $bVal = trim($mFirstBareDiff[1]);
                $aVal = trim($mFirstBareDiff[2]);
                $isCodeLike = preg_match('/^[A-Z]{2,}-[A-Z0-9-]+$/i', $bVal) || preg_match('/^[A-Z]{2,}-[A-Z0-9-]+$/i', $aVal);
                $changes[] = [
                    'field' => $isCodeLike ? 'Kode Barang' : 'Data',
                    'before' => $bVal,
                    'after' => $aVal,
                ];
                if ($isCodeLike && $code === '') {
                    $code = $aVal;
                }
            }

            for ($i = 1; $i < count($lines); $i++) {
                $seg = trim($lines[$i]);
                if ($seg === '') continue;

                if (preg_match('/^([^:]+):\s*(.+?)\s*(?:->|→)\s*(.+)$/u', $seg, $mDiff)) {
                    $changes[] = [
                        'field' => trim($mDiff[1]),
                        'before' => trim($mDiff[2]),
                        'after' => trim($mDiff[3]),
                    ];
                    continue;
                }

                if (preg_match('/^(.+?)\s*(?:->|→)\s*(.+)$/u', $seg, $mBareDiff)) {
                    $bVal = trim($mBareDiff[1]);
                    $aVal = trim($mBareDiff[2]);
                    $isCodeLike = preg_match('/^[A-Z]{2,}-[A-Z0-9-]+$/i', $bVal) || preg_match('/^[A-Z]{2,}-[A-Z0-9-]+$/i', $aVal);
                    $changes[] = [
                        'field' => $isCodeLike ? 'Kode Barang' : 'Perubahan',
                        'before' => $bVal,
                        'after' => $aVal,
                    ];
                    if ($isCodeLike && $code === '') {
                        $code = $aVal;
                    }
                    continue;
                }

                if (preg_match('/^([^:]+):\s*(.+)$/u', $seg, $mKv)) {
                    $label = trim($mKv[1]);
                    $val = trim($mKv[2]);
                    $labelLower = strtolower($label);

                    if (str_contains($labelLower, 'kode') || $labelLower === 'code') {
                        if ($code === '') $code = $val;
                    } elseif (str_contains($labelLower, 'nama barang') || $labelLower === 'nama') {
                        if ($name === '') $name = $val;
                    } elseif (str_contains($labelLower, 'kategori')) {
                        if ($category === '') $category = $val;
                    } elseif (str_contains($labelLower, 'jumlah') || in_array($labelLower, ['jml', 'stok', 'qty'])) {
                        if ($quantity === '') $quantity = $val;
                    } elseif (str_contains($labelLower, 'kondisi')) {
                        if ($condition === '') $condition = $val;
                    } elseif (str_contains($labelLower, 'lokasi') || in_array($labelLower, ['ruangan', 'tempat'])) {
                        if ($location === '') $location = $val;
                    } elseif (str_contains($labelLower, 'keterangan') || in_array($labelLower, ['catatan', 'deskripsi'])) {
                        if ($note === '') $note = $val;
                    } else {
                        $extraFields[] = ['label' => $label, 'value' => $val];
                    }
                } else {
                    if ($note === '') {
                        $note = $seg;
                    } else {
                        $extraFields[] = ['label' => 'Keterangan Tambahan', 'value' => $seg];
                    }
                }
            }

            if (empty($changes) && isset($log->changes) && is_iterable($log->changes)) {
                foreach ($log->changes as $k => $v) {
                    if (is_array($v) && (isset($v['old']) || isset($v['before']) || array_key_exists(0, $v))) {
                        $changes[] = [
                            'field' => is_string($k) ? ucfirst(str_replace('_', ' ', $k)) : ($v['field'] ?? 'Data'),
                            'before' => $v['old'] ?? ($v['before'] ?? ($v[0] ?? '-')),
                            'after' => $v['new'] ?? ($v['after'] ?? ($v[1] ?? '-')),
                        ];
                    }
                }
            }

            $fields = [];
            if ($name !== '') $fields[] = ['key' => 'name', 'label' => 'Nama Barang', 'value' => $name];
            if ($code !== '') $fields[] = ['key' => 'code', 'label' => 'Kode Barang', 'value' => $code];
            if ($category !== '') $fields[] = ['key' => 'category', 'label' => 'Kategori', 'value' => $category];
            if ($quantity !== '') $fields[] = ['key' => 'quantity', 'label' => 'Jumlah', 'value' => $quantity];
            if ($condition !== '') $fields[] = ['key' => 'condition', 'label' => 'Kondisi', 'value' => $condition];
            if ($location !== '') $fields[] = ['key' => 'location', 'label' => 'Lokasi', 'value' => $location];
            if ($note !== '') $fields[] = ['key' => 'note', 'label' => 'Keterangan', 'value' => $note];

            foreach ($extraFields as $ef) {
                $fields[] = ['key' => 'extra', 'label' => $ef['label'], 'value' => $ef['value']];
            }

            return [
                'raw' => str_replace("\n", ' | ', implode("\n", $lines)),
                'decoded_raw' => $decoded,
                'summary' => $summary,
                'name' => $name,
                'code' => $code,
                'fields' => $fields,
                'changes' => $changes,
            ];
        };
    @endphp

    <style>
        .tmb-audit-wrap {
            width: 100%;
            max-width: 86rem;
            margin: 0 auto;
            padding: 14px 0 26px 0;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .tmb-audit-shell {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 22px;
            box-shadow: 0 10px 26px -14px rgba(15, 23, 42, 0.1);
            overflow: hidden;
        }

        .tmb-audit-hero {
            background: linear-gradient(125deg, #0b1324 0%, #0f172a 55%, #1d4ed8 100%);
            color: #ffffff;
            padding: 18px 22px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        @media (min-width: 768px) {
            .tmb-audit-hero {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 20px 24px;
            }
        }

        .tmb-audit-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: fit-content;
            background-color: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #eff6ff;
            border-radius: 8px;
            padding: 3px 9px;
            font-size: 0.625rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            line-height: 1.2;
            margin-bottom: 5px;
        }

        .tmb-status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 800;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .tmb-stat-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }
        @media (min-width: 1024px) {
            .tmb-stat-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 14px;
            }
        }

        .tmb-stat-card {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            cursor: pointer;
            transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }
        .tmb-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px -12px rgba(15, 23, 42, 0.18);
        }

        .tmb-stat-card[data-tone="blue"] { border-color: #bfdbfe; background-color: #f8fbff; }
        .tmb-stat-card[data-tone="blue"].is-selected { background-color: #eff6ff; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14); }
        .tmb-stat-card[data-tone="green"] { border-color: #a7f3d0; background-color: #f6fdf9; }
        .tmb-stat-card[data-tone="green"].is-selected { background-color: #ecfdf5; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.14); }
        .tmb-stat-card[data-tone="indigo"] { border-color: #c7d2fe; background-color: #f7f8ff; }
        .tmb-stat-card[data-tone="indigo"].is-selected { background-color: #eef2ff; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.14); }
        .tmb-stat-card[data-tone="red"] { border-color: #fecaca; background-color: #fff8f8; }
        .tmb-stat-card[data-tone="red"].is-selected { background-color: #fef2f2; border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.14); }

        .tmb-stat-label { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #475569; margin-bottom: 6px; }
        .tmb-stat-value { font-size: 1.5rem; font-weight: 900; line-height: 1.1; letter-spacing: -0.02em; }
        .tmb-stat-icon { width: 34px; height: 34px; min-width: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }

        .tmb-filter-panel { background-color: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 16px; padding: 15px 16px; margin-bottom: 18px; }
        .tmb-filter-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
        @media (min-width: 768px) {
            .tmb-filter-grid { grid-template-columns: 1.5fr 1fr 1fr 1fr auto; align-items: end; }
        }
        .tmb-filter-label { display: block; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #334155; margin-bottom: 5px; }
        .tmb-filter-control { width: 100%; min-width: 0; padding: 9px 12px; background-color: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; border-radius: 11px; font-size: 0.8125rem; font-weight: 600; transition: border-color 0.15s ease, box-shadow 0.15s ease; }
        .tmb-filter-control:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14); }

        .tmb-btn-filter { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 16px; border-radius: 11px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; border: 1.5px solid #1e40af; font-size: 0.8125rem; font-weight: 800; cursor: pointer; white-space: nowrap; }
        .tmb-btn-clear { display: inline-flex; align-items: center; justify-content: center; gap: 5px; padding: 9px 12px; border-radius: 11px; background-color: #ffffff; color: #334155; border: 1.5px solid #cbd5e1; font-size: 0.8125rem; font-weight: 700; text-decoration: none; cursor: pointer; white-space: nowrap; }
        .tmb-list-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 12px; }

        .tmb-audit-mobile { display: flex; flex-direction: column; gap: 12px; }
        @media (min-width: 768px) { .tmb-audit-mobile { display: none; } }

        .tmb-audit-card { background-color: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 18px; padding: 14px 15px; cursor: pointer; transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease; }
        .tmb-audit-card:hover, .tmb-audit-card:active { background-color: #eff6ff; border-color: #93c5fd; box-shadow: 0 8px 18px -12px rgba(37, 99, 235, 0.25); }
        .tmb-audit-card-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; padding-bottom: 9px; border-bottom: 1px solid #e2e8f0; }
        .tmb-audit-card-desc { margin-top: 10px; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 12px; font-size: 0.8125rem; font-weight: 700; color: #0f172a; line-height: 1.4; word-break: break-word; }
        .tmb-audit-card-foot { margin-top: 10px; display: flex; align-items: center; justify-content: space-between; gap: 8px; font-size: 0.72rem; font-weight: 700; color: #475569; }

        .tmb-audit-table-shell { display: none; }
        @media (min-width: 768px) {
            .tmb-audit-table-shell { display: block; width: 100%; overflow-x: auto; border: 1.5px solid #cbd5e1; border-radius: 16px; background-color: #ffffff; }
        }
        .tmb-audit-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.8125rem; color: #0f172a; }
        .tmb-audit-table thead { background-color: #f8fafc; border-bottom: 1.5px solid #cbd5e1; }
        .tmb-audit-table thead th { padding: 12px 15px; font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #334155; white-space: nowrap; }
        .tmb-audit-table tbody tr.tmb-audit-row { border-bottom: 1px solid #e2e8f0; cursor: pointer; transition: background-color 0.14s ease; }
        .tmb-audit-table tbody tr.tmb-audit-row:last-child { border-bottom: none; }
        .tmb-audit-table tbody tr.tmb-audit-row:hover { background-color: #eff6ff; }
        .tmb-audit-table tbody td { padding: 14px 15px; vertical-align: middle; }

        .tmb-rincian-link { display: inline-flex; align-items: center; gap: 3px; color: #2563eb; font-size: 0.75rem; font-weight: 800; white-space: nowrap; transition: color 0.15s ease, transform 0.15s ease; }
        .tmb-audit-row:hover .tmb-rincian-link, .tmb-audit-card:hover .tmb-rincian-link { color: #1d4ed8; transform: translateX(2px); }

        /* Modal Audit Forensik - Nyaman di HP & Tetap Mantap di Desktop */
        .tmb-detail-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 60;
            background-color: rgba(15, 23, 42, 0.62);
            backdrop-filter: blur(7px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }
        @media (min-width: 640px) {
            .tmb-detail-modal-backdrop {
                padding: 16px;
            }
        }
        .tmb-detail-modal-backdrop.is-open { display: flex; }

        .tmb-detail-modal-card {
            width: 100%;
            max-width: 37rem;
            max-height: 86dvh;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 34px 76px -18px rgba(15, 23, 42, 0.48);
            border: 1.5px solid #cbd5e1;
            display: flex;
            flex-direction: column;
            animation: tmbDetailPop 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @media (min-width: 640px) {
            .tmb-detail-modal-card {
                max-height: 90vh;
                border-radius: 26px;
            }
        }
        @keyframes tmbDetailPop {
            from { opacity: 0; transform: translateY(10px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .tmb-detail-modal-head {
            background: linear-gradient(125deg, #0b1324 0%, #1d4ed8 54%, #3b82f6 100%);
            color: #ffffff;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .tmb-detail-modal-head {
                padding: 18px 22px;
                gap: 12px;
            }
        }

        .tmb-detail-modal-badge {
            background-color: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.34);
            border-radius: 10px;
            padding: 5px 10px;
            font-size: 0.72rem;
            font-weight: 800;
            color: #ffffff;
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .tmb-detail-modal-badge {
                border-radius: 12px;
                padding: 7px 12px;
                font-size: 0.75rem;
            }
        }

        .tmb-detail-modal-body {
            padding: 12px 14px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2f6 100%);
        }
        @media (min-width: 640px) {
            .tmb-detail-modal-body {
                padding: 18px 22px;
                gap: 12px;
            }
        }

        .tmb-detail-grid-2 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }
        @media (min-width: 640px) {
            .tmb-detail-grid-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }
        }

        .tmb-detail-tile {
            background: linear-gradient(180deg, #ffffff 0%, #fcfdfe 100%);
            border: 1.5px solid #e2e8f0;
            border-radius: 13px;
            padding: 10px 12px;
            box-shadow: 0 3px 10px -6px rgba(15, 23, 42, 0.16);
        }
        @media (min-width: 640px) {
            .tmb-detail-tile {
                border-radius: 16px;
                padding: 12px 15px;
            }
        }

        .tmb-detail-tile-label {
            font-size: 0.625rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 3px;
        }
        @media (min-width: 640px) {
            .tmb-detail-tile-label {
                font-size: 0.65rem;
                margin-bottom: 4px;
            }
        }

        .tmb-detail-tile-val {
            font-size: 0.84rem;
            font-weight: 700;
            color: #0f172a;
            word-break: break-word;
            line-height: 1.38;
        }
        @media (min-width: 640px) {
            .tmb-detail-tile-val {
                font-size: 0.9rem;
                line-height: 1.4;
            }
        }

        .tmb-detail-tile-sub {
            font-size: 0.7rem;
            font-weight: 500;
            color: #64748b;
            margin-top: 2px;
        }
        @media (min-width: 640px) {
            .tmb-detail-tile-sub {
                font-size: 0.72rem;
            }
        }

        .tmb-detail-tile-muted {
            font-size: 0.75rem;
            font-weight: 600;
            font-style: italic;
            color: #94a3b8;
        }
        @media (min-width: 640px) {
            .tmb-detail-tile-muted {
                font-size: 0.78rem;
            }
        }

        .tmb-modal-section-box {
            background: linear-gradient(160deg, #ffffff 0%, #f8fafc 100%);
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            padding: 12px;
            box-shadow: 0 6px 18px -10px rgba(15, 23, 42, 0.16);
        }
        @media (min-width: 640px) {
            .tmb-modal-section-box {
                border-radius: 22px;
                padding: 16px;
            }
        }

        .tmb-modal-section-box.is-update-modern {
            background: linear-gradient(155deg, #eef2ff 0%, #f8faff 55%, #ffffff 100%);
            border-color: #a5b4fc;
        }

        .tmb-modal-section-box.is-create-modern {
            background: linear-gradient(160deg, #ecfdf5 0%, #f4fdf8 52%, #ffffff 100%);
            border-color: #6ee7b7;
        }

        .tmb-modal-section-box.is-delete-modern {
            background: linear-gradient(160deg, #fff1f2 0%, #fff7f8 52%, #ffffff 100%);
            border-color: #fda4af;
        }

        .tmb-modal-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px dashed rgba(100, 116, 139, 0.28);
        }
        @media (min-width: 640px) {
            .tmb-modal-section-head {
                gap: 10px;
                margin-bottom: 12px;
                padding-bottom: 10px;
            }
        }

        .tmb-modal-section-title {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #1d4ed8;
        }
        @media (min-width: 640px) {
            .tmb-modal-section-title {
                gap: 8px;
                font-size: 0.7rem;
                letter-spacing: 0.06em;
            }
        }

        .tmb-modal-section-box.is-update-modern .tmb-modal-section-title { color: #3730a3; }
        .tmb-modal-section-box.is-create-modern .tmb-modal-section-title { color: #047857; }
        .tmb-modal-section-box.is-delete-modern .tmb-modal-section-title { color: #be123c; }

        .tmb-section-icon-badge {
            width: 24px;
            height: 24px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .tmb-section-icon-badge {
                width: 26px;
                height: 26px;
                border-radius: 9px;
            }
        }
        .is-update-modern .tmb-section-icon-badge { background-color: #e0e7ff; color: #3730a3; border: 1px solid #a5b4fc; }
        .is-create-modern .tmb-section-icon-badge { background-color: #d1fae5; color: #047857; border: 1px solid #6ee7b7; }
        .is-delete-modern .tmb-section-icon-badge { background-color: #ffe4e6; color: #be123c; border: 1px solid #fda4af; }

        .tmb-audit-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 9999px;
            padding: 3px 10px;
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        @media (min-width: 640px) {
            .tmb-audit-pill {
                padding: 3px 11px;
                font-size: 0.625rem;
            }
        }
        .tmb-audit-pill.is-update { background-color: #e0e7ff; color: #3730a3; border: 1px solid #a5b4fc; }
        .tmb-audit-pill.is-create { background-color: #dcfce7; color: #047857; border: 1px solid #6ee7b7; }
        .tmb-audit-pill.is-delete { background-color: #ffe4e6; color: #be123c; border: 1px solid #fda4af; }

        .tmb-diff-row {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 13px;
            padding: 10px 12px;
            margin-bottom: 8px;
            box-shadow: 0 2px 7px -5px rgba(15, 23, 42, 0.14);
        }
        @media (min-width: 640px) {
            .tmb-diff-row {
                border-radius: 15px;
                padding: 12px 14px;
                margin-bottom: 9px;
            }
        }
        .tmb-diff-row:last-child { margin-bottom: 0; }

        .tmb-diff-badges {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            flex-wrap: wrap;
        }
        @media (min-width: 640px) {
            .tmb-diff-badges {
                gap: 8px;
            }
        }

        .tmb-diff-before {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            padding: 4px 10px;
            border-radius: 9px;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #b91c1c;
            border: 1px solid #fca5a5;
            font-size: 0.78rem;
            font-weight: 800;
            word-break: break-word;
        }
        @media (min-width: 640px) {
            .tmb-diff-before {
                min-width: 32px;
                padding: 5px 12px;
                border-radius: 10px;
                font-size: 0.8125rem;
            }
        }

        .tmb-diff-after {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            padding: 4px 10px;
            border-radius: 9px;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #047857;
            border: 1px solid #6ee7b7;
            font-size: 0.78rem;
            font-weight: 800;
            word-break: break-word;
        }
        @media (min-width: 640px) {
            .tmb-diff-after {
                min-width: 32px;
                padding: 5px 12px;
                border-radius: 10px;
                font-size: 0.8125rem;
            }
        }

        .tmb-identity-card {
            border-radius: 14px;
            padding: 11px 12px;
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 3px 10px -6px rgba(15, 23, 42, 0.16);
        }
        @media (min-width: 640px) {
            .tmb-identity-card {
                border-radius: 17px;
                padding: 13px 15px;
            }
        }
        .is-create-modern .tmb-identity-card {
            border-left: 4px solid #10b981;
            background: linear-gradient(120deg, #ffffff 0%, #f0fdf4 100%);
        }
        .is-delete-modern .tmb-identity-card {
            border-left: 4px solid #ef4444;
            background: linear-gradient(120deg, #ffffff 0%, #fff1f2 100%);
        }

        .tmb-identity-name {
            font-size: 0.92rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            word-break: break-word;
        }
        @media (min-width: 640px) {
            .tmb-identity-name {
                font-size: 1rem;
            }
        }

        .tmb-identity-code-row {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(148, 163, 184, 0.25);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            flex-wrap: wrap;
        }

        .tmb-code-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 9px;
            border-radius: 8px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.7rem;
            font-weight: 800;
            word-break: break-all;
        }
        @media (min-width: 640px) {
            .tmb-code-chip {
                padding: 4px 10px;
                border-radius: 9px;
                font-size: 0.75rem;
            }
        }
        .is-create-modern .tmb-code-chip {
            background-color: #dcfce7;
            color: #047857;
            border: 1px solid #86efac;
        }
        .is-delete-modern .tmb-code-chip {
            background-color: #ffe4e6;
            color: #be123c;
            border: 1px solid #fda4af;
        }

        .tmb-delete-subhead {
            margin-top: 10px;
            margin-bottom: 6px;
            font-size: 0.625rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9f1239;
        }
        @media (min-width: 640px) {
            .tmb-delete-subhead {
                margin-top: 12px;
                margin-bottom: 8px;
            }
        }

        .tmb-item-tiles-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
        }
        @media (min-width: 640px) {
            .tmb-item-tiles-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }
        }

        .tmb-item-mini-tile {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 9px 12px;
            box-shadow: 0 2px 8px -5px rgba(15, 23, 42, 0.14);
        }
        @media (min-width: 640px) {
            .tmb-item-mini-tile {
                border-radius: 15px;
                padding: 11px 14px;
            }
        }
        .tmb-item-mini-tile.is-full { grid-column: 1 / -1; }
        .tmb-item-mini-tile.is-missing {
            background-color: rgba(255, 255, 255, 0.68);
            border-style: dashed;
        }
        .is-delete-modern .tmb-item-mini-tile { border-left: 3.5px solid #ef4444; }

        .tmb-delete-audit-strip {
            margin-top: 8px;
            background-color: rgba(255, 255, 255, 0.9);
            border: 1.5px solid #fecdd3;
            border-radius: 12px;
            padding: 9px 12px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
        }
        @media (min-width: 640px) {
            .tmb-delete-audit-strip {
                margin-top: 10px;
                border-radius: 15px;
                padding: 11px 14px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .tmb-delete-audit-full { grid-column: 1 / -1; }

        .tmb-detail-modal-foot {
            background-color: #ffffff;
            border-top: 1.5px solid #e2e8f0;
            padding: 11px 16px;
            display: flex;
            justify-content: flex-end;
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .tmb-detail-modal-foot {
                padding: 13px 22px;
            }
        }

        .tmb-detail-close-btn {
            padding: 8px 18px;
            border-radius: 11px;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            color: #ffffff;
            border: 1.5px solid #1e3a8a;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 14px -6px rgba(29, 78, 216, 0.5);
        }
        @media (min-width: 640px) {
            .tmb-detail-close-btn {
                padding: 9px 20px;
                border-radius: 12px;
                font-size: 0.8125rem;
            }
        }

        .swal2-popup.tmb-swal-card { width: min(92vw, 420px) !important; border-radius: 22px !important; border: 1.5px solid #cbd5e1 !important; padding: 24px 22px !important; box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.28) !important; }
        .swal2-title.tmb-swal-title { font-size: 1.15rem !important; font-weight: 800 !important; color: #0f172a !important; padding-top: 4px !important; }
        .swal2-html-container.tmb-swal-text { font-size: 0.85rem !important; font-weight: 500 !important; color: #475569 !important; margin-top: 6px !important; }
        .swal2-actions.tmb-swal-actions { gap: 10px !important; margin-top: 18px !important; }
        .tmb-swal-btn { border-radius: 12px !important; padding: 10px 18px !important; font-size: 0.825rem !important; font-weight: 700 !important; }
    </style>

    <!-- Header Halaman -->
    <x-slot name="header">
        <div class="flex items-start sm:items-center justify-between gap-2.5 py-0.5">
            <div class="flex items-start sm:items-center gap-2.5 min-w-0 flex-1">
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); color: #ffffff; border: 1.5px solid #1e3a8a;" class="p-2 sm:p-2.5 rounded-xl shadow-sm flex-shrink-0 mt-0.5 sm:mt-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 sm:gap-2.5 flex-wrap">
                        <h2 style="color: #090d16; font-weight: 900; letter-spacing: -0.025em;" class="text-base sm:text-xl leading-tight">
                            Log Aktivitas
                        </h2>
                        <span style="background-color: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd;" class="inline-flex items-center text-[9px] sm:text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full whitespace-nowrap flex-shrink-0">
                            Inventaris TMB
                        </span>
                    </div>
                    <p style="color: #334155; font-weight: 600;" class="flex items-start sm:items-center gap-1.5 text-[11px] sm:text-xs leading-snug mt-1">
                        <span style="background-color: #2563eb;" class="w-1.5 h-1.5 rounded-full flex-shrink-0 mt-1 sm:mt-0"></span>
                        <span>
                            Jejak audit inventaris Teknik &amp; Media Baru
                            <span style="color: #1d4ed8; font-weight: 700;">RRI Bukittinggi</span>.
                        </span>
                    </p>
                </div>
            </div>

            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1;" class="hidden sm:block px-3.5 py-1.5 rounded-xl shadow-2xs text-right flex-shrink-0">
                <p style="color: #0f172a; font-weight: 800;" class="text-xs tracking-tight leading-none">RRI Bukittinggi</p>
                <p style="color: #2563eb; font-weight: 900;" class="text-[10px] tracking-wider uppercase mt-0.5 leading-none">DIVISI TMB</p>
            </div>
        </div>
    </x-slot>

    <!-- Kontainer Utama -->
    <div style="padding-left: env(safe-area-inset-left); padding-right: env(safe-area-inset-right);">
        <div class="tmb-audit-wrap">
            <div class="tmb-audit-shell">

                <!-- Hero Banner Log Aktivitas Sistem -->
                <div class="tmb-audit-hero">
                    <div>
                        <div class="tmb-audit-tag">
                            <span style="background-color: #60a5fa;" class="w-1.5 h-1.5 rounded-full"></span>
                            <span>Audit Trail &amp; Riwayat Sistem</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-extrabold tracking-tight text-white leading-snug">
                            Log Aktivitas Sistem
                        </h3>
                        <p class="text-xs text-blue-100 mt-0.5">
                            Jejak audit inventaris Teknik &amp; Media Baru RRI Bukittinggi
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="tmb-status-chip" style="background-color: #ecfdf5; color: #047857; border: 1px solid #6ee7b7;">
                            <span style="background-color: #10b981;" class="w-2 h-2 rounded-full"></span>
                            <span>Arsip Audit Aman</span>
                        </span>
                        <span class="tmb-status-chip" style="background-color: rgba(255, 255, 255, 0.14); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25);">
                            <span style="background-color: #60a5fa;" class="w-2 h-2 rounded-full"></span>
                            <span>Maks. 50 Data / Halaman</span>
                        </span>
                    </div>
                </div>

                <div class="p-4 sm:p-6">

                    <!-- 4 Kartu Statistik Berwarna -->
                    <div class="tmb-stat-grid">
                        <div class="tmb-stat-card {{ $selectedAction === '' ? 'is-selected' : '' }}" data-tone="blue" data-quick-action="">
                            <div>
                                <div class="tmb-stat-label" style="color: #1e40af;">Semua Log Tampil</div>
                                <div class="tmb-stat-value" style="color: #1d4ed8;">{{ $statAll }}</div>
                            </div>
                            <div class="tmb-stat-icon" style="background-color: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd;">
                                <span class="font-mono text-sm font-black">#</span>
                            </div>
                        </div>

                        <div class="tmb-stat-card {{ str_contains(strtolower($selectedAction), 'tambah') || str_contains(strtolower($selectedAction), 'create') ? 'is-selected' : '' }}" data-tone="green" data-quick-action="create">
                            <div>
                                <div class="tmb-stat-label" style="color: #065f46;">Tambah Barang</div>
                                <div class="tmb-stat-value" style="color: #047857;">{{ $statCreate }}</div>
                            </div>
                            <div class="tmb-stat-icon" style="background-color: #d1fae5; color: #047857; border: 1px solid #6ee7b7;">
                                <span style="background-color: #10b981;" class="w-2.5 h-2.5 rounded-full"></span>
                            </div>
                        </div>

                        <div class="tmb-stat-card {{ str_contains(strtolower($selectedAction), 'edit') || str_contains(strtolower($selectedAction), 'update') ? 'is-selected' : '' }}" data-tone="indigo" data-quick-action="update">
                            <div>
                                <div class="tmb-stat-label" style="color: #3730a3;">Edit Barang</div>
                                <div class="tmb-stat-value" style="color: #4338ca;">{{ $statUpdate }}</div>
                            </div>
                            <div class="tmb-stat-icon" style="background-color: #e0e7ff; color: #4338ca; border: 1px solid #a5b4fc;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="tmb-stat-card {{ str_contains(strtolower($selectedAction), 'hapus') || str_contains(strtolower($selectedAction), 'delete') ? 'is-selected' : '' }}" data-tone="red" data-quick-action="delete">
                            <div>
                                <div class="tmb-stat-label" style="color: #991b1b;">Hapus Barang</div>
                                <div class="tmb-stat-value" style="color: #be123c;">{{ $statDelete }}</div>
                            </div>
                            <div class="tmb-stat-icon" style="background-color: #fee2e2; color: #be123c; border: 1px solid #fca5a5;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Bar -->
                    <form id="tmbAuditFilterForm" method="GET" action="{{ url()->current() }}" class="tmb-filter-panel">
                        <div class="tmb-filter-grid">
                            <div>
                                <label for="audit_search" class="tmb-filter-label">Cari Barang / Kode / Pengguna</label>
                                <input id="audit_search"
                                       type="text"
                                       name="search"
                                       value="{{ $selectedSearch }}"
                                       class="tmb-filter-control"
                                       placeholder="Nama barang, kode, atau pengguna...">
                            </div>

                            <div>
                                <label for="audit_action" class="tmb-filter-label">Jenis Aksi</label>
                                <select id="audit_action" name="action" class="tmb-filter-control">
                                    <option value="" {{ $selectedAction === '' ? 'selected' : '' }}>Semua Aksi</option>
                                    <option value="create" {{ in_array(strtolower($selectedAction), ['create', 'tambah', 'tambah barang']) ? 'selected' : '' }}>Tambah Barang</option>
                                    <option value="update" {{ in_array(strtolower($selectedAction), ['update', 'edit', 'edit barang']) ? 'selected' : '' }}>Edit Barang</option>
                                    <option value="delete" {{ in_array(strtolower($selectedAction), ['delete', 'hapus', 'hapus barang']) ? 'selected' : '' }}>Hapus Barang</option>
                                </select>
                            </div>

                            <div>
                                <label for="audit_from_date" class="tmb-filter-label">Dari Tanggal</label>
                                <input id="audit_from_date"
                                       type="date"
                                       name="from_date"
                                       value="{{ $selectedFrom }}"
                                       class="tmb-filter-control">
                            </div>

                            <div>
                                <label for="audit_to_date" class="tmb-filter-label">Sampai Tanggal</label>
                                <input id="audit_to_date"
                                       type="date"
                                       name="to_date"
                                       value="{{ $selectedTo }}"
                                       class="tmb-filter-control">
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="submit" class="tmb-btn-filter flex-1 md:flex-none">
                                    <span>Filter</span>
                                </button>
                                <a href="{{ url()->current() }}" id="tmbAuditResetLink" class="tmb-btn-clear" title="Reset Filter">
                                    <span>Reset</span>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Judul Daftar & Counter -->
                    <div class="tmb-list-head">
                        <div class="flex items-center gap-2">
                            <span style="background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;" class="p-1.5 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </span>
                            <h4 class="text-sm sm:text-base font-extrabold text-slate-900">
                                Daftar Log Tercatat
                            </h4>
                        </div>

                        <span id="tmbVisibleLogCount" class="text-xs font-bold text-slate-500">
                            Menampilkan {{ $totalLogsCount }} aktivitas
                        </span>
                    </div>

                    <!-- TAMPILAN CARD KHUSUS HP (< 768px) -->
                    <div class="tmb-audit-mobile">
                        @forelse($logCollection as $index => $log)
                            @php
                                $rowNo = method_exists($logCollection, 'firstItem') ? $logCollection->firstItem() + $index : $index + 1;
                                $rawTime = $log->created_at ?? null;
                                $timeCard = $rawTime
                                    ? (\Carbon\Carbon::parse($rawTime)->timezone('Asia/Jakarta')->format('d M Y • H:i') . ' WIB')
                                    : ($log->time_label ?? '-');
                                $timeModal = $rawTime
                                    ? (\Carbon\Carbon::parse($rawTime)->timezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB')
                                    : ($log->time_label ?? '-');

                                $userName = $log->user->name ?? ($log->user_name ?? 'Super Administrator');
                                $userHandle = $log->user->username ?? ($log->user_username ?? ($log->username ?? strtolower(str_replace(' ', '-', $userName))));
                                $rawAction = strtolower(trim($log->action ?? ($log->type ?? ($log->activity ?? 'aktivitas'))));

                                if (str_contains($rawAction, 'tambah') || str_contains($rawAction, 'buat') || str_contains($rawAction, 'create') || str_contains($rawAction, 'store')) {
                                    $actionBucket = 'create';
                                    $actionBadgeText = 'Tambah Barang';
                                    $badgeStyle = 'background-color: #ecfdf5; color: #047857; border: 1px solid #6ee7b7;';
                                    $dotStyle = 'background-color: #10b981;';
                                    $accentBorder = 'border-left: 4px solid #10b981;';
                                } elseif (str_contains($rawAction, 'edit') || str_contains($rawAction, 'update') || str_contains($rawAction, 'ubah') || str_contains($rawAction, 'perbarui')) {
                                    $actionBucket = 'update';
                                    $actionBadgeText = 'Edit Barang';
                                    $badgeStyle = 'background-color: #eef2ff; color: #4338ca; border: 1px solid #a5b4fc;';
                                    $dotStyle = 'background-color: #4f46e5;';
                                    $accentBorder = 'border-left: 4px solid #4f46e5;';
                                } elseif (str_contains($rawAction, 'hapus') || str_contains($rawAction, 'delete') || str_contains($rawAction, 'destroy')) {
                                    $actionBucket = 'delete';
                                    $actionBadgeText = 'Hapus Barang';
                                    $badgeStyle = 'background-color: #fef2f2; color: #be123c; border: 1px solid #fda4af;';
                                    $dotStyle = 'background-color: #ef4444;';
                                    $accentBorder = 'border-left: 4px solid #ef4444;';
                                } else {
                                    $actionBucket = 'other';
                                    $actionBadgeText = $log->action ?? 'Aktivitas';
                                    $badgeStyle = 'background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;';
                                    $dotStyle = 'background-color: #64748b;';
                                    $accentBorder = 'border-left: 4px solid #64748b;';
                                }

                                $payload = $buildLogPayload($log, $actionBucket);
                            @endphp

                            <div class="tmb-audit-card tmb-audit-item tmb-log-modal-trigger"
                                 style="{{ $accentBorder }}"
                                 data-bucket="{{ $actionBucket }}"
                                 data-search-text="{{ e(strtolower($rowNo . ' ' . $timeCard . ' ' . $userName . ' ' . $userHandle . ' ' . $actionBadgeText . ' ' . $payload['raw'])) }}"
                                 data-no="#{{ $rowNo }}"
                                 data-time="{{ e($timeModal) }}"
                                 data-user="{{ e($userName) }}"
                                 data-handle="{{ e($userHandle) }}"
                                 data-action="{{ e($actionBadgeText) }}"
                                 data-summary="{{ e($payload['summary']) }}"
                                 data-full-text="{{ e($payload['decoded_raw']) }}"
                                 data-name="{{ e($payload['name']) }}"
                                 data-code="{{ e($payload['code']) }}"
                                 data-fields="{{ e(json_encode($payload['fields'], JSON_UNESCAPED_UNICODE)) }}"
                                 data-changes="{{ e(json_encode($payload['changes'], JSON_UNESCAPED_UNICODE)) }}">

                                <div class="tmb-audit-card-top">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span style="background-color: #ffffff; color: #0f172a; border: 1px solid #cbd5e1;" class="px-2 py-0.5 rounded-md text-xs font-black flex-shrink-0">
                                            #{{ $rowNo }}
                                        </span>
                                        <span style="{{ $badgeStyle }}" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold whitespace-nowrap">
                                            <span style="{{ $dotStyle }}" class="w-1.5 h-1.5 rounded-full"></span>
                                            <span>{{ $actionBadgeText }}</span>
                                        </span>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500 whitespace-nowrap">
                                        {{ $timeCard }}
                                    </span>
                                </div>

                                <div class="tmb-audit-card-desc">
                                    {{ $payload['summary'] }}
                                </div>

                                <div class="tmb-audit-card-foot">
                                    <span class="truncate">Oleh: <strong class="text-slate-800">{{ $userName }}</strong></span>
                                    <span class="tmb-rincian-link">
                                        <span>Rincian</span>
                                        <span>&rsaquo;</span>
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl px-5 py-8 text-center">
                                <p class="text-sm font-extrabold text-slate-700">Belum ada log aktivitas tercatat.</p>
                                <p class="text-xs font-medium text-slate-500 mt-1">
                                    Setiap penambahan, perubahan, atau penghapusan data akan muncul di sini.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- TAMPILAN TABEL DESKTOP (>= 768px) -->
                    <div class="tmb-audit-table-shell">
                        <table class="tmb-audit-table">
                            <thead>
                                <tr>
                                    <th class="w-14 text-center">No</th>
                                    <th class="w-48">Waktu (WIB)</th>
                                    <th class="w-44">Pengguna</th>
                                    <th class="w-36 text-center">Aksi</th>
                                    <th>Detail Keterangan</th>
                                    <th class="w-24 text-right">Rincian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logCollection as $index => $log)
                                    @php
                                        $rowNo = method_exists($logCollection, 'firstItem') ? $logCollection->firstItem() + $index : $index + 1;
                                        $rawTime = $log->created_at ?? null;
                                        $timeRow = $rawTime
                                            ? (\Carbon\Carbon::parse($rawTime)->timezone('Asia/Jakarta')->format('d M Y - H:i') . ' WIB')
                                            : ($log->time_label ?? '-');
                                        $timeModal = $rawTime
                                            ? (\Carbon\Carbon::parse($rawTime)->timezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB')
                                            : ($log->time_label ?? '-');

                                        $userName = $log->user->name ?? ($log->user_name ?? 'Super Administrator');
                                        $userHandle = $log->user->username ?? ($log->user_username ?? ($log->username ?? strtolower(str_replace(' ', '-', $userName))));
                                        $rawAction = strtolower(trim($log->action ?? ($log->type ?? ($log->activity ?? 'aktivitas'))));

                                        if (str_contains($rawAction, 'tambah') || str_contains($rawAction, 'buat') || str_contains($rawAction, 'create') || str_contains($rawAction, 'store')) {
                                            $actionBucket = 'create';
                                            $actionBadgeText = 'Tambah Barang';
                                            $badgeStyle = 'background-color: #ecfdf5; color: #047857; border: 1px solid #6ee7b7;';
                                            $dotStyle = 'background-color: #10b981;';
                                            $barColor = '#10b981';
                                        } elseif (str_contains($rawAction, 'edit') || str_contains($rawAction, 'update') || str_contains($rawAction, 'ubah') || str_contains($rawAction, 'perbarui')) {
                                            $actionBucket = 'update';
                                            $actionBadgeText = 'Edit Barang';
                                            $badgeStyle = 'background-color: #eef2ff; color: #4338ca; border: 1px solid #a5b4fc;';
                                            $dotStyle = 'background-color: #4f46e5;';
                                            $barColor = '#4f46e5';
                                        } elseif (str_contains($rawAction, 'hapus') || str_contains($rawAction, 'delete') || str_contains($rawAction, 'destroy')) {
                                            $actionBucket = 'delete';
                                            $actionBadgeText = 'Hapus Barang';
                                            $badgeStyle = 'background-color: #fef2f2; color: #be123c; border: 1px solid #fda4af;';
                                            $dotStyle = 'background-color: #ef4444;';
                                            $barColor = '#ef4444';
                                        } else {
                                            $actionBucket = 'other';
                                            $actionBadgeText = $log->action ?? 'Aktivitas';
                                            $badgeStyle = 'background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;';
                                            $dotStyle = 'background-color: #64748b;';
                                            $barColor = '#64748b';
                                        }

                                        $payload = $buildLogPayload($log, $actionBucket);
                                    @endphp

                                    <tr class="tmb-audit-row tmb-audit-item tmb-log-modal-trigger"
                                        data-bucket="{{ $actionBucket }}"
                                        data-search-text="{{ e(strtolower($rowNo . ' ' . $timeRow . ' ' . $userName . ' ' . $userHandle . ' ' . $actionBadgeText . ' ' . $payload['raw'])) }}"
                                        data-no="#{{ $rowNo }}"
                                        data-time="{{ e($timeModal) }}"
                                        data-user="{{ e($userName) }}"
                                        data-handle="{{ e($userHandle) }}"
                                        data-action="{{ e($actionBadgeText) }}"
                                        data-summary="{{ e($payload['summary']) }}"
                                        data-full-text="{{ e($payload['decoded_raw']) }}"
                                        data-name="{{ e($payload['name']) }}"
                                        data-code="{{ e($payload['code']) }}"
                                        data-fields="{{ e(json_encode($payload['fields'], JSON_UNESCAPED_UNICODE)) }}"
                                        data-changes="{{ e(json_encode($payload['changes'], JSON_UNESCAPED_UNICODE)) }}">

                                        <td class="text-center font-extrabold text-slate-900" style="border-left: 3.5px solid {{ $barColor }};">
                                            {{ $rowNo }}
                                        </td>

                                        <td class="font-semibold text-slate-700 whitespace-nowrap">
                                            {{ $timeRow }}
                                        </td>

                                        <td class="font-bold text-slate-900 whitespace-nowrap">
                                            {{ $userName }}
                                        </td>

                                        <td class="text-center whitespace-nowrap">
                                            <span style="{{ $badgeStyle }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold">
                                                <span style="{{ $dotStyle }}" class="w-1.5 h-1.5 rounded-full"></span>
                                                <span>{{ $actionBadgeText }}</span>
                                            </span>
                                        </td>

                                        <td class="font-semibold text-slate-800 leading-snug">
                                            {{ $payload['summary'] }}
                                        </td>

                                        <td class="text-right whitespace-nowrap">
                                            <span class="tmb-rincian-link">
                                                <span>Rincian</span>
                                                <span>&rsaquo;</span>
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center">
                                            <p class="text-sm font-extrabold text-slate-700">Belum ada log aktivitas tercatat.</p>
                                            <p class="text-xs font-medium text-slate-500 mt-1">
                                                Setiap penambahan, perubahan, atau penghapusan data akan muncul di sini.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($logCollection, 'hasPages') && $logCollection->hasPages())
                        <div class="mt-4">
                            {{ $logCollection->withQueryString()->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Popup Audit Forensik -->
    <div id="tmbAuditDetailModal" class="tmb-detail-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="tmbAuditModalTitle">
        <div class="tmb-detail-modal-card" onclick="event.stopPropagation();">
            <div class="tmb-detail-modal-head">
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                    <span id="tmbAuditModalNo" class="tmb-detail-modal-badge">#</span>
                    <div class="min-w-0">
                        <h3 id="tmbAuditModalTitle" class="text-sm sm:text-lg font-extrabold tracking-tight text-white leading-tight truncate">
                            Detail Perubahan Aktivitas
                        </h3>
                        <p id="tmbAuditModalTime" class="text-[11px] sm:text-xs font-normal text-blue-100 mt-0.5 truncate">
                            -
                        </p>
                    </div>
                </div>
                <button type="button" id="tmbCloseAuditModalBtn" style="background-color: rgba(255, 255, 255, 0.16); border: 1px solid rgba(255, 255, 255, 0.28); color: #ffffff;" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl inline-flex items-center justify-center cursor-pointer flex-shrink-0" aria-label="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="tmb-detail-modal-body">
                <!-- Identitas Pelaku & Jenis Aksi -->
                <div class="tmb-detail-grid-2">
                    <div class="tmb-detail-tile">
                        <div class="tmb-detail-tile-label">Diubah Oleh</div>
                        <div id="tmbAuditModalUser" class="tmb-detail-tile-val">Super Administrator</div>
                        <div id="tmbAuditModalHandle" class="tmb-detail-tile-sub">super-administrator</div>
                    </div>
                    <div class="tmb-detail-tile">
                        <div class="tmb-detail-tile-label">Jenis Aksi</div>
                        <div id="tmbAuditModalAction" class="tmb-detail-tile-val text-blue-700">-</div>
                    </div>
                </div>

                <!-- Ringkasan Aktivitas -->
                <div class="tmb-detail-tile">
                    <div class="tmb-detail-tile-label">Ringkasan Aktivitas</div>
                    <div id="tmbAuditModalSummary" class="tmb-detail-tile-val">-</div>
                </div>

                <!-- Panel Audit Perubahan Data (Edit Barang) -->
                <div id="tmbAuditChangesSection" class="tmb-modal-section-box is-update-modern" style="display: none;">
                    <div class="tmb-modal-section-head">
                        <div class="tmb-modal-section-title">
                            <span class="tmb-section-icon-badge">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </span>
                            <span>Rincian Perubahan Data (Sebelum &rarr; Sesudah)</span>
                        </div>
                        <span class="tmb-audit-pill is-update">Audit Perubahan</span>
                    </div>
                    <div id="tmbAuditChangesList"></div>
                </div>

                <!-- Panel Tambah Barang: Ringkas & Fokus Pelacakan Kode Barang -->
                <div id="tmbAuditCreateSection" class="tmb-modal-section-box is-create-modern" style="display: none;">
                    <div class="tmb-modal-section-head">
                        <div class="tmb-modal-section-title">
                            <span class="tmb-section-icon-badge">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </span>
                            <span>Identitas Barang Ditambah</span>
                        </div>
                        <span class="tmb-audit-pill is-create">
                            <span style="background-color: #10b981;" class="w-1.5 h-1.5 rounded-full"></span>
                            <span>Masuk Inventaris</span>
                        </span>
                    </div>

                    <div class="tmb-identity-card">
                        <div style="font-size: 0.625rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Nama Barang</div>
                        <div id="tmbCreateName" class="tmb-identity-name">-</div>

                        <div id="tmbCreateCodeRow" class="tmb-identity-code-row" style="display: none;">
                            <span style="font-size: 0.625rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Kode Barang (Pelacakan)</span>
                            <span id="tmbCreateCodeBadge" class="tmb-code-chip">-</span>
                        </div>
                    </div>
                </div>

                <!-- Panel Hapus Barang: Detail Lengkap Barang Dihapus -->
                <div id="tmbAuditDeleteSection" class="tmb-modal-section-box is-delete-modern" style="display: none;">
                    <div class="tmb-modal-section-head">
                        <div class="tmb-modal-section-title">
                            <span class="tmb-section-icon-badge">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </span>
                            <span>Identitas &amp; Rincian Lengkap Barang Dihapus</span>
                        </div>
                        <span class="tmb-audit-pill is-delete">
                            <span style="background-color: #ef4444;" class="w-1.5 h-1.5 rounded-full"></span>
                            <span>Keluar / Dihapus</span>
                        </span>
                    </div>

                    <!-- Identitas Utama Barang Dihapus -->
                    <div class="tmb-identity-card">
                        <div style="font-size: 0.625rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Nama Barang</div>
                        <div id="tmbDeleteName" class="tmb-identity-name">-</div>

                        <div id="tmbDeleteCodeRow" class="tmb-identity-code-row" style="display: none;">
                            <span style="font-size: 0.625rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Kode Barang</span>
                            <span id="tmbDeleteCodeBadge" class="tmb-code-chip">-</span>
                        </div>
                    </div>

                    <!-- Rincian Atribut Barang Dihapus -->
                    <div class="tmb-delete-subhead">Spesifikasi &amp; Informasi Barang</div>
                    <div id="tmbDeleteFieldsGrid" class="tmb-item-tiles-grid"></div>

                    <!-- Jejak Penghapusan -->
                    <div class="tmb-delete-audit-strip">
                        <div class="tmb-delete-audit-full">
                            <div class="tmb-detail-tile-label" style="margin-bottom: 2px;">Dihapus Oleh</div>
                            <div id="tmbDeleteBy" style="font-size: 0.8rem; font-weight: 800; color: #0f172a;">-</div>
                        </div>
                        <div class="tmb-delete-audit-full">
                            <div class="tmb-detail-tile-label" style="margin-bottom: 2px;">Waktu Penghapusan</div>
                            <div id="tmbDeleteTime" style="font-size: 0.8rem; font-weight: 700; color: #334155;">-</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tmb-detail-modal-foot">
                <button type="button" id="tmbAuditModalFooterClose" class="tmb-detail-close-btn">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById('tmbAuditDetailModal');
            const closeBtn = document.getElementById('tmbCloseAuditModalBtn');
            const footerCloseBtn = document.getElementById('tmbAuditModalFooterClose');

            const DELETE_ATTR_KEYS = ['category', 'quantity', 'condition', 'location', 'note'];
            const DETAIL_LABELS = {
                name: 'Nama Barang',
                code: 'Kode Barang',
                category: 'Kategori',
                quantity: 'Jumlah',
                condition: 'Kondisi',
                location: 'Lokasi',
                note: 'Keterangan',
                extra: 'Keterangan Tambahan'
            };

            function decodeEntities(str) {
                if (typeof str !== 'string') return '';
                const txt = document.createElement('textarea');
                txt.innerHTML = str;
                return txt.value;
            }

            function safeJsonParse(str, fallback) {
                try {
                    const parsed = JSON.parse(str || '');
                    return parsed ?? fallback;
                } catch (err) {
                    return fallback;
                }
            }

            function extractChangesFromText(fullText, summaryText) {
                const combined = decodeEntities(`${fullText || ''} ${summaryText || ''}`)
                    .replace(/\\vert\{\}/gi, '\n')
                    .replace(/\[DETAIL\]|\[PERUBAHAN\]/gi, '\n')
                    .replace(/\s*\|\s*/g, '\n')
                    .trim();

                const afterColon = combined.replace(/^(?:Memperbarui\s*data\s*barang|Edit\s*barang|Update\s*barang)\s*:\s*[^(\n]+\s*/i, '').trim();
                const segments = afterColon
                    .split(/\n|\s+(?=(?:Kode\s*Barang|Kode|Code|Nama\s*Barang|Nama|Kategori|Jumlah|Jml|Stok|Qty|Kondisi|Lokasi|Ruangan|Tempat|Keterangan|Catatan|Deskripsi)\s*:)/i)
                    .map(s => s.trim())
                    .filter(Boolean);

                const result = [];
                segments.forEach(seg => {
                    const kvDiff = seg.match(/^([^:]+):\s*(.+?)\s*(?:->|→)\s*(.+)$/);
                    if (kvDiff) {
                        result.push({
                            field: kvDiff[1].trim(),
                            before: kvDiff[2].trim(),
                            after: kvDiff[3].trim()
                        });
                        return;
                    }

                    const bareDiff = seg.match(/^(.+?)\s*(?:->|→)\s*(.+)$/);
                    if (bareDiff) {
                        const bVal = bareDiff[1].trim();
                        const aVal = bareDiff[2].trim();
                        const isCodeLike = /^[A-Z]{2,}-[A-Z0-9-]+$/i.test(bVal) || /^[A-Z]{2,}-[A-Z0-9-]+$/i.test(aVal);
                        result.push({
                            field: isCodeLike ? 'Kode Barang' : 'Perubahan Data',
                            before: bVal,
                            after: aVal
                        });
                    }
                });

                return result;
            }

            function parseTextFieldsFallback(fullText, summaryText) {
                const combined = decodeEntities(`${fullText || ''} ${summaryText || ''}`)
                    .replace(/\\vert\{\}/gi, '\n')
                    .replace(/\[DETAIL\]|\[PERUBAHAN\]/gi, '\n')
                    .replace(/\s*\|\s*/g, '\n')
                    .trim();

                const segments = combined
                    .split(/\n|\s+(?=(?:Kode\s*Barang|Kode|Code|Nama\s*Barang|Nama|Kategori|Jumlah|Jml|Stok|Qty|Kondisi|Lokasi|Ruangan|Tempat|Keterangan|Catatan|Deskripsi)\s*:)/i)
                    .map(s => s.trim())
                    .filter(Boolean);

                const parsed = [];
                segments.forEach(seg => {
                    const m = seg.match(/^([^:]+):\s*(.+)$/);
                    if (!m) return;
                    const labelLower = m[1].trim().toLowerCase();
                    const val = m[2].trim();
                    if (!val) return;

                    if (labelLower.includes('kode') || labelLower === 'code') {
                        parsed.push({ key: 'code', label: DETAIL_LABELS.code, value: val });
                    } else if (labelLower.includes('nama barang') || labelLower === 'nama') {
                        parsed.push({ key: 'name', label: DETAIL_LABELS.name, value: val });
                    } else if (labelLower.includes('kategori')) {
                        parsed.push({ key: 'category', label: DETAIL_LABELS.category, value: val });
                    } else if (labelLower.includes('jumlah') || ['jml', 'stok', 'qty'].includes(labelLower)) {
                        parsed.push({ key: 'quantity', label: DETAIL_LABELS.quantity, value: val });
                    } else if (labelLower.includes('kondisi')) {
                        parsed.push({ key: 'condition', label: DETAIL_LABELS.condition, value: val });
                    } else if (labelLower.includes('lokasi') || ['ruangan', 'tempat'].includes(labelLower)) {
                        parsed.push({ key: 'location', label: DETAIL_LABELS.location, value: val });
                    } else if (labelLower.includes('keterangan') || ['catatan', 'deskripsi'].includes(labelLower)) {
                        parsed.push({ key: 'note', label: DETAIL_LABELS.note, value: val });
                    }
                });

                return parsed;
            }

            function parseItemIdentityAndAttrs(rawFields, nameVal, codeVal, summaryVal, fullTextVal) {
                const map = new Map();
                const extras = [];

                const sourceFields = (Array.isArray(rawFields) && rawFields.length > 0)
                    ? rawFields
                    : parseTextFieldsFallback(fullTextVal, summaryVal);

                sourceFields.forEach(item => {
                    if (!item || typeof item !== 'object') return;
                    const key = item.key || 'extra';
                    const val = decodeEntities(String(item.value ?? '')).trim();
                    if (val === '') return;

                    if (key === 'extra') {
                        extras.push({
                            key: 'extra',
                            label: decodeEntities(item.label || DETAIL_LABELS.extra),
                            value: val,
                            isMissing: false
                        });
                    } else if (!map.has(key)) {
                        map.set(key, {
                            key,
                            label: decodeEntities(item.label || DETAIL_LABELS[key] || 'Info'),
                            value: val,
                            isMissing: false
                        });
                    }
                });

                const cleanName = decodeEntities(nameVal).trim();
                const cleanCode = decodeEntities(codeVal).trim();
                const cleanSummary = decodeEntities(summaryVal).trim();
                const fallbackName = cleanName !== '' ? cleanName : cleanSummary.replace(/^[^:]+:\s*/i, '').trim();

                const resolvedName = map.has('name')
                    ? map.get('name').value
                    : (fallbackName !== '' && fallbackName !== '-' ? fallbackName : summaryVal);

                const resolvedCode = map.has('code')
                    ? map.get('code').value
                    : cleanCode;

                const deleteAttrs = DELETE_ATTR_KEYS.map(key => {
                    if (map.has(key)) {
                        return map.get(key);
                    }
                    return {
                        key,
                        label: DETAIL_LABELS[key],
                        value: 'Tidak tercatat di log',
                        isMissing: true
                    };
                });

                extras.forEach(ex => deleteAttrs.push(ex));

                return {
                    name: resolvedName,
                    code: resolvedCode,
                    deleteAttrs
                };
            }

            function openAuditModal(trigger) {
                if (!modal || !trigger) return;

                const bucket = (trigger.dataset.bucket || '').toLowerCase();
                const timeStr = decodeEntities(trigger.dataset.time || '-');
                const userStr = decodeEntities(trigger.dataset.user || 'Super Administrator');
                const handleStr = decodeEntities(trigger.dataset.handle || '-');
                const actionStr = decodeEntities(trigger.dataset.action || '-');
                const summaryVal = decodeEntities(trigger.dataset.summary || '-').trim();
                const fullTextVal = decodeEntities(trigger.dataset.fullText || '').trim();
                const nameVal = decodeEntities(trigger.dataset.name || '').trim();
                const codeVal = decodeEntities(trigger.dataset.code || '').trim();

                document.getElementById('tmbAuditModalNo').textContent = trigger.dataset.no || '#';
                document.getElementById('tmbAuditModalTime').textContent = timeStr;
                document.getElementById('tmbAuditModalUser').textContent = userStr;
                document.getElementById('tmbAuditModalHandle').textContent = handleStr;
                document.getElementById('tmbAuditModalAction').textContent = actionStr;
                document.getElementById('tmbAuditModalSummary').textContent = summaryVal;

                let changes = safeJsonParse(trigger.dataset.changes, []);
                const rawFields = safeJsonParse(trigger.dataset.fields, []);

                if (bucket === 'update' && (!Array.isArray(changes) || changes.length === 0)) {
                    changes = extractChangesFromText(fullTextVal, summaryVal);
                }

                // 1. Edit Barang
                const changesSection = document.getElementById('tmbAuditChangesSection');
                const changesList = document.getElementById('tmbAuditChangesList');

                if (changesSection && changesList) {
                    changesList.innerHTML = '';
                    const validChanges = Array.isArray(changes)
                        ? changes.filter(c => c && (String(c.before ?? '').trim() !== '' || String(c.after ?? '').trim() !== ''))
                        : [];

                    if (bucket === 'update') {
                        if (validChanges.length > 0) {
                            validChanges.forEach(ch => {
                                const row = document.createElement('div');
                                row.className = 'tmb-diff-row';

                                const beforeStr = decodeEntities(String(ch.before ?? '-'));
                                const afterStr = decodeEntities(String(ch.after ?? '-'));
                                const fieldStr = decodeEntities(String(ch.field || 'Perubahan Data'));

                                const lbl = document.createElement('div');
                                lbl.className = 'tmb-detail-tile-label';
                                lbl.style.marginBottom = '4px';
                                lbl.textContent = fieldStr;

                                const badgeWrap = document.createElement('div');
                                badgeWrap.className = 'tmb-diff-badges';

                                const beforeBadge = document.createElement('span');
                                beforeBadge.className = 'tmb-diff-before';
                                beforeBadge.textContent = beforeStr;

                                const arrow = document.createElement('span');
                                arrow.style.color = '#475569';
                                arrow.style.fontWeight = '800';
                                arrow.style.fontSize = '0.95rem';
                                arrow.innerHTML = '&rarr;';

                                const afterBadge = document.createElement('span');
                                afterBadge.className = 'tmb-diff-after';
                                afterBadge.textContent = afterStr;

                                badgeWrap.appendChild(beforeBadge);
                                badgeWrap.appendChild(arrow);
                                badgeWrap.appendChild(afterBadge);

                                row.appendChild(lbl);
                                row.appendChild(badgeWrap);
                                changesList.appendChild(row);
                            });
                        } else {
                            const fallbackCard = document.createElement('div');
                            fallbackCard.className = 'tmb-diff-row';
                            fallbackCard.innerHTML = `<div class="tmb-detail-tile-label" style="margin-bottom:4px;">Catatan Audit Perubahan</div><div class="tmb-detail-tile-val">${summaryVal}</div>`;
                            changesList.appendChild(fallbackCard);
                        }
                        changesSection.style.display = 'block';
                    } else {
                        changesSection.style.display = 'none';
                    }
                }

                const parsed = parseItemIdentityAndAttrs(rawFields, nameVal, codeVal, summaryVal, fullTextVal);

                // 2. Tambah Barang
                const createSection = document.getElementById('tmbAuditCreateSection');
                const createName = document.getElementById('tmbCreateName');
                const createCodeRow = document.getElementById('tmbCreateCodeRow');
                const createCodeBadge = document.getElementById('tmbCreateCodeBadge');

                if (createSection) {
                    if (bucket === 'create') {
                        if (createName) createName.textContent = parsed.name;
                        if (parsed.code && createCodeRow && createCodeBadge) {
                            createCodeBadge.innerHTML = `<span>#</span><span>${parsed.code}</span>`;
                            createCodeRow.style.display = 'flex';
                        } else if (createCodeRow) {
                            createCodeRow.style.display = 'none';
                        }
                        createSection.style.display = 'block';
                    } else {
                        createSection.style.display = 'none';
                    }
                }

                // 3. Hapus Barang
                const deleteSection = document.getElementById('tmbAuditDeleteSection');
                const deleteName = document.getElementById('tmbDeleteName');
                const deleteCodeRow = document.getElementById('tmbDeleteCodeRow');
                const deleteCodeBadge = document.getElementById('tmbDeleteCodeBadge');
                const deleteFieldsGrid = document.getElementById('tmbDeleteFieldsGrid');
                const deleteBy = document.getElementById('tmbDeleteBy');
                const deleteTime = document.getElementById('tmbDeleteTime');

                if (deleteSection && deleteFieldsGrid) {
                    deleteFieldsGrid.innerHTML = '';

                    if (bucket === 'delete') {
                        if (deleteName) deleteName.textContent = parsed.name;
                        if (parsed.code && deleteCodeRow && deleteCodeBadge) {
                            deleteCodeBadge.innerHTML = `<span>#</span><span>${parsed.code}</span>`;
                            deleteCodeRow.style.display = 'flex';
                        } else if (deleteCodeRow) {
                            deleteCodeRow.style.display = 'none';
                        }

                        parsed.deleteAttrs.forEach(attr => {
                            const tile = document.createElement('div');
                            tile.className = 'tmb-item-mini-tile';
                            if (attr.key === 'note' || attr.key === 'extra') tile.classList.add('is-full');
                            if (attr.isMissing) tile.classList.add('is-missing');

                            const lbl = document.createElement('div');
                            lbl.className = 'tmb-detail-tile-label';
                            lbl.textContent = attr.label;

                            const val = document.createElement('div');
                            val.className = attr.isMissing ? 'tmb-detail-tile-muted' : 'tmb-detail-tile-val';
                            val.textContent = attr.value;

                            tile.appendChild(lbl);
                            tile.appendChild(val);
                            deleteFieldsGrid.appendChild(tile);
                        });

                        if (deleteBy) deleteBy.textContent = userStr;
                        if (deleteTime) deleteTime.textContent = timeStr;

                        deleteSection.style.display = 'block';
                    } else {
                        deleteSection.style.display = 'none';
                    }
                }

                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }

            function closeAuditModal() {
                if (!modal) return;
                modal.classList.remove('is-open');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('.tmb-log-modal-trigger').forEach(el => {
                el.addEventListener('click', function () {
                    openAuditModal(this);
                });
            });

            if (closeBtn) closeBtn.addEventListener('click', closeAuditModal);
            if (footerCloseBtn) footerCloseBtn.addEventListener('click', closeAuditModal);
            if (modal) modal.addEventListener('click', closeAuditModal);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) {
                    closeAuditModal();
                }
            });

            const searchInput = document.getElementById('audit_search');
            const actionSelect = document.getElementById('audit_action');
            const statCards = document.querySelectorAll('.tmb-stat-card');
            const auditItems = document.querySelectorAll('.tmb-audit-item');
            const countLabel = document.getElementById('tmbVisibleLogCount');

            function applyLiveFilter() {
                const q = (searchInput ? searchInput.value : '').trim().toLowerCase();
                const act = (actionSelect ? actionSelect.value : '').trim().toLowerCase();

                let visibleDesktop = 0;
                let visibleMobile = 0;

                auditItems.forEach(item => {
                    const bucket = (item.dataset.bucket || '').toLowerCase();
                    const hay = decodeEntities(item.dataset.searchText || '').toLowerCase();

                    const matchAction = !act || bucket === act || hay.includes(act);
                    const matchQuery = !q || hay.includes(q);
                    const show = matchAction && matchQuery;

                    item.style.display = show ? '' : 'none';
                    if (show) {
                        if (item.classList.contains('tmb-audit-row')) visibleDesktop++;
                        if (item.classList.contains('tmb-audit-card')) visibleMobile++;
                    }
                });

                const shown = Math.max(visibleDesktop, visibleMobile);
                if (countLabel) {
                    countLabel.textContent = `Menampilkan ${shown} aktivitas`;
                }

                statCards.forEach(card => {
                    const cardAct = card.dataset.quickAction || '';
                    card.classList.toggle('is-selected', cardAct === act);
                });
            }

            statCards.forEach(card => {
                card.addEventListener('click', function () {
                    if (!actionSelect) return;
                    actionSelect.value = this.dataset.quickAction || '';
                    applyLiveFilter();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', applyLiveFilter);
            }
            if (actionSelect) {
                actionSelect.addEventListener('change', applyLiveFilter);
            }

            @if(session('success'))
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: @json(session('success')),
                        confirmButtonText: 'Mantap, Mengerti',
                        confirmButtonColor: '#2563eb',
                        customClass: {
                            popup: 'tmb-swal-card',
                            title: 'tmb-swal-title',
                            htmlContainer: 'tmb-swal-text',
                            actions: 'tmb-swal-actions',
                            confirmButton: 'tmb-swal-btn'
                        }
                    });
                }
            @endif
        });
    </script>
</x-app-layout>