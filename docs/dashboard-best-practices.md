# Dashboard standards for ITM

These rules apply to the admin, client, and independent consultant dashboards.
Each screen answers three questions quickly: what needs attention, what changed,
and where can I act? The data and links must stay within the viewer's role.

## Research-backed rules

1. **Start with the audience's work.** Put the most useful current action and
   a small set of relevant measures at the top. Microsoft's [dashboard guidance](https://learn.microsoft.com/en-us/power-bi/create-reports/service-dashboards)
   treats the dashboard as a one-page summary with links to details; its
   [effective reports guidance](https://learn.microsoft.com/en-us/training/paths/power-bi-effective/)
   starts with identifying the audience and their decisions.
2. **Define every number.** Say whether a count is open, overdue, or all time.
   Never substitute a dash or zero for a feature or measure that has not been
   built. Link a summary to the records behind it.
3. **Make the next action direct.** Use one primary action per view and clear
   links from summaries and lists to the corresponding workflow. Put urgent
   queues before recent activity.
4. **Keep detail scannable.** Use short labels, useful ordering, and a limited
   number of rows on the overview; give users a route to a complete list.
   Use a data table when rows need comparing, with a caption and scoped
   headers, as shown by the [GOV.UK table component](https://design-system.service.gov.uk/components/table/).
5. **Make status understandable without color.** Always pair status color with
   text and an icon. Provide keyboard access and visible focus, and keep
   meaningful graphics and controls legible. See [Microsoft's report
   accessibility guidance](https://learn.microsoft.com/en-in/power-bi/create-reports/desktop-accessibility-creating-reports)
   and [WCAG 2.2 non-text contrast](https://www.w3.org/WAI/WCAG22/understanding/non-text-contrast.html).
6. **Design every state.** Empty lists explain what will appear and offer a
   useful action when one exists. Long lists paginate; wide tables scroll
   within their own container on narrow screens.
7. **Protect the scope.** Client data comes from that client's leads;
   consultant data comes from their own customers and tasks; admin lead work
   remains in `/admin`. Counts, details, and links must respect the same scope.

## Current application

| Dashboard | First decision | Detail route |
| --- | --- | --- |
| Client | Review open requests and requests awaiting information | `/service-requests` (paginated account history) |
| Consultant | Follow up on overdue and open tasks | Own customer records and task controls |
| Admin | Respond to open leads without a recorded human reply | Filtered response queue in `/admin/leads` |

The shared `x-ui.dashboard-stat` component keeps summary links and labels
consistent. The ITM tokens and existing table, badge, and empty-state components
provide the visual and accessibility behavior described in `docs/design-system.md`.
