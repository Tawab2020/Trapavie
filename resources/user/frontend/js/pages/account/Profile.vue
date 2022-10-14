<template>
  <div class="card border-0 rounded shadow">
    <div class="card-body">
      <h5 class="text-md-start text-center">Personal Detail :</h5>

      <div class="row mt-3 text-md-start text-center d-sm-flex">
        <div class="col-md-3">
          <img
            :src="imagePreview"
            class="avatar float-md-left avatar-medium rounded-circle shadow me-md-4"
            v-if="imagePreview"
          />
          <img
            v-else
            :src="`${$FilePath}/user/profiles/${user.image}`"
            class="avatar float-md-left avatar-medium rounded-circle shadow me-md-4"
            alt
          />
        </div>

        <div :class="{'col-md-9 mt-4': !profilePicture, 'col-md-6 mt-4': profilePicture}">
          <label class="form-label">Change Profile Picture</label>
          <div class="form-icon position-relative">
            <input
              @change="profileChange($event.target.files)"
              class="form-control"
              id="profilePicture"
              type="file"
              accept="image/*"
            />
            <small
              v-if="v$.profilePicture.$error"
              class="text-danger"
            >{{ v$.profilePicture.$errors[0].$message }}</small>
          </div>
        </div>

        <div class="col-md-3 mt-4">
          <div v-if="profilePicture">
            <label for class="form-label mx-2">Cancel</label>
            <br />
            <button
              @click="removeProfilePicture()"
              class="btn btn-sm btn-outline-danger p-0 px-2 mx-2 mt-md-2 h5"
            >
              <i class="uil uil-times"></i>
            </button>
          </div>
        </div>
      </div>

      <form autocomplete="new-password" @submit.prevent="handleSubmit()">
        <div class="row mt-4">
          <div class="col-md-12">
            <label class="form-label mb-0">Legal Name</label>
            <span
              class="mb-2 input-info"
            >This is the name on your travel documents, which could be a driver's license or a passport</span>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <div class="form-icon position-relative">
                <input
                  v-model="user.fname"
                  id="first"
                  type="text"
                  class="form-control ps-3"
                  placeholder="First Name :"
                />
              </div>
            </div>
          </div>
          <!--end col-->
          <div class="col-md-6">
            <div class="mb-3">
              <div class="form-icon position-relative">
                <input
                  v-model="user.lname"
                  id="last"
                  type="text"
                  class="form-control ps-3"
                  placeholder="Last Name :"
                />
              </div>
            </div>
          </div>
          <!--end col-->
          <div class="col-lg-12">
            <div class="mb-3">
              <label class="form-label mb-0">Introduction</label>
              <span
                class="mb-2 input-info"
              >Use this space to to tell your story and give some insight into your hobbies, interests and values</span>
              <div class="form-icon position-relative">
                <textarea
                  v-model="userDetail.bio"
                  id="comments"
                  rows="4"
                  class="form-control ps-3"
                  placeholder="Description :"
                ></textarea>
              </div>
            </div>
          </div>
          <!--end col-->
          <div class="col-lg-12">
            <div class="mb-3">
              <label class="form-label">
                Date of Birth
                <i class="uil uil-calendar icons"></i>
              </label>
              <div class="form-icon position-relative">
                <input
                  v-model="userDetail.dob"
                  class="form-control"
                  min="1900-01-01"
                  max="2015-01-01"
                  type="date"
                />
              </div>
            </div>
          </div>
          <!--end col-->
          <div :class="{'col-12': !id_document, 'col-8': id_document}">
            <div class="mb-3">
              <label class="form-label">ID Verification</label>
              <div class="form-icon position-relative">
                <input
                  @change="idChange($event.target.files)"
                  class="form-control"
                  id="id_document"
                  type="file"
                  accept="image/*, application/pdf"
                />
                <small
                  v-if="v$.id_document.$error"
                  class="text-danger"
                >{{ v$.id_document.$errors[0].$message }}</small>
              </div>
            </div>
          </div>
          <!-- end col -->
          <div class="col-4 mt-sm-0">
            <div v-if="id_document">
              <label for class="form-label mx-2">Cancel</label>
              <br />
              <button
                @click="removeIdDocument()"
                class="btn btn-sm btn-outline-danger p-0 px-2 mx-2 mt-2 h5"
              >
                <i class="uil uil-times"></i>
              </button>
            </div>
          </div>
          <div class="col-md-12">
            <label class="form-label mb-0">Address</label>
            <span class="mb-2 input-info">Residential or business registration address</span>
          </div>
          <!--end col-->
          <div class="col-lg-12">
            <div class="mb-3">
              <div class="form-icon position-relative">
                <v-select
                  autocomplete="off"
                  v-model="userDetail.country"
                  @input="$emit('input', $event)"
                  value="Argentina"
                  :options="countries"
                  @option:selected="countrySelected()"
                  label="country"
                  placeholder="Country"
                ></v-select>
              </div>
            </div>
          </div>
          <!--end col-->
          <div class="col-md-12">
            <div class="mb-3">
              <div class="form-icon position-relative">
                <input
                  v-model="userDetail.street"
                  id="first"
                  type="text"
                  class="form-control ps-3"
                  placeholder="Street address"
                />
              </div>
            </div>
          </div>
          <!--end col-->
          <div class="col-md-12">
            <div class="mb-3">
              <div class="form-icon position-relative">
                <input
                  v-model="userDetail.apt"
                  id="first"
                  type="text"
                  class="form-control ps-3"
                  placeholder="Apt, Suite, (optional)"
                />
              </div>
            </div>
          </div>

          <!--end col-->
          <div class="col-md-12">
            <div class="mb-3">
              <div class="form-icon position-relative">
                <v-select
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  title="Select Country First"
                  v-model="userDetail.state"
                  :disabled="!userDetail.country"
                  @option:selected="stateSelected()"
                  :options="states"
                  label="state"
                  :placeholder="statePlaceholder"
                ></v-select>
              </div>
            </div>
          </div>
          <!--end col-->
          <div class="col-md-6">
            <div class="mb-3">
              <div class="form-icon position-relative">
                <v-select
                  v-model="userDetail.city"
                  :disabled="!userDetail.state"
                  :options="cities"
                  taggable
                  label="city"
                  :placeholder="cityPlaceholder"
                ></v-select>
              </div>
            </div>
          </div>
          <!--end col-->
          <div class="col-md-6">
            <div class="mb-3">
              <div class="form-icon position-relative">
                <input
                  v-model="userDetail.zip"
                  id="first"
                  type="text"
                  class="form-control ps-3"
                  placeholder="Zip Code"
                />
                <small
                  v-if="v$.userDetail.zip.$error"
                  class="text-danger"
                >{{ v$.userDetail.zip.$errors[0].$message }}</small>
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
        <div class="row">
          <div class="col-sm-12">
            <input
              type="submit"
              id="submit"
              name="send"
              class="btn btn-outline-warning float-end"
              value="Save Personal Information"
            />
          </div>
          <!--end col-->
        </div>
        <!--end row-->
      </form>
      <!--end form-->
    </div>
  </div>
