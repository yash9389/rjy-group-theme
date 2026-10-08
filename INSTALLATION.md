# RJY Group WordPress Theme — Installation Guide

This guide walks you through installing, activating, and configuring the RJY Group theme step by step. Everything on the site can be edited from the WordPress admin panel; no code changes are required.

## What You Need

- A working WordPress site running WordPress 6.0 or newer.
- The `rjy-group.zip` theme file.
- Administrator access to your WordPress dashboard.

## Step 1: Install the Theme

1. Log in to your WordPress dashboard (`/wp-admin`).
2. Go to **Appearance > Themes**.
3. Click **Add New Theme**, then **Upload Theme**.
4. Click **Choose File**, select `rjy-group.zip`, and click **Install Now**.
5. When the installation finishes, click **Activate**.

The RJY Group theme is active. Run the content migration below to create the editable pages, service records, and primary navigation.

### Manual installation (alternative)

If your host blocks ZIP uploads: unzip `rjy-group.zip`, upload the resulting `rjy-group` folder via FTP/SFTP to `/wp-content/themes/`, then activate the theme under **Appearance > Themes**.

## Step 2: Set Up the Homepage

The theme ships with a `front-page.php` template, so the homepage renders automatically on your site front page.

- To control what visitors see, go to **Settings > Reading**.
- Recommended: under "Your homepage displays", choose **Your latest posts** (the front-page template still renders) or set a static page. Either way, the full RJY homepage design appears.

## Step 3: Configure Site Identity and Branding

1. Go to **Appearance > Customize > Site Identity**.
2. Upload your logo under **Logo**. The theme accepts flexible logo sizes (80px tall is a good reference). If you leave this empty, the bundled white RJY logo is used.
3. Set the **Site Title** and **Tagline**. The tagline is hidden in the header and shown to search engines, so write a clear one-sentence description of the business.
4. Optional: upload a **Site Icon** (512 x 512px works best). This is the favicon shown in browser tabs.

## Step 4: Configure Theme Settings (Customizer)

Go to **Appearance > Customize > RJY Theme Settings**. This panel controls all homepage and contact content.

### Brand and Colors

- **Primary Navy**: the main navigation, footer, and heading color (default `#123b72`).
- **Accent Blue**: buttons, links, and highlight details (default `#1684d8`).

### Hero

- **Eyebrow, Heading, Description**: the top banner text.
- **Hero Image / Hero Video**: pick from your Media Library. The video plays silently in the background and falls back to the poster image on reduced-motion devices.
- **Hero Button label and URL**: the call-to-action button.

### Homepage Headings

- **About Heading**, **Services Heading**, **Portfolio Heading**, **Testimonials Heading**: the section titles. Edit each to match your positioning.

### Contact Information

- **Houston Phone**, **Toll-Free Phone**, **Email**, **Street Address**, **City, State and ZIP**: these values power the contact section, footer, click-to-call links, and search-engine contact data. Update all five.

### Social Links

- Enter full URLs for Facebook, X, Instagram, LinkedIn, and Vimeo. Leave a field empty to hide that icon.

## Step 5: Create the Navigation Menus

1. Go to **Appearance > RJY Content Migration** and run the migration. Missing pages and service records are created, and the default editable menu is assigned to the Primary location.
2. Go to **Appearance > Menus** to edit the menu. Its order mirrors the React header: Home, Services, Industries, About Us, Support, with Contact Us as the header CTA.
3. Nest menu items under Services, Industries, About Us, and Support to create dropdowns. The mobile navigation uses the same hierarchy.

## Step 6: Add Your Content

The theme registers three editable content types, all found in the dashboard sidebar:

### Services

- Go to **Services > Add New**. Enter the service name as the title, a short summary in the excerpt, and a longer description in the editor.
- Add a featured image (900 x 675px recommended).
- Use the **Order** field to control sorting.
- The migration creates the six starter service records. Their detail pages use `/services/{service-slug}/` URLs.

### Portfolio Projects

- Go to **Portfolio Projects > Add New**. Title, excerpt, editor text, featured image, and Order work exactly like Services.

### Testimonials

- Go to **Testimonials > Add New**. Put the quote in the excerpt and the client's name in the title; featured images become client avatars.

### Standard Pages and Articles

- **Pages > Add New** uses the full-width page template with the styled header and footer.
- **Posts > Add New** publishes news or articles using the single-post template, complete with categories, author line, and comments.
- Footer widget areas live under **Appearance > Widgets**. The first footer column falls back to the configured office contact details when empty.

## Step 7: Review the Contact Form

The bundled form validates submissions, stores them under **Enquiries** in the WordPress dashboard, and sends an email via `wp_mail()` to the configured contact address. Enquiry records are administrator-only. Email delivery depends on the host's mail configuration; configure SMTP through your host or an SMTP plugin if needed.

## Step 8: Final Checks

- Visit the homepage on desktop and mobile and confirm sections and the mobile menu work.
- Test the phone and email links.
- Assign the three menus from Step 5.
- Replace starter images with your own where needed.

## Troubleshooting

| Problem | Fix |
| --- | --- |
| Homepage shows blog posts instead of the RJY design | Confirm **Appearance > Themes** shows RJY Group as active. The `front-page.php` template handles the front page automatically. |
| Header menu is missing | Run **Appearance > RJY Content Migration** to create and assign the primary menu. |
| Dropdowns do not open | Nested menu items require at least one child item under **Appearance > Menus**. |
| Hero video does not play | Choose a supported MP4 or WebM file under **RJY Theme Settings > Hero**. Reduced-motion visitors see the poster image instead. |
| Logo looks stretched | Use a wide logo; the theme scales it to about 80px tall with flexible width. |
| Phone numbers are old | Update all values under **RJY Theme Settings > Contact Information**; they feed the header, footer, form, and search data together. |

## Updating

When a new version of the theme is released, re-upload the ZIP via **Appearance > Themes > Add New > Upload Theme**; WordPress offers to replace the active theme with the new version. Your Customizer settings, menus, and content are stored in the database and are preserved.

## Support

Complete `README.txt` in the theme root summarizes the same steps for quick reference. For anything not covered here, contact your site administrator or theme support.

## Services by Industry and About Us pages

The migration seeds the site page hierarchy for services, industries, company information, support, knowledge-center articles, case studies, testimonials, legal pages, and contact. It creates only missing content; existing pages and service records are never overwritten. Review the seeded copy and complete any business-specific details before launch.
