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
                :to="{name: 'accommodationTypes.index'}"
                href="javascript: void(0);"
              >Accommodation Type</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add Accommodation Type</h4>
        <h4 v-else class="page-title">Update Accommodation Type</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit()">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Accommodation Type in {{ language.name }}</h5>
              <div class="row">
                <div
                  :class="{
                              'col-md-5': index == 0,
                              'col-md-12': !index == 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'accommodationType.title_' + language.code"
                      class="form-label"
                    >Accommodation Type title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="accommodationType.title[language.code]"
                      :required="index == 0"
                      :id="'accommodationType.title_' + language.code"
                      placeholder="Type something ..."
                      autofocus
                    />
                  </div>
                </div>
                <template v-if="index == 0">
                  <div class="col-md-2">
                    <div class="mb-3">
                      <label for="accommodationType.ask_for_rooms" class="form-label">Ask for rooms?</label>
                      <select
                        class="form-control"
                        v-model="accommodationType.ask_for_rooms"
                        id="accommodationType.ask_for_rooms"
                      >
                        <option value="1">Yes</option>
                        <option value="0">No</option>
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
                  <div class="col-md-2" v-else-if="accommodationType.image">
                    <label for class="form-label">Current Icon</label>
                    <br />
                    <img
                      :src="`${$FilePath}/venue/data/accommodationTypes/${accommodationType.image.image}`"
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
      accommodationType: {
        title: {},
        ask_for_rooms: 1,
      },
      imagePreview: null,
      errors: [],
      busy: false,
    };
  },
  methods: {
    handleSubmit() {
      this.busy = true;
      this.$Progress.start();
      if (this.editMode) {
        this.updateAccommodationType();
      } else {
        this.createAccommodationType();
      }
    },
    createAccommodationType() {
      axios
        .post("accommodationType", this.accommodationType)
        .then((response) => {
          if (this.imagePreview) {
            let formData = new FormData();
            formData.append("_method", "PUT");
            formData.append("image", this.accommodationType.image);
            axios
              .post(
                `accommodationType/${response.data.id}/uploadIcon`,
                formData,
                {
                  headers: { "Content-Type": "multipart/form-data" },
                }
              )
              .then(({ data }) => {
                console.log(data);
                this.$store.commit("ADD_ACCOMMODATION_TYPE", data);
              })
              .catch((error) => {});
          } else {
            this.$store.commit("ADD_ACCOMMODATION_TYPE", response.data);
          }
          this.$toast.success("Accommodation type was added, successfully.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.$router.push({
            name: "accommodationTypes.index",
          });
          this.busy = false;
        })
        .catch((error) => {
          console.log(error);
          this.$toast.error(
            "Couldn't add accommodation type. Fill required data.",
            {
              position: "top-right",
            }
          );
          this.$Progress.fail();
          this.busy = false;
        });
    },
    updateAccommodationType() {
      let formData = new FormData();
      formData.append("_method", "PUT");
      formData.append("title", JSON.stringify(this.accommodationType.title));
      formData.append("ask_for_rooms", this.accommodationType.ask_for_rooms);
      if (this.imagePreview) {
        formData.append("image", this.accommodationType.image);
      }
      axios
        .post(`accommodationType/${this.accommodationType.id}`, formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then(({ data }) => {
          this.busy = false;
          this.$Progress.finish();
          this.$store.commit("UPDATE_ACCOMMODATION_TYPE", data);
          this.$router.push({
            name: "accommodationTypes.index",
          });
        })
        .catch((error) => {
          this.$toast.error("Couldn't update accommodation type.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
    filesChange(fileList) {
      if (fileList.length == 0) {
        this.accommodationType.image = null;
        this.imagePreview = null;
        return;
      }
      this.showImage(fileList[0]);
      var imageFile = fileList[0];
      this.accommodationType.image = imageFile;
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
    accommodationTypes() {
      if (this.$store.getters.accommodationTypes[0]) {
        return this.$store.getters.accommodationTypes;
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
    if (this.$route.params.accommodationType) {
      this.editMode = true;
      axios
        .get(`accommodationType/${this.$route.params.accommodationType}`)
        .then(({ data }) => {
          this.accommodationType = data;
        })
        .catch((error) => {
          reject(error);
        });
    }
  },
};
</script>
