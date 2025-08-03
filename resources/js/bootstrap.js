import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//追記した部分
// axios.defaults.baseURL = 'http://localhost:8000'; // LaravelのAPIサーバーURL
// axios.defaults.withCredentials = true;
