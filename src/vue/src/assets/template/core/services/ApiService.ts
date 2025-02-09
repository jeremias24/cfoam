import type { App } from "vue";
import type { AxiosResponse } from "axios";
import axios from "axios";
import VueAxios from "vue-axios";
import JwtService from "@/assets/template/core/services/JwtService";

/**
 * @description Service to call HTTP requests via Axios for both Laravel and Python APIs
 */
class ApiService {
  /**
   * @description Property to share Vue instance
   */
  public static vueInstance: App;

  /**
   * @description Separate Axios instance for Python API
   */
  private static pythonAxiosInstance = axios.create({
    baseURL: import.meta.env.VITE_APP_AI_URL, // Python API base URL
    headers: {
      Accept: "application/json",
    },
  });

  /**
   * @description Initialize Vue Axios for Laravel API
   */
  public static init(app: App<Element>) {
    ApiService.vueInstance = app;
    ApiService.vueInstance.use(VueAxios, axios);
    ApiService.vueInstance.axios.defaults.baseURL = import.meta.env.VITE_APP_API_URL; // Laravel API base URL
  }

  /**
   * @description Set default headers for Laravel API
   */
  public static setHeader(): void {
    ApiService.vueInstance.axios.defaults.headers.common["Authorization"] = `Bearer ${JwtService.getToken()}`;
    ApiService.vueInstance.axios.defaults.headers.common["Accept"] = "application/json";
  }

  /**
   * @description Send a POST HTTP request to Laravel API
   * @param resource: string
   * @param params: AxiosRequestConfig
   * @returns Promise<AxiosResponse>
   */
  public static post(resource: string, params: any): Promise<AxiosResponse> {
    return ApiService.vueInstance.axios.post(`${resource}`, params);
  }

  /**
   * @description Send a GET HTTP request to Laravel API
   * @param resource: string
   * @param slug: string
   * @returns Promise<AxiosResponse>
   */
  public static get(resource: string, slug = "" as string): Promise<AxiosResponse> {
    return ApiService.vueInstance.axios.get(`${resource}/${slug}`);
  }

  /**
   * @description Send a POST HTTP request to Python API
   * @param resource: string - Python API endpoint
   * @param params: any - Payload for the request
   * @returns Promise<AxiosResponse>
   */
  public static aiPost(resource: string, params: any): Promise<AxiosResponse> {
    return ApiService.pythonAxiosInstance.post(resource, params);
  }

  /**
   * @description Send a GET HTTP request to Python API
   * @param resource: string - Python API endpoint
   * @returns Promise<AxiosResponse>
   */
  public static aiGet(resource: string): Promise<AxiosResponse> {
    return ApiService.pythonAxiosInstance.get(resource);
  }
}

export default ApiService;
