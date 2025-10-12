# Luxury Jewels WordPress Theme

A premium theme designed for luxury jewelry stores, offering extensive customization options to create a unique and elegant online shopping experience.

## Theme Customization

This theme is highly customizable. The primary methods for customization are the WordPress Customizer and the Product Attributes management screen.

### 1. WordPress Customizer

Navigate to **Appearance > Customize** in your WordPress dashboard to access the theme's options panel.

#### Header & Navigation
*   **Logo Max Width**: Adjust the size of your logo.
*   **Header Layout**: Choose between a standard layout (logo left, nav right) or a centered layout (logo centered, nav below).
*   **Enable Sticky Header**: Make the header stick to the top of the screen on scroll.

#### Brand Colors
*   Customize every color used throughout the site, including accent colors for buttons, text, backgrounds, and borders.

#### Typography
*   **Heading Font**: Specify a font name from Google Fonts for all headings.
*   **Body Font**: Specify a font name from Google Fonts for all body text.

#### Shop & Product Pages
*   **Products Per Row**: Set the number of columns for the product grid on the shop/archive pages (2, 3, or 4).
*   **Shop & Archive Sidebar**: Choose to display a left sidebar, right sidebar, or no sidebar on shop pages.
*   **Sale Badge Text**: Change the default "Sale!" text to something custom.

#### Global Product Tabs
*   Create up to three content tabs that will appear on *every* product page. You can set a title and content for each. This is ideal for information like shipping policies, materials guides, or a brand story.

#### Footer Options
*   **Footer Widget Columns**: Select between 1 and 4 columns for the footer widget area.
*   **Copyright Text**: Customize the copyright notice in the site footer.

### 2. Configuring Product Attributes

Product attributes are a key feature of the theme, allowing for rich, interactive filtering. Navigate to **Products > Attributes** to manage them.

When adding or editing an attribute, you have several custom options:

*   **Display Type**: This is the most important setting.
    *   **Color Swatch**: Displays the attribute terms as clickable color circles.
    *   **Button**: Displays terms as clickable text buttons.
    *   **Dropdown**: Uses a standard dropdown select menu.
*   **Display in Product Card?**: If checked, the swatches or buttons for this attribute will appear directly on the product cards in the shop grid.
*   **Position**: A number that controls the order in which the attribute filters appear in the shop sidebar (lower numbers appear first).

#### Adding Swatch Colors
When the "Display Type" is set to "Color Swatch", you must configure the terms for that attribute (e.g., for a "Metal" attribute, you would configure terms like "Gold", "Silver", etc.). On the term edit screen, you will find a **Swatch Color** color picker to set the specific color for that term.

### 3. Managing Product Tabs

The theme offers two ways to add tabs (displayed as an accordion on the product page):

*   **Global Tabs**: Set in the Customizer (see above). These appear on all products.
*   **Per-Product Tabs**: On the product edit screen, there is a meta box called **"Extra Product Tabs Content"**. Use the editor here to add tabs specific to that one product.
    *   **To create a new tab**, simply add a **Heading 2 (H2)** for your tab title. All content following that heading (until the next H2) will become the content for that tab.

### 4. Navigation Menu

The theme's primary navigation menu is a hybrid system:
1.  It automatically includes core pages like Home, Cart, My Account, and Checkout.
2.  It automatically adds the top 3 most popular product categories as links (these are hidden on mobile).
3.  You can add your own custom links (e.g., "About Us", "Contact") by going to **Appearance > Menus** and adding items to the 'Primary' menu location. Any links that don't overlap with the core pages will be appended to the menu.

### 5. Homepage Content

The "Shop by Collection" section on the homepage is automatically populated with the top 3 product categories. To set the image for a category, edit that product category and assign a **Thumbnail** image. If no image is set, the theme will use one of its default placeholder images.

### 6. Widget Areas

The theme includes two main widget areas:
*   **Shop Sidebar**: Widgets added here will appear in the sidebar on shop and archive pages (if a sidebar layout is selected in the Customizer).
*   **Footer**: The theme supports up to 4 footer widget columns. To use them, you must first uncomment the `register_sidebar` calls for `footer-1` through `footer-4` in `functions.php`. Then, add widgets under **Appearance > Widgets**.
