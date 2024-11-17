<template>
  <b-container fluid>
    <b-card header-tag="header" header-bg-variant="dark" header-text-variant="white" header-border-variant="dark" border-variant="dark" class="user-grid">
      <template #header>
        <b-row>
          <b-col>
            <h4 class="mb-0">Cadastro de Usuários</h4>
          </b-col>
          <b-col class="text-end">
            <b-button @click="createUser" variant="primary" size="sm" title="Cadastrar novo usuário">
              <i class="fa-solid fa-plus"></i>
            </b-button>
          </b-col>
        </b-row>
      </template>
      <b-overlay :show="loading" rounded opacity="0.6" spinner-small spinner-variant="primary">
        <b-table :items="users" :fields="fields" striped small hover fixed sticky-header head-variant="dark"
          table-variant="light">
          <template v-slot:cell(actions)="data">
            <b-button @click="editUser(data.item)" size="sm" variant="primary" class="m-2" title="Editar">
              <i class="fa-regular fa-pen-to-square"></i>
            </b-button>
            <b-button @click="deleteUser(data.item.id)" size="sm" variant="danger" class="m-2" title="Excluir">
              <i class="fa-regular fa-trash-can"></i>
            </b-button>
          </template>
        </b-table>
        <b-pagination v-if="totalRows > perPage" v-model="currentPage" :total-rows="totalRows" :per-page="perPage"
          align="center" />
      </b-overlay>
    </b-card>
  </b-container>
</template>

<script lang="ts">
import { defineComponent, inject, ref, onMounted } from 'vue';
import axios from 'axios';

axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL;

export default defineComponent({
  data() {
    return {
      users: [],
      fields: [
        { key: 'name', label: 'Nome', sortable: false },
        { key: 'email', label: 'E-mail', sortable: false },
        {
          key: 'status', label: 'Status', sortable: false, class: 'text-center', formatter: (value: string) => {
            return value == 'A' ? `Ativo` : `Inativo`;
          }
        },
        { key: 'actions', label: 'Ações', sortable: false, class: 'text-center' }
      ],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      perPageOptions: [
        { value: 5, text: '5' },
        { value: 10, text: '10' },
        { value: 25, text: '25' },
        { value: 50, text: '50' },
        { value: 100, text: '100' }
      ],
      loading: false
    };
  },
  methods: {
    async loadUsers() {
      console.log(this.perPage);
      try {
        this.loading = true;
        const response = await axios.get('/api/users', {
          params: {
            page: this.currentPage,
            per_page: this.perPage
          }
        });
        this.loading = false;

        if (response.status === 200) {
          this.users = response.data.data;
          this.totalRows = response.data.meta.total;
        } else {
          console.error("Erro ao carregar usuários:", response.statusText);
          alert('Erro ao carregar usuários. Tente novamente mais tarde.');
        }
      } catch (error) {
        console.error("Erro ao buscar usuários:", error);
        alert('Erro ao buscar usuários. Verifique a conexão com o servidor.');
      }
    },
    createUser() {
      this.$router.push({ name: 'UserCreate' });
    },
    editUser(user: any) {
      this.$router.push({ name: 'UserEdit', params: { id: user.id } });
    },
    async deleteUser(userId: number) {
      if (confirm("Tem certeza que deseja excluir este usuário?")) {
        try {
          this.loading = true;
          const response = await axios.delete(`/api/users/${userId}`);
          this.loading = false;
          if (response.status === 200) {
            this.loadUsers();
            alert("Usuário excluído com sucesso.");
          } else {
            console.error("Erro ao excluir usuário:", response.statusText);
            alert('Erro ao excluir usuário. Tente novamente mais tarde.');
          }
        } catch (error) {
          console.error("Erro ao excluir usuário:", error);
          alert('Erro ao excluir usuário. Verifique a conexão com o servidor.');
        }
      }
    }
  },
  mounted() {
    this.loadUsers();
  },
  watch: {
    currentPage(page) {
      this.loadUsers();
    },
    perPage(perPage) {
      this.loadUsers();
    }
  }
});
</script>

<style lang="scss" src="../../styles/pages/user/user-grid.scss"></style>