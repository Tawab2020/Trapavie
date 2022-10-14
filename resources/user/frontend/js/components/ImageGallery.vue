<template>
  <div class="image-gallery-back" :class="isOpen ? 'open' : ''"></div>
  <div class="image-gallery p-4" :class="isOpen ? 'open' : ''">
    <div class="d-flex justify-content-end mt-5">
      <i class="uil uil-times-circle pointer" style="font-size: 1.8rem;" @click="$emit('close')"></i>
    </div>

    <div class="container">
      <div id="category-list" class="row justify-content-center py-3">
        <div class="col-12 filters-group-wrap">
          <div class="filters-group">
            <ul
              class="container-filter list-inline mb-0 filter-options text-center"
              v-if="categories.length"
            >
              <li
                class="list-inline-item categories-name border text-dark rounded active"
                data-group="all"
              >All</li>
              <li
                v-for="(category, index) in categories"
                :key="index"
                class="list-inline-item categories-name border text-dark rounded"
                :data-group="category.code"
              >{{category.title}}</li>
            </ul>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->

      <div id="grid" class="row">
        <template v-for="(category, index) in categories" :key="index">
          <div
            v-for="image in category.images"
            :key="image.id"
            class="col-lg-4 col-md-6 col-12 mt-4 pt-2 picture-item"
            :data-groups="JSON.stringify([category.code])"
          >
            <div
              class="card blog border-0 work-container work-classic shadow rounded-md overflow-hidden"
            >
              <a :href="`${$FilePath}/venue/${image.image}`" class="lightbox" title>
                <img
                  :src="`${$FilePath}/venue/${image.image}`"
                  class="img-fluid"
                  :alt="image.caption"
                />
              </a>
              <!-- <img :src="`${$FilePath}/venue/${image.image}`" class="img-fluid work-image" alt /> -->
            </div>
          </div>
          <!--end col-->
        </template>
        <div class="col-1 my-sizer-element"></div>
      </div>
      <!--end row-->
    </div>
    <!--end container-->
  </div>
</template>

<script>
export default {
  data() {
    return {
      refreshKey: 0,
    };
  },

  props: {
    isOpen: {
      type: Boolean,
      default: false,
    },
    categories: {
      type: Array,
      default: false,
    },
  },
  emits: {
    close: "",
  },
  mounted() {
    // document.addEventListener("keyup", this.handleKeyup);
    const tobii = new Tobii();

    setTimeout(() => {
      this.initializeShuffle();
    }, 100);
  },

  methods: {
    handleKeyup() {
      if (this.isOpen && event.keyCode === 27) {
        this.$emit("close");
      }
    },
    initializeShuffle() {
      var Shuffle = window.Shuffle;

      class Demo {
        constructor(element) {
          if (element) {
            this.element = element;
            this.shuffle = new Shuffle(element, {
              itemSelector: ".picture-item",
              sizer: element.querySelector(".my-sizer-element"),
            });

            // Log events.
            this.addShuffleEventListeners();
            this._activeFilters = [];
            this.addFilterButtons();
          }
        }

        /**
         * Shuffle uses the CustomEvent constructor to dispatch events. You can listen
         * for them like you normally would (with jQuery for example).
         */
        addShuffleEventListeners() {
          this.shuffle.on(Shuffle.EventType.LAYOUT, (data) => {
            console.log("layout. data:", data);
          });
          this.shuffle.on(Shuffle.EventType.REMOVED, (data) => {
            console.log("removed. data:", data);
          });
        }

        addFilterButtons() {
          const options = document.querySelector(".filter-options");
          if (!options) {
            return;
          }

          const filterButtons = Array.from(options.children);
          const onClick = this._handleFilterClick.bind(this);
          filterButtons.forEach((button) => {
            button.addEventListener("click", onClick, false);
          });
        }

        _handleFilterClick(evt) {
          const btn = evt.currentTarget;
          const isActive = btn.classList.contains("active");
          const btnGroup = btn.getAttribute("data-group");

          this._removeActiveClassFromChildren(btn.parentNode);

          let filterGroup;
          if (isActive) {
            btn.classList.remove("active");
            filterGroup = Shuffle.ALL_ITEMS;
          } else {
            btn.classList.add("active");
            filterGroup = btnGroup;
          }

          this.shuffle.filter(filterGroup);
        }

        _removeActiveClassFromChildren(parent) {
          const { children } = parent;
          for (let i = children.length - 1; i >= 0; i--) {
            children[i].classList.remove("active");
          }
        }
      }
      new Demo(document.getElementById("grid"));
    },
  },

  watch: {
    isOpen(newValue, oldValue) {
      if (newValue) {
        document.body.style.overflow = "hidden";
      } else {
        document.body.style.overflow = "auto";
      }
      this.refreshKey++;
    },
  },
};
</script>

<style scoped lang="scss">
.image-gallery-back {
  width: 100%;
  min-height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  background-color: rgba(0, 0, 0, 0.5);
  opacity: 0;
  z-index: 9;
  overflow: hidden;
  transition: opacity 0.3s ease-in-out;
  pointer-events: none;
  &.open {
    opacity: 1;
  }
}

.image-gallery {
  width: 100%;
  min-height: 100vh;
  height: 100vh;
  position: fixed;
  border-radius: 14px 14px 0 0;
  top: 100%;
  left: 0;
  background-color: rgb(255, 255, 255);
  z-index: 10;
  overflow: auto;
  transition: all 0.5s ease;
  &.open {
    top: 40px;
  }
}
#category-list {
  position: -webkit-sticky;
  position: sticky;
  top: 10px;
  z-index: 100;
  background: white;
}
</style>
