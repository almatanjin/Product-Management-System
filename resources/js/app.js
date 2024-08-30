// import "../css/app.css"
// import {createApp} from "vue";
// import router from "@/routes/index.js";
// import App from "./App.vue"

//     createApp(App)
//     .use(router)
//     .mount("#app")


import "../css/app.css";
import { createApp } from "vue";
import router from "@/routes/index.js";
import App from "./App.vue";
import CreateProduct from "./pages/CreateProduct.vue"; // Adjust the path as needed

const app = createApp(App);

app.component('CreateProduct', CreateProduct); // Register the component globally

app.use(router).mount("#app");