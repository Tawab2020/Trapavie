<template>
  <!-- Navbar Start -->
  <header id="topnav" class="defaultscroll sticky navbar-white-bg">
    <div class="container d-block d-md-flex align-items-center justify-content-between">
      <!-- Logo container-->
      <a class="logo" href="index.html">
        <span class="logo-light-mode">
          <img src="/assets/admin/images/logo.png" class="l-main" alt />
        </span>
      </a>

      <!-- End Logo container-->
      <div class="menu-extras d-block d-md-none">
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

      <div id="navigation">
        <!-- Navigation Menu-->
        <ul class="navigation-menu">
          <li :class="{'active': this.$route.name.startsWith('home')}">
            <router-link to="/hosting" class="sub-menu-item">Dashboard</router-link>
          </li>
          <li :class="{'active': this.$route.name.startsWith('hosting.venues')}">
            <router-link :to="{name: 'hosting.venues'}" class="sub-menu-item">Venues</router-link>
          </li>
          <li :class="{'active': this.$route.name.startsWith('hosting.bookings')}">
            <router-link :to="{name: 'hosting.bookings'}" class="sub-menu-item">Bookings</router-link>
          </li>
        </ul>
        <!--end navigation menu-->
      </div>
      <!--end navigation-->

      <div class="m-0 p-0 position-relative d-flex">
        <!-- Language Selector-->
        <div class="p-0 d-none d-md-block me-2">
          <div
            class="border border-white d-flex align-items-center rounded-pill py-1 px-3 pointer"
            @click="$emit('showLanguageSelector')"
          >
            <i class="uil uil-english-to-chinese" id="language-icon"></i>
          </div>
        </div>

        <!-- Language Selector End-->

        <!--Logout button Start-->
        <ul v-if="user" class="p-0 m-0 d-none d-md-block">
          <div
            class="border border-white d-flex align-items-center rounded-pill p-2 pointer"
            @click="toggleUserMenu()"
          >
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path d="M3 7H21" stroke="#161c2d" stroke-width="1.5" stroke-linecap="round" />
              <path d="M3 12H21" stroke="#161c2d" stroke-width="1.5" stroke-linecap="round" />
              <path d="M3 17H21" stroke="#161c2d" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            <li class="list-inline-item mb-0 ms-1">
              <div class="user-icon">
                <svg
                  viewBox="0 0 32 32"
                  xmlns="http://www.w3.org/2000/svg"
                  style="display:block;height:100%;width:100%;fill:currentColor"
                  aria-hidden="true"
                  role="presentation"
                  focusable="false"
                >
                  <path
                    d="m16 .7c-8.437 0-15.3 6.863-15.3 15.3s6.863 15.3 15.3 15.3 15.3-6.863 15.3-15.3-6.863-15.3-15.3-15.3zm0 28c-4.021 0-7.605-1.884-9.933-4.81a12.425 12.425 0 0 1 6.451-4.4 6.507 6.507 0 0 1 -3.018-5.49c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5a6.513 6.513 0 0 1 -3.019 5.491 12.42 12.42 0 0 1 6.452 4.4c-2.328 2.925-5.912 4.809-9.933 4.809z"
                  />
                </svg>
              </div>
            </li>
          </div>
          <div class="bg-white shadow-lg position-absolute rounded-2 user-menu" v-if="showUserMenu">
            <ul class="mini-nav list-unstyled my-1 pointer">
              <router-link :to="{name: 'messages.index'}">
                <li class="px-4 py-1" style="color:#161c2d;">{{$t('messages')}}</li>
              </router-link>
              <router-link :to="{name: 'account.profile'}">
                <li class="px-4 py-1" style="color:#161c2d;">{{$t('profile')}}</li>
              </router-link>
              <router-link :to="{name: 'home'}">
                <li class="px-4 py-1" style="color:#161c2d;">{{$t('switch_to_travelling')}}</li>
              </router-link>
              <hr class="m-0" />
              <li class="px-4 py-1" @click="$emit('logout')">{{$t('logout')}}</li>
            </ul>
          </div>
        </ul>
        <!--Logout button End-->
      </div>
      <!--Login button Start-->
      <ul v-if="!user" class="buy-button list-inline mb-0">
        <li class="list-inline-item mb-0">
          <div>
            <router-link :to="{name: 'signup'}" class="btn mx-2" id="signup-btn">{{$t('signup')}}</router-link>
            <router-link
              :to="{name: 'login'}"
              class="btn btn-warning"
              id="login-btn"
            >{{$t('login')}}</router-link>
          </div>
        </li>
      </ul>
      <!--Login button End-->
    </div>
    <!--end container-->
  </header>
  <!--end header-->
  <!-- Navbar End -->
</template>

<script>
export default {
  data() {
    return {
      showUserMenu: false,
    };
  },
  computed: {
    user() {
      return this.$store.getters.user ? this.$store.getters.user.user : null;
    },
  },
  methods: {
    // remove user menu on click outside
    toggleUserMenu() {
      this.showUserMenu = !this.showUserMenu;
    },
  },
};
</script>

<style scoped>
.navbar-white-bg {
  box-shadow: 0 0 3px rgb(60 72 88 / 35%);
}
</style>
