# sunasty/color-math

Zero-dependency PHP library for sRGB, OKLCH, relative luminance, and WCAG 2.1 accessibility contrast calculation.

Originally designed for the [Sunasty Color Picker](https://sunasty.com/color-picker/) client-side tool and the [Sunasty Developer Tools](https://sunasty.com/dev/).

## Installation

```bash
composer require sunasty/color-math
```

## Quick Start

```php
use Sunasty\ColorMath\ColorMath;

// Parse hex colors
$teal = ColorMath::hexToRgb('#0F766E');
$white = [255, 255, 255];

// Compute WCAG contrast
$ratio = ColorMath::contrastRatio($teal, $white);
$wcag = ColorMath::checkWcag($ratio);

echo "Contrast: {$ratio}:1\n";
echo "Passes AA: " . ($wcag['aa_normal'] ? 'Yes' : 'No') . "\n";
```

## Related Tools

- [Sunasty Color Picker & Palette Extractor](https://sunasty.com/color-picker/)
- [Sunasty Developer Utilities](https://sunasty.com/dev/)
- [Sunasty Color Picker Chrome Extension](https://github.com/bsulyan/sunasty-color-picker)

## License

MIT © [Bastien Sulyan](https://ulysan.fr)
