<template>
  <!--begin::Chat drawer-->
  <div
    id="kt_drawer_chat"
    class="bg-body"
    data-kt-drawer="true"
    data-kt-drawer-name="chat"
    data-kt-drawer-activate="true"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'300px', 'md': '500px'}"
    data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_drawer_chat_toggle"
    data-kt-drawer-close="#kt_drawer_chat_close"
  >
    <!--begin::Messenger-->
    <div class="card w-100" id="kt_drawer_chat_messenger">
      <!--begin::Card header-->
      <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
        <!--begin::Title-->
        <div class="card-title">
          <!--begin::User-->
          <div class="d-flex justify-content-center flex-column me-3">
            <a
              href="#"
              class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1"
              > {{chatbotName}} </a
            >

            <!--begin::Info-->
            <div class="mb-0 lh-1">
              <span
                class="badge badge-success badge-circle w-10px h-10px me-1"
              ></span>
              <span class="fs-7 fw-semibold text-gray-500">Active</span>
            </div>
            <!--end::Info-->
          </div>
          <!--end::User-->
        </div>
        <!--end::Title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
          <!--begin::Menu-->
          <div class="me-2">
            <button
              class="btn btn-sm btn-icon btn-active-icon-primary"
              data-kt-menu-trigger="click"
              data-kt-menu-placement="bottom-end"
              data-kt-menu-flip="top-end"
            >
              <i class="bi bi-three-dots fs-3"></i>
            </button>
            <Dropdown4></Dropdown4>
          </div>
          <!--end::Menu-->

          <!--begin::Close-->
          <div
            class="btn btn-sm btn-icon btn-active-icon-primary"
            id="kt_drawer_chat_close"
          >
            <KTIcon icon-name="cross" icon-class="fs-2x" />
          </div>
          <!--end::Close-->
        </div>
        <!--end::Card toolbar-->
      </div>
      <!--end::Card header-->

      <!--begin::Card body-->
      <div class="card-body" id="kt_drawer_chat_messenger_body">
        <!--begin::Messages-->
        <div
          class="scroll-y me-n5 pe-5"
          ref="messagesRef"
          data-kt-element="messages"
          data-kt-scroll="true"
          data-kt-scroll-activate="true"
          data-kt-scroll-height="auto"
          data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
          data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body"
          data-kt-scroll-offset="0px"
        > 
          
          <template v-for="(item, index) in messages" :key="index">
            <MessageIn
              ref="messagesInRef"
              v-if="item.senderId != userLogged.id"
              :name="item.firstName"
              :text="item.message"
              :image="userLogged.id == 1 ? 'public/media/avatars/300-3.jpg' : 'public/media/avatars/300-25.jpg'"
            ></MessageIn>
            <MessageOut
              ref="messagesOutRef"
              v-if="item.senderId == userLogged.id"
              :name="item.firstName"
              :text="item.message"
              :image="userLogged.id == 1 ? 'public/media/avatars/300-6.jpg' : 'public/media/avatars/300-25.jpg'"
            ></MessageOut>
          </template>

        </div>
        <!--end::Messages-->
      </div>
      <!--end::Card body-->

      <!--begin::Card footer-->
      <div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
        <!--begin::Input-->
        <input
          class="form-control form-control-flush mb-3"
          data-kt-element="input"
          placeholder="Type a message"
          v-model="initFormData.message"
          @keydown.enter="addNewMessage"
          @keyup.enter="addNewMessage"
        />
        <!--end::Input-->

        <!--begin:Toolbar-->
        <div class="d-flex flex-stack">
          <!--begin::Actions-->
          <div class="d-flex align-items-end me-2">
           

            <!--begin::Send-->
            <button
              @click="addNewMessage"
              class="btn btn-primary"
              type="button"
              data-kt-element="send"
            >
              Send
            </button>
            <!--end::Send-->
            </div>
        </div>
        <!--end::Toolbar-->
      </div>
      <!--end::Card footer-->
    </div>
    <!--end::Messenger-->
  </div>
  <!--end::Chat drawer-->
</template>

<script lang="ts">
import { getAssetPath } from "@/assets/template/core/helpers/assets";
import { defineComponent, ref, onMounted, computed } from "vue";
import ApiService from "@/assets/template/core/services/ApiService";
import MixinService from "@/assets/template/core/services/MixinService";
import MessageIn from "@/assets/template/layouts/default-layout/components/messenger-parts/MessageIn.vue";
import MessageOut from "@/assets/template/layouts/default-layout/components/messenger-parts/MessageOut.vue";
import Dropdown4 from "@/assets/template/layouts/default-layout/components/dropdown/Dropdown4.vue";
import constants from "@/config/constants";
import echo from "@/config/echo";

interface KTMessage {
  type: string;
  name?: string;
  image: string;
  time: string;
  text: string;
}

export default defineComponent({
  name: "upgrade-to-pro",
  components: {
    MessageIn,
    MessageOut,
    Dropdown4,
  },
  props: {
    data: { type: Object, required: true, default: {} },
  },
  setup(props) {
    const messagesRef = ref<null | HTMLElement>(null);
    const messagesInRef = ref<null | HTMLElement>(null);
    const messagesOutRef = ref<null | HTMLElement>(null);
    const { getMessageChannels, messageEvents } = constants;
    const userLogged = ref(props.data);
    const messages = ref<Array<KTMessage>>([]);

    const initDefaultFormData = {
      id: null,
      message: "",
    };

    const chatbotName = computed(() => {
      return import.meta.env.VITE_APP_CHATBOT_NAME;
    });
    const initFormData = ref(Object.assign({}, initDefaultFormData));

    onMounted(async ()=> {
      echo
        .private(getMessageChannels().message)
        .listen(messageEvents.sent, (data: string) => {
          messages.value.push({
              senderId: data.message.senderId,
              message: data.message.message,
              firstName: data.message.firstName
            }
          );
        });  
      });

    const addNewMessage = () => {

      initFormData.value.senderId = userLogged.value.id;
      initFormData.value.receiverId = userLogged.value.id;

      if (!initFormData.value.message || initFormData.value.message == "") {
        return;
      }

      setTimeout(() => {
        // Assuming data contains the saved message object from your API
        messages.value.push({
          senderId: initFormData.value.senderId,
          receiverId: initFormData.value.senderId,
          message: initFormData.value.message,
          firstName: initFormData.value.firstName // Adjust according to your API response
        });

        setTimeout(() => {
          if (messagesRef.value) {
            messagesRef.value.scrollTop = messagesRef.value.scrollHeight;
          }
        }, 1);
      }, 2000);

      ApiService.post("chat-messages", MixinService.serialize(initFormData.value))
        .then(({ data }) => {
          initFormData.value.message = "";
          setTimeout(() => {
            if (messagesRef.value) {
              messagesRef.value.scrollTop = messagesRef.value.scrollHeight;
            }
          }, 1);
          })
          .catch(({ error }) => {
            console.log(error);
        });
    };
    return {
      messages,
      messagesRef,
      initFormData,
      addNewMessage,
      messagesInRef,
      messagesOutRef,
      getAssetPath,
      userLogged,
      chatbotName
    };
  },
});
</script>
