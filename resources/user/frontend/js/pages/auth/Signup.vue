<template>
  <div class="row">
    <div class="col-12">
      <div class="card border-0" style="z-index: 1">
        <div class="card-body p-0">
          <h4 class="card-title text-center">Signup</h4>
          <form @submit.prevent="register" class="login-form mt-4" novalidate>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">
                    First name
                    <span class="text-danger">*</span>
                  </label>
                  <div class="form-icon position-relative">
                    <input
                      type="text"
                      v-model="user.fname"
                      class="form-control ps-3"
                      placeholder="First Name"
                      autofocus
                    />
                    <small
                      v-if="v$.user.fname.$error"
                      class="text-danger"
                    >{{ v$.user.fname.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <!--end col-->

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">
                    Last name
                    <span class="text-danger">*</span>
                  </label>
                  <div class="form-icon position-relative">
                    <input
                      type="text"
                      v-model="user.lname"
                      class="form-control ps-3"
                      placeholder="Last Name"
                    />
                    <small
                      v-if="v$.user.lname.$error"
                      class="text-danger"
                    >{{ v$.user.lname.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <!--end col-->

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label">
                    Your Email
                    <span class="text-danger">*</span>
                  </label>
                  <div class="form-icon position-relative">
                    <input
                      type="email"
                      v-model="user.email"
                      class="form-control ps-3"
                      placeholder="Email"
                    />
                    <small
                      v-if="v$.user.email.$error"
                      class="text-danger"
                    >{{ v$.user.email.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <!--end col-->

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label">
                    Password
                    <span class="text-danger">*</span>
                  </label>
                  <div class="form-icon position-relative">
                    <input
                      type="password"
                      v-model="user.password"
                      class="form-control ps-3"
                      placeholder="Password"
                    />
                    <small
                      v-if="v$.user.password.$error"
                      class="text-danger"
                    >{{ v$.user.password.$errors[0].$message }}</small>
                  </div>
                </div>
              </div>
              <!--end col-->

              <div class="col-md-12">
                <div class="mb-3">
                  <div class="form-check">
                    <input
                      type="checkbox"
                      v-model="agrees"
                      class="form-check-input"
                      value
                      id="flexCheckDefault"
                    />
                    <label class="form-check-label" for="flexCheckDefault">
                      I Accept
                      <a href="#" class="text-primary">Terms And Condition</a>
                    </label>
                  </div>
                </div>
              </div>
              <!--end col-->

              <div class="col-md-12">
                <div class="d-grid">
                  <button type="submit" :disabled="busy" class="btn btn-primary">Register</button>
                </div>
              </div>
              <!--end col-->

              <div class="col-lg-12 mt-4 text-center">
                <h6>Or Continue With</h6>
                <div class="row">
                  <div class="col-6 mt-3">
                    <div class="d-grid">
                      <a href="#" class="btn btn-light" disabled>
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
                  <router-link :to="{name: 'login'}" class="text-dark fw-bold">Log In</router-link>
                </p>
              </div>
            </div>
            <!--end row-->
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--end row-->
</template>

<script>
import useValidate from "@vuelidate/core";
import {
  required,
  sameAs,
  email,
  minLength,
  helpers,
} from "@vuelidate/validators";
export default {
  name: "Signup",
  data() {
    return {
      v$: useValidate(),
      user: {},
      busy: false,
      agrees: false,
    };
  },
  validations() {
    return {
      user: {
        fname: {
          required: helpers.withMessage("Provide your first name", required),
        },
        lname: {
          required: helpers.withMessage("Provide your last name", required),
        },
        email: {
          required: helpers.withMessage("Provide your email", required),
          email: helpers.withMessage("Provide a valid email", email),
        },
        password: {
          required: helpers.withMessage("Provide a password", required),
          minLengthValue: helpers.withMessage(
            "Password too short",
            minLength(8)
          ),
        },
      },
    };
  },
  methods: {
    register() {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.busy = true;
        this.$Progress.start();
        axios
          .post("auth/register", this.user)
          .then(({ data }) => {
            this.$store.commit("LOGIN", data);
            this.$router.push({ name: "home" });
            console.log("logged in");
            this.$Progress.finish();
            this.busy = false;
          })
          .catch((error) => {
            console.log(error);
            this.$Progress.fail();
            this.busy = false;
          });
      }
    },
    authWithGoogle() {
      window.location.href = "/auth/redirect/google";
    },
    registerWithFacebook() {
      window.location.href = "/auth/redirect/facebook";
    },
  },
};
</script>

<style>
</style>
