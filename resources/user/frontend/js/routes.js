import MainLayout from "./layouts/MainLayout.vue";
import Home from "./pages/Home.vue";
import AccountLayout from "./layouts/AccountLayout.vue";
import Auth from "./layouts/AuthLayout.vue";
import SaveVenueLayout from "./layouts/SaveVenueLayout.vue";
export const routes = [{
        path: "/auth",
        component: Auth,
        children: [{
                path: "login",
                name: "login",
                component: () => import("./pages/auth/Login.vue"),
            },
            {
                path: "signup",
                name: "signup",
                component: () => import("./pages/auth/Signup.vue")
            }
        ]
    },
    {
        path: "",
        component: MainLayout,
        children: [{
                path: "",
                name: "home",
                component: Home,
            },
            {
                path: "saveVenue/:venue?",
                meta: {
                    requiresAuth: true,
                },
                component: SaveVenueLayout,
                children: [{
                        path: "name_and_address",
                        name: "saveVenue.name_and_address",
                        component: () => import('./pages/venue/save/NameAddress.vue')
                    },
                    {
                        path: "arrival",
                        name: "saveVenue.arrival",
                        component: () => import('./pages/venue/save/Arrival.vue')
                    },
                    {
                        path: "description_and_features",
                        name: "saveVenue.description",
                        component: () => import('./pages/venue/save/Description.vue')
                    },
                    {
                        path: "amenities_and_facilities",
                        name: "saveVenue.facilities",
                        component: () => import('./pages/venue/save/Facilities.vue')
                    },
                    {
                        path: "yoga_and_workshop_space",
                        name: "saveVenue.yoga",
                        component: () => import('./pages/venue/save/Yoga.vue')
                    },
                    {
                        path: "surroundings_and_things_to_do",
                        name: "saveVenue.surroundings",
                        component: () => import('./pages/venue/save/Surroundings.vue')
                    },
                    {
                        path: "kitchen_and_food",
                        name: "saveVenue.kitchen",
                        component: () => import('./pages/venue/save/Kitchen.vue')
                    },
                    {
                        path: "accommodations",
                        name: "saveVenue.accommodations",
                        component: () => import('./pages/venue/save/Accommodations.vue')
                    },
                    {
                        path: "venue_pricing",
                        name: "saveVenue.venue_pricing",
                        component: () => import('./pages/venue/save/VenuePricing.vue')
                    },
                    {
                        path: "meals_pricing",
                        name: "saveVenue.meals_pricing",
                        component: () => import('./pages/venue/save/MealsPricing.vue')
                    },
                    {
                        path: "retreat_organizer",
                        name: "saveVenue.retreat_organizer",
                        component: () => import('./pages/venue/save/RetreatOrganizer')
                    },
                    {
                        path: "check_in_check_out",
                        name: "saveVenue.check_in_check_out",
                        component: () => import('./pages/venue/save/CheckIn')
                    },
                    {
                        path: "cancellation_policy",
                        name: "saveVenue.cancellation_policy",
                        component: () => import('./pages/venue/save/CancellationPolicy')
                    }
                ]
            },
            {
                path: "about",
                name: "about.index",
                component: () => import('./pages/about/Index.vue')
            },
            {
                path: "account",
                name: "account",
                meta: {
                    requiresAuth: true,
                },
                component: AccountLayout,
                children: [{
                    path: "profile",
                    name: "account.profile",
                    component: () => import('./pages/account/Profile.vue')
                }]
            },
            {
                path: "wizard",
                name: "wizard",
                component: () => import('./pages/wizard/Index.vue')
            },
            {
                path: "/venues",
                name: "venues.index",
                component: () => import('./pages/venue/Index.vue')
            },
            {
                path: "venues/:venue",
                name: "venue.show",
                component: () => import('./pages/venue/Show.vue')
            },
            {
                path: "venues/:venue/book",
                name: "venue.book",
                component: () => import('./pages/venue/booking/Request.vue')
            },
            {
                path: "venues/booking/confirm/:venueBooking",
                name: "venue.booking.confirm",
                component: () => import('./pages/venue/booking/Confirmation.vue')
            },
            {
                path: "venues/booking/ConfirmationSuccess/:venueBooking",
                name: "venue.booking.confirmationSuccess",
                component: () => import('./pages/venue/booking/ConfirmationSuccess.vue')
            },
            {
                path: "venues/booking/secondPayment/:venueBooking",
                name: "venue.booking.secondPayment",
                component: () => import('./pages/venue/booking/SecondPayment.vue')
            },
            {
                path: "venues/booking/RemainingBalancePaymentSuccess/:venueBooking",
                name: "venue.booking.remainingBalancePaymentSuccess",
                component: () => import('./pages/venue/booking/RemainingBalancePaymentSuccess.vue'),
            },
            {
                path: "venues",
                name: "venues.index",
                component: () => import('./pages/venue/Index.vue')
            },
            // {
            //     path: "messages",
            //     name: "messages.index",
            //     component: () => import('./pages/message/Index.vue')
            // },
            {
                path: "messages/:user?/:senderType?/:receiverType?",
                name: "messages.index",
                component: () => import('./pages/message/Index.vue')
            },
        ],
    },
    {
        path: "/hosting",
        component: MainLayout,
        children: [{
                path: "",
                name: "hosting.home",
                component: () => import('./pages/hosting/Dashboard.vue')
            },
            {
                path: "venues",
                name: "hosting.venues",
                component: () => import('./pages/hosting/venues/Index.vue')
            },
            {
                path: "bookings",
                name: "hosting.bookings",
                component: () => import('./pages/hosting/booking/Index.vue')
            },
            {
                path: "bookings/details/:venueBooking",
                name: "hosting.booking.details",
                component: () => import('./pages/hosting/booking/Details.vue')
            }
        ]
    }

];
