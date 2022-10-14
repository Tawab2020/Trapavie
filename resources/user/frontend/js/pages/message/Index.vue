<template>
  <!-- Chat Start -->
  <section class="section mt-60" id="messages">
    <div class="container mt-lg-3">
      <div class="row">
        <h4 class="mb-3">Messages</h4>
        <div class="col-lg-4 col-12">
          <div class="card border-0 rounded shadow">
            <ul
              class="nav nav-pills nav-justified flex-column flex-sm-row rounded"
              id="pills-tab"
              role="tablist"
            >
              <li class="nav-item">
                <a
                  class="chat-type-nav-link active"
                  id="guest-tab"
                  data-bs-toggle="pill"
                  href="#guest"
                  role="tab"
                  aria-controls="guest"
                  aria-selected="false"
                >
                  <div class="text-center py-2">
                    <h6 class="mb-0">Guest</h6>
                  </div>
                </a>
                <!--end nav link-->
              </li>
              <!--end nav item-->

              <li class="nav-item">
                <a
                  class="chat-type-nav-link"
                  id="host-tab"
                  data-bs-toggle="pill"
                  href="#host"
                  role="tab"
                  aria-controls="host"
                  aria-selected="false"
                >
                  <div class="text-center py-2">
                    <h6 class="mb-0">Host</h6>
                  </div>
                </a>
                <!--end nav link-->
              </li>
              <!--end nav item-->

              <li class="nav-item">
                <a
                  class="chat-type-nav-link"
                  id="all-tab"
                  data-bs-toggle="pill"
                  href="#all"
                  role="tab"
                  aria-controls="all"
                  aria-selected="false"
                >
                  <div class="text-center py-2">
                    <h6 class="mb-0">All</h6>
                  </div>
                </a>
                <!--end nav link-->
              </li>
              <!--end nav item-->
            </ul>
            <!--end nav pills-->

            <div class="tab-content" id="pills-tabContent">
              <div
                class="tab-pane fade show active"
                id="guest"
                role="tabpanel"
                aria-labelledby="guest-tab"
              >
                <a
                  href="#"
                  @click="selectConversation(conversation.id)"
                  class="d-flex chat-list active p-2 rounded position-relative"
                  v-for="conversation in guestConversations"
                  :class="conversation.id == activeConversation.id ? 'active-chat' : ''"
                  :key="conversation.id"
                >
                  <div class="position-relative">
                    <img
                      :src="`${$FilePath}/user/profiles/${otherUser(conversation.users).image}`"
                      class="avatar avatar-md-sm rounded-circle border shadow"
                      alt
                    />
                  </div>
                  <div class="overflow-hidden flex-1 ms-2">
                    <div class="d-flex justify-content-between">
                      <h6
                        class="text-dark mb-0 d-block"
                      >{{otherUser(conversation.users).fname}} {{otherUser(conversation.users).lname}}</h6>
                      <small class="text-muted">10 Min</small>
                    </div>
                    <div class="text-muted text-truncate">Hello</div>
                  </div>
                </a>
                <span
                  class="d-block my-0 mx-auto text-center"
                  v-if="guestConversations.length <= 0"
                >You have no conversations as a guest.</span>
              </div>
              <!--end teb pane-->

              <div class="tab-pane fade" id="host" role="tabpanel" aria-labelledby="host-tab">
                <a
                  href="#"
                  @click="selectConversation(conversation.id)"
                  class="d-flex chat-list active p-2 rounded position-relative"
                  v-for="conversation in hostConversations"
                  :key="conversation.id"
                  :class="conversation.id == activeConversation.id ? 'active-chat' : ''"
                >
                  <div class="position-relative">
                    <img
                      :src="`${$FilePath}/user/profiles/${otherUser(conversation.users).image}`"
                      class="avatar avatar-md-sm rounded-circle border shadow"
                      alt
                    />
                  </div>
                  <div class="overflow-hidden flex-1 ms-2">
                    <div class="d-flex justify-content-between">
                      <h6
                        class="text-dark mb-0 d-block"
                      >{{otherUser(conversation.users).fname}} {{otherUser(conversation.users).lname}}</h6>
                      <small class="text-muted">10 Min</small>
                    </div>
                    <div class="text-muted text-truncate">Hello</div>
                  </div>
                </a>
                <span
                  class="d-block my-0 mx-auto text-center"
                  v-if="hostConversations.length <= 0"
                >You have no conversations as a host.</span>
              </div>
              <!--end teb pane-->

              <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                <a
                  href="#"
                  @click="selectConversation(conversation.id, true)"
                  class="d-flex chat-list active p-2 rounded position-relative"
                  v-for="conversation in conversations"
                  :key="conversation.id"
                  :class="conversation.id == activeConversation.id ? 'active-chat' : ''"
                >
                  <div class="position-relative">
                    <img
                      :src="`${$FilePath}/user/profiles/${otherUser(conversation.users).image}`"
                      class="avatar avatar-md-sm rounded-circle border shadow"
                      alt
                    />
                  </div>
                  <div class="overflow-hidden flex-1 ms-2">
                    <div class="d-flex justify-content-between">
                      <h6
                        class="text-dark mb-0 d-block"
                      >{{otherUser(conversation.users).fname}} {{otherUser(conversation.users).lname}}</h6>
                      <small class="text-muted">10 Min</small>
                    </div>
                    <div class="text-muted text-truncate">Hello</div>
                  </div>
                </a>
                <span
                  class="d-block my-0 mx-auto text-center"
                  v-if="conversations.length <= 0"
                >You have no conversations.</span>
              </div>
              <!--end teb pane-->
            </div>
            <!--end tab content-->
            <div class="p-2 chat chat-list" data-simplebar style="max-height: 360px;"></div>
          </div>
        </div>
        <div class="col-lg-8 col-12">
          <div class="card chat chat-person border-0 shadow rounded">
            <div class="d-flex justify-content-between border-bottom p-4">
              <div class="d-flex">
                <img
                  v-if="activeConversation.id"
                  :src="`${$FilePath}/user/profiles/${otherUser(activeConversation.users).image}`"
                  class="avatar avatar-md-sm rounded-circle border shadow"
                  alt
                />
                <div class="overflow-hidden ms-3 d-flex align-items-center">
                  <a
                    v-if="activeConversation.id"
                    href="#"
                    class="text-dark mb-0 h6 d-block text-truncate"
                  >{{otherUser(activeConversation.users).fname + " " + otherUser(activeConversation.users).lname}}</a>
                </div>
              </div>

              <ul class="list-unstyled mb-0">
                <li class="dropdown dropdown-primary list-inline-item">
                  <button
                    type="button"
                    class="btn btn-icon btn-pills btn-soft-primary dropdown-toggle p-0"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="uil uil-ellipsis-h"></i>
                  </button>
                  <div
                    class="dropdown-menu dd-menu dropdown-menu-end bg-white shadow border-0 mt-3 py-3"
                  >
                    <a class="dropdown-item text-dark" href="#">
                      <span class="mb-0 d-inline-block me-1">
                        <i class="uil uil-user align-middle h6"></i>
                      </span> Profile
                    </a>
                    <a class="dropdown-item text-dark" href="#">
                      <span class="mb-0 d-inline-block me-1">
                        <i class="uil uil-setting align-middle h6"></i>
                      </span> Profile Settings
                    </a>
                    <a class="dropdown-item text-dark" href="#">
                      <span class="mb-0 d-inline-block me-1">
                        <i class="uil uil-trash align-middle h6"></i>
                      </span> Delete
                    </a>
                  </div>
                </li>
              </ul>
            </div>

            <ul
              class="p-4 list-unstyled mb-0 chat"
              ref="chat"
              id="chat"
              data-simplebar
              style="background: url('/assets/user/frontend/images/chat-bg.png') center center; max-height: 500px;"
            >
              <template v-for="message in activeConversation.messages" :key="message.id">
                <li v-if="message.user_id != this.user.id" :key="dateTimeKey">
                  <div class="d-inline-block">
                    <div class="d-flex chat-type mb-3">
                      <div class="position-relative">
                        <img
                          :src="`${$FilePath}/user/profiles/${otherUser(activeConversation.users).image}`"
                          class="avatar avatar-md-sm rounded-circle border shadow"
                          alt
                        />
                      </div>

                      <div class="chat-msg" style="max-width: 500px;">
                        <p
                          class="text-muted small msg px-3 py-2 bg-light rounded mb-1"
                        >{{message.body}}</p>
                        <small class="text-muted msg-time">
                          <i class="uil uil-clock-nine me-1"></i>
                          {{ $DateTime.fromISO(message.created_at).toRelative() }}
                        </small>
                      </div>
                    </div>
                  </div>
                </li>

                <li v-else class="chat-right" :key="dateTimeKey">
                  <div class="d-inline-block">
                    <div class="d-flex chat-type mb-3">
                      <div class="position-relative chat-user-image">
                        <img
                          :src="`${$FilePath}/user/profiles/${user.image}`"
                          class="avatar avatar-md-sm rounded-circle border shadow"
                          alt
                        />
                      </div>

                      <div class="chat-msg" style="max-width: 500px;">
                        <p
                          class="text-muted small msg px-3 py-2 bg-light rounded mb-1"
                        >{{message.body}}</p>
                        <small class="text-muted msg-time">
                          <i class="uil uil-clock-nine me-1"></i>
                          {{ $DateTime.fromISO(message.created_at).toRelative() }}
                        </small>
                      </div>
                    </div>
                  </div>
                </li>
              </template>
              <i id="lastMessage"></i>
            </ul>

            <div class="p-2 rounded-bottom shadow">
              <form @submit.prevent="sendMessage()">
                <div class="row">
                  <div class="col">
                    <input
                      type="text"
                      v-model="message.body"
                      class="form-control"
                      placeholder="Enter Message..."
                    />
                  </div>
                  <div class="col-auto">
                    <button
                      class="btn btn-icon btn-primary"
                      type="submit"
                      :disabled="formSubmitting"
                    >
                      <i class="uil uil-message"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!--end col-->
      </div>
      <!--end row-->
    </div>
    <!--end container-->
  </section>
  <!--end section-->
  <!-- Profile End -->
