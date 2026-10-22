<?php

namespace App\Support;

class AmenityIcon
{
    private const PATHS = [
        'Wi-Fi' => '<path d="M5 12.5a10 10 0 0 1 14 0"/><path d="M8.2 15.4a6 6 0 0 1 7.6 0"/><path d="M12 18.5h.01"/>',
        'Parking' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 16V8h3.6a2.4 2.4 0 0 1 0 4.8H9"/>',
        'Breakfast' => '<path d="M4 19h16"/><path d="M7 19c.6-4 2.2-7 5-7s4.4 3 5 7"/><path d="M12 5v3"/>',
        'Hot water' => '<path d="M7 6c1.6 1.6 1.6 2.6 0 4.2S5.4 13 7 14.6"/><path d="M12 6c1.6 1.6 1.6 2.6 0 4.2s-1.6 2.6 0 4.2"/><path d="M17 6c1.6 1.6 1.6 2.6 0 4.2s-1.6 2.6 0 4.2"/>',
        'Heater' => '<path d="M12 3v8"/><rect x="8" y="11" width="8" height="5" rx="1"/><path d="M9 20h6"/><path d="M10 16v4M14 16v4"/>',
        'Mountain view' => '<path d="M3 18 9 8l4 6 2-3 6 7H3z"/>',
        'Kitchenette' => '<path d="M4 10h16v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8z"/><path d="M8 10V7h8v3"/>',
        'Fireplace' => '<path d="M6 20V9l6-4 6 4v11"/><path d="M4 20h16"/><path d="M12 20v-5"/>',
        'TV' => '<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M8 21h8"/><path d="M12 18v3"/>',
        'Caretaker' => '<circle cx="12" cy="8" r="3"/><path d="M5.5 19.5c1-3 3.2-4.5 6.5-4.5s5.5 1.5 6.5 4.5"/>',
    ];

    public static function svg(string $name): string
    {
        $inner = self::PATHS[$name] ?? '<circle cx="12" cy="12" r="2.2"/>';

        return '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">'.$inner.'</svg>';
    }
}
