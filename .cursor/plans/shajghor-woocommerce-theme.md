---
name: buity WooCommerce Theme
overview: Build a new production-ready WordPress WooCommerce theme at wp-content/themes/buity-theme/ with clean semantic HTML, light beauty-shop styling (white + soft pink/green), full homepage shop sections, and WooCommerce hooks for product cards, AJAX cart, and product search.
todos:
  - id: scaffold-theme
    content: Create buity-theme folder, style.css header, functions.php bootstrap, index/page/404/search.php
    status: pending
  - id: theme-setup-enqueue
    content: Implement inc/theme-setup.php (WC support, menus, logo) and inc/enqueue.php (conditional CSS/JS)
    status: pending
  - id: header-footer
    content: Build semantic header.php/footer.php with template-parts (nav, search, cart, footer columns)
    status: pending
  - id: woocommerce-core
    content: Add inc/woocommerce.php hooks, content-product.php, product-card partial, archive-product.php, single templates
    status: pending
  - id: homepage
    content: Implement inc/home-helpers.php, front-page.php, hero/categories/product-section template-parts
    status: pending
  - id: ajax-cart-search
    content: Add inc/cart-ajax.php + cart.js fragments; product search form + search.php + pre_get_posts
    status: pending
  - id: customizer-styles
    content: Add inc/customizer.php for hero/footer settings; complete main/home/shop/product CSS and responsive menu
    status: pending
---

# buity Theme — Implementation Plan

See also: [../PLAN.md](../PLAN.md) in the theme workspace root for the full readable plan.

**Target path:** `/var/www/html/buity/wp-content/themes/buity-theme/`
