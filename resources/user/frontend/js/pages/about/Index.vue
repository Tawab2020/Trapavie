<template>
  <!-- Hero Start -->
  <section
    class="bg-half-170 d-table w-100"
    :style="`background: url('${$FilePath}/about/${about.bg_image}');`"
  >
    <div class="bg-overlay"></div>
    <div class="container">
      <div class="row mt-5 justify-content-center">
        <div class="col-lg-12 text-center">
          <div class="pages-heading title-heading">
            <h2 class="text-white title-dark">{{about.main_title[lang]}}</h2>
            <p class="text-white-50 para-desc mb-0 mx-auto">{{about.excerpt[lang]}}</p>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->

      <div class="position-breadcrumb">
        <nav aria-label="breadcrumb" class="d-inline-block">
          <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
            <li class="breadcrumb-item">
              <router-link to="/">Tripavie</router-link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{about.main_title[lang]}}</li>
          </ul>
        </nav>
      </div>
    </div>
    <!--end container-->
  </section>
  <!--end section-->

  <div class="position-relative">
    <div class="shape overflow-hidden text-white">
      <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor" />
      </svg>
    </div>
  </div>
  <!-- Hero End -->

  <!-- About Start -->
  <section class="section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-5 col-md-5 mt-4 pt-2 mt-sm-0 pt-sm-0">
          <div class="position-relative">
            <img
              :src="`${$FilePath}/about/${about.image}`"
              class="rounded img-fluid mx-auto d-block"
              alt
            />
          </div>
        </div>
        <!--end col-->

        <div class="col-lg-7 col-md-7 mt-4 pt-2 mt-sm-0 pt-sm-0">
          <div class="section-title ms-lg-4">
            <h4 class="title mb-4">{{about.secondary_title[$i18n.locale]}}</h4>
            <p class="text-muted">{{about.description[lang]}}</p>
            <router-link :to="about.btn_url" class="btn btn-primary mt-3">
              {{about.btn_text[lang]}}
              <i class="uil uil-angle-right-b"></i>
            </router-link>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->
    </div>
    <!--end container-->
  </section>
  <!--end section-->
  <!-- About End -->

  <!-- Team Start -->
  <section class="section bg-light">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <div class="section-title mb-4 pb-2">
            <h4 class="title mb-4">{{about.team_title[lang]}}</h4>
            <p class="text-muted para-desc mx-auto mb-0">{{about.team_excerpt[lang]}}</p>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->

      <div class="row">
        <div v-for="(teamMember, index) in teamMembers" :key="index" class="col-md-12 g-5">
          <div class="card bg-white border-0">
            <div class="card-body p-0 row g-0" :class="index % 2 == 0 ? 'row-reverse' : ''">
              <div class="position-relative col-md-5">
                <img :src="`${$FilePath}/team/${teamMember.image}`" class="team-img rounded" alt />
              </div>
              <div class="content p-5 col-md-7">
                <h4 class="mb-0">
                  <span class="name text-dark">{{teamMember.name[lang]}}</span>
                </h4>
                <small class="designation text-muted">{{teamMember.title[lang]}}</small>
                <br />
                <div class="designation text-muted mt-2">{{teamMember.bio[lang]}}</div>
              </div>
            </div>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->
    </div>
    <!--end container-->
  </section>
  <!--end section-->
  <!-- Team End -->

  <!-- FAQ Start -->
  <section class="section">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <div class="section-title mb-4 pb-2">
            <h4 class="title mb-4">FAQ</h4>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->
      <div class="row">
        <div class="col-lg-4 col-md-5 col-12 d-none d-md-block">
          <div class="sticky-bar">
            <template v-for="(parentCat, index) in parentCats" :key="index">
              <h4 :class="{'pt-5': index != 0}">{{parentCat.title[lang]}}</h4>

              <div class="rounded-md shadow p-4">
                <ul class="list-unstyled sidebar-nav mb-0 py-2" id="navmenu-nav">
                  <li
                    v-for="(faqCat, index) in parentCat.children"
                    :key="index"
                    class="mb-3 navbar-item"
                  >
                    <a
                      href="javascript:void(0);"
                      @click="scrollToElement(`#title_${faqCat.id}`)"
                      class="mouse-down h6 text-dark"
                    >{{faqCat.title[lang]}}</a>
                  </li>
                </ul>
              </div>
            </template>
          </div>
        </div>
        <!--end col-->

        <div class="col-lg-8 col-md-7 col-12" id="faqCats">
          <template v-for="(parentCat, i) in parentCats" :key="i">
            <template v-for="(faqCat, index) in parentCat.children" :key="index">
              <div
                :class="{'section-title mt-5': index > 0 || i > 0, 'section-title': index == 0 && i == 0 }"
                :id="`title_${faqCat.id}`"
              >
                <h5>{{faqCat.title[lang]}}</h5>
              </div>

              <div class="accordion mt-4 pt-1" :id="`faq_${faqCat.id}`">
                <div
                  v-for="(faq, index) in faqCat.faqs"
                  :key="index"
                  class="accordion-item rounded shadow bg-white mt-2"
                >
                  <h2 class="accordion-header" :id="`heading${faq.id}`">
                    <button
                      class="accordion-button border-0 bg-light collapsed"
                      type="button"
                      data-bs-toggle="collapse"
                      :data-bs-target="`#collapse_${faq.id}`"
                      aria-expanded="false"
                      :aria-controls="`collapse_${faq.id}`"
                    >{{faq.question[lang]}}</button>
                  </h2>
                  <div
                    :id="`collapse_${faq.id}`"
                    class="accordion-collapse border-0 collapse"
                    :aria-labelledby="`heading${faq.id}`"
                    :data-bs-parent="`#faq_${faqCat.id}`"
                  >
                    <div class="accordion-body text-muted">{{faq.answer[lang]}}</div>
                  </div>
                </div>
              </div>
            </template>
          </template>
        </div>
        <!--end col-->
      </div>
      <!--end row-->
    </div>
    <!--end container-->
  </section>
  <!--end section-->
  <!-- FAQ End -->
</template>

<script>
export default {
  data() {
    return {
      faqCats: [],
      teamMembers: [],
    };
  },
  computed: {
    about() {
      if (this.$store.getters.about.btn_url) {
        return this.$store.getters.about;
      }
      return {
        main_title: {},
        secondary_title: {},
        excerpt: {},
        description: {},
        btn_text: {},
        btn_url: "",
        team_title: {},
        team_excerpt: {},
      };
    },
    parentCats() {
      return this.faqCats.filter((cat) => cat.parent_id == 0);
    },
    lang() {
      return this.$store.getters.activeLocale;
    },
  },

  methods: {
    scrollToElement(id) {
      let el = document.querySelector(id);
      // scroll with offset
      let y = el.getBoundingClientRect().top + window.pageYOffset - 100;
      window.scrollTo({ top: y, behavior: "smooth" });
    },
  },
  mounted() {
    axios
      .get("about")
      .then(({ data }) => {
        this.teamMembers = data.teamMembers;
        this.faqCats = data.faqCats;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style>
.team-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.row-reverse {
  flex-direction: row-reverse;
}
</style>
