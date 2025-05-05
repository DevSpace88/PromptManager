/*
 * Main and demo navigation arrays
 *
 */

export default {
  main: [
    {
      name: "Dashboard",
      to: "/dashboard",
      icon: "si si-speedometer",
    },
    {
      name: "AI Tools",
      heading: true,
    },
    {
      name: "Prompts",
      to: "/prompts",
      icon: "si si-bubble",
    },
    {
      name: "Workflows",
      to: "/workflows",
      icon: "si si-flow",
    },
    {
      name: "Playground",
      to: "/playground",
      icon: "si si-chemistry",
    },
    {
      name: "API Keys",
      to: "/api-keys",
      icon: "si si-key",
    },
    {
      name: "Models",
      icon: "si si-ghost",
      subActivePaths: "/models",
      sub: [
        {
          name: "Overview",
          to: "/models",
        },
        {
          name: "Settings",
          to: "/models/settings",
        },
        {
          name: "Fine-tuning",
          to: "/models/fine-tuning",
        }
      ],
    },
    {
      name: "Content",
      heading: true,
    },
    {
      name: "Document Management",
      icon: "si si-docs",
      subActivePaths: "/documents",
      sub: [
        {
          name: "My Documents",
          to: "/documents",
        },
        {
          name: "Upload",
          to: "/documents/upload",
        },
        {
          name: "Shared",
          to: "/documents/shared",
        },
      ],
    },
    {
      name: "Knowledge Base",
      to: "/knowledge-base",
      icon: "si si-book-open",
    },
    {
      name: "Settings",
      heading: true,
    },
    {
      name: "User Profile",
      icon: "si si-user",
      to: "/profile",
    },
    {
      name: "System",
      icon: "si si-settings",
      subActivePaths: "/settings",
      sub: [
        {
          name: "General",
          to: "/settings/general",
        },
        {
          name: "Appearance",
          to: "/settings/appearance",
        },
        {
          name: "Security",
          to: "/settings/security",
        },
        {
          name: "Integrations",
          to: "/settings/integrations",
        },
      ],
    },
  ],

    boxed: [
    {
      name: "Dashboard-TEST",
      to: "/preview/backend-boxed/",
      icon: "si si-compass",
    },
    {
      name: "Pages",
      heading: true,
    },
    {
      name: "Variations",
      icon: "si si-puzzle",
      sub: [
        {
          name: "Simple 1",
          to: "/preview/backend-boxed/simple1",
        },
        {
          name: "Simple 2",
          to: "/preview/backend-boxed/simple2",
        },
        {
          name: "Image 1",
          to: "/preview/backend-boxed/image1",
        },
        {
          name: "Image 2",
          to: "/preview/backend-boxed/image2",
        },
      ],
    },
    {
      name: "Search",
      to: "/preview/backend-boxed/search",
      icon: "si si-magnifier",
    },
    {
      name: "Go Back",
      to: "/preview/backend/dashboard",
      icon: "si si-action-undo",
    },
  ],
  demo: [
    {
      name: "Home",
      to: "#",
      icon: "fa fa-home",
      badge: 5,
    },
    {
      name: "Manage",
      heading: true,
    },
    {
      name: "Products",
      icon: "fa fa-briefcase",
      sub: [
        {
          name: "HTML Templates",
          icon: "fab fa-html5",
          sub: [
            {
              name: "Description",
              to: "#",
              icon: "fa fa-pencil-alt",
            },
            {
              name: "Statistics",
              to: "#",
              icon: "fa fa-chart-line",
            },
            {
              name: "Sales",
              to: "#",
              icon: "fa fa-chart-area",
              badge: 320,
            },
            {
              name: "Media",
              to: "#",
              icon: "far fa-images",
              badge: 18,
            },
            {
              name: "Files",
              to: "#",
              icon: "far fa-file-alt",
              badge: 32,
            },
          ],
        },
        {
          name: "WordPress Themes",
          icon: "fab fa-wordpress",
          sub: [
            {
              name: "Description",
              to: "#",
              icon: "fa fa-pencil-alt",
            },
            {
              name: "Statistics",
              to: "#",
              icon: "fa fa-chart-line",
            },
            {
              name: "Sales",
              to: "#",
              icon: "fa fa-chart-area",
              badge: 680,
            },
            {
              name: "Media",
              to: "#",
              icon: "far fa-images",
              badge: 15,
            },
            {
              name: "Files",
              to: "#",
              icon: "far fa-file-alt",
              badge: 20,
            },
          ],
        },
        {
          name: "Web Applications",
          icon: "fab fa-medapps",
          sub: [
            {
              name: "Description",
              to: "#",
              icon: "fa fa-pencil-alt",
            },
            {
              name: "Statistics",
              to: "#",
              icon: "fa fa-chart-line",
            },
            {
              name: "Sales",
              to: "#",
              icon: "fa fa-chart-area",
              badge: 158,
            },
            {
              name: "Media",
              to: "#",
              icon: "far fa-images",
              badge: 65,
            },
            {
              name: "Files",
              to: "#",
              icon: "far fa-file-alt",
              badge: 78,
            },
          ],
        },
        {
          name: "Video Templates",
          icon: "fab fa-youtube",
          sub: [
            {
              name: "Description",
              to: "#",
              icon: "fa fa-pencil-alt",
            },
            {
              name: "Statistics",
              to: "#",
              icon: "fa fa-chart-line",
            },
            {
              name: "Sales",
              to: "#",
              icon: "fa fa-chart-area",
              badge: 920,
            },
            {
              name: "Media",
              to: "#",
              icon: "far fa-images",
              badge: 7,
            },
            {
              name: "Files",
              to: "#",
              icon: "far fa-file-alt",
              badge: 19,
            },
          ],
        },
        {
          name: "Add Product",
          to: "#",
          icon: "fa fa-plus",
        },
      ],
    },
    {
      name: "Payments",
      icon: "fa fa-money-bill-wave",
      sub: [
        {
          name: "Scheduled",
          to: "#",
          badge: 3,
          "badge-variant": "success",
        },
        {
          name: "Recurring",
          to: "#",
        },
        {
          name: "Manage",
          to: "#",
        },
        {
          name: "New Payment",
          to: "#",
          icon: "fa fa-plus",
        },
      ],
    },
    {
      name: "Services",
      icon: "fa fa-globe-americas",
      sub: [
        {
          name: "Hosting",
          to: "#",
        },
        {
          name: "Web Design",
          to: "#",
        },
        {
          name: "Web Development",
          to: "#",
        },
        {
          name: "Graphic Design",
          to: "#",
        },
        {
          name: "Legal",
          to: "#",
        },
        {
          name: "Consulting",
          to: "#",
        },
      ],
    },
    {
      name: "Personal",
      heading: true,
    },
    {
      name: "Profile",
      icon: "far fa-user",
      sub: [
        {
          name: "Edit",
          to: "#",
        },
        {
          name: "Settings",
          to: "#",
        },
        {
          name: "Log out",
          to: "#",
        },
      ],
    },
  ],
};
