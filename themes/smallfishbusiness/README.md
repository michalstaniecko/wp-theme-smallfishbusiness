# Small Fish Business Theme

A minimalist WordPress blog theme built with Vite and TailwindCSS v4.

## Requirements

- WordPress 6.0+
- PHP 8.0+
- Node.js 20.19+ or 22.12+

## Installation

1. Upload the theme folder to `/wp-content/themes/`
2. Activate the theme in WordPress Admin > Appearance > Themes
3. Configure menus in Appearance > Menus
4. Add widgets in Appearance > Widgets

## Development

```bash
# Navigate to theme development directory
cd smallfishbusiness-develop

# Install dependencies
npm install

# Start development server with HMR
npm run dev

# Build for production
npm run build
```

## Theme Features

- Minimalist, clean design
- TailwindCSS v4 styling
- Gutenberg block support with custom styles
- Table of Contents for articles
- Responsive mobile-first layout
- Custom widgets (About, Categories, Popular Posts)

## Customization

### Colors

Edit `src/css/main.css` to change the color palette in the `@theme` section.

### Fonts

Update font family in `src/css/main.css` under `@theme`.

### Block Styles

Custom block styles are registered in `inc/gutenberg.php`.

## Support

For issues and feature requests, please open an issue on GitHub.

## License

GPL v2 or later
