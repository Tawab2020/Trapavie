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
                        <li class="breadcrumb-item active">Update Profile</li>
                    </ol>
                </div>
                <h4 class="page-title">Update Profile</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="updateProfile()" enctype=”multipart/form-data”>
                        <h5 class="mb-4 text-uppercase">
                            <i class="mdi mdi-account-circle me-1"></i> Update Profile info
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" v-model="admin.name" required id="name"
                                        placeholder="Enter name" />
                                    <small v-if="errors.name" class="text-danger">{{ errors.name[0] }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" v-model="admin.username" required id="username"
                                        placeholder="Enter username" />
                                    <small v-if="errors.username" class="text-danger">{{ errors.username[0] }}</small>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Profile Image</label>
                                    <input type="file" class="form-control"
                                        @change="filesChange($event.target.files)" accept="image/*" id="profilePicture" />
                                    <small v-if="errors.image" class="text-danger">{{ errors.image[0] }}</small>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-md-6" v-if="imagePreview">
                                <label for="" class="form-label">Image Preview</label> <br>
                                <img :src="imagePreview" id="image" alt="" class="rounded" height="48">
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="text-end">
                            <button :disabled="busy" type="submit" class="btn btn-success mt-2">
                                <i class="mdi mdi-content-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="changePassword()">
                        <h5 class="mb-4 text-uppercase">
                            <i class="mdi mdi-account-circle me-1"></i> Change Password
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" v-model="passwords.currentPassword" required
                                        :class="{ 'is-invalid': errors.currentPassword }"
                                        id="currentPassword" placeholder="Enter Current password" />
                                    <small v-if="errors.currentPassword" class="text-danger">{{ errors.currentPassword[0] }}</small> <br>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input
                                        type="password"
                                        :disabled="passwords.currentPassword == ''"
                                        class="form-control"
                                        :class="{'is-invalid': errors.password}"
                                        v-model="passwords.password"
                                        required
                                        id="password"
                                        placeholder="Enter New password" />
                                    <small v-if="errors.password" class="text-danger">{{ errors.password[0] }}</small>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-success mt-2">
                                <i class="mdi mdi-content-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
</template>

<script>
import { Storage } from "./../../../helpers/storage";
export default {
  data() {
    return {
      admin: {},
      errors: [],
      profilePicture: null,
      imagePreview: null,
      passwords: {},
      busy: false
    };
  },
  mounted() {
    this.admin = this.$store.getters.admin.user;
  },

  methods: {
    updateProfile() {
        this.errors = [];
        this.busy = true;
        this.$Progress.start();
        axios
        .put(`update/${this.admin.id}`, this.admin)
        .then((response) => {
            if (this.profilePicture) {
                let formData = new FormData();
                formData.append("_method", "PUT");
                formData.append("image", this.profilePicture);
                axios
                .post(`update/${this.admin.id}/uploadImage`, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                })
                .then((response) => {
                    this.$store.commit("UPDATE_ADMIN", response.data);
                })
                .catch((error) => {
                    this.$toast.error("Could not upload image.", {
                        position: "top-right",
                    });
                    this.$Progress.fail();
                    this.errors = error.data.errors;
                });
            }

          this.$store.commit("UPDATE_ADMIN", response.data);
          this.$toast.success("Profile updated successfully.", {
              position: "top-right",
          });
          this.clearProfileInput();
          this.$Progress.finish();
          this.busy = false;

        })
        .catch((error) => {
            this.$toast.error("Could not update admin", {
                        position: "top-right",
                    });
          this.$Progress.fail();
        this.busy = false;

          this.errors = error.data.errors;
        });
    },

    changePassword() {
        this.errors = [];

        this.busy = true;
        this.$Progress.start();
      axios
        .put(`changePassword/${this.admin.id}`, this.passwords)
        .then((response) => {
        document.getElementById("password").value = null;
        this.passwords = {};

          this.$toast.success("Password was changed, successfully.", {
              position: "top-right",
          });
          this.busy = false;
          this.$Progress.finish();
        })
        .catch((error) => {
            this.$toast.error("Couldn't change password.", {
              position: "top-right",
          });
          this.busy = false;
          this.errors = error.data.errors;
          this.$Progress.fail();
        });
    },

    filesChange(fileList) {
      if (fileList.length == 0) {
        this.profilePicture = null;
        this.imagePreview = null;
        return;
      }
      this.showImage(fileList[0]);
      var imageFile = fileList[0];
      this.profilePicture = imageFile;
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

    clearProfileInput(){
        this.imagePreview = null;
        this.profilePicture = null;
        document.getElementById("profilePicture").value = null;
    }
  },
};
</script>

<style></style>