</template>
<script>
import vSelect from "vue-select";
import useValidate from "@vuelidate/core";
import { required, maxLength, helpers } from "@vuelidate/validators";
const isImage = (value) => (value ? value.type.startsWith("image") : true);
const sizeCheck = (value) => (value ? value.size < 5 * 1024 * 1024 : true);
const isValidIdType = (value) =>
  value
    ? value.type.startsWith("image") || value.type.startsWith("application/pdf")
    : true;

export default {
  components: {
    vSelect,
  },
  data() {
    return {
      v$: useValidate(),
      userDetail: {},
      profilePicture: null,
      imagePreview: null,
      id_document: null,
      profileChanged: false,
      countries: [],
      states: [],
      cities: [],
    };
  },
  validations() {
    return {
      userDetail: {
        zip: {
          maxLengthValue: helpers.withMessage(
            "Please enter a valid Zip Code",
            maxLength(10)
          ),
        },
      },
      profilePicture: {
        isImage: helpers.withMessage(
          "Please provide an image file only.",
          isImage
        ),
        sizeCheck: helpers.withMessage(
          "Image can't be larger than 5MB.",
          sizeCheck
        ),
      },
      id_document: {
        isValidIdType: helpers.withMessage(
          "Please provide an image or a pdf document.",
          isValidIdType
        ),
      },
    };
  },
  methods: {
    handleSubmit() {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.$Progress.start();
        axios
          .put(`user/${this.user.id}`, this.user)
          .then(({ data }) => {
            if (this.profilePicture) {
              let formData = new FormData();
              formData.append("_method", "PUT");
              formData.append("image", this.profilePicture);
              axios
                .post(`user/${this.user.id}/uploadImage`, formData, {
                  headers: { "Content-Type": "multipart/form-data" },
                })
                .then((response) => {
                  this.$store.commit("UPDATE_PROFILE", response.data);
                  this.user.image = response.data.image;
                  this.removeProfilePicture();
                })
                .catch((error) => {
                  this.$Progress.fail();
                  this.$toast.error(
                    "Couldn't save profile picture, try again.",
                    {
                      position: "top-right",
                    }
                  );
                  console.log(error);
                });
            }
            this.$store.commit("UPDATE_PROFILE", data);
          })
          .catch((error) => {
            this.$Progress.fail();
            console.log(error);
          });

        axios
          .put(`userDetail/${this.user.id}`, this.userDetail)
          .then(({ data }) => {
            this.userDetail = data;
            if (this.id_document) {
              let formData = new FormData();
              formData.append("_method", "PUT");
              formData.append("id_document", this.id_document);
              axios
                .post(`userDetail/${this.userDetail.id}/uploadId`, formData, {
                  headers: { "Content-Type": "multipart/form-data" },
                })
                .then(({ data }) => {
                  this.removeIdDocument();
                  console.log("done");
                })
                .catch((error) => {
                  this.$Progress.fail();
                  console.log(error);
                });
            }
          })
          .catch((error) => {
            this.$Progress.fail();
            console.log(error);
          });
        this.$Progress.finish();
        this.$toast.success("Profile was updated.", {
          position: "top-right",
        });
      }
    },
    profileChange(fileList) {
      if (fileList.length == 0) {
        this.profilePicture = null;
        this.imagePreview = null;
        return;
      }
      this.profileChanged = true;
      this.showImage(fileList[0]);
      var imageFile = fileList[0];
      this.profilePicture = imageFile;
      this.v$.profilePicture.$touch();
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

    removeProfilePicture() {
      this.profilePicture = null;
      this.imagePreview = null;
      document.getElementById("profilePicture").value = null;
    },

    idChange(fileList) {
      if (fileList.length == 0) {
        this.id_document = null;
        return;
      }
      var idFile = fileList[0];
      this.id_document = idFile;
      this.v$.id_document.$touch();
      return;
    },

    removeIdDocument() {
      this.id_document = null;
      document.getElementById("id_document").value = null;
    },

    countrySelected() {
      this.userDetail.state = null;
      this.userDetail.city = null;
      this.getStates();
    },

    getStates() {
      axios
        .post("getStates", { country: this.userDetail.country })
        .then(({ data }) => {
          this.states = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    stateSelected() {
      this.userDetail.city = null;
      this.getCities();
    },

    getCities() {
      axios
        .post("getCities", {
          country: this.userDetail.country,
          state: this.userDetail.state,
        })
        .then(({ data }) => {
          this.cities = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  computed: {
    user() {
      return this.$store.getters.user.user;
    },
    statePlaceholder() {
      return this.userDetail.country
        ? "State / Province"
        : "State / Province (select country first)";
    },
    cityPlaceholder() {
      return this.userDetail.state ? "City" : "City (select state first)";
    },
    userDetailCountry() {
      return this.userDetail.country;
    },
  },
  watch: {
    userDetailCountry: function (val) {
      if (this.userDetail.country == null) {
        this.userDetail.state = null;
        this.userDetail.city = null;
      }
    },
  },
  mounted() {
    axios
      .get(`userDetail/${this.user.id}`)
      .then(({ data }) => {
        this.userDetail = data;
        if (this.userDetail.country) {
          this.getStates();
          this.getCities();
        }
      })
      .catch((error) => {
        console.log(error);
      });
    axios
      .get("getCountries")
      .then(({ data }) => {
        this.countries = data;
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style>
</style>
