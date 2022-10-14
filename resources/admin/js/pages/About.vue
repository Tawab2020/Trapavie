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
            <li class="breadcrumb-item active">About</li>
          </ol>
        </div>
        <h4 class="page-title">Update About Page</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="updateAbout()" enctype="multipart/form-data">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">About Page in {{ language.name }}</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label
                      :for="'about.main_title_' + language.code"
                      class="form-label"
                    >Main Title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="about.main_title[language.code]"
                      :required="index == 0"
                      :id="'about.main_title_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label
                      :for="'about.secondary_title_' + language.code"
                      class="form-label"
                    >Secondary Title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="about.secondary_title[language.code]"
                      :required="index == 0"
                      :id="'about.secondary_title_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <!-- end col -->
              </div>
              <!-- end row -->
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label
                      :for="'about.excerpt_' + language.code"
                      class="form-label"
                    >Excerpt in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="about.excerpt[language.code]"
                      :required="index == 0"
                      :id="'about.excerpt_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <!-- end col -->
              </div>
              <!-- end row -->
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label
                      :for="'about.description_' + language.code"
                      class="form-label"
                    >description in {{ language.name }}</label>
                    <textarea
                      class="form-control"
                      v-model="about.description[language.code]"
                      :required="index == 0"
                      :id="'about.description_' + language.code"
                      placeholder="Type something ..."
                    ></textarea>
                  </div>
                </div>
                <!-- end col -->
              </div>
              <!-- end row -->
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label
                      :for="'about.btn_text_' + language.code"
                      class="form-label"
                    >Button Text in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="about.btn_text[language.code]"
                      :id="'about.btn_text_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <div v-if="index == 0" class="col-md-6">
                  <div class="mb-3">
                    <label for="about.btn_url" class="form-label">Button URL</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="about.btn_url"
                      id="about.btn_url"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <!-- end col -->
              </div>
              <!-- end row -->
              <div class="row" v-if="index == 0">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="bg_image" class="form-label">Background Image</label>
                    <input
                      type="file"
                      accept="image/*"
                      class="form-control"
                      @change="
                        bg_imageSelect($event.target.files)
                      "
                      id="bg_image"
                    />
                    <small v-if="imageErrors.bg_image" class="text-danger">
                      {{
                      imageErrors.bg_image[0]
                      }}
                    </small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="image" class="form-label">About Image</label>
                    <input
                      type="file"
                      accept="image/*"
                      class="form-control"
                      @change="
                        imageSelect($event.target.files)
                      "
                      id="image"
                    />
                    <small v-if="imageErrors.image" class="text-danger">
                      {{
                      imageErrors.image[0]
                      }}
                    </small>
                  </div>
                </div>
                <!-- end col -->
              </div>
              <!-- end row -->
              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label
                      :for="'about.team_title_' + language.code"
                      class="form-label"
                    >Team Section Title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="about.team_title[language.code]"
                      :required="index == 0"
                      :id="'about.team_title_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="mb-3">
                    <label
                      :for="'about.team_excerpt_' + language.code"
                      class="form-label"
                    >Team Section excerpt in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="about.team_excerpt[language.code]"
                      :required="index == 0"
                      :id="'about.team_excerpt_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <!-- end col -->
              </div>
              <!-- end row -->

              <div v-if="index == 0" class="text-end">
                <button :disabled="busy" type="submit" class="btn btn-success mt-2">
                  <i class="mdi mdi-content-save"></i> Save
                </button>
              </div>
              <hr />
              <br />
            </template>
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
      about: {
        main_title: {},
        secondary_title: {},
        excerpt: {},
        description: {},
        btn_text: {},
        team_title: {},
        team_excerpt: {},
      },
      errors: [],
      imageErrors: [],
      busy: false,
    };
  },
  methods: {
    updateAbout() {
      this.busy = true;
      this.$Progress.start();

      if (this.imagesPresent) {
        let formData = new FormData();
        formData.append("_method", "PUT");
        if (this.about.bg_image) {
          if (typeof this.about.bg_image.name == "string") {
            formData.append("bg_image", this.about.bg_image);
          }
        }
        if (this.about.image) {
          if (typeof this.about.image.name == "string") {
            formData.append("image", this.about.image);
          }
        }
        axios
          .post("about/uploadImages", formData, {
            headers: { "Content-Type": "multipart/form-data" },
          })
          .then(({ data }) => {
            this.$toast.success(
              "About page images were updated successfully.",
              {
                position: "top-right",
              }
            );
            document.getElementById("bg_image").value = null;
            document.getElementById("image").value = null;
          })
          .catch((error) => {
            this.imageErrors = error.data.errors;
            this.$toast.error("Could not update about page Images.", {
              position: "top-right",
            });
            this.$Progress.fail();
            this.busy = false;
          });
      }

      axios
        .put("about/update", this.about)
        .then(({ data }) => {
          this.$toast.success("About page was updated.", {
            position: "top-right",
          });
          this.busy = false;
          this.$Progress.finish();
        })
        .catch((error) => {
          this.errors = error.data.errors;
          this.$toast.error("Could not update about page.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.busy = false;
        });
    },
    bg_imageSelect(fileList) {
      var imageFile = fileList[0];
      this.about.bg_image = imageFile;
      return;
    },
    imageSelect(fileList) {
      var imageFile = fileList[0];
      this.about.image = imageFile;
      return;
    },
  },
  computed: {
    languages() {
      return this.$store.getters.languages;
    },
    imagesPresent() {
      if (this.about.bg_image) {
        if (typeof this.about.bg_image.name == "string") {
          return true;
        }
      }
      if (this.about.image) {
        if (typeof this.about.image.name == "string") {
          return true;
        }
      }
      return false;
    },
  },
  mounted() {
    axios
      .get("about")
      .then(({ data }) => {
        this.about = data;
      })
      .catch((error) => console.error(error));
  },
};
</script>

<style></style>
