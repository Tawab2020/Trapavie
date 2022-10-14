<template>
  <div class="back-to-home rounded d-none d-sm-block">
    <router-link :to="{name: 'home'}" class="btn btn-icon btn-primary">
      <i class="uil uil-home fs-15rem"></i>
    </router-link>
  </div>

  <!-- Hero Start -->
  <section class="cover-user bg-white">
    <div class="container-fluid px-0">
      <div class="row g-0 position-relative">
        <div class="col-lg-4 cover-my-30 order-2">
          <div class="text-center my-sm-5">
            <img src="/assets/admin/images/logo.png" height="72" alt />
          </div>

          <div class="cover-user-img align-items-center">
            <router-view></router-view>
          </div>
          <!-- end about detail -->
        </div>
        <!-- end col -->

        <div
          class="col-lg-8 offset-lg-4 padding-less img order-1"
          style="background-image:url('/assets/user/frontend/images/auth-bg.jpeg')"
          data-jarallax="{'speed': 0.5}"
        ></div>
        <!-- end col -->
      </div>
      <!--end row-->
    </div>
    <!--end container fluid-->
  </section>
  <!--end section-->
  <!-- Hero End -->
</template>

<script>
export default {};
</script>

<style>
.cover-user-img {
  height: 83.4vh !important;
}
@media (min-width: 1025px) {
  .cover-user .cover-user-img {
    padding: 0px 14%;
  }
}
</style>
