<?php
function icon_svg(
    string $class,
    array $parts
): string {
    return '<svg class="' .
        $class .
        '" viewBox="0 0 24 24" ' .
        'fill="none" ' .
        'stroke="currentColor" ' .
        'stroke-width="2" ' .
        'stroke-linecap="round" ' .
        'stroke-linejoin="round" ' .
        'aria-hidden="true">' .
        implode("", $parts) .
        "</svg>";
}

function icon_tree_pine(
    string $class = ""
): string {
    return icon_svg($class, [
        '<path d="M12 2L8 7h8l-4-5z"/>',
        '<path d="M12 7l-5 7h10l-5-7z"/>',
        '<path d="M12 14l-6 8h12l-6-8z"/>',
    ]);
}

function icon_pointer(
    string $class = ""
): string {
    return icon_svg($class, [
        '<path d="M3 3l7.5 17L13 13l7-2-17-8z"/>',
    ]);
}

function icon_dollar(
    string $class = ""
): string {
    return icon_svg($class, [
        '<line x1="12" y1="1" x2="12" y2="23"/>',
        '<path d="' .
            'M17 5H9.5a3.5 3.5 0 0 0 0 7' .
            'H14a3.5 3.5 0 0 1 0 7H6"/>',
    ]);
}

function icon_sparkles(
    string $class = ""
): string {
    return icon_svg($class, [
        '<path d="' .
            'M9 3l1.5 3L14 7.5 10.5 9 ' .
            '9 12 7.5 9 4 7.5 7.5 6z"/>',
        '<path d="' .
            'M18 13l1 2 2 1-2 1-1 2' .
            '-1-2-2-1 2-1z"/>',
        '<path d="' .
            'M6 14l.75 1.5L8.5 16' .
            'l-1.75.5L6 18l-.75-1.5' .
            'L3.5 16l1.75-.5z"/>',
    ]);
}

function icon_arrow_left(
    string $class = ""
): string {
    return icon_svg($class, [
        '<path d="M19 12H5"/>',
        '<path d="M11 19l-7-7 7-7"/>',
    ]);
}

function icon_sun(
    string $class = ""
): string {
    return icon_svg($class, [
        '<circle cx="12" cy="12" r="4"/>',
        '<line x1="12" y1="2" x2="12" y2="4"/>',
        '<line x1="12" y1="20" x2="12" y2="22"/>',
        '<line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>',
        '<line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>',
        '<line x1="2" y1="12" x2="4" y2="12"/>',
        '<line x1="20" y1="12" x2="22" y2="12"/>',
        '<line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>',
        '<line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
    ]);
}

function icon_moon(
    string $class = ""
): string {
    return icon_svg($class, [
        '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>',
    ]);
}
