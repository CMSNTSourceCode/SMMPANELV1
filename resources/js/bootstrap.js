import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-CSRF-TOKEN"] = `${webData?.csrfToken}`;
window.axios.defaults.headers.common["X-Requested-With"] = "application/json";

// Add a request interceptor
axios.interceptors.request.use(
    function (config) {
        // Do something before request is sent
        config.headers["Authorization"] = `Bearer ${userData?.access_token}`;
        return config;
    },
    function (error) {
        // Do something with request error
        return Promise.reject(error);
    }
);
