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
              >Retreat Data Category</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 class="page-title">Retreat Data Category</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="updateRetreatCatagory()">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Retreat Data Category in {{ language.name }}</h5>
              <div class="row">
                <div
                  :class="{
                              'col-md-6': index == 0,
                              'col-md-12': index != 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'retreatDataCategory.title_' + language.code"
                      class="form-label"
                    >Title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="retreatDataCategory.title[language.code]"
                      :required="index == 0"
                      :disabled="index == 0"
                      :id="'retreatDataCategory.title_' + language.code"
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
                  <div class="col-md-2" v-else-if="retreatDataCategory.image">
                    <label for class="form-label">Current Icon</label>
                    <br />
                    <img
                      :src="`${$FilePath}/venue/data/${retreatDataCategory.image.image}`"
                      id="image"
                      alt
                      class="rounded"
                      height="48"
                    />
                  </div>
                </template>
              </div>
              <!-- end row -->
              <hr/>
              <br/>
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
      retreatDataCategory: {
        title: {},
        icon:''
      },
      errors: [],
      imagePreview: null,
      busy: false,
    };
  },
  methods: {
    updateRetreatCatagory() {
      let formData = new FormData()
      let title = JSON.stringify(this.retreatDataCategory.title);
      console.log('typeof ', typeof title )
      formData.append("title",title)
      formData.append('icon',this.retreatDataCategory.icon)
      this.busy = true;
      this.$Progress.start();
      axios
        .post(
          `retreats/category/${this.retreatDataCategory.id}`,
         formData
        )
        .then((response) => {
          this.$toast.success("Category updated, successfully.", {
            position: "top-right",
          });
          this.$router.push({
            name: "retreats.index",
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
        this.retreatDataCategory.icon = null;
        this.imagePreview = null;
        return;
      }
      this.showImage(fileList[0]);
      var imageFile = fileList[0];
      this.retreatDataCategory.icon = imageFile;
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
      .get(`retreats/category/show/${this.$route.params.retreatDataCategory}`)
      .then(({ data }) => {
        this.retreatDataCategory ={
          id:data.id,
          title:JSON.parse(data.title),
          icon:data.icon,
        };
      })
      .catch((error) => {
        reject(error);
      });
  },
};
</script>

<style>
</style>
