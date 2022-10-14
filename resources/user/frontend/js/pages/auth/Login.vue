<template>
  <div class="row">
    <div class="col-12">
      <div class="card login-page border-0" style="z-index: 1">
        <div class="card-body p-0">
          <h4 class="card-title text-center">Login</h4>
          <form @submit.prevent="login()" class="login-form mt-4" novalidate>
            <div class="row">
              <div class="col-lg-12">
                <div class="mb-3">
                  <label class="form-label">
                    Your Email
                    <span class="text-danger">*</span>
                  </label>
                  <div class="form-icon position-relative">
                    <i class="uil uil-envelope icons"></i>
                    <input
                      type="email"
                      v-model="user.email"
                      class="form-control ps-5"
                      placeholder="Email"
                      required
                      autofocus
                    />
                    <small
                      v-if="v$.user.email.$error"
                      class="text-danger"
                    >{{ v$.user.email.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <!--end col-->

              <div class="col-lg-12">
                <div class="mb-3">
                  <label class="form-label">
                    Password
                    <span class="text-danger">*</span>
                  </label>
                  <div class="form-icon position-relative">
                    <i class="uil uil-key-skeleton icons"></i>
                    <input
                      type="password"
                      v-model="user.password"
                      class="form-control ps-5"
                      placeholder="Password"
                      required
                    />
                    <small
                      v-if="v$.user.password.$error"
                      class="text-danger"
                    >{{ v$.user.password.$errors[0].$message }}</small>
                    <small v-if="loginFailed" class="text-danger">Email or password incorrect</small>
                  </div>
                </div>
              </div>
              <!--end col-->

              <div class="col-lg-12">
                <div class="d-flex justify-content-between">
                  <div class="mb-3">
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        value
                        id="flexCheckDefault"
                        v-model="user.remember_me"
                      />
                      <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                    </div>
                  </div>
                  <p class="forgot-pass mb-0">
                    <a
                      href="auth-cover-re-password.html"
                      class="text-dark fw-bold"
                    >Forgot password ?</a>
                  </p>
                </div>
              </div>
              <!--end col-->

              <div class="col-lg-12 mb-0">
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary" :disabled="busy">Sign in</button>
                </div>
              </div>
              <!--end col-->

              <div class="col-lg-12 mt-4 text-center">
                <h6>Or Continue With</h6>
                <div class="row">
                  <div class="col-6 mt-3">
                    <div class="d-grid">
                      <a href="javascript:void(0)" class="btn btn-light">
                        <i class="mdi mdi-facebook text-primary"></i> Facebook
                      </a>
                    </div>
                  </div>
                  <!--end col-->

                  <div class="col-6 mt-3">
                    <div class="d-grid">
                      <a @click="authWithGoogle()" class="btn btn-light">
                        <i class="mdi mdi-google text-danger"></i> Google
                      </a>
                    </div>
                  </div>
                  <!--end col-->
                </div>
              </div>
              <!--end col-->

              <div class="col-12 text-center">
                <p class="mb-0 mt-3">
                  <small class="text-dark me-2">Don't have an account ?</small>
                  <router-link :to="{name: 'signup'}" class="text-dark fw-bold">Sign Up</router-link>
                </p>
              </div>
              <!--end col-->
            </div>
            <!--end row-->
          </form>
        </div>
      </div>
    </div>
    <!--end col-->
  </div>
  <!--end row-->
</template>

<script>
import useValidate from "@vuelidate/core";
import { required, email, helpers } from "@vuelidate/validators";
export default {
  data() {
    return {
      v$: useValidate(),
      user: {},
      busy: false,
      loginFailed: false,
    };
  },
  validations() {
    return {
      user: {
        email: {
          required: helpers.withMessage("Provide an email.", required),
          email: helpers.withMessage("Invalid email", email),
        },
        password: {
          required: helpers.withMessage("Provide a password", required),
        },
      },
    };
  },
  methods: {
    authWithGoogle() {
      window.location.href = "/auth/redirect/google";
    },
    login() {
      this.loginFailed = false;
      this.v$.$validate();
      if (!this.v$.$error) {
        this.busy = true;
        this.$Progress.start();
        this.$store
          .dispatch("login", this.user)
          .then((result) => {
            this.$router.push({ name: "home" });
            this.busy = false;
            this.$Progress.finish();
          })
          .catch((err) => {
            this.loginFailed = true;
            this.busy = false;
            this.$Progress.fail();
            this.$toast.error("Login failed.", {
              position: "top-right",
            });
          });
      }
    },
  },
};
</script>

<style>
</style>
