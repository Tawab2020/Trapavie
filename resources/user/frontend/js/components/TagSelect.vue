<template>
  <span
    v-for="(item, index) in items"
    :key="index"
    @click="toggle(item)"
    :class="selectedItems.some(i => i.id == item.id) || selectedItem.id == item.id ? 'badge  badge-md bg-custom-success me-2 mt-2' : 'badge badge-md badge-soft-white me-2 mt-2'"
  >{{item.title['en']}}</span>
  <span
    v-for="(item, index) in otherValues"
    :key="index"
    @click="removeOther(item)"
    class="badge badge-md bg-custom-success me-2 mt-2 other-badge"
  >{{item.title}}</span>
  <span
    v-if="showOther"
    @click="showOtherInput = !showOtherInput"
    class="badge badge-md badge-soft-white me-2 mt-2"
  >Other</span>
  <div v-if="showOtherInput" class="row mt-2">
    <div class="col-md-3 col-8">
      <input
        v-model="otherValue"
        type="text"
        class="form-control"
        v-on:keydown.enter.prevent="addOther"
      />
    </div>
    <div class="col-md-1 col-4">
      <button @click="addOther" type="button" class="btn btn-success">Add</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["savedItems", "options", "hasNewOptions", "single", "savedItem"],
  data() {
    return {
      selectedItems: [],
      selectedItem: {}, // when single prop is true
      showOther: this.hasNewOptions,
      showOtherInput: false,
      otherValue: "",
      otherValues: [], // this is just for showing the new values.
    };
  },
  computed: {
    items() {
      return this.options;
    },
    oldItems() {
      return this.savedItems;
    },
    user() {
      return this.$store.getters.user.user;
    },
  },
  emits: {
    itemSelected: "selectedItems, selectedItem",
  },
  methods: {
    removeItem(item) {
      // change this to single remove
      let occurances = this.selectedItems.filter((itm) => itm.id == item.id);
      occurances.forEach((occ) => {
        const index = this.selectedItems.findIndex((itm) => itm.id == occ.id);

        this.selectedItems.splice(index, 1);
      });
    },
    addItem(item) {
      this.selectedItems.push(item);
    },
    toggle(item) {
      if (this.single) {
        this.selectedItem = item;
        this.otherValues = [];
        this.$emit("itemSelected", this.selectedItem);
        return;
      }
      if (this.selectedItems.some((i) => i.id == item.id)) {
        this.removeItem(item);
      } else {
        this.addItem(item);
      }
      this.$emit("itemSelected", this.selectedItems);
    },
    addOther() {
      const index = this.items.findIndex(
        (item) => item.title["en"] == this.otherValue
      );
      if (index >= 0) {
        if (this.single) {
          this.selectedItem = this.items[index];
        } else {
          this.selectedItems.push(this.items[index]);
          this.otherValue = "";
        }
        return;
      }
      let newItem = {
        title: this.otherValue,
        user_id: this.user.id,
        id: 0,
      };
      if (this.single) {
        this.selectedItem = newItem;
        this.otherValues.push(this.selectedItem);
        this.showOtherInput = false;
        this.$emit("itemSelected", this.selectedItem);

        return;
      }
      this.selectedItems.push(newItem);
      this.otherValues.push(newItem);
      this.$emit("itemSelected", this.selectedItems);

      this.otherValue = "";
    },
    removeOther(other) {
      if (this.single) {
        this.selectedItem = [];
        this.$emit("itemSelected", this.selectedItem);
      }
      var index = this.otherValues.findIndex(
        (item) => item.title.en == other.title.en
      );
      this.otherValues.splice(index, 1);
      index = this.selectedItems.findIndex(
        (item) => item.title.en == other.title.en
      );
      this.selectedItems.splice(index, 1);
      this.$emit("itemSelected", this.selectedItems);
    },
  },
  watch: {
    savedItem: function () {
      this.selectedItem = this.savedItem;
    },
    savedItems: function () {
      this.selectedItems = Array.from(this.savedItems);
    },
  },
  mounted() {
    if (this.single) {
      this.selectedItem = this.savedItem;
      return;
    }
    this.savedItems.forEach((savedItem) => {
      this.selectedItems.push(savedItem);
    });
  },
};
</script>

<style>
</style>
