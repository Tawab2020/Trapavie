<template>
  <div class="auth-fluid">
    <!--Auth fluid left content -->
    <div class="auth-fluid-form-box">
      <div class="align-items-center d-flex h-100">
        <div class="card-body">
          <!-- Logo -->
          <div class="auth-brand text-center text-lg-start">
            <span>
              <img src="/assets/admin/images/logo.png" alt height="72" />
            </span>
          </div>

          <!-- title-->
          <h4 class="mt-0">Sign In</h4>
          <p class="text-muted mb-4">Enter your username and password to access account.</p>

          <!-- form -->
          <form @submit.prevent="login()">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input
                class="form-control"
                type="text"
                id="username"
                v-model="admin.username"
                required
                placeholder="Enter your username"
              />
            </div>
            <div class="mb-3">
              <small class="text-muted float-end">
                Forgot your password? Contact Super
                Admin.
              </small>
              <label for="password" class="form-label">Password</label>
              <input
                class="form-control"
                type="password"
                id="password"
                v-model="admin.password"
                required
                placeholder="Enter your password"
              />
            </div>
            <div class="mb-3">
              <div class="form-check">
                <input
                  v-model="admin.remember_me"
                  type="checkbox"
                  class="form-check-input"
                  id="checkbox-signin"
                />
                <label class="form-check-label" for="checkbox-signin">Remember me</label>
              </div>
            </div>
            <div class="d-grid mb-0 text-center">
              <button class="btn btn-primary" type="submit">
                <i class="mdi mdi-login"></i> Log In
              </button>
            </div>
          </form>
          <!-- end form-->

          <!-- Footer-->
          <footer class="footer footer-alt">
            <p class="text-muted">TRIPAVIE - THE TRAVEL OF YOUR LIFE</p>
          </footer>
        </div>
        <!-- end .card-body -->
      </div>
      <!-- end .align-items-center.d-flex.h-100-->
    </div>
    <!-- end auth-fluid-form-box-->

    <!-- Auth fluid right content -->
    <div class="auth-fluid-right text-center">
      <div class="auth-user-testimonial">
        <h2 class="mb-3">Tripavie Admin CMS!</h2>
        <p class="lead">Manage all the website content using a GUI.</p>
        <p>TRIPAVIE</p>
      </div>
      <!-- end auth-user-testimonial-->
    </div>
    <!-- end Auth fluid right content -->
  </div>
  <!-- end auth-fluid-->
</template>

<script>
export default {
  data() {
    return {
      admin: {
        username: "",
        password: "",
      },
      errors: [],
    };
  },
  methods: {
    login() {
      this.$store
        .dispatch("login", this.admin)
        .then((result) => {
          this.$router.push({ name: "dashboard" });
        })
        .catch((err) => {
          console.error(err);
          this.$toast.error("Invalid username or passowrd", {
            position: "top-right",
          });
        });
    },
  },
};
</script>

<style></style>
