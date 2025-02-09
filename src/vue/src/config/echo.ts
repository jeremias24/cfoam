import axios, { AxiosError, AxiosResponse } from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

declare global {
    interface Window {
        Pusher: typeof Pusher;
    }
}

interface IAuthChannel {
    name: string;
}

window.Pusher = Pusher;

const authorizer = (channel: IAuthChannel) => {
    return {
        authorize: (socketId: string, callback: (arg0: boolean, arg1: AxiosError | AxiosResponse) => void) => {
            axios
                .post("/broadcasting/auth", {
                    socket_id: socketId,
                    channel_name: channel.name,
                })
                .then((response) => {
                    callback(false, response.data);
                })
                .catch((err: unknown) => {
                    const error = err as AxiosError
                    callback(true, error);
                });
        },
    };
}

const echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    authorizer,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws"],
});

export default echo;
