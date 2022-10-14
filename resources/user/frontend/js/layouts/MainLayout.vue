<template>
  <!-- <hosting-header
    v-if="$route.path.includes('/hosting')"
    @logout="logout()"
    @showLanguageSelector="showLanguageSelector()"
  ></hosting-header>-->
  <main-header @logout="logout()" @showLanguageSelector="showLanguageSelector()"></main-header>
  <div class="main-content">
    <router-view></router-view>
  </div>
  <vue-progress-bar></vue-progress-bar>

  <!-- Footer Start -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="footer-py-60">
            <div class="row">
              <div class="col-lg-4 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                <a href="#" class="logo-footer">
                  <img src="/assets/admin/images/logo.png" height="72" alt />
                </a>
                <p class="mt-4">The trip of your life.</p>
                <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-4">
                  <li class="list-inline-item me-1">
                    <a href="javascript:void(0)" class="rounded">
                      <i class="uil uil-facebook-f"></i>
                    </a>
                  </li>
                  <li class="list-inline-item mx-1">
                    <a href="javascript:void(0)" class="rounded">
                      <i class="uil uil-instagram"></i>
                    </a>
                  </li>
                  <li class="list-inline-item mx-1">
                    <a href="javascript:void(0)" class="rounded">
                      <i class="uil uil-twitter"></i>
                    </a>
                  </li>
                </ul>
                <!--end icon-->
              </div>
              <!--end col-->

              <div class="col-lg-2 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h5 class="footer-head">Venues</h5>
                <ul class="list-unstyled footer-list mt-4">
                  <li>
                    <a href="javascript:void(0)" class="text-foot">
                      <i class="uil uil-angle-right-b me-1"></i>
                      Listings
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)" class="text-foot">
                      <i class="uil uil-angle-right-b me-1"></i>
                      Bookings
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)" class="text-foot">
                      <i class="uil uil-angle-right-b me-1"></i>
                      Pricing
                    </a>
                  </li>
                </ul>
              </div>
              <!--end col-->

              <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h5 class="footer-head">Retreats</h5>
                <ul class="list-unstyled footer-list mt-4">
                  <li>
                    <a href="javascript:void(0)" class="text-foot">
                      <i class="uil uil-angle-right-b me-1"></i>
                      Book a retreat
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)" class="text-foot">
                      <i class="uil uil-angle-right-b me-1"></i>
                      Listings
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)" class="text-foot">
                      <i class="uil uil-angle-right-b me-1"></i>
                      Blog
                    </a>
                  </li>
                </ul>
              </div>
              <!--end col-->

              <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h5 class="footer-head">Newsletter</h5>
                <p class="mt-4">We might do this later.</p>
                <form>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="foot-subscribe mb-3">
                        <label class="form-label">
                          Write your email
                          <span class="text-danger">*</span>
                        </label>
                        <div class="form-icon position-relative">
                          <i data-feather="mail" class="fea icon-sm icons"></i>
                          <input
                            type="email"
                            name="email"
                            id="emailsubscribe"
                            class="form-control ps-5 rounded"
                            placeholder="Your email : "
                            required
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="d-grid">
                        <input
                          type="submit"
                          id="submitsubscribe"
                          name="send"
                          class="btn btn-soft-primary"
                          value="Subscribe"
                        />
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!--end col-->
            </div>
            <!--end row-->
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->
    </div>
    <!--end container-->

    <div class="footer-py-30 footer-bar">
      <div class="container text-center">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <div class="text-sm-start">
              <p class="mb-0">© 2022 TRIPAVIE.</p>
            </div>
          </div>
          <!--end col-->

          <div class="col-sm-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
            <ul class="list-unstyled text-sm-end mb-0">
              <li class="list-inline-item">
                <a href="javascript:void(0)">
                  <img
                    src="/assets/user/frontend/images/payments/american-ex.png"
                    class="avatar avatar-ex-sm"
                    title="American Express"
                    alt
                  />
                </a>
              </li>
              <li class="list-inline-item">
                <a href="javascript:void(0)">
                  <img
                    src="/assets/user/frontend/images/payments/discover.png"
                    class="avatar avatar-ex-sm"
                    title="Discover"
                    alt
                  />
                </a>
              </li>
              <li class="list-inline-item">
                <a href="javascript:void(0)">
                  <img
                    src="/assets/user/frontend/images/payments/master-card.png"
                    class="avatar avatar-ex-sm"
                    title="Master Card"
                    alt
                  />
                </a>
              </li>
              <li class="list-inline-item">
                <a href="javascript:void(0)">
                  <img
                    src="/assets/user/frontend/images/payments/paypal.png"
                    class="avatar avatar-ex-sm"
                    title="Paypal"
                    alt
                  />
                </a>
              </li>
              <li class="list-inline-item">
                <a href="javascript:void(0)">
                  <img
                    src="/assets/user/frontend/images/payments/visa.png"
                    class="avatar avatar-ex-sm"
                    title="Visa"
                    alt
                  />
                </a>
              </li>
            </ul>
          </div>
          <!--end col-->
        </div>
        <!--end row-->
      </div>
      <!--end container-->
    </div>
  </footer>
  <!--end footer-->
  <!-- Footer End -->

  <!-- Back to top -->
  <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5">
    <i data-feather="arrow-up" class="fea icon-sm icons align-middle"></i>
  </a>
  <!-- Back to top -->

  <!-- Language Selector Modal Start -->
  <div
    class="modal fade"
    ref="languageSelectorModal"
    id="language-selector-modal"
    tabindex="-1"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content rounded shadow border-0">
        <button
          type="button"
          class="btn-close float-end m-2"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
        <div class="modal-body py-4">
          <h6 class="mb-1">{{$t('choose_language')}}</h6>
          <div class="text-center">
            <ul
              class="list-unstyled d-flex flex-wrap justify-content-start m-0"
              id="languages-list"
            >
              <li
                v-for="language in languages"
                :key="language.id"
                :class="activeLocale == language.code ? 'border rounded-pill py-2 px-3 me-2 mt-2 d-flex align-items-center active' : 'border rounded-pill py-2 px-3 me-2 mt-2 d-flex align-items-center'"
                @click="handleLocaleChange(language.code)"
              >
                <img
                  :src="`${$FilePath}/languages/${language.icon}`"
                  class="me-1"
                  :alt="language.name + 'flag'"
                  width="20"
                />
                <span>{{language.name}}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Language Selector Modal End -->
