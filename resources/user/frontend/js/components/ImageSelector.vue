<template>
  <div class="row mt-3">
    <div class="col-md-3 col-12">
      <span
        :class="this.images.length + this.savedImages.length >= 6 ? 'customFileSelector d-inline-block py-2 px-3 text-center customDisabled no-select' : 'customFileSelector d-inline-block py-2 px-3 text-center no-select'"
        @click="selectFiles"
      >
        <i class="uil uil-camera-plus"></i>
        <small class="d-block">Add Photos</small>
      </span>
      <input
        @change="imagesSelected($event.target.files)"
        hidden
        class="form-control"
        id="generalPhotosInput"
        ref="fileSelector"
        type="file"
        accept="image/*"
        multiple
        :disabled="this.images.length + this.savedImages.length >= 6"
      />
    </div>
    <div class="col-md-9 col-12 mt-3 mt-md-0 text-center">
      <div class="row">
        <div class="col-lg-2 col-6" v-for="(image, index) in savedImages" :key="index">
          <img
            :src="image.result ? image.result : `${$FilePath}/venue/${image.image}`"
            class="facilityImagePreview float-md-left shadow"
          />
          <br />
          <button
            @click="editClickedOnSaved(image.id)"
            type="button"
            class="btn btn-sm btn-outline-success p-0 px-2 mx-1 mt-2 h5"
          >
            <i class="uil uil-edit"></i>
          </button>
          <button
            @click="toBeDeletedId = image.id; toBeDeletedType = 1; deleteModal.show()"
            type="button"
            class="btn btn-sm btn-outline-danger p-0 px-2 mx-1 mt-2 h5"
          >
            <i class="uil uil-trash"></i>
          </button>
        </div>
        <div class="col-lg-2 col-6" v-for="(image, index) in images" :key="index">
          <img
            :src="image.result ? image.result : image.image"
            class="facilityImagePreview float-md-left shadow"
          />
          <br />
          <button
            @click="editClicked(image.id)"
            type="button"
            class="btn btn-sm btn-outline-success p-0 px-2 mx-1 mt-2 h5"
          >
            <i class="uil uil-edit"></i>
          </button>
          <button
            @click="toBeDeletedId = image.id; toBeDeletedType = 0; deleteModal.show()"
            type="button"
            class="btn btn-sm btn-outline-danger p-0 px-2 mx-1 mt-2 h5"
          >
            <i class="uil uil-trash"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- Edit Modal Start -->
  <div
    ref="modal"
    class="modal fade"
    id="edit-modal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded shadow border-0">
        <div class="modal-header border-bottom">
          <h5 class="modal-title" id="LoginForm-title">Crop Image</h5>
          <button
            type="button"
            class="btn btn-icon btn-close"
            data-bs-dismiss="modal"
            id="close-modal"
          >
            <i class="uil uil-times fs-4 text-dark"></i>
          </button>
        </div>
        <div class="modal-body py-3">
          <div class="text-center">
            <div>
              <cropper
                ref="cropper"
                class="cropper"
                :src="toBeEdited.isOld ? `${$FilePath}/venue/${toBeEdited.image}` : toBeEdited.image "
                :defaultPosition="toBeEdited.coordinates ? {left: toBeEdited.coordinates.left, top: toBeEdited.coordinates.top} : undefined"
                :defaultSize="toBeEdited.coordinates ? {height: toBeEdited.coordinates.height, width: toBeEdited.coordinates.width} : undefined"
              />
            </div>
            <div class="row">
              <div class="col-12">
                <label class="form-label float-start mt-2" for="caption">Caption</label>
                <input
                  type="text"
                  id="caption"
                  class="form-control"
                  v-model="toBeEdited.caption"
                  v-on:keydown.enter.prevent="cropAndSave"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            @click="cropAndSave()"
            class="btn btn-outline-primary"
            data-bs-dismiss="modal"
          >Crop and Save</button>
          <button
            type="button"
            role="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Edit Modal End -->
  <!-- Confirm discount Deletion Modal Start -->
  <div
    class="modal fade"
    ref="deleteModal"
    id="confirm-delete-modal"
    tabindex="-1"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded shadow border-0">
        <div class="modal-body py-5">
          <div class="text-center">
            <div
              class="icon d-flex align-items-center justify-content-center bg-soft-danger rounded-circle mx-auto"
              style="height: 95px; width:95px;"
            >
              <h1 class="mb-0">
                <i class="uil uil-trash align-middle"></i>
              </h1>
            </div>
            <div class="mt-4">
              <h4>Delete Image?</h4>
              <p class="text-muted">Are you absolutely sure you want to delete this image?</p>
              <div class="mt-4">
                <a
                  href="javascript:void(0)"
                  @click="handleDelete()"
                  data-bs-dismiss="modal"
                  class="btn btn-outline-danger"
                >Yes, Delete it.</a>
                <a
                  href="javascript:void(0)"
                  data-bs-dismiss="modal"
                  class="btn ms-4 btn-secondary"
                >Cancel</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Confirm discount Deletion Modal End -->
