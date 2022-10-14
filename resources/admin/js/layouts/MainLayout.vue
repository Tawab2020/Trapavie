<template>
  <!-- Topbar Start -->
  <div class="navbar-custom topnav-navbar topnav-navbar-dark">
    <div class="container-fluid">
      <!-- LOGO -->
      <router-link :to="{name: 'dashboard'}" class="topnav-logo">
        <span class="topnav-logo-lg">
          <img src="/assets/admin/images/logo.png" alt />
        </span>
      </router-link>

      <ul class="list-unstyled topbar-menu float-end mb-0">
        <li class="dropdown notification-list">
          <a
            class="nav-link dropdown-toggle arrow-none"
            data-bs-toggle="dropdown"
            href="#"
            id="topbar-notifydrop"
            role="button"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="dripicons-bell noti-icon"></i>
            <span class="noti-icon-badge"></span>
          </a>
          <div
            class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg"
            aria-labelledby="topbar-notifydrop"
          >
            <!-- item-->
            <div class="dropdown-item noti-title">
              <h5 class="m-0">
                <span class="float-end">
                  <a href="javascript: void(0);" class="text-dark">
                    <small>Clear All</small>
                  </a>
                </span>Notification
              </h5>
            </div>

            <div style="max-height: 230px" data-simplebar>
              <!-- item-->
              <a href="javascript:void(0);" class="dropdown-item notify-item">
                <div class="notify-icon bg-primary">
                  <i class="mdi mdi-comment-account-outline"></i>
                </div>
                <p class="notify-details">
                  Caleb Flakelar commented on Admin
                  <small class="text-muted">1 min ago</small>
                </p>
              </a>

              <!-- item-->
              <a href="javascript:void(0);" class="dropdown-item notify-item">
                <div class="notify-icon bg-info">
                  <i class="mdi mdi-account-plus"></i>
                </div>
                <p class="notify-details">
                  New user registered.
                  <small class="text-muted">5 hours ago</small>
                </p>
              </a>

              <!-- item-->
              <a href="javascript:void(0);" class="dropdown-item notify-item">
                <div class="notify-icon">
                  <img
                    src="/assets/admin/images/users/avatar-2.jpg"
                    class="img-fluid rounded-circle"
                    alt
                  />
                </div>
                <p class="notify-details">Cristina Pride</p>
                <p class="text-muted mb-0 user-msg">
                  <small>
                    Hi, How are you? What about our next
                    meeting
                  </small>
                </p>
              </a>

              <!-- item-->
              <a href="javascript:void(0);" class="dropdown-item notify-item">
                <div class="notify-icon bg-primary">
                  <i class="mdi mdi-comment-account-outline"></i>
                </div>
                <p class="notify-details">
                  Caleb Flakelar commented on Admin
                  <small class="text-muted">4 days ago</small>
                </p>
              </a>

              <!-- item-->
              <a href="javascript:void(0);" class="dropdown-item notify-item">
                <div class="notify-icon">
                  <img
                    src="/assets/admin/images/users/avatar-4.jpg"
                    class="img-fluid rounded-circle"
                    alt
                  />
                </div>
                <p class="notify-details">Karen Robinson</p>
                <p class="text-muted mb-0 user-msg">
                  <small>
                    Wow ! this admin looks good and awesome
                    design
                  </small>
                </p>
              </a>

              <!-- item-->
              <a href="javascript:void(0);" class="dropdown-item notify-item">
                <div class="notify-icon bg-info">
                  <i class="mdi mdi-heart"></i>
                </div>
                <p class="notify-details">
                  Carlos Crouch liked
                  <b>Admin</b>
                  <small class="text-muted">13 days ago</small>
                </p>
              </a>
            </div>

            <!-- All-->
            <a
              href="javascript:void(0);"
              class="dropdown-item text-center text-primary notify-item notify-all"
            >View All</a>
          </div>
        </li>

        <li class="dropdown notification-list">
          <a
            class="nav-link dropdown-toggle nav-user arrow-none me-0"
            data-bs-toggle="dropdown"
            id="topbar-userdrop"
            href="#"
            role="button"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <span class="account-user-avatar">
              <img
                :src="`${$FilePath}/admin/images/${admin.id}/${admin.image}`"
                alt="user-image"
                class="rounded-circle"
              />
            </span>
            <span>
              <span class="account-user-name">{{admin.name}}</span>
              <span class="account-position">{{admin.username}}</span>
            </span>
          </a>
          <div
            class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
            aria-labelledby="topbar-userdrop"
          >
            <!-- item-->
            <div class="dropdown-header noti-title">
              <h6 class="text-overflow m-0">Controls</h6>
            </div>

            <!-- item-->
            <router-link :to="{ name: 'profile.update' }" class="dropdown-item notify-item">
              <i class="mdi mdi-account-circle me-1"></i>
              <span>My Profile</span>
            </router-link>

            <!-- item-->
            <a @click="logout()" href="#" class="dropdown-item notify-item">
              <i class="mdi mdi-logout me-1"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
      <a class="button-menu-mobile disable-btn">
        <div class="lines">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </a>
    </div>
  </div>
  <!-- end Topbar -->

  <!-- Start Content-->
  <div class="container-fluid">
    <!-- Begin page -->
    <div class="wrapper">
      <!-- ========== Left Sidebar Start ========== -->
      <div class="leftside-menu leftside-menu-detached">
        <div class="leftbar-user">
          <a href="javascript: void(0);">
            <img
              :src="`${$FilePath}/admin/images/${admin.id}/${admin.image}`"
              alt="user-image"
              height="42"
              class="rounded-circle shadow-sm"
            />
            <span class="leftbar-user-name mx-0">{{ admin.name }}</span>
          </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">
          <li class="side-nav-title side-nav-item">General</li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('dashboard'), 'side-nav-item' : !this.$route.name.startsWith('dashboard')}"
          >
            <router-link
              :to="{ name: 'dashboard' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-home"></i>
              <span>Dashboard</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('currency'), 'side-nav-item' : !this.$route.name.startsWith('currency')}"
          >
            <router-link
              :to="{ name: 'currency.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="mdi mdi-currency-usd-circle-outline"></i>
              <span>Currencies</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('language'), 'side-nav-item' : !this.$route.name.startsWith('language')}"
          >
            <router-link
              :to="{ name: 'language.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-web"></i>
              <span>Languages</span>
            </router-link>
          </li>

          <li class="side-nav-title side-nav-item mt-3">About Page</li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('user'), 'side-nav-item' : !this.$route.name.startsWith('user')}"
          >
            <router-link
              :to="{ name: 'user.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-user"></i>
              <span>Users</span>
            </router-link>
          </li>
          <li class="side-nav-title side-nav-item mt-3">About Page</li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('about'), 'side-nav-item' : !this.$route.name.startsWith('about')}"
          >
            <router-link
              :to="{ name: 'about.update' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-information"></i>
              <span>About Page</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('faq'), 'side-nav-item' : !this.$route.name.startsWith('faq')}"
          >
            <router-link
              :to="{ name: 'faqCat.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-question"></i>
              <span>FAQ</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('team'), 'side-nav-item' : !this.$route.name.startsWith('team')}"
          >
            <router-link
              :to="{ name: 'team.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-user-group"></i>
              <span>Team Members</span>
            </router-link>
          </li>
          <li class="side-nav-title side-nav-item mt-3">Venues</li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('venue.index'), 'side-nav-item' : !this.$route.name.startsWith('venue.index')}"
          >
            <router-link
              :to="{ name: 'venue.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-list"></i>
              <span>All Venues</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('venue.pending'), 'side-nav-item' : !this.$route.name.startsWith('venue.pending')}"
          >
            <router-link
              :to="{ name: 'venue.pending' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-list"></i>
              <span>Pending Venues</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('venue.live'), 'side-nav-item' : !this.$route.name.startsWith('venue.live')}"
          >
            <router-link
              :to="{ name: 'venue.live' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-list"></i>
              <span>Live Venues</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('venue.rejected'), 'side-nav-item' : !this.$route.name.startsWith('venue.rejected')}"
          >
            <router-link
              :to="{ name: 'venue.rejected' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-list"></i>
              <span>Rejected Venues</span>
            </router-link>
          </li>

          <li class="side-nav-title side-nav-item mt-3">Venue Data</li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('venueData'), 'side-nav-item' : !this.$route.name.startsWith('venueData')}"
          >
            <router-link
              :to="{ name: 'venueData.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-list"></i>
              <span>Venue Data Tags</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('propertyType'), 'side-nav-item' : !this.$route.name.startsWith('propertyType')}"
          >
            <router-link
              :to="{ name: 'propertyTypes.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-store"></i>
              <span>Property Types</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('accommodationType'), 'side-nav-item' : !this.$route.name.startsWith('accommodationType')}"
          >
            <router-link
              :to="{ name: 'accommodationTypes.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="mdi mdi-folder-home-outline"></i>
              <span>Accommodation Types</span>
            </router-link>
          </li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('cancellatioPolicyData'), 'side-nav-item' : !this.$route.name.startsWith('cancellatioPolicyData')}"
          >
            <router-link
              :to="{ name: 'cancellationPolicyData.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-wrong"></i>
              <span>Cancellation Policies</span>
            </router-link>
          </li>


          <li class="side-nav-title side-nav-item mt-3">Retreat Data</li>
          <li
            class="side-nav-item"
            :class="{'menuitem-active' : this.$route.name.startsWith('retreats')}"
          >
            <router-link
              :to="{ name: 'retreats.index' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-information"></i>
              <span>Retreat Data Tags</span>
            </router-link>
          </li>

          <li class="side-nav-title side-nav-item mt-3">Payment and Fees</li>
          <li
            :class="{'side-nav-item menuitem-active' : this.$route.name.startsWith('fee'), 'side-nav-item' : !this.$route.name.startsWith('fee')}"
          >
            <router-link
              :to="{ name: 'fee.update' }"
              class="side-nav-link"
              tag="li"
              exact-active-class="menuitem-active"
            >
              <i class="dripicons-information"></i>
              <span>Transaction Fees</span>
            </router-link>
          </li>
        </ul>

        <!-- End Sidebar -->

        <div class="clearfix"></div>
        <!-- Sidebar -left -->
      </div>
      <!-- Left Sidebar End -->

      <div class="content-page">
        <div class="content">
          <router-view></router-view>
        </div>
        <!-- End Content -->

        <!-- Footer Start -->
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">{{year}} © Triapavie.com</div>
            </div>
          </div>
        </footer>
        <!-- end Footer -->
      </div>
      <!-- content-page -->
    </div>
    <!-- end wrapper-->
  </div>
  <!-- END Container -->
</template>

<script>
export default {
  computed: {
    admin() {
      return this.$store.getters.admin ? this.$store.getters.admin.user : null;
    },
    year() {
      return new Date().getFullYear();
    },
  },
  methods: {
    logout() {
      axios
        .get(`admin/auth/logout`)
        .then((result) => {
          this.$store.commit("LOGOUT");
          this.$router.push({ name: "admin.login" });
        })
        .catch((err) => {});
    },
  },
  created() {
    this.$Progress.start();
    this.$router.beforeEach((to, from, next) => {
      if (to.meta.progress !== undefined) {
        let meta = to.meta.progress;
        this.$Progress.parseMeta(meta);
      }
      this.$Progress.start();
      next();
    });
    this.$router.afterEach((to, from) => {
      this.$Progress.finish();
    });
  },
  mounted() {
    this.$Progress.finish();
    this.$store.dispatch("initialize");
  },
};
</script>

<style></style>
