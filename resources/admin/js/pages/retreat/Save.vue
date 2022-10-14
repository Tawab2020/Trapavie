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
              <router-link :to="{name: 'retreats.index'}" href="javascript: void(0);">Retreat Datas</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add Retreat Data</h4>
        <h4 v-else class="page-title">Update Retreat Data</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="submitForm()">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Retreat Data in {{ language.name }}</h5>
              <div class="row">
                <div
                  :class="{
                              'col-md-3': index == 0,
                              'col-md-12': index != 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'retreats.title_' + language.code"
                      class="form-label"
                    >Option title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="retreats.title[language.code]"
                      :required="index == 0"
                      :id="'retreats.title_' + language.code"
                      placeholder="Type something ..."
                      autofocus
                    />
                  </div>
                </div>
                <template v-if="index == 0">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label
                        for="retreats.Retreat_data_category_id"
                        class="form-label"
                      >Retreat Data Category</label>
                      <select
                        class="form-control"
                        v-model="retreats.category_id"
                        id="retreats.Retreat_data_category_id"
                        placeholder="Type something ..."
                      >
                        <option value="-1" disabled>Please Select</option>

                        <option
                          v-for="(c, index) in categories"
                          :key="index"
                          :value="c.id"
                        >{{parseJson(c.title).en}}</option>
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
                  <div class="col-md-2" v-else-if="retreats.image">
                    <label for class="form-label">Current Icon</label>
                    <br />
                    <img
                      :src="`${$FilePath}/Retreat/data/${retreats.image.image}`"
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
  props:{
    categoryId:{
      required:false,
      default(){
        return null;
      }
    },
    retreatId:{
      required:false,
      default(){
        return '';
      }
    }
  },
  data() {
    return {
      editMode: false,
      categories:[],
      retreats: {
        category_id:this.$route.params.categoryId??-1,
        title: {},
        icon:''
      },
      errors: [],
      imagePreview: null,
      busy: false,
    };
  },
  methods: {
    submitForm() {
      let url = "retreats/store";
      if(this.retreatId){
        url = 'retreats/update/'+this.retreatId
      }
      let formData = new FormData();
      formData.append("icon",this.retreats.icon)
      formData.append('title',JSON.stringify(this.retreats.title))
      formData.append('category_id',this.retreats.category_id);
      axios
        .post(url, formData)
        .then((response) => {
          this.$toast.success("Option was added, successfully.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.$router.back();
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
    updateretreats() {
      axios
        .put(`retreats/${this.retreats.id}`, this.retreats)
        .then((response) => {
          if (this.imagePreview) {
            let formData = new FormData();
            formData.append("_method", "PUT");
            formData.append("image", this.retreats.image);
            axios
              .post(`retreats/${this.retreats.id}/uploadIcon`, formData, {
                headers: { "Content-Type": "multipart/form-data" },
              })
              .then(({ data }) => {
                console.log(data);
                this.$store.commit("UPDATE_Retreat_DATA", data);
              })
              .catch((error) => {
                this.$toast.error("Couldn't update category icon.", {
                  position: "top-right",
                });
                this.$Progress.fail();
                this.busy = false;
              });
          } else {
            this.$store.commit("UPDATE_Retreat_DATA", response.data);
          }
          this.$toast.success("Option was updated, successfully.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.$router.push({
            name: "retreats.index",
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
        this.retreats.icon = null;
        this.imagePreview = null;
        return;
      }
      this.showImage(fileList[0]);
      var imageFile = fileList[0];
      this.retreats.icon = imageFile;
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
    parseJson(arg){
      return JSON.parse(arg);
    },
  },
  computed: {
    languages() {
      return this.$store.getters.languages;
    },
  },
  created() {
    axios.get('retreats/categories/bare')
    .then(res=>{
      this.categories = res.data;
    })
    if(this.retreatId){
      axios.get(`retreats/show/${this.retreatId}`)
        .then(({ data }) => {
          this.retreats ={
            category_id: data.category_id,
            title:JSON.parse(data.title),
            icon:data.icon,
          };

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
