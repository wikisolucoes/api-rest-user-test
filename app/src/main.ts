import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import BootstrapVue3 from 'bootstrap-vue-3';
import TheMask from 'vue-the-mask';

// Importar bootstrap e bootstrap-vue
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css';

// Importar estilos globais
import './styles/main.scss';

const app = createApp(App);

app.use(BootstrapVue3);
app.use(TheMask);

app.use(router);

app.mount('#app');
