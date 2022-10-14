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
              <router-link
                :to="{name: 'venueData.index'}"
                href="javascript: void(0);"
              >Venue Data Category</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 class="page-title">Update Venue Data Category</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="updatevenueDataCategory()">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Venue Data Category in {{ language.name }}</h5>
              <div class="row">
                <div
                  :class="{
                              'col-md-6': index == 0,
                              'col-md-12': index != 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'venueDataCategory.title_' + language.code"
                      class="form-label"
                    >Title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="venueDataCategory.title[language.code]"
                      :required="index == 0"
                      :disabled="index == 0"
                      :id="'venueDataCategory.title_' + language.code"
                      placeholder="Type something ..."
                      autofocus
                    />
                  </div>
                </div>
                <template v-if="index == 0">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label for="icon" class="form-label">Default Icon</label>
                      <input
                        type="file"
                        class="form-control"
                        @change="filesChange($event.target.files)"
                        accept="image/*"
                        id="icon"
                      />
                      <small v-if="errors.image" class="text-danger">{{ errors.image[0] }}</small>
                    </div>
                  </div>
                  <!-- end col -->
                  <div class="col-md-2" v-if="imagePreview">
                    <label for class="form-label">Image Preview</label>
                    <br />
                    <img :src="imagePreview" id="image" alt class="rounded" height="48" />
                  </div>
                  <div class="col-md-2" v-else-if="venueDataCategory.image">
                    <label for class="form-label">Current Icon</label>
                    <br />
                    <img
                      :src="`${$FilePath}/venue/data/${venueDataCategory.image.image}`"
                      id="image"
                      alt
                      class="rounded"
                      height="48"
                    />
                  </div>
                </template>
              </div>
              <!-- end row -->
              <hr />
              <br />
            </template>

            <div class="text-end">
              <button type="submit" :disabled="busy" class="btn btn-success mt-2">
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
      venueDataCategory: {
        title: {},
      },
      errors: [],
      imagePreview: null,
      busy: false,
    };
  },
  methods: {
    updatevenueDataCategory() {
      this.busy = true;
      this.$Progress.start();
      axios
        .put(
          `venueDataCategory/${this.venueDataCategory.id}`,
          this.venueDataCategory
        )
        .then((response) => {
          if (this.imagePreview) {
            let formData = new FormData();
            formData.append("_method", "PUT");
            formData.append("image", this.venueDataCategory.image);
            axios
              .post(
                `venueDataCategory/${this.venueDataCategory.id}/uploadIcon`,
                formData,
                {
                  headers: { "Content-Type": "multipart/form-data" },
                }
              )
              .then(({ data }) => {
                console.log(data);
                this.$store.commit("UPDATE_VENUE_DATA_CATEGORY", data);
              })
              .catch((error) => {
                this.$toast.error("Couldn't update category icon.", {
                  position: "top-right",
                });
                this.$Progress.fail();
                this.busy = false;
              });
          } else {
            this.$store.commit("UPDATE_VENUE_DATA_CATEGORY", response.data);
          }
          this.$toast.success("Category updated, successfully.", {
            position: "top-right",
          });
          this.$router.push({
            name: "venueData.index",
          });
          this.$Progress.finish();
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error("Couldn't update category.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
    filesChange(fileList) {
      if (fileList.length == 0) {
        this.venueDataCategory.image = null;
        this.imagePreview = null;
        return;
      }
      this.showImage(fileList[0]);
      var imageFile = fileList[0];
      this.venueDataCategory.image = imageFile;
      return;
    },

    showImage(file) {
      var reader = new FileReader();
      if (reader) {
        reader.onload = (e) => {
          this.imagePreview = e.target.result;
        };
      }
      reader.readAsDataURL(file);
    },
  },
  computed: {
    languages() {
      return this.$store.getters.languages;
    },
  },
  mounted() {
    axios
      .get(`venueDataCategory/${this.$route.params.venueDataCategory}`)
      .then(({ data }) => {
        this.venueDataCategory = data;
      })
      .catch((error) => {
        reject(error);
      });
  },
};
</script>

<style>
</style>
