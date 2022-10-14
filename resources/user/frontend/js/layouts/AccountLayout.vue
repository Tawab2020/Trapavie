<template>
  <!-- Profile Start -->
  <section class="section mt-60">
    <div class="container mt-lg-3">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-12 d-lg-block d-none">
          <div class="sidebar sticky-bar p-4 rounded shadow">
            <div class="widget mt-4">
              <h5 class="widget-title">Account</h5>
              <ul class="list-unstyled sidebar-nav mb-0" id="navmenu-nav">
                <li
                  :class="{'navbar-item account-menu px-0 active' : this.$route.name == 'account.profile', 'navbar-item account-menu px-0': this.$route.name != 'account.profile'}"
                >
                  <router-link
                    :to="{name: 'account.profile'}"
                    class="navbar-link d-flex rounded shadow align-items-center py-2 px-4"
                  >
                    <span class="h4 mb-0">
                      <i class="uil uil-user-circle"></i>
                    </span>
                    <h6 class="mb-0 ms-2">Profile</h6>
                  </router-link>
                </li>
                <li class="navbar-item account-menu px-0">
                  <router-link
                    :to="{name: 'account.profile'}"
                    class="navbar-link d-flex rounded shadow align-items-center py-2 px-4"
                  >
                    <span class="h4 mb-0">
                      <i class="uil uil-setting"></i>
                    </span>
                    <h6 class="mb-0 ms-2">Settings</h6>
                  </router-link>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!--end col-->

        <div class="col-lg-8 col-12">
          <router-view></router-view>
        </div>
        <!--end col-->
      </div>
      <!--end row-->
    </div>
    <!--end container-->
  </section>
  <!--end section-->
  <!-- Profile Setting End -->
</template>

<script>
export default {
  computed: {
    user() {
      return this.$store.getters.user.user;
    },
  },
};
</script>

<style>
</style>
