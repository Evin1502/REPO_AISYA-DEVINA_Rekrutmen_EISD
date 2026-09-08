<?php

return [
    /**
     * Daftar wilayah layanan penjemputan (kelurahan/kecamatan).
     *
     * Sengaja berupa daftar TETAP (bukan input teks bebas) supaya nilainya
     * konsisten dan bisa diagregasi dengan andal di Admin\DashboardController
     * (breakdown volume sampah & ketepatan waktu per wilayah kota).
     *
     * Kalau area operasional bertambah, tinggal tambah entri di sini --
     * tidak perlu migration/ubah kode lain.
     */
    'service_areas' => [
        'Cihapit',
        'Cibeunying Kidul',
        'Citarum',
        'Merdeka',
        'Sumur Bandung',
        'Tamansari',
    ],
];
