<?php
$text = "#Nama Produk\tSKU Variasi Qty\n1KERUPUK JENGKOL 250 GRAM\t2\n2KECIPIR UDANG 250 GRAM\t1\n3KERUPUK JANGEK KETUMBAR 250\nGRAM\n1\n4KARDUS UNTUK PACKING [TIDAK\nUNTUK DIJUAL SECARA TERPISAH]\n1\nPesan: (2605139TSEPWQK)";
$section = $text;
$startPos = stripos($text, '#Nama Produk');
if ($startPos === false) { $startPos = stripos($text, 'Nama Produk'); }
if ($startPos !== false) { $section = substr($text, $startPos); }
$endPos = stripos($section, 'Pesan:');
if ($endPos !== false) { $section = substr($section, 0, $endPos); }
$lines = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $section))));
foreach ($lines as $idx => $line) {
    echo "LINE $idx: [$line] (hex=" . bin2hex($line) . ")\n";
}
foreach ($lines as $idx => $line) {
    if (!preg_match('/^\d+/', $line)) { echo "skip $idx no digit prefix\n"; continue; }
    $clean = preg_replace('/^\d+\s*/', '', $line);
    echo "clean $idx: [$clean] (hex=" . bin2hex($clean) . ")\n";
    if (preg_match('/^(.*\D)\s+(\d+)$/', $clean, $m)) {
        echo "match $idx: ['{$m[1]}', '{$m[2]}']\n";
    } else {
        echo "no qty match $idx\n";
    }
}
