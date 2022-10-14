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
              <router-link :to="{name: 'language.index'}" href="javascript: void(0);">Languages</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add Language</h4>
        <h4 v-else class="page-title">Update Language</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit()" enctype="multipart/form-data">
            <h5 class="mb-4 text-uppercase">Language Details</h5>
            <div v-for="(value, key) in enTranslations" :key="key" class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="language.name" class="form-label">English</label>
                  <span class="d-block">{{enTranslations[key]}}</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="language.name" class="form-label">{{this.$route.params.language}}</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="translations[key]"
                    id="language.name"
                    placeholder="Type something ..."
                  />
                </div>
              </div>
              <!-- end col -->
              <hr />
            </div>
            <hr />
            <br />

            <div class="text-end">
              <button type="submit" class="btn btn-success mt-2">
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
      errors: [],
      translations: [],
      enTranslations: [],
      languageCode: "",
    };
  },
  methods: {
    handleSubmit() {
      axios
        .post(`language/${this.languageCode}/update`, this.translations)
        .then((response) => {
          this.$toast.success("Translations updated.", {
            position: "top-right",
          });
        })
        .catch((error) => {
          this.$toast.danger("An error occured.", {
            position: "top-right",
          });
        });
    },
  },
  mounted() {
    this.languageCode = this.$route.params.language;
    axios
      .get(`language/${this.languageCode}/loadTranslations`)
      .then(({ data }) => {
        this.translations = data.language;
        this.enTranslations = data.en;
      });
  },
};
</script>

<style>
</style>
