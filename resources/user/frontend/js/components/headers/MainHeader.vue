<template>
  <header id="topnav" class="defaultscroll sticky bg-white">
    <div class="container d-flex">
      <!-- Logo container-->
      <router-link id="logo" class="logo flex-1" :to="{name: 'home'}">
        <img src="/assets/admin/images/logo.png" class="l-main" alt />
      </router-link>
      <!-- Logo End -->
      <!-- End Logo container-->

      <div id="navigation" class="flex-1">
        <!-- HOSTING MODE NAVIGATION -->
        <ul class="navigation-menu" v-if="hostingMode">
          <li :class="{'active': this.$route.name.startsWith('home')}">
            <router-link to="/hosting" class="sub-menu-item">Dashboard</router-link>
          </li>
          <li :class="{'active': this.$route.name.startsWith('hosting.venues')}">
            <router-link :to="{name: 'hosting.venues'}" class="sub-menu-item">{{$t('venues')}}</router-link>
          </li>
          <li :class="{'active': this.$route.name.startsWith('hosting.bookings')}">
            <router-link :to="{name: 'hosting.bookings'}" class="sub-menu-item">Bookings</router-link>
          </li>
          <li class="d-md-none">
            <router-link :to="{name: 'home'}" class="sub-menu-item">{{$t('switch_to_travelling')}}</router-link>
          </li>
        </ul>

        <!-- TRAVELLING MODE Navigation Menu-->
        <ul class="navigation-menu" v-else>
          <li :class="{'active': this.$route.name.startsWith('home')}">
            <router-link to="/" class="sub-menu-item">{{$t('home')}}</router-link>
          </li>
          <li v-if="!hostingMode" class="d-md-none">
            <router-link v-if="user" to="/hosting">
              <span v-if="user.type> 0">{{$t('switch_to_hosting')}}</span>
            </router-link>
          </li>
          <li :class="{'active': this.$route.name.startsWith('about')}">
            <router-link :to="{name: 'about.index'}" class="sub-menu-item">{{$t('about')}}</router-link>
          </li>
          <li :class="{'active': this.$route.name.startsWith('venues.index')}">
            <router-link :to="{name: 'venues.index'}" class="sub-menu-item">{{$t('venues')}}</router-link>
          </li>
          <li
            v-if="user"
            :class="{'active': this.$route.name.startsWith('message')}"
            class="d-md-none"
          >
            <router-link :to="{name: 'messages.index'}" class="sub-menu-item">{{$t('messages')}}</router-link>
          </li>
          <li
            v-if="user"
            :class="{'active': this.$route.name.startsWith('account.profile')}"
            class="d-md-none"
          >
            <router-link :to="{name: 'account.profile'}" class="sub-menu-item">{{$t('profile')}}</router-link>
          </li>
          <li v-if="user !== null">
            <a href="javascript:void(0)" @click="$emit('logout')" class="d-md-none">{{$t('logout')}}</a>
          </li>
          <template v-else>
            <li class="d-flex justify-content-around">
              <router-link :to="{name: 'login'}" class="d-md-none">
                <span class="btn btn-warning text-dark">{{$t('login')}}</span>
              </router-link>
              <router-link :to="{name: 'signup'}" class="d-md-none mx-2" id="signup-btn">
                <span class="btn btn-outline-warning text-dark">{{$t('signup')}}</span>
              </router-link>
            </li>
          </template>
        </ul>
        <!--end navigation menu-->
      </div>
      <!--end navigation-->

      <!--Login button Start-->
      <div
        id="right-side-buttons"
        style="justify-content: right; align-items:center;"
        class="flex-1 flex-sm-2"
      >
        <ul class="buy-button list-inline mb-0">
          <li class="list-inline-item" v-if="!hostingMode">
            <router-link v-if="user" to="/hosting">
              <button
                v-if="user.type > 0"
                type="button"
                class="btn text-dark d-none d-md-block"
              >{{$t('switch_to_hosting')}}</button>
            </router-link>
          </li>
          <li class="list-inline-item">
            <!-- Language Selector-->
            <div class="p-0 d-none d-md-block me-2">
              <div
                class="border border-white d-flex align-items-center rounded-pill p-2 px-3 pointer"
                @click="$emit('showLanguageSelector')"
              >
                <i class="uil uil-english-to-chinese text-dark" id="language-icon"></i>
              </div>
            </div>
            <!-- Language Selector End-->
          </li>
          <li class="list-inline-item" v-if="user">
            <!-- Notifications -->
            <div class="dropdown dropdown-primary d-none d-md-block me-2">
              <div
                class="border border-white d-flex align-items-center rounded-pill p-2 pointer"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="uil uil-bell text-dark" style="font-size: 1.4rem; line-height:0;"></i>
              </div>
              <div
                class="dropdown-menu notificationsDropdownMenue dd-menu shadow-lg dropdown-menu-end bg-white shadow rounded border-0 mt-3 py-3"
                id="notificationsDropdownMenue"
                ref="notificationsDropdownMenue"
                style="width: 300px;"
              >
                <ul class="list-unstyled" v-if="notifications.length">
                  <!-- style clicked notifications differently -->
                  <li
                    class="m-3"
                    v-for="notification in notifications.slice(0,5)"
                    :key="notification.id"
                    @click="readNotification(notification)"
                  >{{notification.data.message}}</li>
                </ul>
                <span class="m-3" v-else>You have no new notifications.</span>
              </div>
            </div>
            <!-- Notifications end -->
          </li>
          <li class="list-inline-item" v-if="user">
            <!-- drop down -->
            <div class="dropdown dropdown-primary d-none d-md-block">
              <div
                class="border border-white d-flex align-items-center rounded-pill p-2 pointer"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="uil uil-bars text-dark" style="font-size: 1.4rem; line-height:0;"></i>
                <i class="uil uil-user-circle text-dark" style="font-size: 1.4rem; line-height:0;"></i>
              </div>
              <div
                class="dropdown-menu dd-menu shadow-lg dropdown-menu-end bg-white shadow rounded border-0 mt-3 py-3"
                style="width: 200px;"
              >
                <router-link :to="{name: 'messages.index'}" class="dropdown-item text-dark">
                  <i class="uil uil-envelopes align-middle me-1"></i>
                  {{$t('messages')}}
                </router-link>
                <router-link :to="{name: 'account.profile'}" class="dropdown-item text-dark">
                  <i class="uil uil-user align-middle me-1"></i>
                  {{$t('profile')}}
                </router-link>
                <router-link
                  :to="{name: 'home'}"
                  class="dropdown-item text-dark"
                  v-if="hostingMode"
                >
                  <i class="uil uil-plane-fly align-middle me-1"></i>
                  {{$t('switch_to_travelling')}}
                </router-link>
                <template v-if="user">
                  <div v-if="user.type == 0" class="dropdown-divider my-2 border-top"></div>
                  <router-link
                    v-if="user.type == 0"
                    :to="{name: 'saveVenue.name_and_address'}"
                    class="dropdown-item text-dark"
                  >
                    <i class="uil uil-university align-middle me-1"></i>
                    {{$t('host_a_venue')}}
                  </router-link>
                </template>
                <div class="dropdown-divider my-2 border-top"></div>
                <a
                  href="javascript:void(0)"
                  class="dropdown-item text-dark"
                  @click="$emit('logout')"
                >
                  <i class="uil uil-lock"></i>
                  {{$t('logout')}}
                </a>
              </div>
            </div>
            <!-- drop down end -->
          </li>
        </ul>
        <ul v-if="user == null" class="d-none d-md-block list-inline mb-0">
          <li class="float-end mt-3 me-3">
            <router-link :to="{name: 'login'}">
              <span class="btn btn-warning text-dark">{{$t('login')}}</span>
            </router-link>
            <router-link :to="{name: 'signup'}" class="ms-2" id="signup-btn">
              <span class="btn btn-outline-warning text-dark">{{$t('signup')}}</span>
            </router-link>
          </li>
        </ul>
        <!--Login button End-->
      </div>
      <div class="menu-extras">
        <div class="menu-item">
          <!-- Mobile menu toggle-->
          <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
            <div class="lines">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </a>
          <!-- End mobile menu toggle-->
        </div>
      </div>
    </div>
    <!--end container-->
  </header>
  <!-- Navbar Start -->

  <!--end header-->
  <!-- Navbar End -->
