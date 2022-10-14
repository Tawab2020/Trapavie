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
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add FAQ Category</h4>
        <h4 v-else class="page-title">Update FAQ Category</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit()" enctype="multipart/form-data">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">FAQ Category Details in {{ language.name }}</h5>
              <div class="row">
                <div
                  :class="{
                              'col-md-6': index == 0,
                              'col-md-12': index != 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'faqCat.title_' + language.code"
                      class="form-label"
                    >Title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="faqCat.title[language.code]"
                      :required="index == 0"
                      :id="'faqCat.title_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <div v-if="index == 0 && !hasChildren" class="col-md-6">
                  <div class="mb-3">
                    <label for="faqCat.parent_id" class="form-label">Parent Category</label>
                    <select
                      class="form-control"
                      v-model="faqCat.parent_id"
                      :required="index == 0"
                      id="faqCat.parent_id"
                      placeholder="Type something ..."
                    >
                      <option value="0">No Parent</option>
                      <option
                        v-for="(parentCat, index) in parentCats"
                        :key="index"
                        :value="parentCat.id"
                      >{{parentCat.title['en']}}</option>
                    </select>
                  </div>
                </div>
                <div v-if="index == 0 && hasChildren" class="col-md-6">
                  <label for="faqCat.parent_id" class="form-label">Parent Category</label>
                  <br />
                  <span
                    class="text-warning mt-1 d-inline-block"
                  >You can not change the parent of a category that has children.</span>
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
      faqCat: {
        title: {},
        parent_id: 0,
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
        this.updateFaqCat();
      } else {
        this.createFaqCat();
      }
      this.busy = false;
    },
    createFaqCat() {
      axios
        .post("faqCat", this.faqCat)
        .then((response) => {
          this.$store.commit("ADD_FAQ_CAT", response.data);
          this.$router.push({ name: "faqCat.index" });
          this.$toast.success("FAQ category was created.", {
            position: "top-right",
          });
          this.$Progress.finish();
        })
        .catch((error) => {
          console.log(error);
          this.$toast.error("Couldn't create FAQ Category.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
    updateFaqCat() {
      axios
        .put(`faqCat/${this.faqCat.id}`, this.faqCat)
        .then((response) => {
          this.$router.push({ name: "faqCat.index" });
          this.$toast.success("FAQ category was updated.", {
            position: "top-right",
          });
          this.$Progress.finish();
        })
        .catch((error) => {
          this.$toast.error("Couldn't update FAQ Category.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
  },
  computed: {
    parentCats() {
      return this.$store.getters.faqCats.filter(
        (cat) => cat.parent_id == 0 && cat.id != this.faqCat.id
      );
    },
    languages() {
      return this.$store.getters.languages;
    },
    hasChildren() {
      return this.$store.getters.faqCats.filter(
        (cat) => cat.parent_id == this.faqCat.id
      ).length > 0
        ? true
        : false;
    },
  },
  mounted() {
    if (this.$route.params.faqCat) {
      this.editMode = true;
      axios
        .get(`faqCat/${this.$route.params.faqCat}`)
        .then(({ data }) => {
          this.faqCat = data;
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
