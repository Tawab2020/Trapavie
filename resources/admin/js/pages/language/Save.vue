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
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="language.name" class="form-label">Language Name</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="language.name"
                    required
                    id="language.name"
                    placeholder="Type something ..."
                  />
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="language.code" class="form-label">Language Code</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="language.code"
                    required
                    :disabled="editMode"
                    id="language.code"
                    placeholder="Type something ..."
                  />
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="icon" class="form-label">Icon</label>
                  <input
                    type="file"
                    accept="image/*"
                    class="form-control"
                    @change="
                        iconSelect($event.target.files)
                      "
                    id="icon"
                  />
                  <small v-if="errors.icon" class="text-danger">
                    {{
                    errors.icon[0]
                    }}
                  </small>
                </div>
              </div>
              <!-- end col -->
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
      language: {},
      errors: [],
    };
  },
  methods: {
    handleSubmit() {
      if (this.editMode) {
        this.updateLanguage();
      } else {
        this.createLanguage();
      }
    },
    createLanguage() {
      axios
        .post("language", this.language)
        .then((response) => {
          //Upload Image
          let formData = new FormData();
          formData.append("_method", "PUT");
          formData.append("icon", this.language.icon);
          axios
            .post(`language/${response.data.id}/uploadIcon`, formData, {
              headers: { "Content-Type": "multipart/form-data" },
            })
            .then((response) => {
              this.$router.push({ name: "language.index" });
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch((error) => {
          console.log(error);
        });
    },
    updateLanguage() {
      axios
        .put(`language/${this.language.id}`, this.language)
        .then(({ data }) => {
          // check if file is selected
          if (typeof this.language.icon.name == "string") {
            //Upload Image
            let formData = new FormData();
            formData.append("_method", "PUT");
            formData.append("icon", this.language.icon);
            axios
              .post(`language/${this.language.id}/uploadIcon`, formData, {
                headers: { "Content-Type": "multipart/form-data" },
              })
              .then((response) => {
                this.$store.commit("UPDATE_LANGUAGE", response.data);
              })
              .catch((error) => {
                console.log(error);
              });
          }
          this.$router.push({ name: "language.index" });
          this.$store.commit("UPDATE_LANGUAGE", data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    iconSelect(fileList) {
      var iconFile = fileList[0];
      this.language.icon = iconFile;
      return;
    },
  },
  mounted() {
    if (this.$route.params.language) {
      this.editMode = true;
      axios
        .get(`language/${this.$route.params.language}`)
        .then(({ data }) => {
          this.language = data;
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
