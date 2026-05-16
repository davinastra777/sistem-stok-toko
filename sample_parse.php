<?php
$text = '#Nama Produk\tSKU Variasi Qty\n1KERUPUK JENGKOL 250 GRAM\t2\n2KECIPIR UDANG 250 GRAM\t1\n3KERUPUK JANGEK KETUMBAR 250\nGRAM\n1\n4KARDUS UNTUK PACKING [TIDAK\nUNTUK DIJUAL SECARA TERPISAH]\n1\nPesan: (2605139TSEPWQK)';
$section = $text;
$startPos = stripos($text, '#Nama Produk');
if ($startPos === false) {
    $startPos = stripos($text, 'Nama Produk');
}
if ($startPos !== false) {
    $section = substr($text, $startPos);
}
$endPos = stripos($section, 'Pesan:');
if ($endPos !== false) {
    $section = substr($section, 0, $endPos);
}
$lines = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $section))));
$items = [];
for ($i = 0; $i < count($lines); $i++) {
    $line = $lines[$i];
    if (!preg_match('/^\d+/', $line)) {
        continue;
    }
    $line = preg_replace('/^\d+\s*/', '', $line);
    if (preg_match('/^(.*\D)\s+(\d+)$/', $line, $match)) {
        $namaProduk = trim($match[1]);
        $qty = (int) $match[2];
    } else {
        $namaProduk = $line;
        while (isset($lines[$i + 1]) && !preg_match('/^\d+/', $lines[$i + 1]) && !is_numeric($lines[$i + 1])) {
            $namaProduk .= ' ' . $lines[++$i];
        }
        if (isset($lines[$i + 1]) && is_numeric($lines[$i + 1])) {
            $qty = (int) $lines[++$i];
        } else {
            continue;
        }
    }
    $namaProduk = preg_replace('/\s+/', ' ', trim($namaProduk));
    $upperName = strtoupper($namaProduk);
    if ($qty < 1) {
        continue;
    }
    if (
        str_contains($upperName, 'BERAT') ||
        str_contains($upperName, 'BATAS KIRIM') ||
        str_contains($upperName, 'KOTA') ||
        str_contains($upperName, 'KARDUS') ||
        str_contains($upperName, 'PACKING') ||
        str_contains($upperName, 'SKU VARIASI') ||
        str_contains($upperName, 'PESAN:')
    ) {
        continue;
    }
    $items[] = [
        'produk' => $namaProduk,
        'qty' => $qty,
    ];
}
print_r($items);
