<?php

declare(strict_types=1);

return [
    'form' => [
        'title' => 'Judul',
        'url' => 'URL',
        'icon' => 'Ikon',
        'classes' => 'Kelas CSS',
        'rel' => 'Atribut Rel',
        'linkable_type' => 'Tipe',
        'linkable_id' => 'ID',
    ],
    'resource' => [
        'name' => [
            'label' => 'Nama',
        ],
        'locations' => [
            'label' => 'Lokasi',
            'empty' => 'Tidak Ditugaskan',
        ],
        'items' => [
            'label' => 'Item',
        ],
        'is_visible' => [
            'label' => 'Visibilitas',
            'visible' => 'Terlihat',
            'hidden' => 'Tersembunyi',
        ],
    ],
    'actions' => [
        'add' => [
            'label' => 'Tambah ke Menu',
        ],
        'edit' => 'Ubah',
        'delete' => 'Hapus',
        'indent' => 'Indentasi',
        'unindent' => 'Hapus Indentasi',
        'locations' => [
            'label' => 'Lokasi',
            'heading' => 'Kelola Lokasi',
            'description' => 'Pilih menu mana yang muncul di setiap lokasi.',
            'submit' => 'Perbarui',
            'form' => [
                'location' => [
                    'label' => 'Lokasi',
                ],
                'menu' => [
                    'label' => 'Menu yang Ditugaskan',
                ],
            ],
            'empty' => [
                'heading' => 'Tidak ada lokasi yang terdaftar',
            ],
        ],
    ],
    'items' => [
        'expand' => 'Perluas',
        'collapse' => 'Ciutkan',
        'empty' => [
            'heading' => 'Tidak ada item di menu ini.',
        ],
    ],
    'custom_link' => 'Tautan Kustom',
    'custom_text' => 'Teks Kustom',
    'open_in' => [
        'label' => 'Buka di',
        'options' => [
            'self' => 'Tab yang sama',
            'blank' => 'Tab baru',
            'parent' => 'Tab induk',
            'top' => 'Tab teratas',
        ],
    ],
    'notifications' => [
        'created' => [
            'title' => 'Tautan dibuat',
        ],
        'locations' => [
            'title' => 'Lokasi menu diperbarui',
        ],
    ],
    'panel' => [
        'empty' => [
            'heading' => 'Tidak ada item ditemukan',
            'description' => 'Tidak ada item di menu ini.',
        ],
        'pagination' => [
            'previous' => 'Sebelumnya',
            'next' => 'Berikutnya',
        ],
    ],
];
