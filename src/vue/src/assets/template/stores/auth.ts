import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/assets/template/core/services/ApiService";
import JwtService from "@/assets/template/core/services/JwtService";

type Errors = string | Record<string, any>;
export interface User {
  id: string | null;
  employee_id: string | null;
  username: string;
  password: string;
  api_token: string;
  user_class_id: string;
  party_number: string | null;
  party_id: string | null;
  accesses: string[] | null;
  details: object | null;
}

export const useAuthStore = defineStore("auth", () => {
  const errors = ref<Errors>({});
  const user = ref<User>({} as User);
  const isAuthenticated = ref(!!JwtService.getToken());
  const userLoggedData = ref(JwtService.getUser());


  function setAuth(authUser: User) {
    isAuthenticated.value = true;
    user.value = authUser;
    userLoggedData.value = authUser;
    errors.value = '';
    JwtService.saveToken(user.value.api_token);
    JwtService.saveUser(user.value)
  }

  function setError(error: Errors) {
    errors.value = error;
  }

  function purgeAuth() {
    isAuthenticated.value = false;
    user.value = {} as User;
    userLoggedData.value = {};
    errors.value = '';
    JwtService.destroyToken();
    JwtService.destroyUser();
  }

  function login(credentials: User) {
    return ApiService.post("login", credentials)
      .then(({ data }) => {
        setAuth(data.user);
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function impersonate(credentials: User) {
    return ApiService.post("impersonate", credentials)
      .then(({ data }) => {
        setAuth(data.user);
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function logout() {
    purgeAuth();
    return ApiService.post("logout", null)
      .then(({ data }) => {
        console.log(data);
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function register(credentials: User) {
    return ApiService.post("register", credentials)
      .then(({ data }) => {
        setAuth(data);
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function forgotPassword(email: string) {
    return ApiService.post("forgot-password", email)
      .then(() => {
        setError({});
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function verifyAuth() {
    if (JwtService.getToken()) {
      ApiService.setHeader();
      ApiService.post("verify-token", { api_token: JwtService.getToken() })
        .then(({ data }) => {
          setAuth(data.user);
        })
        .catch(({ response }) => {
          setError(response.data.errors);
          purgeAuth();
        });
    } else {
      purgeAuth();
    }
  }

  function getUser() {
    return userLoggedData.value;
  }

  return {
    errors,
    user,
    isAuthenticated,
    userLoggedData,
    login,
    impersonate,
    logout,
    register,
    forgotPassword,
    verifyAuth,
    setAuth,
    getUser,

  };
});
