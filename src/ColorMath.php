<?php

declare(strict_types=1);

namespace Sunasty\ColorMath;

/**
 * Sunasty ColorMath: Zero-dependency sRGB, OKLCH, and WCAG 2.1 contrast calculations in PHP.
 * Published by Sunasty (https://sunasty.com/color-picker/).
 */
final class ColorMath
{
    /**
     * @return array{0: int, 1: int, 2: int}
     */
    public static function hexToRgb(string $hex): array
    {
        $clean = ltrim(trim($hex), '#');
        if (strlen($clean) === 3) {
            $clean = $clean[0].$clean[0].$clean[1].$clean[1].$clean[2].$clean[2];
        }
        if (strlen($clean) !== 6) {
            throw new \InvalidArgumentException("Invalid hex color: {$hex}");
        }
        $val = hexdec($clean);
        return [
            ($val >> 16) & 255,
            ($val >> 8) & 255,
            $val & 255,
        ];
    }

    public static function rgbToHex(int $r, int $g, int $b): string
    {
        $r = max(0, min(255, $r));
        $g = max(0, min(255, $g));
        $b = max(0, min(255, $b));
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    public static function linearize(float $channel8bit): float
    {
        $v = max(0.0, min(255.0, $channel8bit)) / 255.0;
        return $v <= 0.04045 ? $v / 12.92 : (($v + 0.055) / 1.055) ** 2.4;
    }

    public static function relativeLuminance(int $r, int $g, int $b): float
    {
        return 0.2126 * self::linearize((float)$r)
             + 0.7152 * self::linearize((float)$g)
             + 0.0722 * self::linearize((float)$b);
    }

    /**
     * @param array{0: int, 1: int, 2: int} $colorA
     * @param array{0: int, 1: int, 2: int} $colorB
     */
    public static function contrastRatio(array $colorA, array $colorB): float
    {
        $l1 = self::relativeLuminance($colorA[0], $colorA[1], $colorA[2]);
        $l2 = self::relativeLuminance($colorB[0], $colorB[1], $colorB[2]);
        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);
        return round(($lighter + 0.05) / ($darker + 0.05), 2);
    }

    /**
     * @return array{ratio: float, aa_normal: bool, aa_large: bool, aaa_normal: bool, aaa_large: bool}
     */
    public static function checkWcag(float $contrast): array
    {
        return [
            'ratio' => $contrast,
            'aa_normal' => $contrast >= 4.5,
            'aa_large' => $contrast >= 3.0,
            'aaa_normal' => $contrast >= 7.0,
            'aaa_large' => $contrast >= 4.5,
        ];
    }
}
