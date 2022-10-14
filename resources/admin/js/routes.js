import MainLayout from "./layouts/MainLayout.vue";
import Home from "./pages/Home.vue";
import Profile from "./pages/Profile.vue";
import Login from "./pages/Login.vue";

export const routes = [
    {
        path: "/admin/login",
        name: "admin.login",
        component: Login,
    },
    {
        path: "/admin",
        component: MainLayout,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "",
                name: "dashboard",
                component: Home,
            },
            {
                path: "currency",
                name: "currency.index",
                component: () => import('./pages/currency/Index.vue')
            },
            {
                path: "users",
                name: "user.index",
                component: () => import('./pages/cms/user.vue')
            },
            {
                path: "currency/save/:currency?",
                name: "currency.save",
                component: () => import('./pages/currency/Save.vue')
            },
            {
                path: "profile",
                name: "profile.update",
                component: Profile,
            },
            {
                path: "about",
                name: "about.update",
                component: () => import('./pages/About.vue'),
            },
            {
                path: "team",
                name: "team.index",
                component: () => import('./pages/team/Index.vue')
            },
            {
                path: "team/save/:teamMember?",
                name: "team.save",
                component: () => import('./pages/team/Save.vue')
            },
            {
                path: "faq-categories",
                name: "faqCat.index",
                component: () => import('./pages/faq/Index.vue')
            },
            {
                path: "faq-categories/save/:faqCat?",
                name: "faqCat.save",
                component: () => import('./pages/faq/Save.vue')
            },
            {
                path: "faq-categories/:faqCat",
                name: "faqCat.show",
                component: () => import('./pages/faq/Show.vue')
            },
            {
                path: "faq-categories/:faqCat/faq/save/:faq?",
                name: "faq.save",
                component: () => import('./pages/faq/SaveFaq.vue')
            },
            {
                path: "languages",
                name: "language.index",
                component: () => import('./pages/language/Index.vue')
            },
            {
                path: "language/save/:language?",
                name: "language.save",
                component: () => import('./pages/language/Save.vue')
            },
            {
                path: "languages/:language/translate",
                name: "language.translate",
                component: () => import('./pages/language/translation/Save.vue')
            },
            {
                path: "venue",
                name: "venue.index",
                component: () => import('./pages/venue/Index.vue')
            },
            {
                path: "venue-booking/:id",
                props:true,
                name: "venue.bookings",
                component: () => import('./pages/venue/booking/index.vue')
            },
            {
                path: "venue/pending",
                name: "venue.pending",
                component: () => import('./pages/venue/Pending.vue')
            },
            {
                path: "venue/live",
                name: "venue.live",
                component: () => import('./pages/venue/Live.vue')
            },
            {
                path: "venue/rejected",
                name: "venue.rejected",
                component: () => import('./pages/venue/Rejected.vue')
            },
            {
                path: "venueData",
                name: "venueData.index",
                component: () => import('./pages/venue/venueData/Index.vue')
            },
            {
                path: "venueData/:venueDataCategory?/save/:venueData?",
                name: "venueData.save",
                component: () => import('./pages/venue/venueData/Save.vue')
            },
            {
                path: "venueDataCategory/:venueDataCategory/save",
                name: "venueDataCategory.save",
                component: () => import('./pages/venue/venueData/SaveCategory.vue')
            },

            // retreats
            {
                path: "retreats",
                name: "retreats.index",
                component: () => import('./pages/retreat/Index.vue')
            },
            {
                props: true,
                path: "retreat/:categoryId?/save/:retreatId?",
                name: "retreat.save",
                component: () => import('./pages/retreat/Save.vue')
            },
            {
                path: "retreat-category/:id?/save",
                name: "retreat.category.save",
                props: true,
                component: () => import('./pages/retreat/SaveCategory.vue')
            },
            // end retreat
            {
                path: "propertyTypes",
                name: "propertyTypes.index",
                component: () => import('./pages/venue/propertyType/index.vue')
            },
            {
                path: "propertyType/:propertyType?",
                name: "propertyType.save",
                component: () => import('./pages/venue/propertyType/save.vue')
            },
            {
                path: "accommodationTypes",
                name: "accommodationTypes.index",
                component: () => import('./pages/venue/accommodationType/index.vue')
            },
            {
                path: "accommodationType/:accommodationType?",
                name: "accommodationType.save",
                component: () => import('./pages/venue/accommodationType/save.vue')
            },
            {
                path: "cancellationPolicyData",
                name: "cancellationPolicyData.index",
                component: () => import('./pages/venue/cancellationPolicyData/index.vue')
            },
            {
                path: "cancellationPolicyData/:cancellationPolicy?/save",
                name: "cancellationPolicyData.save",
                component: () => import('./pages/venue/cancellationPolicyData/save.vue')
            },
            {
                path: "fee",
                name: "fee.update",
                component: () => import('./pages/Fee.vue'),
            },
        ],
    },
];