</template>

<script>
import MainHeader from "../components/headers/MainHeader.vue";
import HostingHeader from "../components/headers/HostingHeader.vue";
export default {
  components: {
    MainHeader,
    HostingHeader,
  },
  data() {
    return {
      showUserMenu: false,
    };
  },
  methods: {
    logout() {
      this.$store.commit("LOGOUT");
      this.$router.push({ name: "home" });
    },
    // remove user menu on click outside
    toggleUserMenu() {
      this.showUserMenu = !this.showUserMenu;
    },
    showLanguageSelector() {
      this.languageSelectorModal.show();
    },
    handleLocaleChange(lang) {
      this.changeLocale(lang);
      this.languageSelectorModal.hide();
    },
    changeLocale(lang) {
      axios.get(`loadTranslations/${lang}`).then(({ data }) => {
        this.$i18n.locale = lang;
        this.$i18n.setLocaleMessage(lang, data);
        this.$store.commit("UPDATE_ACTIVE_LOCALE", lang);
      });
    },
  },
  computed: {
    user() {
      return this.$store.getters.user ? this.$store.getters.user.user : null;
    },
    google_id() {
      return this.$route.query.googleAuth;
    },
    translations() {
      return this.$store.getters.translations;
    },
    languageSelectorModal() {
      return new bootstrap.Modal(this.$refs.languageSelectorModal);
    },
    languages() {
      let languages = this.$store.getters.languages;

      return languages;
    },
    activeLocale() {
      return this.$store.getters.activeLocale;
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
  watch: {
    user: function () {
      //this is to get user data after login
      this.$store.dispatch("initialize");
    },
    languages: function (languages) {
      // if language is not available, switch to en. admin may have deactivaed a language
      if (
        this.activeLocale &&
        !languages.some((language) => language.code == this.activeLocale)
      ) {
        this.changeLocale("en");
      }
    },
  },
  mounted() {
    this.$Progress.finish();
    if (this.google_id) {
      axios
        .post("auth/loginWithGoogleId", { google_id: this.google_id })
        .then((response) => {
          this.$store.commit("LOGIN", response.data);
          this.$router.replace("/");
        })
        .catch((error) => {
          this.$router.replace("/");
          console.log(error);
        });
    }
  },
  beforeCreate() {
    this.$store.dispatch("initialize");
  },
  created() {
    // Change luxon DateTime js locale on app locale change
    this.unwatch = this.$store.watch(
      (getters) => getters.activeLocale,
      (newValue) => {
        this.$DateTimeSettings.defaultLocale = newValue;
        this.$store.commit("UPDATE_DATETIME_KEY");
      }
    );
  },
  beforeDestroy() {
    this.unwatch();
  },
};
</script>

<style>
.user-icon {
  height: 20px;
  width: 20px;
}

.pointer {
  cursor: pointer;
}

.user-menu {
  width: 200px;
  top: 50px;
  right: 0;
}
#language-icon {
  font-size: 1.3rem;
}
ul#languages-list li {
  cursor: pointer;
}
ul#languages-list li:hover {
  background-color: rgb(247, 247, 247) !important;
}
ul#languages-list li.active {
  border-color: #0000008c !important;
}
ul.mini-nav li:hover {
  background-color: rgb(247, 247, 247) !important;
}
div.main-content {
  min-height: calc(100vh - 432px);
}
</style>
