import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import UserGrid from '../pages/user/UserGrid.vue';
import UserForm from '../pages/user/UserForm.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'Home',
    component: UserGrid,
  },
  {
    path: '/create',
    name: 'UserCreate',
    component: UserForm,
  },
  {
    path: '/edit/:id',
    name: 'UserEdit',
    component: UserForm,
    props: true,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
