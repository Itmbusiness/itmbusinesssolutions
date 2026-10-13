# Public page image upgrade

The attached page designs guide the layout and imagery. The two external analysis
documents are background references; their template prompts and proposed 3D
sculpture are not project instructions. This update uses generated raster images
and the existing Blade, Alpine, motion and CMS architecture.

## Images

Images are in `public/images/public/`. Each subject has a 1536px WebP and a 640px
WebP (`<subject>-v1.webp` and `<subject>-v1-640.webp`). The generated originals are
retained in Codex's generated-images directory. These are illustrative assets,
not photographs of ITM's actual staff, clients or offices.

| Subject | Placement | Generation brief |
| --- | --- | --- |
| registration | Home, registration headers and category cards | Navy leather folders, blank documents, teal glass panels, gold pen and greenery on a graphite desk; objects on right, clear navy space on left. |
| consultation | About approach, trusts and advisory cards | Two Black African professionals reviewing blank papers in a bright contemporary office; natural expressions, daylight and teal accents. |
| requirements | Requirements header and document preparation | White checklist clipboard, navy binders, teal folder and padlock shield; objects on right, navy space on left. |
| quotation | Quotation header, process, next steps and closing invitations | Upright navy folder, teal glass document panel, blank ruled pages and gold pen; right-side composition with clear navy left side. |
| resources | Guidance header and document articles | Open navy reference book, organized papers, calculator and gold-tipped pen; daylight, right-side composition and navy left side. |
| tax | Tax service and article cards | Calculator, abstract financial worksheet, gold-accented pen and a professional hand reviewing papers on a light desk. |
| employer | Employer and NSSA categories | Two Black African colleagues wearing safety vests and hard hats reviewing a clipboard in a bright industrial workshop. |
| branding | Business document categories | Navy and teal business brochures, white cards, presentation folder and gold pen on a bright white desk. |
| licensing | Business licensing categories | Black African shop owner in a navy apron holding a document folder outside an unbranded contemporary storefront. |

All prompts requested realistic proportions and materials, no logos, readable
text, watermark or UI. Raster imagery provides the visual depth; HTML supplies
all actual content and controls.

The `public.media` component reserves image dimensions, loads below-fold images
lazily and supplies responsive sources. Shared headings and closing invitations
use decorative images; card titles and CMS content provide their accessible text.

## Owner changes

Admin Settings has a Public Images grid with current previews. Upload or replace
a JPEG, PNG or WebP image up to 8 MB and 6000 pixels per side. Remove hides that
subject's image; restore brings back its generated default. Replaced uploads are
deleted only when no other image setting uses them. Generated originals remain
available for restoration.

Uploads use Laravel's `public_images` disk rooted at `public/images/uploads`,
which is gitignored and directly served without requiring a Windows storage
symlink. Preserve this folder when deploying or backing up uploaded media.
`App\Support\PublicMedia` assigns images by content subject, with registration
as the fallback for newly added CMS content.

## Requirements and quotation

The requirements finder uses published services in published categories, reads
their existing JSON requirements and suitability lists, and links to the full
service. Alpine filters by service, category, document or audience text. Without
JavaScript every checklist remains available as a native disclosure.

The quotation layout retains existing fields, consent, honeypot, validation and
lead handling. The next-step panel moves below the form on mobile; Alpine adds a
disabled sending state only after native validation allows submission.

`tools/optimize-public-images.php subject=absolute-source.png` produces the two
WebP sizes using PHP GD. No new production dependency is required.

## Verification

Run the PublicExperienceTest, PublicSiteTest, QuoteRequestTest and AdminTest
feature suites after `npm run build` completes. Running the build and tests
simultaneously can race the font manifest. Visual browser checks are still needed
when a browser connection is available.