</template>

<script>
import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";

export default {
  props: ["previousImages"],
  emits: {
    imageAdded: "savedImages, images",
    savedImageDeleted: "savedImagesToBeDeleted",
    newImageDeleted: "newImageDeletedId",
  },
  components: {
    Cropper,
  },
  data() {
    return {
      images: [],
      toBeEdited: {},
      toBeDeletedId: null,
      toBeDeletedType: 0, // 0 for new 1 for saved image
      savedImages: [],
    };
  },
  computed: {
    editModal() {
      return new bootstrap.Modal(this.$refs.modal, {
        keyboard: false,
      });
    },
    deleteModal() {
      return new bootstrap.Modal(this.$refs.deleteModal, {
        keyboard: false,
      });
    },
  },
  methods: {
    selectFiles() {
      this.$refs.fileSelector.click();
    },
    imagesSelected(fileList) {
      if (fileList.length == 0) {
        return;
      }
      if (fileList.length + this.savedImages.length + this.images.length > 6) {
        alert(
          "You can not upload more than 6 photos in total. Delete the previous images, in order to add more."
        );
        document.getElementById("generalPhotosInput").value = null;
        return;
      }
      Array.from(fileList).forEach((file) => {
        let id = Math.floor(Math.random() * (999999 - 888888 + 1) + 999999); // generate a new ID for new images, using this we will track them and modify them, can not use index,
        this.showImage(file, id);
      });
      // this.v$.venue.listingBanner.$touch();
      return;
    },
    showImage(file, id) {
      var reader = new FileReader();
      if (reader) {
        reader.onload = (e) => {
          let image = {
            image: e.target.result, // store result in image, because user might not crop the image.
            result: e.target.result,
            caption: "",
            isNew: true,
            id: id,
          };
          this.images.push(image);
          this.$emit("imageAdded", image);
        };
      }
      reader.readAsDataURL(file);
    },

    editClicked(id) {
      let index = this.images.findIndex((image) => image.id == id);
      this.toBeEdited = this.images[index];
      this.showEditModal();
    },
    editClickedOnSaved(id) {
      let index = this.savedImages.findIndex((image) => image.id == id);
      this.toBeEdited = this.savedImages[index];
      let img = this.savedImages[index].image;
      this.toBeEdited.isOld = true;
      this.showEditModal();
    },
    handleDelete() {
      if (this.toBeDeletedType == 0) {
        this.deleteClicked(this.toBeDeletedId);
      } else {
        this.deleteClickedOnSaved(this.toBeDeletedId);
      }
    },
    deleteClicked(id) {
      const index = this.images.findIndex((image) => image.id == id);
      this.images.splice(index, 1);
      this.$emit("newImageDeleted", id);
    },
    deleteClickedOnSaved(id) {
      const index = this.savedImages.findIndex((image) => image.id == id);
      this.savedImages.splice(index, 1);
      this.$emit("savedImageDeleted", id);
    },
    cropAndSave() {
      this.editModal.hide();
      const { coordinates, canvas } = this.$refs.cropper.getResult();
      let image = {};
      if (this.toBeEdited.isOld) {
        const index = this.savedImages.findIndex(
          (image) => image.id == this.toBeEdited.id
        );
        this.savedImages[index].result = canvas.toDataURL();
        this.savedImages[index].caption = this.toBeEdited.caption;
        this.savedImages[index].coordinates = coordinates;
        this.savedImages[index].edited = true;
        image = this.savedImages[index];
      } else {
        const index = this.images.findIndex(
          (image) => image.id == this.toBeEdited.id
        );
        this.images[index].result = canvas.toDataURL();
        this.images[index].caption = this.toBeEdited.caption;
        this.images[index].coordinates = coordinates;
        image = this.images[index];
      }
      this.$emit("imageAdded", image);

      this.caption = "";
    },
    showEditModal() {
      this.editModal.show();
    },
  },
  mounted() {
    this.savedImages = this.previousImages;
    const { cropper, modal } = this.$refs;
    modal.addEventListener("shown.bs.modal", () => {
      if (cropper) {
        cropper.refresh();
      }
    });
  },
};
</script>

<style>
.facilityImagePreview {
  width: 110px;
  max-height: 84px;
  border-radius: 10px;
}
.customFileSelector {
  border: 1px solid #e3e3e3;
  border-radius: 1rem;
  cursor: pointer;
}
.customFileSelector .uil {
  font-size: 2rem;
  color: #9ba3af;
}
.customFileSelector small {
  color: #9ba3af;
}
.customDisabled {
  cursor: not-allowed;
}
</style>
