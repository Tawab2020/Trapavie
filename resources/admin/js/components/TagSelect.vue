<template>
  <span
    v-for="(item, index) in items"
    :key="index"
    @click="toggle(item)"
    :class="selectedItems.some(i => i.id == item.id) ? 'badge badge-md bg-custom-success me-2' : 'badge badge-md badge-soft-white me-2'"
    style="color: red"
  >{{item.title['en']}}</span>
</template>

<script>
export default {
  props: ["savedItems", "options"],
  data() {
    return {
      selectedItems: [],
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
    itemSelected: "selectedItems",
  },
  methods: {
    removeItem(item) {
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
      if (this.selectedItems.some((i) => i.id == item.id)) {
        this.removeItem(item);
      } else {
        this.addItem(item);
      }
      this.$emit("itemSelected", this.selectedItems);
    },
  },
  watch: {
    savedItems: function () {
      this.selectedItems = Array.from(this.savedItems);
    },
  },
  mounted() {
    this.savedItems.forEach((savedItem) => {
      this.selectedItems.push(savedItem);
    });
  },
};
</script>

<style>
.badge {
  display: inline-block;
  padding: 0.35em 0.65em;
  font-size: 0.75em;
  font-weight: 700;
  line-height: 1;
  color: #fff;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.25rem;
}
.badge-md {
  cursor: pointer;
  letter-spacing: 0.5px;
  padding: 10px 14px;
  font-weight: 600;
  font-size: 14px;
  line-height: 11px;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: -moz-none;
  -o-user-select: none;
  user-select: none;
  white-space: pre-wrap;
}
.badge-soft-white {
  background-color: rgba(255, 255, 255, 0.1) !important;
  border: 1px solid rgba(90, 109, 144, 0.1) !important;
  color: #5a6d90 !important;
}
.bg-custom-success {
  border: 1px solid rgb(46 202 139 / 50%) !important;
  color: #2eca8b !important;
}
</style>
