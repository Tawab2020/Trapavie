<template>
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Tripavie</a>
            </li>
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">Admin</a>
            </li>
            <li class="breadcrumb-item">
              <router-link :to="{name: 'faqCat.index'}" href="javascript: void(0);">FAQ Categories</router-link>
            </li>
            <li class="breadcrumb-item">
              <a href="javascript: void(0);">FAQ</a>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add Question</h4>
        <h4 v-else class="page-title">Update Question</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit()">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Question Details in {{ language.name }}</h5>
              <div class="row">
                <div
                  :class="{
                              'col-md-8': index == 0,
                              'col-md-12': index != 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'faq.question_' + language.code"
                      class="form-label"
                    >Question in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="faq.question[language.code]"
                      :required="index == 0"
                      :id="'faq.question_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <div v-if="index == 0" class="col-md-4">
                  <div class="mb-3">
                    <label for="faq.faq_cat_id" class="form-label">FAQ Category</label>
                    <select
                      class="form-control"
                      v-model="faq.faq_cat_id"
                      :required="index == 0"
                      id="faq.faq_cat_id"
                      placeholder="Type something ..."
                    >
                      <option value="-1" disabled>Please Select</option>
                      <optgroup
                        v-for="(parentCat, i) in parentCats"
                        :key="i"
                        :label="parentCat.title['en']"
                      >
                        <template v-for="(faqCat, index) in faqCats" :key="index">
                          <option
                            v-if="faqCat.parent_id == parentCat.id"
                            :value="faqCat.id"
                          >{{faqCat.title['en']}}</option>
                        </template>
                      </optgroup>
                    </select>
                  </div>
                </div>
              </div>
              <!-- end row -->
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label
                      :for="'faq.answer_' + language.code"
                      class="form-label"
                    >Answer in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="faq.answer[language.code]"
                      :required="index == 0"
                      :id="'faq.answer_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
              </div>
              <!-- end row -->
              <hr />
              <br />
            </template>

            <div class="text-end">
              <button :disabled="busy" type="submit" class="btn btn-success mt-2">
                <i class="mdi mdi-content-save"></i> Save
              </button>
            </div>
          </form>
        </div>
        <!-- end card body -->
      </div>
      <!-- end card -->
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editMode: false,
      faq: {
        question: {},
        answer: {},
        faq_cat_id: this.$route.params.faqCat,
      },
      errors: [],
      busy: false,
    };
  },
  methods: {
    handleSubmit() {
      this.busy = true;
      this.$Progress.start();
      if (this.editMode) {
        this.updateFaq();
      } else {
        this.createFaq();
      }
      this.busy = false;
      this.$Progress.finish();
    },
    createFaq() {
      axios
        .post("faq", this.faq)
        .then((response) => {
          this.$store.commit("ADD_FAQ_CAT", response.data);
          this.$router.push({
            name: "faqCat.show",
            params: { faqCat: this.faq.faq_cat_id },
          });
          this.$toast.success("FAQ was created successfully.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          console.log(error);
          this.busy = false;
          this.$Progress.fail();
          this.$toast.error("Couldn't create FAQ.", {
            position: "top-right",
          });
        });
    },
    updateFaq() {
      axios
        .put(`faq/${this.faq.id}`, this.faq)
        .then((response) => {
          this.$router.push({
            name: "faqCat.show",
            params: { faqCat: this.faq.faq_cat_id },
          });
          this.$toast.success("FAQ was updated successfully.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          console.log(error);
          this.busy = false;
          this.$Progress.fail();
          this.$toast.error("Couldn't update FAQ.", {
            position: "top-right",
          });
        });
    },
  },
  computed: {
    faqCats() {
      return this.$store.getters.faqCats;
    },
    parentCats() {
      return this.$store.getters.faqCats.filter((cat) => cat.parent_id == 0);
    },
    languages() {
      return this.$store.getters.languages;
    },
  },
  mounted() {
    if (this.$route.params.faq) {
      this.editMode = true;
      axios
        .get(`faq/${this.$route.params.faq}`)
        .then(({ data }) => {
          this.faq = data;
        })
        .catch((error) => {
          reject(error);
        });
    }
  },
};
</script>

<style>
</style>
