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
              <router-link :to="{name: 'team.index'}" href="javascript: void(0);">Team Member</router-link>
            </li>
            <li v-if="!editMode" class="breadcrumb-item">Add</li>
            <li v-else class="breadcrumb-item">Edit</li>
          </ol>
        </div>
        <h4 v-if="!editMode" class="page-title">Add Team Member</h4>
        <h4 v-else class="page-title">Update Team Member</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit()" enctype="multipart/form-data">
            <template v-for="(language, index) in languages" :key="index">
              <h5 class="mb-4 text-uppercase">Team Member Details in {{ language.name }}</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label
                      :for="'teamMember.name_' + language.code"
                      class="form-label"
                    >Name in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="teamMember.name[language.code]"
                      :id="'teamMember.name_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <div
                  :class="{
                              'col-md-3': index == 0,
                              'col-md-6': index != 0,
                            }"
                >
                  <div class="mb-3">
                    <label
                      :for="'teamMember.title_' + language.code"
                      class="form-label"
                    >Title in {{ language.name }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="teamMember.title[language.code]"
                      :id="'teamMember.title_' + language.code"
                      placeholder="Type something ..."
                    />
                  </div>
                </div>
                <div class="col-md-3" v-if="index == 0">
                  <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input
                      type="file"
                      accept="image/*"
                      class="form-control"
                      @change="
                        imageSelect($event.target.files)
                      "
                      id="image"
                    />
                    <small v-if="errors.bg_image" class="text-danger">
                      {{
                      errors.bg_image[0]
                      }}
                    </small>
                  </div>
                </div>
                <!-- end col -->
              </div>
              <!-- end row -->
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label
                      :for="'teamMember.bio_' + language.code"
                      class="form-label"
                    >Bio in {{ language.name }}</label>
                    <textarea
                      class="form-control"
                      rows="10"
                      v-model="teamMember.bio[language.code]"
                      :id="'teamMember.bio_' + language.code"
                      placeholder="Type something ..."
                    ></textarea>
                  </div>
                </div>
                <!-- end col -->
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
      teamMember: {
        name: {},
        title: {},
        bio: {},
      },
      errors: [],
      busy: false,
    };
  },
  methods: {
    handleSubmit() {
      this.$Progress.start();
      this.busy = true;
      if (this.editMode) {
        this.updateTeamMember();
      } else {
        this.createTeamMember();
      }
      this.busy = false;
    },
    createTeamMember() {
      axios
        .post("teamMember", this.teamMember)
        .then((response) => {
          //Upload Image
          let formData = new FormData();
          formData.append("_method", "PUT");
          formData.append("image", this.teamMember.image);
          axios
            .post(`teamMember/${response.data.id}/uploadImage`, formData, {
              headers: { "Content-Type": "multipart/form-data" },
            })
            .then((response) => {
              this.$router.push({ name: "team.index" });
            })
            .catch((error) => {
              console.log(error);
            });
          this.$toast.success("Team member was added, successfully.", {
            position: "top-right",
          });
          this.$Progress.finish();
          this.busy = false;
        })
        .catch((error) => {
          this.$toast.error("Couldn't add team member.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
    updateTeamMember() {
      axios
        .put(`teamMember/${this.teamMember.id}`, this.teamMember)
        .then((response) => {
          // check if file is selected
          if (typeof this.teamMember.image.name == "string") {
            //Upload Image
            let formData = new FormData();
            formData.append("_method", "PUT");
            formData.append("image", this.teamMember.image);
            axios
              .post(`teamMember/${this.teamMember.id}/uploadImage`, formData, {
                headers: { "Content-Type": "multipart/form-data" },
              })
              .then((response) => {
                this.$router.push({ name: "team.index" });
              })
              .catch((error) => {
                console.log(error);
              });
          } else {
            this.$router.push({ name: "team.index" });
            this.$toast.success("Team member was updated, successfully.", {
              position: "top-right",
            });
            this.$Progress.finish();
            this.busy = false;
          }
        })
        .catch((error) => {
          this.$toast.error("Couldn't update team member.", {
            position: "top-right",
          });
          this.$Progress.fail();
          this.busy = false;
        });
    },
    imageSelect(fileList) {
      var imageFile = fileList[0];
      this.teamMember.image = imageFile;
      return;
    },
  },
  computed: {
    languages() {
      return this.$store.getters.languages;
    },
  },
  mounted() {
    if (this.$route.params.teamMember) {
      this.editMode = true;
      axios
        .get(`teamMember/${this.$route.params.teamMember}`)
        .then(({ data }) => {
          this.teamMember = data;
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
