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
              <router-link :to="{name: 'venueData.index'}" href="javascript: void(0);">Venue Data</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add Venue Data</h4>
        <h4 v-else class="page-title">Update Venue Data</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit()">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Venue Data in {{ language.name }}</h5>
              <div class="row">
                <div
                  :class="{
                              'col-md-3': index == 0,
                              'col-md-12': index != 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'venueData.title_' + language.code"
                      class="form-label"
                    >Option title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="venueData.title[language.code]"
                      :required="index == 0"
                      :id="'venueData.title_' + language.code"
                      placeholder="Type something ..."
                      autofocus
                    />
                  </div>
                </div>
                <template v-if="index == 0">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label
                        for="venueData.venue_data_category_id"
                        class="form-label"
                      >Venue Data Category</label>
                      <select
                        class="form-control"
                        v-model="venueData.venue_data_category_id"
                        id="venueData.venue_data_category_id"
                        placeholder="Type something ..."
                      >
                        <option value="-1" disabled>Please Select</option>

                        <option
                          v-for="(venueDataCategory, index) in venueDataCategories"
                          :key="index"
                          :value="venueDataCategory.id"
                        >{{venueDataCategory.title['en']}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <label for="icon" class="form-label">Icon</label>
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
                  <div class="col-md-2" v-else-if="venueData.image">
                    <label for class="form-label">Current Icon</label>
                    <br />
                    <img
                      :src="`${$FilePath}/venue/data/${venueData.image.image}`"
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
      venueData: {
        title: {},
        venue_data_category_id: this.$route.params.venueDataCategory || -1,
      },
      errors: [],
      imagePreview: null,
      busy: false,
    };
  },
  methods: {
    handleSubmit() {
      this.busy = true;
      this.$Progress.start();
      if (this.editMode) {
        this.updateVenueData();
      } else { 
        this.createVenueData();
      }
    },
    createVenueData() {
      axios
        .post("venueData", this.venueData)
        .then((response) => {
          if (this.imagePreview) {
            let formData = new FormData();
            console.log('formdatass',formData);

            formData.append("_method", "PUT");
            formData.append("image", this.venueData.image);
            axios
              .post(`venueData/${response.data.id}/uploadIcon`, formData, {
                headers: { "Content-Type": "multipart/form-data" },
              })
              .then(({ data }) => {
                console.log(data);
                this.$store.commit("ADD_VENUE_DATA", data);
              })
              .catch((error) => {
                this.$toast.error("Couldn't update category icon.", {
                  position: "top-right",
                });
                this.$Progress.fail();
                this.busy = false;
              });
          } else { 
            this.$store.commit("ADD_VENUE_DATA", response.data);
          }
          this.$toast.success("Option was added, successfully.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.$router.push({
            name: "venueData.index",
          });
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error("Couldn't add option. Fill required data.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
    updateVenueData() {
      axios
        .put(`venueData/${this.venueData.id}`, this.venueData)
        .then((response) => {
          if (this.imagePreview) {
            let formData = new FormData();
            formData.append("_method", "PUT");
            formData.append("image", this.venueData.image);
            axios
              .post(`venueData/${this.venueData.id}/uploadIcon`, formData, {
                headers: { "Content-Type": "multipart/form-data" },
              })
              .then(({ data }) => {
                console.log(data);
                this.$store.commit("UPDATE_VENUE_DATA", data);
              })
              .catch((error) => {
                this.$toast.error("Couldn't update category icon.", {
                  position: "top-right",
                });
                this.$Progress.fail();
                this.busy = false;
              });
          } else {
            this.$store.commit("UPDATE_VENUE_DATA", response.data);
          }
          this.$toast.success("Option was updated, successfully.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.$router.push({
            name: "venueData.index",
          });
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error("Couldn't update option.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
    filesChange(fileList) {
      if (fileList.length == 0) {
        this.venueData.image = null;
        this.imagePreview = null;
        return;
      }
      this.showImage(fileList[0]);
      var imageFile = fileList[0];
      this.venueData.image = imageFile;
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
    venueDataCategories() {
      if (this.$store.getters.venueDataCategories[0]) {
        return this.$store.getters.venueDataCategories;
      }
      return [
        {
          title: {},
        },
      ];
    },
    languages() {
      return this.$store.getters.languages;
    },
  },
  mounted() {
    if (this.$route.params.venueData) {
      this.editMode = true;
      axios
        .get(`venueData/${this.$route.params.venueData}`)
        .then(({ data }) => {
          this.venueData = data;
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
