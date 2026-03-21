# Shopify App Design & UI Guidelines (Internal)

If you are making UI changes in this repository, read this file first.

## Goal

Keep the embedded app aligned with Shopify App Home guidance and App Bridge web components. Prefer Polaris web components and App Bridge navigation patterns over custom CSS/UI.

## Non‑negotiables

- Use Polaris web components (`<s-*>`) for UI instead of custom HTML + Tailwind classes.
- Use App Bridge web components for navigation (`<s-app-nav>` + `<s-link>`).
- Keep the app embedded and HTTPS only.
- Do not duplicate the App Nav inside the app body.

## Required scripts and meta

Include these in the HTML head:
- `<meta name="shopify-api-key" content="...">`
- `<script src="https://cdn.shopify.com/shopifycloud/app-bridge.js"></script>`
- `<script src="https://cdn.shopify.com/shopifycloud/polaris.js"></script>`

## App Bridge navigation behavior

- App Bridge web components trigger `shopify:navigate` events.
- Your frontend must listen for `shopify:navigate` and forward to the client router.
- If you use Inertia, that means `router.visit(href)` (as done in `resources/js/bootstrap/inertia.js`).
  For other routers, use the equivalent navigation API.

## App Nav rules

- Use `<s-app-nav>` wherever your app defines global navigation.
- Add the home route as `<s-link href="/" rel="home">Home</s-link>`.
- Do not render the home link again in the visible nav list.
- Keep navigation labels short and scannable.
- Use nouns instead of verbs for nav labels.
- Avoid more than 7 items; overflow is collapsed into a "View more" menu by Shopify.
- Avoid placing the main navigation in the page header.
- Do not replicate App Nav links inside the page body.

## App name guidelines

- The app name should be short (20 characters or fewer).
- Do not put a description in the app name.
- The app name in the App Nav should represent your app homepage.

## Page structure rules

- Use `<s-page>` as the top‑level wrapper for each page.
- Use `<s-section>` for logical groups inside a page.
- Use `<s-stack>` for simple horizontal layout.
- Use `<s-box>` for small layout grouping only when needed.
- Prefer `<s-text>` and `<s-paragraph>` over raw text nodes.

## Homepage expectations

- The home page should provide daily value to merchants.
- Provide status updates or quick stats when possible.
- Include clear call‑to‑action buttons for core tasks.
- Keep onboarding short and dismissible if it is not essential.

## Tabs usage (secondary navigation)

- Use tabs sparingly and only when the App Nav is not sufficient.
- Tabs should only change content below them and never the header.
- Tabs should not wrap to multiple lines.

## Accessibility (strict)

- Provide text labels for all interactive controls.
- Ensure all icon-only buttons have accessible labels.
- Maintain logical tab order and visible focus states.
- Do not rely on color alone to convey meaning.
- Use semantic Polaris components instead of custom divs for form fields.

## Responsiveness (strict)

- Layout must work on desktop and mobile widths.
- Avoid fixed widths that clip content.
- Use Polaris layout components for spacing and alignment.
- Never assume a specific viewport size.

## Status, loading, and empty states

- Provide a loading state for async actions.
- Provide empty states when lists or tables have no data.
- Show clear error messages when requests fail.
- Avoid blocking the whole page for small inline actions.

## Forms and actions

- Primary action must be clear and singular per page or section.
- Use helper text for non-obvious fields.
- Validate inputs and show inline errors.
- Do not hide critical actions in overflow menus.

## Tables and lists

- Use tables only when multiple columns are required.
- Keep row actions consistent across the table.
- Use pagination or lazy loading for large datasets.

## Template file map (paths in this repo)

- HTML shell: `resources/views/app.blade.php` (App Bridge + Polaris scripts).
- App bootstrap: `resources/js/bootstrap/inertia.js` (wires `shopify:navigate`).
- Global layout: `resources/js/layouts/AppLayout.vue` (renders `<s-app-nav>`).
- App Nav component: `resources/js/components/app/AppNav.vue`.
- Header component: `resources/js/components/app/AppHeader.vue` (optional).
- Page components: `resources/js/pages/**/Index.vue` (Polaris web components only).

## Adding a new page (step‑by‑step)

1. Create a new page component.
2. Use `<s-page>` and `<s-section>` for structure.
3. Add a controller/handler that renders the page.
4. Add the route in your router.
5. Add a nav item in your App Nav.

## Do / Don’t

- Do: prefer Polaris web components for layout and typography.
- Do: keep the App Nav only in the Shopify nav surface.
- Do: keep text and actions inside `<s-page>` and `<s-section>`.
- Do not: add Tailwind utility classes for layout/spacing unless Polaris lacks a component.
- Do not: wrap layout in raw `<div>` containers when a Polaris component exists.
- Do not: place a second navigation menu in the body.
- Do not: remove the App Bridge script tag.
- Do not: introduce custom colors or typography that conflict with Shopify admin UI.
- Do not: replace Polaris components with raw HTML unless there is no equivalent.

## Validation and checks

- Verify App Bridge and Polaris scripts load in the HTML head.
- Verify App Nav appears in Shopify admin left nav.
- Verify `shopify:navigate` triggers client navigation.
- Verify pages render without Tailwind utility classes.
- Verify keyboard-only navigation works.
- Verify color contrast is acceptable.
- Verify empty and error states exist for data views.

## References

- App Nav web component: https://shopify.dev/docs/api/app-home/app-bridge-web-components/app-nav
- Navigation guidelines: https://shopify.dev/docs/apps/design/navigation
- App Design Guidelines: https://shopify.dev/docs/apps/design
- App Home overview: https://shopify.dev/docs/api/app-home
- App Bridge: https://shopify.dev/docs/api/app-bridge