</template>

<script>
export default {
  data() {
    return {
      notifications: [],
    };
  },
  methods: {
    closeNotificationsDropdown() {
      let dd = this.$refs.notificationsDropdownMenue;
      dd.dropdown("toggle");
    },
    readNotification(notification) {
      axios
        .get(`markNotificationAsRead/${notification.id}`)
        .then((response) => {
          const index = this.notifications.findIndex(
            (nf) => nf.id == notification.id
          );
          this.notifications.splice(index, 1);
        });
      switch (notification.type) {
        case "App\\Notifications\\RetreatOrganizer\\BookingConfirmed":
          this.$router.push({
            name: "venue.booking.confirm",
            params: { venueBooking: notification.data.booking_id },
          });
          break;
        case "App\\Notifications\\VenueHost\\BookingRequested":
          this.$router.push({
            name: "hosting.bookings",
          });
          break;
        default:
          break;
      }
      let id = notification.data.param_id;
    },
  },
  computed: {
    user() {
      return this.$store.getters.user ? this.$store.getters.user.user : null;
    },
    hostingMode() {
      return this.$route.name.startsWith("hosting") ? true : false;
    },
  },
  mounted() {
    if (this.user) {
      axios.get("unreadNotifications").then((response) => {
        this.notifications = response.data;
      });
    }
  },
};
</script>

<style scoped>
.sub-menu-item {
  font-size: 14px !important;
}
#topnav {
  box-shadow: 0 0 3px rgb(60 72 88 / 35%);
}
@media (max-width: 1400px) {
  #right-side-buttons {
    flex: 3 !important;
  }
  #navigation {
    flex: 3 !important;
  }
}
</style>>
