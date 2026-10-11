# Cookie Consent Banner

A lightweight WordPress plugin that displays a dismissible GDPR cookie consent notice. No external scripts, no tracking pixels, no bloat — just a clean banner with fully configurable content and colors.

## Features

- Dismissible banner fixed to the bottom of the page
- Configurable **message text**, **accept button label**, and **Privacy Policy link**
- Fully configurable **colors** (banner background, text, button background, button text)
- Consent stored in a **first-party cookie** (no external dependencies)
- Configurable **cookie duration** (default: 365 days)
- Banner hidden immediately on click — no page reload required
- Zero jQuery, zero external scripts

## WordPress Concepts Demonstrated

| Concept | Where Used |
|---|---|
| Settings API (two sections: Content + Colors) | `class-ccb-settings.php` |
| `sanitize_textarea_field`, `esc_url_raw`, `sanitize_hex_color` | Field-appropriate sanitization |
| `wp_localize_script()` | Passing PHP settings (cookie duration) to JS |
| `wp_add_inline_style()` | Injecting CSS custom properties from saved options |
| `register_activation_hook` | Setting default options on activation |
| `wp_footer` action | Rendering banner markup |
| `wp_enqueue_style` / `wp_enqueue_script` | Front-end asset loading |
| First-party cookie via `document.cookie` | Storing consent client-side without external services |

## File Structure

```
cookie-consent-banner/
├── cookie-consent-banner.php   # Plugin bootstrap + activation hook
├── includes/
│   ├── class-ccb-settings.php  # Settings API page (Settings → Cookie Consent)
│   └── class-ccb-banner.php    # Enqueue assets + render banner in footer
├── assets/
│   ├── css/front.css           # Banner styles with CSS custom properties
│   └── js/front.js             # Cookie read/write + dismiss logic
└── README.md
```

## Installation

1. Upload the `cookie-consent-banner` folder to `/wp-content/plugins/`
2. Activate in **Plugins → Installed Plugins**
3. Configure via **Settings → Cookie Consent**

## Requirements

- WordPress 6.0+
- PHP 8.0+
