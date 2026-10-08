# RJY Group WordPress Theme

Standalone WordPress theme adapted from the RJY Group React site. The theme includes a video-led homepage, responsive dropdown navigation, page hubs, service detail records, editable theme settings, and a WordPress enquiry backend.

## Install

1. Copy the `rjy-group` folder into `wp-content/themes/` and activate **RJY Group** in WordPress.
2. Open **Appearance → RJY Content Migration** and run the migration. It creates missing pages and service records, plus the editable primary menu and dropdown items. Existing pages and services are not overwritten.
3. In **Settings → Reading**, select the intended Home page as the static front page. The homepage layout is provided by `front-page.php`.
4. Set a logo under **Appearance → Customize → Site Identity** and update contact details under **Appearance → Customize → RJY Theme Settings**.
5. Edit dropdown items under **Appearance → Menus**. The default menu is assigned to the Primary Menu location.

## Editable sections

The homepage and page hubs use theme templates. Service records are editable under **Services** in the dashboard and render at `/services/{service-slug}/`. Supporting pages are regular hierarchical WordPress Pages and can be edited in the block editor.

## Contact details

The contact form stores enquiries in the WordPress database under **Enquiries** and sends a notification using `wp_mail()`. Delivery depends on the WordPress host's mail configuration; configure SMTP through the host or an SMTP plugin if needed. Enquiry records are limited to administrators because they contain contact information.