</template>

<script>
import Pusher from "pusher-js";
export default {
  data() {
    return {
      message: {
        body: "",
      },
      conversations: [],
      activeConversation: {},
      formSubmitting: false,
      chatKey: 0,
      tobeMessaged: {},
      isFromAll: false,
    };
  },
  methods: {
    getConversations() {
      axios.get("conversations").then((response) => {
        this.conversations = response.data;

        // if there is a user in params, check if already had conv
        let convPresent = false;
        if (this.$route.params.user) {
          this.conversations.forEach((conv) => {
            if (this.otherUser(conv.users).id == this.$route.params.user) {
              this.activeConversation = conv;
              convPresent = true;
              return;
            }
          });
          if (convPresent) {
            return;
          }
          // if the user doesnt not have a previous conversation create new one
          this.user.pivot = { type: this.$route.params.senderType };
          let newConv = {
            id: -1, // meaning that it does not exisit in DB
            messages: [],
            pivot: {
              type: this.$route.params.senderType,
            },
            users: [this.user, this.tobeMessaged],
          };
          this.conversations.push(newConv);
          this.activeConversation = newConv;
          return;
        }

        // if no user in params, set the first conversation as active
        this.activeConversation = this.conversations[0];
      });
    },
    selectConversation(id, isFromAll = false) {
      isFromAll ? (this.isFromAll = true) : (this.isFromAll = false);
      const index = this.conversations.findIndex((conv) => conv.id == id);
      this.activeConversation = this.conversations[index];
    },
    sendMessage() {
      this.formSubmitting = true;
      if (this.activeConversation.id == -1) {
        let cnv = {
          users: [
            this.activeConversation.users[0].id, // this user
            this.activeConversation.users[1].id, // user being messaged to
          ],
          senderType: this.activeConversation.pivot.type,
          receiverType: this.$route.params.receiverType,
        };
        axios.post("createConversation", cnv).then((response) => {
          this.activeConversation = response.data;
          this.message.conversation_id = this.activeConversation.id;
          this.message.user_id = this.user.id;
          axios
            .post("sendMessage", this.message)
            .then((response) => {
              this.message.body = "";
              this.formSubmitting = false;
            })
            .catch((error) => {
              console.error(error);
              this.formSubmitting = false;
            });
        });
      } else {
        this.message.conversation_id = this.activeConversation.id;
        this.message.user_id = this.user.id;
        axios
          .post("sendMessage", this.message)
          .then((response) => {
            this.message.body = "";
            this.formSubmitting = false;
          })
          .catch((error) => {
            console.error(error);
            this.formSubmitting = false;
          });
      }
    },

    // this function is for displaying the other user in the conversation
    otherUser(users) {
      const index = users.findIndex((user) => user.id != this.user.id);
      return users[index];
    },

    // this function is for displaying the current user in the conversation
    thisUser(users) {
      const index = users.findIndex((user) => user.id == this.user.id);
      return users[index];
    },

    connectPusher() {
      Pusher.logToConsole = true;

      var pusher = new Pusher("c7d1108cc1c63650facd", {
        cluster: "us2",
      });

      var channel = pusher.subscribe(
        `conversation.${this.activeConversation.id}`
      );
      const active = this.activeConversation;
      channel.bind("message-sent", function (data) {
        active.messages.push(data.message);
      });
    },
    scrollToBottomOfConversation() {
      let container = document.querySelector(
        "#chat .simplebar-content-wrapper"
      );
      let topPos = this.lastMessage.offsetTop;
      container.scrollTo({ top: topPos, behavior: "smooth" });
    },

    showTab(id) {
      let el = document.getElementById(id);
      let tab = new bootstrap.Tab(el);
      tab.show();
    },
  },
  computed: {
    user() {
      return this.$store.getters.user.user;
    },
    activeLanguage() {
      return this.$store.getters.activeLocale;
    },
    lastMessage() {
      return document.getElementById("lastMessage");
    },
    guestConversations() {
      return this.conversations.filter(
        (conversation) => conversation.pivot.type == 0
      );
    },
    hostConversations() {
      return this.conversations.filter(
        (conversation) => conversation.pivot.type == 1
      );
    },
    activeConversationId() {
      return this.activeConversation.id;
    },
    dateTimeKey() {
      return this.$store.getters.dateTimeKey;
    },
  },
  watch: {
    activeConversation: {
      deep: true,
      handler: function () {
        setTimeout(() => {
          this.scrollToBottomOfConversation();
        }, 1);
      },
    },
    activeConversationId: function () {
      this.connectPusher();
      if (!this.isFromAll) {
        if (this.activeConversation.pivot.type == 0) {
          this.showTab("guest-tab");
        } else {
          this.showTab("host-tab");
        }
      }
    },
  },
  mounted() {
    if (this.$route.params.user) {
      axios.get(`userInfo/${this.$route.params.user}`).then((response) => {
        this.tobeMessaged = response.data;
        this.getConversations();
      });
    } else {
      this.getConversations();
    }
  },
};
</script>

<style>
.chat-type-nav-link {
  color: #212529 !important;
}
.chat-type-nav-link.active h6 {
  position: relative !important;
}
#messages .nav-pills {
  background: #f6f6f6 !important;
}
.nav-pills a.active {
  display: inline-block;
  border-bottom: 2px solid black;
}
.active-chat {
  background-color: #f6f6f6 !important;
}
</style>
